<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin William',
            'email' => 'william.correa.dev@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'address_line' => 'Rua do Admin, 123',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zipcode' => '01000-000',
            'country' => 'Brasil',
        ]);

        User::factory()->create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
    }
}
