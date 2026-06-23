<?php
// database/seeders/GallerySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Créer le dossier storage
        if (!Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->makeDirectory('galleries');
        }

        // Images de remplacement avec des URLs validées
        $imageUrls = [
            // Images de concerts
            'https://picsum.photos/seed/concert1/800/600',
            'https://picsum.photos/seed/concert2/800/600',
            'https://picsum.photos/seed/concert3/800/600',
            'https://picsum.photos/seed/concert4/800/600',
            'https://picsum.photos/seed/concert5/800/600',
            
            // Images de trophées
            'https://picsum.photos/seed/trophy1/800/600',
            'https://picsum.photos/seed/trophy2/800/600',
            'https://picsum.photos/seed/trophy3/800/600',
            'https://picsum.photos/seed/trophy4/800/600',
            
            // Images d'événements
            'https://picsum.photos/seed/event1/800/600',
            'https://picsum.photos/seed/event2/800/600',
            'https://picsum.photos/seed/event3/800/600',
            'https://picsum.photos/seed/event4/800/600',
            
            // Images de membres
            'https://picsum.photos/seed/member1/800/600',
            'https://picsum.photos/seed/member2/800/600',
            'https://picsum.photos/seed/member3/800/600',
            'https://picsum.photos/seed/member4/800/600',
            'https://picsum.photos/seed/member5/800/600',
            
            // Images de cérémonies
            'https://picsum.photos/seed/ceremony1/800/600',
            'https://picsum.photos/seed/ceremony2/800/600',
            'https://picsum.photos/seed/ceremony3/800/600',
            
            // Photos de groupe
            'https://picsum.photos/seed/group1/800/600',
            'https://picsum.photos/seed/group2/800/600',
            'https://picsum.photos/seed/group3/800/600',
            'https://picsum.photos/seed/group4/800/600',
            'https://picsum.photos/seed/group5/800/600',
        ];

        // Données de la galerie
        $galleryItems = [];

        // Trophées et distinctions (4)
        $trophyTitles = [
            '🏆 Trophée d\'Excellence 2024',
            '🏅 Distinction du Ministère de la Culture',
            '🌟 Prix du Public - Festival International',
            '🎖️ Certificat d\'Excellence Musicale',
        ];
        foreach ($trophyTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Distinction honorifique remportée par la Fanfare Céleste pour son excellence musicale et son engagement.',
                'category' => 'trophies',
                'type' => 'photo',
                'is_published' => true,
                'order' => $i + 1,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Concerts (5)
        $concertTitles = [
            '🎵 Concert de Gala - Palais des Congrès',
            '🎶 Festival International de Musique de Cotonou',
            '🎄 Concert de Noël - Église Notre-Dame',
            '🎷 Soirée Jazz et Gospel',
            '🤝 Concert de la Réconciliation Nationale',
        ];
        foreach ($concertTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Un moment exceptionnel de partage musical avec un public nombreux et chaleureux.',
                'category' => 'concerts',
                'type' => 'photo',
                'is_published' => true,
                'order' => 10 + $i,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Événements (4)
        $eventTitles = [
            '📅 Cérémonie de remise des prix',
            '🌍 Rencontre internationale des fanfares',
            '🚌 Tournée de promotion - Nord Bénin',
            '🎭 Festival des Arts et Cultures',
        ];
        foreach ($eventTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Un événement marquant qui a renforcé la notoriété de la fanfare.',
                'category' => 'events',
                'type' => 'photo',
                'is_published' => true,
                'order' => 20 + $i,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Vie des membres (5)
        $memberTitles = [
            '🎯 Répétition Générale - Salle de Musique',
            '🚪 Journée Portes Ouvertes',
            '📚 Formation Musicale - Atelier',
            '🙏 Retraite Spirituelle de la Fanfare',
            '🎓 Sortie de Promotion 2024',
        ];
        foreach ($memberTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Des moments forts de la vie communautaire de notre fanfare.',
                'category' => 'members',
                'type' => 'photo',
                'is_published' => true,
                'order' => 30 + $i,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Cérémonies (3)
        $ceremonyTitles = [
            '⛪ Célébration de Pâques - Cathédrale',
            '🕊️ Messe du Nouvel An',
            '🙌 Cérémonie de bénédiction',
        ];
        foreach ($ceremonyTitles as $i => $title) {
            $galleryItems[] = [
                'title' => $title,
                'description' => 'Des moments spirituels forts vécus en musique.',
                'category' => 'ceremonies',
                'type' => 'photo',
                'is_published' => true,
                'order' => 40 + $i,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Photos de groupe (5)
        for ($i = 1; $i <= 5; $i++) {
            $galleryItems[] = [
                'title' => '📸 Photo de Famille - Promotion ' . (2020 + $i),
                'description' => 'Toute la fanfare réunie pour une photo de famille inoubliable.',
                'category' => 'group',
                'type' => 'photo',
                'is_published' => true,
                'order' => 50 + $i,
                'image_index' => array_rand($imageUrls),
            ];
        }

        // Créer les entrées
        $successCount = 0;
        foreach ($galleryItems as $index => $item) {
            try {
                // Utiliser picsum.photos pour des images aléatoires
                $imageUrl = $imageUrls[array_rand($imageUrls)];
                
                // Télécharger l'image avec Http client
                $response = Http::timeout(30)->get($imageUrl);
                
                if ($response->successful()) {
                    $imageContent = $response->body();
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
                    $successCount++;
                    $this->command->info("✅ Image créée: {$item['title']}");
                } else {
                    $this->command->warn("⚠️ Échec téléchargement: {$item['title']}");
                }
            } catch (\Exception $e) {
                $this->command->error("❌ Erreur pour {$item['title']}: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info('========================================');
        $this->command->info("✅ Galerie remplie avec {$successCount} images sur " . count($galleryItems) . ' tentatives !');
        $this->command->info('========================================');
    }
}