<?php
class SessionHelper {
    public static function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }
    public static function get(string $key) {
        return $_SESSION[$key] ?? null;
    }
    public static function has(string $key): bool {
        return isset($_SESSION[$key]);
    }
    public static function destroy(): void {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}
