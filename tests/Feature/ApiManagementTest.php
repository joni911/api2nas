<?php

namespace Tests\Feature;

use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('api-management.index'))->assertRedirect('/login');
        $this->get(route('api-management.create'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_list_api_keys(): void
    {
        $user = User::factory()->create();
        ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Listed Key',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('api-management.index'))
            ->assertOk()
            ->assertSee('Listed Key');
    }

    public function test_create_page_loads(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('api-management.create'))
            ->assertOk();
    }

    public function test_it_creates_an_api_key_owned_by_the_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('api-management.store'), ['name' => 'My Key'])
            ->assertRedirect(route('api-management.index'));

        $key = ApiKey::first();
        $this->assertNotNull($key);
        $this->assertSame('My Key', $key->name);
        $this->assertSame($user->id, $key->user_id);
        $this->assertStringStartsWith('api_', $key->api_key);
        $this->assertTrue($key->is_active);
    }

    public function test_store_validates_name(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('api-management.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_it_shows_own_api_key_with_uploaded_data(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Owned',
            'is_active' => true,
        ]);
        ApiData::create([
            'api_id' => $key->id,
            'nama_file' => 'file.png',
            'ip_address' => '127.0.0.1',
            'file_path' => 'Owned/2026/01/file.png',
            'url' => 'http://localhost/storage/file.png',
            'status' => 'uploaded',
        ]);

        $this->actingAs($user)
            ->get(route('api-management.show', $key))
            ->assertOk()
            ->assertSee('file.png');
    }

    public function test_it_cannot_show_another_users_api_key(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $owner->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Secret',
            'is_active' => true,
        ]);

        $this->actingAs($other)
            ->get(route('api-management.show', $key))
            ->assertNotFound();
    }

    public function test_it_updates_own_api_key(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Before',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->put(route('api-management.update', $key), [
                'name' => 'After',
                'is_active' => false,
            ])
            ->assertRedirect(route('api-management.index'));

        $key->refresh();
        $this->assertSame('After', $key->name);
        $this->assertFalse($key->is_active);
    }

    public function test_it_deletes_own_api_key(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Gone',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->delete(route('api-management.destroy', $key))
            ->assertRedirect(route('api-management.index'));

        $this->assertDatabaseMissing('api_keys', ['id' => $key->id]);
    }
}