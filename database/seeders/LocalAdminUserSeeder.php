<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LocalAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'fujikake.takayoshi@gmail.com'],
            [
                'name' => '藤掛貴由',
                'password' => Hash::make('password'),
                'role' => 1,
                'email_verified_at' => now(),
            ]
        );
    }
}
