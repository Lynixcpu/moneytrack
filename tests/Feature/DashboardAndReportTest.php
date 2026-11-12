<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardAndReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'CategorySeeder']);
    }

    public function test_dashboard_calculates_balance_accurately(): void
    {
        $user = User::factory()->create();
        $incCat = Category::where('type', 'income')->first();
        $expCat = Category::where('type', 'expense')->first();

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incCat->id,
            'title' => 'Pemasukan A',
            'amount' => 500000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expCat->id,
            'title' => 'Pengeluaran B',
            'amount' => 200000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        // Expected balance: 500.000 - 200.000 = 300.000
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('300.000');
        $response->assertSee('500.000');
        $response->assertSee('200.000');
    }

    public function test_history_page_filters_by_type(): void
    {
        $user = User::factory()->create();
        $incCat = Category::where('type', 'income')->first();
        $expCat = Category::where('type', 'expense')->first();

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incCat->id,
            'title' => 'Uang Saku Spesifik',
            'amount' => 450000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expCat->id,
            'title' => 'Bayar Kost Spesifik',
            'amount' => 700000,
            'transaction_date' => now()->format('Y-m-d'),
        ]);

        // Filter income only
        $response = $this->actingAs($user)->get('/history?type=income');
        $response->assertStatus(200);
        $response->assertSee('Uang Saku Spesifik');
        $response->assertDontSee('Bayar Kost Spesifik');
    }

    public function test_report_page_loads_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports?period=this_month');
        $response->assertStatus(200);
        $response->assertSee('Laporan Finansial');
        $response->assertSee('Saldo Bersih');
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        // Update profile
        $resProfile = $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru Mahasiswa',
            'email' => 'namabaru@student.ac.id',
        ]);

        $resProfile->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru Mahasiswa',
            'email' => 'namabaru@student.ac.id',
        ]);

        // Update password
        $resPass = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newsecurepass123',
            'password_confirmation' => 'newsecurepass123',
        ]);

        $resPass->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue(Hash::check('newsecurepass123', $user->password));
    }
}
