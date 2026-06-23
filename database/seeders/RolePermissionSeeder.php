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

        // ============================================
        // DÉFINITION DES PERMISSIONS
        // ============================================
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

            // Gestion du contenu du site
            'manage pages',
            'manage carousels',
            'manage testimonials',
            'manage news',

            // Administration système
            'manage roles',
            'view logs',
            'manage settings',

            'manage documents',
        ];

        // Créer toutes les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ============================================
        // CRÉATION DES RÔLES
        // ============================================
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $president = Role::firstOrCreate(['name' => 'president']);
        $treasurer = Role::firstOrCreate(['name' => 'treasurer']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);
        $conductor = Role::firstOrCreate(['name' => 'conductor']);
        $sectionLeader = Role::firstOrCreate(['name' => 'section-leader']);
        $member = Role::firstOrCreate(['name' => 'member']);

        // ============================================
        // ASSIGNATION DES PERMISSIONS PAR RÔLE
        // ============================================

        // --- SUPER ADMIN (Accès total) ---
        $superAdmin->syncPermissions(Permission::all());

        // --- ADMIN (Accès complet sauf quelques permissions sensibles) ---
        $admin->syncPermissions([
            // Membres
            'view members', 'create members', 'edit members', 'delete members', 'approve members',
            // Finances
            'view finances', 'create transactions', 'approve transactions', 'export reports',
            // Événements
            'view events', 'create events', 'edit events', 'delete events', 'manage participations',
            // Bureau
            'view committee', 'manage committee',
            // Documents
            'view documents', 'upload documents', 'delete documents',
            // Contenu du site
            'manage pages', 'manage carousels', 'manage testimonials', 'manage news',
            // Système
            'manage settings', 'view logs',
            'manage documents',
        ]);

        // --- PRÉSIDENT (Accès large, sauf suppression et paramètres système) ---
        $president->syncPermissions([
            // Membres
            'view members', 'approve members',
            // Finances
            'view finances', 'approve transactions',
            // Événements
            'view events', 'create events', 'edit events',
            // Bureau
            'view committee', 'manage committee',
            // Documents
            'view documents', 'upload documents',
            // Contenu du site - Gestion des pages uniquement
            'manage pages',
            // Système
            'view logs',
            'manage documents'
        ]);

        // --- SECRÉTAIRE (Gestion du contenu et des membres) ---
        $secretary->syncPermissions([
            // Membres
            'view members', 'create members', 'edit members',
            // Événements
            'view events', 'create events', 'edit events', 'manage participations',
            // Bureau
            'view committee',
            // Documents
            'view documents', 'upload documents',
            // Contenu du site - Accès complet
            'manage pages', 'manage carousels', 'manage testimonials', 'manage news',
            // Système - Accès aux paramètres
            'manage settings',
            'manage documents',
        ]);

        // --- TRÉSORIER (Gestion financière uniquement) ---
        $treasurer->syncPermissions([
            'view members',
            'view finances', 'create transactions', 'export reports',
            'view events',
            'view documents'
        ]);

        // --- CHEF DE FANFARE ---
        $conductor->syncPermissions([
            'view members', 'edit members',
            'view events', 'create events', 'edit events', 'manage participations',
            'view documents'
        ]);

        // --- RESPONSABLE DE SECTION ---
        $sectionLeader->syncPermissions([
            'view members',
            'view events', 'manage participations'
        ]);

        // --- MEMBRE SIMPLE ---
        $member->syncPermissions([
            'view events'
        ]);

        // ============================================
        // CRÉATION DES UTILISATEURS PAR DÉFAUT
        // ============================================

        // 1. Super Administrateur
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@fanfareceleste.org'],
            [
                'name' => 'Super Administrateur',
                'password' => bcrypt('Admin123!'),
                'email_verified_at' => now()
            ]
        );
        $superAdminUser->assignRole('super-admin');

        // 2. Administrateur
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@fanfare.com'],
            [
                'name' => 'Administrateur',
                'password' => bcrypt('Admin123!'),
                'email_verified_at' => now()
            ]
        );
        $adminUser->assignRole('admin');

        // 3. Président
        $presidentUser = User::firstOrCreate(
            ['email' => 'president@fanfareceleste.org'],
            [
                'name' => 'Président',
                'password' => bcrypt('President123!'),
                'email_verified_at' => now()
            ]
        );
        $presidentUser->assignRole('president');

        // 4. Secrétaire
        $secretaryUser = User::firstOrCreate(
            ['email' => 'secretaire@fanfareceleste.org'],
            [
                'name' => 'Secrétaire',
                'password' => bcrypt('Secret123!'),
                'email_verified_at' => now()
            ]
        );
        $secretaryUser->assignRole('secretary');

        // 5. Trésorier
        $treasurerUser = User::firstOrCreate(
            ['email' => 'tresorier@fanfareceleste.org'],
            [
                'name' => 'Trésorier',
                'password' => bcrypt('Tresorier123!'),
                'email_verified_at' => now()
            ]
        );
        $treasurerUser->assignRole('treasurer');

        // 6. Chef de fanfare
        $conductorUser = User::firstOrCreate(
            ['email' => 'chef@fanfareceleste.org'],
            [
                'name' => 'Chef de fanfare',
                'password' => bcrypt('Chef123!'),
                'email_verified_at' => now()
            ]
        );
        $conductorUser->assignRole('conductor');

        // 7. Membre simple
        $memberUser = User::firstOrCreate(
            ['email' => 'membre@fanfareceleste.org'],
            [
                'name' => 'Membre',
                'password' => bcrypt('Membre123!'),
                'email_verified_at' => now()
            ]
        );
        $memberUser->assignRole('member');

        $this->command->info('✅ Rôles et permissions créés avec succès !');
        $this->command->info('📋 Utilisateurs par défaut :');
        $this->command->info('   - Super Admin: admin@fanfareceleste.org / Admin123!');
        $this->command->info('   - Admin: admin@fanfare.com / Admin123!');
        $this->command->info('   - Président: president@fanfareceleste.org / President123!');
        $this->command->info('   - Secrétaire: secretaire@fanfareceleste.org / Secret123!');
        $this->command->info('   - Trésorier: tresorier@fanfareceleste.org / Tresorier123!');
        $this->command->info('   - Chef: chef@fanfareceleste.org / Chef123!');
        $this->command->info('   - Membre: membre@fanfareceleste.org / Membre123!');
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