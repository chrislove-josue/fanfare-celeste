<?php
// database/seeders/CommitteeSeeder.php

namespace Database\Seeders;

use App\Models\CommitteeMember;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        // Vider la table committee_members avant de remplir
        DB::table('committee_members')->truncate();

        // Définir la composition du bureau avec les BONNES clés
        $committeeStructure = [
            [
                'email' => 'mariusadoliwossi@gmail.com',
                'position' => 'le_maitre',  // ✅ Clé correcte
                'responsibilities' => 'Direction générale de la fanfare, coordination des activités, animation des répétitions, conduite des prestations',
            ],
            [
                'email' => 'hassou.tchankpan@example.com',
                'position' => 'general_secretary',  // ✅ Clé correcte
                'responsibilities' => 'Gestion administrative, secrétariat, correspondances, comptes rendus, archivage',
            ],
            [
                'email' => 'bienvenu.kpodo@example.com',
                'position' => 'treasurer',  // ✅ Clé correcte
                'responsibilities' => 'Gestion financière, suivi des cotisations, budgets, rapports financiers',
            ],
            [
                'email' => 'blaise.anagonou@example.com',
                'position' => 'conductor',  // ✅ Clé correcte (Surveillant Général)
                'responsibilities' => 'Supervision des répétitions, discipline, coordination des sections',
            ],
            [
                'email' => 'aime.zounde@example.com',
                'position' => 'deputy_conductor',  // ✅ Clé correcte (Surveillant Adjoint)
                'responsibilities' => 'Assistance au surveillant général, suivi des présences, discipline',
            ],
            [
                'email' => 'clement.sehou@example.com',
                'position' => 'organisateur',  // ✅ Clé correcte
                'responsibilities' => 'Organisation des événements, logistique, coordination des prestations',
            ],
            [
                'email' => 'celestin.kotcho@example.com',
                'position' => 'conseiller',  // ✅ Clé correcte
                'responsibilities' => 'Conseil et accompagnement du bureau, orientation stratégique',
            ],
        ];

        $positionOrder = CommitteeMember::getPositionOrder();

        foreach ($committeeStructure as $data) {
            $member = Member::where('email', $data['email'])->first();
            
            if ($member) {
                CommitteeMember::create([
                    'member_id' => $member->id,
                    'position' => $data['position'],
                    'position_order' => $positionOrder[$data['position']] ?? 99,
                    'start_date' => now()->subMonths(6),
                    'is_current' => true,
                    'responsibilities' => $data['responsibilities'] ?? null,
                ]);
                
                $this->command->info("✅ Ajout de {$member->full_name} au poste de " . 
                    CommitteeMember::getPositions()[$data['position']]);
            } else {
                $this->command->warn("⚠️ Membre non trouvé pour l'email: {$data['email']}");
            }
        }

        $this->command->info('✅ Comité initialisé avec succès!');
    }
}