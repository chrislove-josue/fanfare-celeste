<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports');
    }

    /**
     * Rapport des membres
     */
    public function members(Request $request)
    {
        $query = Member::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }
        
        $members = $query->get();
        
        $statistics = [
            'total' => $members->count(),
            'active' => $members->where('status', 'active')->count(),
            'pending' => $members->where('status', 'pending')->count(),
            'suspended' => $members->where('status', 'suspended')->count(),
            'sections' => $members->groupBy('section')->map->count(),
            'instruments' => $members->groupBy('instrument')->map->count(),
        ];
        
        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.members-pdf', compact('members', 'statistics'));
            return $pdf->download('rapport_membres_' . date('Y-m-d') . '.pdf');
        }
        
        return view('reports.members', compact('members', 'statistics'));
    }

    /**
     * Rapport financier
     */
    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        $transactions = FinancialTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get();
            
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        
        $incomeByCategory = $transactions->where('type', 'income')->groupBy('category');
        $expensesByCategory = $transactions->where('type', 'expense')->groupBy('category');
        
        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.financial-pdf', compact(
                'transactions', 'totalIncome', 'totalExpenses', 'balance',
                'startDate', 'endDate', 'incomeByCategory', 'expensesByCategory'
            ));
            return $pdf->download('rapport_financier_' . date('Y-m-d') . '.pdf');
        }
        
        return view('reports.financial', compact(
            'transactions', 'totalIncome', 'totalExpenses', 'balance',
            'startDate', 'endDate', 'incomeByCategory', 'expensesByCategory'
        ));
    }

    /**
     * Rapport des événements
     */
    public function events(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfYear()->format('Y-m-d'));
        
        $events = Event::whereBetween('start_date', [$startDate, $endDate])->get();
        
        $statistics = [
            'total' => $events->count(),
            'by_type' => $events->groupBy('type')->map->count(),
            'by_status' => $events->groupBy('status')->map->count(),
            'participants' => $events->sum('participant_count'),
            'budget_total' => $events->sum('budget'),
            'actual_total' => $events->sum('actual_cost'),
        ];
        
        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.events-pdf', compact('events', 'statistics', 'startDate', 'endDate'));
            return $pdf->download('rapport_evenements_' . date('Y-m-d') . '.pdf');
        }
        
        return view('reports.events', compact('events', 'statistics', 'startDate', 'endDate'));
    }

    /**
     * Rapport des cotisations
     */
    public function contributions(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $contributions = Contribution::whereYear('payment_date', $year)->get();
        
        $statistics = [
            'total' => $contributions->sum('amount'),
            'monthly' => $contributions->where('type', 'monthly')->sum('amount'),
            'special' => $contributions->where('type', 'special')->sum('amount'),
            'donations' => $contributions->where('type', 'donation')->sum('amount'),
            'by_month' => $contributions->groupBy('month')->map->sum('amount'),
            'members_paid' => $contributions->pluck('member_id')->unique()->count(),
        ];
        
        // Taux de paiement mensuel
        $totalMembers = Member::where('status', 'active')->count();
        $monthlyPaid = Contribution::where('type', 'monthly')
            ->where('year', $year)
            ->where('month', now()->month)
            ->pluck('member_id')
            ->unique()
            ->count();
            
        $statistics['payment_rate'] = $totalMembers > 0 
            ? round(($monthlyPaid / $totalMembers) * 100, 2) 
            : 0;
        
        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.contributions-pdf', compact('contributions', 'statistics', 'year'));
            return $pdf->download('rapport_cotisations_' . date('Y-m-d') . '.pdf');
        }
        
        return view('reports.contributions', compact('contributions', 'statistics', 'year'));
    }
}