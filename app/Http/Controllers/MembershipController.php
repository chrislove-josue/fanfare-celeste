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
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'birth_place' => 'nullable|string',
            'instrument' => 'nullable|string',
            'instrument_level' => 'nullable|string',
            'section' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'id_document' => 'nullable|file|max:5120',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        $validated['member_number'] = Member::generateMemberNumber();
        $validated['status'] = 'pending';
        $validated['entry_date'] = now();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        if ($request->hasFile('id_document')) {
            $validated['id_document'] = $request->file('id_document')->store('members/documents', 'public');
        }

        $member = Member::create($validated);

        // Créer un compte utilisateur
        $user = User::create([
            'name' => $member->full_name,
            'email' => $member->email,
            'password' => Hash::make('password123'), // À changer par l'utilisateur
            'member_id' => $member->id
        ]);
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