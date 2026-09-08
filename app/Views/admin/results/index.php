<?php
$passRate = ($stats['total_attempts'] > 0)
    ? round(($stats['total_pass'] / $stats['total_attempts']) * 100, 1) : 0;
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #6366f1 !important;">
            <div class="fw-bold fs-2 text-primary"><?= $stats['total_attempts'] ?? 0 ?></div>
            <div class="text-muted small">Total Submissions</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #22c55e !important;">
            <div class="fw-bold fs-2 text-success"><?= $stats['total_pass'] ?? 0 ?></div>
            <div class="text-muted small">Passed</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #ef4444 !important;">
            <div class="fw-bold fs-2 text-danger"><?= $stats['total_fail'] ?? 0 ?></div>
            <div class="text-muted small">Failed</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #f59e0b !important;">
            <div class="fw-bold fs-2 text-warning"><?= $passRate ?>%</div>
            <div class="text-muted small">Pass Rate</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #06b6d4 !important;">
            <div class="fw-bold fs-2 text-info"><?= $stats['avg_score'] ?? '—' ?></div>
            <div class="text-muted small">Avg Score</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center py-3 px-2" style="border-top:3px solid #ec4899 !important;">
            <div class="fw-bold fs-2" style="color:#ec4899;"><?= $stats['cheating_flags'] ?? 0 ?></div>
            <div class="text-muted small">Integrity Flags</div>
        </div>
    </div>
</div>

