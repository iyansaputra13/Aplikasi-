<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RestockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    /**
     * Display restock form
     */
    public function index()
    {
        $items = Item::orderBy('name')->get();
        $recentRestocks = RestockHistory::with('item', 'user')
                                       ->latest()
                                       ->take(10)
                                       ->get();
        
        return view('restock.index', compact('items', 'recentRestocks'));
    }

    /**
     * Store restock
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity_added' => 'required|integer|min:1',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($request->item_id);
            
            // Calculate values
            $quantityBefore = $item->quantity;
            $quantityAdded = $request->quantity_added;
            $quantityAfter = $quantityBefore + $quantityAdded;
            
            $totalCost = $request->cost_per_unit 
                ? $request->cost_per_unit * $quantityAdded 
                : null;

            // Create restock history
            RestockHistory::create([
                'item_id' => $item->id,
                'user_id' => auth()->id(),
                'quantity_before' => $quantityBefore,
                'quantity_added' => $quantityAdded,
                'quantity_after' => $quantityAfter,
                'cost_per_unit' => $request->cost_per_unit,
                'total_cost' => $totalCost,
                'supplier' => $request->supplier,
                'notes' => $request->notes,
            ]);

            // Update item stock
            $item->increment('quantity', $quantityAdded);

            DB::commit();

            return redirect()->route('restock.index')
                           ->with('success', "Berhasil menambah {$quantityAdded} unit {$item->name}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show restock history
     */
    public function history(Request $request)
    {
        $query = RestockHistory::with('item', 'user');
        
        // Filter by item
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }
        
        $restocks = $query->latest()->paginate(20)->withQueryString();
        $items = Item::orderBy('name')->get();
        
        // Statistics
        $statisticsQuery = clone $query;
        $allRestocks = $statisticsQuery->get();
        
        $statistics = [
            'total_restocks' => $allRestocks->count(),
            'total_quantity_added' => $allRestocks->sum('quantity_added'),
            'total_cost' => $allRestocks->sum('total_cost'),
            'unique_items' => $allRestocks->unique('item_id')->count(),
        ];
        
        return view('restock.history', compact('restocks', 'items', 'statistics'));
    }

    /**
     * Show restock detail
     */
    public function show($id)
    {
        $restock = RestockHistory::with('item', 'user')->findOrFail($id);
        return view('restock.show', compact('restock'));
    }

    /**
     * Batch restock form
     */
    public function batch()
    {
        $items = Item::orderBy('name')->get();
        return view('restock.batch', compact('items'));
    }

    /**
     * Store batch restock
     */
    public function batchStore(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $restockedItems = [];

            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                
                $quantityBefore = $item->quantity;
                $quantityAdded = $itemData['quantity'];
                $quantityAfter = $quantityBefore + $quantityAdded;
                
                $costPerUnit = $itemData['cost_per_unit'] ?? null;
                $totalCost = $costPerUnit ? $costPerUnit * $quantityAdded : null;

                // Create restock history
                RestockHistory::create([
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'quantity_before' => $quantityBefore,
                    'quantity_added' => $quantityAdded,
                    'quantity_after' => $quantityAfter,
                    'cost_per_unit' => $costPerUnit,
                    'total_cost' => $totalCost,
                    'supplier' => $request->supplier,
                    'notes' => $request->notes,
                ]);

                // Update item stock
                $item->increment('quantity', $quantityAdded);
                
                $restockedItems[] = "{$item->name} (+{$quantityAdded})";
            }

            DB::commit();

            return redirect()->route('restock.index')
                           ->with('success', 'Berhasil restock ' . count($restockedItems) . ' barang: ' . implode(', ', $restockedItems));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}