<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Inventory System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Sidebar hover effect */
        .sidebar-link {
            position: relative;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #3b82f6, #2563eb);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar-link.active::before,
        .sidebar-link:hover::before {
            transform: scaleY(1);
        }
        
        /* Gradient background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white shadow-xl border-r border-gray-200">
            
            <!-- Logo -->
            <div class="mb-8 px-3">
                <div class="flex items-center justify-center gradient-bg rounded-xl p-4">
                    <i class="fas fa-warehouse text-white text-3xl mr-3"></i>
                    <div>
                        <h1 class="text-xl font-bold text-white">Inventory</h1>
                        <p class="text-xs text-blue-100">Management System</p>
                    </div>
                </div>
            </div>
            
            <!-- User Info -->
            @auth
            <div class="mb-6 px-3">
                <div class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-600">{{ auth()->user()->getRoleDisplayName() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium text-white rounded-full {{ auth()->user()->getRoleBadgeColor() }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            @endauth
            
            <!-- Navigation Menu -->
            @auth
            <nav class="space-y-2">
                
                @if(auth()->user()->isAdmin())
                    <!-- ADMIN MENU -->
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin Menu</p>
                    
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('dashboard') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('items.index') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('items.*') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-boxes w-5"></i>
                        <span class="ml-3 font-medium">Kelola Barang</span>
                    </a>
                    
                    <a href="{{ route('restock.index') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('restock.*') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-box-open w-5"></i>
                        <span class="ml-3 font-medium">Restock</span>
                    </a>
                    
                    <a href="{{ route('reports.sales') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('reports.sales') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-3 font-medium">Laporan Penjualan</span>
                    </a>
                    
                    <a href="{{ route('reports.stock') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('reports.stock') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-boxes w-5"></i>
                        <span class="ml-3 font-medium">Laporan Stok</span>
                    </a>
                    
                    <a href="{{ route('reports.profit') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('reports.profit') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-3 font-medium">Analisis Profit</span>
                    </a>
                    
                    <a href="#" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-3 font-medium">Kelola User</span>
                        <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Soon</span>
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('users.*') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-3 font-medium">Kelola User</span>
                    </a>
                    
                @else
                    <!-- CASHIER MENU -->
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Cashier Menu</p>
                    
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('dashboard') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-3 font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('transactions.index') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('transactions.*') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3 font-medium">Transaksi Penjualan</span>
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('products.*') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-3 font-medium">Lihat Produk</span>
                    </a>
                    
                    <a href="{{ route('transactions.history') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('transactions.history') || request()->routeIs('transactions.show') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-receipt w-5"></i>
                        <span class="ml-3 font-medium">Riwayat Transaksi</span>
                    </a>
                @endif
                
                <!-- Divider -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Account</p>
                    
                    <a href="{{ route('profile') }}" class="sidebar-link flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-blue-50 {{ request()->routeIs('profile') ? 'active bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-user-circle w-5"></i>
                        <span class="ml-3 font-medium">Profile</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link w-full flex items-center px-3 py-3 text-red-600 rounded-lg hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3 font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
            @endauth
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="sm:ml-64 min-h-screen bg-gray-50">
        
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    
                    <!-- Mobile Menu Button -->
                    <button id="sidebarToggle" class="sm:hidden inline-flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800">@yield('title')</h2>
                        <p class="text-sm text-gray-500 mt-1">@yield('subtitle', 'Manage your inventory efficiently')</p>
                    </div>
                    
                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        
                        <!-- Notification Bell -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Current Time -->
                        <div class="hidden md:block text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            <span id="currentTime"></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-slideDown">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-slideDown">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif
            
            <!-- Main Content Area -->
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} Inventory Management System. All rights reserved.</p>
                    <p class="mt-1">Made with <i class="fas fa-heart text-red-500"></i> by Your Company</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script>
        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
        
        // Update Current Time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        updateTime();
        setInterval(updateTime, 1000);
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-slideDown');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>