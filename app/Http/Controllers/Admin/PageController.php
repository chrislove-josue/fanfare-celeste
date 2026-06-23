<?php
// app/Http/Controllers/Admin/PageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('parent')->ordered()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $parents = Page::whereNull('parent_id')->get();
        return view('admin.pages.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'is_published' => 'boolean',
            'order' => 'integer',
            'template' => 'string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = $validated['is_published'] ?? false ? now() : null;

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page créée avec succès !');
    }

    public function edit(Page $page)
    {
        $parents = Page::whereNull('parent_id')
            ->where('id', '!=', $page->id)
            ->get();
        return view('admin.pages.edit', compact('page', 'parents'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'is_published' => 'boolean',
            'order' => 'integer',
            'template' => 'string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if (($validated['is_published'] ?? false) && !$page->published_at) {
            $validated['published_at'] = now();
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page mise à jour avec succès !');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')
            ->with('success', 'Page supprimée avec succès !');
    }
}