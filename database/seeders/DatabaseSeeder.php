<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users\User;
use App\Models\Users\UserWiFi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::updateOrCreate([
            'email' => 'admin@test.com'
        ], [
            'name' => 'user Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        UserWiFi::updateOrCreate([
            'status' => 'active',
            'user_id' => $admin->id
        ]);
    }
}
