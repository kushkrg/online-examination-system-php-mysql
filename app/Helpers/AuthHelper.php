<?php
namespace App\Helpers;

class AuthHelper {
    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Allow only the given role, or any role in the given array.
     * e.g. requireRole('admin') or requireRole(['admin', 'examiner'])
     */
    public static function requireRole($roles) {
        self::requireLogin();
        $roles = (array) $roles;
        if (!in_array($_SESSION['user_role'], $roles, true)) {
            http_response_code(403);
            echo "403 Forbidden - You do not have permission to access this page.";
            exit;
        }
    }

    public static function isAdmin() {
        return ($_SESSION['user_role'] ?? null) === 'admin';
    }

    /** Where each role lands after logging in. */
    public static function homeFor($role) {
        return in_array($role, ['admin', 'examiner'], true) ? '/admin/dashboard' : '/student/dashboard';
    }
}
