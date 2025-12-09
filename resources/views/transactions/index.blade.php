@extends('layouts.app')

@section('title', 'Transaksi Penjualan')
@section('subtitle', 'Point of Sale - Input transaksi penjualan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Products List -->
    <div class="lg:col-span-2 space-y-4">
        
        <!-- Search Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    id="searchProduct"
                    placeholder="Cari produk..." 
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Produk</h3>
            
            <div id="productsList" class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[600px] overflow-y-auto">
                @foreach($items as $item)
                <button 
                    type="button"
                    onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }}, {{ $item->quantity }})"
                    class="bg-gradient-to-br from-blue-50 to-purple-50 hover:from-blue-100 hover:to-purple-100 rounded-lg p-4 text-left border border-gray-200 hover:border-blue-300 transition-all transform hover:scale-105"
                >
                    <div class="flex flex-col h-full">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg w-12 h-12 flex items-center justify-center text-white font-bold text-lg mb-3">
                            {{ substr($item->name, 0, 1) }}
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ $item->name }}</h4>
                        <p class="text-xs text-gray-600 mb-2">Stok: {{ $item->quantity }}</p>
                        <p class="text-blue-600 font-bold mt-auto">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Cart & Checkout -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 sticky top-24">
            <div class="p-4 border-b border-gray-200 gradient-bg">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Keranjang Belanja
                </h3>
            </div>
            
            <form id="checkoutForm" action="{{ route('transactions.store') }}" method="POST">
                @csrf
                
                <!-- Cart Items -->
                <div id="cartItems" class="p-4 max-h-[300px] overflow-y-auto space-y-2">
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                        <p class="text-sm">Keranjang kosong</p>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div class="p-4 border-t border-gray-200 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                        <input 
                            type="text" 
                            name="customer_name" 
                            placeholder="Opsional"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select 
                            name="payment_method" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        >
                            <option value="cash">Tunai</option>
                            <option value="debit">Debit Card</option>
                            <option value="credit">Credit Card</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                </div>
                
                <!-- Summary -->
                <div class="p-4 border-t border-gray-200 bg-gray-50 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Item:</span>
                        <span id="totalItems" class="font-semibold">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900">Total:</span>
                        <span id="totalAmount" class="font-bold text-xl text-blue-600">Rp 0</span>
                    </div>
                </div>
                
                <!-- Payment Input -->
                <div class="p-4 border-t border-gray-200 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar *</label>
                        <input 
                            type="number" 
                            name="paid_amount" 
                            id="paidAmount"
                            placeholder="0"
                            min="0"
                            step="1000"
                            required
                            oninput="calculateChange()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-bold"
                        >
                    </div>
                    
                    <div id="changeDisplay" class="hidden p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-green-700">Kembalian:</span>
                            <span id="changeAmount" class="font-bold text-lg text-green-700">Rp 0</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="p-4 border-t border-gray-200 space-y-2">
                    <button 
                        type="submit" 
                        id="checkoutBtn"
                        disabled
                        class="w-full gradient-bg text-white font-semibold py-3 px-4 rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fas fa-check-circle mr-2"></i>
                        Proses Pembayaran
                    </button>
                    
                    <button 
                        type="button" 
                        onclick="clearCart()"
                        class="w-full bg-red-50 text-red-600 font-semibold py-3 px-4 rounded-lg hover:bg-red-100 transition-colors"
                    >
                        <i class="fas fa-trash mr-2"></i>
                        Kosongkan Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let cart = [];

function addToCart(id, name, price, maxStock) {
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        if (existingItem.quantity >= maxStock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1, maxStock });
    }
    
    updateCart();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCart();
}

function updateQuantity(id, delta) {
    const item = cart.find(item => item.id === id);
    if (!item) return;
    
    const newQuantity = item.quantity + delta;
    
    if (newQuantity < 1) {
        removeFromCart(id);
        return;
    }
    
    if (newQuantity > item.maxStock) {
        alert('Stok tidak mencukupi!');
        return;
    }
    
    item.quantity = newQuantity;
    updateCart();
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const totalItemsSpan = document.getElementById('totalItems');
    const totalAmountSpan = document.getElementById('totalAmount');
    const checkoutBtn = document.getElementById('checkoutBtn');
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-shopping-basket text-4xl mb-2"></i>
                <p class="text-sm">Keranjang kosong</p>
            </div>
        `;
        checkoutBtn.disabled = true;
    } else {
        let html = '';
        let totalItems = 0;
        let totalAmount = 0;
        
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            totalItems += item.quantity;
            totalAmount += subtotal;
            
            html += `
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-sm text-gray-900 flex-1 pr-2">${item.name}</h4>
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 bg-white border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <span class="w-8 text-center font-semibold">${item.quantity}</span>
                            <button type="button" onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 bg-white border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                        <span class="font-bold text-blue-600">Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                    <input type="hidden" name="items[${item.id}][id]" value="${item.id}">
                    <input type="hidden" name="items[${item.id}][quantity]" value="${item.quantity}">
                </div>
            `;
        });
        
        cartItemsDiv.innerHTML = html;
        totalItemsSpan.textContent = totalItems;
        totalAmountSpan.textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
        totalAmountSpan.dataset.amount = totalAmount;
        
        checkoutBtn.disabled = false;
    }
    
    calculateChange();
}

function calculateChange() {
    const totalAmount = parseFloat(document.getElementById('totalAmount').dataset.amount || 0);
    const paidAmount = parseFloat(document.getElementById('paidAmount').value || 0);
    const changeAmount = paidAmount - totalAmount;
    
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmountSpan = document.getElementById('changeAmount');
    
    if (paidAmount > 0 && changeAmount >= 0) {
        changeDisplay.classList.remove('hidden');
        changeAmountSpan.textContent = 'Rp ' + changeAmount.toLocaleString('id-ID');
    } else {
        changeDisplay.classList.add('hidden');
    }
}

function clearCart() {
    if (cart.length === 0) return;
    
    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        updateCart();
        document.getElementById('paidAmount').value = '';
    }
}

// Search functionality
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const products = document.querySelectorAll('#productsList button');
    
    products.forEach(product => {
        const productName = product.querySelector('h4').textContent.toLowerCase();
        if (productName.includes(searchTerm)) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
});

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang masih kosong!');
        return;
    }
    
    const totalAmount = parseFloat(document.getElementById('totalAmount').dataset.amount || 0);
    const paidAmount = parseFloat(document.getElementById('paidAmount').value || 0);
    
    if (paidAmount < totalAmount) {
        e.preventDefault();
        alert('Jumlah pembayaran kurang dari total!');
        return;
    }
});
</script>
@endpush
@endsection