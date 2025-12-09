@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('subtitle', 'Informasi lengkap transaksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('transactions.history') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
    </a>
    
    <!-- Transaction Info Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header -->
        <div class="gradient-bg text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">{{ $transaction->transaction_code }}</h2>
                    <p class="text-blue-100">{{ $transaction->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    @php
                        $badgeColor = match($transaction->payment_method) {
                            'cash' => 'bg-green-100 text-green-800',
                            'debit' => 'bg-blue-100 text-blue-800',
                            'credit' => 'bg-purple-100 text-purple-800',
                            'qris' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $badgeColor }}">
                        {{ $transaction->getPaymentMethodDisplayName() }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Transaction Details -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kasir</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold mr-3">
                            {{ substr($transaction->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $transaction->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pelanggan</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $transaction->customer_name ?: 'Umum' }}</p>
                            <p class="text-xs text-gray-500">Pelanggan</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Item</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-2xl">{{ $transaction->items->sum('quantity') }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->items->count() }} jenis produk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Items List -->
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Pembelian</h3>
            <div class="space-y-3">
                @foreach($transaction->items as $item)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold mr-4">
                            {{ substr($item->item_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $item->item_name }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $item->quantity }} x Rp {{ number_format($item->item_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Payment Summary -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            <div class="max-w-md ml-auto space-y-3">
                <div class="flex justify-between text-gray-700">
                    <span>Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between pt-3 border-t border-gray-300">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between text-gray-700">
                    <span>Dibayar</span>
                    <span class="font-semibold">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between pt-3 border-t border-gray-300">
                    <span class="font-semibold text-green-700">Kembalian</span>
                    <span class="text-xl font-bold text-green-700">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes (if exists) -->
        @if($transaction->notes)
        <div class="p-6 border-t border-gray-200 bg-yellow-50">
            <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                Catatan
            </h4>
            <p class="text-gray-700">{{ $transaction->notes }}</p>
        </div>
        @endif
        
        <!-- Actions -->
        <div class="p-6 border-t border-gray-200 flex gap-3">
            <a 
                href="{{ route('transactions.receipt', $transaction->id) }}" 
                class="flex-1 bg-blue-600 text-white text-center font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors"
            >
                <i class="fas fa-print mr-2"></i>Cetak Struk
            </a>
            <a 
                href="{{ route('transactions.history') }}" 
                class="flex-1 bg-gray-200 text-gray-700 text-center font-semibold py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors"
            >
                <i class="fas fa-list mr-2"></i>Lihat Semua Transaksi
            </a>
        </div>
    </div>
</div>
@endsection