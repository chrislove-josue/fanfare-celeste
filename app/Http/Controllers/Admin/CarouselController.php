<?php
// app/Http/Controllers/Admin/CarouselController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::ordered()->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    public function create()
    {
        return view('admin.carousels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('carousels', 'public');
            $validated['image'] = $path;
        }

        Carousel::create($validated);

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Slide créé avec succès !');
    }

    public function edit(Carousel $carousel)
    {
        return view('admin.carousels.edit', compact('carousel'));
    }

    public function update(Request $request, Carousel $carousel)
{
    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:2048',
        'link' => 'nullable|url',
        'link_text' => 'nullable|string|max:255',
        'order' => 'integer',
        'is_active' => 'boolean',
    ]);

    if ($request->hasFile('image')) {
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }
        // CORRECTION : store() au lieu de storage()
        $path = $request->file('image')->store('carousels', 'public');
        $validated['image'] = $path;
    }

    $carousel->update($validated);

    return redirect()->route('admin.carousels.index')
        ->with('success', 'Slide mis à jour avec succès !');
}

    /**
 * Basculer le statut actif/inactif
 */
public function toggle(Carousel $carousel)
{
    $carousel->is_active = !$carousel->is_active;
    $carousel->save();

    $status = $carousel->is_active ? 'activé' : 'désactivé';
    return redirect()->route('admin.carousels.index')
        ->with('success', "Slide {$status} avec succès !");
}

    public function destroy(Carousel $carousel)
    {
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }
        $carousel->delete();

        return redirect()->route('admin.carousels.index')
            ->with('success', 'Slide supprimé avec succès !');
    }
}