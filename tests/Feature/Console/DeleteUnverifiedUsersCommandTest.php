<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUnverifiedUsersCommandTest extends TestCase
{
    use RefreshDatabase;

    public int $days = 30;

    public function test_unverified_users_older_than_thirty_days_gets_deleted()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::today()->subtract('days', $this->days + 1)
        ]);

        $this->assertNotNull($user->fresh());

        $this->artisan('delete:unverified-users', ['days' => $this->days]);

        $this->assertNull($user->fresh());
    }

    public function test_unverified_users_before_thirty_days_cannot_be_deleted()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::today()->subtract('days', $this->days)
        ]);

        $this->assertNotNull($user->fresh());

        $this->artisan('delete:unverified-users', ['days' => $this->days]);

        $this->assertNotNull($user->fresh());
    }

    public function test_verified_users_cannot_be_deleted()
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::today()->subtract('days', $this->days + 1)
        ]);

        $this->assertNotNull($user->fresh());

        $this->artisan('delete:unverified-users', ['days' => $this->days]);

        $this->assertNotNull($user->fresh());
    }
}
