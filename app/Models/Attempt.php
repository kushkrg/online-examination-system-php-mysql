<?php
namespace App\Models;

use PDO;

class Attempt {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function startAttempt($user_id, $exam_id) {
        $query = "SELECT id, start_time FROM exam_attempts WHERE user_id = :user_id AND exam_id = :exam_id AND status = 'in_progress'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'exam_id' => $exam_id]);
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['id']; 
        }

        $query = "INSERT INTO exam_attempts (user_id, exam_id, start_time, status) VALUES (:user_id, :exam_id, NOW(), 'in_progress')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'exam_id' => $exam_id]);
        
        return $this->conn->lastInsertId();
    }

    public function getAttempt($attempt_id) {
        $query = "SELECT * FROM exam_attempts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $attempt_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveAnswer($attempt_id, $question_id, $option_id) {
        $query = "SELECT id FROM student_answers WHERE attempt_id = :attempt_id AND question_id = :question_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['attempt_id' => $attempt_id, 'question_id' => $question_id]);
        
        if ($stmt->rowCount() > 0) {
            $update = "UPDATE student_answers SET selected_option_id = :option_id WHERE attempt_id = :attempt_id AND question_id = :question_id";
            $upStmt = $this->conn->prepare($update);
            return $upStmt->execute(['option_id' => $option_id, 'attempt_id' => $attempt_id, 'question_id' => $question_id]);
        } else {
            $insert = "INSERT INTO student_answers (attempt_id, question_id, selected_option_id) VALUES (:attempt_id, :question_id, :option_id)";
            $inStmt = $this->conn->prepare($insert);
            return $inStmt->execute(['attempt_id' => $attempt_id, 'question_id' => $question_id, 'option_id' => $option_id]);
        }
    }

    public function getSavedAnswers($attempt_id) {
        $query = "SELECT question_id, selected_option_id FROM student_answers WHERE attempt_id = :attempt_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['attempt_id' => $attempt_id]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    public function evaluateAttempt($attempt_id) {
        $attempt = $this->getAttempt($attempt_id);
        if (!$attempt || $attempt['status'] === 'evaluated') return false;

        $examQuery = $this->conn->prepare("SELECT * FROM exams WHERE id = :id");
        $examQuery->execute(['id' => $attempt['exam_id']]);
        $exam = $examQuery->fetch(PDO::FETCH_ASSOC);

        $answersQuery = $this->conn->prepare("
            SELECT sa.id, sa.selected_option_id, q.marks, o.is_correct 
            FROM student_answers sa 
            JOIN questions q ON sa.question_id = q.id 
            LEFT JOIN options o ON sa.selected_option_id = o.id 
            WHERE sa.attempt_id = :attempt_id
        ");
        $answersQuery->execute(['attempt_id' => $attempt_id]);
        $answers = $answersQuery->fetchAll(PDO::FETCH_ASSOC);

        $total_score = 0.00;
        $negative_penalty = (float) $exam['negative_marking_ratio'];

        foreach ($answers as $ans) {
            if ($ans['selected_option_id']) {
                if ($ans['is_correct']) {
                    $total_score += (float) $ans['marks'];
                    $updateAns = $this->conn->prepare("UPDATE student_answers SET marks_obtained = :marks WHERE id = :id");
                    $updateAns->execute(['marks' => $ans['marks'], 'id' => $ans['id']]);
                } else {
                    $total_score -= $negative_penalty;
                    $updateAns = $this->conn->prepare("UPDATE student_answers SET marks_obtained = :marks WHERE id = :id");
                    $updateAns->execute(['marks' => -$negative_penalty, 'id' => $ans['id']]);
                }
            }
        }

        $total_score = max(0, $total_score); // Floor at 0
        $updateAttempt = $this->conn->prepare("UPDATE exam_attempts SET status = 'evaluated', score = :score, end_time = NOW() WHERE id = :id");
        return $updateAttempt->execute(['score' => $total_score, 'id' => $attempt_id]);
    }

    public function getResultDetails($attempt_id) {
        $query = "
            SELECT ea.*, e.title, e.total_marks, e.passing_marks, 
                   (SELECT COUNT(*) FROM questions WHERE exam_id = e.id) as total_questions,
                   (SELECT COUNT(*) FROM student_answers sa JOIN options o ON sa.selected_option_id = o.id WHERE sa.attempt_id = ea.id AND o.is_correct = 1) as correct_answers,
                   (SELECT COUNT(*) FROM student_answers sa JOIN options o ON sa.selected_option_id = o.id WHERE sa.attempt_id = ea.id AND o.is_correct = 0) as incorrect_answers
            FROM exam_attempts ea 
            JOIN exams e ON ea.exam_id = e.id 
            WHERE ea.id = :id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $attempt_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllResults($search = '', $examId = '', $result = '') {
        $where = "WHERE ea.status = 'evaluated'";
        $params = [];
        if (!empty($search)) {
            $where .= " AND (u.name LIKE :search OR u.email LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if (!empty($examId)) {
            $where .= " AND e.id = :exam_id";
            $params['exam_id'] = $examId;
        }
        if ($result === 'pass') {
            $where .= " AND ea.score >= e.passing_marks";
        } elseif ($result === 'fail') {
            $where .= " AND ea.score < e.passing_marks";
        }
        $query = "
            SELECT ea.id, ea.score, ea.status, ea.start_time, ea.end_time, ea.tab_switches,
                   u.name as student_name, u.email,
                   e.title as exam_title, e.passing_marks, e.total_marks, e.id as exam_id,
                   (SELECT COUNT(*) FROM student_answers sa2 JOIN options o2 ON sa2.selected_option_id = o2.id WHERE sa2.attempt_id = ea.id AND o2.is_correct = 1) as correct_count,
                   (SELECT COUNT(*) FROM student_answers sa2 JOIN options o2 ON sa2.selected_option_id = o2.id WHERE sa2.attempt_id = ea.id AND o2.is_correct = 0) as wrong_count,
                   (SELECT COUNT(*) FROM questions q2 WHERE q2.exam_id = e.id) as total_questions
            FROM exam_attempts ea
            JOIN users u ON ea.user_id = u.id
            JOIN exams e ON ea.exam_id = e.id
            $where
            ORDER BY ea.end_time DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResultStats() {
        $query = "
            SELECT
                COUNT(*) as total_attempts,
                SUM(ea.score >= e.passing_marks) as total_pass,
                SUM(ea.score < e.passing_marks) as total_fail,
                ROUND(AVG(ea.score), 1) as avg_score,
                SUM(ea.tab_switches > 0) as cheating_flags,
                COUNT(DISTINCT ea.user_id) as unique_students
            FROM exam_attempts ea
            JOIN exams e ON ea.exam_id = e.id
            WHERE ea.status = 'evaluated'
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAttemptDetail($attempt_id) {
        $query = "
            SELECT q.question_text, q.image_url,
                   o_sel.option_text as selected_text,
                   o_correct.option_text as correct_text,
                   o_sel.is_correct as is_correct,
                   sa.marks_obtained,
                   q.marks as full_marks
            FROM student_answers sa
            JOIN questions q ON sa.question_id = q.id
            LEFT JOIN options o_sel ON sa.selected_option_id = o_sel.id
            LEFT JOIN options o_correct ON o_correct.question_id = q.id AND o_correct.is_correct = 1
            WHERE sa.attempt_id = :attempt_id
            ORDER BY q.id ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['attempt_id' => $attempt_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExamList() {
        $query = "SELECT DISTINCT e.id, e.title FROM exam_attempts ea JOIN exams e ON ea.exam_id = e.id WHERE ea.status = 'evaluated' ORDER BY e.title";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logCheating($attempt_id) {
        $query = "UPDATE exam_attempts SET tab_switches = tab_switches + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $attempt_id]);
    }
}

