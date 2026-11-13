<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    /**
     * Store an image uploaded from the rich text editor.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:4096'],
        ]);

        $file = $validated['file'];
        $path = $file->storeAs(
            'editor',
            Str::uuid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        return response()->json([
            'location' => Storage::disk('public')->url($path),
        ]);
    }
}
