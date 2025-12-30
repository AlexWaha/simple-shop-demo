<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'dummy-admin',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'dummy-user',
            'email' => 'user@test.com',
            'is_admin' => false,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
    }
}
