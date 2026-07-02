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

    /**
     * Liste des postes disponibles dans le comité
     * CORRESPOND EXACTEMENT aux clés utilisées dans le seeder
     */
    public static function getPositions(): array
    {
        return [
            'le_maitre' => 'Le Maître',
            'general_secretary' => 'Secrétaire Général',
            'treasurer' => 'Trésorier Général',
            'conductor' => 'Surveillant Général',
            'deputy_conductor' => 'Surveillant Adjoint',
            'conseiller' => 'Conseiller',
            'organisateur' => 'Organisateur',
        ];
    }

    /**
     * Récupère le libellé du poste
     */
    public function getPositionLabelAttribute(): string
    {
        return self::getPositions()[$this->position] ?? $this->position;
    }

    /**
     * Ordre d'affichage des postes
     */
    public static function getPositionOrder(): array
    {
        return [
            'le_maitre' => 1,
            'general_secretary' => 2,
            'treasurer' => 3,
            'conductor' => 4,
            'deputy_conductor' => 5,
            'conseiller' => 6,
            'organisateur' => 7,
        ];
    }

    public function endMandate(): void
    {
        $this->update([
            'end_date' => now(),
            'is_current' => false
        ]);
    }

    /**
     * Vérifie si le membre est actif dans le comité
     */
    public function isActive(): bool
    {
        return $this->is_current === true;
    }

    /**
 * Postes regroupés par catégorie
 * Utilisé pour l'affichage groupé (formulaires, listes)
 */
public static function getPositionsByCategory(): array
{
    $positions = self::getPositions();

    return [
        'Bureau exécutif' => [
            'le_maitre' => $positions['le_maitre'],
            'general_secretary' => $positions['general_secretary'],
            'treasurer' => $positions['treasurer'],
        ],
        'Surveillance' => [
            'conductor' => $positions['conductor'],
            'deputy_conductor' => $positions['deputy_conductor'],
        ],
        'Conseil' => [
            'conseiller' => $positions['conseiller'],
            'organisateur' => $positions['organisateur'],
        ],
    ];
}
}