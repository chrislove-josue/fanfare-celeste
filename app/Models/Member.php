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

    // ==========================================
    // RELATIONS
    // ==========================================

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

    // ==========================================
    // ACCESSORS
    // ==========================================

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

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) return null;

        $path = storage_path('app/public/' . $this->photo);

        if (!file_exists($path)) return null;

        $mime = mime_content_type($path);

        $src = match($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png'               => imagecreatefrompng($path),
            'image/webp'              => imagecreatefromwebp($path),
            default                   => null,
        };

        if (!$src) {
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }

        $sw = imagesx($src);
        $sh = imagesy($src);
        $size = min($sw, $sh);
        $ox = intval(($sw - $size) / 2);
        $oy = intval(($sh - $size) / 2);

        $dst = imagecreatetruecolor(68, 68);

        if ($mime === 'image/png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, $ox, $oy, 68, 68, $size, $size);

        ob_start();
        imagejpeg($dst, null, 90);
        $data = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return 'data:image/jpeg;base64,' . base64_encode($data);
    }

    public function getMemberCardQrCodeAttribute(): string
    {
        $data = json_encode([
            'member_number' => $this->member_number,
            'name' => $this->full_name,
            'section' => $this->section,
            'instrument' => $this->instrument
        ]);
        return base64_encode(QrCode::format('svg')->size(300)->generate($data));
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeByInstrument($query, $instrument)
    {
        return $query->where('instrument', $instrument);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ==========================================
    // MÉTHODES
    // ==========================================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function hasUser(): bool
    {
        return $this->user !== null;
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

    // ==========================================
    // MÉTHODES STATIQUES (pour les formulaires)
    // ==========================================

    /**
     * Liste des sections disponibles
     */
    public static function getSections(): array
    {
        return [
            'Trompettes' => 'Trompettes',
            'Trombones' => 'Trombones',
            'Saxophones' => 'Saxophones',
            'Percussions' => 'Percussions',
            'Clairons' => 'Clairons',
            'Batterie' => 'Batterie',
            'Clarinettes' => 'Clarinettes',
            'Flûtes' => 'Flûtes',
            'Administration' => 'Administration'
        ];
    }

    /**
     * Liste des instruments disponibles
     */
    public static function getInstruments(): array
    {
        return [
            'Trompette' => 'Trompette',
            'Trombone' => 'Trombone',
            'Saxophone Alto' => 'Saxophone Alto',
            'Saxophone Ténor' => 'Saxophone Ténor',
            'Saxophone Baryton' => 'Saxophone Baryton',
            'Batterie' => 'Batterie',
            'Caisse Claire' => 'Caisse Claire',
            'Grosse Caisse' => 'Grosse Caisse',
            'Cymbales' => 'Cymbales',
            'Clarinette' => 'Clarinette',
            'Flûte' => 'Flûte',
            'Piano' => 'Piano',
            'Guitare' => 'Guitare'
        ];
    }

    /**
     * Liste des niveaux d'instrument
     */
    public static function getInstrumentLevels(): array
    {
        return [
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé',
            'expert' => 'Expert'
        ];
    }

    /**
     * Liste des statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            'active' => 'Actif',
            'pending' => 'En attente',
            'inactive' => 'Inactif',
            'suspended' => 'Suspendu',
            'withdrawn' => 'Retiré',
            'deceased' => 'Décédé',
            'training' => 'En formation'
        ];
    }

    /**
     * Générer le numéro de membre
     */
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