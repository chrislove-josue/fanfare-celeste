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
        // Récupérer les membres actifs du comité triés par ordre des postes
        $positionOrder = CommitteeMember::getPositionOrder();
        
        $committeeMembers = CommitteeMember::with('member')
            ->where('is_current', true)
            ->get()
            ->sortBy(function($item) use ($positionOrder) {
                return $positionOrder[$item->position] ?? 99;
            });
        
        // Récupérer aussi les anciens membres
        $formerMembers = CommitteeMember::with('member')
            ->where('is_current', false)
            ->orderBy('end_date', 'desc')
            ->get();
        
        // Statistiques
        $totalMembers = Member::active()->count();
        $positions = CommitteeMember::getPositions();
        $positionsByCategory = CommitteeMember::getPositionsByCategory();
        
        // Vérifier les postes vacants
        $occupiedPositions = CommitteeMember::where('is_current', true)
            ->pluck('position')
            ->toArray();
        
        $vacantPositions = array_diff(array_keys($positions), $occupiedPositions);
        
        return view('admin.committee.index', compact(
            'committeeMembers',
            'formerMembers',
            'totalMembers',
            'positions',
            'positionsByCategory',
            'vacantPositions'
        ));
    }

    /**
     * Affiche le formulaire d'ajout
     */
    public function create()
    {
        // Récupérer les membres déjà dans le comité
        $committeeMemberIds = CommitteeMember::where('is_current', true)
            ->pluck('member_id')
            ->toArray();
        
        // Membres disponibles pour le comité (tous les membres actifs sauf ceux déjà dans le comité)
        $availableMembers = Member::active()
            ->whereNotIn('id', $committeeMemberIds)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        $positions = CommitteeMember::getPositions();
        $positionsByCategory = CommitteeMember::getPositionsByCategory();
        
        // Postes déjà occupés
        $occupiedPositions = CommitteeMember::where('is_current', true)
            ->pluck('position')
            ->toArray();
        
        return view('admin.committee.create', compact(
            'availableMembers',
            'positions',
            'positionsByCategory',
            'occupiedPositions'
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
            'responsibilities' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            // Vérifier si le membre est déjà dans le comité
            $exists = CommitteeMember::where('member_id', $validated['member_id'])
                ->where('is_current', true)
                ->exists();
            
            if ($exists) {
                return back()->with('error', 'Ce membre est déjà dans le comité actif.')
                             ->withInput();
            }

            // Vérifier si le poste est déjà occupé (pour les postes uniques)
            $uniquePositions = ['le_maitre', 'general_secretary', 'treasurer', 'conductor', 'deputy_conductor'];
            
            if (in_array($validated['position'], $uniquePositions)) {
                $positionOccupied = CommitteeMember::where('position', $validated['position'])
                    ->where('is_current', true)
                    ->exists();
                
                if ($positionOccupied) {
                    return back()->with('error', 'Ce poste est déjà occupé par un autre membre.')
                                 ->withInput();
                }
            }

            // Définir l'ordre automatiquement
            $positionOrder = CommitteeMember::getPositionOrder();
            $validated['position_order'] = $positionOrder[$validated['position']] ?? 99;
            
            $validated['is_current'] = $request->has('is_current');

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
        $positionsByCategory = CommitteeMember::getPositionsByCategory();
        
        // Postes déjà occupés (sauf celui en cours d'édition)
        $occupiedPositions = CommitteeMember::where('is_current', true)
            ->where('id', '!=', $id)
            ->pluck('position')
            ->toArray();
        
        return view('admin.committee.edit', compact(
            'committeeMember',
            'positions',
            'positionsByCategory',
            'occupiedPositions'
        ));
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
            'responsibilities' => 'nullable|string',
            'is_current' => 'boolean',
        ]);

        DB::beginTransaction();
        
        try {
            // Vérifier si le poste est déjà occupé par un autre membre
            $uniquePositions = ['le_maitre', 'general_secretary', 'treasurer', 'conductor', 'deputy_conductor'];
            
            if (in_array($validated['position'], $uniquePositions)) {
                $positionOccupied = CommitteeMember::where('position', $validated['position'])
                    ->where('is_current', true)
                    ->where('id', '!=', $id)
                    ->exists();
                
                if ($positionOccupied) {
                    return back()->with('error', 'Ce poste est déjà occupé par un autre membre.')
                                 ->withInput();
                }
            }
            
            // Mettre à jour l'ordre
            $positionOrder = CommitteeMember::getPositionOrder();
            $validated['position_order'] = $positionOrder[$validated['position']] ?? 99;
            
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

    /**
     * Réorganiser l'ordre des membres du comité
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'exists:committee_members,id',
        ]);

        DB::beginTransaction();
        
        try {
            foreach ($request->orders as $index => $id) {
                CommitteeMember::where('id', $id)->update([
                    'position_order' => $index + 1,
                ]);
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ordre mis à jour avec succès.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les membres disponibles pour un poste spécifique
     * (Utilisé pour l'API/AJAX)
     */
    public function getAvailableMembers(Request $request)
    {
        $position = $request->get('position');
        
        $committeeMemberIds = CommitteeMember::where('is_current', true)
            ->pluck('member_id')
            ->toArray();
        
        $availableMembers = Member::active()
            ->whereNotIn('id', $committeeMemberIds)
            ->select('id', 'first_name', 'last_name', 'member_number', 'email')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->full_name,
                    'member_number' => $member->member_number,
                    'email' => $member->email,
                ];
            });
        
        return response()->json($availableMembers);
    }
}