<!-- Charts + Filters Row -->
<div class="row g-4 mb-4">
    <!-- Pass/Fail Donut -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark m-0">Pass / Fail Distribution</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="passFailChart" height="200"></canvas>
                <div class="d-flex gap-4 mt-3">
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:50%;background:#22c55e;display:inline-block;"></span><small class="text-muted">Pass (<?= $stats['total_pass'] ?? 0 ?>)</small></div>
                    <div class="d-flex align-items-center gap-2"><span style="width:12px;height:12px;border-radius:50%;background:#ef4444;display:inline-block;"></span><small class="text-muted">Fail (<?= $stats['total_fail'] ?? 0 ?>)</small></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Distribution Bar Chart -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark m-0">Score Distribution <small class="text-muted fw-normal">(all submitted exams)</small></h6>
            </div>
            <div class="card-body">
                <canvas id="scoreDistChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filters + Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-graph-up text-primary me-2"></i>Result Records <span class="badge bg-primary rounded-pill ms-1"><?= count($results) ?></span></h6>
            </div>
            <div class="col-md-8">
                <form method="GET" action="/admin/results" class="d-flex flex-wrap gap-2 justify-content-end" id="filter-form">
                    <select name="exam_id" class="form-select form-select-sm" style="width:auto;" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All Exams</option>
                        <?php foreach ($exams as $ex): ?>
                            <option value="<?= $ex['id'] ?>" <?= ($_GET['exam_id'] ?? '') == $ex['id'] ? 'selected' : '' ?>><?= htmlspecialchars($ex['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="result" class="form-select form-select-sm" style="width:auto;" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All Results</option>
                        <option value="pass" <?= ($_GET['result'] ?? '') === 'pass' ? 'selected' : '' ?>>Pass Only</option>
                        <option value="fail" <?= ($_GET['result'] ?? '') === 'fail' ? 'selected' : '' ?>>Fail Only</option>
                    </select>
                    <div class="input-group input-group-sm" style="width:200px;">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Student name or email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <?php if (!empty($_GET['search']) || !empty($_GET['exam_id']) || !empty($_GET['result'])): ?>
                        <a href="/admin/results" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Student</th>
                        <th>Exam</th>
                        <th>Score</th>
                        <th>Performance</th>
                        <th>Result</th>
                        <th>Integrity</th>
                        <th>Submitted</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-file-earmark-bar-graph fs-1 d-block mb-2 opacity-50"></i>
                                No results found matching your filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($results as $i => $res):
                            $isPassed  = $res['score'] >= $res['passing_marks'];
                            $pct       = $res['total_marks'] > 0 ? round(($res['score'] / $res['total_marks']) * 100) : 0;
                            $skipped   = max(0, $res['total_questions'] - $res['correct_count'] - $res['wrong_count']);
                        ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                             style="width:34px;height:34px;font-size:13px;background:hsl(<?= (crc32($res['student_name']) % 360 + 360) % 360 ?>,60%,50%);">
                                            <?= strtoupper(substr($res['student_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark small"><?= htmlspecialchars($res['student_name']) ?></div>
                                            <div class="text-muted" style="font-size:11px;"><?= htmlspecialchars($res['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-semibold small text-dark"><?= htmlspecialchars($res['exam_title']) ?></span></td>
                                <td>
                                    <span class="fw-bold <?= $isPassed ? 'text-success' : 'text-danger' ?>">
                                        <?= rtrim(rtrim($res['score'], '0'), '.') ?> / <?= rtrim(rtrim($res['total_marks'], '0'), '.') ?>
                                    </span>
                                </td>
                                <td style="min-width:130px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:6px;">
                                            <div class="progress-bar <?= $isPassed ? 'bg-success' : 'bg-danger' ?>" style="width:<?= $pct ?>%;"></div>
                                        </div>
                                        <small class="text-muted"><?= $pct ?>%</small>
                                    </div>
                                    <div class="mt-1 d-flex gap-2" style="font-size:11px;">
                                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> <?= $res['correct_count'] ?></span>
                                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> <?= $res['wrong_count'] ?></span>
                                        <span class="text-muted"><i class="bi bi-dash-circle-fill"></i> <?= $skipped ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($isPassed): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-trophy-fill me-1"></i>Pass</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-circle-fill me-1"></i>Fail</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($res['tab_switches'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= $res['tab_switches'] ?> flag(s)</span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-shield-check me-1"></i>Clean</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= $res['end_time'] ? date('d M Y, h:i A', strtotime($res['end_time'])) : '—' ?></td>
                                <td class="pe-4 text-end">
                                    <button class="btn btn-sm btn-outline-primary py-1 px-2 review-btn"
                                            data-id="<?= $res['id'] ?>"
                                            data-name="<?= htmlspecialchars($res['student_name']) ?>"
                                            data-exam="<?= htmlspecialchars($res['exam_title']) ?>"
                                            data-score="<?= rtrim(rtrim($res['score'],'0'),'.') ?>"
                                            data-total="<?= rtrim(rtrim($res['total_marks'],'0'),'.') ?>"
                                            data-pass="<?= $isPassed ? '1' : '0' ?>"
                                            title="Review Answers">
                                        <i class="bi bi-eye"></i> Review
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Answer Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3" id="review-modal-header">
                <div>
                    <h5 class="modal-title text-white fw-bold mb-0" id="review-modal-title"></h5>
                    <small class="text-white opacity-75" id="review-modal-sub"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="review-modal-body">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ─── Pass / Fail Donut ───────────────────────────────────────────────────────
const passFail = new Chart(document.getElementById('passFailChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pass', 'Fail'],
        datasets: [{
            data: [<?= $stats['total_pass'] ?? 0 ?>, <?= $stats['total_fail'] ?? 0 ?>],
            backgroundColor: ['#22c55e', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 6
        }]
    },
    options: {
        cutout: '70%',
        plugins: { legend: { display: false } },
        responsive: true
    }
});

// ─── Score Distribution Bar ──────────────────────────────────────────────────
const scores = <?= json_encode(array_column($results, 'score')) ?>;
const totals  = <?= json_encode(array_column($results, 'total_marks')) ?>;
const buckets = {'0–20%':0,'21–40%':0,'41–60%':0,'61–80%':0,'81–100%':0};
scores.forEach((s, i) => {
    const pct = totals[i] > 0 ? (s / totals[i]) * 100 : 0;
    if      (pct <= 20) buckets['0–20%']++;
    else if (pct <= 40) buckets['21–40%']++;
    else if (pct <= 60) buckets['41–60%']++;
    else if (pct <= 80) buckets['61–80%']++;
    else                buckets['81–100%']++;
});
new Chart(document.getElementById('scoreDistChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(buckets),
        datasets: [{
            label: 'Students',
            data: Object.values(buckets),
            backgroundColor: ['#ef4444','#f59e0b','#6366f1','#06b6d4','#22c55e'],
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        },
        responsive: true
    }
});

// ─── Answer Review Modal ─────────────────────────────────────────────────────
document.querySelectorAll('.review-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id     = this.dataset.id;
        const name   = this.dataset.name;
        const exam   = this.dataset.exam;
        const score  = this.dataset.score;
        const total  = this.dataset.total;
        const passed = this.dataset.pass === '1';

        const header = document.getElementById('review-modal-header');
        header.style.background = passed
            ? 'linear-gradient(135deg,#16a34a,#22c55e)'
            : 'linear-gradient(135deg,#b91c1c,#ef4444)';
        document.getElementById('review-modal-title').textContent = name + ' — Answer Review';
        document.getElementById('review-modal-sub').textContent   = exam + ' | Score: ' + score + ' / ' + total + ' | ' + (passed ? '🏆 PASSED' : '✗ FAILED');
        document.getElementById('review-modal-body').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

        new bootstrap.Modal(document.getElementById('reviewModal')).show();

        fetch('/admin/results/attempt-detail?id=' + id)
            .then(r => r.json())
            .then(questions => {
                if (!questions || !questions.length) {
                    document.getElementById('review-modal-body').innerHTML = '<p class="text-muted text-center py-4">No answer data available.</p>';
                    return;
                }
                let html = '';
                questions.forEach((q, idx) => {
                    const correct   = q.is_correct == 1;
                    const skipped   = !q.selected_text;
                    const cardColor = skipped ? '#f8f9fa' : (correct ? 'rgba(34,197,94,.06)' : 'rgba(239,68,68,.06)');
                    const borderCol = skipped ? '#dee2e6' : (correct ? '#22c55e' : '#ef4444');
                    const icon      = skipped ? '⚪' : (correct ? '✅' : '❌');
                    const marksText = skipped ? '<span class="text-muted">Skipped</span>'
                                   : (correct ? `<span class="text-success fw-bold">+${q.full_marks} marks</span>`
                                              : `<span class="text-danger fw-bold">${q.marks_obtained} marks</span>`);

                    html += `
                        <div class="mb-3 p-3 rounded-3 border" style="background:${cardColor};border-color:${borderCol} !important;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <p class="fw-semibold mb-0 me-3" style="line-height:1.5;">${icon} <span class="text-primary">Q${idx+1}.</span> ${q.question_text}</p>
                                ${marksText}
                            </div>
                            ${q.image_url ? `<img src="${q.image_url}" class="img-fluid rounded mb-2 border" style="max-height:150px;">` : ''}
                            <div class="row g-2 mt-1">
                                <div class="col-md-6">
                                    <div class="p-2 rounded border ${skipped ? 'bg-light text-muted' : (correct ? 'bg-success text-white border-success' : 'bg-danger text-white border-danger')}" style="font-size:13px;">
                                        <strong>Student's Answer:</strong> ${q.selected_text || '<em>Not Answered</em>'}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 rounded border bg-success bg-opacity-10 text-success border-success" style="font-size:13px;">
                                        <strong>Correct Answer:</strong> ${q.correct_text || '—'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('review-modal-body').innerHTML = html;
            });
    });
});
</script>
