@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('subtitle', 'Lihat informasi produk dan stok tersedia')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-3">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama barang, kategori, atau barcode..." 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search'))
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                <i class="fas fa-times mr-2"></i>Reset
            </a>
            @endif
        </form>
    </div>
    
    <!-- Stock Alerts -->
    @if(isset($lowStockItems) && $lowStockItems->count() > 0)
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800 mb-2">Stok Menipis ({{ $lowStockItems->count() }} item)</h3>
                <div class="space-y-1">
                    @foreach($lowStockItems->take(3) as $item)
                    <p class="text-sm text-yellow-700">
                        <span class="font-medium">{{ $item->name }}</span> - Stok: 
                        <span class="font-bold">{{ $item->quantity }}</span> 
                        (Min: {{ $item->min_stock }})
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
    
    @if(isset($outOfStockItems) && $outOfStockItems->count() > 0)
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-times-circle text-red-600 text-xl mt-1 mr-3"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-red-800 mb-2">Stok Habis ({{ $outOfStockItems->count() }} item)</h3>
                <div class="space-y-1">
                    @foreach($outOfStockItems->take(3) as $item)
                    <p class="text-sm text-red-700">
                        <span class="font-medium">{{ $item->name }}</span> - 
                        <span class="font-bold">STOK HABIS!</span>
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
    
    <!-- Products Grid -->
    @if(isset($items) && $items->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
        @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            
            <!-- Product Image Placeholder -->
            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <i class="fas fa-box text-white text-5xl opacity-50"></i>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                
                <!-- Category Badge -->
                @if($item->category)
                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mb-2">
                    {{ $item->category }}
                </span>
                @endif
                
                <!-- Product Name -->
                <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate" title="{{ $item->name }}">
                    {{ $item->name }}
                </h3>
                
                <!-- Description -->
                @if($item->description)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ $item->description }}
                </p>
                @endif
                
                <!-- Price -->
                <div class="mb-3">
                    <p class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </p>
                </div>
                
                <!-- Stock Info -->
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Stok Tersedia</p>
                        <p class="text-lg font-bold text-gray-800">{{ $item->quantity }}</p>
                    </div>
                    
                    <!-- Stock Status Badge -->
                    <div>
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
                    </div>
                </div>
                
                <!-- Barcode (if exists) -->
                @if($item->barcode)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-barcode mr-1"></i>{{ $item->barcode }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        {{ $items->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Produk</h3>
        <p class="text-gray-600">
            @if(request('search'))
                Tidak ditemukan produk dengan kata kunci "{{ request('search') }}"
            @else
                Belum ada produk yang terdaftar di sistem
            @endif
        </p>
    </div>
    @endif
</div>
@endsection