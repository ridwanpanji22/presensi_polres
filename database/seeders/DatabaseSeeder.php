<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Admin',
            'position' => 'Admin',
            'rank' => 'Admin',
            'nrp' => '1234',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'User',
            'position' => 'User',
            'rank' => 'User',
            'nrp' => '4321',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);
    }
}
