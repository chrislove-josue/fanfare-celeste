<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser les rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Gestion des membres
            'view members',
            'create members',
            'edit members',
            'delete members',
            'approve members',
            
            // Gestion financière
            'view finances',
            'create transactions',
            'approve transactions',
            'export reports',
            
            // Gestion des événements
            'view events',
            'create events',
            'edit events',
            'delete events',
            'manage participations',
            
            // Gestion du bureau
            'view committee',
            'manage committee',
            
            // Gestion des documents
            'view documents',
            'upload documents',
            'delete documents',
            
            // Administration
            'manage roles',
            'view logs',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $president = Role::create(['name' => 'president']);
        $treasurer = Role::create(['name' => 'treasurer']);
        $secretary = Role::create(['name' => 'secretary']);
        $conductor = Role::create(['name' => 'conductor']);
        $sectionLeader = Role::create(['name' => 'section-leader']);
        $member = Role::create(['name' => 'member']);

        // Assigner les permissions
        $superAdmin->givePermissionTo(Permission::all());
        
        $president->givePermissionTo([
            'view members', 'approve members',
            'view finances', 'approve transactions',
            'view events', 'create events', 'edit events',
            'view committee', 'manage committee',
            'view documents', 'upload documents',
            'view logs'
        ]);
        
        $treasurer->givePermissionTo([
            'view members',
            'view finances', 'create transactions', 'export reports',
            'view events',
            'view documents'
        ]);
        
        $secretary->givePermissionTo([
            'view members', 'create members', 'edit members',
            'view events', 'create events', 'edit events', 'manage participations',
            'view committee',
            'view documents', 'upload documents'
        ]);
        
        $conductor->givePermissionTo([
            'view members', 'edit members',
            'view events', 'create events', 'edit events', 'manage participations',
            'view documents'
        ]);
        
        $sectionLeader->givePermissionTo([
            'view members',
            'view events', 'manage participations'
        ]);
        
        $member->givePermissionTo([
            'view events'
        ]);

        // Créer un super administrateur par défaut
        $admin = User::create([
            'name' => 'Super Administrateur',
            'email' => 'admin@fanfareceleste.org',
            'password' => bcrypt('Admin123!'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super-admin');

        // Créer un président par défaut
        $presidentUser = User::create([
            'name' => 'Président',
            'email' => 'president@fanfareceleste.org',
            'password' => bcrypt('President123!'),
            'email_verified_at' => now(),
        ]);
        $presidentUser->assignRole('president');

        // Créer un trésorier par défaut
        $treasurerUser = User::create([
            'name' => 'Trésorier',
            'email' => 'tresorier@fanfareceleste.org',
            'password' => bcrypt('Tresorier123!'),
            'email_verified_at' => now(),
        ]);
        $treasurerUser->assignRole('treasurer');
    }
}