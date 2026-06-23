<?php
// database/seeders/CreateMembersForAdminsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;

class CreateMembersForAdminsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Création des membres pour les administrateurs...');
        
        $adminEmails = [
            'admin@fanfareceleste.org',
            'admin@fanfare.com',
            'membre@fanfareceleste.org'
        ];
        
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->command->error("❌ Utilisateur non trouvé: {$email}");
                continue;
            }
            
            if ($user->member_id) {
                $this->command->warn("⚠️ {$user->name} a déjà un membre lié.");
                continue;
            }
            
            // Utiliser le timestamp pour un numéro unique
            $uniqueSuffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $memberNumber = 'FC-' . date('Y') . '-' . $uniqueSuffix;
            
            // S'assurer que le numéro est unique
            while (Member::where('member_number', $memberNumber)->exists()) {
                $uniqueSuffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $memberNumber = 'FC-' . date('Y') . '-' . $uniqueSuffix;
            }
            
            $columns = [
                'first_name' => $user->name,
                'last_name' => 'Admin',
                'email' => $user->email,
                'phone' => '+229 97 00 00 00',
                'member_number' => $memberNumber,
                'status' => 'active',
                'entry_date' => now(),
                'birth_date' => '2000-01-01',
                'address' => 'Non renseigné',
                'birth_place' => 'Non renseigné',
                'instrument' => 'Non renseigné',
                'instrument_level' => 'debutant',
                'section' => 'Non renseigné',
                'section_role' => 'Membre',
                'photo' => null,
                'id_document' => null,
                'emergency_contact_name' => null,
                'emergency_contact_phone' => null,
                'medical_notes' => null,
                'skills' => null,
                'notes' => null,
            ];
            
            try {
                $member = Member::create($columns);
                
                $user->member_id = $member->id;
                $user->save();
                
                $this->command->info("✅ Créé et lié: {$user->name} → Membre #{$member->member_number}");
            } catch (\Exception $e) {
                $this->command->error("❌ Erreur pour {$user->name}: " . $e->getMessage());
                continue;
            }
        }
        
        $this->command->info('Terminé !');
    }
}