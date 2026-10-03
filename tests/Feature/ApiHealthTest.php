<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiHealthTest extends TestCase
{
    public function test_api_health_endpoint_returns_healthy_status(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertOk()
            ->assertJson([
                'status' => 'healthy',
                'version' => '1.0.0',
            ])
            ->assertJsonStructure(['status', 'timestamp', 'version']);
    }

    public function test_web_health_endpoint_returns_healthy_status(): void
    {
        $this->getJson('/health')
            ->assertOk()
            ->assertJson(['status' => 'healthy']);
    }
}