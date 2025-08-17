<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'user Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => hash('sha256', '12345678'),
            'status' => 'active'
        ]);
    }
}
