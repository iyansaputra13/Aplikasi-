@extends('layouts.app')

@section('title', 'Dashboard Inventory')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard Inventory</h1>
    </div>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Barang</h5>
                        <h2 class="mb-0">{{ $totalItems ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Stok Aman</h5>
                        <h2 class="mb-0">{{ $safeStockItems ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Stok Menipis</h5>
                        <h2 class="mb-0">{{ $lowStockItemsCount ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Stok Habis</h5>
                        <h2 class="mb-0">{{ $outOfStockItemsCount ?? 0 }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('items.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('items.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-list"></i> Lihat Semua Barang
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('items.index') }}?search=" class="btn btn-info w-100">
                            <i class="fas fa-search"></i> Cari Barang
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('items.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-exclamation-triangle"></i> Cek Stok Menipis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi Stok -->
@if(isset($lowStockItems) && $lowStockItems->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Stok Menipis!</h5>
            <div class="table-responsive">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stok Saat Ini</th>
                            <th>Stok Minimum</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->min_stock }}</td>
                            <td class="text-danger">
                                -{{ $item->min_stock - $item->quantity }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($outOfStockItems) && $outOfStockItems->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger">
            <h5><i class="fas fa-times-circle"></i> Stok Habis!</h5>
            <ul class="mb-0">
                @foreach($outOfStockItems as $item)
                <li><strong>{{ $item->name }}</strong> - Stok: 0</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<!-- Barang Terbaru -->
@if(isset($recentItems) && $recentItems->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-clock"></i> Barang Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Tanggal Ditambah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category ?? '-' }}</td>
                                <td>
                                    @if($item->quantity == 0)
                                        <span class="badge bg-danger">0</span>
                                    @elseif($item->isLowStock())
                                        <span class="badge bg-warning">{{ $item->quantity }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $item->quantity }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection