<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Student Dashboard' ?> - Online Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; }
        .navbar { background-color: #4F46E5; }
        .navbar-brand, .nav-link { color: rgba(255,255,255,0.9) !important; }
        .nav-link.active, .nav-link:hover { color: #fff !important; font-weight: bold; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .hover-elevate { transition: transform 0.2s, box-shadow 0.2s; }
        .hover-elevate:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-4 py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/student/dashboard"><i class="bi bi-mortarboard-fill me-2"></i>Exam Portal</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/student/dashboard') !== false) ? 'active' : '' ?>" href="/student/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Results</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="/logout" class="btn btn-light btn-sm text-primary fw-bold px-3 rounded-pill">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <?= $content ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
