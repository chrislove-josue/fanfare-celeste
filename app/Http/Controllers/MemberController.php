<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Constructeur avec permissions
     */
    public function __construct()
    {
        $this->middleware('permission:view members')->only(['index', 'show', 'manageRoles', 'viewDocument']);
        $this->middleware('permission:create members')->only(['create', 'store']);
        $this->middleware('permission:edit members')->only(['edit', 'update']);
        $this->middleware('permission:delete members')->only(['destroy']);
        $this->middleware('permission:approve members')->only(['approve', 'reject']);
    }

    // ==========================================
    // INDEX - Liste des membres
    // ==========================================
    public function index(Request $request)
    {
        $query = Member::query();
        
        // 🔍 Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
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
        
        // Filtrer par compte utilisateur
        if ($request->filled('has_user')) {
            if ($request->has_user === 'yes') {
                $query->whereHas('user');
            } elseif ($request->has_user === 'no') {
                $query->whereDoesntHave('user');
            }
        }
        
        // Tri
        $sort = $request->sort ?? 'member_number';
        $direction = $request->direction ?? 'asc';
        $query->orderBy($sort, $direction);
        
        $members = $query->paginate(15);
        
        // 📊 Statistiques pour les cartes
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $pendingMembers = Member::where('status', 'pending')->count();
        $newMembers = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $usersCount = Member::whereHas('user')->count();
        
        // 🏷️ Options pour les filtres
        $sections = Member::select('section')->distinct()->whereNotNull('section')->pluck('section');
        $instruments = Member::select('instrument')->distinct()->whereNotNull('instrument')->pluck('instrument');
        $statuses = ['pending', 'active', 'suspended', 'withdrawn', 'deceased', 'training'];

        
        return view('admin.members.index', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'pendingMembers',
            'newMembers',
            'usersCount',
            'sections',
            'instruments',
            'statuses'
        ));
    }

    // ==========================================
    // CREATE - Formulaire de création
    // ==========================================
    public function create()
    {
        $sections = Member::getSections();
        $instruments = Member::getInstruments();
        $instrumentLevels = Member::getInstrumentLevels();
        $statuses = Member::getStatuses();
        
        return view('admin.members.create', compact(
            'sections',
            'instruments',
            'instrumentLevels',
            'statuses'
        ));
    }


    // ==========================================
// STORE - Enregistrement d'un nouveau membre
// ==========================================
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:members,email',
        'phone' => 'required|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
        'paroisse' => 'required|string|max:255',
        'sous_region' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'birth_place' => 'nullable|string|max:255',
        'instrument' => 'nullable|string|max:100',
        'instrument_level' => 'nullable|string|in:Soprano,Alto,Ténor,Basse',
        'entry_date' => 'nullable|date',
        'section' => 'nullable|string|max:100',
        'section_role' => 'nullable|string|max:100',
        'photo' => 'nullable|image|max:2048',
        'id_document' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,webp',
        'adhesion_letter' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,rtf',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'medical_notes' => 'nullable|string',
        'skills' => 'nullable|string',
        'status' => 'nullable|in:active,pending,inactive,suspended,withdrawn,deceased,training',
    ]);

    DB::beginTransaction();
    
    try {
        // Générer le numéro de membre
        $validated['member_number'] = Member::generateMemberNumber();
        $validated['status'] = $request->status ?? 'pending';

        // Générer un email par défaut si non fourni
        if (empty($validated['email'])) {
            $validated['email'] = Member::generateDefaultEmail($validated['first_name'], $validated['last_name']);
        }

        // Combiner Paroisse et Sous-Région dans le champ address avec les préfixes
        $address = 'Paroisse: ' . $validated['paroisse'];
        if (!empty($validated['sous_region'])) {
            $address .= ' | Sous-région: ' . $validated['sous_region'];
        }
        $validated['address'] = $address;

        // Supprimer les champs temporaires
        unset($validated['paroisse'], $validated['sous_region']);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $this->ensureDirectoryExists('members/photos');
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        // Gestion du document d'identité
        if ($request->hasFile('id_document')) {
            $this->ensureDirectoryExists('members/documents');
            $validated['id_document'] = $request->file('id_document')->store('members/documents', 'public');
        }

        // Gestion de la lettre d'adhésion
        if ($request->hasFile('adhesion_letter')) {
            $this->ensureDirectoryExists('members/letters');
            $validated['adhesion_letter'] = $request->file('adhesion_letter')->store('members/letters', 'public');
        }

        // Créer le membre
        $member = Member::create($validated);

        // Créer automatiquement un compte utilisateur
        $this->createUserAccount($member);

        DB::commit();
        
        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Membre ajouté avec succès. Numéro: ' . $member->member_number);
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage())
                     ->withInput();
    }
}

    // ==========================================
    // SHOW - Détails d'un membre
    // ==========================================
    public function show(Member $member)
    {
        // 📊 Statistiques du membre
        $contributions = $member->transactions()
            ->where('type', 'income')
            ->sum('amount');
            
        $transactionsCount = $member->transactions()->count();
        $eventsCount = $member->eventParticipations()->count();
        
        // Événements à venir du membre
        $upcomingEvents = $member->eventParticipations()
            ->with('event')
            ->whereHas('event', function($q) {
                $q->where('start_date', '>=', now());
            })
            ->get();
        
        // Dernières transactions
        $recentTransactions = $member->transactions()
            ->latest('transaction_date')
            ->take(5)
            ->get();
        
        // Position au bureau
        $committeePosition = $member->currentCommitteePosition;
        
        // Rôles de l'utilisateur
        $userRoles = $member->user ? $member->user->roles->pluck('name')->toArray() : [];
        
        return view('admin.members.show', compact(
            'member',
            'contributions',
            'transactionsCount',
            'eventsCount',
            'upcomingEvents',
            'recentTransactions',
            'committeePosition',
            'userRoles'
        ));
    }

    // ==========================================
    // EDIT - Formulaire de modification
    // ==========================================
    public function edit(Member $member)
    {
        $sections = Member::getSections();
        $instruments = Member::getInstruments();
        $instrumentLevels = Member::getInstrumentLevels();
        $statuses = Member::getStatuses();
        
        // ✅ Récupérer tous les rôles disponibles
        $availableRoles = Role::all();
        
        // ✅ Récupérer les rôles de l'utilisateur
        $userRoles = $member->user ? $member->user->roles->pluck('name')->toArray() : [];
        
        return view('admin.members.edit', compact(
            'member',
            'sections',
            'instruments',
            'instrumentLevels',
            'statuses',
            'availableRoles',
            'userRoles'
        ));
    }


    // ==========================================
