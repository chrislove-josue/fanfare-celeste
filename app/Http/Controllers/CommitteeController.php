<?php
// app/Http/Controllers/CommitteeController.php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommitteeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view committee')->only(['index', 'show']);
        $this->middleware('permission:manage committee')->only(['create', 'store', 'edit', 'update', 'destroy', 'endMandate']);
    }

    /**
     * Affiche la liste des membres du comité
     */
    public function index()
    {
        $committeeMembers = CommitteeMember::with('member')
            ->orderBy('position_order')
            ->get();
        
        $totalMembers = Member::active()->count();
        $positions = CommitteeMember::getPositions();
        
        return view('admin.committee.index', compact(
            'committeeMembers',
            'totalMembers',
            'positions'
        ));
    }

    /**
     * Affiche le formulaire d'ajout
     */
    public function create()
    {
        $committeeMemberIds = CommitteeMember::where('is_current', true)
            ->pluck('member_id')
            ->toArray();
        
        $availableMembers = Member::active()
            ->whereNotIn('id', $committeeMemberIds)
            ->get();
        
        $positions = CommitteeMember::getPositions();
        $nextOrder = CommitteeMember::max('position_order') + 1;
        
        return view('admin.committee.create', compact(
            'availableMembers',
            'positions',
            'nextOrder'
        ));
    }

    /**
     * Enregistre un nouveau membre dans le comité
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'position' => 'required|string|in:' . implode(',', array_keys(CommitteeMember::getPositions())),
            'start_date' => 'required|date',
            'position_order' => 'nullable|integer|min:1|max:99',
            'responsibilities' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            $exists = CommitteeMember::where('member_id', $validated['member_id'])
                ->where('is_current', true)
                ->exists();
            
            if ($exists) {
                return back()->with('error', 'Ce membre est déjà dans le comité actif.')
                             ->withInput();
            }

            $validated['is_current'] = $request->has('is_current');
            
            if (empty($validated['position_order'])) {
                $validated['position_order'] = CommitteeMember::max('position_order') + 1;
            }

            CommitteeMember::create($validated);
            
            DB::commit();

            return redirect()->route('admin.committee.index')
                ->with('success', 'Membre ajouté au comité avec succès.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Affiche les détails d'un membre du comité
     */
    public function show($id)
    {
        $committeeMember = CommitteeMember::with('member')->findOrFail($id);
        $positions = CommitteeMember::getPositions();
        
        return view('admin.committee.show', compact('committeeMember', 'positions'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $committeeMember = CommitteeMember::with('member')->findOrFail($id);
        $positions = CommitteeMember::getPositions();
        
        return view('admin.committee.edit', compact('committeeMember', 'positions'));
    }

    /**
     * Met à jour un membre du comité
     */
    public function update(Request $request, $id)
    {
        $committeeMember = CommitteeMember::findOrFail($id);
        
        $validated = $request->validate([
            'position' => 'required|string|in:' . implode(',', array_keys(CommitteeMember::getPositions())),
            'start_date' => 'required|date',
            'position_order' => 'nullable|integer|min:1|max:99',
            'responsibilities' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            $validated['is_current'] = $request->has('is_current');
            
            $committeeMember->update($validated);
            
            DB::commit();

            return redirect()->route('admin.committee.index')
                ->with('success', 'Membre du comité mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprime un membre du comité
     */
    public function destroy($id)
    {
        $committeeMember = CommitteeMember::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            $committeeMember->delete();
            
            DB::commit();

            return redirect()->route('admin.committee.index')
                ->with('success', 'Membre retiré du comité avec succès.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Termine le mandat d'un membre
     */
    public function endMandate($id)
    {
        $committeeMember = CommitteeMember::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            $committeeMember->update([
                'is_current' => false,
                'end_date' => now(),
            ]);
            
            DB::commit();

            return redirect()->route('admin.committee.index')
                ->with('success', 'Mandat terminé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la fin du mandat: ' . $e->getMessage());
        }
    }
}