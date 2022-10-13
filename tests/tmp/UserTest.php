<?php

namespace Tests\Feature;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\User;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    const TABLE_NAME = 'users';

    public function setUp(): void
    {
        parent::setUp();
        
        $this->createApplication();
    }
    
    public function  factory(): int
    {
        $count = random_int(min:1, max: 9);
        
        User::factory($count)->create();
        return $count;
    }
    
    
    public function test_get_all(): void
    {
        $count = $this->factory();
        
        $this->assertDatabaseCount(table: self::TABLE_NAME, count: $count);
        
        $result = User::all();
        
        $this->assertCount(expectedCount: $count, haystack: $result);
    }
    
    
    /**
     * A basic unit test example.
     *
     * @return void
     */
    
//     public function test_return_name_charactor_count()
//     {
//         //parent::setUp();
//         $user = User::factory()->create([
//             'name' => 'John',
//         ]);
//         
//         $result = $user->getNameCount();
//         
//         $this->assertEquals(4, $result);
//     }
}
