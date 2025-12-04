<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create test user
        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Attach some achievements to test user for demonstration
        $achievements = Achievement::limit(5)->get();
        foreach ($achievements as $achievement) {
            $testUser->achievements()->attach($achievement->id, [
                'unlocked_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
