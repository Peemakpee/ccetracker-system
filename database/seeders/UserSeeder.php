<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([

            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('user1234'),
            'email_verified_at' => now(),
            'is_admin' => true

        ]);
    }
}
