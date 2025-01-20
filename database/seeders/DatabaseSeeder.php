<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First create roles
        $this->call(RoleSeeder::class);

        // Create test user
        $this->call(CreateTestUserSeeder::class);

        // Import users first as they are referenced by other tables
        $this->call(ImportUsersSeeder::class);

        // Import addresses which might reference users
        $this->call(ImportAddressesSeeder::class);

        // Import reports last as they might reference both users and addresses
        $this->call(ProcessReportsSeeder::class);
    }
}
