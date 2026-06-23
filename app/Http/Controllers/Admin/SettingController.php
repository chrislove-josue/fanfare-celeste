<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Affiche la page des paramètres
     */
    public function index()
    {
        // Récupérer les paramètres existants (vous pouvez les stocker en DB ou en cache)
        $settings = [
            'site_name' => config('app.name', 'Fanfare Céleste'),
            'site_description' => config('app.description', ''),
            'contact_email' => config('mail.from.address', 'contact@fanfareceleste.org'),
            'contact_phone' => config('app.phone', '+229 97 00 00 00'),
            'contact_address' => config('app.address', ''),
            'facebook_url' => config('app.facebook', ''),
            'instagram_url' => config('app.instagram', ''),
            'youtube_url' => config('app.youtube', ''),
            'tiktok_url' => config('app.tiktok', ''),
            'whatsapp_number' => config('app.whatsapp', ''),
            'maintenance_mode' => config('app.maintenance', false),
            'membership_fee' => config('app.membership_fee', 5000),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Met à jour les paramètres
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'maintenance_mode' => 'boolean',
            'membership_fee' => 'nullable|numeric|min:0',
        ]);

        // Stocker les paramètres dans le cache (ou dans une table settings)
        foreach ($validated as $key => $value) {
            Cache::forever('setting_' . $key, $value);
        }

        // Si vous voulez les stocker dans un fichier .env
        // $this->updateEnvFile($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }

    /**
     * Vide le cache de l'application
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()->route('admin.settings')
            ->with('success', 'Cache vidé avec succès !');
    }

    /**
     * Met à jour le fichier .env (optionnel)
     */
    private function updateEnvFile($settings)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        $mappings = [
            'site_name' => 'APP_NAME',
            'contact_email' => 'MAIL_FROM_ADDRESS',
            'maintenance_mode' => 'APP_MAINTENANCE',
        ];

        foreach ($mappings as $key => $envKey) {
            if (isset($settings[$key])) {
                $value = $settings[$key];
                $envContent = preg_replace(
                    '/^' . $envKey . '=.*$/m',
                    $envKey . '=' . $value,
                    $envContent
                );
            }
        }

        file_put_contents($envFile, $envContent);
    }

    /**
     * Récupère une valeur de paramètre
     */
    public static function get($key, $default = null)
    {
        return Cache::get('setting_' . $key, $default);
    }
}