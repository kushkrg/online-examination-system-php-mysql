<?php
namespace App\Helpers;

class AuthHelper {
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if ($_SESSION['user_role'] !== $role) {
            http_response_code(403);
            echo "403 Forbidden - You do not have permission to access this page.";
            exit;
        }
    }
}
