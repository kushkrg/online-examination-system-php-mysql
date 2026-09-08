<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; }

        /* Navbar */
        .top-nav { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 14px 0; position: sticky; top: 0; z-index: 100; }
        .nav-brand { font-size: 1.3rem; font-weight: 800; background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }

        /* Hero */
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%); color: #fff; padding: 80px 0 60px; }
        .hero h1 { font-size: 3rem; font-weight: 800; line-height: 1.15; }
        .hero p { font-size: 1.15rem; opacity: .85; }
        .hero-badge { display:inline-block; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3); border-radius:99px; padding:4px 16px; font-size:.85rem; margin-bottom:16px; }

        /* Floating Card visual */
        .hero-card { background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); border-radius:16px; padding:24px; backdrop-filter:blur(8px); }
        .hero-stat { text-align:center; padding:12px; }
        .hero-stat .val { font-size:2rem; font-weight:800; }
        .hero-stat .lbl { font-size:.78rem; opacity:.75; }

        /* Exam Cards */
        .exam-card { border:none; border-radius:16px; box-shadow:0 2px 16px rgba(0,0,0,.06); transition:transform .2s, box-shadow .2s; overflow:hidden; }
        .exam-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(79,70,229,.15); }
        .exam-card .card-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color:#fff; border:none; padding:20px; }
        .exam-card .status-live { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.2);border-radius:99px;padding:3px 12px;font-size:.78rem; }
        .exam-card .pulse { width:7px;height:7px;border-radius:50%;background:#4ade80;animation:pulse 1s infinite; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:.3} }

        /* Section headers */
        .section-title { font-size:1.75rem; font-weight:800; }

        /* CTA section */
        .cta-section { background:linear-gradient(135deg,#1e1b4b,#312e81); color:#fff; border-radius:24px; padding:48px; }

        /* Stat strip */
        .stat-strip { background:#fff; border-radius:16px; box-shadow:0 2px 16px rgba(0,0,0,.06); padding:28px; }
        .stat-item { text-align:center; padding:0 16px; }
        .stat-item .num { font-size:2rem; font-weight:800; color:#4f46e5; }
        .stat-item .label { font-size:.8rem; color:#6b7280; }
        .stat-divider { width:1px; background:#e5e7eb; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="top-nav">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="nav-brand"><i class="bi bi-mortarboard-fill" style="-webkit-text-fill-color:#4f46e5;"></i> ExamPortal</span>
        <div class="d-flex gap-2">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php $dest = ($_SESSION['user_role'] === 'admin') ? '/admin/dashboard' : '/student/dashboard'; ?>
                <a href="<?= $dest ?>" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-grid-1x2 me-1"></i>Go to Dashboard
                </a>
                <a href="/logout" class="btn btn-outline-secondary btn-sm">Logout</a>
            <?php else: ?>
                <a href="/login"    class="btn btn-outline-secondary btn-sm fw-semibold">Login</a>
                <a href="/register" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-person-plus me-1"></i>Register Free
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-badge"><i class="bi bi-lightning-charge-fill me-1"></i>Trusted Online Exam Platform</span>
                <h1>Ace Your Exams<br>with Confidence</h1>
                <p class="mb-4">Register for free, browse live exams, and get instant results. Real-time proctoring ensures exam integrity.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/register" class="btn btn-light btn-lg fw-bold shadow px-4">
                        <i class="bi bi-person-plus me-2"></i>Register Now
                    </a>
                    <a href="/login" class="btn btn-outline-light btn-lg fw-semibold px-4">Login</a>
                    <?php else: ?>
                    <a href="/student/dashboard" class="btn btn-light btn-lg fw-bold shadow px-4">
                        <i class="bi bi-grid-1x2 me-2"></i>Go to Dashboard
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card">
                    <div class="row g-0">
                        <div class="col-4 hero-stat border-end border-white border-opacity-25">
                            <div class="val"><?= count($exams) ?></div>
                            <div class="lbl">Active Exams</div>
                        </div>
                        <div class="col-4 hero-stat border-end border-white border-opacity-25">
                            <div class="val">🔒</div>
                            <div class="lbl">Proctored</div>
                        </div>
                        <div class="col-4 hero-stat">
                            <div class="val">⚡</div>
                            <div class="lbl">Instant Results</div>
                        </div>
                    </div>
                    <hr style="border-color:rgba(255,255,255,.2);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-shield-check fs-2 opacity-75"></i>
                        <div>
                            <div class="fw-semibold">Anti-Cheat Protected</div>
                            <div class="small opacity-75">Tab-switch monitoring & real-time violation alerts</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container py-5">

    <!-- Available Exams -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">Available Exams</h2>
            <p class="text-muted mb-0">Browse all currently published exams. Register or login to attempt.</p>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fs-6 px-3 py-2">
            <?= count($exams) ?> Exam(s) Live
        </span>
    </div>

    <?php if (empty($exams)): ?>
    <div class="text-center py-5">
        <div class="card border-0 shadow-sm py-5 px-4" style="border-radius:16px;">
            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3 opacity-50"></i>
            <h5 class="text-muted fw-semibold">No Exams Available Right Now</h5>
            <p class="text-muted small mb-4">There are no published exams at the moment. Please check back later.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/register" class="btn btn-primary mx-auto" style="width:fit-content;">Register to Get Notified</a>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-4 mb-5">
        <?php foreach ($exams as $exam):
            $now   = new DateTime();
            $start = new DateTime($exam['start_time']);
            $end   = new DateTime($exam['end_time']);
            $isLive    = $now >= $start && $now <= $end;
            $isUpcoming = $now < $start;
            $isEnded   = $now > $end;
            $hue = (crc32($exam['title']) % 360 + 360) % 360;
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="card exam-card h-100">
                <div class="card-header" style="background:linear-gradient(135deg,hsl(<?=$hue?>,65%,45%),hsl(<?=($hue+30)%360?>,70%,50%));">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="fw-bold mb-1 me-2"><?= htmlspecialchars($exam['title']) ?></h5>
                        <?php if ($isLive): ?>
                            <span class="status-live flex-shrink-0"><span class="pulse"></span>LIVE</span>
                        <?php elseif ($isUpcoming): ?>
                            <span class="badge bg-light text-dark flex-shrink-0"><i class="bi bi-clock me-1"></i>Upcoming</span>
                        <?php else: ?>
                            <span class="badge bg-dark bg-opacity-50 flex-shrink-0">Ended</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($exam['description'])): ?>
                    <p class="small mb-0 mt-1 opacity-80" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        <?= htmlspecialchars($exam['description']) ?>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-4 text-center">
                            <div class="bg-light rounded py-2">
                                <div class="fw-bold text-primary"><?= $exam['question_count'] ?></div>
                                <div class="text-muted" style="font-size:11px;">Questions</div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="bg-light rounded py-2">
                                <div class="fw-bold text-info"><?= $exam['duration_minutes'] ?> min</div>
                                <div class="text-muted" style="font-size:11px;">Duration</div>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="bg-light rounded py-2">
                                <div class="fw-bold text-success"><?= rtrim(rtrim($exam['total_marks'],'0'),'.') ?></div>
                                <div class="text-muted" style="font-size:11px;">Total Marks</div>
                            </div>
                        </div>
                    </div>

                    <ul class="list-unstyled small text-muted mb-3">
                        <li class="mb-1"><i class="bi bi-check2-circle text-success me-2"></i>Pass Marks: <strong><?= rtrim(rtrim($exam['passing_marks'],'0'),'.') ?></strong></li>
                        <li class="mb-1"><i class="bi bi-calendar-event me-2 text-primary"></i>Start: <strong><?= date('d M Y, h:i A', strtotime($exam['start_time'])) ?></strong></li>
                        <li class="mb-1"><i class="bi bi-calendar-x me-2 text-danger"></i>End: <strong><?= date('d M Y, h:i A', strtotime($exam['end_time'])) ?></strong></li>
                        <?php if ($exam['negative_marking_ratio'] > 0): ?>
                        <li><i class="bi bi-exclamation-triangle text-warning me-2"></i>Negative marking: <strong>-<?= $exam['negative_marking_ratio'] ?>/wrong</strong></li>
                        <?php endif; ?>
                    </ul>

                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-people me-1"></i>
                        <span><?= $exam['attempt_count'] ?> attempt(s)</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="/register" class="btn btn-primary w-100 fw-semibold shadow-sm">
                            <i class="bi bi-person-plus me-1"></i>Register to Attempt
                        </a>
                        <a href="/login" class="btn btn-link w-100 text-muted small mt-1 text-decoration-none">Already have an account? Login →</a>
                    <?php elseif ($_SESSION['user_role'] === 'student'): ?>
                        <?php if ($isLive): ?>
                            <a href="/student/exam/instructions?exam_id=<?= $exam['id'] ?>" class="btn btn-success w-100 fw-semibold shadow-sm">
                                <i class="bi bi-play-circle me-1"></i>Start Exam Now
                            </a>
                        <?php elseif ($isUpcoming): ?>
                            <button class="btn btn-outline-secondary w-100 fw-semibold" disabled>
                                <i class="bi bi-clock me-1"></i>Starts <?= date('d M, h:i A', strtotime($exam['start_time'])) ?>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled><i class="bi bi-x-circle me-1"></i>Exam Ended</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/admin/questions?exam_id=<?= $exam['id'] ?>" class="btn btn-outline-primary w-100 fw-semibold">
                            <i class="bi bi-list-check me-1"></i>Manage Questions
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- CTA Banner -->
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="cta-section text-center">
        <h3 class="fw-bold mb-2">Ready to Test Your Knowledge?</h3>
        <p class="opacity-75 mb-4">Create your free account in seconds and start attempting exams instantly.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/register" class="btn btn-light btn-lg fw-bold px-5 shadow">
                <i class="bi bi-person-plus me-2"></i>Register Free
            </a>
            <a href="/login" class="btn btn-outline-light btn-lg fw-semibold px-5">Login</a>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Footer -->
<footer class="text-center text-muted small py-4 border-top mt-5">
    &copy; <?= date('Y') ?> ExamPortal &mdash; All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
