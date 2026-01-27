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
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        self::$pdo->exec($sql);
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
