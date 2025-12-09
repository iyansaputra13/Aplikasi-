@extends('layouts.app')

@section('title', 'Batch Restock')
@section('subtitle', 'Restock multiple items sekaligus')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('restock.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Restock
    </a>
    
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Header -->
        <div class="gradient-bg text-white px-6 py-4">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-layer-group mr-2"></i>
                Batch Restock - Multiple Items
            </h2>
            <p class="text-sm text-blue-100 mt-1">Tambah stok untuk beberapa barang sekaligus</p>
        </div>
        
        <form action="{{ route('restock.batch.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Common Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-gray-200">
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-truck text-purple-600 mr-1"></i>Supplier (Untuk semua item)
                    </label>
                    <input 
                        type="text" 
                        id="supplier" 
                        name="supplier" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama supplier"
                    >
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-600 mr-1"></i>Catatan Umum
                    </label>
                    <input 
                        type="text" 
                        id="notes" 
                        name="notes" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Catatan untuk batch ini"
                    >
                </div>
            </div>
            
            <!-- Items List -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Barang</h3>
                    <button 
                        type="button" 
                        onclick="addItem()" 
                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700"
                    >
                        <i class="fas fa-plus mr-2"></i>Tambah Barang
                    </button>
                </div>
                
                <div id="itemsList" class="space-y-4">
                    <!-- Items will be added here dynamically -->
                </div>
                
                <div id="emptyState" class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-box-open text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-600 font-medium mb-2">Belum ada barang</p>
                    <p class="text-sm text-gray-500 mb-4">Klik tombol "Tambah Barang" untuk mulai</p>
                    <button 
                        type="button" 
                        onclick="addItem()" 
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700"
                    >
                        <i class="fas fa-plus mr-2"></i>Tambah Barang Pertama
                    </button>
                </div>
            </div>
            
            <!-- Summary -->
            <div id="summarySection" class="hidden bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Items</p>
                        <p class="text-2xl font-bold text-blue-900" id="totalItems">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Quantity</p>
                        <p class="text-2xl font-bold text-blue-900" id="totalQuantity">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Biaya</p>
                        <p class="text-2xl font-bold text-blue-900" id="totalCost">Rp 0</p>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <button 
                type="submit" 
                id="submitBtn"
                disabled
                class="w-full gradient-bg text-white font-semibold py-4 px-6 rounded-lg hover:opacity-90 transition-opacity shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <i class="fas fa-check-circle mr-2"></i>Proses Batch Restock
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 0;
const items = @json($items);

function addItem() {
    const itemsList = document.getElementById('itemsList');
    const emptyState = document.getElementById('emptyState');
    
    const itemHtml = `
        <div class="item-row bg-gray-50 border border-gray-200 rounded-lg p-4" data-index="${itemIndex}">
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang</label>
                    <select 
                        name="items[${itemIndex}][item_id]" 
                        required
                        onchange="updateItemInfo(${itemIndex})"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">-- Pilih --</option>
                        ${items.map(item => `
                            <option 
                                value="${item.id}" 
                                data-stock="${item.quantity}"
                                data-price="${item.price}"
                            >
                                ${item.name} (Stok: ${item.quantity})
                            </option>
                        `).join('')}
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                    <input 
                        type="number" 
                        name="items[${itemIndex}][quantity]" 
                        min="1"
                        required
                        oninput="updateSummary()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="0"
                    >
                </div>
                
                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Beli/Unit</label>
                    <input 
                        type="number" 
                        name="items[${itemIndex}][cost_per_unit]" 
                        min="0"
                        step="100"
                        oninput="updateSummary()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="0"
                    >
                </div>
                
                <div class="col-span-2 flex items-end">
                    <button 
                        type="button" 
                        onclick="removeItem(${itemIndex})"
                        class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div id="item-info-${itemIndex}" class="hidden mt-2 text-xs text-gray-600">
                <span>Stok saat ini: <strong id="current-stock-${itemIndex}">0</strong></span>
            </div>
        </div>
    `;
    
    itemsList.insertAdjacentHTML('beforeend', itemHtml);
    emptyState.classList.add('hidden');
    itemIndex++;
    updateSummary();
}

function removeItem(index) {
    const row = document.querySelector(`[data-index="${index}"]`);
    row.remove();
    
    const remainingItems = document.querySelectorAll('.item-row');
    if (remainingItems.length === 0) {
        document.getElementById('emptyState').classList.remove('hidden');
    }
    
    updateSummary();
}

function updateItemInfo(index) {
    const select = document.querySelector(`[name="items[${index}][item_id]"]`);
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const stock = option.dataset.stock;
        document.getElementById(`current-stock-${index}`).textContent = stock;
        document.getElementById(`item-info-${index}`).classList.remove('hidden');
    } else {
        document.getElementById(`item-info-${index}`).classList.add('hidden');
    }
}

function updateSummary() {
    const rows = document.querySelectorAll('.item-row');
    let totalItems = rows.length;
    let totalQuantity = 0;
    let totalCost = 0;
    
    rows.forEach(row => {
        const qty = parseInt(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const cost = parseFloat(row.querySelector('input[name*="[cost_per_unit]"]').value) || 0;
        
        totalQuantity += qty;
        totalCost += qty * cost;
    });
    
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalQuantity').textContent = totalQuantity;
    document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
    
    const summarySection = document.getElementById('summarySection');
    const submitBtn = document.getElementById('submitBtn');
    
    if (totalItems > 0) {
        summarySection.classList.remove('hidden');
        submitBtn.disabled = false;
    } else {
        summarySection.classList.add('hidden');
        submitBtn.disabled = true;
    }
}

// Add first item on load
window.addEventListener('DOMContentLoaded', () => {
    addItem();
});
</script>
@endpush
@endsection