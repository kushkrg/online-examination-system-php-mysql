<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class HomeController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index() {
        // Fetch published exams visible to the public
        $stmt = $this->db->prepare("
            SELECT e.id, e.title, e.description, e.duration_minutes, e.start_time, e.end_time,
                   e.total_marks, e.passing_marks, e.negative_marking_ratio,
                   (SELECT COUNT(*) FROM questions q WHERE q.exam_id = e.id) as question_count,
                   (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.exam_id = e.id) as attempt_count
            FROM exams e
            WHERE e.status = 'published'
            ORDER BY e.start_time ASC
        ");
        $stmt->execute();
        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Welcome — Online Exam Portal';
        require_once __DIR__ . '/../Views/home.php';
    }
}
