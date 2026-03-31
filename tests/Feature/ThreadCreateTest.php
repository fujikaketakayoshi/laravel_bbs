<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_create_thread(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('thread_store'), [
                'title' => 'テストタイトル',
                'body' => 'テスト本文',
            ]);

        $response->assertForbidden();
    }

    public function test_verified_user_can_create_thread(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('thread_store'), [
                'title' => 'テストタイトル',
                'body' => 'テスト本文',
            ]);

        $this->assertDatabaseHas('threads', [
            'title' => 'テストタイトル',
            'body' => 'テスト本文',
        ]);
    }

    public function test_guest_cannot_create_thread(): void
    {
        $response = $this->post(route('thread_store'), [
            'title' => 'テストタイトル',
            'body' => 'テスト本文',
        ]);

        $response->assertRedirect(route('login'));
    }
}
