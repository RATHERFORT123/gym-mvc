<?php

class Database
{
    private static $pdo = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$pdo === null) {

            // 1️⃣ Connect without DB
            $pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 2️⃣ Create DB
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);

            // 3️⃣ Connect with DB
            self::$pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 4️⃣ Tables + migrations
            self::createTables();

            // 5️⃣ Admin seed
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
            is_verified BOOLEAN DEFAULT 0,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS user_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            first_name VARCHAR(50),
            last_name VARCHAR(50),
            mobile_number VARCHAR(15),
            college_year ENUM('1','2','3','4'),
            semester INT,
            branch VARCHAR(50),

            department VARCHAR(100) NULL,
            position VARCHAR(100) NULL,
            subject_expert VARCHAR(150) NULL,
            employee_code VARCHAR(50) NULL,

            middle_name VARCHAR(50) NULL,
            gender ENUM('Male', 'Female', 'Other') NULL,
            birth_date DATE NULL,
            blood_group VARCHAR(10) NULL,
            bmi_index DECIMAL(5,2) NULL,
            waist_size DECIMAL(5,2) NULL,

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

        CREATE TABLE IF NOT EXISTS plans_master (
            id INT AUTO_INCREMENT PRIMARY KEY,
            plan_key VARCHAR(10) NOT NULL UNIQUE,
            name VARCHAR(100) NOT NULL,
            price_user DECIMAL(10,2) NOT NULL DEFAULT 0,
            price_faculty DECIMAL(10,2) NOT NULL DEFAULT 0,
            price_female DECIMAL(10,2) NOT NULL DEFAULT 0,
            duration_days INT NOT NULL DEFAULT 30,
            upi_id VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,

            user_id INT NOT NULL,
            plan_id INT NULL,
            amount DECIMAL(10,2) NOT NULL,

            payment_method ENUM('upi') DEFAULT 'upi',
            upi_id VARCHAR(100) NOT NULL,

            payer_upi VARCHAR(100) DEFAULT NULL,
            utr_number VARCHAR(50) DEFAULT NULL,

            status ENUM('pending','verified','rejected') DEFAULT 'pending',
            is_read TINYINT(1) DEFAULT 0,
            paid_at DATETIME DEFAULT NULL,
            verified_at DATETIME DEFAULT NULL,

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (plan_id) REFERENCES plans_master(id) ON DELETE SET NULL
        );

        CREATE TABLE IF NOT EXISTS user_subscriptions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            plan_id INT NULL,
            payment_id INT NOT NULL,

            start_date DATE NOT NULL,
            end_date DATE NOT NULL,

            status ENUM('active','expired','cancelled') DEFAULT 'active',

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (plan_id) REFERENCES plans_master(id) ON DELETE SET NULL,
            FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS attendance (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            role ENUM('user','faculty') NOT NULL,
            attendance_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_attendance (user_id, attendance_date),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) UNIQUE,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            status ENUM('active', 'inactive') DEFAULT 'inactive',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
        ";

        self::$pdo->exec($sql);

        // ==========================
        // 🔐 CONSTRAINTS & INDEXES
        // ==========================

        try {
            self::$pdo->exec("ALTER TABLE payments ADD UNIQUE KEY unique_utr (utr_number)");
        } catch (Exception $e) {}

        try {
            self::$pdo->exec("CREATE INDEX idx_payments_user ON payments(user_id)");
            self::$pdo->exec("CREATE INDEX idx_payments_status ON payments(status)");
        } catch (Exception $e) {}

        // ==========================
        // 🔄 MIGRATIONS (Profile Fields Update)
        // ==========================
        try {
            $cols = [
                "middle_name"  => "VARCHAR(50) NULL",
                "gender"       => "ENUM('Male', 'Female', 'Other') NULL",
                "birth_date"   => "DATE NULL",
                "blood_group"  => "VARCHAR(10) NULL",
                "bmi_index"    => "DECIMAL(5,2) NULL",
                "waist_size"   => "DECIMAL(5,2) NULL",
                "employee_code" => "VARCHAR(50) NULL"
            ];

            foreach ($cols as $col => $type) {
                try {
                    self::$pdo->exec("ALTER TABLE user_profiles ADD COLUMN $col $type");
                } catch (Exception $e) { /* Column might already exist */ }
            }

            // Remove old fields
            try {
                self::$pdo->exec("ALTER TABLE user_profiles DROP COLUMN qualification");
            } catch (Exception $e) {}
            try {
                self::$pdo->exec("ALTER TABLE user_profiles MODIFY COLUMN gender ENUM('Male', 'Female', 'Other') NULL");
            } catch (Exception $e) {}

            // Add duration_days to plans_master
            try {
                self::$pdo->exec("ALTER TABLE plans_master ADD COLUMN duration_days INT NOT NULL DEFAULT 30");
            } catch (Exception $e) { /* Column might already exist */ }

            // Add price_female to plans_master
            try {
                self::$pdo->exec("ALTER TABLE plans_master ADD COLUMN price_female DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER price_faculty");
            } catch (Exception $e) { /* Column might already exist */ }

            // Add is_read to payments
            try {
                self::$pdo->exec("ALTER TABLE payments ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER status");
            } catch (Exception $e) { /* Column might already exist */ }

        } catch (Exception $e) {}

        // ==========================
        // 🌱 SEED DATA
        // ==========================

        try {
            $stmt = self::$pdo->prepare(
                "INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)"
            );
            $stmt->execute(['global_upi', defined('UPI_ID') ? UPI_ID : 'your-upi@bank']);
        } catch (Exception $e) {}

        try {
            $defaults = [
                ['1m','1 Month',199,199,199],
                ['3m','3 Months',499,499,499],
                ['6m','6 Months',899,899,899],
            ];

            foreach ($defaults as $d) {
                $stmt = self::$pdo->prepare("SELECT COUNT(*) FROM plans_master WHERE plan_key=?");
                $stmt->execute([$d[0]]);

                if ($stmt->fetchColumn() == 0) {
                    $ins = self::$pdo->prepare(
                        "INSERT INTO plans_master (plan_key,name,price_user,price_faculty,price_female,upi_id)
                         VALUES (?,?,?,?,?,?)"
                    );
                    $ins->execute([$d[0],$d[1],$d[2],$d[3],$d[4],defined('UPI_ID') ? UPI_ID : null]);
                }
            }
        } catch (Exception $e) {}
    }

    private static function createAdmin()
    {
        $stmt = self::$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'");
        if ($stmt->fetchColumn() == 0) {

            $password = password_hash('admin123', PASSWORD_DEFAULT);

            $ins = self::$pdo->prepare(
                "INSERT INTO users (name,email,password,role)
                 VALUES (?,?,?,?)"
            );
            $ins->execute([
                'Admin',
                'admin@gym.com',
                $password,
                'admin'
            ]);
        }
    }
}
