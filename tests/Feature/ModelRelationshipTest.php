<?php

namespace Tests\Feature;

use App\Http\Middleware\ApiKeyMiddleware;
use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_api_key_belongs_to_a_user_and_has_many_data(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Key',
            'is_active' => true,
        ]);
        $data = ApiData::create([
            'api_id' => $key->id,
            'nama_file' => 'file.png',
            'ip_address' => '127.0.0.1',
            'file_path' => 'Key/2026/01/file.png',
            'url' => 'http://localhost/storage/file.png',
            'status' => 'uploaded',
        ]);

        $this->assertTrue($key->user->is($user));
        $this->assertTrue($key->apiData->contains($data));
    }

    public function test_an_api_data_record_belongs_to_an_api_key(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::create([
            'user_id' => $user->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Key',
            'is_active' => true,
        ]);
        $data = ApiData::create([
            'api_id' => $key->id,
            'nama_file' => 'file.png',
            'ip_address' => '127.0.0.1',
            'file_path' => 'Key/2026/01/file.png',
            'url' => 'http://localhost/storage/file.png',
            'status' => 'uploaded',
        ]);

        $this->assertTrue($data->apiKey->is($key));
    }

    public function test_api_key_casts_is_active_to_boolean(): void
    {
        $key = ApiKey::create([
            'user_id' => User::factory()->create()->id,
            'api_key' => 'api_cast',
            'name' => 'Key',
            'is_active' => 1,
        ]);

        $this->assertIsBool($key->fresh()->is_active);
        $this->assertTrue($key->fresh()->is_active);
    }

    public function test_api_data_casts_meta_fields_to_string(): void
    {
        $key = ApiKey::create([
            'user_id' => User::factory()->create()->id,
            'api_key' => 'api_meta',
            'name' => 'Key',
            'is_active' => true,
        ]);
        $data = ApiData::create([
            'api_id' => $key->id,
            'nama_file' => 'file.png',
            'ip_address' => '127.0.0.1',
            'file_path' => 'Key/2026/01/file.png',
            'url' => 'http://localhost/storage/file.png',
            'status' => 'uploaded',
            'id_tabel' => 42,
            'tabel_name' => 'users',
        ]);

        $this->assertSame('42', $data->fresh()->id_tabel);
        $this->assertSame('users', $data->fresh()->tabel_name);
    }

    public function test_api_key_middleware_rejects_missing_key(): void
    {
        $middleware = new ApiKeyMiddleware;

        $response = $middleware->handle(Request::create('/x'), fn () => new Response('ok'));

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame('{"error":"API key is required"}', $response->getContent());
    }

    public function test_api_key_middleware_rejects_invalid_key(): void
    {
        $middleware = new ApiKeyMiddleware;

        $response = $middleware->handle(
            Request::create('/x', 'GET', ['apikey' => 'nope']),
            fn () => new Response('ok')
        );

        $this->assertSame(401, $response->getStatusCode());
    }

    public function test_api_key_middleware_allows_valid_key(): void
    {
        $key = ApiKey::create([
            'user_id' => User::factory()->create()->id,
            'api_key' => 'api_valid',
            'name' => 'Key',
            'is_active' => true,
        ]);
        $middleware = new ApiKeyMiddleware;

        $response = $middleware->handle(
            Request::create('/x', 'GET', ['apikey' => $key->api_key]),
            fn ($request) => new Response('ok '.$request->input('authenticated_api_key')->id)
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('ok '.$key->id, $response->getContent());
    }
}