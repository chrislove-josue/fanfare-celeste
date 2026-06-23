<?php
// database/seeders/GallerySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Créer le dossier storage
        if (!Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->makeDirectory('galleries');
        }

        // Catégories avec leurs icônes
        $categories = [
            'concerts' => '🎵 Concerts',
            'rehearsals' => '🎶 Répétitions',
            'trophies' => '🏆 Trophées',
            'events' => '📅 Événements',
            'members' => '👥 Vie des membres',
            'ceremonies' => '⛪ Cérémonies',
            'group' => '📸 Photos de groupe',
        ];

        // Données de la galerie
        $galleryItems = [];

        // Trophées et distinctions
        for ($i = 1; $i <= 4; $i++) {
            $galleryItems[] = [
                'title' => 'Trophée d\'Excellence ' . (2023 + $i),
                'description' => 'Distinction honorifique remportée par la Fanfare Céleste pour son excellence musicale.',
                'category' => 'trophies',
                'type' => 'photo',
                'is_published' => true,
                'order' => $i,
                'metadata' => ['year' => 2023 + $i, 'event' => 'Concours National'],
            ];
        }

        // Concerts
        $concertTitles = [
            'Concert de Gala au Palais des Congrès',
            'Festival International de Musique de Cotonou',
            'Concert de Noël - Église Notre-Dame',
            'Soirée Jazz et Gospel',
            'Concert de la Réconciliation Nationale',
        ];
        foreach ($concertTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Un moment exceptionnel de partage musical avec le public.',
                'category' => 'concerts',
                'type' => 'photo',
                'is_published' => true,
                'order' => 10 + $i,
                'metadata' => ['venue' => 'Salle de concert', 'audience' => rand(200, 2000)],
            ];
        }

        // Événements
        $eventTitles = [
            'Cérémonie de remise des prix',
            'Rencontre internationale des fanfares',
            'Tournée de promotion dans le nord du Bénin',
            'Festival des Arts et Cultures',
        ];
        foreach ($eventTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Un événement marquant dans la vie de la fanfare.',
                'category' => 'events',
                'type' => 'photo',
                'is_published' => true,
                'order' => 20 + $i,
                'metadata' => ['location' => 'Bénin', 'duration' => rand(1, 5) . ' jours'],
            ];
        }

        // Vie des membres
        $memberTitles = [
            'Répétition Générale',
            'Journée Portes Ouvertes',
            'Formation Musicale',
            'Retraite Spirituelle',
            'Sortie de Promotion',
        ];
        foreach ($memberTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Des moments forts de la vie de notre communauté.',
                'category' => 'members',
                'type' => 'photo',
                'is_published' => true,
                'order' => 30 + $i,
                'metadata' => ['participants' => rand(30, 100)],
            ];
        }

        // Photos de groupe
        for ($i = 1; $i <= 5; $i++) {
            $galleryItems[] = [
                'title' => 'Photo de famille - Promotion ' . (2020 + $i),
                'description' => 'Toute la fanfare réunie pour une photo de famille.',
                'category' => 'group',
                'type' => 'photo',
                'is_published' => true,
                'order' => 40 + $i,
                'metadata' => ['year' => 2020 + $i, 'members_count' => rand(40, 80)],
            ];
        }

        // Créer les entrées
        foreach ($galleryItems as $item) {
            // Télécharger une image placeholder
            $imageUrl = $this->getRandomImage($item['category']);
            try {
                $imageContent = file_get_contents($imageUrl);
                $imageName = 'galleries/' . time() . '_' . rand(100, 999) . '.jpg';
                Storage::disk('public')->put($imageName, $imageContent);

                $item['file_path'] = $imageName;
                $item['file_name'] = basename($imageName);
                $item['file_size'] = strlen($imageContent);
                $item['mime_type'] = 'image/jpeg';
                $item['views_count'] = rand(10, 500);
                $item['likes_count'] = rand(0, 50);
                $item['created_at'] = now()->subDays(rand(1, 365));
                $item['updated_at'] = $item['created_at'];

                Gallery::create($item);
            } catch (\Exception $e) {
                $this->command->error('Erreur pour l\'image: ' . $e->getMessage());
                continue;
            }
        }

        $this->command->info('✅ Galerie remplie avec ' . count($galleryItems) . ' images !');
    }

    private function getRandomImage($category): string
    {
        // Images Unsplash par catégorie
        $images = [
            'concerts' => [
                'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800',
                'https://images.unsplash.com/photo-1507838153414-b4b713384a76?w=800',
                'https://images.unsplash.com/photo-1460881680851-30b4e771aedc?w=800',
                'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800',
            ],
            'rehearsals' => [
                'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800',
                'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800',
                'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=800',
            ],
            'trophies' => [
                'https://images.unsplash.com/photo-1533230408717-46b9393e6b26?w=800',
                'https://images.unsplash.com/photo-1518706604296-ba0529fc9e40?w=800',
                'https://images.unsplash.com/photo-1516826957135-700dedea698c?w=800',
            ],
            'events' => [
                'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800',
                'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800',
                'https://images.unsplash.com/photo-1519677100203-a0e668c92439?w=800',
            ],
            'members' => [
                'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800',
                'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800',
                'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800',
            ],
            'ceremonies' => [
                'https://images.unsplash.com/photo-1519677100203-a0e668c92439?w=800',
                'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800',
                'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
            ],
            'group' => [
                'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800',
                'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800',
                'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800',
            ],
        ];

        $categoryImages = $images[$category] ?? $images['events'];
        return $categoryImages[array_rand($categoryImages)];
    }
}