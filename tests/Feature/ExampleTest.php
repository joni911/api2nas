<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * The landing page embeds JSON-LD structured data. Blade's "@context"
     * directive must not be triggered by the JSON-LD "@context" key.
     */
    public function test_landing_page_renders_json_ld_context(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('"@context": "https://schema.org"', false)
            ->assertSee('"@type": "WebApplication"', false);
    }
}