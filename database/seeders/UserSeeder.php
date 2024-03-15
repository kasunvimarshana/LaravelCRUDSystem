<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory()->create([
            'name' => 'Owner',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::factory()->create([
            'name' => 'Manager',
            'username' => 'manager',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        User::factory()->create([
            'name' => 'Cashier',
            'username' => 'cashier',
            'password' => Hash::make('password'),
            'role' => 'cashier',
        ]);

        User::factory(10)->create();
    }
}
