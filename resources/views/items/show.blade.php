@extends('layouts.app')

@section('title', 'Detail Barang')
@section('subtitle', 'Informasi lengkap barang')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('items.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Barang
    </a>
    
    <!-- Main Info Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header with Gradient -->
        <div class="gradient-bg text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">{{ $item->name }}</h2>
                    @if($item->category)
                    <span class="inline-block px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        {{ $item->category }}
                    </span>
                    @endif
                </div>
                <div class="text-right">
                    @if($item->quantity == 0)
                        <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>HABIS
                        </span>
                    @elseif($item->isLowStock())
                        <span class="px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-exclamation-triangle mr-1"></i>MENIPIS
                        </span>
                    @else
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>AMAN
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Image Placeholder -->
        <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
            <i class="fas fa-box text-white text-8xl opacity-30"></i>
        </div>
        
        <!-- Info Grid -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Left Column -->
            <div class="space-y-4">
                
                <!-- Price -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <p class="text-sm text-green-700 font-medium mb-1">Harga Jual</p>
                    <p class="text-3xl font-bold text-green-800">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </p>
                </div>
                
                <!-- Stock -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-sm text-blue-700 font-medium mb-1">Stok Tersedia</p>
                    <p class="text-3xl font-bold text-blue-800">
                        {{ $item->quantity }}
                        <span class="text-lg font-normal text-blue-600">unit</span>
                    </p>
                </div>
                
                <!-- Min Stock -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <p class="text-sm text-yellow-700 font-medium mb-1">Stok Minimum</p>
                    <p class="text-2xl font-bold text-yellow-800">
                        {{ $item->min_stock }}
                        <span class="text-sm font-normal text-yellow-600">unit</span>
                    </p>
                    <p class="text-xs text-yellow-600 mt-1">Notifikasi jika stok ≤ nilai ini</p>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                
                <!-- Description -->
                @if($item->description)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-align-left text-gray-600 mr-2"></i>
                        Deskripsi
                    </h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $item->description }}</p>
                </div>
                @endif
                
                <!-- Barcode -->
                @if($item->barcode)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-barcode text-gray-600 mr-2"></i>
                        Barcode
                    </h3>
                    <p class="text-2xl font-mono font-bold text-gray-800">{{ $item->barcode }}</p>
                </div>
                @endif
                
                <!-- Meta Info -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                        Informasi Lainnya
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ditambahkan:</span>
                            <span class="font-semibold text-gray-900">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terakhir Diupdate:</span>
                            <span class="font-semibold text-gray-900">{{ $item->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID Barang:</span>
                            <span class="font-mono font-semibold text-gray-900">#{{ $item->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 flex gap-3">
            <a 
                href="{{ route('items.edit', $item->id) }}" 
                class="flex-1 bg-blue-600 text-white text-center font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors"
            >
                <i class="fas fa-edit mr-2"></i>Edit Barang
            </a>
            <a 
                href="{{ route('items.index') }}" 
                class="flex-1 bg-gray-200 text-gray-700 text-center font-semibold py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors"
            >
                <i class="fas fa-list mr-2"></i>Lihat Semua
            </a>
            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');" class="flex-1">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full bg-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-red-700 transition-colors"
                >
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
    
    <!-- Stock History (Placeholder for future feature) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history text-purple-600 mr-2"></i>
            Riwayat Stok
        </h3>
        <div class="text-center py-8 text-gray-400">
            <i class="fas fa-clock text-4xl mb-3"></i>
            <p class="text-sm">Fitur riwayat stok akan segera hadir</p>
            <p class="text-xs mt-1">Anda akan bisa melihat history restock dan penjualan</p>
        </div>
    </div>
</div>
@endsection