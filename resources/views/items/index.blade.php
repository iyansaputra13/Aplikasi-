@extends('layouts.app')

@section('title', 'Kelola Barang')
@section('subtitle', 'Manajemen inventori barang')

@section('content')
<div class="space-y-6">
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Barang</h2>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $items->count() }} barang</p>
        </div>
        <a 
            href="{{ route('items.create') }}" 
            class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-opacity shadow-lg"
        >
            <i class="fas fa-plus mr-2"></i>Tambah Barang Baru
        </a>
    </div>
    
    <!-- Stock Alerts -->
    @if($lowStockItems->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800 mb-2">Stok Menipis ({{ $lowStockItems->count() }} item)</h3>
                <div class="space-y-1">
                    @foreach($lowStockItems->take(3) as $item)
                    <p class="text-sm text-yellow-700">
                        <strong>{{ $item->name }}</strong> - Stok: {{ $item->quantity }} (Min: {{ $item->min_stock }})
                    </p>
                    @endforeach
                    @if($lowStockItems->count() > 3)
                    <p class="text-sm text-yellow-600 font-medium mt-2">
                        +{{ $lowStockItems->count() - 3 }} barang lainnya
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($outOfStockItems->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-times-circle text-red-600 text-xl mt-1 mr-3"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-red-800 mb-2">Stok Habis ({{ $outOfStockItems->count() }} item)</h3>
                <div class="space-y-1">
                    @foreach($outOfStockItems->take(3) as $item)
                    <p class="text-sm text-red-700">
                        <strong>{{ $item->name }}</strong> - HABIS!
                    </p>
                    @endforeach
                    @if($outOfStockItems->count() > 3)
                    <p class="text-sm text-red-600 font-medium mt-2">
                        +{{ $outOfStockItems->count() - 3 }} barang lainnya
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Search & Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('items.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}"
                    placeholder="Cari nama barang, kategori, atau barcode..." 
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if($search)
            <a href="{{ route('items.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                <i class="fas fa-times mr-2"></i>Reset
            </a>
            @endif
        </form>
    </div>
    
    <!-- Items Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($item->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                    @if($item->barcode)
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-barcode mr-1"></i>{{ $item->barcode }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->category)
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $item->category }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-gray-900">{{ $item->quantity }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->quantity == 0)
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Habis
                                </span>
                            @elseif($item->isLowStock())
                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Menipis
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a 
                                    href="{{ route('items.show', $item->id) }}" 
                                    class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
                                    title="Lihat Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a 
                                    href="{{ route('items.edit', $item->id) }}" 
                                    class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus barang ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                @if($search)
                    Tidak Ditemukan
                @else
                    Belum Ada Barang
                @endif
            </h3>
            <p class="text-gray-600 mb-6">
                @if($search)
                    Tidak ada barang yang cocok dengan pencarian "{{ $search }}"
                @else
                    Mulai tambahkan barang pertama Anda
                @endif
            </p>
            <a href="{{ route('items.create') }}" class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90">
                <i class="fas fa-plus mr-2"></i>Tambah Barang Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection