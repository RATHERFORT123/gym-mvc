<?php

class Database
{
    private static $instance = null;
    private static $pdo = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$pdo === null) {

            // 1️⃣ Connect WITHOUT database
            $pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 2️⃣ Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);

            // 3️⃣ Connect WITH database
            self::$pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 4️⃣ Create tables
            self::createTables();

            // 5️⃣ Create admin
            self::createAdmin();
        }

        return self::$pdo;
    }

    private static function createTables()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            email VARCHAR(100) UNIQUE,
            password VARCHAR(255),
            role ENUM('admin','user','faculty') DEFAULT 'user',
            is_verified BOOLEAN default 0,
            is_active BOOLEAN default 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
            
        CREATE TABLE IF NOT EXISTS user_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            first_name VARCHAR(50),
            last_name VARCHAR(50),
            mobile_number VARCHAR(15),
            college_year ENUM('1', '2', '3', '4'),
            semester INT,
            branch VARCHAR(50),
            height_cm DECIMAL(5,2),
            weight_kg DECIMAL(5,2),
            fitness_goal VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS user_plans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            plan_name VARCHAR(100),
            workout_plan TEXT,
            diet_plan TEXT,
            assigned_by VARCHAR(50) DEFAULT 'Instructor',
            start_date DATE,
            end_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );
        ";
        self::$pdo->exec($sql);

        // Update: Add is_active column if not exists
        try {
            self::$pdo->exec("ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT 1 AFTER is_verified");
        } catch (Exception $e) {
            // Column likely exists
        }
    }

    private static function createAdmin()
    {
        $stmt = self::$pdo->prepare(
            "SELECT COUNT(*) FROM users WHERE role='admin'"
        );
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {

            $password = password_hash('admin123', PASSWORD_DEFAULT);

            $insert = self::$pdo->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, ?)"
            );

            $insert->execute([
                'Admin',
                'admin@gym.com',
                $password,
                'admin'
            ]);
        }
    }
}
