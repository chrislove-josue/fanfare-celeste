<?php
// app/Models/CommitteeMember.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeMember extends Model
{
    protected $table = 'committee_members';
    
    protected $fillable = [
        'member_id', 'position', 'position_order', 'start_date',
        'end_date', 'is_current', 'responsibilities', 'achievements'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public static function getPositions(): array
    {
        return [
            'president' => 'Président',
            'vice_president' => 'Vice-président',
            'general_secretary' => 'Secrétaire général',
            'deputy_secretary' => 'Secrétaire adjoint',
            'treasurer' => 'Trésorier',
            'deputy_treasurer' => 'Trésorier adjoint',
            'conductor' => 'Chef de fanfare',
            'deputy_conductor' => 'Chef adjoint',
            'section_leader_trumpet' => 'Responsable Trompettes',
            'section_leader_trombone' => 'Responsable Trombones',
            'section_leader_saxophone' => 'Responsable Saxophones',
            'section_leader_percussion' => 'Responsable Percussions',
        ];
    }

    public function endMandate(): void
    {
        $this->update([
            'end_date' => now(),
            'is_current' => false
        ]);
    }
}