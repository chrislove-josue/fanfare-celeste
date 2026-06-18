<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    private function generateUniqueMemberNumber(): string
    {
        $tries = 50;
        while ($tries-- > 0) {
            $candidate = Member::generateMemberNumber();
            $exists = Member::where('member_number', $candidate)->exists();
            if (! $exists) {
                return $candidate;
            }
        }

        // En dernier recours (cas très improbable), fallback sur un suffixe aléatoire.
        $year = date('Y');
        $random = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        return 'FC-' . $year . '-' . $random;
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

    public function run(): void
    {
        // Membres du bureau
        $committeeMembers = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Mendy',
                'email' => 'president@fanfareceleste.org',
                'phone' => '+22997000001',
                'position' => 'Président',
                'instrument' => 'Trompette',
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Dossou',
                'email' => 'tresorier@fanfareceleste.org',
                'phone' => '+22997000002',
                'position' => 'Trésorier',
                'instrument' => 'Percussion',
            ],
            [
                'first_name' => 'Paul',
                'last_name' => 'Zinsou',
                'email' => 'secretaire@fanfareceleste.org',
                'phone' => '+22997000003',
                'position' => 'Secrétaire général',
                'instrument' => 'Saxophone',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Agossou',
                'email' => 'chef@fanfareceleste.org',
                'phone' => '+22997000004',
                'position' => 'Chef de fanfare',
                'instrument' => 'Trompette',
            ],
        ];

        foreach ($committeeMembers as $data) {
            // Évite les doublons si on relance des seeders.
            $member = Member::updateOrCreate(
                ['email' => $data['email']],
                [
                    // Génération automatique et unique du member_number
                    // (utilisée si l'email n'existe pas encore)
                    'member_number' => Member::where('email', $data['email'])->exists()
                        ? (string) Member::where('email', $data['email'])->value('member_number')
                        : $this->generateUniqueMemberNumber(),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'address' => 'Cotonou, Bénin',
                    'birth_date' => '1980-01-01',
                    'instrument' => $data['instrument'],
                    'entry_date' => '2010-01-01',
                    'section' => $data['instrument'] === 'Percussion' ? 'Percussions' : 'Cuivres',
                    'status' => 'active',
                ]
            );

            // Certaines bases n'ont pas la colonne member_id sur users.
            // On la renseigne uniquement si elle existe.
            $userPayload = [
                'name' => $member->full_name,
                'password' => Hash::make('password123'),
            ];

            // La colonne member_id n'existe pas toujours dans users.
            // Dans ce projet, si elle n'existe pas, on ne l'injecte pas.
            if ($this->columnExistsOnUsers('member_id')) {
                $userPayload['member_id'] = $member->id;
            }

            User::firstOrCreate(
                ['email' => $member->email],
                $userPayload
            );
        }

        // Membres normaux
        $sections = ['Trompettes', 'Trombones', 'Saxophones', 'Percussions'];
        $instruments = ['Trompette', 'Trombone', 'Saxophone Alto', 'Batterie', 'Caisse Claire'];
        $firstNames = ['Marc', 'Luc', 'Jean', 'Pierre', 'André', 'Philippe', 'Thomas', 'Nicolas', 'François', 'Michel'];
        $lastNames = ['Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez'];

        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $section = $sections[array_rand($sections)];
            $instrument = $instruments[array_rand($instruments)];
            
            $email = strtolower($firstName . '.' . $lastName . '@example.com');

            // Clé d'idempotence: email unique sur members.
            // member_number sera généré uniquement si le membre n'existe pas encore.
            $existingMemberNumber = Member::where('email', $email)->value('member_number');

            $member = Member::updateOrCreate(
                ['email' => $email],
                [
                    'member_number' => $existingMemberNumber ?: $this->generateUniqueMemberNumber(),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => '+229' . rand(90000000, 99999999),
                    'address' => 'Cotonou, Bénin',
                    'birth_date' => fake()->dateTimeBetween('1980-01-01', '2000-12-31')->format('Y-m-d'),
                    'instrument' => $instrument,
                    'instrument_level' => fake()->randomElement(['debutant', 'intermediaire', 'avance', 'expert']),
                    'entry_date' => fake()->dateTimeBetween('2015-01-01', '2024-12-31')->format('Y-m-d'),
                    'section' => $section,
                    'status' => fake()->randomElement(['active', 'active', 'active', 'active', 'training']),
                    'emergency_contact_name' => 'Contact Urgence',
                    'emergency_contact_phone' => '+229' . rand(90000000, 99999999),
                ]
            );


            if ($member->status === 'active') {
                $userPayload = [
                    'name' => $member->full_name,
                    'email' => $member->email,
                    'password' => Hash::make('password123'),
                ];

                if ($this->columnExistsOnUsers('member_id')) {
                    $userPayload['member_id'] = $member->id;
                }

                User::firstOrCreate(
                    ['email' => $member->email],
                    $userPayload
                );
            }

        }
    }
}