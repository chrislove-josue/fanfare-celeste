<?php
// database/seeders/GallerySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\GalleryMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Créer le dossier storage
        if (!Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->makeDirectory('galleries');
        }

        $this->command->info('========================================');
        $this->command->info('🎨 Création des galeries et médias...');
        $this->command->info('========================================');

        // Catégories
        $categories = [
            'concerts' => '🎵 Concerts',
            'rehearsals' => '🎶 Répétitions',
            'trophies' => '🏆 Trophées',
            'events' => '📅 Événements',
            'members' => '👥 Vie des membres',
            'ceremonies' => '⛪ Cérémonies',
            'group' => '📸 Photos de groupe',
        ];

        $galleryCount = 0;
        $mediaCount = 0;

        // Tableau d'images réelles (Unsplash - photos de fanfare/musique)
        $realImages = [
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800', // Concert
            'https://images.unsplash.com/photo-1507838153414-b4b713384a76?w=800', // Orchestre
            'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800', // Musique
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800', // Festival
            'https://images.unsplash.com/photo-1460881680851-30b4e771aedc?w=800', // Concert
            'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=800', // Scène
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800', // Événement
            'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800', // Concert
            'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=800', // Événement
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800', // Groupe
            'https://images.unsplash.com/photo-1519677100203-a0e668c92439?w=800', // Cérémonie
            'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800', // Mariage
            'https://images.unsplash.com/photo-1518706604296-ba0529fc9e40?w=800', // Trophée
            'https://images.unsplash.com/photo-1533230408717-46b9393e6b26?w=800', // Trophée
        ];

        // ============================================
        // 1. GALERIE TROPHÉES
        // ============================================
        $gallery = $this->createGallery([
            'title' => '🏆 Nos Trophées et Distinctions',
            'description' => 'Collection des trophées et reconnaissances obtenues par la Fanfare Céleste au fil des années.',
            'category' => 'trophies',
            'is_published' => true,
            'event_date' => '2024-06-15',
            'location' => 'Cotonou, Bénin',
            'order' => 1,
        ]);
        $galleryCount++;

        $trophyMedia = [
            ['title' => 'Trophée d\'Excellence 2024', 'description' => 'Premier prix au Concours National des Fanfares'],
            ['title' => 'Distinction Ministérielle', 'description' => 'Reconnaissance du Ministère de la Culture'],
            ['title' => 'Prix du Public 2023', 'description' => 'Meilleure fanfare selon le public'],
            ['title' => 'Certificat d\'Excellence', 'description' => 'Reconnue comme référence musicale'],
        ];
        foreach ($trophyMedia as $index => $data) {
            $imageUrl = $realImages[array_rand($realImages)];
            $this->addMediaToGallery($gallery, $data['title'], $data['description'], 'photo', $index === 0, $imageUrl);
            $mediaCount++;
        }

        // ============================================
        // 2. GALERIES CONCERTS
        // ============================================
        $concertGalleries = [
            [
                'title' => '🎵 Concert de Gala 2024',
                'description' => 'Concert exceptionnel au Palais des Congrès de Cotonou',
                'event_date' => '2024-05-20',
                'location' => 'Palais des Congrès, Cotonou',
                'media' => [
                    ['title' => 'Scène principale', 'description' => 'La scène du concert de gala'],
                    ['title' => 'Public en liesse', 'description' => 'Le public enthousiaste'],
                    ['title' => 'Orchestre en action', 'description' => 'Les musiciens en pleine performance'],
                    ['title' => 'Moment de grâce', 'description' => 'Un moment magique du concert'],
                ]
            ],
            [
                'title' => '🎶 Festival International de Musique',
                'description' => 'Participation au festival international de Cotonou',
                'event_date' => '2024-03-10',
                'location' => 'Stade de l\'Amitié, Cotonou',
                'media' => [
                    ['title' => 'Cérémonie d\'ouverture', 'description' => 'Début du festival'],
                    ['title' => 'Performance', 'description' => 'La fanfare en représentation'],
                    ['title' => 'Rencontre internationale', 'description' => 'Échange avec d\'autres fanfares'],
                ]
            ]
        ];

        foreach ($concertGalleries as $concertData) {
            $gallery = $this->createGallery([
                'title' => $concertData['title'],
                'description' => $concertData['description'],
                'category' => 'concerts',
                'is_published' => true,
                'event_date' => $concertData['event_date'],
                'location' => $concertData['location'],
                'order' => $galleryCount + 1,
            ]);
            $galleryCount++;

            foreach ($concertData['media'] as $index => $data) {
                $imageUrl = $realImages[array_rand($realImages)];
                $this->addMediaToGallery($gallery, $data['title'], $data['description'], 'photo', $index === 0, $imageUrl);
                $mediaCount++;
            }
        }

        // ============================================
        // 3. GALERIE ÉVÉNEMENTS
        // ============================================
        $gallery = $this->createGallery([
            'title' => '📅 Événements Marquants',
            'description' => 'Les événements qui ont marqué la vie de la fanfare.',
            'category' => 'events',
            'is_published' => true,
            'event_date' => '2024-04-25',
            'location' => 'Cotonou, Bénin',
            'order' => $galleryCount + 1,
        ]);
        $galleryCount++;

        $eventMedia = [
            ['title' => 'Cérémonie de remise des prix', 'description' => 'Remise des distinctions'],
            ['title' => 'Rencontre internationale', 'description' => 'Échange avec les fanfares invitées'],
            ['title' => 'Tournée de promotion', 'description' => 'Tournée dans le nord du Bénin'],
            ['title' => 'Festival des Arts', 'description' => 'Participation au festival culturel'],
        ];
        foreach ($eventMedia as $index => $data) {
            $imageUrl = $realImages[array_rand($realImages)];
            $this->addMediaToGallery($gallery, $data['title'], $data['description'], 'photo', $index === 0, $imageUrl);
            $mediaCount++;
        }

        // ============================================
        // 4. GALERIE VIE DES MEMBRES
        // ============================================
        $gallery = $this->createGallery([
            'title' => '👥 Vie des Membres',
            'description' => 'Les moments forts de la vie communautaire de notre fanfare.',
            'category' => 'members',
            'is_published' => true,
            'order' => $galleryCount + 1,
        ]);
        $galleryCount++;

        $memberMedia = [
            ['title' => 'Répétition Générale', 'description' => 'Préparation du concert'],
            ['title' => 'Journée Portes Ouvertes', 'description' => 'Découverte de la fanfare'],
            ['title' => 'Formation Musicale', 'description' => 'Atelier de formation'],
            ['title' => 'Retraite Spirituelle', 'description' => 'Retraite annuelle'],
            ['title' => 'Sortie de Promotion', 'description' => 'Cérémonie de sortie'],
        ];
        foreach ($memberMedia as $index => $data) {
            $imageUrl = $realImages[array_rand($realImages)];
            $this->addMediaToGallery($gallery, $data['title'], $data['description'], 'photo', $index === 0, $imageUrl);
            $mediaCount++;
        }

        // ============================================
        // 5. GALERIE CÉRÉMONIES
        // ============================================
        $gallery = $this->createGallery([
            'title' => '⛪ Cérémonies Religieuses',
            'description' => 'Les cérémonies religieuses auxquelles participe la fanfare.',
            'category' => 'ceremonies',
            'is_published' => true,
            'order' => $galleryCount + 1,
        ]);
        $galleryCount++;

        $ceremonyMedia = [
            ['title' => 'Célébration de Pâques', 'description' => 'Messe de Pâques à la cathédrale'],
            ['title' => 'Messe du Nouvel An', 'description' => 'Célébration du Nouvel An'],
            ['title' => 'Cérémonie de Bénédiction', 'description' => 'Bénédiction de la fanfare'],
        ];
        foreach ($ceremonyMedia as $index => $data) {
            $imageUrl = $realImages[array_rand($realImages)];
            $this->addMediaToGallery($gallery, $data['title'], $data['description'], 'photo', $index === 0, $imageUrl);
            $mediaCount++;
        }

        // ============================================
        // 6. GALERIE PHOTOS DE GROUPE
        // ============================================
        $gallery = $this->createGallery([
            'title' => '📸 Photos de Famille',
            'description' => 'Les photos de groupe de la Fanfare Céleste à travers les années.',
            'category' => 'group',
            'is_published' => true,
            'order' => $galleryCount + 1,
        ]);
        $galleryCount++;

        for ($i = 1; $i <= 5; $i++) {
            $imageUrl = $realImages[array_rand($realImages)];
            $this->addMediaToGallery(
                $gallery,
                'Photo de Famille ' . (2020 + $i),
                'Toute la fanfare réunie en ' . (2020 + $i),
                'photo',
                $i === 1,
                $imageUrl
            );
            $mediaCount++;
        }

        // ============================================
        // 7. STATISTIQUES
        // ============================================
        $this->command->info('========================================');
        $this->command->info('✅ Galeries créées avec succès !');
        $this->command->info('========================================');
        $this->command->info("📊 Statistiques :");
        $this->command->info("   - Galeries créées : {$galleryCount}");
        $this->command->info("   - Médias ajoutés : {$mediaCount}");
        $this->command->info('========================================');
        
        $this->command->info('📂 Détail par catégorie :');
        foreach ($categories as $key => $label) {
            $count = Gallery::where('category', $key)->count();
            $media = GalleryMedia::whereHas('gallery', function($q) use ($key) {
                $q->where('category', $key);
            })->count();
            $this->command->info("   - {$label}: {$count} galerie(s), {$media} média(s)");
        }
        $this->command->info('========================================');
        $this->command->info('🎉 Seeder terminé !');
    }

    private function createGallery(array $data): Gallery
    {
        $gallery = Gallery::create([
            'title' => $data['title'],
            'slug' => Gallery::generateSlug($data['title']),
            'description' => $data['description'] ?? null,
            'category' => $data['category'],
            'is_published' => $data['is_published'] ?? true,
            'event_date' => $data['event_date'] ?? null,
            'location' => $data['location'] ?? null,
            'order' => $data['order'] ?? 0,
            'created_by' => 1,
        ]);

        $this->command->info("   ✅ Galerie créée: {$data['title']}");
        return $gallery;
    }

    private function addMediaToGallery(Gallery $gallery, string $title, string $description, string $type = 'photo', bool $isCover = false, string $imageUrl = null)
    {
        try {
            if ($imageUrl) {
                // Télécharger l'image depuis l'URL
                $imageContent = @file_get_contents($imageUrl);
                if ($imageContent === false) {
                    throw new \Exception('Impossible de télécharger l\'image');
                }
            } else {
                // Générer une image placeholder
                $imageContent = $this->generatePlaceholderImage($title);
            }
            
            $fileName = time() . '_' . Str::random(10) . '.jpg';
            $filePath = 'galleries/' . $gallery->id . '/media/' . $fileName;
            
            Storage::disk('public')->put($filePath, $imageContent);

            GalleryMedia::create([
                'gallery_id' => $gallery->id,
                'title' => $title,
                'description' => $description,
                'type' => $type,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => strlen($imageContent),
                'mime_type' => 'image/jpeg',
                'is_cover' => $isCover,
                'is_published' => true,
                'order' => $gallery->media()->count() + 1,
                'views_count' => rand(10, 500),
                'likes_count' => rand(0, 50),
            ]);

            if ($isCover && !$gallery->cover_image) {
                $gallery->update(['cover_image' => $filePath]);
            }

        } catch (\Exception $e) {
            $this->command->error("   ❌ Erreur pour {$title}: " . $e->getMessage());
        }
    }

    private function generatePlaceholderImage(string $text): string
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        $colors = [
            ['r' => 57, 'g' => 70, 'b' => 178],
            ['r' => 64, 'g' => 168, 'b' => 27],
            ['r' => 255, 'g' => 107, 'b' => 53],
            ['r' => 139, 'g' => 92, 'b' => 246],
            ['r' => 245, 'g' => 158, 'b' => 11],
            ['r' => 236, 'g' => 73, 'b' => 153],
            ['r' => 6, 'g' => 182, 'b' => 212],
            ['r' => 52, 'g' => 73, 'b' => 94],
        ];

        $color = $colors[array_rand($colors)];
        $bgColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $shadowColor = imagecolorallocate($image, 0, 0, 0);

        imagefill($image, 0, 0, $bgColor);

        for ($i = 0; $i < $height; $i += 2) {
            $alpha = ($i / $height) * 30;
            $overlay = imagecolorallocatealpha($image, 255, 255, 255, $alpha);
            imageline($image, 0, $i, $width, $i, $overlay);
        }

        $borderColor = imagecolorallocate($image, 255, 255, 255);
        imagerectangle($image, 10, 10, $width - 10, $height - 10, $borderColor);

        $text = mb_substr($text, 0, 25);
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2 - 20;

        imagestring($image, $fontSize, $x + 2, $y + 2, $text, $shadowColor);
        imagestring($image, $fontSize, $x, $y, $text, $textColor);

        $subText = 'Fanfare Céleste';
        $subWidth = imagefontwidth(3) * strlen($subText);
        $subX = ($width - $subWidth) / 2;
        $subY = $y + $textHeight + 20;
        imagestring($image, 3, $subX, $subY, $subText, $textColor);

        ob_start();
        imagejpeg($image, null, 80);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return $imageData;
    }
}