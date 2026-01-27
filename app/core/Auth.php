<?php

class Auth
{
    public static function check()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }
    }

    public static function role($roles = [])
    {
        self::check();

        if (!in_array($_SESSION['role'], $roles)) {
            die("❌ Access Denied");
        }
    }
}
