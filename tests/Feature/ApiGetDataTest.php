<?php

namespace Tests\Feature;

use App\Models\ApiData;
use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiGetDataTest extends TestCase
{
    use RefreshDatabase;

    private function makeRecord(): ApiData
    {
        $key = ApiKey::create([
            'user_id' => User::factory()->create()->id,
            'api_key' => 'api_'.uniqid(),
            'name' => 'Test API',
            'is_active' => true,
        ]);

        return ApiData::create([
            'api_id' => $key->id,
            'nama_file' => 'stored.png',
            'ip_address' => '127.0.0.1',
            'file_path' => 'Test_API/2026/01/stored.png',
            'url' => 'http://localhost/storage/Test_API/2026/01/stored.png',
            'status' => 'uploaded',
            'id_tabel' => '10',
            'tabel_name' => 'posts',
        ]);
    }

    public function test_it_returns_file_metadata_by_id(): void
    {
        $record = $this->makeRecord();

        $this->getJson('/api/data2nas/'.$record->id)
            ->assertOk()
            ->assertJson([
                'id' => $record->id,
                'url' => $record->url,
                'nama_file' => 'stored.png',
                'file_path' => 'Test_API/2026/01/stored.png',
                'id_tabel' => '10',
                'tabel_name' => 'posts',
            ])
            ->assertJsonStructure(['id', 'url', 'nama_file', 'file_path', 'id_tabel', 'tabel_name', 'created_at']);
    }

    public function test_it_returns_404_for_unknown_id(): void
    {
        $this->getJson('/api/data2nas/999999')->assertNotFound();
    }
}