<?php
// database/seeders/PartnerSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // AJOUTER CETTE LIGNE

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le dossier des logos
        if (!Storage::disk('public')->exists('partners/logos')) {
            Storage::disk('public')->makeDirectory('partners/logos');
        }

        $partners = [
            [
                'name' => 'Ministère de la Culture',
                'logo' => $this->createPartnerLogo('Ministère de la Culture', '#1a237e'),
                'category' => 'institutional',
                'description' => 'Soutien aux activités culturelles et artistiques',
                'website' => 'https://www.culture.bj',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Église Évangélique du Bénin',
                'logo' => $this->createPartnerLogo('Église Évangélique', '#2e7d32'),
                'category' => 'official',
                'description' => 'Partenaire spirituel et institutionnel',
                'website' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Radio Lumière',
                'logo' => $this->createPartnerLogo('Radio Lumière', '#f57c00'),
                'category' => 'media',
                'description' => 'Diffusion de nos concerts et événements',
                'website' => 'https://www.radiolumiere.bj',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'ORCA Bénin',
                'logo' => $this->createPartnerLogo('ORCA Bénin', '#1565c0'),
                'category' => 'sponsor',
                'description' => 'Sponsor officiel de la fanfare',
                'website' => 'https://www.orca.bj',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Institut Français du Bénin',
                'logo' => $this->createPartnerLogo('Institut Français', '#e53935'),
                'category' => 'cultural',
                'description' => 'Partenariat culturel et échanges artistiques',
                'website' => 'https://www.institutfrancais-benin.com',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Ambassade de France',
                'logo' => $this->createPartnerLogo('Ambassade de France', '#1a237e'),
                'category' => 'institutional',
                'description' => 'Soutien aux échanges culturels',
                'website' => 'https://www.ambafrance-bj.org',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Canal 3 Bénin',
                'logo' => $this->createPartnerLogo('Canal 3 Bénin', '#e65100'),
                'category' => 'media',
                'description' => 'Couverture médiatique des événements',
                'website' => 'https://www.canal3benin.com',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'MTN Bénin',
                'logo' => $this->createPartnerLogo('MTN Bénin', '#ffcb00'),
                'category' => 'sponsor',
                'description' => 'Sponsor principal de la fanfare',
                'website' => 'https://www.mtn.bj',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }

        $this->command->info('✅ Partenaires créés avec logos !');
    }

    private function createPartnerLogo(string $name, string $color): string
    {
        $width = 400;
        $height = 200;
        $image = imagecreatetruecolor($width, $height);

        // Couleur de fond
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $r, $g, $b);
        $white = imagecolorallocate($image, 255, 255, 255);
        $lightColor = imagecolorallocate($image, min(255, $r + 50), min(255, $g + 50), min(255, $b + 50));

        imagefill($image, 0, 0, $bgColor);

        // Dégradé
        for ($i = 0; $i < $height; $i += 2) {
            $alpha = ($i / $height) * 30;
            $overlay = imagecolorallocatealpha($image, 255, 255, 255, $alpha);
            imageline($image, 0, $i, $width, $i, $overlay);
        }

        // Cadre
        imagerectangle($image, 5, 5, $width - 5, $height - 5, $lightColor);

        // Texte du nom du partenaire
        $text = mb_substr($name, 0, 25);
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;

        // Ombre
        imagestring($image, $fontSize, $x + 2, $y + 2, $text, imagecolorallocate($image, 0, 0, 0));
        imagestring($image, $fontSize, $x, $y, $text, $white);

        // Ajouter une petite étoile ou icône
        $starColor = imagecolorallocate($image, 255, 255, 255);
        $starX = $width - 30;
        $starY = 20;
        imagestring($image, 3, $starX, $starY, '★', $starColor);

        // Sauvegarder
        ob_start();
        imagejpeg($image, null, 85);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $fileName = time() . '_' . Str::slug($name) . '.jpg';
        $filePath = 'partners/logos/' . $fileName;
        Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }
}