<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Greeting based on Indonesian current hour
        $hour = (int) Carbon::now()->format('H');
        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        // Summary Calculations (Scoped to user)
        $totalIncome = (float) Transaction::where('user_id', $userId)
            ->income()
            ->sum('amount');

        $totalExpense = (float) Transaction::where('user_id', $userId)
            ->expense()
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $transactionCount = Transaction::where('user_id', $userId)->count();

        // Recent 5 Transactions
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Monthly Trend for the last 6 months
        $months = [];
        $monthlyIncome = [];
        $monthlyExpense = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->translatedFormat('M Y');
            $year = $date->year;
            $month = $date->month;

            $months[] = $monthLabel;

            $inc = (float) Transaction::where('user_id', $userId)
                ->income()
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $exp = (float) Transaction::where('user_id', $userId)
                ->expense()
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->sum('amount');

            $monthlyIncome[] = $inc;
            $monthlyExpense[] = $exp;
        }

        // Expense breakdown by Category for Current User (All-time or this month)
        $expenseCategories = Transaction::where('transactions.user_id', $userId)
            ->expense()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category_name, SUM(transactions.amount) as total_amount')
            ->groupBy('categories.name')
            ->orderByDesc('total_amount')
            ->get();

        $categoryLabels = $expenseCategories->pluck('category_name')->toArray();
        $categoryTotals = $expenseCategories->pluck('total_amount')->map(fn($v) => (float) $v)->toArray();

        // Categories available for quick-add modal
        $incomeCategories = Category::availableFor($userId)->income()->get();
        $outcomeCategories = Category::availableFor($userId)->expense()->get();

        return view('dashboard', compact(
            'user',
            'greeting',
            'totalIncome',
            'totalExpense',
            'balance',
            'transactionCount',
            'recentTransactions',
            'months',
            'monthlyIncome',
            'monthlyExpense',
            'categoryLabels',
            'categoryTotals',
            'incomeCategories',
            'outcomeCategories'
        ));
    }
}
