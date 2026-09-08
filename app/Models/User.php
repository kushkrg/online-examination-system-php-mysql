<?php
namespace App\Models;

use PDO;

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($name, $email, $password, $role = 'student', $city = null) {
        $query = "INSERT INTO " . $this->table . " (name, email, password, role, city) VALUES (:name, :email, :password, :role, :city)";
        $stmt = $this->conn->prepare($query);

        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hashed);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':city', $city);

        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
    
    public function getAllStudents($search = '', $status = '') {
        $where = "WHERE role = 'student'";
        $params = [];
        if (!empty($search)) {
            $where .= " AND (name LIKE :search OR email LIKE :search OR city LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if ($status !== '') {
            $where .= " AND status = :status";
            $params['status'] = $status;
        }
        $query = "SELECT u.id, u.name, u.email, u.city, u.status, u.created_at,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id) as total_attempts,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'submitted') as completed_exams,
            (SELECT ROUND(AVG(ea.score),1) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'submitted') as avg_score
            FROM " . $this->table . " u $where ORDER BY u.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id) {
        $query = "SELECT u.id, u.name, u.email, u.city, u.status, u.created_at,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id) as total_attempts,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'submitted') as completed_exams,
            (SELECT ROUND(AVG(ea.score),1) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'submitted') as avg_score,
            (SELECT SUM(ea.tab_switches) FROM exam_attempts ea WHERE ea.user_id = u.id) as total_violations
            FROM " . $this->table . " u WHERE u.id = :id AND role = 'student' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentExamHistory($id) {
        $query = "SELECT e.title, ea.start_time, ea.end_time, ea.score, e.total_marks, e.passing_marks, ea.status, ea.tab_switches
            FROM exam_attempts ea
            JOIN exams e ON ea.exam_id = e.id
            WHERE ea.user_id = :id
            ORDER BY ea.start_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStudent($id, $data) {
        $fields = "name = :name, email = :email, city = :city, status = :status";
        $params = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'city' => $data['city'] ?? null,
            'status' => $data['status'],
        ];
        if (!empty($data['password'])) {
            $fields .= ", password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $query = "UPDATE " . $this->table . " SET $fields WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function toggleStatus($id) {
        $query = "UPDATE " . $this->table . " SET status = IF(status='active','inactive','active') WHERE id = :id AND role='student'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function deleteStudent($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND role = 'student'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getStudentStats() {
        $query = "SELECT 
            COUNT(*) as total,
            SUM(status = 'active') as active,
            SUM(status = 'inactive') as inactive,
            COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as new_this_week
            FROM " . $this->table . " WHERE role = 'student'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers($search = '', $role = '', $status = '') {
        $where = "WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $where .= " AND (u.name LIKE :search OR u.email LIKE :search OR u.city LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if (!empty($role)) {
            $where .= " AND u.role = :role";
            $params['role'] = $role;
        }
        if ($status !== '') {
            $where .= " AND u.status = :status";
            $params['status'] = $status;
        }
        $query = "SELECT u.id, u.name, u.email, u.city, u.role, u.status, u.created_at,
            CASE WHEN u.role = 'student' THEN
                (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'evaluated')
            ELSE NULL END as exams_taken,
            CASE WHEN u.role = 'student' THEN
                (SELECT ROUND(AVG(ea.score),1) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'evaluated')
            ELSE NULL END as avg_score
            FROM " . $this->table . " u $where ORDER BY u.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT u.id, u.name, u.email, u.city, u.role, u.status, u.created_at,
            CASE WHEN u.role = 'student' THEN (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'evaluated') ELSE NULL END as exams_taken,
            CASE WHEN u.role = 'student' THEN (SELECT ROUND(AVG(ea.score),1) FROM exam_attempts ea WHERE ea.user_id = u.id AND ea.status = 'evaluated') ELSE NULL END as avg_score,
            CASE WHEN u.role = 'student' THEN (SELECT SUM(ea.tab_switches) FROM exam_attempts ea WHERE ea.user_id = u.id) ELSE NULL END as total_violations
            FROM " . $this->table . " u WHERE u.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $query = "INSERT INTO " . $this->table . " (name, email, password, role, city, status) VALUES (:name, :email, :password, :role, :city, :status)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'     => $data['role'],
            'city'     => $data['city'] ?? null,
            'status'   => $data['status'] ?? 'active',
        ]);
    }

    public function updateUser($id, $data) {
        $fields = "name = :name, email = :email, city = :city, role = :role, status = :status";
        $params = [
            'id'     => $id,
            'name'   => $data['name'],
            'email'  => $data['email'],
            'city'   => $data['city'] ?? null,
            'role'   => $data['role'],
            'status' => $data['status'],
        ];
        if (!empty($data['password'])) {
            $fields .= ", password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET $fields WHERE id = :id");
        return $stmt->execute($params);
    }

    public function toggleUserStatus($id) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET status = IF(status='active','inactive','active') WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteUser($id) {
        // Prevent deleting the last admin
        $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE role = 'admin' AND status = 'active'");
        $checkStmt->execute();
        $adminCount = $checkStmt->fetchColumn();
        $userStmt = $this->conn->prepare("SELECT role FROM " . $this->table . " WHERE id = :id");
        $userStmt->execute(['id' => $id]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] === 'admin' && $adminCount <= 1) {
            return false; // Cannot delete last admin
        }
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAllUserStats() {
        $query = "SELECT
            COUNT(*) as total,
            SUM(role = 'admin') as admins,
            SUM(role = 'student') as students,
            SUM(role = 'examiner') as examiners,
            SUM(status = 'active') as active,
            SUM(status = 'inactive') as inactive,
            COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_this_month
            FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $params = ['email' => $email];
        if ($excludeId) {
            $query .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        $query .= " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }
}


