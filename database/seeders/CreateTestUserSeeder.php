<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateTestUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'reichelt@myparkplatz24.de',
            'role' => 'lawyer',
            'password' => Hash::make('test'),
            'language' => 'de',
            'confirmed' => true,
            'street' => '',
            'zip' => '',
            'city' => '',
            'country' => 'Deutschland',
            'companyName' => '',
            'emailReportUpdates' => 1,
            'emailAddressUpdates' => 1,
            'questionActive' => 0,
            'question' => '',
            'answer' => '',
        ]);
    }
}
