<?php
// app/Http/Controllers/VideoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Liste des vidéos de la chaîne YouTube
     * @return array
     */
    public function getVideosList()
    {
        // ⚠️ REMPLACEZ LES IDs PAR LES VRAIS IDs DES VIDÉOS
        return [
            
            [
                'id' => 'ZhshH7mBWSU',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU ( Gigo twé tin to mimin)',
                'thumbnail' => 'https://img.youtube.com/vi/ZhshH7mBWSU/mqdefault.jpg'
            ],
            [
                'id' => 'Z-srI1QfO7g',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU (collection cantique)',
                'thumbnail' => 'https://img.youtube.com/vi/Z-srI1QfO7g/mqdefault.jpg'
            ],
            [
                'id' => 'SoExyglPc1M',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU Ali jiyewhé gbon/ to onou',
                'thumbnail' => 'https://img.youtube.com/vi/SoExyglPc1M/mqdefault.jpg'
            ],
            [
                'id' => '_oaF8p9GyF4',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU(Ijo mimo)',
                'thumbnail' => 'https://img.youtube.com/vi/_oaF8p9GyF4/mqdefault.jpg'
            ],
            [
                'id' => 'NqUqx8xAEOE',
                'title' => 'FANFARE LA TROMPETTE DE DIEU extrait To onou hélé godo',
                'thumbnail' => 'https://img.youtube.com/vi/NqUqx8xAEOE/mqdefault.jpg'
            ],
            [
                'id' => '5pRVZTz6W7s',
                'title' => "Fanfare Céleste la Trompette de Dieu de l'Atlantique était au Nigeria",
                'thumbnail' => 'https://img.youtube.com/vi/5pRVZTz6W7s/mqdefault.jpg'
            ],
            [
                'id' => 'TanJH6grYZ4',
                'title' => 'fanfare la trompette de dieu Mi na Mian jio',
                'thumbnail' => 'https://img.youtube.com/vi/TanJH6grYZ4/mqdefault.jpg'
            ],
            [
                'id' => 'I3ni9GMyCtM',
                'title' => 'Fanfare FCTD ATLANTIQUE ( BENIN était au NIGERIA)',
                'thumbnail' => 'https://img.youtube.com/vi/I3ni9GMyCtM/mqdefault.jpg'
            ],
            [
                'id' => 'g8ZKx2Ghz58',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU ( Mon âme)',
                'thumbnail' => 'https://img.youtube.com/vi/g8ZKx2Ghz58/mqdefault.jpg'
            ],
            [
                'id' => 'EUCxunR5beY',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU (Tipinti)',
                'thumbnail' => 'https://img.youtube.com/vi/EUCxunR5beY/mqdefault.jpg'
            ],
            [
                'id' => 'ZhshH7mBWSU',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU ( Gigo twé tin to mimin)',
                'thumbnail' => 'https://img.youtube.com/vi/ZhshH7mBWSU/mqdefault.jpg'
            ],
            [
                'id' => 'tL-9NeB0IxE',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU ( Halef)',
                'thumbnail' => 'https://img.youtube.com/vi/tL-9NeB0IxE/mqdefault.jpg'
            ],
            [
                'id' => 'Z-srI1QfO7g',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU (collection cantique)',
                'thumbnail' => 'https://img.youtube.com/vi/Z-srI1QfO7g/mqdefault.jpg'
            ],
            [
                'id' => 'SoExyglPc1M',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU Ali jiyewhé gbon/ to onou',
                'thumbnail' => 'https://img.youtube.com/vi/SoExyglPc1M/mqdefault.jpg'
            ],
            [
                'id' => '_oaF8p9GyF4',
                'title' => 'FANFARE CELESTE LA TROMPETTE DE DIEU(Ijo mimo)',
                'thumbnail' => 'https://img.youtube.com/vi/_oaF8p9GyF4/mqdefault.jpg'
            ],
            
        ];
    }

    /**
     * Affiche la liste des vidéos
     */
    public function index()
    {
        $videos = $this->getVideosList();
        return view('videos.index', compact('videos'));
    }

    /**
     * Affiche une vidéo spécifique
     */
    public function show($videoId)
    {
        $videos = $this->getVideosList();
        
        // Recherche de la vidéo actuelle
        $currentVideo = null;
        $currentIndex = 0;
        
        foreach ($videos as $index => $video) {
            if ($video['id'] === $videoId) {
                $currentVideo = $video;
                $currentIndex = $index;
                break;
            }
        }
        
        // Si la vidéo n'existe pas
        if (!$currentVideo) {
            abort(404);
        }

        // Vidéo suivante et précédente
        $nextVideo = isset($videos[$currentIndex + 1]) ? $videos[$currentIndex + 1] : null;
        $prevVideo = isset($videos[$currentIndex - 1]) ? $videos[$currentIndex - 1] : null;

        return view('videos.show', compact('currentVideo', 'videos', 'nextVideo', 'prevVideo'));
    }
}