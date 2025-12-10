@extends('layouts.app')

@section('title', 'Profile Saya')
@section('subtitle', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="gradient-bg px-6 py-8 text-center text-white">
            <div class="w-24 h-24 mx-auto rounded-full bg-white bg-opacity-20 flex items-center justify-center text-4xl font-bold mb-4">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
            <p class="text-blue-100 mt-1">{{ $user->email }}</p>
            <span class="inline-block mt-3 px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                {{ $user->getRoleDisplayName() }}
            </span>
        </div>
        
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Member Since</p>
                    <p class="text-xl font-bold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm mb-1">Account Type</p>
                    <p class="text-xl font-bold text-gray-900">{{ ucfirst($user->role) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm mb-1">Status</p>
                    <p class="text-xl font-bold text-green-600">Active</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Profile Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Edit Profile</h3>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-blue-600 mr-1"></i>Nama Lengkap
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
                    <i class="fas fa-envelope text-blue-600 mr-1"></i>Email
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
            
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Ubah Password (Opsional)</h4>
                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key text-yellow-600 mr-1"></i>Password Saat Ini
                    </label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                        placeholder="Masukkan password saat ini"
                    >
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
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
                        <i class="fas fa-lock text-green-600 mr-1"></i>Konfirmasi Password Baru
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
            
            <!-- Submit Button -->
            <div class="pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="w-full gradient-bg text-white font-semibold py-4 px-6 rounded-lg hover:opacity-90 transition-opacity shadow-lg"
                >
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Account Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 text-2xl mr-3 mt-1"></i>
            <div>
                <h4 class="font-semibold text-blue-900 mb-2">Informasi Akun</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Password harus minimal 8 karakter</li>
                    <li>• Email harus unique dan valid</li>
                    <li>• Perubahan akan langsung berlaku setelah disimpan</li>
                    <li>• Hubungi admin untuk mengubah role akun</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection