<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin', // Username
            'password' => bcrypt('admin'),
            'role' => 'admin', // Role
            'name' => 'rama', // Name
            'created_at' => Carbon::now(), // Timestamp for created_at
            'updated_at' => Carbon::now(), // Timestamp for updated_at
        ]);
    }
}
