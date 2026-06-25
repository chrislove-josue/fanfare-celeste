<?php
// database/seeders/CarouselSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // AJOUTER CETTE LIGNE

class CarouselSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le dossier des carousels
        if (!Storage::disk('public')->exists('carousels')) {
            Storage::disk('public')->makeDirectory('carousels');
        }

        $carousels = [
            [
                'title' => 'Fanfare Céleste',
                'subtitle' => 'La Trompette de Dieu de l\'Atlantique',
                'image' => $this->createCarouselImage('🎵 Fanfare Céleste', '#1a237e'),
                'link' => route('about'),
                'link_text' => 'Découvrir',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Rejoignez-nous',
                'subtitle' => 'Une grande famille musicale vous attend',
                'image' => $this->createCarouselImage('🎺 Rejoignez-nous', '#2e7d32'),
                'link' => route('membership.apply'),
                'link_text' => 'Adhérer maintenant',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Événements à venir',
                'subtitle' => 'Venez vivre des moments uniques',
                'image' => $this->createCarouselImage('📅 Événements', '#e65100'),
                'link' => route('public.events.index'),
                'link_text' => 'Voir les événements',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($carousels as $data) {
            Carousel::create($data);
        }

        $this->command->info('✅ Carousels créés avec succès !');
    }

    private function createCarouselImage(string $text, string $color): string
    {
        $width = 1920;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);

        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $r, $g, $b);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $lightColor = imagecolorallocate($image, min(255, $r + 50), min(255, $g + 50), min(255, $b + 50));

        imagefill($image, 0, 0, $bgColor);

        // Dégradé
        for ($i = 0; $i < $height; $i += 2) {
            $alpha = ($i / $height) * 40;
            $overlay = imagecolorallocatealpha($image, 255, 255, 255, $alpha);
            imageline($image, 0, $i, $width, $i, $overlay);
        }

        // Cadre décoratif
        imagerectangle($image, 20, 20, $width - 20, $height - 20, $lightColor);
        imagerectangle($image, 30, 30, $width - 30, $height - 30, $white);

        // Grand titre
        $fontSize = 6;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2 - 30;

        // Ombre
        imagestring($image, $fontSize, $x + 4, $y + 4, $text, $black);
        imagestring($image, $fontSize, $x, $y, $text, $white);

        // Sous-titre
        $subText = 'Fanfare Céleste';
        $subFontSize = 4;
        $subWidth = imagefontwidth($subFontSize) * strlen($subText);
        $subX = ($width - $subWidth) / 2;
        $subY = $y + $textHeight + 40;
        imagestring($image, $subFontSize, $subX, $subY, $subText, $lightColor);

        // Emoji musique
        $emoji = '🎵';
        $emojiWidth = imagefontwidth(4) * strlen($emoji);
        $emojiX = ($width - $emojiWidth) / 2;
        $emojiY = $y - 60;
        imagestring($image, 4, $emojiX, $emojiY, $emoji, $white);

        ob_start();
        imagejpeg($image, null, 85);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $fileName = time() . '_' . Str::slug($text) . '.jpg';
        $filePath = 'carousels/' . $fileName;
        Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }
}