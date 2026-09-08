<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Instructions - <?= htmlspecialchars($exam['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Strict Single Viewport Constraints */
        body, html { height: 100%; overflow: hidden; background-color: #f3f4f6; font-family: 'Inter', sans-serif; }
        .wrapper { display: flex; align-items: center; justify-content: center; height: 100vh; padding: 20px; }
        .instruction-box { width: 100%; max-width: 800px; background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; flex-direction: column; max-height: 90vh; }
        .box-header { padding: 25px; border-bottom: 1px solid #eee; text-align: center; }
        .box-body { padding: 30px; overflow-y: auto; flex-grow: 1; }
        .box-footer { padding: 20px 30px; border-top: 1px solid #eee; background: #fafafa; border-radius: 0 0 16px 16px; }
        
        .instruction-list li { margin-bottom: 15px; color: #4b5563; font-size: 1.05rem; }
        
        .fade-in { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="instruction-box">
            
            <!-- Step 1: Instructions -->
            <div id="instructions-section" class="d-flex flex-column h-100">
                <div class="box-header bg-primary text-white" style="border-radius: 16px 16px 0 0;">
                    <h4 class="fw-bold m-0"><i class="bi bi-info-circle-fill me-2"></i>Exam Instructions</h4>
                    <p class="mb-0 mt-1 opacity-75 fw-bold"><?= htmlspecialchars($exam['title']) ?></p>
                </div>
                
                <div class="box-body">
                    <h5 class="fw-bold text-dark mb-4">Please read the following instructions carefully:</h5>
                    <ul class="instruction-list ps-3 mb-0">
                        <li>The exam duration is <strong><?= $exam['duration_minutes'] ?> minutes</strong>. The timer will start immediately after you begin.</li>
                        <li>This exam consists of multiple-choice questions. Total marks: <strong><?= rtrim(rtrim($exam['total_marks'], '0'), '.') ?></strong>.</li>
                        <?php if($exam['negative_marking_ratio'] > 0): ?>
                            <li class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Negative marking is active. <?= $exam['negative_marking_ratio'] ?> marks will be deducted for each incorrect answer.</li>
                        <?php endif; ?>
                        <li><strong>Proctoring is active:</strong> You are not allowed to switch browser tabs, exit full-screen, or copy-paste text. Doing so will result in an automatic warning and potential auto-submission of the exam.</li>
                        <li>Ensure you have a stable internet connection. If disconnected, your answers are auto-saved.</li>
                    </ul>
                    
                    <div class="alert alert-warning mt-4 mb-0 border-warning bg-warning bg-opacity-10 d-flex align-items-center rounded-3">
                        <div class="form-check m-0 w-100">
                            <input class="form-check-input mt-1" type="checkbox" id="agreeCheck" style="transform: scale(1.3);">
                            <label class="form-check-label fw-bold ms-2 text-dark user-select-none cursor-pointer w-100" for="agreeCheck" style="cursor: pointer;">
                                I have read and understood the instructions. I agree to abide by the rules.
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="box-footer text-end mt-auto">
                    <a href="/student/dashboard" class="btn btn-light fw-bold px-4 me-2 border">Cancel</a>
                    <button id="btnProceed" class="btn btn-primary fw-bold px-5" disabled>Proceed <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
            </div>

            <!-- Step 2: Registration (Revealed After) -->
            <div id="registration-section" class="d-flex flex-column h-100" style="display: none !important;">
                <div class="box-header bg-success text-white" style="border-radius: 16px 16px 0 0;">
                    <h4 class="fw-bold m-0"><i class="bi bi-person-badge-fill me-2"></i>Confirm Details</h4>
                    <p class="mb-0 mt-1 opacity-75">Almost there! Please verify your information.</p>
                </div>
                
                <div class="box-body d-flex flex-column justify-content-center text-center">
                    <form action="/student/exam/start" method="POST" id="registrationForm">
                        <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                        
                        <div class="mb-4">
                            <div class="bg-light d-inline-block rounded-circle p-4 mb-3 border shadow-sm">
                                <i class="bi bi-person-fill fs-1 text-secondary"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($_SESSION['user_name']) ?></h4>
                            <p class="text-muted mb-0">Student Account</p>
                        </div>
                        
                        <div class="mb-2 px-md-5 mx-md-5">
                            <label class="form-label text-muted small fw-bold text-uppercase">Confirm Your City / Location</label>
                            <input type="text" name="city" class="form-control form-control-lg text-center fw-bold border-2 shadow-sm" placeholder="e.g. New York" required>
                        </div>
                    </form>
                </div>
                
                <div class="box-footer text-center mt-auto p-3">
                    <button id="btnStartExam" type="submit" form="registrationForm" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3" style="font-size: 1.1rem;">
                        Start Exam Now <i class="bi bi-play-circle-fill ms-2"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('agreeCheck').addEventListener('change', function() {
            document.getElementById('btnProceed').disabled = !this.checked;
        });

        document.getElementById('btnProceed').addEventListener('click', function() {
            document.getElementById('instructions-section').style.setProperty('display', 'none', 'important');
            const regSection = document.getElementById('registration-section');
            regSection.style.setProperty('display', 'flex', 'important');
            regSection.classList.add('fade-in');
        });
    </script>
</body>
</html>
