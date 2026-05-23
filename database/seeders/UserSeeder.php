<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a fixed admin user for easy login
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

         User::factory()->create([
            'name' => 'Mahmoud Hamdy',
            'email' => 'mahmoudhamdyae@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('Ishidauryu2&'),
        ]);

        User::factory(49)->create();
    }
}
