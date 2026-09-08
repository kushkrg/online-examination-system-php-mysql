<?php
require_once 'index.php'; // to load autoloader

use App\Config\Database;
use App\Models\Question;

$db = (new Database())->connect();
$q = new Question($db);

// See if there are any questions
$stmt = $db->query("SELECT id FROM questions LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $id = $row['id'];
    echo "Attempting to delete question $id\n";
    $result = $q->deleteMCQ($id);
    var_dump($result);
    if (!$result) {
        var_dump($stmt->errorInfo());
    }
} else {
    echo "No questions to delete.\n";
}
