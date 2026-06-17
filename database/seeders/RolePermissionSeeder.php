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
        // Si les tables Spatie ne sont pas créées (cas actuel), on skip pour que
        // le reste des seeders puisse remplir la base.
        if (! $this->hasSpatieTables()) {
            return;
        }

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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $president = Role::firstOrCreate(['name' => 'president']);
        $treasurer = Role::firstOrCreate(['name' => 'treasurer']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);
        $conductor = Role::firstOrCreate(['name' => 'conductor']);
        $sectionLeader = Role::firstOrCreate(['name' => 'section-leader']);
        $member = Role::firstOrCreate(['name' => 'member']);

        // Assigner les permissions
        $superAdmin->syncPermissions(Permission::all());

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
            'view documents', 'upload documents'
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
        // Au minimum on a besoin de permissions/roles si tu utilises les classes Spatie.
        try {
            return DB::selectOne("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name IN ('permissions','roles') LIMIT 1") !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

