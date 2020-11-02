<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_required_fields_for_login()
    {
        $this->json('POST', '/api/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function test_invalid_credentials_for_login()
    {
        $this->json('POST', '/api/login', [
            'email' => $this->faker->safeEmail,
            'password' => '12345678'
        ])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The provided credentials are incorrect."],
                ]
            ]);
    }

    public function test_user_can_login()
    {
        User::factory()->create([
            'name' => 'john doe',
            'email' => 'john@demo.com',
            'password' => bcrypt('12345678')
        ]);

        $this->json('POST', '/api/login', [
            'email' => 'john@demo.com',
            'password' => '12345678'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data",
                "access_token"
            ]);
    }
}
