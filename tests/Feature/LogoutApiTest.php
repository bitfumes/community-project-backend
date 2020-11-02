<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_be_authenticated()
    {
        $this->json('POST', '/api/logout')
            ->assertStatus(401);
    }

    public function test_user_can_logout()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->json('POST', '/api/logout')
            ->assertStatus(200);
    }
}
