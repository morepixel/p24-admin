<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ImportUsersSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = '/Users/l2sr6t/Documents/projekte/p24/Entwicklung/LIVE Datenbank/2024/users_20241213_0927CET.sql';
        $sql = File::get($sqlFile);
        
        // Disable foreign key checks for MySQL
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Split SQL into separate statements and process each one
        $lines = explode("\n", $sql);
        $currentStatement = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }
            
            $currentStatement .= ' ' . $line;
            
            // If this line ends with a semicolon, we have a complete statement
            if (substr($line, -1) === ';') {
                // Only process INSERT statements
                if (stripos($currentStatement, 'INSERT INTO') !== false) {
                    try {
                        // Extract values from INSERT statement
                        if (preg_match('/VALUES\s*\((.*?)\)/i', $currentStatement, $matches)) {
                            $values = $matches[1];
                            // If this is a password field, hash it with bcrypt
                            if (stripos($currentStatement, 'password') !== false) {
                                $values = preg_replace_callback('/\'([^\']+)\'/', function($match) {
                                    return "'" . Hash::make($match[1]) . "'";
                                }, $values);
                                $currentStatement = preg_replace('/VALUES\s*\((.*?)\)/i', 'VALUES (' . $values . ')', $currentStatement);
                            }
                        }
                        DB::unprepared($currentStatement);
                    } catch (\Exception $e) {
                        // Log the error but continue with other statements
                        \Log::error("Error importing user: " . $e->getMessage());
                        \Log::error("Statement: " . $currentStatement);
                    }
                } else {
                    // Execute non-INSERT statements as is
                    try {
                        DB::unprepared($currentStatement);
                    } catch (\Exception $e) {
                        \Log::error("Error executing statement: " . $e->getMessage());
                    }
                }
                $currentStatement = '';
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
