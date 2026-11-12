<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'CategorySeeder']);
    }

    public function test_user_can_create_income_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::where('type', 'income')->first();

        $response = $this->actingAs($user)->post('/transactions', [
            'title' => 'Beasiswa Prestasi',
            'type' => 'income',
            'category_id' => $category->id,
            'amount' => '1.500.000',
            'transaction_date' => now()->format('Y-m-d'),
            'description' => 'Uang beasiswa semester ini',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'title' => 'Beasiswa Prestasi',
            'amount' => 1500000,
        ]);
    }

    public function test_user_can_create_expense_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::where('type', 'expense')->first();

        $response = $this->actingAs($user)->post('/transactions', [
            'title' => 'Beli Buku Pemrograman Web',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => '120.000',
            'transaction_date' => now()->format('Y-m-d'),
            'description' => 'Buku teks kuliah',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'title' => 'Beli Buku Pemrograman Web',
            'amount' => 120000,
        ]);
    }

    public function test_user_can_update_their_own_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::where('type', 'expense')->first();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Makan Siang',
            'amount' => 25000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->put("/transactions/{$transaction->id}", [
            'title' => 'Makan Siang Spesial',
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => '35.000',
            'transaction_date' => now()->format('Y-m-d'),
            'description' => 'Di kantin kampus bersama teman',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'title' => 'Makan Siang Spesial',
            'amount' => 35000,
        ]);
    }

    public function test_user_cannot_update_another_users_transaction(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::where('type', 'income')->first();

        $transactionB = Transaction::create([
            'user_id' => $userB->id,
            'category_id' => $category->id,
            'title' => 'Gaji User B',
            'amount' => 1000000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($userA)->put("/transactions/{$transactionB->id}", [
            'title' => 'Hacked title',
            'type' => 'income',
            'category_id' => $category->id,
            'amount' => '500.000',
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('transactions', [
            'id' => $transactionB->id,
            'title' => 'Gaji User B',
        ]);
    }

    public function test_user_cannot_delete_another_users_transaction(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $category = Category::where('type', 'expense')->first();

        $transactionB = Transaction::create([
            'user_id' => $userB->id,
            'category_id' => $category->id,
            'title' => 'Beban User B',
            'amount' => 50000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($userA)->delete("/transactions/{$transactionB->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('transactions', [
            'id' => $transactionB->id,
        ]);
    }

    public function test_user_can_delete_their_own_transaction(): void
    {
        $user = User::factory()->create();
        $category = Category::where('type', 'expense')->first();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Beli Pulsa',
            'amount' => 50000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->delete("/transactions/{$transaction->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
