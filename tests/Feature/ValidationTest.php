<?php

namespace Tests\Feature;

use App\Models\User;
use App\Http\Requests\ThreadRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_thread_validate()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post(route('thread_store'), [
            'title' => '件名です',
            'body' => '本文です',
    	]);
	    $response->assertSessionHasNoErrors();
    }
    
    public function test_thread_validate_title_null()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post(route('thread_store'), [
            'title' => null,
            'body' => '本文です',
    	]);
	    $response->assertSessionHasErrors(['title']);
    }
    
    public function test_thread_validate_body_null()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post(route('thread_store'), [
            'title' => 'タイトルです',
            'body' => null,
    	]);
	    $response->assertSessionHasErrors(['body']);
    }

    public function test_reply_validate()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post(route('reply_store'), [
            'body' => '本文です',
    	]);
	    $response->assertSessionHasNoErrors();
    }
    
    public function test_reply_validate_body_null()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->post(route('reply_store'), [
            'body' => null,
    	]);
	    $response->assertSessionHasErrors(['body']);
    }
}
