<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    // Database configuration settings
    private $host = '127.0.0.1';
    private $db_name = 'online_exam_db'; // We will create this database soon
    private $username = 'root';
    private $password = ''; // Default MAMP/XAMPP password can be empty or 'root'
    
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Set error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            // For production, log this error instead of showing it to the user
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
