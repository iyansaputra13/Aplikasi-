@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('subtitle', 'Analisis stok dan inventori')

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Total Items -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Barang</p>
                    <h3 class="text-3xl font-bold">{{ $totalItems }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Jenis produk</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-boxes text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Value -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Nilai Stok</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                    <p class="text-green-100 text-xs mt-1">Total inventori</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Low Stock -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Stok Menipis</p>
                    <h3 class="text-3xl font-bold">{{ $lowStockCount }}</h3>
                    <p class="text-yellow-100 text-xs mt-1">Perlu restock</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Out of Stock -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Stok Habis</p>
                    <h3 class="text-3xl font-bold">{{ $outOfStockCount }}</h3>
                    <p class="text-red-100 text-xs mt-1">Harus restock</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Breakdown -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Stok per Kategori</h3>
                <p class="text-sm text-gray-500">Distribusi stok berdasarkan kategori</p>
            </div>
            <div class="bg-purple-100 rounded-lg p-3">
                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
            </div>
        </div>
        <div style="height: 350px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    
    <!-- Stock Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Detail Stok Barang</h3>
                <p class="text-sm text-gray-500">Urutkan dari stok terkecil</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Min Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
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
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->min_stock }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Category Chart
const categoryData = @json($categoryStats);
const categoryCtx = document.getElementById('categoryChart').getContext('2d');

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
        }, {
            label: 'Total Stok',
            data: categoryData.map(item => item.total_stock),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
@endsection