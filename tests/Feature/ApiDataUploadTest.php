<?php

namespace Tests\Feature;

use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiDataUploadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1x1 transparent PNG.
     */
    private const PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    private function makeKey(string $name = 'Test API', bool $active = true): ApiKey
    {
        return ApiKey::create([
            'user_id' => User::factory()->create()->id,
            'api_key' => 'api_'.uniqid(),
            'name' => $name,
            'is_active' => $active,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'foto profil',
            'id_tabel' => '123',
            'tabel_name' => 'users',
            'apikey' => $this->makeKey()->api_key,
        ], $overrides);
    }

    public function test_it_uploads_a_base64_file_and_returns_public_url(): void
    {
        Storage::fake('public');

        $key = $this->makeKey();
        $response = $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'foto profil',
            'id_tabel' => '123',
            'tabel_name' => 'users',
            'apikey' => $key->api_key,
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'File uploaded successfully')
            ->assertJsonStructure(['message', 'data' => ['id', 'url', 'file_info' => ['original_name', 'stored_name', 'size', 'mime_type']]]);

        $this->assertDatabaseCount('api_data', 1);

        $record = ApiData::first();
        $this->assertSame('uploaded', $record->status);
        $this->assertSame('123', $record->id_tabel);
        $this->assertSame('users', $record->tabel_name);
        $this->assertSame($key->id, $record->api_id);

        // Folder structure: api_name/year/month
        $this->assertStringStartsWith('Test_API/'.date('Y/m').'/', $record->file_path);
        $this->assertStringEndsWith('.png', $record->file_path);
        Storage::disk('public')->assertExists($record->file_path);
    }

    public function test_it_accepts_a_data_uri_prefixed_base64_payload(): void
    {
        Storage::fake('public');

        $key = $this->makeKey('Photo Bucket');
        $response = $this->postJson('/api/data2nas', [
            'file' => 'data:image/png;base64,'.self::PNG_BASE64,
            'nama_sistem' => 'avatar',
            'apikey' => $key->api_key,
        ]);

        $response->assertCreated();

        $record = ApiData::first();
        $this->assertStringStartsWith('Photo_Bucket/'.date('Y/m').'/', $record->file_path);
        $this->assertStringEndsWith('.png', $record->file_path);
        Storage::disk('public')->assertExists($record->file_path);
    }

    public function test_it_creates_a_storage_directory_per_api_name_year_and_month(): void
    {
        Storage::fake('public');

        $key = $this->makeKey('My Cool API');
        $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'a file',
            'apikey' => $key->api_key,
        ])->assertCreated();

        $path = ApiData::first()->file_path;
        $this->assertStringStartsWith('My_Cool_API/'.date('Y/m').'/', $path);
    }

    public function test_it_allows_optional_id_tabel_and_tabel_name_to_be_null(): void
    {
        Storage::fake('public');

        $key = $this->makeKey();
        $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'no meta',
            'apikey' => $key->api_key,
        ])->assertCreated();

        $record = ApiData::first();
        $this->assertNull($record->id_tabel);
        $this->assertNull($record->tabel_name);
    }

    public function test_it_rejects_missing_required_fields(): void
    {
        $this->postJson('/api/data2nas', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file', 'nama_sistem', 'apikey']);
    }

    public function test_it_rejects_unknown_api_key(): void
    {
        $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'x',
            'apikey' => 'does-not-exist',
        ])->assertStatus(422);
    }

    public function test_it_rejects_inactive_api_key(): void
    {
        $key = $this->makeKey('Inactive', false);

        $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'x',
            'apikey' => $key->api_key,
        ])->assertStatus(401)
            ->assertJson(['error' => 'Invalid or inactive API key']);
    }

    public function test_it_rejects_invalid_base64_content(): void
    {
        $key = $this->makeKey();

        $this->postJson('/api/data2nas', [
            'file' => '!!!!not-base64!!!!',
            'nama_sistem' => 'x',
            'apikey' => $key->api_key,
        ])->assertStatus(400)
            ->assertJson(['error' => 'Invalid base64 format']);
    }

    public function test_it_uses_the_explicit_extension_when_provided(): void
    {
        Storage::fake('public');

        $key = $this->makeKey();
        $this->postJson('/api/data2nas', [
            'file' => self::PNG_BASE64,
            'nama_sistem' => 'forced',
            'apikey' => $key->api_key,
            'extension' => 'jpg',
        ])->assertCreated();

        $this->assertStringEndsWith('.jpg', ApiData::first()->file_path);
    }

    public function test_it_records_the_client_ip_address(): void
    {
        Storage::fake('public');

        $key = $this->makeKey();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->postJson('/api/data2nas', [
                'file' => self::PNG_BASE64,
                'nama_sistem' => 'ip test',
                'apikey' => $key->api_key,
            ])->assertCreated();

        $this->assertSame('203.0.113.10', ApiData::first()->ip_address);
    }
}