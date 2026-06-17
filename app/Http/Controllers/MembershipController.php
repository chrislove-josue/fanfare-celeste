<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MembershipController extends Controller
{
    public function applyForm()
    {
        return view('membership.apply');
    }

    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: sauvegarder en base (application members)
        Log::info('Membership application received', $validated);

        return redirect()->route('home')->with('success', "Votre demande d'adhésion a été envoyée avec succès.");
    }
}

