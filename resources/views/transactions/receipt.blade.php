@extends('layouts.app')

@section('title', 'Struk Transaksi')
@section('subtitle', 'Transaksi berhasil')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Success Message -->
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
            <div>
                <h3 class="font-semibold text-green-800">Transaksi Berhasil!</h3>
                <p class="text-sm text-green-700">Pembayaran telah diterima dan stok telah dikurangi</p>
            </div>
        </div>
    </div>
    
    <!-- Receipt Card -->
    <div id="receiptContent" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header -->
        <div class="gradient-bg text-white p-6 text-center">
            <i class="fas fa-warehouse text-4xl mb-3"></i>
            <h2 class="text-2xl font-bold">Inventory System</h2>
            <p class="text-sm text-blue-100 mt-1">Struk Pembayaran</p>
        </div>
        
        <!-- Transaction Info -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 mb-1">No. Transaksi</p>
                    <p class="font-bold text-gray-900">{{ $transaction->transaction_code }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Tanggal</p>
                    <p class="font-bold text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Kasir</p>
                    <p class="font-bold text-gray-900">{{ $transaction->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Pelanggan</p>
                    <p class="font-bold text-gray-900">{{ $transaction->customer_name ?: 'Umum' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Items -->
        <div class="p-6">
            <h3 class="font-bold text-gray-900 mb-4">Detail Pembelian</h3>
            <div class="space-y-3">
                @foreach($transaction->items as $item)
                <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $item->item_name }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $item->quantity }} x Rp {{ number_format($item->item_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="font-bold text-gray-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Summary -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            <div class="space-y-2">
                <div class="flex justify-between text-lg">
                    <span class="font-semibold text-gray-900">Total</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold text-gray-900">{{ $transaction->getPaymentMethodDisplayName() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibayar</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-300">
                    <span class="font-semibold text-green-700">Kembalian</span>
                    <span class="font-bold text-lg text-green-700">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-6 text-center border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-1">Terima kasih atas pembelian Anda!</p>
            <p class="text-xs text-gray-500">Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">
        <button 
            onclick="printReceipt()" 
            class="flex-1 bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors"
        >
            <i class="fas fa-print mr-2"></i>
            Cetak Struk
        </button>
        <a 
            href="{{ route('transactions.index') }}" 
            class="flex-1 bg-green-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-green-700 transition-colors text-center"
        >
            <i class="fas fa-plus mr-2"></i>
            Transaksi Baru
        </a>
        <a 
            href="{{ route('dashboard') }}" 
            class="flex-1 bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-gray-700 transition-colors text-center"
        >
            <i class="fas fa-home mr-2"></i>
            Dashboard
        </a>
    </div>
</div>

@push('scripts')
<script>
function printReceipt() {
    window.print();
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #receiptContent, #receiptContent * {
        visibility: visible;
    }
    #receiptContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endpush
@endsection