<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    protected $table = 'contributions';
    
    protected $fillable = [
        'member_id', 'type', 'amount', 'month', 'year',
        'payment_date', 'payment_method', 'transaction_id', 'receipt_number'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public static function generateReceiptNumber(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)->latest()->first();
        
        if ($last) {
            $lastNumber = intval(substr($last->receipt_number, -5));
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return 'REC-' . $year . '-' . $newNumber;
    }

    public function scopeMonthly($query)
    {
        return $query->where('type', 'monthly');
    }

    public function scopeSpecial($query)
    {
        return $query->where('type', 'special');
    }

    public function scopeDonation($query)
    {
        return $query->where('type', 'donation');
    }
}