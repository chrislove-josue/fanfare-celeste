<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Event;
use App\Models\EventParticipant;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier les rôles et permissions
        if ($user->hasRole('super-admin') || $user->hasRole('admin') || $user->can('manage all')) {
            return $this->adminDashboard();
        } 
        
        if ($user->hasRole('manager') || $user->can('manage members')) {
            return $this->managerDashboard();
        }
        
        // Membre simple
        return $this->memberDashboard();
    }
    
    /**
     * Dashboard pour l'administrateur (accès complet)
     */
    private function adminDashboard()
    {
        // Statistiques des membres
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $pendingMembers = Member::where('status', 'pending')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();
        $newMembersThisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        // Statistiques des événements
        $totalEvents = Event::count();
        $completedEvents = Event::where('status', 'completed')->count();
        $upcomingEvents = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->count();
        $eventsThisMonth = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $upcomingEventsList = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();

        // Derniers membres
        $recentMembers = Member::latest()->take(5)->get();

        // Répartition par section
        $membersBySection = Member::query()
            ->selectRaw('section, COUNT(*) as total')
            ->whereNotNull('section')
            ->groupBy('section')
            ->get();

        // Évolution mensuelle des inscriptions
        $monthlyRegistrations = $this->getMonthlyRegistrations();

        return view('admin.dashboard', compact(
            'totalMembers',
            'activeMembers',
            'pendingMembers',
            'inactiveMembers',
            'totalEvents',
            'completedEvents',
            'upcomingEvents',
            'eventsThisMonth',
            'upcomingEventsList',
            'newMembersThisMonth',
            'recentMembers',
            'membersBySection',
            'monthlyRegistrations'
        ));
    }
    
    /**
     * Dashboard pour les managers (gestion des membres)
     */
    private function managerDashboard()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $pendingMembers = Member::where('status', 'pending')->count();
        $newMembersThisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        
        $recentMembers = Member::latest()->take(10)->get();
        $membersBySection = Member::query()
            ->selectRaw('section, COUNT(*) as total')
            ->whereNotNull('section')
            ->groupBy('section')
            ->get();

        return view('admin.manager-dashboard', compact(
            'totalMembers',
            'activeMembers',
            'pendingMembers',
            'newMembersThisMonth',
            'recentMembers',
            'membersBySection'
        ));
    }
    
    /**
     * Dashboard pour les membres simples
     */
    private function memberDashboard()
    {
        $user = Auth::user();
        $member = $user->member;
        
        $myEvents = 0;
        $myParticipations = collect();
        
        if ($member) {
            $myParticipations = EventParticipant::where('member_id', $member->id)
                ->with('event')
                ->latest()
                ->limit(5)
                ->get();
                
            $myEvents = EventParticipant::where('member_id', $member->id)->count();
        }
        
        $upcomingEvents = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->limit(5)
            ->get();

        return view('admin.member-dashboard', compact(
            'myEvents',
            'myParticipations',
            'upcomingEvents',
            'member'
        ));
    }

    /**
     * Récupère les données mensuelles d'inscription des 12 derniers mois
     */
    private function getMonthlyRegistrations()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M'),
                'count' => Member::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count()
            ];
        }
        return $months;
    }
}