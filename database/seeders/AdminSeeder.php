<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
User::create([
    'name' => 'Administrator',
    'email' => 'admin@example.com',
    'password' => Hash::make('11223344'), // <--- HARUS ADA Hash::make()
    'role' => 'admin',
]);
    }
}
