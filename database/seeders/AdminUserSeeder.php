<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'lawyer',
            'language' => 'de',
            'street' => 'Teststraße 1',
            'zip' => '12345',
            'city' => 'Berlin',
            'country' => 'Deutschland',
            'companyName' => 'Test GmbH',
            'confirmed' => true,
        ]);
    }
}
