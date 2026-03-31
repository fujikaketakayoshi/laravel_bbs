<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Thread;
use App\Models\Reply;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_thread(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $thread = Thread::factory()->create(['delete_flag' => 0]);

        $response = $this->actingAs($admin)->delete(route('admin.thread_delete', $thread->id));

        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('threads', [
            'id' => $thread->id,
            'delete_flag' => 1,
        ]);
    }

    public function test_non_admin_cannot_delete_thread(): void
    {
        $user = User::factory()->create(['role' => 0]);
        $thread = Thread::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.thread_delete', $thread->id));

        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_thread(): void
    {
        $thread = Thread::factory()->create();

        $response = $this->delete(route('admin.thread_delete', $thread->id));

        $response->assertRedirect(route('login'));
    }


    public function test_admin_can_delete_reply(): void
    {
        $admin = User::factory()->create(['role' => 1]);
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create([
            'thread_id' => $thread->id,
            'delete_flag' => 0,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.reply_delete', $reply->id));

        $response->assertRedirect(route('thread', $reply->thread_id));

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'delete_flag' => 1,
        ]);
    }

    public function test_non_admin_cannot_delete_reply(): void
    {
        $user = User::factory()->create(['role' => 0]);
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create([
            'thread_id' => $thread->id,
            'delete_flag' => 0,
        ]);

        $response = $this->actingAs($user)->delete(route('admin.reply_delete', $reply->id));

        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_reply(): void
    {
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create([
            'thread_id' => $thread->id,
            'delete_flag' => 0,
        ]);

        $response = $this->delete(route('admin.reply_delete', $reply->id));

        $response->assertRedirect(route('login'));
    }
}