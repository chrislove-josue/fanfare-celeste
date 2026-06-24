<?php
// app/Http/Controllers/Admin/PartnerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage partners')->except(['index', 'show']);
    }

    /**
     * Liste des partenaires
     */
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'yes');
        }

        $partners = $query->ordered()->paginate(15);
        $categories = Partner::getCategories();

        $totalPartners = Partner::count();
        $activePartners = Partner::where('is_active', true)->count();
        $inactivePartners = Partner::where('is_active', false)->count();

        return view('admin.partners.index', compact(
            'partners',
            'categories',
            'totalPartners',
            'activePartners',
            'inactivePartners'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Partner::getCategories();
        return view('admin.partners.create', compact('categories'));
    }

    /**
     * Enregistrer un partenaire
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'category' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('partners/logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->order ?? 0;

        Partner::create($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire ajouté avec succès !');
    }

    /**
     * Afficher un partenaire
     */
    public function show(Partner $partner)
    {
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Partner $partner)
    {
        $categories = Partner::getCategories();
        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    /**
     * Mettre à jour un partenaire
     */
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'category' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $path = $request->file('logo')->store('partners/logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->order ?? 0;

        $partner->update($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire mis à jour avec succès !');
    }

    /**
     * Supprimer un partenaire
     */
    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire supprimé avec succès !');
    }

    /**
     * Basculer le statut actif/inactif
     */
    public function toggleActive(Partner $partner)
    {
        $partner->is_active = !$partner->is_active;
        $partner->save();

        $status = $partner->is_active ? 'activé' : 'désactivé';
        return redirect()->route('admin.partners.index')
            ->with('success', "Partenaire {$status} avec succès !");
    }
}