<?php
$isPassed = $result['score'] >= $result['passing_marks'];
$totalAnswered = $result['correct_answers'] + $result['incorrect_answers'];
$accuracy = ($totalAnswered > 0) ? round(($result['correct_answers'] / $totalAnswered) * 100) : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h4 class="fw-bold m-0 text-dark">Result Summary</h4>
    <a href="/student/dashboard" class="btn btn-outline-primary fw-bold px-4 rounded-pill">Back to Dashboard</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Score Section -->
                    <div class="col-md-5 <?= $isPassed ? 'bg-success' : 'bg-danger' ?> bg-opacity-10 d-flex flex-column justify-content-center align-items-center p-5 text-center border-end border-white">
                        <div class="mb-4 mt-3">
                            <?php if($isPassed): ?>
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 100px; height: 100px;">
                                    <i class="bi bi-trophy-fill" style="font-size: 3rem;"></i>
                                </div>
                                <h2 class="fw-bold text-success mt-2">Congratulations!</h2>
                                <p class="text-success opacity-75 fw-bold mb-0">You successfully passed the exam.</p>
                            <?php else: ?>
                                <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 100px; height: 100px;">
                                    <i class="bi bi-x-circle-fill" style="font-size: 3.5rem;"></i>
                                </div>
                                <h2 class="fw-bold text-danger mt-2">Needs Improvement</h2>
                                <p class="text-danger opacity-75 fw-bold mb-0">You didn't meet the passing criteria.</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-white px-5 py-3 rounded-pill shadow-sm mb-3">
                            <h1 class="display-3 fw-bold mb-0 <?= $isPassed ? 'text-success' : 'text-danger' ?>" style="line-height:1;">
                                <?= rtrim(rtrim($result['score'], '0'), '.') ?>
                            </h1>
                            <small class="text-muted fw-bold text-uppercase opacity-75">Out of <?= rtrim(rtrim($result['total_marks'], '0'), '.') ?></small>
                        </div>
                    </div>
                    
                    <!-- Details Section -->
                    <div class="col-md-7 p-5 bg-white">
                        <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom"><?= htmlspecialchars($result['title']) ?></h4>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 border shadow-sm">
                                    <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.75rem;">Passing Marks</small>
                                    <h3 class="fw-bold m-0 text-dark"><?= rtrim(rtrim($result['passing_marks'], '0'), '.') ?></h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 border shadow-sm">
                                    <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.75rem;">Accuracy</small>
                                    <h3 class="fw-bold m-0 text-dark"><?= $accuracy ?>%</h3>
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <div class="text-center p-3 border rounded-4 border-success bg-success bg-opacity-10 shadow-sm">
                                    <h3 class="fw-bold text-success m-0"><?= $result['correct_answers'] ?></h3>
                                    <small class="text-success fw-bold text-uppercase" style="font-size: 0.7rem;">Correct</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-3 border rounded-4 border-danger bg-danger bg-opacity-10 shadow-sm">
                                    <h3 class="fw-bold text-danger m-0"><?= $result['incorrect_answers'] ?></h3>
                                    <small class="text-danger fw-bold text-uppercase" style="font-size: 0.7rem;">Incorrect</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <?php $skipped = $result['total_questions'] - $totalAnswered; ?>
                                <div class="text-center p-3 border rounded-4 border-secondary bg-light shadow-sm">
                                    <h3 class="fw-bold text-secondary m-0"><?= $skipped ?></h3>
                                    <small class="text-secondary fw-bold text-uppercase" style="font-size: 0.7rem;">Skipped</small>
                                </div>
                            </div>
                        </div>

                        <?php if($result['tab_switches'] > 0): ?>
                            <div class="alert alert-warning border-warning m-0 rounded-4 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill fs-2 me-3 text-warning"></i> 
                                    <div>
                                        <strong>Proctoring Notice:</strong><br>
                                        We recorded <span class="fw-bold text-danger"><?= $result['tab_switches'] ?></span> tab-switch warnings during your exam session.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
