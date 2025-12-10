@extends('layouts.app')

@section('title', 'Tambah User')
@section('subtitle', 'Buat akun user baru')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Back Button -->
    <a href="{{ route('users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-6">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar User
    </a>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header -->
        <div class="gradient-bg text-white px-6 py-4">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah User Baru
            </h2>
        </div>
        
        <!-- Form -->
        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-blue-600 mr-1"></i>Nama Lengkap *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope text-blue-600 mr-1"></i>Email *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="user@example.com"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag text-purple-600 mr-1"></i>Role *
                </label>
                <select 
                    id="role" 
                    name="role" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    <strong>Admin:</strong> Akses penuh ke semua fitur<br>
                    <strong>Kasir:</strong> Hanya transaksi penjualan & lihat produk
                </p>
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock text-green-600 mr-1"></i>Password *
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock text-green-600 mr-1"></i>Konfirmasi Password *
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Ulangi password"
                >
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a 
                    href="{{ route('users.index') }}" 
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors"
                >
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                
                <button 
                    type="submit" 
                    class="px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-opacity"
                >
                    <i class="fas fa-save mr-2"></i>Simpan User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection