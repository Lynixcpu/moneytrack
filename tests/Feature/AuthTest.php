<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
    }

    public function test_user_can_view_register_page(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
    }

    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@student.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@student.ac.id',
            'name' => 'Budi Santoso',
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'testuser@student.ac.id',
            'password' => Hash::make('secret12345'),
        ]);

        $response = $this->post('/login', [
            'email' => 'testuser@student.ac.id',
            'password' => 'secret12345',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'wrongpass@student.ac.id',
            'password' => Hash::make('secret12345'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
