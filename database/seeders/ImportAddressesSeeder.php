<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportAddressesSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = '/Users/l2sr6t/Documents/projekte/p24/Entwicklung/LIVE Datenbank/2024/addresses_20241213_0926CET.sql';
        $sql = File::get($sqlFile);
        
        // Split SQL into separate statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    DB::unprepared($statement);
                } catch (\Exception $e) {
                    // Log the error but continue with other statements
                    \Log::error("Error importing address: " . $e->getMessage());
                    \Log::error("Statement: " . $statement);
                }
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
