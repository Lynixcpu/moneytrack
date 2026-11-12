<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
    ];

    /**
     * Category belongs to an optional user (null means default system category).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category has many transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope for a specific user plus system defaults.
     */
    public function scopeAvailableFor(Builder $query, ?int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id');
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        });
    }

    /**
     * Scope income categories.
     */
    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope expense categories.
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }
}
