@extends('layouts.app')

@section('title', 'Riwayat Restock')
@section('subtitle', 'History penambahan stok barang')

@section('content')
<div class="space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('restock.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Restock
    </a>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filter Riwayat
        </h3>
        
        <form method="GET" action="{{ route('restock.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Item Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                <select 
                    name="item_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Barang</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Supplier -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <input 
                    type="text" 
                    name="supplier" 
                    value="{{ request('supplier') }}"
                    placeholder="Nama supplier"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Buttons -->
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('restock.history') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Restock</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_restocks'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Qty Ditambah</p>
            <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_quantity_added'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Biaya</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($statistics['total_cost'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Unique Items</p>
            <p class="text-2xl font-bold text-purple-600">{{ $statistics['unique_items'] }}</p>
        </div>
    </div>
    
    <!-- History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Daftar Riwayat Restock</h3>
        </div>
        
        @if($restocks->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok Sebelum</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ditambah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok Sesudah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($restocks as $restock)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $restock->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $restock->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($restock->item->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $restock->item->name }}</p>
                                    @if($restock->supplier)
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-truck mr-1"></i>{{ $restock->supplier }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $restock->quantity_before }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-800 font-bold rounded-full">
                                +{{ $restock->quantity_added }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $restock->quantity_after }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            {{ $restock->getFormattedTotalCost() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $restock->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $restocks->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Riwayat</h3>
            <p class="text-gray-600 mb-4">
                @if(request()->hasAny(['item_id', 'date_from', 'date_to', 'supplier']))
                    Tidak ditemukan riwayat dengan filter yang dipilih
                @else
                    Belum ada riwayat restock
                @endif
            </p>
            <a href="{{ route('restock.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Restock Sekarang
            </a>
        </div>
        @endif
    </div>
</div>
@endsection