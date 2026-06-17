<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Member extends Model
{
    protected $table = 'members';
    
    protected $fillable = [
        'member_number', 'first_name', 'last_name', 'email', 'phone', 'whatsapp',
        'address', 'birth_date', 'birth_place', 'instrument', 'instrument_level',
        'entry_date', 'section', 'section_role', 'photo', 'status', 'id_document',
        'emergency_contact_name', 'emergency_contact_phone', 'medical_notes',
        'skills', 'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    // Relations
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    public function eventParticipations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function committeePositions(): HasMany
    {
        return $this->hasMany(CommitteeMember::class);
    }

    public function currentCommitteePosition()
    {
        return $this->hasOne(CommitteeMember::class)->where('is_current', true);
    }

    public function rehearsalAttendances(): HasMany
    {
        return $this->hasMany(RehearsalAttendance::class);
    }

    // Accesseurs
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameUpperCaseAttribute(): string
    {
        return strtoupper($this->full_name);
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? Storage::url($this->photo) : asset('images/default-avatar.png');
    }

    public function getMemberCardQrCodeAttribute(): string
    {
        $data = json_encode([
            'member_number' => $this->member_number,
            'name' => $this->full_name,
            'section' => $this->section,
            'instrument' => $this->instrument
        ]);
        return base64_encode(QrCode::format('png')->size(300)->generate($data));
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeByInstrument($query, $instrument)
    {
        return $query->where('instrument', $instrument);
    }

    // Méthodes
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canVote(): bool
    {
        return $this->isActive() && $this->entry_date->diffInMonths(now()) >= 6;
    }

    public function getTotalContributions($year = null): float
    {
        $year = $year ?? now()->year;
        return $this->contributions()
            ->whereYear('payment_date', $year)
            ->sum('amount');
    }

    public function isContributionPaid($month, $year): bool
    {
        return $this->contributions()
            ->where('type', 'monthly')
            ->where('month', $month)
            ->where('year', $year)
            ->exists();
    }

    // Générer le numéro de membre
    public static function generateMemberNumber(): string
    {
        $year = date('Y');
        $lastMember = self::whereYear('created_at', $year)->latest()->first();
        
        if ($lastMember) {
            $lastNumber = intval(substr($lastMember->member_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'FC-' . $year . '-' . $newNumber;
    }
}