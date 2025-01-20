<?php

namespace Database\Seeders;

class SqliteConverter
{
    public static function convertSqlToSqlite($sql)
    {
        // Remove MySQL specific syntax
        $sql = preg_replace('/ENGINE=InnoDB.*?;/i', ';', $sql);
        $sql = preg_replace('/AUTO_INCREMENT/i', 'AUTOINCREMENT', $sql);
        $sql = preg_replace('/UNSIGNED/i', '', $sql);
        
        // Convert data types
        $sql = str_replace('datetime', 'timestamp', $sql);
        $sql = str_replace('longtext', 'text', $sql);
        
        // Remove backticks
        $sql = str_replace('`', '', $sql);
        
        // Remove any MySQL specific comments
        $sql = preg_replace('/^#.*$/m', '', $sql);
        
        return $sql;
    }
    
    public static function convertFile($inputFile)
    {
        $sql = file_get_contents($inputFile);
        return self::convertSqlToSqlite($sql);
    }
}
