<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark">My Results</h4>
    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2"><?= count($results) ?> exam(s) taken</span>
</div>

<?php if (empty($results)): ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-clipboard-data fs-1 text-muted mb-3 d-block opacity-50"></i>
        <h5 class="text-muted fw-bold">No results yet.</h5>
        <p class="text-muted small mb-3">Your scores will appear here after you submit an exam.</p>
        <a href="/student/dashboard" class="btn btn-primary fw-bold px-4">Browse Exams</a>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Exam</th>
                        <th>Score</th>
                        <th>Correct</th>
                        <th>Result</th>
                        <th>Integrity</th>
                        <th>Submitted</th>
                        <th class="text-end pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $r):
                        $passed = (float)$r['score'] >= (float)$r['passing_marks'];
                        $fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');
                    ?>
                    <tr>
                        <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($r['exam_title']) ?></td>
                        <td class="fw-bold <?= $passed ? 'text-success' : 'text-danger' ?>"><?= $fmt($r['score']) ?> / <?= $fmt($r['total_marks']) ?></td>
                        <td class="text-muted"><?= (int)$r['correct_count'] ?> of <?= (int)$r['total_questions'] ?></td>
                        <td>
                            <?php if ($passed): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="bi bi-trophy-fill me-1"></i>Pass</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="bi bi-x-circle-fill me-1"></i>Fail</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$r['tab_switches'] > 0): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= (int)$r['tab_switches'] ?> warning(s)</span>
                            <?php else: ?>
                                <span class="badge bg-light text-success border"><i class="bi bi-shield-check me-1"></i>Clean</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= $r['end_time'] ? date('d M Y, h:i A', strtotime($r['end_time'])) : '—' ?></td>
                        <td class="text-end pe-4">
                            <a href="/student/exam/result?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary fw-semibold">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
