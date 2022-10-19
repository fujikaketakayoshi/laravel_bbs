<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowViewTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_home()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    
    public function test_show_thread_1()
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

        $response = $this->get('/thread/1');
        $response->assertStatus(200);
    }


    public function test_show_thread_0()
    {
        $response = $this->get('/thread/0');
        $response->assertStatus(404);
    }
    
}
