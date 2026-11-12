<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'amount',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Transaction belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope query to only include transactions of a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for income transactions.
     */
    public function scopeIncome(Builder $query): Builder
    {
        return $query->whereHas('category', function ($q) {
            $q->where('type', 'income');
        });
    }

    /**
     * Scope for expense transactions.
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->whereHas('category', function ($q) {
            $q->where('type', 'expense');
        });
    }

    /**
     * Get formatted rupiah amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted Indonesian transaction date.
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->transaction_date)->translatedFormat('d M Y');
    }
}
