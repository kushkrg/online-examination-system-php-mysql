<?php
namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use App\Models\Attempt;
use App\Config\Database;

class AdminController {
    private $db;
    private $examModel;
    private $questionModel;
    private $userModel;

    public function __construct() {
        AuthHelper::requireRole('admin');
        $database = new Database();
        $this->db = $database->connect();
        $this->examModel = new Exam($this->db);
        $this->questionModel = new Question($this->db);
        $this->userModel = new User($this->db);
    }

    public function dashboard() {
        $examStats   = $this->examModel->getExamStats();
        $userStats   = $this->userModel->getAllUserStats();
        $attemptModel = new \App\Models\Attempt($this->db);
        $resultStats  = $attemptModel->getResultStats();
        
        $title = 'Dashboard';
        ob_start();
        require_once __DIR__ . '/../Views/admin/dashboard.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function exams() {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $exams  = $this->examModel->getAllExams($search, $status);
        $stats  = $this->examModel->getExamStats();
        
        $title = 'Manage Exams';
        ob_start();
        require_once __DIR__ . '/../Views/admin/exams/index.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function createExam() {
        $title = 'Create New Exam';
        ob_start();
        require_once __DIR__ . '/../Views/admin/exams/create.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function storeExam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'start_time' => str_replace('T', ' ', $_POST['start_time']) . ':00',
                'end_time' => str_replace('T', ' ', $_POST['end_time']) . ':00',
                'total_marks' => $_POST['total_marks'],
                'passing_marks' => $_POST['passing_marks'],
                'negative_marking_ratio' => $_POST['negative_marking'] ?? 0,
                'status' => $_POST['status'],
                'created_by' => $_SESSION['user_id']
            ];

