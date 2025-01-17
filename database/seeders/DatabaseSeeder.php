<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ImportReportsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'language' => 'de',
            'street' => 'Test Street 1',
            'zip' => '12345',
            'city' => 'Test City',
            'country' => 'Deutschland',
            'companyName' => 'Test Company',
        ]);

        $this->call([
            RoleSeeder::class,
            ImportReportsSeeder::class,
        ]);
    }
}
