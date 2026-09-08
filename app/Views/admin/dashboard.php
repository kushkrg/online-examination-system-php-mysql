<?php
$passRate = ($resultStats['total_attempts'] ?? 0) > 0
    ? round(($resultStats['total_pass'] / $resultStats['total_attempts']) * 100, 1) : 0;
?>

<!-- Welcome Banner -->
<div class="rounded-4 p-4 mb-4 text-white" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">Welcome back, Admin! 👋</h4>
            <p class="opacity-75 mb-0">Here's what's happening in your exam system today.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/exams/create" class="btn btn-light fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i>New Exam
            </a>
            <a href="/admin/users/create" class="btn btn-outline-light fw-semibold">
                <i class="bi bi-person-plus me-1"></i>Add User
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <?php
    $cards = [
        ['label'=>'Total Exams',       'value'=>$examStats['total']??0,               'sub'=>($examStats['published']??0).' published',    'color'=>'#6366f1','icon'=>'bi-card-checklist',     'href'=>'/admin/exams'],
        ['label'=>'Total Users',       'value'=>$userStats['total']??0,               'sub'=>($userStats['students']??0).' students',       'color'=>'#22c55e','icon'=>'bi-people-fill',        'href'=>'/admin/users'],
        ['label'=>'Total Submissions', 'value'=>$resultStats['total_attempts']??0,    'sub'=>($resultStats['unique_students']??0).' unique students', 'color'=>'#f59e0b','icon'=>'bi-send-check-fill','href'=>'/admin/results'],
        ['label'=>'Pass Rate',         'value'=>$passRate.'%',                         'sub'=>($resultStats['total_pass']??0).' passed',    'color'=>'#06b6d4','icon'=>'bi-trophy-fill',        'href'=>'/admin/results'],
        ['label'=>'Avg Score',         'value'=>($resultStats['avg_score']??'—'),      'sub'=>'across all exams',                            'color'=>'#8b5cf6','icon'=>'bi-graph-up-arrow',     'href'=>'/admin/results'],
        ['label'=>'Integrity Flags',   'value'=>$resultStats['cheating_flags']??0,    'sub'=>'tab switches detected',                       'color'=>'#ef4444','icon'=>'bi-shield-exclamation', 'href'=>'/admin/results'],
    ];
    foreach($cards as $card): ?>
    <div class="col-sm-6 col-xl-4">
        <a href="<?= $card['href'] ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left:4px solid <?=$card['color']?> !important;transition:transform .15s,box-shadow .15s;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:<?=$card['color']?>1a;">
                        <i class="bi <?=$card['icon']?> fs-4" style="color:<?=$card['color']?>;"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold"><?=$card['label']?></div>
                        <div class="fw-bold fs-3 lh-1 text-dark"><?=$card['value']?></div>
                        <div class="text-muted" style="font-size:11px;"><?=$card['sub']?></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Exam Status Breakdown + Quick Actions -->
<div class="row g-4 mb-4">
    <!-- Status Breakdown -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold text-dark m-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Exam Status Breakdown</h6>
            </div>
            <div class="card-body pt-0">
                <?php
                $statusItems = [
                    ['label'=>'Published', 'value'=>$examStats['published']??0, 'total'=>max($examStats['total']??1,1), 'color'=>'#22c55e'],
                    ['label'=>'Draft',     'value'=>$examStats['draft']??0,     'total'=>max($examStats['total']??1,1), 'color'=>'#94a3b8'],
                    ['label'=>'Completed', 'value'=>$examStats['completed']??0, 'total'=>max($examStats['total']??1,1), 'color'=>'#6366f1'],
                ];
                foreach($statusItems as $si):
                    $pct = $si['total'] > 0 ? round(($si['value']/$si['total'])*100) : 0;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold text-dark"><?=$si['label']?></span>
                        <span class="text-muted"><?=$si['value']?> exam(s) &middot; <?=$pct?>%</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:99px;">
                        <div class="progress-bar" style="width:<?=$pct?>%;background:<?=$si['color']?>;border-radius:99px;"></div>
                    </div>
                </div>
                <?php endforeach; ?>

                <hr class="my-3">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-success"><?=$resultStats['total_pass']??0?></div>
                        <div class="text-muted small">Passed</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-danger"><?=$resultStats['total_fail']??0?></div>
                        <div class="text-muted small">Failed</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-warning"><?=$resultStats['cheating_flags']??0?></div>
                        <div class="text-muted small">Flags</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold text-dark m-0"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body pt-0 d-flex flex-column gap-2">
                <?php
                $actions = [
                    ['href'=>'/admin/exams/create',  'icon'=>'bi-plus-circle-fill',         'label'=>'Create New Exam',       'color'=>'#6366f1'],
                    ['href'=>'/admin/users/create',  'icon'=>'bi-person-plus-fill',          'label'=>'Add New User',          'color'=>'#22c55e'],
                    ['href'=>'/admin/exams',         'icon'=>'bi-card-checklist',            'label'=>'Manage All Exams',      'color'=>'#f59e0b'],
                    ['href'=>'/admin/students',      'icon'=>'bi-mortarboard-fill',          'label'=>'View Students',         'color'=>'#06b6d4'],
                    ['href'=>'/admin/results',       'icon'=>'bi-graph-up-arrow',            'label'=>'View Results & Analytics','color'=>'#8b5cf6'],
                    ['href'=>'/admin/users',         'icon'=>'bi-shield-lock-fill',          'label'=>'Manage Users',          'color'=>'#ec4899'],
                ];
                foreach($actions as $action): ?>
                <a href="<?=$action['href']?>" class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none quick-action-btn"
                   style="transition:background .15s;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:<?=$action['color']?>1a;">
                        <i class="bi <?=$action['icon']?>" style="color:<?=$action['color']?>;font-size:16px;"></i>
                    </div>
                    <span class="fw-semibold text-dark small"><?=$action['label']?></span>
                    <i class="bi bi-chevron-right text-muted ms-auto small"></i>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.1) !important; }
.quick-action-btn:hover { background:#f8fafc; }
</style>
