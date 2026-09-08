<?php
session_start();

// Simple autoloader for now
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    // e.g. App\Config\Database -> app/Config/Database.php
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Simple router to get started
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Determine the base path based on your setup. 
// If running via `php -S localhost:8000`, it will just be `/`
$basePath = '/';

if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = '/' . substr($uri, strlen($basePath));
}

switch ($uri) {
    case '/':
    case '/index.php':
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;
    
    case '/login':
        $controller = new \App\Controllers\AuthController();
        $controller->login();
        break;

    case '/register':
        $controller = new \App\Controllers\AuthController();
        $controller->register();
        break;

    case '/logout':
        $controller = new \App\Controllers\AuthController();
        $controller->logout();
        break;

    // Admin Routes
    case '/admin/dashboard':
        $controller = new \App\Controllers\AdminController();
        $controller->dashboard();
        break;
        
    case '/admin/exams':
        $controller = new \App\Controllers\AdminController();
        $controller->exams();
        break;
        
    case '/admin/exams/create':
        $controller = new \App\Controllers\AdminController();
        $controller->createExam();
        break;
        
    case '/admin/students':
        $controller = new \App\Controllers\AdminController();
        $controller->students();
        break;

    case '/admin/students/profile':
        $controller = new \App\Controllers\AdminController();
        $controller->studentProfile();
        break;

    case '/admin/students/edit':
        $controller = new \App\Controllers\AdminController();
        $controller->editStudent();
        break;

    case '/admin/students/update':
        $controller = new \App\Controllers\AdminController();
        $controller->updateStudent();
        break;

    case '/admin/students/toggle-status':
        $controller = new \App\Controllers\AdminController();
        $controller->toggleStudentStatus();
        break;

    case '/admin/students/delete':
        $controller = new \App\Controllers\AdminController();
        $controller->deleteStudent();
        break;

    case '/admin/exams/store':
        $controller = new \App\Controllers\AdminController();
        $controller->storeExam();
        break;

    case '/admin/exams/edit':
        $controller = new \App\Controllers\AdminController();
        $controller->editExam();
        break;

    case '/admin/exams/update':
        $controller = new \App\Controllers\AdminController();
        $controller->updateExam();
        break;

    case '/admin/exams/delete':
        $controller = new \App\Controllers\AdminController();
        $controller->deleteExam();
        break;

    case '/admin/questions':
        $controller = new \App\Controllers\AdminController();
        $controller->questions();
        break;

    case '/admin/questions/ajax':
        $controller = new \App\Controllers\AdminController();
        $controller->questionsAjax();
        break;

    case '/admin/questions/import-csv':
        $controller = new \App\Controllers\AdminController();
        $controller->importQuestionsCSV();
        break;

    case '/admin/questions/sample-csv':
        $controller = new \App\Controllers\AdminController();
        $controller->downloadSampleCSV();
        break;

    case '/admin/questions/store':
        $controller = new \App\Controllers\AdminController();
        $controller->storeQuestion();
        break;

    case '/admin/questions/edit':
        $controller = new \App\Controllers\AdminController();
        $controller->editQuestion();
        break;

    case '/admin/questions/update':
        $controller = new \App\Controllers\AdminController();
        $controller->updateQuestion();
        break;

    case '/admin/questions/delete':
        $controller = new \App\Controllers\AdminController();
        $controller->deleteQuestion();
        break;
        
    case '/admin/results':
        $controller = new \App\Controllers\AdminController();
        $controller->results();
        break;

    case '/admin/results/attempt-detail':
        $controller = new \App\Controllers\AdminController();
        $controller->attemptDetail();
        break;

    case '/admin/users':
        $controller = new \App\Controllers\AdminController();
        $controller->users();
        break;

    case '/admin/users/create':
        $controller = new \App\Controllers\AdminController();
        $controller->createUser();
        break;

    case '/admin/users/store':
        $controller = new \App\Controllers\AdminController();
        $controller->storeUser();
        break;

    case '/admin/users/edit':
        $controller = new \App\Controllers\AdminController();
        $controller->editUser();
        break;

    case '/admin/users/update':
        $controller = new \App\Controllers\AdminController();
        $controller->updateUser();
        break;

    case '/admin/users/toggle-status':
        $controller = new \App\Controllers\AdminController();
        $controller->toggleUserStatus();
        break;

    case '/admin/users/delete':
        $controller = new \App\Controllers\AdminController();
        $controller->deleteUser();
        break;

    // Student Routes
    case '/student/dashboard':
        $controller = new \App\Controllers\StudentController();
        $controller->dashboard();
        break;

    case '/student/exam/instructions':
        $controller = new \App\Controllers\StudentController();
        $controller->instructions();
        break;

    case '/student/exam/start':
        $controller = new \App\Controllers\StudentController();
        $controller->startExam();
        break;

    case '/student/exam/active':
        $controller = new \App\Controllers\StudentController();
        $controller->activeExam();
        break;

    case '/student/exam/save_answer':
        $controller = new \App\Controllers\StudentController();
        $controller->saveAnswer();
        break;

    case '/student/exam/submit':
        $controller = new \App\Controllers\StudentController();
        $controller->submitExam();
        break;
        
    case '/student/exam/result':
        $controller = new \App\Controllers\StudentController();
        $controller->result();
        break;

    case '/student/exam/violation':
        $controller = new \App\Controllers\StudentController();
        $controller->logViolation();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        break;
}
