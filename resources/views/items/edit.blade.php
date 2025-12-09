@extends('layouts.app')

@section('title', 'Edit Barang')
@section('subtitle', 'Perbarui informasi barang')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Back Button -->
    <a href="{{ route('items.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-6">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Barang
    </a>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header -->
        <div class="gradient-bg text-white px-6 py-4">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Barang: {{ $item->name }}
            </h2>
        </div>
        
        <!-- Form -->
        <form action="{{ route('items.update', $item->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                
                <!-- Basic Info Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag text-blue-600 mr-1"></i>Nama Barang *
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $item->name) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama barang"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-folder text-purple-600 mr-1"></i>Kategori
                            </label>
                            <input 
                                type="text" 
                                id="category" 
                                name="category" 
                                value="{{ old('category', $item->category) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: Elektronik, Makanan"
                            >
                        </div>
                        
                        <!-- Barcode -->
                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-barcode text-gray-600 mr-1"></i>Barcode
                            </label>
                            <input 
                                type="text" 
                                id="barcode" 
                                name="barcode" 
                                value="{{ old('barcode', $item->barcode) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('barcode') border-red-500 @enderror"
                                placeholder="Scan atau input manual"
                            >
                            @error('barcode')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-green-600 mr-1"></i>Deskripsi
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Deskripsi detail tentang barang..."
                    >{{ old('description', $item->description) }}</textarea>
                </div>
                
                <!-- Pricing & Stock Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                        Harga & Stok
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>Harga (Rp) *
                            </label>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price', $item->price) }}" 
                                min="0" 
                                step="100"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                placeholder="0"
                            >
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-boxes text-blue-600 mr-1"></i>Stok Saat Ini *
                            </label>
                            <input 
                                type="number" 
                                id="quantity" 
                                name="quantity" 
                                value="{{ old('quantity', $item->quantity) }}" 
                                min="0"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                                placeholder="0"
                            >
                            @error('quantity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Min Stock -->
                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>Stok Minimum *
                            </label>
                            <input 
                                type="number" 
                                id="min_stock" 
                                name="min_stock" 
                                value="{{ old('min_stock', $item->min_stock) }}" 
                                min="1"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('min_stock') border-red-500 @enderror"
                                placeholder="5"
                            >
                            @error('min_stock')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Notifikasi jika stok ≤ nilai ini</p>
                        </div>
                    </div>
                </div>
                
                <!-- Current Status Info -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 text-xl mt-1 mr-3"></i>
                        <div class="flex-1">
                            <h4 class="font-semibold text-blue-900 mb-2">Status Saat Ini</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700">Stok: <strong>{{ $item->quantity }}</strong></p>
                                    <p class="text-blue-700">Min Stok: <strong>{{ $item->min_stock }}</strong></p>
                                </div>
                                <div>
                                    <p class="text-blue-700">Status: 
                                        @if($item->quantity == 0)
                                            <span class="font-bold text-red-600">HABIS</span>
                                        @elseif($item->isLowStock())
                                            <span class="font-bold text-yellow-600">MENIPIS</span>
                                        @else
                                            <span class="font-bold text-green-600">AMAN</span>
                                        @endif
                                    </p>
                                    <p class="text-blue-700">Ditambahkan: <strong>{{ $item->created_at->format('d M Y') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('items.index') }}" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors"
                    >
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    
                    <button 
                        type="submit" 
                        class="px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-opacity"
                    >
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Danger Zone (Delete) -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-red-800 mb-2 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Danger Zone
        </h3>
        <p class="text-sm text-red-700 mb-4">
            Menghapus barang akan menghapus semua data terkait. Tindakan ini tidak dapat dibatalkan.
        </p>
        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini? Tindakan ini tidak dapat dibatalkan!');">
            @csrf
            @method('DELETE')
            <button 
                type="submit" 
                class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors"
            >
                <i class="fas fa-trash mr-2"></i>Hapus Barang
            </button>
        </form>
    </div>
</div>
@endsection