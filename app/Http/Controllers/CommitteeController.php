<?php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommitteeController extends Controller
{
    /**
     * Affiche la liste des membres du bureau
     */
    public function index()
    {
        $committeeMembers = CommitteeMember::with('member')
            ->orderBy('position_order')
            ->orderBy('start_date', 'desc')
            ->paginate(20);
        
        $positions = CommitteeMember::getPositions();
        $currentCommittee = CommitteeMember::with('member')
            ->where('is_current', true)
            ->orderBy('position_order')
            ->get();
        
        return view('committee.index', compact('committeeMembers', 'positions', 'currentCommittee'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $members = Member::where('status', 'active')->orderBy('first_name')->get();
        $positions = CommitteeMember::getPositions();
        
        return view('committee.create', compact('members', 'positions'));
    }

    /**
     * Enregistre un nouveau membre du bureau
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'position' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'responsibilities' => 'nullable|string',
        ]);

        // Vérifier si le membre est déjà dans le bureau actuel
        $existing = CommitteeMember::where('member_id', $request->member_id)
            ->where('is_current', true)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Ce membre est déjà en fonction dans le bureau');
        }

        // Définir l'ordre
        $lastOrder = CommitteeMember::max('position_order') ?? 0;
        $validated['position_order'] = $lastOrder + 1;
        $validated['is_current'] = true;

        CommitteeMember::create($validated);

        return redirect()->route('admin.committee.index')
            ->with('success', 'Membre du bureau ajouté avec succès');
    }

    /**
     * Affiche les détails d'un membre du bureau
     */
    public function show(CommitteeMember $committee)
    {
        return view('committee.show', compact('committee'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(CommitteeMember $committee)
    {
        $members = Member::where('status', 'active')->orderBy('first_name')->get();
        $positions = CommitteeMember::getPositions();
        
        return view('committee.edit', compact('committee', 'members', 'positions'));
    }

    /**
     * Met à jour un membre du bureau
     */
    public function update(Request $request, CommitteeMember $committee)
    {
        $validated = $request->validate([
            'position' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'responsibilities' => 'nullable|string',
            'achievements' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        $committee->update($validated);

        return redirect()->route('admin.committee.show', $committee)
            ->with('success', 'Membre du bureau mis à jour avec succès');
    }

    /**
     * Supprime un membre du bureau
     */
    public function destroy(CommitteeMember $committee)
    {
        $committee->delete();

        return redirect()->route('admin.committee.index')
            ->with('success', 'Membre du bureau supprimé avec succès');
    }

    /**
     * Termine le mandat d'un membre du bureau
     */
    public function endMandate(CommitteeMember $committee)
    {
        $committee->update([
            'end_date' => now(),
            'is_current' => false
        ]);

        return redirect()->route('admin.committee.show', $committee)
            ->with('success', 'Mandat terminé avec succès');
    }
}