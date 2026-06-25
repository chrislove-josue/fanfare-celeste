<?php
// app/Http/Controllers/PublicCarouselController.php

namespace App\Http\Controllers;

use App\Models\Carousel;
use Illuminate\Http\Request;

class PublicCarouselController extends Controller
{
    /**
     * Récupère tous les carousels actifs
     */
    public function index()
    {
        $carousels = Carousel::active()->ordered()->get();
        return response()->json($carousels);
    }

    /**
     * Récupère les carousels pour la section hero
     */
    public function getHeroSlides()
    {
        $slides = Carousel::active()
            ->ordered()
            ->take(5) // Limiter à 5 slides pour la hero
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'image' => asset('storage/' . $slide->image),
                    'link' => $slide->link,
                    'link_text' => $slide->link_text,
                ];
            });

        return response()->json($slides);
    }

    /**
     * Récupère un carousel spécifique
     */
    public function show($id)
    {
        $carousel = Carousel::active()->findOrFail($id);
        return response()->json([
            'id' => $carousel->id,
            'title' => $carousel->title,
            'subtitle' => $carousel->subtitle,
            'image' => asset('storage/' . $carousel->image),
            'link' => $carousel->link,
            'link_text' => $carousel->link_text,
        ]);
    }
}