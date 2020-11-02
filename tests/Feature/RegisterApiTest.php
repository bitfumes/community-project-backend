<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_required_fields_for_register()
    {
        $this->json('POST', '/api/register')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function test_provided_email_is_unique_in_users()
    {
        User::factory()->create([
            'name' => 'john doe',
            'email' => 'john@demo.com',
            'password' => bcrypt('johndoe123')
        ]);

        $this->json('POST', '/api/register', [
            'email' => 'john@demo.com',
        ])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email has already been taken."],
                ]
            ]);
    }

    public function test_validation_for_password_cofirmation()
    {
        $this->json('POST', '/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
        ])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."],
                ]
            ]);

        $this->json('POST', '/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'passwrd',
        ])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."],
                ]
            ]);
    }

    public function test_user_can_register()
    {
        $this->json('POST', '/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                "message",
                "data",
                "access_token"
            ]);
    }
}
