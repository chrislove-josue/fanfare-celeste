<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupération des données pour le dashboard (vue existante)
        $totalMembers = \App\Models\Member::count();
        $activeMembers = \App\Models\Member::active()->count();

        $upcomingEvents = \App\Models\Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->where('start_date', '>=', now())
            ->count();

        $eventsThisMonth = \App\Models\Event::whereIn('status', ['planned', 'upcoming', 'ongoing'])
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $newMembersThisMonth = \App\Models\Member::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $pendingMembers = \App\Models\Member::where('status', 'pending')->count();

        // Finances (fallback si les colonnes/relations ne sont pas exactes)
        $balance = \App\Models\FinancialTransaction::sum('amount');
        $monthlyBalance = \App\Models\FinancialTransaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $recentMembers = \App\Models\Member::latest()->take(5)->get();
        $recentTransactions = \App\Models\FinancialTransaction::latest('transaction_date')->take(5)->get();

        // Evolution mensuelle (12 mois Jan->Déc de l'année courante, avec 0 si aucun data)
        $currentYear = now()->year;

        $monthlyEvolutionRows = \App\Models\FinancialTransaction::query()
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



        // Membres par section (si la colonne existe)
        $membersBySection = \App\Models\Member::query()
            ->selectRaw('section, COUNT(*) as total')
            ->groupBy('section')
            ->get();



        return view('dashboard', compact(
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
}

