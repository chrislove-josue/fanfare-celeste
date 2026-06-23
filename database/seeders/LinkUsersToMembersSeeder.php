<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;

class LinkUsersToMembersSeeder extends Seeder
{
    public function run()
    {
        // Lier les utilisateurs aux membres par email
        $users = User::all();
        
        foreach ($users as $user) {
            // Chercher un membre avec le même email
            $member = Member::where('email', $user->email)->first();
            
            if ($member && !$user->member_id) {
                $user->member_id = $member->id;
                $user->save();
                
                $this->command->info("Lié l'utilisateur {$user->name} au membre {$member->full_name}");
            }
        }
        
        $this->command->info('Liaison utilisateurs-membres terminée !');
    }
}