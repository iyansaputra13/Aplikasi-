@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-boxes"></i> Daftar Barang</h2>
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Barang Baru
    </a>
</div>

<!-- Notifikasi Stok -->
@if($lowStockItems->count() > 0)
<div class="alert alert-warning">
    <h5><i class="fas fa-exclamation-triangle"></i> Peringatan Stok Menipis!</h5>
    <ul class="mb-0">
        @foreach($lowStockItems as $item)
        <li>{{ $item->name }} - Stok: {{ $item->quantity }} (Min: {{ $item->min_stock }})</li>
        @endforeach
    </ul>
</div>
@endif

@if($outOfStockItems->count() > 0)
<div class="alert alert-danger">
    <h5><i class="fas fa-times-circle"></i> Stok Habis!</h5>
    <ul class="mb-0">
        @foreach($outOfStockItems as $item)
        <li>{{ $item->name }} - STOK HABIS!</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Pencarian -->
<form method="GET" action="{{ route('items.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="{{ $search }}">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
</form>

<!-- Tabel Barang -->
<div class="card">
    <div class="card-body">
        @if($items->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>
                            @if($item->quantity == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($item->isLowStock())
                                <span class="badge bg-warning">Menipis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin hapus barang ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada data barang.</p>
            <a href="{{ route('items.create') }}" class="btn btn-primary">Tambah Barang Pertama</a>
        </div>
        @endif
    </div>
</div>
@endsection