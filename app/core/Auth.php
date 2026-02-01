<?php

class Auth
{
    /**
     * Check if user is logged in
     */
    public static function check()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }

    /**
     * Check if user has allowed role(s)
     * Example: Auth::role(['user','faculty'])
     */
    public static function role(array $roles)
    {
        self::check();

        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            http_response_code(403);
            die('Access denied');
        }
    }

    /**
     * Helper: is admin?
     */
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Helper: is faculty?
     */
    public static function isFaculty()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'faculty';
    }

    /**
     * Helper: is normal user?
     */
    public static function isUser()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
    }
}
