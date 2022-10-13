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
    public function test_thread_validate(bool $expect):void
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $request  = new ThreadRequest();
        $rules    = $request->rules();
        $dataList = [
            'title' => '件名です',
            'body' => '本文です',
        ];

        $validator = Validator::make($dataList, $rules);
        $result    = $validator->passes();

        $this->assertEquals($expect, $result);
    }
    
    public function dataprovider(): array
    {
        return [
            'expect'   => ['title', '件名です', true],
            'required' => ['title', null, false],
            'required' => ['title', '', false],
        ];
    }
}
