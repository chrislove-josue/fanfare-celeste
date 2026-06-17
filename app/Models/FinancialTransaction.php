<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FinancialTransaction extends Model
{
    protected $table = 'financial_transactions';
    
    protected $fillable = [
        'member_id', 'type', 'category', 'amount', 'description',
        'transaction_date', 'status', 'approved_by', 'approved_at',
        'receipt_path', 'reference', 'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_path ? Storage::url($this->receipt_path) : null;
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public static function getCategories($type)
    {
        if ($type === 'income') {
            return [
                'cotisation' => 'Cotisation mensuelle',
                'don' => 'Don',
                'offrande' => 'Offrande',
                'subvention' => 'Subvention',
                'concert' => 'Recette de concert',
                'evenement' => 'Événement spécial',
                'autre' => 'Autre'
            ];
        } else {
            return [
                'instrument' => 'Achat d\'instrument',
                'reparation' => 'Réparation',
                'uniforme' => 'Uniforme',
                'transport' => 'Transport',
                'evenement' => 'Organisation d\'événement',
                'communication' => 'Communication',
                'location' => 'Location de salle',
                'divers' => 'Divers'
            ];
        }
    }
}