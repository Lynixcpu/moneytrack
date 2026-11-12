<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display financial report with analytics, charts, and period filtering.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $period = $request->get('period', 'this_month');

        $startDate = null;
        $endDate = null;
        $periodTitle = 'Bulan Ini';

        $now = Carbon::now();

        switch ($period) {
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                $periodTitle = 'Bulan Lalu (' . $startDate->translatedFormat('F Y') . ')';
                break;

            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodTitle = 'Tahun ' . $now->year;
                break;

            case 'all':
                $periodTitle = 'Semua Waktu';
                break;

            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $periodTitle = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
                } else {
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    $period = 'this_month';
                    $periodTitle = $startDate->translatedFormat('F Y');
                }
                break;

            case 'this_month':
            default:
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodTitle = $startDate->translatedFormat('F Y');
                $period = 'this_month';
                break;
        }

        // Base query for period
        $query = Transaction::where('user_id', $userId)->with('category');

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ]);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Calculate Totals
        $totalIncome = (float) $transactions->where('category.type', 'income')->sum('amount');
        $totalExpense = (float) $transactions->where('category.type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Savings / Efficiency Rate
        $savingsRate = $totalIncome > 0 ? round(($netBalance / $totalIncome) * 100, 1) : 0;

        // Group expenses by category
        $expenseByCategory = $transactions->where('category.type', 'expense')
            ->groupBy('category.name')
            ->map(function ($items, $categoryName) use ($totalExpense) {
                $sum = (float) $items->sum('amount');
                $percentage = $totalExpense > 0 ? round(($sum / $totalExpense) * 100, 1) : 0;
                return [
                    'name' => $categoryName,
                    'total' => $sum,
                    'percentage' => $percentage,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Group income by category
        $incomeByCategory = $transactions->where('category.type', 'income')
            ->groupBy('category.name')
            ->map(function ($items, $categoryName) use ($totalIncome) {
                $sum = (float) $items->sum('amount');
                $percentage = $totalIncome > 0 ? round(($sum / $totalIncome) * 100, 1) : 0;
                return [
                    'name' => $categoryName,
                    'total' => $sum,
                    'percentage' => $percentage,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        // Trend Chart Data (daily if within a month, or monthly if year/all)
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        if ($period === 'this_year' || $period === 'all') {
            // Group by month
            $monthsMap = [];
            for ($m = 1; $m <= 12; $m++) {
                $key = sprintf('%02d', $m);
                $label = Carbon::createFromDate(null, $m, 1)->translatedFormat('M');
                $monthsMap[$key] = [
                    'label' => $label,
                    'income' => 0,
                    'expense' => 0,
                ];
            }

            foreach ($transactions as $t) {
                $mKey = Carbon::parse($t->transaction_date)->format('m');
                if (isset($monthsMap[$mKey])) {
                    if ($t->category && $t->category->type === 'income') {
                        $monthsMap[$mKey]['income'] += (float) $t->amount;
                    } else {
                        $monthsMap[$mKey]['expense'] += (float) $t->amount;
                    }
                }
            }

            foreach ($monthsMap as $item) {
                $chartLabels[] = $item['label'];
                $chartIncome[] = $item['income'];
                $chartExpense[] = $item['expense'];
            }
        } else {
            // Group by day for month or custom
            $groupedByDate = $transactions->groupBy(function ($item) {
                return Carbon::parse($item->transaction_date)->format('d M');
            });

            // If empty or few, let's create intervals
            if ($startDate && $endDate && $startDate->diffInDays($endDate) <= 31) {
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $dayFormat = $current->format('d M');
                    $dayStr = $current->format('Y-m-d');
                    $dayTrans = $transactions->where('transaction_date', $dayStr);

                    $chartLabels[] = $dayFormat;
                    $chartIncome[] = (float) $dayTrans->where('category.type', 'income')->sum('amount');
                    $chartExpense[] = (float) $dayTrans->where('category.type', 'expense')->sum('amount');

                    $current->addDay();
                }
            } else {
                foreach ($groupedByDate as $dateLabel => $items) {
                    $chartLabels[] = $dateLabel;
                    $chartIncome[] = (float) $items->where('category.type', 'income')->sum('amount');
                    $chartExpense[] = (float) $items->where('category.type', 'expense')->sum('amount');
                }
            }
        }

        return view('reports.index', compact(
            'period',
            'periodTitle',
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'savingsRate',
            'expenseByCategory',
            'incomeByCategory',
            'transactions',
            'chartLabels',
            'chartIncome',
            'chartExpense'
        ));
    }
}