// UPDATE - Mise à jour d'un membre
// ==========================================
public function update(Request $request, Member $member)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:members,email,' . $member->id,
        'phone' => 'required|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
        'paroisse' => 'required|string|max:255',
        'sous_region' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'birth_place' => 'nullable|string|max:255',
        'instrument' => 'nullable|string|max:100',
        'instrument_level' => 'nullable|string|in:Soprano,Alto,Ténor,Basse',
        'entry_date' => 'nullable|date',
        'section' => 'nullable|string|max:100',
        'section_role' => 'nullable|string|max:100',
        'status' => 'required|in:active,pending,inactive,suspended,withdrawn,deceased,training',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'medical_notes' => 'nullable|string',
        'skills' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
        'adhesion_letter' => 'nullable|file|max:5120|mimes:pdf,doc,docx,txt,rtf',
    ]);

    DB::beginTransaction();
    
    try {
        // Générer un email par défaut si non fourni
        if (empty($validated['email'])) {
            $validated['email'] = Member::generateDefaultEmail($validated['first_name'], $validated['last_name']);
        }

        // Combiner Paroisse et Sous-Région dans le champ address avec les préfixes
        $address = 'Paroisse: ' . $validated['paroisse'];
        if (!empty($validated['sous_region'])) {
            $address .= ' | Sous-région: ' . $validated['sous_region'];
        }
        $validated['address'] = $address;

        // Supprimer les champs temporaires
        unset($validated['paroisse'], $validated['sous_region']);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $this->ensureDirectoryExists('members/photos');
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        // Gestion du document d'identité
        if ($request->has('remove_document') && $request->remove_document == '1') {
            if ($member->id_document && Storage::disk('public')->exists($member->id_document)) {
                Storage::disk('public')->delete($member->id_document);
            }
            $validated['id_document'] = null;
        }
        
        if ($request->hasFile('id_document')) {
            $file = $request->file('id_document');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];
            
            if (!in_array($extension, $allowedTypes)) {
                throw new \Exception('Format de fichier non supporté. Utilisez JPG, PNG, PDF ou WebP.');
            }
            
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('Le fichier est trop volumineux. Taille max : 5MB.');
            }
            
            if ($member->id_document && Storage::disk('public')->exists($member->id_document)) {
                Storage::disk('public')->delete($member->id_document);
            }
            
            $this->ensureDirectoryExists('members/documents');
            $validated['id_document'] = $file->store('members/documents', 'public');
        }

        // Gestion de la lettre d'adhésion
        if ($request->has('remove_adhesion_letter') && $request->remove_adhesion_letter == '1') {
            if ($member->adhesion_letter && Storage::disk('public')->exists($member->adhesion_letter)) {
                Storage::disk('public')->delete($member->adhesion_letter);
            }
            $validated['adhesion_letter'] = null;
        }
        
        if ($request->hasFile('adhesion_letter')) {
            $file = $request->file('adhesion_letter');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
            
            if (!in_array($extension, $allowedTypes)) {
                throw new \Exception('Format de fichier non supporté. Utilisez PDF, DOC, DOCX, TXT ou RTF.');
            }
            
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('Le fichier est trop volumineux. Taille max : 5MB.');
            }
            
            if ($member->adhesion_letter && Storage::disk('public')->exists($member->adhesion_letter)) {
                Storage::disk('public')->delete($member->adhesion_letter);
            }
            
            $this->ensureDirectoryExists('members/letters');
            $validated['adhesion_letter'] = $file->store('members/letters', 'public');
        }

        // Mettre à jour le membre
        $member->update($validated);

        // Mettre à jour l'utilisateur associé
        if ($member->user) {
            $member->user->update([
                'name' => $member->full_name,
                'email' => $member->email,
            ]);
            
            if ($request->has('roles')) {
                $member->user->syncRoles($request->roles);
            }
        }

        DB::commit();

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Membre mis à jour avec succès');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur : ' . $e->getMessage())
                     ->withInput();
    }
}

    

    // ==========================================
    // DESTROY - Suppression d'un membre
    // ==========================================
    public function destroy(Member $member)
    {
        DB::beginTransaction();
        
        try {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            if ($member->id_document && Storage::disk('public')->exists($member->id_document)) {
                Storage::disk('public')->delete($member->id_document);
            }
            if ($member->adhesion_letter && Storage::disk('public')->exists($member->adhesion_letter)) {
                Storage::disk('public')->delete($member->adhesion_letter);
            }
            
            if ($member->user) {
                $member->user->delete();
            }
            
            $member->delete();
            
            DB::commit();

            return redirect()->route('admin.members.index')
                ->with('success', 'Membre supprimé avec succès');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    // ==========================================
    // APPROVE - Approuver un membre en attente
    // ==========================================
    public function approve(Member $member)
    {
        DB::beginTransaction();
        
        try {
            $member->update(['status' => 'active']);
            
            if (!$member->user) {
                $this->createUserAccount($member);
            }
            
            DB::commit();
            
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Membre approuvé avec succès. Un compte utilisateur a été créé.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'approbation: ' . $e->getMessage());
        }
    }

    // ==========================================
    // REJECT - Rejeter un membre
    // ==========================================
    public function reject(Member $member)
    {
        $member->update(['status' => 'inactive']);
        
        return redirect()->route('admin.members.show', $member)
            ->with('warning', 'Membre rejeté avec succès');
    }

    // ==========================================
    // GENERATE CARD - Générer la carte de membre
    // ==========================================
    public function generateCard(Member $member)
    {
        $qrCode = $member->memberCardQrCode;
        
        $pdf = Pdf::loadView('admin.members.card', compact('member', 'qrCode'));
        $pdf->setPaper([0, 0, 297.64, 419.53]);
        
        return $pdf->download('carte_membre_' . $member->member_number . '.pdf');
    }

    // ==========================================
    // VIEW DOCUMENT - Afficher le document d'identité
    // ==========================================
    public function viewDocument(Member $member)
    {
        if (!$member->id_document) {
            return redirect()->route('admin.members.show', $member)
                ->with('error', 'Ce membre n\'a pas de document d\'identité.');
        }

        $path = storage_path('app/public/' . $member->id_document);
        
        if (!file_exists($path)) {
            return redirect()->route('admin.members.show', $member)
                ->with('error', 'Le fichier n\'existe pas.');
        }

        $mime = mime_content_type($path);
        $isImage = str_starts_with($mime, 'image/');

        if ($isImage) {
            $imageData = base64_encode(file_get_contents($path));
            $imageSrc = 'data:' . $mime . ';base64,' . $imageData;
            
            return view('admin.members.document', compact('member', 'imageSrc'));
        } else {
            return response()->file($path, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="document_' . $member->member_number . '.' . pathinfo($path, PATHINFO_EXTENSION) . '"'
            ]);
        }
    }

    // ==========================================
    // EXPORT - Exporter les membres en PDF
    // ==========================================
    public function export()
    {
        $members = Member::orderBy('last_name')->get();
        $totalMembers = $members->count();
        $activeMembers = $members->where('status', 'active')->count();
        
        $pdf = Pdf::loadView('admin.members.export', compact('members', 'totalMembers', 'activeMembers'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('liste_membres_' . date('Y-m-d') . '.pdf');
    }

    // ==========================================
    // CREATE USER - Créer un compte utilisateur
    // ==========================================
    public function createUser(Request $request, Member $member)
    {
        if ($member->user) {
            return back()->with('error', 'Ce membre a déjà un compte utilisateur.');
        }

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        
        try {
            $password = $request->password;
            
            $user = User::create([
                'name' => $member->full_name,
                'email' => $member->email,
                'password' => Hash::make($password),
                'member_id' => $member->id,
            ]);
            
            $user->assignRole('member');
            
            DB::commit();

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Compte utilisateur créé avec succès pour ' . $member->full_name . '. Mot de passe : ' . $password);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création du compte: ' . $e->getMessage());
        }
    }

    // ==========================================
    // RESET PASSWORD - Réinitialiser le mot de passe
    // ==========================================
    public function resetPassword(Request $request, Member $member)
    {
        if (!$member->user) {
            return back()->with('error', 'Ce membre n\'a pas de compte utilisateur.');
        }

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        
        try {
            $password = $request->password;
            
            $member->user->update([
                'password' => Hash::make($password),
            ]);
            
            DB::commit();

            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Mot de passe réinitialisé avec succès pour ' . $member->full_name . '. Nouveau mot de passe : ' . $password);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la réinitialisation: ' . $e->getMessage());
        }
    }

    // ==========================================
    // MANAGE ROLES - Gestion des rôles
    // ==========================================
    public function manageRoles(Member $member)
    {
        if (!$member->user) {
            return redirect()->route('admin.members.show', $member)
                ->with('error', 'Ce membre n\'a pas de compte utilisateur. Veuillez d\'abord créer un compte.');
        }
        
        $availableRoles = Role::all();
        $userRoles = $member->user->roles->pluck('name')->toArray();
        
        return view('admin.members.roles', compact('member', 'availableRoles', 'userRoles'));
    }

    // ==========================================
    // ASSIGN ROLE - Assigner un rôle
    // ==========================================
    public function assignRole(Request $request, Member $member)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        if (!$member->user) {
            return back()->with('error', 'Ce membre n\'a pas de compte utilisateur.');
        }

        DB::beginTransaction();
        
        try {
            $role = Role::findByName($request->role);
            $member->user->assignRole($role);
            
            DB::commit();
            
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Rôle "' . $role->name . '" assigné avec succès à ' . $member->full_name);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'assignation: ' . $e->getMessage());
        }
    }

    // ==========================================
    // REMOVE ROLE - Retirer un rôle
    // ==========================================
    public function removeRole(Request $request, Member $member)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        if (!$member->user) {
            return back()->with('error', 'Ce membre n\'a pas de compte utilisateur.');
        }

        DB::beginTransaction();
        
        try {
            $role = Role::findByName($request->role);
            $member->user->removeRole($role);
            
            DB::commit();
            
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Rôle "' . $role->name . '" retiré avec succès de ' . $member->full_name);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors du retrait: ' . $e->getMessage());
        }
    }

    // ==========================================
    // SYNC ROLES - Synchroniser les rôles
    // ==========================================
    public function syncRoles(Request $request, Member $member)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,name'
        ]);

        if (!$member->user) {
            return back()->with('error', 'Ce membre n\'a pas de compte utilisateur.');
        }

        DB::beginTransaction();
        
        try {
            $roles = Role::whereIn('name', $request->roles ?? [])->get();
            $member->user->syncRoles($roles);
            
            DB::commit();
            
            return redirect()->route('admin.members.show', $member)
                ->with('success', 'Rôles mis à jour avec succès pour ' . $member->full_name);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la synchronisation: ' . $e->getMessage());
        }
    }

    // ==========================================
    // VERIFY - Page publique de vérification (scan QR code)
    // ==========================================
    public function verify(string $memberNumber)
    {
        $member = Member::where('member_number', $memberNumber)->first();

        if (!$member) {
            abort(404, 'Membre introuvable.');
        }

        $currentCommitteePosition = $member->currentCommitteePosition;

        // Découpage du champ "address" au format "Paroisse: X | Sous-région: Y"
        $paroisse = null;
        $sousRegion = null;
        if ($member->address) {
            if (preg_match('/Paroisse\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
                $paroisse = trim($m[1]);
            }
            if (preg_match('/Sous[-\s]?r[ée]gion\s*:\s*(.*?)(?:\||$)/iu', $member->address, $m)) {
                $sousRegion = trim($m[1]);
            }
            if (!$paroisse && !$sousRegion) {
                $paroisse = trim($member->address);
            }
        }

        return view('members.verify', compact(
            'member',
            'currentCommitteePosition',
            'paroisse',
            'sousRegion'
        ));
    }

    // ==========================================
    // MÉTHODES PRIVÉES
    // ==========================================

    private function createUserAccount(Member $member): User
    {
        $password = Str::random(10);
        
        $user = User::create([
            'name' => $member->full_name,
            'email' => $member->email,
            'password' => Hash::make($password),
            'member_id' => $member->id,
        ]);

        $user->assignRole('member');

        return $user;
    }

    private function ensureDirectoryExists(string $path): void
    {
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
    }
}