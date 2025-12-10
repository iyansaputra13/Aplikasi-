@extends('layouts.app')

@section('title', 'Edit User')
@section('subtitle', 'Perbarui informasi user')

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
                <i class="fas fa-user-edit mr-2"></i>
                Edit User: {{ $user->name }}
            </h2>
        </div>
        
        <!-- Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-blue-600 mr-1"></i>Nama Lengkap *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
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
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
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
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Section -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <h4 class="font-semibold text-blue-900 mb-2">Ubah Password (Opsional)</h4>
                <p class="text-sm text-blue-800 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                
                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-green-600 mr-1"></i>Password Baru
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
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
                        <i class="fas fa-lock text-green-600 mr-1"></i>Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ulangi password baru"
                    >
                </div>
            </div>
            
            <!-- User Info -->
            <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded">
                <h4 class="font-semibold text-gray-900 mb-2">Informasi User</h4>
                <div class="text-sm text-gray-700 space-y-1">
                    <p>• Bergabung: {{ $user->created_at->format('d M Y, H:i') }}</p>
                    <p>• Terakhir update: {{ $user->updated_at->format('d M Y, H:i') }}</p>
                    <p>• User ID: #{{ $user->id }}</p>
                </div>
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
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection