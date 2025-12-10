<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Sales Report Index
     */
    public function index(Request $request)
    {
        // Default date range (last 30 days)
        $dateFrom = $request->date_from ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');
        
        // Get transactions in date range
        $transactions = Transaction::with('items')
                                   ->whereDate('created_at', '>=', $dateFrom)
                                   ->whereDate('created_at', '<=', $dateTo)
                                   ->get();
        
        // Calculate statistics
        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItemsSold = $transactions->sum(function($transaction) {
            return $transaction->items->sum('quantity');
        });
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
        
        // Daily sales data for chart
        $dailySales = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
                                 ->whereDate('created_at', '>=', $dateFrom)
                                 ->whereDate('created_at', '<=', $dateTo)
                                 ->groupBy('date')
                                 ->orderBy('date')
                                 ->get();
        
        // Top selling products
        $topProducts = TransactionItem::select('item_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
                                      ->whereHas('transaction', function($query) use ($dateFrom, $dateTo) {
                                          $query->whereDate('created_at', '>=', $dateFrom)
                                                ->whereDate('created_at', '<=', $dateTo);
                                      })
                                      ->groupBy('item_name', 'item_id')
                                      ->orderByDesc('total_sold')
                                      ->take(10)
                                      ->get();
        
        // Payment method breakdown
        $paymentMethods = Transaction::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
                                     ->whereDate('created_at', '>=', $dateFrom)
                                     ->whereDate('created_at', '<=', $dateTo)
                                     ->groupBy('payment_method')
                                     ->get();
        
        return view('reports.index', compact(
            'dateFrom',
            'dateTo',
            'totalSales',
            'totalTransactions',
            'totalItemsSold',
            'averageTransaction',
            'dailySales',
            'topProducts',
            'paymentMethods'
        ));
    }
    
    /**
     * Stock Report
     */
    public function stock()
    {
        $items = Item::orderBy('quantity', 'asc')->get();
        
        // Statistics
        $totalItems = $items->count();
        $totalValue = $items->sum(function($item) {
            return $item->quantity * $item->price;
        });
        $lowStockCount = $items->filter(function($item) {
            return $item->isLowStock();
        })->count();
        $outOfStockCount = $items->where('quantity', 0)->count();
        
        // Category breakdown
        $categoryStats = Item::select('category', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_stock'))
                             ->groupBy('category')
                             ->get();
        
        return view('reports.stock', compact(
            'items',
            'totalItems',
            'totalValue',
            'lowStockCount',
            'outOfStockCount',
            'categoryStats'
        ));
    }
    
    /**
     * Profit Analysis (Restock vs Sales)
     */
    public function profit(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');
        
        // Total revenue from sales
        $totalRevenue = Transaction::whereDate('created_at', '>=', $dateFrom)
                                   ->whereDate('created_at', '<=', $dateTo)
                                   ->sum('total_amount');
        
        // Total cost from restock
        $totalCost = DB::table('restock_histories')
                       ->whereDate('created_at', '>=', $dateFrom)
                       ->whereDate('created_at', '<=', $dateTo)
                       ->sum('total_cost');
        
        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;
        
        // Monthly trend
        $monthlyData = Transaction::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue')
                                  ->whereDate('created_at', '>=', Carbon::now()->subMonths(12))
                                  ->groupBy('month')
                                  ->orderBy('month')
                                  ->get();
        
        return view('reports.profit', compact(
            'dateFrom',
            'dateTo',
            'totalRevenue',
            'totalCost',
            'grossProfit',
            'profitMargin',
            'monthlyData'
        ));
    }
    
    /**
     * Export Sales Report to CSV
     */
    public function exportSales(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');
        
        $transactions = Transaction::with('user', 'items')
                                   ->whereDate('created_at', '>=', $dateFrom)
                                   ->whereDate('created_at', '<=', $dateTo)
                                   ->get();
        
        $filename = 'sales_report_' . $dateFrom . '_to_' . $dateTo . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        fputcsv($output, [
            'Tanggal',
            'Kode Transaksi',
            'Kasir',
            'Pelanggan',
            'Total Item',
            'Total Harga',
            'Metode Pembayaran'
        ]);
        
        // CSV Data
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction->created_at->format('d/m/Y H:i'),
                $transaction->transaction_code,
                $transaction->user->name,
                $transaction->customer_name ?: 'Umum',
                $transaction->items->sum('quantity'),
                $transaction->total_amount,
                $transaction->getPaymentMethodDisplayName()
            ]);
        }
        
        fclose($output);
        exit;
    }
}