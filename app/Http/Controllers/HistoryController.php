<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Display a full history of user transactions with search and multi-filtering.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Transaction::where('user_id', $userId)
            ->with('category');

        // Search in title or description
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type: income or expense
        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by start date
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        // Filter by end date
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Sort order
        $sortOrder = $request->get('sort', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        $query->orderBy('transaction_date', $sortOrder)
              ->orderBy('id', $sortOrder);

        // Calculate summary for the filtered result set
        $filteredQuery = clone $query;
        $allFiltered = $filteredQuery->get();

        $filteredIncome = (float) $allFiltered->where('category.type', 'income')->sum('amount');
        $filteredExpense = (float) $allFiltered->where('category.type', 'expense')->sum('amount');
        $filteredNet = $filteredIncome - $filteredExpense;
        $totalCount = $allFiltered->count();

        // Paginate results (15 per page)
        $transactions = $query->paginate(15)->withQueryString();

        // Categories list for filter dropdown
        $allCategories = Category::availableFor($userId)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $incomeCategories = Category::availableFor($userId)->income()->orderBy('name')->get();
        $expenseCategories = Category::availableFor($userId)->expense()->orderBy('name')->get();

        return view('transactions.history', compact(
            'transactions',
            'allCategories',
            'incomeCategories',
            'expenseCategories',
            'filteredIncome',
            'filteredExpense',
            'filteredNet',
            'totalCount',
            'sortOrder'
        ));
    }
}
