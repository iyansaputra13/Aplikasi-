@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('subtitle', 'Analisis penjualan dan revenue')

@section('content')
<div class="space-y-6">
    
    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar text-blue-600 mr-2"></i>
            Periode Laporan
        </h3>
        
        <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ $dateFrom }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ $dateTo }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    <i class="fas fa-sync mr-2"></i>Update
                </button>
            </div>
            
            <div class="flex items-end">
                <a href="{{ route('reports.sales.export', request()->query()) }}" class="w-full px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 text-center">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </form>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Total Sales -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Penjualan</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Transactions -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold">{{ $totalTransactions }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Items Sold -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Item Terjual</p>
                    <h3 class="text-3xl font-bold">{{ $totalItemsSold }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-box text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Average Transaction -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Rata-rata</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daily Sales Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Grafik Penjualan Harian</h3>
                <p class="text-sm text-gray-500">Trend penjualan per hari</p>
            </div>
            <div class="bg-blue-100 rounded-lg p-3">
                <i class="fas fa-chart-area text-blue-600 text-xl"></i>
            </div>
        </div>
        <div style="height: 350px;">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>
    
    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Top 10 Produk Terlaris</h3>
                    <p class="text-sm text-gray-500">Berdasarkan quantity terjual</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
            
            @if($topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center flex-1">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold mr-3">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $product->item_name }}</p>
                            <p class="text-xs text-gray-500">Revenue: Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-purple-600">{{ $product->total_sold }}</p>
                        <p class="text-xs text-gray-500">unit</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-box-open text-4xl mb-2"></i>
                <p class="text-sm">Belum ada data penjualan</p>
            </div>
            @endif
        </div>
        
        <!-- Payment Methods -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Metode Pembayaran</h3>
                    <p class="text-sm text-gray-500">Distribusi metode pembayaran</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <i class="fas fa-credit-card text-green-600 text-xl"></i>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Daily Sales Chart
const dailySalesData = @json($dailySales);
const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');

new Chart(dailySalesCtx, {
    type: 'line',
    data: {
        labels: dailySalesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        }),
        datasets: [{
            label: 'Penjualan (Rp)',
            data: dailySalesData.map(item => item.total),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Payment Methods Chart
const paymentData = @json($paymentMethods);
const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');

new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: paymentData.map(item => {
            const labels = {
                'cash': 'Tunai',
                'debit': 'Debit Card',
                'credit': 'Credit Card',
                'qris': 'QRIS'
            };
            return labels[item.payment_method] || item.payment_method;
        }),
        datasets: [{
            data: paymentData.map(item => item.total),
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(249, 115, 22, 0.8)'
            ],
            borderColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(249, 115, 22)'
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
                        size: 12
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection