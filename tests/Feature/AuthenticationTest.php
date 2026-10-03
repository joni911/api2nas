<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_registration_routes_are_disabled(): void
    {
        $this->assertFalse(Route::has('register'));

        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertNotFound();

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_a_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('secret-password'),
        ]);

        $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_home_requires_authentication(): void
    {
        $this->get('/home')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_home(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/home')
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_a_user_can_logout(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_admin_user_seeder_creates_the_default_admin(): void
    {
        $this->seed(AdminUserSeeder::class);

        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');

        $admin = User::where('email', $email)->first();
        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check($password, $admin->password));

        $this->post('/login', ['email' => $email, 'password' => $password]);
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_user_seeder_is_idempotent(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseCount('users', 1);
    }
}