<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity', 
        'price',
        'category',
        'min_stock',
        'barcode'
    ];

    // Method untuk cek stok menipis
    public function isLowStock()
    {
        return $this->quantity <= $this->min_stock;
    }

    // Method untuk notifikasi stok habis
    public function isOutOfStock()
    {
        return $this->quantity === 0;
    }
}