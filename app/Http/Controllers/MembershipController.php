<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class MembershipController extends Controller
{
    /**
     * Affiche le formulaire d'adhésion
     */
    public function applyForm()
    {
        $sections = Member::getSections();
        $instruments = Member::getInstruments();
        $pupitres = Member::getInstrumentLevels();
        
        return view('membership.apply', compact('sections', 'instruments', 'pupitres'));
    }

    /**
     * Traite la soumission du formulaire d'adhésion
     */
    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'paroisse' => 'required|string|max:255',
            'sous_region' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'section' => 'required|string|in:' . implode(',', array_keys(Member::getSections())),
            'instrument' => 'required|string|in:' . implode(',', array_keys(Member::getInstruments())),
            'instrument_level' => 'nullable|string|in:' . implode(',', array_keys(Member::getInstrumentLevels())),
            'skills' => 'required|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'id_document' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
            'adhesion_letter' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,rtf',
            'engagement' => 'required|accepted',
        ]);

        // Si l'email n'est pas fourni, le générer automatiquement
        if (empty($validated['email'])) {
            $validated['email'] = Member::generateDefaultEmail($validated['first_name'], $validated['last_name']);
        }

        // Combiner Paroisse et Sous-Région dans le champ address
        $address = $validated['paroisse'];
        if (!empty($validated['sous_region'])) {
            $address .= ' | ' . $validated['sous_region'];
        }

        // Préparer les données
        $memberData = [
            'member_number' => Member::generateMemberNumber(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'address' => $address,
            'birth_date' => $validated['birth_date'] ?? null,
            'birth_place' => $validated['birth_place'] ?? null,
            'section' => $validated['section'] ?? null,
            'instrument' => $validated['instrument'] ?? null,
            'instrument_level' => $validated['instrument_level'] ?? null,
            'section_role' => null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            'medical_notes' => $validated['medical_notes'] ?? null,
            'skills' => $validated['skills'] ?? null,
            'status' => 'pending',
            'entry_date' => now(),
        ];

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $memberData['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        // Gestion du document d'identité
        if ($request->hasFile('id_document')) {
            $memberData['id_document'] = $request->file('id_document')->store('members/documents', 'public');
        }

        // Gestion de la lettre d'adhésion
        if ($request->hasFile('adhesion_letter')) {
            $memberData['adhesion_letter'] = $request->file('adhesion_letter')->store('members/letters', 'public');
        }

        // Créer le membre
        $member = Member::create($memberData);

        // Envoyer les données via WhatsApp (webhook)
        try {
            $waUrl = config('services.wa_new_member.url');
            if ($waUrl) {
                $payload = [
                    'event' => 'new_member',
                    'member_number' => $member->member_number,
                    'first_name' => $member->first_name,
                    'last_name' => $member->last_name,
                    'full_name' => $member->full_name,
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'whatsapp' => $member->whatsapp,
                    'paroisse' => $validated['paroisse'],
                    'sous_region' => $validated['sous_region'] ?? null,
                    'section' => $member->section,
                    'instrument' => $member->instrument,
                    'instrument_level' => $member->instrument_level,
                    'skills' => $validated['skills'],
                    'birth_date' => optional($member->birth_date)->format('Y-m-d'),
                    'birth_place' => $member->birth_place,
                ];

                if (class_exists(\Illuminate\Support\Facades\Http::class)) {
                    \Illuminate\Support\Facades\Http::post($waUrl, $payload)->timeout(5);
                }
            }
        } catch (\Exception $e) {
            \Log::error('WhatsApp new member send failed: ' . $e->getMessage());
        }

        // Créer un compte utilisateur
        $userData = [
            'name' => $member->full_name,
            'email' => $member->email,
            'password' => Hash::make('password123'),
        ];

        try {
            if (Schema::hasColumn('users', 'member_id')) {
                $userData['member_id'] = $member->id;
            }
        } catch (\Exception $e) {
            // ignore
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
            \Log::error('Erreur envoi email: ' . $e->getMessage());
        }

        return redirect()->route('membership.thankyou')
            ->with('success', 'Votre adhésion a été enregistrée avec succès. Votre numéro de membre est: ' . $member->member_number . '. Vous serez contacté pour le test pratique.');
    }

    /**
     * Page de remerciement après adhésion
     */
    public function thankYou()
    {
        return view('membership.thankyou');
    }
}