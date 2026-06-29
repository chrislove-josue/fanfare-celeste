<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\Contribution;
use App\Models\Member;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view finances')->only(['dashboard', 'transactions', 'report']);
        $this->middleware('permission:create transactions')->only(['create', 'store']);
        $this->middleware('permission:approve transactions')->only(['approve', 'reject']);
    }

    /**
     * Tableau de bord financier
     */
    public function dashboard()
    {
        // Solde général
        $totalIncome = FinancialTransaction::where('type', 'income')->where('status', 'approved')->sum('amount');
        $totalExpense = FinancialTransaction::where('type', 'expense')->where('status', 'approved')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        // Situation mensuelle
        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->where('status', 'approved')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
            
        $monthlyExpense = FinancialTransaction::where('type', 'expense')
            ->where('status', 'approved')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
        
        $monthlyBalance = $monthlyIncome - $monthlyExpense;
        
        // Transactions en attente
        $pendingTransactions = FinancialTransaction::where('status', 'pending')->count();
        $pendingAmount = FinancialTransaction::where('status', 'pending')->sum('amount');
        
        // Graphique des revenus par catégorie
        $incomeByCategory = FinancialTransaction::where('type', 'income')
            ->where('status', 'approved')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        // Graphique des dépenses par catégorie
        $expensesByCategory = FinancialTransaction::where('type', 'expense')
            ->where('status', 'approved')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        // Évolution mensuelle des finances
        $monthlyEvolution = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyEvolution[$i] = [
                'income' => FinancialTransaction::where('type', 'income')
                    ->where('status', 'approved')
                    ->whereMonth('transaction_date', $i)
                    ->whereYear('transaction_date', now()->year)
                    ->sum('amount'),
                'expense' => FinancialTransaction::where('type', 'expense')
                    ->where('status', 'approved')
                    ->whereMonth('transaction_date', $i)
                    ->whereYear('transaction_date', now()->year)
                    ->sum('amount'),
            ];
        }
        
        // Dernières transactions
        $recentTransactions = FinancialTransaction::with('member', 'approver')
            ->latest()
            ->take(10)
            ->get();
        
        // Statistiques des cotisations - CORRIGÉ
        $totalContributions = Contribution::whereYear('payment_date', now()->year)->sum('amount');
        
        // ✅ CORRECTION : Utiliser groupByRaw() au lieu de groupBy()
        $monthlyContributions = Contribution::whereYear('payment_date', now()->year)
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupByRaw('MONTH(payment_date)')
            ->orderByRaw('MONTH(payment_date)')
            ->get();
        
        return view('admin.financial-dashboard', compact(
            'balance', 'totalIncome', 'totalExpense',
            'monthlyIncome', 'monthlyExpense', 'monthlyBalance',
            'pendingTransactions', 'pendingAmount',
            'incomeByCategory', 'expensesByCategory',
            'monthlyEvolution', 'recentTransactions',
            'totalContributions', 'monthlyContributions'
        ));
    }

    /**
     * Liste des transactions
     */
    public function transactions(Request $request)
    {
        $query = FinancialTransaction::with('member', 'approver');
        
        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        // Statistiques pour les filtres
        $totalIncome = FinancialTransaction::where('type', 'income')->where('status', 'approved')->sum('amount');
        $totalExpense = FinancialTransaction::where('type', 'expense')->where('status', 'approved')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        return view('admin.financial.transactions', compact('transactions', 'totalIncome', 'totalExpense', 'balance'));
    }

    /**
     * Formulaire de création d'une transaction
     */
    public function create()
    {
        $members = Member::where('status', 'active')->orderBy('first_name')->get();
        $incomeCategories = FinancialTransaction::getCategories('income');
        $expenseCategories = FinancialTransaction::getCategories('expense');
        
        return view('admin.financial.create', compact('members', 'incomeCategories', 'expenseCategories'));
    }

    /**
     * Enregistrer une nouvelle transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'member_id' => 'nullable|exists:members,id',
            'receipt' => 'nullable|file|max:2048|mimes:pdf,jpeg,png,jpg'
        ]);

        DB::beginTransaction();
        
        try {
            if ($request->hasFile('receipt')) {
                $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            }

            $validated['status'] = 'pending';
            $validated['reference'] = 'TRX-' . strtoupper(uniqid());
            
            $transaction = FinancialTransaction::create($validated);

            DB::commit();
            
            return redirect()->route('admin.financial.transactions')
                ->with('success', 'Transaction enregistrée avec succès. Référence: ' . $transaction->reference);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Approuver une transaction
     */
    public function approve(FinancialTransaction $transaction)
    {
        $transaction->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('admin.financial.transactions')
            ->with('success', 'Transaction approuvée avec succès');
    }

    /**
     * Rejeter une transaction
     */
    public function reject(Request $request, FinancialTransaction $transaction)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('admin.financial.transactions')
            ->with('warning', 'Transaction rejetée');
    }

    /**
     * Générer un rapport financier
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        $transactions = FinancialTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('transaction_date')
            ->get();
            
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        
        $incomeByCategory = $transactions->where('type', 'income')->groupBy('category')->map->sum('amount');
        $expensesByCategory = $transactions->where('type', 'expense')->groupBy('category')->map->sum('amount');
        
        return view('admin.financial.report', compact(
            'transactions', 'totalIncome', 'totalExpenses', 'balance',
            'startDate', 'endDate', 'incomeByCategory', 'expensesByCategory'
        ));
    }

    /**
     * Exporter le rapport en PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        $transactions = FinancialTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->orderBy('transaction_date')
            ->get();
            
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        
        $pdf = PDF::loadView('admin.financial.pdf-report', compact(
            'transactions', 'totalIncome', 'totalExpenses', 'balance', 'startDate', 'endDate'
        ));
        
        return $pdf->download('rapport_financier_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporter le rapport en Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        return Excel::download(new FinancialReportExport($startDate, $endDate), 'rapport_financier_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Gestion des cotisations
     */
    public function contributions(Request $request)
    {
        $query = Contribution::with('member');
        
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        $contributions = $query->latest()->paginate(20);
        $members = Member::where('status', 'active')->get();
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Membres ayant payé ce mois
        $paidMembers = Contribution::where('type', 'monthly')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->pluck('member_id')
            ->toArray();
        
        $unpaidMembers = Member::where('status', 'active')
            ->whereNotIn('id', $paidMembers)
            ->get();
        
        // Statistiques
        $totalMonthlyContributions = Contribution::where('type', 'monthly')
            ->where('year', $currentYear)
            ->sum('amount');
            
        $totalSpecialContributions = Contribution::where('type', 'special')
            ->where('year', $currentYear)
            ->sum('amount');
            
        $totalDonations = Contribution::where('type', 'donation')
            ->where('year', $currentYear)
            ->sum('amount');
        
        return view('admin.financial.contributions', compact(
            'contributions', 'members', 'unpaidMembers', 
            'currentMonth', 'currentYear',
            'totalMonthlyContributions', 'totalSpecialContributions', 'totalDonations'
        ));
    }

    /**
     * Enregistrer une cotisation
     */
    public function recordContribution(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:monthly,special,donation',
            'amount' => 'required|numeric|min:0',
            'month' => 'required_if:type,monthly|nullable|integer|min:1|max:12',
            'year' => 'required_if:type,monthly|nullable|integer',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $validated['receipt_number'] = Contribution::generateReceiptNumber();
        $validated['status'] = 'paid';

        Contribution::create($validated);

        return redirect()->route('admin.financial.contributions')
            ->with('success', 'Cotisation enregistrée avec succès. Reçu N°: ' . $validated['receipt_number']);
    }

    /**
     * Supprimer une transaction
     */
    public function destroy(FinancialTransaction $transaction)
    {
        // Vérifier si la transaction peut être supprimée
        if ($transaction->status === 'approved') {
            return back()->with('error', 'Les transactions approuvées ne peuvent pas être supprimées.');
        }
        
        // Supprimer le reçu si existant
        if ($transaction->receipt_path) {
            \Storage::disk('public')->delete($transaction->receipt_path);
        }
        
        $transaction->delete();
        
        return redirect()->route('admin.financial.transactions')
            ->with('success', 'Transaction supprimée avec succès');
    }

    /**
     * Afficher une transaction
     */
    public function show(FinancialTransaction $transaction)
    {
        $transaction->load(['member', 'approver']);
        return view('admin.financial.show', compact('transaction'));
    }

    /**
     * Modifier une transaction
     */
    public function edit(FinancialTransaction $transaction)
    {
        if ($transaction->status === 'approved') {
            return redirect()->route('admin.financial.transactions.show', $transaction)
                ->with('error', 'Les transactions approuvées ne peuvent pas être modifiées.');
        }
        
        $members = Member::where('status', 'active')->orderBy('first_name')->get();
        $incomeCategories = FinancialTransaction::getCategories('income');
        $expenseCategories = FinancialTransaction::getCategories('expense');
        
        return view('admin.financial.edit', compact('transaction', 'members', 'incomeCategories', 'expenseCategories'));
    }

    /**
     * Mettre à jour une transaction
     */
    public function update(Request $request, FinancialTransaction $transaction)
    {
        if ($transaction->status === 'approved') {
            return redirect()->route('admin.financial.transactions.show', $transaction)
                ->with('error', 'Les transactions approuvées ne peuvent pas être modifiées.');
        }
        
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'member_id' => 'nullable|exists:members,id',
            'receipt' => 'nullable|file|max:2048|mimes:pdf,jpeg,png,jpg'
        ]);

        DB::beginTransaction();
        
        try {
            if ($request->hasFile('receipt')) {
                if ($transaction->receipt_path) {
                    \Storage::disk('public')->delete($transaction->receipt_path);
                }
                $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            }
            
            $transaction->update($validated);

            DB::commit();
            
            return redirect()->route('admin.financial.transactions.show', $transaction)
                ->with('success', 'Transaction mise à jour avec succès');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }
}