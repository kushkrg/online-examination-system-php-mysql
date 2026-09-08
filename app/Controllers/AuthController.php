<?php
namespace App\Controllers;

use App\Models\User;
use App\Config\Database;

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($user['role'] === 'admin') {
                    header('Location: /admin/dashboard');
                } else {
                    header('Location: /student/dashboard');
                }
                exit;
            } else {
                $error = "Invalid login credentials";
                require_once __DIR__ . '/../Views/auth/login.php';
                return;
            }
        }
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $city = $_POST['city'] ?? '';
            
            if ($this->userModel->emailExists($email)) {
                $error = "Email is already registered.";
                require_once __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if ($this->userModel->register($name, $email, $password, 'student', $city)) {
                header('Location: /login?success=1');
                exit;
            } else {
                $error = "Something went wrong.";
            }
        }
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
