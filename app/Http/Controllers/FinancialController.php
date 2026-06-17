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

    public function dashboard()
    {
        // Solde général
        $totalIncome = FinancialTransaction::where('type', 'income')->where('status', 'approved')->sum('amount');
        $totalExpenses = FinancialTransaction::where('type', 'expense')->where('status', 'approved')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        
        // Situation mensuelle
        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->where('status', 'approved')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');
            
        $monthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->where('status', 'approved')
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');
        
        $monthlyBalance = $monthlyIncome - $monthlyExpenses;
        
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
        
        // Statistiques des cotisations
        $totalContributions = Contribution::whereYear('payment_date', now()->year)->sum('amount');
        $monthlyContributions = Contribution::whereYear('payment_date', now()->year)
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->get();
        
        return view('financial.dashboard', compact(
            'balance', 'totalIncome', 'totalExpenses',
            'monthlyIncome', 'monthlyExpenses', 'monthlyBalance',
            'pendingTransactions', 'pendingAmount',
            'incomeByCategory', 'expensesByCategory',
            'monthlyEvolution', 'recentTransactions',
            'totalContributions', 'monthlyContributions'
        ));
    }

    public function transactions()
    {
        $transactions = FinancialTransaction::with('member', 'approver')
            ->latest()
            ->paginate(20);
        
        return view('financial.transactions', compact('transactions'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->get();
        $incomeCategories = FinancialTransaction::getCategories('income');
        $expenseCategories = FinancialTransaction::getCategories('expense');
        
        return view('financial.create', compact('members', 'incomeCategories', 'expenseCategories'));
    }

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
            
            return redirect()->route('financial.transactions')
                ->with('success', 'Transaction enregistrée avec succès. En attente d\'approbation.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function approve(FinancialTransaction $transaction)
    {
        $transaction->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('financial.transactions')
            ->with('success', 'Transaction approuvée avec succès');
    }

    public function reject(Request $request, FinancialTransaction $transaction)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('financial.transactions')
            ->with('warning', 'Transaction rejetée');
    }

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
        
        $incomeByCategory = $transactions->where('type', 'income')->groupBy('category');
        $expensesByCategory = $transactions->where('type', 'expense')->groupBy('category');
        
        return view('financial.report', compact(
            'transactions', 'totalIncome', 'totalExpenses', 'balance',
            'startDate', 'endDate', 'incomeByCategory', 'expensesByCategory'
        ));
    }

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
        
        $pdf = PDF::loadView('financial.pdf-report', compact(
            'transactions', 'totalIncome', 'totalExpenses', 'balance', 'startDate', 'endDate'
        ));
        
        return $pdf->download('rapport_financier_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        return Excel::download(new FinancialReportExport($startDate, $endDate), 'rapport_financier.xlsx');
    }

    public function contributions()
    {
        $members = Member::where('status', 'active')->get();
        $contributions = Contribution::with('member')
            ->latest()
            ->paginate(20);
        
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
        
        return view('financial.contributions', compact('members', 'contributions', 'unpaidMembers', 'currentMonth', 'currentYear'));
    }

    public function recordContribution(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:monthly,special,donation',
            'amount' => 'required|numeric|min:0',
            'month' => 'required_if:type,monthly|nullable|integer|min:1|max:12',
            'year' => 'required_if:type,monthly|nullable|integer',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date'
        ]);

        $validated['receipt_number'] = Contribution::generateReceiptNumber();

        Contribution::create($validated);

        return redirect()->route('financial.contributions')
            ->with('success', 'Cotisation enregistrée avec succès. Reçu N°: ' . $validated['receipt_number']);
    }
}