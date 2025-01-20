<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConvertPasswordsSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
            // Konvertiere das Passwort zu Bcrypt
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($user->password)
                ]);
        }
    }
}
