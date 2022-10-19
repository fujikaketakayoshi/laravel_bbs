<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Thread;
use App\Models\Reply;
//use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_admin_thread()
    {
        $user = User::factory()->create(['role' => 1]);
        $response = $this->actingAs($user)->get('/');

        $response->assertSee('<button type="submit" class="btn btn-danger">削除</button>', false);
    }
    
    public function test_show_admin_reply()
    {
        $user = User::factory()->create(['role' => 1]);
        $response = $this->actingAs($user)->get('/thread/1');

        $response->assertSee('<button type="submit" class="btn btn-danger">削除</button>', false);
    }
    
    public function test_delete_thread()
    {
        $user = User::factory()->create(['role' => 1]);
        $thread = Thread::factory()->create();
        //dd($thread);
        
        $data =[
            'id' => $thread->id,
        ];
        
        $this->assertDatabaseHas('threads', $data);

        $response = $this->actingAs($user)->delete(route('admin.thread_delete', $data));
        $response->assertRedirect('/admin/index');
        
        $delete_thread = Thread::find($thread->id);
        $this->assertEquals($delete_thread->delete_flag, 1);
    }

    public function test_delete_reply()
    {
        $user = User::factory()->create(['role' => 1]);
        $thread = Thread::factory()->create();
        $reply = Reply::factory()->create(['thread_id' => $thread->id]);
        //dd($thread);
        $data =[
            'id' => $reply->id,
        ];
        
        $this->assertDatabaseHas('replies', $data);

        $response = $this->actingAs($user)->delete(route('admin.reply_delete', $data));
        $response->assertRedirect('/thread/' . $reply->thread_id);
        
        $delete_reply = Reply::find($reply->id);
        $this->assertEquals($delete_reply->delete_flag, 1);
    }



}
