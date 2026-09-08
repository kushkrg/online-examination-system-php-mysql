<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Online Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .sidebar { min-height: 100vh; background-color: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); }
        .sidebar .nav-link { color: #4b5563; font-weight: 500; margin-bottom: 5px; border-radius: 8px; padding: 10px 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #4F46E5; color: #fff; }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { padding: 30px; }
        .top-navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.02); padding: 15px 30px; margin-bottom: 30px; border-radius: 12px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .btn-primary { background-color: #4F46E5; border-color: #4F46E5; }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <h4 class="text-primary fw-bold mb-4 px-2">🚀 ExamApp</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false) ? 'active' : '' ?>" href="/admin/dashboard">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/exams') !== false) ? 'active' : '' ?>" href="/admin/exams">
                            <i class="bi bi-card-checklist"></i> Exams
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/students') !== false) ? 'active' : '' ?>" href="/admin/students">
                            <i class="bi bi-people"></i> Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/results') !== false) ? 'active' : '' ?>" href="/admin/results">
                            <i class="bi bi-graph-up"></i> Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false) ? 'active' : '' ?>" href="/admin/users">
                            <i class="bi bi-shield-lock"></i> Users
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="/logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center top-navbar">
                    <h5 class="m-0 fw-bold"><?= $title ?? 'Dashboard' ?></h5>
                    <div class="d-flex align-items-center">
                        <span class="me-3 fw-medium">Admin Portal</span>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-weight:bold;">
                            A
                        </div>
                    </div>
                </div>

                <?= $content ?>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
