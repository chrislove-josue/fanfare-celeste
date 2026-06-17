<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
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
            $member = Member::create([
                'member_number' => Member::generateMemberNumber(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => 'Cotonou, Bénin',
                'birth_date' => '1980-01-01',
                'instrument' => $data['instrument'],
                'entry_date' => '2010-01-01',
                'section' => $data['instrument'] === 'Percussion' ? 'Percussions' : 'Cuivres',
                'status' => 'active',
            ]);

            User::create([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => Hash::make('password123'),
                'member_id' => $member->id,
            ]);
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
            
            $member = Member::create([
                'member_number' => Member::generateMemberNumber(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . '@example.com'),
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
            ]);

            if ($member->status === 'active') {
                User::create([
                    'name' => $member->full_name,
                    'email' => $member->email,
                    'password' => Hash::make('password123'),
                    'member_id' => $member->id,
                ]);
            }
        }
    }
}