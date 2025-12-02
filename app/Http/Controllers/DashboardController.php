<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // TAMBAH INI

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $lowStockItems = Item::where('quantity', '<=', DB::raw('min_stock'))->where('quantity', '>', 0)->get();
        $outOfStockItems = Item::where('quantity', 0)->get();
        $recentItems = Item::latest()->take(5)->get();

        $safeStockItems = Item::where('quantity', '>', DB::raw('min_stock'))->count();
        $lowStockItemsCount = $lowStockItems->count();
        $outOfStockItemsCount = $outOfStockItems->count();

        return view('dashboard', compact(
            'totalItems',
            'lowStockItems', 
            'outOfStockItems',
            'recentItems',
            'safeStockItems',
            'lowStockItemsCount',
            'outOfStockItemsCount'
        ));
    }
}