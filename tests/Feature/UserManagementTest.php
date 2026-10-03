<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('user-management.index'))->assertRedirect('/login');
        $this->get(route('user-management.create'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_user_list(): void
    {
        User::factory()->count(3)->create();

        $this->actingAs(User::factory()->create())
            ->get(route('user-management.index'))
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_create_page_loads(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('user-management.create'))
            ->assertOk();
    }

    public function test_it_creates_a_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('user-management.store'), [
                'name' => 'Managed User',
                'email' => 'managed@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('user-management.index'));

        $this->assertDatabaseHas('users', ['email' => 'managed@example.com']);
    }

    public function test_it_validates_user_creation(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('user-management.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
            ])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_edit_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('user-management.edit', $user))
            ->assertOk()
            ->assertViewHas('user');
    }

    public function test_it_updates_a_user_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs(User::factory()->create())
            ->put(route('user-management.update', $user), [
                'name' => 'New Name',
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('user-management.index'));

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertTrue(Hash::check('new-password-123', $user->password));
    }

    public function test_it_deletes_a_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('user-management.destroy', $user))
            ->assertRedirect(route('user-management.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}