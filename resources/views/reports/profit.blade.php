@extends('layouts.app')

@section('title', 'Analisis Profit')
@section('subtitle', 'Revenue vs Cost analysis')

@section('content')
<div class="space-y-6">
    
    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar text-blue-600 mr-2"></i>
            Periode Analisis
        </h3>
        
        <form method="GET" action="{{ route('reports.profit') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        </form>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Revenue</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-green-100 text-xs mt-1">Dari penjualan</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-arrow-up text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Cost -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Total Cost</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($totalCost, 0, ',', '.') }}</h3>
                    <p class="text-red-100 text-xs mt-1">Biaya restock</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-arrow-down text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Gross Profit -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Gross Profit</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Revenue - Cost</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Profit Margin -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Profit Margin</p>
                    <h3 class="text-3xl font-bold">{{ number_format($profitMargin, 1) }}%</h3>
                    <p class="text-purple-100 text-xs mt-1">Persentase profit</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-percent text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profit Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Visual Breakdown -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Revenue vs Cost</h3>
                    <p class="text-sm text-gray-500">Perbandingan pendapatan dan biaya</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <i class="fas fa-balance-scale text-green-600 text-xl"></i>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="profitChart"></canvas>
            </div>
        </div>
        
        <!-- Monthly Trend -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Trend Revenue (12 Bulan)</h3>
                    <p class="text-sm text-gray-500">Perkembangan revenue bulanan</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-chart-area text-blue-600 text-xl"></i>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Profit Analysis -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                Analisis Profit
            </h3>
            
            <div class="space-y-3">
                @if($grossProfit > 0)
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="text-green-800 font-semibold mb-1">✅ Profit Positif</p>
                        <p class="text-green-700 text-sm">Bisnis menguntungkan dengan margin {{ number_format($profitMargin, 1) }}%</p>
                    </div>
                @else
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-800 font-semibold mb-1">⚠️ Profit Negatif</p>
                        <p class="text-red-700 text-sm">Biaya melebihi revenue, perlu evaluasi</p>
                    </div>
                @endif
                
                @if($profitMargin >= 30)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-blue-800 font-semibold mb-1">💎 Margin Sangat Baik</p>
                        <p class="text-blue-700 text-sm">Profit margin di atas 30% menunjukkan bisnis yang sehat</p>
                    </div>
                @elseif($profitMargin >= 15)
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                        <p class="text-yellow-800 font-semibold mb-1">⚡ Margin Cukup Baik</p>
                        <p class="text-yellow-700 text-sm">Margin 15-30% adalah standar industri retail</p>
                    </div>
                @elseif($profitMargin > 0)
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded">
                        <p class="text-orange-800 font-semibold mb-1">📊 Margin Rendah</p>
                        <p class="text-orange-700 text-sm">Pertimbangkan untuk optimasi harga atau efisiensi biaya</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recommendations -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tasks text-purple-600 mr-2"></i>
                Rekomendasi
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Monitor Trend</p>
                        <p class="text-sm text-gray-600">Pantau trend bulanan untuk identifikasi pola</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-green-100 rounded-full p-2 mr-3">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Optimasi Harga</p>
                        <p class="text-sm text-gray-600">Review harga produk untuk maksimalkan profit</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                        <i class="fas fa-box text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Efisiensi Stok</p>
                        <p class="text-sm text-gray-600">Hindari overstocking untuk kurangi biaya</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-yellow-100 rounded-full p-2 mr-3">
                        <i class="fas fa-trophy text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Focus Best Sellers</p>
                        <p class="text-sm text-gray-600">Prioritas produk dengan margin tertinggi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Profit Chart
const profitCtx = document.getElementById('profitChart').getContext('2d');
new Chart(profitCtx, {
    type: 'bar',
    data: {
        labels: ['Revenue', 'Cost', 'Profit'],
        datasets: [{
            label: 'Rupiah',
            data: [{{ $totalRevenue }}, {{ $totalCost }}, {{ $grossProfit }}],
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(59, 130, 246, 0.8)'
            ],
            borderColor: [
                'rgb(34, 197, 94)',
                'rgb(239, 68, 68)',
                'rgb(59, 130, 246)'
            ],
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

// Monthly Trend Chart
const monthlyData = @json($monthlyData);
const trendCtx = document.getElementById('trendChart').getContext('2d');

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => {
            const [year, month] = item.month.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        }),
        datasets: [{
            label: 'Revenue',
            data: monthlyData.map(item => item.revenue),
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
</script>
@endpush
@endsection