            if ($this->examModel->create($data)) {
                header('Location: /admin/exams?success=created');
                exit;
            } else {
                echo "Failed to create exam.";
            }
        }
    }

    public function editExam() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/exams');
            exit;
        }

        $exam = $this->examModel->getById($id);
        if (!$exam) {
            header('Location: /admin/exams');
            exit;
        }

        $title = 'Edit Exam';
        ob_start();
        require_once __DIR__ . '/../Views/admin/exams/edit.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function updateExam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'start_time' => str_replace('T', ' ', $_POST['start_time']) . ':00',
                'end_time' => str_replace('T', ' ', $_POST['end_time']) . ':00',
                'total_marks' => $_POST['total_marks'],
                'passing_marks' => $_POST['passing_marks'],
                'negative_marking_ratio' => $_POST['negative_marking'] ?? 0,
                'status' => $_POST['status']
            ];

            if ($this->examModel->update($id, $data)) {
                header('Location: /admin/exams?success=updated');
                exit;
            } else {
                echo "Failed to update exam.";
            }
        }
    }

    public function deleteExam() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->examModel->delete($id);
        }
        header('Location: /admin/exams?success=deleted');
        exit;
    }

    public function questions() {
        $exam_id = $_GET['exam_id'] ?? null;
        if (!$exam_id) {
            header('Location: /admin/exams');
            exit;
        }
        
        $examQuery = $this->db->prepare("SELECT * FROM exams WHERE id = :id");
        $examQuery->execute(['id' => $exam_id]);
        $exam = $examQuery->fetch(\PDO::FETCH_ASSOC);
        
        $title = 'Manage Questions - ' . $exam['title'];
        ob_start();
        require_once __DIR__ . '/../Views/admin/exams/questions.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function questionsAjax() {
        header('Content-Type: application/json');
        $exam_id = $_GET['exam_id'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        $limit = 5; 
        
        if (!$exam_id) {
            echo json_encode(['error' => 'Missing exam_id']);
            exit;
        }

        $total = $this->questionModel->countByExamId($exam_id, $search);
        $questions = $this->questionModel->getPaginatedByExamId($exam_id, $page, $limit, $search);
        
        echo json_encode([
            'questions' => $questions,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
        exit;
    }

    public function students() {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $students = $this->userModel->getAllStudents($search, $status);
        $stats = $this->userModel->getStudentStats();
        
        $title = 'Manage Students';
        ob_start();
        require_once __DIR__ . '/../Views/admin/students/index.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function studentProfile() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['error' => 'Missing ID']); exit; }
        $student = $this->userModel->getStudentById($id);
        $history = $this->userModel->getStudentExamHistory($id);
        echo json_encode(['student' => $student, 'history' => $history]);
        exit;
    }

    public function editStudent() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: /admin/students'); exit; }
        $student = $this->userModel->getStudentById($id);
        if (!$student) { header('Location: /admin/students'); exit; }
        $title = 'Edit Student';
        ob_start();
        require_once __DIR__ . '/../Views/admin/students/edit.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function updateStudent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'name'     => $_POST['name'],
                'email'    => $_POST['email'],
                'city'     => $_POST['city'] ?? null,
                'status'   => $_POST['status'],
                'password' => $_POST['password'] ?? '',
            ];
            if ($this->userModel->updateStudent($id, $data)) {
                header('Location: /admin/students?success=updated');
            } else {
                header('Location: /admin/students/edit?id=' . $id . '&error=1');
            }
            exit;
        }
    }

    public function toggleStudentStatus() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if ($id && $this->userModel->toggleStatus($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function deleteStudent() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if ($id && $this->userModel->deleteStudent($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function storeQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exam_id = $_POST['exam_id'];
            $question_text = $_POST['question_text'];
            $marks = $_POST['marks'] ?? 1.00;
            
            $image_url = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $dest = __DIR__ . '/../../public/uploads/questions/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image_url = '/uploads/questions/' . $filename;
                }
            }
            
            $options = [];
            $correct_option = $_POST['correct_option'] ?? 0;
            
            foreach ($_POST['options'] as $index => $optText) {
                if (!empty(trim($optText))) {
                    $options[] = [
                        'text' => trim($optText),
                        'is_correct' => ($index == $correct_option)
                    ];
                }
            }

            if ($this->questionModel->createMCQ($exam_id, $question_text, $marks, $options, $image_url)) {
                header("Location: /admin/questions?exam_id=$exam_id&success=added");
                exit;
            } else {
                echo "Failed to add question.";
            }
        }
    }

    public function importQuestionsCSV() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/exams'); exit;
        }
        $exam_id = $_POST['exam_id'] ?? null;
        if (!$exam_id || !isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            header("Location: /admin/questions?exam_id=$exam_id&error=upload"); exit;
        }

        $imported = 0;
        $errors   = [];
        if (($handle = fopen($_FILES['csv_file']['tmp_name'], 'r')) !== false) {
            $header = fgetcsv($handle); // Skip header row
            $row_num = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $row_num++;
                if (count($row) < 6) {
                    $errors[] = "Row $row_num: Not enough columns (expected at least 6).";
                    continue;
                }
                [$question_text, $marks, $opt_a, $opt_b, $opt_c, $opt_d, $correct] = array_pad($row, 7, '');
                $question_text = trim($question_text);
                $marks = is_numeric(trim($marks)) ? (float)trim($marks) : 1.0;
                $correct = strtoupper(trim($correct ?? 'A'));

                $opts_raw = array_filter([
                    'A' => trim($opt_a),
                    'B' => trim($opt_b),
                    'C' => trim($opt_c),
                    'D' => trim($opt_d),
                ]);
                if (empty($question_text) || count($opts_raw) < 2) {
                    $errors[] = "Row $row_num: Question text or options are empty.";
                    continue;
                }
                if (!isset($opts_raw[$correct])) {
                    $correct = array_key_first($opts_raw);
                }

                $options = [];
                foreach ($opts_raw as $letter => $text) {
                    $options[] = ['text' => $text, 'is_correct' => ($letter === $correct)];
                }

                if ($this->questionModel->createMCQ($exam_id, $question_text, $marks, $options)) {
                    $imported++;
                } else {
                    $errors[] = "Row $row_num: Failed to save to database.";
                }
            }
            fclose($handle);
        }

        $msg = urlencode("Imported $imported question(s) successfully." . (!empty($errors) ? ' Errors: ' . implode(' | ', $errors) : ''));
        header("Location: /admin/questions?exam_id=$exam_id&import_msg=" . $msg);
        exit;
    }

    public function downloadSampleCSV() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sample_questions.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['question_text', 'marks', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option']);
        fputcsv($out, ['What is the capital of France?', '1', 'Berlin', 'Paris', 'Rome', 'Madrid', 'B']);
        fputcsv($out, ['Which planet is closest to the sun?', '2', 'Earth', 'Venus', 'Mercury', 'Mars', 'C']);
        fputcsv($out, ['True or False: PHP is a programming language.', '1', 'True', 'False', '', '', 'A']);
        fclose($out);
        exit;
    }

    public function editQuestion() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/exams');
            exit;
        }

        $question = $this->questionModel->getById($id);
        if (!$question) {
            header('Location: /admin/exams');
            exit;
        }

        $title = 'Edit Question';
        ob_start();
        require_once __DIR__ . '/../Views/admin/exams/question_edit.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function updateQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $exam_id = $_POST['exam_id'];
            $question_text = $_POST['question_text'];
            $marks = $_POST['marks'] ?? 1.00;
            
            $image_url = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $dest = __DIR__ . '/../../public/uploads/questions/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image_url = '/uploads/questions/' . $filename;
                }
            }
            
            $options = [];
            $correct_option = $_POST['correct_option'] ?? 0;
            
            foreach ($_POST['options'] as $index => $optText) {
                if (!empty(trim($optText))) {
                    $options[] = [
                        'text' => trim($optText),
                        'is_correct' => ($index == $correct_option)
                    ];
                }
            }

            if ($this->questionModel->updateMCQ($id, $question_text, $marks, $options, $image_url)) {
                header("Location: /admin/questions?exam_id=$exam_id&success=updated");
                exit;
            } else {
                echo "Failed to update question.";
            }
        }
    }

    public function deleteQuestion() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        $exam_id = $_GET['exam_id'] ?? null;
        if ($id) {
            $result = $this->questionModel->deleteMCQ($id);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database deletion failed']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing ID']);
        }
        exit;
    }

    public function results() {
        $attemptModel = new Attempt($this->db);
        $search  = $_GET['search']  ?? '';
        $examId  = $_GET['exam_id'] ?? '';
        $result  = $_GET['result']  ?? '';
        $results = $attemptModel->getAllResults($search, $examId, $result);
        $stats   = $attemptModel->getResultStats();
        $exams   = $attemptModel->getExamList();

        $title = 'Exam Results';
        ob_start();
        require_once __DIR__ . '/../Views/admin/results/index.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function attemptDetail() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['error' => 'Missing ID']); exit; }
        $attemptModel = new Attempt($this->db);
        $data = $attemptModel->getAttemptDetail($id);
        echo json_encode($data);
        exit;
    }
    public function users() {
        $search = $_GET['search'] ?? '';
        $role   = $_GET['role']   ?? '';
        $status = $_GET['status'] ?? '';
        $users  = $this->userModel->getAllUsers($search, $role, $status);
        $stats  = $this->userModel->getAllUserStats();

        $title = 'User Management';
        ob_start();
        require_once __DIR__ . '/../Views/admin/users/index.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function createUser() {
        $title = 'Create New User';
        ob_start();
        require_once __DIR__ . '/../Views/admin/users/create.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->emailExists($_POST['email'])) {
                header('Location: /admin/users/create?error=email_exists');
                exit;
            }
            $data = [
                'name'     => $_POST['name'],
                'email'    => $_POST['email'],
                'password' => $_POST['password'],
                'role'     => $_POST['role'],
                'city'     => $_POST['city'] ?? null,
                'status'   => $_POST['status'] ?? 'active',
            ];
            if ($this->userModel->createUser($data)) {
                header('Location: /admin/users?success=created');
            } else {
                header('Location: /admin/users/create?error=create_failed');
            }
            exit;
        }
    }

    public function editUser() {
        $id   = $_GET['id'] ?? null;
        if (!$id) { header('Location: /admin/users'); exit; }
        $user = $this->userModel->getUserById($id);
        if (!$user) { header('Location: /admin/users'); exit; }
        $title = 'Edit User';
        ob_start();
        require_once __DIR__ . '/../Views/admin/users/edit.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layouts/admin.php';
    }

    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id   = $_POST['id'];
            $data = [
                'name'     => $_POST['name'],
                'email'    => $_POST['email'],
                'city'     => $_POST['city'] ?? null,
                'role'     => $_POST['role'],
                'status'   => $_POST['status'],
                'password' => $_POST['password'] ?? '',
            ];
            if ($this->userModel->updateUser($id, $data)) {
                header('Location: /admin/users?success=updated');
            } else {
                header('Location: /admin/users/edit?id=' . $id . '&error=1');
            }
            exit;
        }
    }

    public function toggleUserStatus() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if ($id && $this->userModel->toggleUserStatus($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function deleteUser() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['success' => false, 'error' => 'Missing ID']); exit; }
        $result = $this->userModel->deleteUser($id);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Cannot delete the last active admin.']);
        }
        exit;
    }
}
