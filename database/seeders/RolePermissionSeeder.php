<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (! $this->hasSpatieTables()) {
            return;
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions existantes + nouvelles pour le contenu
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

            // Gestion du contenu (NOUVEAU)
            'manage pages',
            'manage carousels',
            'manage testimonials',
            'manage news',

            // Administration
            'manage roles',
            'view logs',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $president = Role::firstOrCreate(['name' => 'president']);
        $treasurer = Role::firstOrCreate(['name' => 'treasurer']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);
        $conductor = Role::firstOrCreate(['name' => 'conductor']);
        $sectionLeader = Role::firstOrCreate(['name' => 'section-leader']);
        $member = Role::firstOrCreate(['name' => 'member']);

        // Assigner les permissions
        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'view members', 'create members', 'edit members', 'delete members', 'approve members',
            'view finances', 'create transactions', 'approve transactions', 'export reports',
            'view events', 'create events', 'edit events', 'delete events', 'manage participations',
            'view committee', 'manage committee',
            'view documents', 'upload documents', 'delete documents',
            'manage pages', 'manage carousels', 'manage testimonials', 'manage news',
            'manage settings'
        ]);

        $president->syncPermissions([
            'view members', 'approve members',
            'view finances', 'approve transactions',
            'view events', 'create events', 'edit events',
            'view committee', 'manage committee',
            'view documents', 'upload documents',
            'view logs'
        ]);

        $treasurer->syncPermissions([
            'view members',
            'view finances', 'create transactions', 'export reports',
            'view events',
            'view documents'
        ]);

        $secretary->syncPermissions([
            'view members', 'create members', 'edit members',
            'view events', 'create events', 'edit events', 'manage participations',
            'view committee',
            'view documents', 'upload documents',
            'manage pages', 'manage carousels', 'manage testimonials', 'manage news'
        ]);

        $conductor->syncPermissions([
            'view members', 'edit members',
            'view events', 'create events', 'edit events', 'manage participations',
            'view documents'
        ]);

        $sectionLeader->syncPermissions([
            'view members',
            'view events', 'manage participations'
        ]);

        $member->syncPermissions([
            'view events'
        ]);

        // Créer un super administrateur par défaut
        $admin = User::firstOrCreate(
            ['email' => 'admin@fanfareceleste.org'],
            ['name' => 'Super Administrateur', 'password' => bcrypt('Admin123!'), 'email_verified_at' => now()]
        );
        $admin->assignRole('super-admin');

        // Créer un administrateur simple
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@fanfare.com'],
            ['name' => 'Administrateur', 'password' => bcrypt('Admin123!'), 'email_verified_at' => now()]
        );
        $adminUser->assignRole('admin');

        // Créer un président par défaut
        $presidentUser = User::firstOrCreate(
            ['email' => 'president@fanfareceleste.org'],
            ['name' => 'Président', 'password' => bcrypt('President123!'), 'email_verified_at' => now()]
        );
        $presidentUser->assignRole('president');

        // Créer un trésorier par défaut
        $treasurerUser = User::firstOrCreate(
            ['email' => 'tresorier@fanfareceleste.org'],
            ['name' => 'Trésorier', 'password' => bcrypt('Tresorier123!'), 'email_verified_at' => now()]
        );
        $treasurerUser->assignRole('treasurer');
    }

    private function hasSpatieTables(): bool
    {
        try {
            return DB::selectOne("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name IN ('permissions','roles') LIMIT 1") !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }
}