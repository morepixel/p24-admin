<?php

namespace App\Helpers;

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
        $sql = str_replace('bigint', 'integer', $sql);
        
        // Remove character set specifications
        $sql = preg_replace('/CHARACTER SET [a-zA-Z0-9_]+ COLLATE [a-zA-Z0-9_]+/i', '', $sql);
        
        // Remove backticks
        $sql = str_replace('`', '', $sql);
        
        // Remove any MySQL specific comments
        $sql = preg_replace('/^#.*$/m', '', $sql);
        
        // Convert MySQL specific functions
        $sql = str_replace('NOW()', "datetime('now')", $sql);
        
        // Add missing columns for Laravel
        $sql = str_replace(
            'CREATE TABLE users (',
            'DROP TABLE IF EXISTS users;
            CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            role VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL DEFAULT "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
            language VARCHAR(255) NOT NULL DEFAULT "de",
            emailCode VARCHAR(255) NULL,
            confirmed BOOLEAN DEFAULT 0,
            street VARCHAR(255) NOT NULL,
            zip VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            country VARCHAR(255) NOT NULL,
            companyName VARCHAR(255) NOT NULL,
            companyForm VARCHAR(255) NULL,
            companyOfficer VARCHAR(255) NULL,
            companyNumber VARCHAR(255) NULL,
            companyCourt VARCHAR(255) NULL,
            taxInfo BIGINT NULL,
            birthdate VARCHAR(255) NULL,
            lastActivity TIMESTAMP NULL,
            emailReportUpdates SMALLINT DEFAULT 1,
            emailAddressUpdates SMALLINT DEFAULT 1,
            phone VARCHAR(255) NULL,
            questionActive SMALLINT DEFAULT 0,
            question VARCHAR(2000) DEFAULT "",
            answer VARCHAR(2000) DEFAULT "",
            welcomeNsl TIMESTAMP NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            deleted_at TIMESTAMP NULL
            )',
            $sql
        );
        
        $sql = str_replace(
            'CREATE TABLE reports (',
            'DROP TABLE IF EXISTS reports;
            CREATE TABLE reports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            companyName VARCHAR(128) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            plateCode1 VARCHAR(255) NOT NULL,
            plateCode2 VARCHAR(255) NOT NULL,
            plateCode3 VARCHAR(255) NOT NULL,
            date TIMESTAMP NOT NULL,
            uploadStatus INTEGER NOT NULL,
            status INTEGER NOT NULL,
            sentStatus INTEGER NOT NULL,
            alreadyInSystem INTEGER DEFAULT 0,
            city VARCHAR(255) NOT NULL,
            zip VARCHAR(255) NOT NULL,
            street VARCHAR(255) NOT NULL,
            country VARCHAR(255) NOT NULL,
            lat DOUBLE NOT NULL,
            lng DOUBLE NOT NULL,
            "order" INTEGER DEFAULT 0,
            userId INTEGER NULL,
            addressId INTEGER DEFAULT 0,
            lawyerDetails VARCHAR(2000) NULL,
            halterDatum VARCHAR(128) NULL,
            halterName VARCHAR(128) NULL,
            zahlungsziel VARCHAR(128) NULL,
            kennnummer VARCHAR(128) NULL,
            halterPLZ VARCHAR(128) NULL,
            halterOrt VARCHAR(128) NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            deleted_at TIMESTAMP NULL
            )',
            $sql
        );
        
        // Convert INSERT statements
        $sql = preg_replace(
            '/INSERT INTO `?users`? \(.*?\) VALUES/',
            'INSERT INTO users (id, firstname, lastname, email, role, password, language, street, zip, city, country, companyName, created_at, updated_at) VALUES',
            $sql
        );
        
        $sql = preg_replace(
            '/INSERT INTO `?reports`? \(.*?\) VALUES/',
            'INSERT INTO reports (id, companyName, firstname, lastname, email, plateCode1, plateCode2, plateCode3, date, uploadStatus, status, sentStatus, city, zip, street, country, lat, lng, userId, addressId, created_at, updated_at) VALUES',
            $sql
        );
        
        return $sql;
    }
    
    public static function convertFile($inputFile)
    {
        $sql = file_get_contents($inputFile);
        return self::convertSqlToSqlite($sql);
    }
}
