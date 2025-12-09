@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan sistem inventory Anda')

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Items -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Barang</p>
                    <h3 class="text-3xl font-bold">{{ $totalItems ?? 0 }}</h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-box mr-1"></i>Jenis produk
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-boxes text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Safe Stock -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Stok Aman</p>
                    <h3 class="text-3xl font-bold">{{ $safeStockItems ?? 0 }}</h3>
                    <p class="text-green-100 text-xs mt-2">
                        <i class="fas fa-check-circle mr-1"></i>Stok mencukupi
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Low Stock -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Stok Menipis</p>
                    <h3 class="text-3xl font-bold">{{ $lowStockItemsCount ?? 0 }}</h3>
                    <p class="text-yellow-100 text-xs mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu restock
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Out of Stock -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Stok Habis</p>
                    <h3 class="text-3xl font-bold">{{ $outOfStockItemsCount ?? 0 }}</h3>
                    <p class="text-red-100 text-xs mt-2">
                        <i class="fas fa-times-circle mr-1"></i>Segera restock
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Stock Status Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Status Stok Barang</h3>
                    <p class="text-sm text-gray-500">Distribusi kondisi stok</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="stockStatusChart"></canvas>
            </div>
        </div>
        
        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Distribusi Kategori</h3>
                    <p class="text-sm text-gray-500">Jumlah barang per kategori</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('items.create') }}" class="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-plus text-blue-600 mr-2"></i>
                        <span class="text-blue-900 font-medium">Tambah Barang Baru</span>
                    </a>
                    <a href="{{ route('items.index') }}" class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-list text-green-600 mr-2"></i>
                        <span class="text-green-900 font-medium">Kelola Barang</span>
                    </a>
                    <button class="block w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-box-open text-purple-600 mr-2"></i>
                        <span class="text-purple-900 font-medium">Restock Barang</span>
                        <span class="ml-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Soon</span>
                    </button>
                @else
                    <a href="#" class="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                        <span class="text-blue-900 font-medium">Transaksi Baru</span>
                        <span class="ml-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Soon</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-box text-green-600 mr-2"></i>
                        <span class="text-green-900 font-medium">Lihat Produk</span>
                    </a>
                    <button class="block w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                        <i class="fas fa-receipt text-purple-600 mr-2"></i>
                        <span class="text-purple-900 font-medium">Riwayat Transaksi</span>
                        <span class="ml-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Soon</span>
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Stock Alerts -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bell text-red-500 mr-2"></i>
                Peringatan Stok
            </h3>
            
            @if(isset($lowStockItems) && $lowStockItems->count() > 0)
                <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <h4 class="font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Stok Menipis ({{ $lowStockItems->count() }} item)
                    </h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($lowStockItems->take(5) as $item)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 font-medium">{{ $item->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-yellow-700">Stok: <strong>{{ $item->quantity }}</strong></span>
                                <span class="text-gray-500">(Min: {{ $item->min_stock }})</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if(isset($outOfStockItems) && $outOfStockItems->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <h4 class="font-semibold text-red-800 mb-2">
                        <i class="fas fa-times-circle mr-1"></i>
                        Stok Habis ({{ $outOfStockItems->count() }} item)
                    </h4>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($outOfStockItems->take(5) as $item)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 font-medium">{{ $item->name }}</span>
                            <span class="text-red-700 font-bold">HABIS!</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if((!isset($lowStockItems) || $lowStockItems->count() == 0) && (!isset($outOfStockItems) || $outOfStockItems->count() == 0))
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                    <p class="text-gray-600 font-medium">Semua stok dalam kondisi aman!</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Recent Items Table -->
    @if(isset($recentItems) && $recentItems->count() > 0)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Barang Terbaru</h3>
                <p class="text-sm text-gray-500">5 barang terakhir ditambahkan</p>
            </div>
            <a href="{{ route(auth()->user()->isAdmin() ? 'items.index' : 'products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentItems as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($item->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $item->name }}</span>
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
                            @if($item->quantity == 0)
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                    0
                                </span>
                            @elseif($item->isLowStock())
                                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $item->quantity }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    {{ $item->quantity }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Stock Status Pie Chart
const stockStatusCtx = document.getElementById('stockStatusChart').getContext('2d');
new Chart(stockStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Stok Aman', 'Stok Menipis', 'Stok Habis'],
        datasets: [{
            data: [
                {{ $safeStockItems ?? 0 }}, 
                {{ $lowStockItemsCount ?? 0 }}, 
                {{ $outOfStockItemsCount ?? 0 }}
            ],
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',
                'rgba(234, 179, 8, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderColor: [
                'rgb(34, 197, 94)',
                'rgb(234, 179, 8)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        family: 'Inter'
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' items';
                    }
                }
            }
        }
    }
});

// Category Bar Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = @json($categoryData ?? []);

new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: categoryData.map(item => item.category || 'Tanpa Kategori'),
        datasets: [{
            label: 'Jumlah Barang',
            data: categoryData.map(item => item.count),
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderColor: 'rgb(99, 102, 241)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Jumlah: ' + context.parsed.y + ' items';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        family: 'Inter'
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        family: 'Inter'
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush
@endsection