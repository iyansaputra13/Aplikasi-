<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total items
        $totalItems = Item::count();
        
        // Stock statistics
        $lowStockItems = Item::where('quantity', '<=', DB::raw('min_stock'))
                             ->where('quantity', '>', 0)
                             ->get();
        $outOfStockItems = Item::where('quantity', 0)->get();
        $safeStockItems = Item::where('quantity', '>', DB::raw('min_stock'))->count();
        
        // Count for each status
        $lowStockItemsCount = $lowStockItems->count();
        $outOfStockItemsCount = $outOfStockItems->count();
        
        // Recent items (last 5)
        $recentItems = Item::latest()->take(5)->get();
        
        // Category distribution data for chart
        $categoryData = Item::select('category', DB::raw('count(*) as count'))
                            ->groupBy('category')
                            ->orderByDesc('count')
                            ->get()
                            ->map(function($item) {
                                return [
                                    'category' => $item->category ?: 'Tanpa Kategori',
                                    'count' => $item->count
                                ];
                            });

        return view('dashboard', compact(
            'totalItems',
            'lowStockItems',
            'outOfStockItems',
            'recentItems',
            'safeStockItems',
            'lowStockItemsCount',
            'outOfStockItemsCount',
            'categoryData'
        ));
    }
}