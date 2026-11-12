<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions with CRUD modals.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        $incomeCategories = Category::availableFor($userId)->income()->orderBy('name')->get();
        $expenseCategories = Category::availableFor($userId)->expense()->orderBy('name')->get();
        $allCategories = Category::availableFor($userId)->orderBy('type')->orderBy('name')->get();

        return view('transactions.index', compact(
            'transactions',
            'incomeCategories',
            'expenseCategories',
            'allCategories'
        ));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        // Clean amount if it contains 'Rp' or dots
        $amount = $request->amount;
        if (is_string($amount)) {
            $cleaned = preg_replace('/[^0-9]/', '', $amount);
            $request->merge(['amount' => $cleaned]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'title.required' => 'Judul transaksi wajib diisi.',
            'title.max' => 'Judul transaksi maksimal 150 karakter.',
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.numeric' => 'Nominal transaksi harus berupa angka.',
            'amount.min' => 'Nominal transaksi minimal Rp 1.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
            'description.max' => 'Catatan transaksi maksimal 500 karakter.',
        ]);

        // Validate category existence and type
        $category = Category::availableFor($userId)
            ->where('id', $validated['category_id'])
            ->where('type', $validated['type'])
            ->first();

        if (!$category) {
            return back()->withErrors([
                'category_id' => 'Kategori yang dipilih tidak valid atau tidak cocok dengan jenis transaksi.',
            ])->withInput();
        }

        Transaction::create([
            'user_id' => $userId,
            'category_id' => $category->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        return back()->with('success', 'Transaksi "' . $validated['title'] . '" berhasil ditambahkan.');
    }

    /**
     * Return transaction data for edit modal (JSON).
     */
    public function edit(Transaction $transaction): JsonResponse|View
    {
        // Security check: ensure user owns the transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit transaksi ini.');
        }

        $transaction->load('category');

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $transaction->id,
                    'title' => $transaction->title,
                    'type' => $transaction->category->type,
                    'category_id' => $transaction->category_id,
                    'amount' => (float) $transaction->amount,
                    'transaction_date' => $transaction->transaction_date->format('Y-m-d'),
                    'description' => $transaction->description,
                ],
            ]);
        }

        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // Security check: ensure user owns the transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengubah transaksi ini.');
        }

        $userId = Auth::id();

        // Clean amount
        $amount = $request->amount;
        if (is_string($amount)) {
            $cleaned = preg_replace('/[^0-9]/', '', $amount);
            $request->merge(['amount' => $cleaned]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'title.required' => 'Judul transaksi wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.min' => 'Nominal transaksi minimal Rp 1.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
        ]);

        $category = Category::availableFor($userId)
            ->where('id', $validated['category_id'])
            ->where('type', $validated['type'])
            ->first();

        if (!$category) {
            return back()->withErrors([
                'category_id' => 'Kategori yang dipilih tidak valid atau tidak cocok dengan jenis transaksi.',
            ])->withInput();
        }

        $transaction->update([
            'category_id' => $category->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        return back()->with('success', 'Transaksi "' . $transaction->title . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Security check: ensure user owns the transaction
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus transaksi ini.');
        }

        $title = $transaction->title;
        $transaction->delete();

        return back()->with('success', 'Transaksi "' . $title . '" berhasil dihapus.');
    }
}
