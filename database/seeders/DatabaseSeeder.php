<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. D'abord les rôles et permissions (sans liaison membre)
            RolePermissionSeeder::class,
            
            // 2. Ensuite les membres
            MemberSeeder::class,
            
            // 3. Puis les événements
            EventSeeder::class,
            
            // 4. Enfin, lier les utilisateurs aux membres
            LinkUsersToMembersSeeder::class,
            
            // 5. Créer les administrateurs
            CreateMembersForAdminsSeeder::class,
            
            // 6. Remplir la galerie
            GallerySeeder::class,
            
            PartnerSeeder::class,

            CarouselSeeder::class,
        ]);
    }
}