<?php
class AuthHelper {
    public static function isLoggedIn(): bool {
        return SessionHelper::has('user_id');
    }
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
        // Block back/forward cache to protected pages
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
    public static function requireRole(string $role): void {
        self::requireLogin();
        if (SessionHelper::get('role') !== $role) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit();
        }
    }
}