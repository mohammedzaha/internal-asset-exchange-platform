<?php
class SecurityHelper {
    public static function sanitize($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
