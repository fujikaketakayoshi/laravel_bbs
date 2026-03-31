<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Thread;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplyCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_create_reply(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $thread = Thread::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reply_store'), [
                'thread_id' => $thread->id,
                'body' => '返信テスト',
            ]);

        $response->assertForbidden();
    }

    public function test_verified_user_can_create_reply(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $thread = Thread::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reply_store'), [
                'thread_id' => $thread->id,
                'body' => '返信テスト',
            ]);

        $this->assertDatabaseHas('replies', [
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'body' => '返信テスト',
        ]);
    }

    public function test_guest_cannot_create_reply(): void
    {
        $thread = Thread::factory()->create();

        $response = $this->post(route('reply_store'), [
            'thread_id' => $thread->id,
            'body' => '返信テスト',
        ]);

        $response->assertRedirect(route('login'));
    }
}
