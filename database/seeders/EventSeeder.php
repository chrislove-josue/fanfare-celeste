<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $responsible = User::where('email', 'chef@fanfareceleste.org')->first();
        
        $events = [
            [
                'title' => 'Concert de Gala',
                'description' => 'Grand concert de gala de la Fanfare Céleste',
                'type' => 'concert',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15)->addHours(3),
                'location' => 'Palais des Congrès, Cotonou',
                'budget' => 1500000,
            ],
            [
                'title' => 'Animation Mariage Royal',
                'description' => 'Animation musicale pour un mariage prestigieux',
                'type' => 'entertainment',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7)->addHours(4),
                'location' => 'Hôtel de la Plage, Cotonou',
                'budget' => 500000,
            ],
            [
                'title' => 'Répétition Générale',
                'description' => 'Répétition pour le concert à venir',
                'type' => 'rehearsal',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(3)->addHours(2),
                'location' => 'Salle de répétition, Église Céleste',
                'budget' => 0,
            ],
            [
                'title' => 'Campagne Évangélique',
                'description' => 'Campagne d\'évangélisation avec musique',
                'type' => 'evangelical_campaign',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(32)->addHours(5),
                'location' => 'Stade Municipal, Porto-Novo',
                'budget' => 800000,
            ],
        ];

        foreach ($events as $event) {
            Event::create(array_merge($event, [
                'responsible_id' => $responsible->id,
                'status' => 'planned',
            ]));
        }
    }
}