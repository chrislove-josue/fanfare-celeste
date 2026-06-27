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
                'id' => 'NqUqx8xAEOE', // Exemple: ABC123XYZ
                'title' => 'FANFARE LA TROMPETTE DE DIEU extrait To onou hélé godo',
                'views' => '136 vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/NqUqx8xAEOE/mqdefault.jpg'
            ],
            [
                'id' => 'VIDEO_ID_2',
                'title' => 'FANFARE CÉLESTE BLA TROMPETTE DE DIEU Cantiques 306 whenu algoo',
                'views' => '2,2 k vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/VIDEO_ID_2/mqdefault.jpg'
            ],
            [
                'id' => 'VIDEO_ID_3',
                'title' => 'Fanfare la trompette de dieu',
                'views' => '101 vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/VIDEO_ID_3/mqdefault.jpg'
            ],
            [
                'id' => 'VIDEO_ID_4',
                'title' => 'ALT JIYEWHE GBNO/TO ON',
                'views' => 'vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/VIDEO_ID_4/mqdefault.jpg'
            ],
            [
                'id' => 'VIDEO_ID_5',
                'title' => 'ljo mimo eko fayo',
                'views' => 'vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/VIDEO_ID_5/mqdefault.jpg'
            ],
            [
                'id' => 'VIDEO_ID_6',
                'title' => 'Cantique Collection',
                'views' => 'vues',
                'date' => 'il y a 4 ans',
                'thumbnail' => 'https://img.youtube.com/vi/VIDEO_ID_6/mqdefault.jpg'
            ],
            // Ajoutez les autres vidéos ici...
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