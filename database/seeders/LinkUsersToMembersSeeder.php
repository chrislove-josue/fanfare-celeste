<?php
// database/seeders/LinkUsersToMembersSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;

class LinkUsersToMembersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('🔄 Liaison utilisateurs-membres...');
        $this->command->info('========================================');
        
        $users = User::all();
        $linked = 0;
        $alreadyLinked = 0;
        $notFound = 0;
        
        foreach ($users as $user) {
            // Chercher un membre avec le même email
            $member = Member::where('email', $user->email)->first();
            
            if ($member) {
                // Vérifier si l'utilisateur n'est pas déjà lié
                if (!$user->member_id) {
                    $user->member_id = $member->id;
                    $user->save();
                    $linked++;
                    $this->command->info("✅ Lié: {$user->name} → {$member->full_name} (Membre ID: {$member->id})");
                } else {
                    $alreadyLinked++;
                    $this->command->warn("⚠️ Déjà lié: {$user->name} → Membre ID: {$member->id}");
                }
            } else {
                $notFound++;
                $this->command->error("❌ Aucun membre trouvé pour: {$user->email}");
            }
        }
        
        $this->command->info('========================================');
        $this->command->info('📊 RÉSULTAT DE LA LIAISON');
        $this->command->info('========================================');
        $this->command->info("✅ {$linked} utilisateur(s) lié(s) avec succès.");
        $this->command->info("⚠️ {$alreadyLinked} utilisateur(s) déjà lié(s).");
        $this->command->info("❌ {$notFound} utilisateur(s) sans membre correspondant.");
        $this->command->info('========================================');
        
        $this->command->info('Liaison utilisateurs-membres terminée ! 🎉');
    }
}