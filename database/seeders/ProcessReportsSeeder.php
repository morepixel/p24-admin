<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProcessReportsSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = '/Users/l2sr6t/Documents/projekte/p24/Entwicklung/LIVE Datenbank/2024/reports_20241213_0927CET.sql';
        
        if (!File::exists($sqlFile)) {
            echo "SQL file not found: $sqlFile\n";
            return;
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $handle = fopen($sqlFile, 'r');
        $count = 0;
        $currentStatement = '';

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }

            $currentStatement .= ' ' . $line;

            // If this line ends with a semicolon, we have a complete statement
            if (substr($line, -1) === ';') {
                // Only process INSERT statements for reports table
                if (stripos($currentStatement, 'INSERT INTO `reports`') !== false) {
                    try {
                        // Replace timestamp column names
                        $currentStatement = str_replace('createdAt', 'created_at', $currentStatement);
                        $currentStatement = str_replace('updatedAt', 'updated_at', $currentStatement);
                        $currentStatement = str_replace('deletedAt', 'deleted_at', $currentStatement);
                        
                        DB::unprepared($currentStatement);
                        $count++;
                    } catch (\Exception $e) {
                        echo "Error importing report: " . $e->getMessage() . "\n";
                    }
                }
                $currentStatement = '';
            }
        }

        fclose($handle);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Import completed. Total reports imported: $count\n";
    }
}
