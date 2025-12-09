<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display transaction form (POS)
     */
    public function index()
    {
        $items = Item::where('quantity', '>', 0)->get();
        return view('transactions.index', compact('items'));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,debit,credit,qris',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $totalAmount = 0;
            $transactionItems = [];

            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                
                // Check stock
                if ($item->quantity < $itemData['quantity']) {
                    return back()->with('error', "Stok {$item->name} tidak mencukupi!");
                }

                $subtotal = $item->price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $transactionItems[] = [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'item_price' => $item->price,
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $subtotal,
                ];

                // Reduce stock
                $item->decrement('quantity', $itemData['quantity']);
            }

            // Calculate change
            $changeAmount = $request->paid_amount - $totalAmount;

            if ($changeAmount < 0) {
                DB::rollBack();
                return back()->with('error', 'Jumlah pembayaran kurang!');
            }

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create transaction items
            foreach ($transactionItems as $itemData) {
                $transaction->items()->create($itemData);
            }

            DB::commit();

            return redirect()->route('transactions.receipt', $transaction->id)
                           ->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show transaction receipt
     */
    public function receipt($id)
    {
        $transaction = Transaction::with('items', 'user')->findOrFail($id);
        return view('transactions.receipt', compact('transaction'));
    }

    /**
     * Show transaction history
     */
    public function history(Request $request)
    {
        $query = Transaction::with('user', 'items');
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Get transactions
        $transactions = $query->latest()->paginate(20)->withQueryString();
        
        // Calculate statistics
        $statisticsQuery = clone $query;
        $allTransactions = $statisticsQuery->get();
        
        $statistics = [
            'total_transactions' => $allTransactions->count(),
            'total_sales' => $allTransactions->sum('total_amount'),
            'total_items_sold' => $allTransactions->sum(function($transaction) {
                return $transaction->items->sum('quantity');
            }),
            'average_transaction' => $allTransactions->count() > 0 
                ? $allTransactions->sum('total_amount') / $allTransactions->count() 
                : 0,
        ];
        
        return view('transactions.history', compact('transactions', 'statistics'));
    }

    /**
     * Show transaction detail
     */
    public function show($id)
    {
        $transaction = Transaction::with('items', 'user')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Export transactions to Excel
     */
    public function export(Request $request)
    {
        $query = Transaction::with('user', 'items');
        
        // Apply same filters as history
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->latest()->get();
        
        // Generate CSV
        $filename = 'transactions_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        fputcsv($output, [
            'Kode Transaksi',
            'Tanggal',
            'Waktu',
            'Kasir',
            'Pelanggan',
            'Total Item',
            'Total Harga',
            'Dibayar',
            'Kembalian',
            'Metode Pembayaran'
        ]);
        
        // CSV Data
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction->transaction_code,
                $transaction->created_at->format('d/m/Y'),
                $transaction->created_at->format('H:i:s'),
                $transaction->user->name,
                $transaction->customer_name ?: 'Umum',
                $transaction->items->sum('quantity'),
                $transaction->total_amount,
                $transaction->paid_amount,
                $transaction->change_amount,
                $transaction->getPaymentMethodDisplayName()
            ]);
        }
        
        fclose($output);
        exit;
    }
}