<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\FinancialTransaction;
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
        
        if ($user->can('manage finances')) {
            return $this->financialDashboard();
        }
        
        // Membre simple
        return $this->memberDashboard();
    }
    
    /**
     * Dashboard pour l'administrateur (accès complet)
     */
    private function adminDashboard()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::active()->count();

        $upcomingEvents = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->count();

        $eventsThisMonth = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $newMembersThisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $pendingMembers = Member::where('status', 'pending')->count();

        $balance = FinancialTransaction::sum('amount');
        $monthlyBalance = FinancialTransaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $recentMembers = Member::latest()->take(5)->get();
        $recentTransactions = FinancialTransaction::latest('transaction_date')->take(5)->get();

        $currentYear = now()->year;
        $monthlyEvolutionRows = FinancialTransaction::query()
            ->selectRaw("MONTH(transaction_date) as month, 
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense")
            ->whereYear('transaction_date', $currentYear)
            ->groupByRaw('MONTH(transaction_date)')
            ->get();

        $monthlyEvolutionByMonth = $monthlyEvolutionRows->keyBy('month');
        $monthlyEvolution = collect(range(1, 12))->map(function (int $month) use ($monthlyEvolutionByMonth) {
            $row = $monthlyEvolutionByMonth->get($month);
            return [
                'income' => (float) ($row->income ?? 0),
                'expense' => (float) ($row->expense ?? 0),
            ];
        })->values()->toArray();

        $membersBySection = Member::query()
            ->selectRaw('section, COUNT(*) as total')
            ->groupBy('section')
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'activeMembers',
            'balance',
            'monthlyBalance',
            'upcomingEvents',
            'eventsThisMonth',
            'newMembersThisMonth',
            'pendingMembers',
            'recentMembers',
            'recentTransactions',
            'monthlyEvolution',
            'membersBySection'
        ));
    }
    
    /**
     * Dashboard pour les managers (gestion des membres)
     */
    private function managerDashboard()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::active()->count();
        $pendingMembers = Member::where('status', 'pending')->count();
        $newMembersThisMonth = Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        
        $recentMembers = Member::latest()->take(10)->get();
        $membersBySection = Member::query()
            ->selectRaw('section, COUNT(*) as total')
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
     * Dashboard pour les gestionnaires financiers
     */
    private function financialDashboard()
    {
        $balance = FinancialTransaction::sum('amount');
        $monthlyBalance = FinancialTransaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
        
        $recentTransactions = FinancialTransaction::latest('transaction_date')->take(10)->get();
        
        $currentYear = now()->year;
        $monthlyEvolutionRows = FinancialTransaction::query()
            ->selectRaw("MONTH(transaction_date) as month, 
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense")
            ->whereYear('transaction_date', $currentYear)
            ->groupByRaw('MONTH(transaction_date)')
            ->get();

        $monthlyEvolutionByMonth = $monthlyEvolutionRows->keyBy('month');
        $monthlyEvolution = collect(range(1, 12))->map(function (int $month) use ($monthlyEvolutionByMonth) {
            $row = $monthlyEvolutionByMonth->get($month);
            return [
                'income' => (float) ($row->income ?? 0),
                'expense' => (float) ($row->expense ?? 0),
            ];
        })->values()->toArray();

        return view('admin.financial-dashboard', compact(
            'balance',
            'monthlyBalance',
            'recentTransactions',
            'monthlyEvolution'
        ));
    }
    
    /**
     * Dashboard pour les membres simples
     * Utilise la relation member_id dans users
     */
    private function memberDashboard()
    {
        $user = Auth::user();
        
        // Récupérer le membre via la relation définie dans User
        $member = $user->member; // Utilise member_id dans la table users
        
        $myContributions = 0;
        $myTransactions = 0;
        $myEvents = 0;
        
        if ($member) {
            $myContributions = FinancialTransaction::where('member_id', $member->id)
                ->where('type', 'income')
                ->sum('amount');
                
            $myTransactions = FinancialTransaction::where('member_id', $member->id)->count();
            
            $myEvents = \App\Models\EventParticipation::where('member_id', $member->id)->count();

        }
        
        $upcomingEvents = Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->limit(5)
            ->get();

        return view('admin.member-dashboard', compact(
            'myContributions',
            'myTransactions',
            'myEvents',
            'upcomingEvents',
            'member'
        ));
    }
}