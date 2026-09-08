<?php
namespace App\Models;

use PDO;

class Question {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByExamId($exam_id) {
        $query = "SELECT * FROM questions WHERE exam_id = :exam_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':exam_id', $exam_id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$q) {
            if ($q['question_type'] === 'mcq') {
                $optQuery = "SELECT * FROM options WHERE question_id = :q_id ORDER BY id ASC";
                $optStmt = $this->conn->prepare($optQuery);
                $optStmt->bindParam(':q_id', $q['id']);
                $optStmt->execute();
                $q['options'] = $optStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return $questions;
    }

    public function getPaginatedByExamId($exam_id, $page = 1, $limit = 5, $search = '') {
        $offset = ($page - 1) * $limit;
        $searchQuery = '';
        if (!empty($search)) {
            $searchQuery = " AND question_text LIKE :search";
        }

        $query = "SELECT * FROM questions WHERE exam_id = :exam_id" . $searchQuery . " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':exam_id', (int)$exam_id, PDO::PARAM_INT);
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$q) {
            if ($q['question_type'] === 'mcq') {
                $optQuery = "SELECT * FROM options WHERE question_id = :q_id ORDER BY id ASC";
                $optStmt = $this->conn->prepare($optQuery);
                $optStmt->execute(['q_id' => $q['id']]);
                $q['options'] = $optStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return $questions;
    }

    public function countByExamId($exam_id, $search = '') {
        $searchQuery = '';
        if (!empty($search)) {
            $searchQuery = " AND question_text LIKE :search";
        }
        $query = "SELECT COUNT(*) as total FROM questions WHERE exam_id = :exam_id" . $searchQuery;
        $stmt = $this->conn->prepare($query);
        
        $params = ['exam_id' => $exam_id];
        if (!empty($search)) {
            $params['search'] = '%' . $search . '%';
        }
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getById($id) {
        $query = "SELECT * FROM questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        $q = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($q && $q['question_type'] === 'mcq') {
            $optQuery = "SELECT * FROM options WHERE question_id = :q_id ORDER BY id ASC";
            $optStmt = $this->conn->prepare($optQuery);
            $optStmt->execute(['q_id' => $id]);
            $q['options'] = $optStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $q;
    }

    public function createMCQ($exam_id, $question_text, $marks, $options, $image_url = null) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO questions (exam_id, question_text, question_type, marks, image_url) VALUES (:exam_id, :question_text, 'mcq', :marks, :image_url)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':exam_id', $exam_id);
            $stmt->bindParam(':question_text', $question_text);
            $stmt->bindParam(':marks', $marks);
            $stmt->bindParam(':image_url', $image_url);
            $stmt->execute();
            $question_id = $this->conn->lastInsertId();

            $optQuery = "INSERT INTO options (question_id, option_text, is_correct) VALUES (:q_id, :opt_text, :is_correct)";
            $optStmt = $this->conn->prepare($optQuery);

            foreach ($options as $opt) {
                $is_correct = $opt['is_correct'] ? 1 : 0;
                $optStmt->bindParam(':q_id', $question_id);
                $optStmt->bindParam(':opt_text', $opt['text']);
                $optStmt->bindParam(':is_correct', $is_correct);
                $optStmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateMCQ($id, $question_text, $marks, $options, $image_url = null) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE questions SET question_text = :question_text, marks = :marks" . ($image_url ? ", image_url = :image_url" : "") . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $params = [
                'question_text' => $question_text,
                'marks' => $marks,
                'id' => $id
            ];
            if ($image_url) {
                $params['image_url'] = $image_url;
            }
            $stmt->execute($params);

            $delOpts = "DELETE FROM options WHERE question_id = :id";
            $delStmt = $this->conn->prepare($delOpts);
            $delStmt->execute(['id' => $id]);

            $optQuery = "INSERT INTO options (question_id, option_text, is_correct) VALUES (:q_id, :opt_text, :is_correct)";
            $optStmt = $this->conn->prepare($optQuery);

            foreach ($options as $opt) {
                $is_correct = $opt['is_correct'] ? 1 : 0;
                $optStmt->bindParam(':q_id', $id);
                $optStmt->bindParam(':opt_text', $opt['text']);
                $optStmt->bindParam(':is_correct', $is_correct);
                $optStmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function deleteMCQ($id) {
        $query = "DELETE FROM questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
