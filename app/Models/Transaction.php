<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'customer_name',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode()
    {
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
                               ->latest()
                               ->first();
        
        $number = $lastTransaction ? (int) substr($lastTransaction->transaction_code, -4) + 1 : 1;
        
        return 'TRX' . $date . sprintf('%04d', $number);
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Transaction Items
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayName()
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'debit' => 'Debit Card',
            'credit' => 'Credit Card',
            'qris' => 'QRIS',
            default => 'Unknown',
        };
    }
}