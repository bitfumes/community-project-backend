<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\UserVerificationEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Notification;
use Tests\TestCase;

class EmailVerificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_their_email()
    {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        Sanctum::actingAs($user);

        $this->assertNull($user->email_verified_at);

        Notification::assertNothingSent();

        $notification = new UserVerificationEmail();

        $message = $notification->toMail($user);

        $this->assertNotNull($message->actionUrl);

        $this->json('POST', $message->actionUrl)
            ->assertStatus(200)
            ->assertJsonStructure([
                "message"
            ]);

        $this->assertNotNull($user->email_verified_at);
    }

    public function test_verification_email_can_be_resend()
    {
        Notification::fake();

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Notification::assertNothingSent();

        $this->json('POST', '/api/email/verification-notification')
            ->assertStatus(200)
            ->assertJsonStructure([
                "message"
            ]);

        Notification::assertSentTo(
            [$user], UserVerificationEmail::class
        );
    }
}
