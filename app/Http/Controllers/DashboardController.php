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

        // Evolution mensuelle (12 derniers mois)
        $monthlyEvolution = \App\Models\FinancialTransaction::query()
            ->selectRaw("MONTH(transaction_date) as month, 
                SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense")
            ->whereYear('transaction_date', now()->year)
            ->groupByRaw('MONTH(transaction_date)')
            ->orderByRaw('MONTH(transaction_date)')
            ->get();

        // dashboard.blade.php utilise array_column($monthlyEvolution, 'income/expense')
        // => on convertit en array PHP ici.
        $monthlyEvolution = $monthlyEvolution->map(function ($row) {
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

        // Eviter l'appel à Gate/HasPermissions de Spatie si les tables Spatie n'existent pas.
        // Si permissions/roles sont absentes, on coupe les checks.
        $hasSpatie = $this->hasSpatieTables();

        if (! $hasSpatie) {
            \Illuminate\Support\Facades\Gate::forget('view events');
        }

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

