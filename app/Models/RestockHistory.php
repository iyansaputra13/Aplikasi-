<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'quantity_before',
        'quantity_added',
        'quantity_after',
        'cost_per_unit',
        'total_cost',
        'supplier',
        'notes',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Relationship with Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted cost per unit
     */
    public function getFormattedCostPerUnit()
    {
        return $this->cost_per_unit ? 'Rp ' . number_format($this->cost_per_unit, 0, ',', '.') : '-';
    }

    /**
     * Get formatted total cost
     */
    public function getFormattedTotalCost()
    {
        return $this->total_cost ? 'Rp ' . number_format($this->total_cost, 0, ',', '.') : '-';
    }
}