<?php
namespace App\Models;

use PDO;

class Exam {
    private $conn;
    private $table = 'exams';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllExams($search = '', $status = '') {
        $where = "WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $where .= " AND (e.title LIKE :search OR e.description LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if (!empty($status)) {
            $where .= " AND e.status = :status";
            $params['status'] = $status;
        }
        $query = "SELECT e.*,
            (SELECT COUNT(*) FROM questions q WHERE q.exam_id = e.id) as question_count,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.exam_id = e.id) as attempt_count,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.exam_id = e.id AND ea.status = 'evaluated' AND ea.score >= e.passing_marks) as pass_count
            FROM " . $this->table . " e $where ORDER BY e.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (title, description, duration_minutes, start_time, end_time, total_marks, passing_marks, negative_marking_ratio, status, created_by) 
                VALUES 
                (:title, :description, :duration, :start_time, :end_time, :total_marks, :passing_marks, :negative_marking_ratio, :status, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':duration', $data['duration']);
        $stmt->bindParam(':start_time', $data['start_time']);
        $stmt->bindParam(':end_time', $data['end_time']);
        $stmt->bindParam(':total_marks', $data['total_marks']);
        $stmt->bindParam(':passing_marks', $data['passing_marks']);
        $stmt->bindParam(':negative_marking_ratio', $data['negative_marking_ratio']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':created_by', $data['created_by']);

        return $stmt->execute();
    }
    
    public function getById($id) {
        $query = "SELECT e.*,
            (SELECT COUNT(*) FROM questions q WHERE q.exam_id = e.id) as question_count,
            (SELECT COUNT(*) FROM exam_attempts ea WHERE ea.exam_id = e.id) as attempt_count
            FROM " . $this->table . " e WHERE e.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET 
                  title = :title, description = :description, duration_minutes = :duration, 
                  start_time = :start_time, end_time = :end_time, total_marks = :total_marks, 
                  passing_marks = :passing_marks, negative_marking_ratio = :negative_marking_ratio, 
                  status = :status 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    public function getExamStats() {
        $query = "SELECT 
            COUNT(*) as total,
            SUM(status = 'published') as published,
            SUM(status = 'draft') as draft,
            SUM(status = 'completed') as completed,
            (SELECT COUNT(*) FROM exam_attempts WHERE status = 'evaluated') as total_submissions
            FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

