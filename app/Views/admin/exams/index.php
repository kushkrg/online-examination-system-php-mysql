<?php
$statusMeta = [
    'published' => ['label'=>'Published','color'=>'#22c55e','bg'=>'rgba(34,197,94,.12)','icon'=>'bi-broadcast-pin'],
    'draft'     => ['label'=>'Draft',    'color'=>'#94a3b8','bg'=>'rgba(148,163,184,.15)','icon'=>'bi-pencil-fill'],
    'completed' => ['label'=>'Completed','color'=>'#6366f1','bg'=>'rgba(99,102,241,.12)', 'icon'=>'bi-patch-check-fill'],
];
?>

<?php if (isset($_GET['success'])): ?>
<?php $msgs=['created'=>'Exam created!','updated'=>'Exam updated!','deleted'=>'Exam deleted!']; ?>
<div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
    <i class="bi bi-check-circle-fill me-2"></i><?= $msgs[$_GET['success']] ?? 'Done!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <?php $cards = [
        ['label'=>'Total Exams',        'value'=>$stats['total']??0,             'color'=>'#6366f1','icon'=>'bi-card-checklist'],
        ['label'=>'Published',          'value'=>$stats['published']??0,         'color'=>'#22c55e','icon'=>'bi-broadcast-pin'],
        ['label'=>'Drafts',             'value'=>$stats['draft']??0,             'color'=>'#94a3b8','icon'=>'bi-pencil-fill'],
        ['label'=>'Completed',          'value'=>$stats['completed']??0,         'color'=>'#8b5cf6','icon'=>'bi-patch-check-fill'],
        ['label'=>'Total Submissions',  'value'=>$stats['total_submissions']??0, 'color'=>'#f59e0b','icon'=>'bi-send-check-fill'],
    ];
    foreach($cards as $card): ?>
    <div class="col-sm-6 col-xl">
        <div class="card border-0 shadow-sm h-100" style="border-top:3px solid <?=$card['color']?> !important;">
            <div class="card-body py-3 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                     style="width:44px;height:44px;background:<?=$card['color']?>1a;">
                    <i class="bi <?=$card['icon']?> fs-5" style="color:<?=$card['color']?>;"></i>
                </div>
                <div class="fw-bold fs-2 lh-1"><?=$card['value']?></div>
                <div class="text-muted small mt-1"><?=$card['label']?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter + Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-card-checklist text-primary me-2"></i>All Exams <span class="badge bg-primary rounded-pill ms-1"><?= count($exams) ?></span></h6>
            </div>
            <div class="col-md-8">
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <form method="GET" action="/admin/exams" class="d-flex flex-wrap gap-2" id="filter-form">
                        <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="document.getElementById('filter-form').submit()">
                            <option value="">All Status</option>
                            <option value="published" <?= ($_GET['status']??'')==='published'?'selected':'' ?>>Published</option>
                            <option value="draft"     <?= ($_GET['status']??'')==='draft'    ?'selected':'' ?>>Draft</option>
                            <option value="completed" <?= ($_GET['status']??'')==='completed'?'selected':'' ?>>Completed</option>
                        </select>
                        <div class="input-group input-group-sm" style="width:210px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search exams..." value="<?= htmlspecialchars($_GET['search']??'') ?>">
                        </div>
                        <?php if(!empty($_GET['search'])||!empty($_GET['status'])): ?>
                            <a href="/admin/exams" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                        <?php endif; ?>
                    </form>
                    <a href="/admin/exams/create" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i>New Exam</a>
                </div>
            </div>
        </div>
    </div>

    <?php if(empty($exams)): ?>
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-card-checklist fs-1 d-block mb-3 opacity-50"></i>
        <p class="mb-3">No exams found.</p>
        <a href="/admin/exams/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Create First Exam</a>
    </div>
    <?php else: ?>

    <!-- Exam Cards Grid -->
    <div class="card-body p-4">
        <div class="row g-4">
            <?php foreach($exams as $exam):
                $sm = $statusMeta[$exam['status']] ?? $statusMeta['draft'];
                $passRate = $exam['attempt_count'] > 0
                    ? round(($exam['pass_count'] / $exam['attempt_count']) * 100) : null;
                $now = new DateTime();
                $start = new DateTime($exam['start_time']);
                $end   = new DateTime($exam['end_time']);
                $isLive = $now >= $start && $now <= $end && $exam['status'] === 'published';
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100 exam-card" style="border-top:3px solid <?=$sm['color']?> !important;transition:transform .2s,box-shadow .2s;">
                    <div class="card-body p-4">
                        <!-- Header Row -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1 me-2">
                                <h6 class="fw-bold text-dark mb-1 lh-sm"><?= htmlspecialchars($exam['title']) ?></h6>
                                <?php if($isLive): ?>
                                    <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25 d-inline-flex align-items-center gap-1">
                                        <span class="d-inline-block rounded-circle bg-success" style="width:6px;height:6px;animation:pulse 1s infinite;"></span>LIVE
                                    </span>
                                <?php else: ?>
                                    <span class="badge px-2 py-1" style="background:<?=$sm['bg']?>;color:<?=$sm['color']?>;border:1px solid <?=$sm['color']?>33;">
                                        <i class="bi <?=$sm['icon']?> me-1"></i><?=$sm['label']?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold fs-5 text-primary"><?= rtrim(rtrim($exam['total_marks'],'0'),'.') ?></div>
                                <div class="text-muted" style="font-size:11px;">Total Marks</div>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if(!empty($exam['description'])): ?>
                        <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= htmlspecialchars($exam['description']) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Mini Stats Row -->
                        <div class="row g-2 mb-3">
                            <div class="col-4 text-center">
                                <div class="bg-light rounded py-2">
                                    <div class="fw-bold text-primary"><?= $exam['question_count'] ?? 0 ?></div>
                                    <div class="text-muted" style="font-size:10px;">Questions</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="bg-light rounded py-2">
                                    <div class="fw-bold text-info"><?= $exam['attempt_count'] ?? 0 ?></div>
                                    <div class="text-muted" style="font-size:10px;">Attempts</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="bg-light rounded py-2">
                                    <div class="fw-bold <?= $passRate !== null ? ($passRate >= 50 ? 'text-success' : 'text-danger') : 'text-muted' ?>">
                                        <?= $passRate !== null ? $passRate.'%' : '—' ?>
                                    </div>
                                    <div class="text-muted" style="font-size:10px;">Pass Rate</div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Info -->
                        <div class="d-flex align-items-center gap-2 text-muted small mb-3">
                            <i class="bi bi-clock"></i>
                            <span><?= $exam['duration_minutes'] ?> mins</span>
                            <span class="mx-1">•</span>
                            <i class="bi bi-calendar-event"></i>
                            <span><?= date('d M Y', strtotime($exam['start_time'])) ?></span>
                        </div>

                        <!-- Negative Marking badge -->
                        <?php if($exam['negative_marking_ratio'] > 0): ?>
                        <div class="mb-3">
                            <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-25 small">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Negative Marking: -<?=$exam['negative_marking_ratio']?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Footer -->
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/admin/questions?exam_id=<?=$exam['id']?>" class="btn btn-sm btn-primary flex-grow-1 shadow-sm">
                                <i class="bi bi-list-check me-1"></i>Questions
                            </a>
                            <a href="/admin/exams/edit?id=<?=$exam['id']?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn btn-sm btn-outline-danger delete-exam-btn"
                                    data-id="<?=$exam['id']?>" data-title="<?=htmlspecialchars($exam['title'])?>" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.exam-card:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,.1) !important; }
@keyframes pulse { 0%,100%{opacity:1}50%{opacity:.4} }
</style>

<script>
document.querySelectorAll('.delete-exam-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id, title = this.dataset.title;
        if (!confirm(`Delete exam "${title}"?\n\nThis will also permanently delete ALL questions, attempts, and results associated with it.`)) return;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        this.disabled = true;
        window.location.href = '/admin/exams/delete?id=' + id;
    });
});
</script>
