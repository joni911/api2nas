<?php

namespace Tests\Feature;

use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataApiManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeData(User $owner, string $name = 'file.png'): ApiData
    {
        $key = ApiKey::create([
            'user_id' => $owner->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Bucket',
            'is_active' => true,
        ]);

        return ApiData::create([
            'api_id' => $key->id,
            'nama_file' => $name,
            'ip_address' => '127.0.0.1',
            'file_path' => 'Bucket/2026/01/'.$name,
            'url' => 'http://localhost/storage/Bucket/2026/01/'.$name,
            'status' => 'uploaded',
        ]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('data-api.index'))->assertRedirect('/login');
    }

    public function test_index_only_lists_own_uploaded_data(): void
    {
        $me = User::factory()->create();
        $someoneElse = User::factory()->create();

        $this->makeData($me, 'mine.png');
        $this->makeData($someoneElse, 'theirs.png');

        $this->actingAs($me)
            ->get(route('data-api.index'))
            ->assertOk()
            ->assertSee('mine.png')
            ->assertDontSee('theirs.png');
    }

    public function test_show_displays_own_data(): void
    {
        $me = User::factory()->create();
        $data = $this->makeData($me, 'visible.png');

        $this->actingAs($me)
            ->get(route('data-api.show', $data))
            ->assertOk()
            ->assertSee('visible.png');
    }

    public function test_show_returns_404_for_another_users_data(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $data = $this->makeData($other);

        $this->actingAs($me)
            ->get(route('data-api.show', $data))
            ->assertNotFound();
    }

    public function test_destroy_deletes_record_and_file(): void
    {
        Storage::fake('public');
        $me = User::factory()->create();
        $data = $this->makeData($me, 'remove-me.png');
        Storage::disk('public')->put($data->file_path, 'content');

        $this->actingAs($me)
            ->delete(route('data-api.destroy', $data))
            ->assertRedirect(route('data-api.index'));

        $this->assertDatabaseMissing('api_data', ['id' => $data->id]);
        Storage::disk('public')->assertMissing($data->file_path);
    }

    public function test_destroy_cannot_remove_another_users_data(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        $data = $this->makeData($other);

        $this->actingAs($me)
            ->delete(route('data-api.destroy', $data))
            ->assertNotFound();

        $this->assertDatabaseHas('api_data', ['id' => $data->id]);
    }
}