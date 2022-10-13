<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestUserAccessTest extends TestCase
{
    public function test_can_make_thread()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post('/thread_store', [
            'title' => 'testing',
            'body' => 'this is body',
        ]);
        
        $response->assertRedirect('/');
    }
    
    public function test_can_make_reply()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post('/reply_store', [
            'body' => 'this is reply',
            'thread_id' => 1,
        ]);
        
        $response->assertRedirect('/thread/1');
    }

    public function test_guest_access_thread()
    {
        $response = $this->get('/thread/1');
        
        $response->assertStatus(200);
    }

    
    public function test_guest_can_not_make_thread()
    {
        $response = $this->post('/thread_store', [
            'title' => 'testing',
            'body' => 'this is body',
        ]);
        
         $response->assertRedirect('/login');
    }
    
    public function test_guest_can_not_make_reply()
    {
        $response = $this->post('/reply_store', [
            'body' => 'this is guest reply',
            'thread_id' => 1,
        ]);
        
         $response->assertRedirect('/login');
    }
    
}
