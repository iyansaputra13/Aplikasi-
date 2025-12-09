@extends('layouts.app')

@section('title', 'Restock Barang')
@section('subtitle', 'Tambah stok barang ke inventori')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Restock Form -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Quick Actions -->
        <div class="flex gap-3">
            <a href="{{ route('restock.batch') }}" class="flex-1 bg-purple-600 text-white text-center font-semibold py-3 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-layer-group mr-2"></i>Restock Batch (Multiple Items)
            </a>
            <a href="{{ route('restock.history') }}" class="flex-1 bg-blue-600 text-white text-center font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-history mr-2"></i>Lihat Riwayat Restock
            </a>
        </div>
        
        <!-- Single Restock Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="gradient-bg text-white px-6 py-4">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-box-open mr-2"></i>
                    Restock Single Item
                </h2>
            </div>
            
            <form action="{{ route('restock.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Select Item -->
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-box text-blue-600 mr-1"></i>Pilih Barang *
                    </label>
                    <select 
                        id="item_id" 
                        name="item_id" 
                        required
                        onchange="updateItemInfo()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('item_id') border-red-500 @enderror"
                    >
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                        <option 
                            value="{{ $item->id }}" 
                            data-stock="{{ $item->quantity }}"
                            data-min-stock="{{ $item->min_stock }}"
                            data-price="{{ $item->price }}"
                            {{ old('item_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->name }} (Stok: {{ $item->quantity }})
                        </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Item Info Display -->
                <div id="itemInfo" class="hidden bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-blue-700 font-medium">Stok Saat Ini</p>
                            <p id="currentStock" class="text-2xl font-bold text-blue-900">0</p>
                        </div>
                        <div>
                            <p class="text-blue-700 font-medium">Stok Minimum</p>
                            <p id="minStock" class="text-2xl font-bold text-blue-900">0</p>
                        </div>
                        <div>
                            <p class="text-blue-700 font-medium">Harga Jual</p>
                            <p id="itemPrice" class="text-lg font-bold text-blue-900">Rp 0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quantity to Add -->
                <div>
                    <label for="quantity_added" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-plus-circle text-green-600 mr-1"></i>Jumlah Ditambah *
                    </label>
                    <input 
                        type="number" 
                        id="quantity_added" 
                        name="quantity_added" 
                        value="{{ old('quantity_added') }}"
                        min="1"
                        required
                        oninput="calculateAfter()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity_added') border-red-500 @enderror"
                        placeholder="Masukkan jumlah"
                    >
                    @error('quantity_added')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock After (Preview) -->
                <div id="afterPreview" class="hidden bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700 font-medium mb-1">Stok Setelah Restock</p>
                    <p class="text-3xl font-bold text-green-800">
                        <span id="stockAfter">0</span> unit
                    </p>
                </div>
                
                <!-- Cost Per Unit (Optional) -->
                <div>
                    <label for="cost_per_unit" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dollar-sign text-yellow-600 mr-1"></i>Harga Beli per Unit (Opsional)
                    </label>
                    <input 
                        type="number" 
                        id="cost_per_unit" 
                        name="cost_per_unit" 
                        value="{{ old('cost_per_unit') }}"
                        min="0"
                        step="100"
                        oninput="calculateTotalCost()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0"
                    >
                    <p class="mt-1 text-xs text-gray-500">Untuk tracking biaya pembelian</p>
                </div>
                
                <!-- Total Cost Preview -->
                <div id="costPreview" class="hidden bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <p class="text-sm text-yellow-700 font-medium mb-1">Total Biaya</p>
                    <p class="text-2xl font-bold text-yellow-800" id="totalCost">Rp 0</p>
                </div>
                
                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-truck text-purple-600 mr-1"></i>Supplier (Opsional)
                    </label>
                    <input 
                        type="text" 
                        id="supplier" 
                        name="supplier" 
                        value="{{ old('supplier') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Nama supplier"
                    >
                </div>
                
                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-600 mr-1"></i>Catatan (Opsional)
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Catatan tambahan..."
                    >{{ old('notes') }}</textarea>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full gradient-bg text-white font-semibold py-4 px-6 rounded-lg hover:opacity-90 transition-opacity shadow-lg"
                >
                    <i class="fas fa-check-circle mr-2"></i>Proses Restock
                </button>
            </form>
        </div>
    </div>
    
    <!-- Recent Restocks Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 sticky top-24">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Restock Terbaru
                </h3>
            </div>
            
            <div class="p-4 max-h-[600px] overflow-y-auto">
                @if($recentRestocks->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentRestocks as $restock)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $restock->item->name }}</h4>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">
                                    +{{ $restock->quantity_added }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <p><i class="fas fa-user mr-1"></i>{{ $restock->user->name }}</p>
                                <p><i class="fas fa-calendar mr-1"></i>{{ $restock->created_at->format('d M Y, H:i') }}</p>
                                @if($restock->supplier)
                                <p><i class="fas fa-truck mr-1"></i>{{ $restock->supplier }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada restock</p>
                    </div>
                @endif
            </div>
            
            @if($recentRestocks->count() > 0)
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('restock.history') }}" class="block text-center text-blue-600 hover:text-blue-700 font-medium text-sm">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateItemInfo() {
    const select = document.getElementById('item_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const stock = option.dataset.stock;
        const minStock = option.dataset.minStock;
        const price = option.dataset.price;
        
        document.getElementById('currentStock').textContent = stock;
        document.getElementById('minStock').textContent = minStock;
        document.getElementById('itemPrice').textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        document.getElementById('itemInfo').classList.remove('hidden');
    } else {
        document.getElementById('itemInfo').classList.add('hidden');
    }
    
    calculateAfter();
}

function calculateAfter() {
    const select = document.getElementById('item_id');
    const option = select.options[select.selectedIndex];
    const quantityAdded = parseInt(document.getElementById('quantity_added').value) || 0;
    
    if (option.value && quantityAdded > 0) {
        const currentStock = parseInt(option.dataset.stock);
        const afterStock = currentStock + quantityAdded;
        
        document.getElementById('stockAfter').textContent = afterStock;
        document.getElementById('afterPreview').classList.remove('hidden');
    } else {
        document.getElementById('afterPreview').classList.add('hidden');
    }
    
    calculateTotalCost();
}

function calculateTotalCost() {
    const costPerUnit = parseFloat(document.getElementById('cost_per_unit').value) || 0;
    const quantityAdded = parseInt(document.getElementById('quantity_added').value) || 0;
    
    if (costPerUnit > 0 && quantityAdded > 0) {
        const totalCost = costPerUnit * quantityAdded;
        document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
        document.getElementById('costPreview').classList.remove('hidden');
    } else {
        document.getElementById('costPreview').classList.add('hidden');
    }
}
</script>
@endpush
@endsection