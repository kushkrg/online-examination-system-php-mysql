<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark">Available Exams</h4>
</div>

<div class="row">
    <?php if (empty($exams)): ?>
        <div class="col-12 text-center py-5 bg-white rounded shadow-sm border-0">
            <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block opacity-50"></i>
            <h5 class="text-muted fw-bold">No exams available at the moment.</h5>
            <p class="text-muted small">Please check back later when new exams are published.</p>
        </div>
    <?php else: ?>
        <?php foreach ($exams as $exam): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-elevate">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold text-dark m-0 pe-2"><?= htmlspecialchars($exam['title']) ?></h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 whitespace-nowrap">
                                <?= $exam['duration_minutes'] ?> Mins
                            </span>
                        </div>
                        <p class="card-text text-muted small line-clamp-2" style="min-height: 40px;">
                            <?= htmlspecialchars($exam['description'] ?? 'No description provided.') ?>
                        </p>
                        
                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 11px; text-transform: uppercase;">Pass / Total</small>
                                <strong class="text-dark fs-5"><?= rtrim(rtrim($exam['passing_marks'], '0'), '.') ?>/<span class="fs-6 text-muted"><?= rtrim(rtrim($exam['total_marks'], '0'), '.') ?></span></strong>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block fw-bold" style="font-size: 11px; text-transform: uppercase;">Start Date</small>
                                <strong class="text-dark"><?= date('M d', strtotime($exam['start_time'])) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 pt-0 mt-2">
                        <a href="/student/exam/instructions?exam_id=<?= $exam['id'] ?>" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-3">Attempt Exam</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
