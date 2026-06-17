<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class PublicGalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::query()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('gallery.public.index', compact('galleries'));
    }
}

