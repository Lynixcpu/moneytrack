<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get transactions belonging to the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get custom categories belonging to the user.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Calculate total income for user.
     */
    public function totalIncome(): float
    {
        return (float) $this->transactions()
            ->whereHas('category', function ($q) {
                $q->where('type', 'income');
            })
            ->sum('amount');
    }

    /**
     * Calculate total expense for user.
     */
    public function totalExpense(): float
    {
        return (float) $this->transactions()
            ->whereHas('category', function ($q) {
                $q->where('type', 'expense');
            })
            ->sum('amount');
    }

    /**
     * Calculate net balance (Saldo = Total Pemasukan - Total Pengeluaran).
     */
    public function currentBalance(): float
    {
        return $this->totalIncome() - $this->totalExpense();
    }
}
