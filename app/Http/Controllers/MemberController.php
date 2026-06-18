<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view members')->only(['index', 'show']);
        $this->middleware('permission:create members')->only(['create', 'store']);
        $this->middleware('permission:edit members')->only(['edit', 'update']);
        $this->middleware('permission:delete members')->only(['destroy']);
        $this->middleware('permission:approve members')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = Member::query();
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }
        
        if ($request->filled('instrument')) {
            $query->where('instrument', $request->instrument);
        }
        
        $members = $query->latest()->paginate(15);
        
        // Statistiques pour le filtre
        $sections = Member::select('section')->distinct()->whereNotNull('section')->pluck('section');
        $instruments = Member::select('instrument')->distinct()->whereNotNull('instrument')->pluck('instrument');
        $statuses = ['pending', 'active', 'suspended', 'withdrawn', 'deceased', 'training'];
        
        return view('admin.members.index', compact('members', 'sections', 'instruments', 'statuses'));
    }

    public function create()
    {
        $sections = ['Trompettes', 'Trombones', 'Saxophones', 'Percussions', 'Clairons', 'Batterie'];
        $instruments = ['Trompette', 'Trombone', 'Saxophone Alto', 'Saxophone Ténor', 'Saxophone Baryton', 'Batterie', 'Caisse Claire', 'Grosse Caisse', 'Cymbales'];
        
        return view('admin.members.create', compact('sections', 'instruments'));
    }

    public function store(Request $request)
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
            'instrument_level' => 'nullable|in:debutant,intermediaire,avance,expert',
            'entry_date' => 'required|date',
            'section' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'id_document' => 'nullable|file|max:5120',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $validated['member_number'] = Member::generateMemberNumber();
            $validated['status'] = 'pending';

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
            }

            if ($request->hasFile('id_document')) {
                $validated['id_document'] = $request->file('id_document')->store('members/documents', 'public');
            }

            $member = Member::create($validated);

            // Créer un compte utilisateur pour le membre
            $user = User::create([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => Hash::make('password123'), // À changer par l'utilisateur
                'member_id' => $member->id
            ]);
            $user->assignRole('member');

            DB::commit();
            
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Membre ajouté avec succès. Numéro: ' . $member->member_number);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    public function show(Member $member)
    {
        $contributions = $member->contributions()->latest()->take(6)->get();
        $events = $member->eventParticipations()->with('event')->latest()->take(6)->get();
        $transactions = $member->transactions()->latest()->take(6)->get();
        $committeePosition = $member->currentCommitteePosition;
        
        return view('admin.members.show', compact('member', 'contributions', 'events', 'transactions', 'committeePosition'));
    }

    public function edit(Member $member)
    {
        $sections = ['Trompettes', 'Trombones', 'Saxophones', 'Percussions', 'Clairons', 'Batterie'];
        $instruments = ['Trompette', 'Trombone', 'Saxophone Alto', 'Saxophone Ténor', 'Saxophone Baryton', 'Batterie', 'Caisse Claire', 'Grosse Caisse', 'Cymbales'];
        $statuses = ['pending', 'active', 'suspended', 'withdrawn', 'deceased', 'training'];
        $instrumentLevels = ['debutant', 'intermediaire', 'avance', 'expert'];
        
        return view('admin.members.edit', compact('member', 'sections', 'instruments', 'statuses', 'instrumentLevels'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'required|string',
            'instrument' => 'nullable|string',
            'instrument_level' => 'nullable|in:debutant,intermediaire,avance,expert',
            'section' => 'nullable|string',
            'status' => 'required|in:pending,active,suspended,withdrawn,deceased,training',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'skills' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member->update($validated);

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Membre mis à jour avec succès');
    }

    public function destroy(Member $member)
    {
        // Supprimer les fichiers associés
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        if ($member->id_document) {
            Storage::disk('public')->delete($member->id_document);
        }
        
        // Supprimer l'utilisateur associé
        if ($member->user) {
            $member->user->delete();
        }
        
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre supprimé avec succès');
    }

    public function approve(Member $member)
    {
        $member->update(['status' => 'active']);
        
        // Envoyer un email de bienvenue (à implémenter)
        // Mail::to($member->email)->send(new WelcomeMemberMail($member));
        
        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Membre approuvé avec succès');
    }

    public function reject(Member $member)
    {
        $member->update(['status' => 'withdrawn']);
        
        return redirect()->route('admin.members.show', $member)
            ->with('warning', 'Membre rejeté');
    }

    public function generateCard(Member $member)
    {
        $qrCode = $member->memberCardQrCode;
        
        $pdf = PDF::loadView('admin.members.card', compact('member', 'qrCode'));
        $pdf->setPaper('a6', 'landscape');
        
        return $pdf->download('carte_membre_' . $member->member_number . '.pdf');
    }

    public function export()
    {
        $members = Member::all();
        $pdf = PDF::loadView('admin.members.export', compact('members'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('liste_membres_' . date('Y-m-d') . '.pdf');
    }
}