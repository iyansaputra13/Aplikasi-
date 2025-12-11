<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $items = Item::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        })->latest()->paginate(20); // ← GANTI dari get() jadi paginate(20)

        $lowStockItems = Item::where('quantity', '<=', DB::raw('min_stock'))
            ->where('quantity', '>', 0)
            ->get();
        $outOfStockItems = Item::where('quantity', 0)->get();

        // Check if this is a products route (for cashier view)
        if ($request->routeIs('products.index')) {
            return view('products.index', compact('items', 'lowStockItems', 'outOfStockItems', 'search'));
        }

        // Default admin view
        return view('items.index', compact('items', 'lowStockItems', 'outOfStockItems', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255|unique:items,barcode'
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255|unique:items,barcode,' . $item->id
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
