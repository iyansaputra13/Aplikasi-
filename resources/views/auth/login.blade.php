@extends('layouts.guest')

@section('title', 'Login')
@section('subtitle', 'Silakan login untuk melanjutkan')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h2>
    <p class="text-gray-600 mb-6">Masukkan kredensial Anda untuk login</p>
    
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-1"></i>Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                placeholder="nama@email.com"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-1"></i>Password
            </label>
            <div class="relative">
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                    placeholder="••••••••"
                >
                <button 
                    type="button" 
                    onclick="togglePassword()"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                >
                    <i id="toggleIcon" class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    name="remember" 
                    {{ old('remember') ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lupa Password?
                </a>
            @endif
        </div>
        
        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full gradient-bg text-white font-semibold py-3 px-4 rounded-lg hover:opacity-90 transition-opacity shadow-lg"
        >
            <i class="fas fa-sign-in-alt mr-2"></i>Login
        </button>
    </form>
    
    <!-- Register Link -->
    @if (Route::has('register'))
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                Daftar Sekarang
            </a>
        </p>
    </div>
    @endif
    
    <!-- Demo Credentials -->
    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <p class="text-xs font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-1"></i>Demo Credentials:
        </p>
        <div class="space-y-1 text-xs text-blue-800">
            <p><strong>Admin:</strong> admin@inventory.com / admin123</p>
            <p><strong>Cashier:</strong> cashier1@inventory.com / cashier123</p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection