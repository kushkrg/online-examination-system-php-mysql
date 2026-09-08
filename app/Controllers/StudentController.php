<?php
namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Exam;
use App\Config\Database;

class StudentController {
    private $db;
    private $examModel;

    public function __construct() {
        AuthHelper::requireRole('student');
        $database = new Database();
        $this->db = $database->connect();
        $this->examModel = new Exam($this->db);
    }

    public function dashboard() {
        // Get all published exams
        $query = "SELECT * FROM exams WHERE status = 'published' ORDER BY start_time DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $exams = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $title = 'Student Dashboard';
        ob_start();
        require_once __DIR__ . '/../Views/student/dashboard.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/student.php';
    }

    public function instructions() {
        $exam_id = $_GET['exam_id'] ?? null;
        if (!$exam_id) {
            header('Location: /student/dashboard');
            exit;
        }

        $query = "SELECT * FROM exams WHERE id = :id AND status = 'published'";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $exam_id]);
        $exam = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$exam) {
            header('Location: /student/dashboard');
            exit;
        }

        require_once __DIR__ . '/../Views/student/exam_instructions.php';
    }

    public function startExam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exam_id = $_POST['exam_id'];
            $city = $_POST['city'];
            
            $attemptModel = new \App\Models\Attempt($this->db);
            $attempt_id = $attemptModel->startAttempt($_SESSION['user_id'], $exam_id);
            
            $_SESSION['active_attempt_id'] = $attempt_id;
            
            header("Location: /student/exam/active?id=" . $exam_id);
            exit;
        }
    }

    public function activeExam() {
        $exam_id = $_GET['id'] ?? null;
        $attempt_id = $_SESSION['active_attempt_id'] ?? null;
        
        if (!$exam_id || !$attempt_id) {
            header('Location: /student/dashboard');
            exit;
        }

        $examQuery = $this->db->prepare("SELECT * FROM exams WHERE id = :id");
        $examQuery->execute(['id' => $exam_id]);
        $exam = $examQuery->fetch(\PDO::FETCH_ASSOC);

        $attemptModel = new \App\Models\Attempt($this->db);
        $attempt = $attemptModel->getAttempt($attempt_id);
        
        $start_time = strtotime($attempt['start_time']);
        $duration_seconds = $exam['duration_minutes'] * 60;
        $elapsed = time() - $start_time;
        $remaining_seconds = max(0, $duration_seconds - $elapsed);
        
        if ($remaining_seconds <= 0 || $attempt['status'] === 'completed') {
            header("Location: /student/exam/submit?attempt_id=" . $attempt_id);
            exit;
        }

        $questionModel = new \App\Models\Question($this->db);
        $questions = $questionModel->getByExamId($exam_id);
        
        $savedAnswers = $attemptModel->getSavedAnswers($attempt_id);

        require_once __DIR__ . '/../Views/student/exam_active.php';
    }

    public function saveAnswer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $attempt_id = $_SESSION['active_attempt_id'] ?? null;
            
            if ($attempt_id && isset($data['question_id']) && isset($data['option_id'])) {
                $attemptModel = new \App\Models\Attempt($this->db);
                $attemptModel->saveAnswer($attempt_id, $data['question_id'], $data['option_id']);
                echo json_encode(['status' => 'success']);
                exit;
            }
        }
        http_response_code(400);
        echo json_encode(['status' => 'error']);
    }

    public function submitExam() {
        $attempt_id = $_GET['attempt_id'] ?? $_SESSION['active_attempt_id'] ?? null;
        if ($attempt_id) {
            $attemptModel = new \App\Models\Attempt($this->db);
            $attemptModel->evaluateAttempt($attempt_id);
            unset($_SESSION['active_attempt_id']);
            header("Location: /student/exam/result?id=" . $attempt_id);
            exit;
        }
        header("Location: /student/dashboard");
        exit;
    }

    public function result() {
        $attempt_id = $_GET['id'] ?? null;
        if (!$attempt_id) {
            header('Location: /student/dashboard');
            exit;
        }

        $attemptModel = new \App\Models\Attempt($this->db);
        $result = $attemptModel->getResultDetails($attempt_id);
        
        if ($result['user_id'] != $_SESSION['user_id']) {
            header('Location: /student/dashboard');
            exit;
        }

        $title = 'Exam Result';
        ob_start();
        require_once __DIR__ . '/../Views/student/result.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/student.php';
    }

    public function logViolation() {
        $attempt_id = $_SESSION['active_attempt_id'] ?? null;
        if ($attempt_id) {
            $attemptModel = new \App\Models\Attempt($this->db);
            $attemptModel->logCheating($attempt_id);
        }
    }
}
