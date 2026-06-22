<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    /**
     * Affiche le formulaire d'adhésion
     */
    public function applyForm()
    {
        $sections = ['Trompettes', 'Trombones', 'Saxophones', 'Percussions', 'Clairons', 'Batterie'];
        $instruments = ['Trompette', 'Trombone', 'Saxophone Alto', 'Saxophone Ténor', 'Saxophone Baryton', 'Batterie', 'Caisse Claire', 'Grosse Caisse', 'Cymbales'];
        
        return view('membership.apply', compact('sections', 'instruments'));
    }

    /**
     * Traite la soumission du formulaire d'adhésion
     */
    public function submitApplication(Request $request)
    {
        // Backward-compatible: le formulaire actuel n’envoie que name/email/phone/message
        // (et non first_name/last_name/address/birth_date).
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
        ]);

        // On mappe d’abord le champ 'name' du formulaire vers first_name/last_name
        // (le formulaire actuel n’envoie pas explicitement ces champs).
        $parts = preg_split('/\s+/', trim($validated['name']));
        $validated['first_name'] = $parts[0] ?? null;
        $validated['last_name'] = $parts[1] ?? (count($parts) > 1 ? end($parts) : null);

        // Champs optionnels (si des anciens formulaires les fournissent)
        $validated['whatsapp'] = $request->input('whatsapp');
        $validated['birth_place'] = $request->input('birth_place');
        $validated['instrument'] = $request->input('instrument');
        $validated['instrument_level'] = $request->input('instrument_level');
        $validated['section'] = $request->input('section');
        $validated['emergency_contact_name'] = $request->input('emergency_contact_name');
        $validated['emergency_contact_phone'] = $request->input('emergency_contact_phone');
        $validated['medical_notes'] = $request->input('medical_notes');
        $validated['skills'] = $request->input('skills');
        $validated['notes'] = $request->input('message');

        // Champs requis par la table 'members' : si absents, on évite de les passer à NULL.
        // (sinon, la validation du formulaire déclencherait les erreurs que tu vois.)
        $validated['address'] = $request->input('address') ?? 'N/A';
        $validated['birth_date'] = $request->input('birth_date') ?? now()->toDateString();

        // Gestion fichiers (optionnel)
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        if ($request->hasFile('id_document')) {
            $validated['id_document'] = $request->file('id_document')->store('members/documents', 'public');
        }

        // Pour compatibilité avec la colonne attendue par le modèle.
        // Le champ 'name' n'existe pas dans la table 'members'.
        // On stocke donc dans (first_name/last_name) si fournis sinon dans first_name/last_name vides.
        if (empty($validated['first_name']) && !empty($validated['name'])) {
            // split simple (optionnel)
            $parts = preg_split('/\s+/', trim($validated['name']));
            $validated['first_name'] = $parts[0] ?? null;
            $validated['last_name'] = $parts[1] ?? (count($parts) > 1 ? end($parts) : null);
        }

        $validated['member_number'] = Member::generateMemberNumber();
        $validated['status'] = 'pending';
        $validated['entry_date'] = now();

        // Supprimer les clés non attendues par la table
        unset($validated['name'], $validated['message']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        if ($request->hasFile('id_document')) {
            $validated['id_document'] = $request->file('id_document')->store('members/documents', 'public');
        }

        $member = Member::create($validated);

        // Créer un compte utilisateur
        // La table users ne contient pas forcément member_id (selon la migration actuelle)
        // donc on ne l’insère que si la colonne existe.
        $userData = [
            'name' => $member->full_name,
            'email' => $member->email,
            'password' => Hash::make('password123'),
        ];

        try {
            if (\Schema::hasColumn('users', 'member_id')) {
                $userData['member_id'] = $member->id;
            }
        } catch (\Exception $e) {
            // ignore (contexte DB pas dispo)
        }

        $user = User::create($userData);
        $user->assignRole('member');

        // Envoyer un email de confirmation
        try {
            Mail::send('emails.membership-confirmation', ['member' => $member], function ($mail) use ($member) {
                $mail->to($member->email)
                    ->subject('Confirmation de votre adhésion - Fanfare Céleste');
            });
        } catch (\Exception $e) {
            // Log l'erreur mais continue
            \Log::error('Erreur envoi email: ' . $e->getMessage());
        }

        return redirect()->route('membership.thankyou')
            ->with('success', 'Votre adhésion a été enregistrée avec succès. Votre numéro de membre est: ' . $member->member_number);
    }

    /**
     * Page de remerciement après adhésion
     */
    public function thankYou()
    {
        return view('membership.thankyou');
    }
}