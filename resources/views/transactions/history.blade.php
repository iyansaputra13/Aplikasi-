@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('subtitle', 'Daftar semua transaksi penjualan')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filter Transaksi
        </h3>
        
        <form method="GET" action="{{ route('transactions.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Kode transaksi, pelanggan..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select 
                    name="payment_method"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit Card</option>
                    <option value="credit" {{ request('payment_method') == 'credit' ? 'selected' : '' }}>Credit Card</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('transactions.history') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
                <button type="button" onclick="exportToExcel()" class="ml-auto px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </form>
    </div>
    
    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_transactions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Penjualan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($statistics['total_sales'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Item Terjual</p>
            <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_items_sold'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Transaksi</p>
            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($statistics['average_transaction'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Daftar Transaksi</h3>
        </div>
        
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $transaction->transaction_code }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->items->count() }} item</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $transaction->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $transaction->customer_name ?: 'Umum' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeColor = match($transaction->payment_method) {
                                    'cash' => 'bg-green-100 text-green-800',
                                    'debit' => 'bg-blue-100 text-blue-800',
                                    'credit' => 'bg-purple-100 text-purple-800',
                                    'qris' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                                {{ $transaction->getPaymentMethodDisplayName() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a 
                                href="{{ route('transactions.show', $transaction->id) }}" 
                                class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium"
                            >
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            <a 
                                href="{{ route('transactions.receipt', $transaction->id) }}" 
                                class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium ml-2"
                            >
                                <i class="fas fa-print mr-1"></i>Cetak
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Transaksi</h3>
            <p class="text-gray-600 mb-4">
                @if(request()->hasAny(['search', 'date_from', 'date_to', 'payment_method']))
                    Tidak ditemukan transaksi dengan filter yang dipilih
                @else
                    Belum ada transaksi yang tercatat
                @endif
            </p>
            @if(auth()->user()->isCashier())
            <a href="{{ route('transactions.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Buat Transaksi Baru
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function exportToExcel() {
    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('export', 'excel');
    
    // Redirect to export endpoint
    window.location.href = '{{ route('transactions.export') }}?' + urlParams.toString();
}
</script>
@endpush
@endsection