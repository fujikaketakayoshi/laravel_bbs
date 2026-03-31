<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WithdrawTest extends TestCase
{
    use RefreshDatabase;
    public function test_authenticated_user_can_withdraw(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null, // 未認証でもOK
        ]);

        $response = $this->actingAs($user)
            ->post(route('withdrawal_done'));

        $response->assertRedirect(route('index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_verified_user_can_withdraw(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(), // 認証済み
        ]);

        $response = $this->actingAs($user)
            ->post(route('withdrawal_done'));

        $response->assertRedirect(route('index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_guest_cannot_withdraw(): void
    {
        $response = $this->post(route('withdrawal_done'));

        $response->assertRedirect(route('login'));
    }
}
