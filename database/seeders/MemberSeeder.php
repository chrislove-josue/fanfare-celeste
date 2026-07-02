<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    private function generateUniqueMemberNumber(): string
    {
        // Récupérer le dernier numéro utilisé
        $lastMember = Member::where('member_number', 'LIKE', 'FCTDA-%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastMember) {
            // Extraire le numéro et l'incrémenter
            $lastNumber = (int) str_replace('FCTDA-', '', $lastMember->member_number);
            $newNumber = $lastNumber + 1;
        } else {
            // Commencer à 1 s'il n'y a pas de membre
            $newNumber = 1;
        }
        
        // S'assurer que le numéro est unique (au cas où)
        $tries = 50;
        while ($tries-- > 0) {
            // Formatage avec 3 chiffres (001, 002, 003, ...)
            $candidate = 'FCTDA-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $exists = Member::where('member_number', $candidate)->exists();
            if (! $exists) {
                return $candidate;
            }
            $newNumber++;
        }
        
        // En dernier recours, utiliser un timestamp
        return 'FCTDA-' . str_pad(time(), 3, '0', STR_PAD_LEFT);
    }

    private function columnExistsOnUsers(string $column): bool
    {
        // Vérifie si la colonne existe sur la table users.
        // Compatible MySQL.
        try {
            $result = 
                \DB::table('information_schema.columns')
                    ->where('table_schema', env('DB_DATABASE'))
                    ->where('table_name', 'users')
                    ->where('column_name', $column)
                    ->exists();

            return $result;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function createUserWithRole(array $userData, string $role = 'member'): void
    {
        // Vérifier que l'email existe
        if (!isset($userData['email'])) {
            throw new \Exception('L\'email est requis pour créer un utilisateur');
        }

        // Créer ou récupérer l'utilisateur
        $user = User::firstOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'] ?? $userData['email'],
                'password' => Hash::make($userData['password'] ?? 'password123'),
            ]
        );

        // Assigner le rôle si la table roles existe
        try {
            // Vérifier si la table roles existe
            $tableExists = \DB::table('information_schema.tables')
                ->where('table_schema', env('DB_DATABASE'))
                ->where('table_name', 'roles')
                ->exists();

            if ($tableExists) {
                // Récupérer l'ID du rôle
                $roleRecord = \DB::table('roles')->where('name', $role)->first();
                
                if ($roleRecord) {
                    // Vérifier si l'assignation existe déjà
                    $exists = \DB::table('role_user')
                        ->where('user_id', $user->id)
                        ->where('role_id', $roleRecord->id)
                        ->exists();

                    if (!$exists) {
                        \DB::table('role_user')->insert([
                            'user_id' => $user->id,
                            'role_id' => $roleRecord->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignorer les erreurs si la table roles n'existe pas
        }

        // Si la colonne member_id existe sur users, on l'ajoute
        if ($this->columnExistsOnUsers('member_id') && isset($userData['member_id'])) {
            $user->member_id = $userData['member_id'];
            $user->save();
        }
    }

    private function formatAddress(string $parish, string $subRegion): string
    {
        return sprintf(
            'Paroisse: %s | Sous-région: %s',
            $parish,
            $subRegion
        );
    }

    public function run(): void
    {
        // 1. CRÉATION DU SUPERADMIN
        $this->createUserWithRole([
            'name' => 'Super Admin',
            'email' => 'superadmin@fanfareceleste.org',
            'password' => 'Admin123!',
        ], 'superadmin');

        // 2. CRÉATION DE L'ADMIN
        $this->createUserWithRole([
            'name' => 'Admin Fanfare',
            'email' => 'admin@fanfareceleste.org',
            'password' => 'Admin123!',
        ], 'admin');

        // Membres du bureau (membres importants)
        $committeeMembers = [
            [
                'first_name' => 'Marius',
                'last_name' => 'ADOLIWOSSI',
                'email' => 'mariusadoliwossi@gmail.com',
                'phone' => '+22995614766',
                'position' => 'Maître / Chef de fanfare',
                'instrument' => 'Saxophone',
                'birth_date' => '1990-11-08',
                'birth_place' => 'Tangbo-Djèvié',
                'parish' => 'St Michel Sohoun Centre',
                'sub_region' => 'Allada C/Sékou',
                'entry_date' => '2010-01-01',
                'section' => 'Cuivres',
                'status' => 'active',
                // Le member_number sera généré automatiquement
                'role' => 'admin'
            ],
        ];

        foreach ($committeeMembers as $data) {
            // Formater l'adresse avec la paroisse et la sous-région
            $address = $this->formatAddress(
                $data['parish'] ?? 'Non spécifié',
                $data['sub_region'] ?? 'Non spécifié'
            );

            // Générer le member_number automatiquement
            $memberNumber = $this->generateUniqueMemberNumber();

            $member = Member::create([
                'member_number' => $memberNumber,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $address,
                'birth_date' => $data['birth_date'] ?? '1980-01-01',
                'birth_place' => $data['birth_place'] ?? null,
                'instrument' => $data['instrument'],
                'entry_date' => $data['entry_date'] ?? '2010-01-01',
                'section' => $data['section'] ?? 'Cuivres',
                'status' => $data['status'] ?? 'active',
            ]);

            // Créer l'utilisateur avec le rôle spécifié
            $this->createUserWithRole([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => 'password123',
                'member_id' => $member->id,
            ], $data['role'] ?? 'member');
        }

        // Membres normaux à partir du document PDF
        $membersFromPDF = [
            [
                'first_name' => 'Aimé',
                'last_name' => 'ZOUNDE',
                'phone' => '+22969337597',
                'birth_date' => '1996-08-05',
                'birth_place' => 'Sékou',
                'parish' => 'St Raphael Zoundétomin',
                'sub_region' => 'Tori Bossito A',
                'instrument' => 'Tuba',
                'section' => 'Cuivres',
                'email' => 'aime.zounde@example.com'
            ],
            [
                'first_name' => 'Clément',
                'last_name' => 'LOKONON',
                'phone' => '+22962709162',
                'birth_date' => '1992-05-25',
                'birth_place' => 'Zè',
                'parish' => 'St Jean Sèhè 2',
                'sub_region' => 'Allada C/Sèkou',
                'instrument' => 'Caisse claire',
                'section' => 'Percussions',
                'email' => 'clement.lokonon@example.com'
            ],
            [
                'first_name' => 'Sabin',
                'last_name' => 'HOUNSOUGBO',
                'phone' => '+22997348392',
                'birth_date' => '1982-09-03',
                'birth_place' => 'Toffo',
                'parish' => 'Bon Samaritin',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'sabin.hounsougbo@example.com'
            ],
            [
                'first_name' => 'Clément',
                'last_name' => 'KPONDJI',
                'phone' => '+22967412954',
                'birth_date' => '1993-09-03',
                'birth_place' => 'Adjan',
                'parish' => 'St Michel Sohoun-Hô',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'clement.kpondji@example.com'
            ],
            [
                'first_name' => 'Jérémie',
                'last_name' => 'SOUNNE',
                'phone' => '+22962670980',
                'birth_date' => '1992-07-23',
                'birth_place' => 'Tangbo-Djèvié',
                'parish' => 'St Michel Sohoun',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'jeremie.sounne@example.com'
            ],
            [
                'first_name' => 'Guy',
                'last_name' => 'DANSOU',
                'phone' => '+22994955402',
                'birth_date' => '1981-01-01',
                'birth_place' => 'Sékou',
                'parish' => 'Sèhè 1',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Cymbale',
                'section' => 'Percussions',
                'email' => 'guy.dansou@example.com'
            ],
            [
                'first_name' => 'Bienvenu',
                'last_name' => 'KPODO',
                'phone' => '+22996881527',
                'birth_date' => '1987-06-04',
                'birth_place' => 'Allada',
                'parish' => 'St Raphael Simanhotomin',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'bienvenu.kpodo@example.com'
            ],
            [
                'first_name' => 'Clément',
                'last_name' => 'SEHOU',
                'phone' => '+22951021356',
                'birth_date' => '1997-05-12',
                'birth_place' => 'Adjan',
                'parish' => 'Mont Sinai Hollansatin',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Cornet',
                'section' => 'Cuivres',
                'email' => 'clement.sehou@example.com'
            ],
            [
                'first_name' => 'Hassou Gilbert',
                'last_name' => 'TCHANKPAN',
                'phone' => '+22995912152',
                'birth_date' => '1992-12-28',
                'birth_place' => 'Dodji-Bata',
                'parish' => 'Akpatchamin 2',
                'sub_region' => 'Zè/G',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'hassou.tchankpan@example.com'
            ],
            [
                'first_name' => 'Blaise',
                'last_name' => 'ANAGONOU',
                'phone' => '+22996956401',
                'birth_date' => '1990-04-01',
                'birth_place' => 'Hounsa',
                'parish' => 'Alpha Oméga Agbandonou',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'blaise.anagonou@example.com'
            ],
            [
                'first_name' => 'Jacques',
                'last_name' => 'ZANNOU',
                'phone' => '+22997629603',
                'birth_date' => '1985-01-02',
                'birth_place' => 'Dodji-Bata',
                'parish' => 'Adjan Centre',
                'sub_region' => 'Zè/H',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'jacques.zannou@example.com'
            ],
            [
                'first_name' => 'François',
                'last_name' => 'ADJIDE',
                'phone' => '+22965708733',
                'birth_date' => '1994-01-25',
                'birth_place' => 'Kpanroun',
                'parish' => 'St Michel Goulou',
                'sub_region' => 'Zè/A',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'francois.adjide@example.com'
            ],
            [
                'first_name' => 'Jacques',
                'last_name' => 'SIMAHO',
                'phone' => '+22952441389',
                'birth_date' => '1994-09-19',
                'birth_place' => 'Allada',
                'parish' => 'St Raphael Simahotomin',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'jacques.simaho@example.com'
            ],
            [
                'first_name' => 'Célestin',
                'last_name' => 'KOTCHO',
                'phone' => '+22965404653',
                'birth_date' => '1990-01-01',
                'birth_place' => 'Allada',
                'parish' => 'Mont Sinai Hollansatin',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'celestin.kotcho@example.com'
            ],
            [
                'first_name' => 'Moise Dossou',
                'last_name' => 'TINYEMON',
                'phone' => '+22969126669',
                'birth_date' => '1998-12-12',
                'birth_place' => 'Akassato',
                'parish' => 'Jehova Jireh Golo Lohoussa',
                'sub_region' => 'Golo-Djigbé A',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'moise.tinyemon@example.com'
            ],
            [
                'first_name' => 'Olivier',
                'last_name' => 'KANDJETE',
                'phone' => '+22990424971',
                'birth_date' => '1997-07-20',
                'birth_place' => 'Adjan',
                'parish' => 'St Michel Sohoun Ho',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trombone',
                'section' => 'Cuivres',
                'email' => 'olivier.kandjete@example.com'
            ],
            [
                'first_name' => 'Abel',
                'last_name' => 'AFFANOU',
                'phone' => '+22994691826',
                'birth_date' => '1983-01-01',
                'birth_place' => 'Abomey-Calavi',
                'parish' => 'St Samuel Dodji-Aliho',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'abel.affanou@example.com'
            ],
            [
                'first_name' => 'Isaac',
                'last_name' => 'AKOTO',
                'phone' => '+22969440934',
                'birth_date' => '1993-11-20',
                'birth_place' => 'Wawata',
                'parish' => 'St Michel Sohoun',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Caisse claire',
                'section' => 'Percussions',
                'email' => 'isaac.akoto@example.com'
            ],
            [
                'first_name' => 'Emile',
                'last_name' => 'ANAGONOU',
                'phone' => '+22996310775',
                'birth_date' => '1995-01-01',
                'birth_place' => 'Non spécifié',
                'parish' => 'Alpha Oméga Agbandonou',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Grosse Caisse',
                'section' => 'Percussions',
                'email' => 'emile.anagonou@example.com'
            ],
            [
                'first_name' => 'KEGNIDE Epiphane',
                'last_name' => 'HODONOU',
                'phone' => '+22999912288',
                'birth_date' => '1997-05-11',
                'birth_place' => 'Akassato',
                'parish' => 'Agondotan Centre',
                'sub_region' => 'Zè/C',
                'instrument' => 'Caisse claire',
                'section' => 'Percussions',
                'email' => 'epiphane.hodonou@example.com'
            ],
            [
                'first_name' => 'Désiré',
                'last_name' => 'BOSSOU',
                'phone' => '+22962390233',
                'birth_date' => '1986-08-08',
                'birth_place' => 'Abomey-Calavi',
                'parish' => 'Finagnon Tankpa',
                'sub_region' => 'Allada C/Sékou',
                'instrument' => 'Cymbal',
                'section' => 'Percussions',
                'email' => 'desire.bossou@example.com'
            ],
            [
                'first_name' => 'Mawunyon Elisée',
                'last_name' => 'ZOUNDE',
                'phone' => '+22996235453',
                'birth_date' => '1998-06-14',
                'birth_place' => 'Konouhoué',
                'parish' => 'Terre des Anges Zoundétomey',
                'sub_region' => 'Tori Cada',
                'instrument' => 'Trompette',
                'section' => 'Cuivres',
                'email' => 'mawunyon.zounde@example.com'
            ],
        ];

        foreach ($membersFromPDF as $data) {
            // Formater l'adresse avec la paroisse et la sous-région
            $address = $this->formatAddress(
                $data['parish'] ?? 'Non spécifié',
                $data['sub_region'] ?? 'Non spécifié'
            );

            // Générer le member_number automatiquement
            $memberNumber = $this->generateUniqueMemberNumber();

            $member = Member::create([
                'member_number' => $memberNumber,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $address,
                'birth_date' => $data['birth_date'],
                'birth_place' => $data['birth_place'] ?? null,
                'instrument' => $data['instrument'],
                'entry_date' => '2010-01-01',
                'section' => $data['section'],
                'status' => 'active',
            ]);

            // Créer l'utilisateur associé avec le rôle 'member'
            if ($member->status === 'active') {
                $this->createUserWithRole([
                    'name' => $member->full_name,
                    'email' => $member->email,
                    'password' => 'password123',
                    'member_id' => $member->id,
                ], 'member');
            }
        }

        $this->command->info('✅ Seeders exécutés avec succès!');
        $this->command->info('👤 SuperAdmin: superadmin@fanfareceleste.org / Admin123!');
        $this->command->info('👤 Admin: admin@fanfareceleste.org / Admin123!');
        $this->command->info('👤 Membres: email@example.com / password123');
        $this->command->info('📝 Les numéros de membres sont au format FCTDA-001, FCTDA-002, ... (auto-incrémenté)');
    }
}