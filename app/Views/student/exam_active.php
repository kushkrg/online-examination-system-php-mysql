<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Active Exam - <?= htmlspecialchars($exam['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body, html { height: 100%; overflow: hidden; background-color: #f3f4f6; font-family: 'Inter', sans-serif; user-select: none; }
        .exam-header { background-color: #fff; padding: 15px 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; z-index: 10; position: relative; }
        
        .timer-box { background: #4F46E5; color: white; padding: 8px 20px; border-radius: 8px; font-weight: bold; font-size: 1.2rem; display: flex; align-items: center; }
        .timer-warning { background: #ef4444; animation: pulse 1s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        
        .main-container { display: flex; height: calc(100vh - 75px); }
        
        .question-area { flex: 1; padding: 40px; display: flex; flex-direction: column; overflow-y: auto; }
        .question-card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 20px; flex: 1; }
        
        .option-label { display: block; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 1.05rem; margin-bottom: 15px; }
        .option-label:hover { border-color: #a5b4fc; background: #eef2ff; }
        .form-check-input:checked + .option-label { border-color: #4F46E5; background: #eef2ff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .form-check-input { display: none; } 
        
        .palette-area { width: 280px; background: #fff; border-left: 1px solid #e5e7eb; padding: 18px; display: flex; flex-direction: column; }
        
        .palette-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; align-content: start; overflow-y: auto; padding-right: 4px; }
        .palette-btn { width: 100%; aspect-ratio: 1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 600; border: 1px solid #e2e8f0; background: #f8fafc; color: #475569; cursor: pointer; transition: all 0.15s ease-in-out; }
        .palette-btn:hover { background: #eef2ff; border-color: #a5b4fc; color: #4338ca; transform: translateY(-1px); }
        .palette-btn.answered { background: #10b981; color: #ffffff; border-color: #059669; }
        .palette-btn.answered:hover { background: #059669; border-color: #047857; color: #ffffff; }
        .palette-btn.active { border-color: #4F46E5; background: #eef2ff; color: #4F46E5; box-shadow: 0 0 0 2.5px rgba(79, 70, 229, 0.25); font-weight: 700; }
        .palette-btn.answered.active { background: #10b981; border-color: #047857; color: #ffffff; box-shadow: 0 0 0 2.5px rgba(16, 185, 129, 0.35); }
        .palette-indicator { width: 14px; height: 14px; border-radius: 4px; display: inline-block; flex-shrink: 0; }
        .palette-indicator.active-indicator { border: 2px solid #4F46E5; background: #eef2ff; }
        
        .nav-buttons { padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 15px; }
    </style>
</head>
<body>

    <div class="exam-header">
        <div>
            <h5 class="fw-bold m-0 text-dark"><?= htmlspecialchars($exam['title']) ?></h5>
            <small class="text-muted">Candidate: <?= htmlspecialchars($_SESSION['user_name']) ?></small>
        </div>
        <div class="timer-box" id="timerDisplay">
            <i class="bi bi-clock-history me-2"></i> <span id="timeText">--:--:--</span>
        </div>
        <button onclick="submitExam()" class="btn btn-danger fw-bold px-4">Submit Exam</button>
    </div>

    <div class="main-container">
        <!-- Question Area -->
        <div class="question-area">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-card" id="q-section-<?= $index ?>" style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="fw-bold text-primary">Question <?= $index + 1 ?> of <?= count($questions) ?></h5>
                        <span class="badge bg-light text-dark border"><?= $q['marks'] ?> Marks</span>
                    </div>
                    
                    <h4 class="text-dark mb-4" style="line-height: 1.5;"><?= nl2br(htmlspecialchars($q['question_text'])) ?></h4>
                    
                    <?php if (!empty($q['image_url'])): ?>
                        <div class="mb-4 text-center">
                            <img src="<?= htmlspecialchars($q['image_url']) ?>" alt="Question Image" class="img-fluid rounded border shadow-sm" style="max-height: 300px;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="options-container mt-4">
                        <?php foreach ($q['options'] as $opt): 
                            $isChecked = (isset($savedAnswers[$q['id']]) && $savedAnswers[$q['id']] == $opt['id']) ? 'checked' : '';
                        ?>
                            <div>
                                <input class="form-check-input opt-radio" type="radio" name="q_<?= $q['id'] ?>" id="opt_<?= $opt['id'] ?>" value="<?= $opt['id'] ?>" data-qid="<?= $q['id'] ?>" <?= $isChecked ?>>
                                <label class="option-label" for="opt_<?= $opt['id'] ?>">
                                    <span class="fw-bold me-2 text-muted"><?= chr(65 + array_search($opt, $q['options'])) ?>.</span>
                                    <?= htmlspecialchars($opt['option_text']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="nav-buttons mt-auto">
                <button class="btn btn-outline-secondary px-4 fw-bold py-2" id="btnPrev" disabled><i class="bi bi-chevron-left me-1"></i> Previous</button>
                <span id="saveStatus" class="text-success fw-bold align-self-center" style="opacity: 0; transition: opacity 0.3s;"><i class="bi bi-cloud-check"></i> Saved</span>
                <button class="btn btn-primary px-5 fw-bold py-2" id="btnNext">Next <i class="bi bi-chevron-right ms-1"></i></button>
            </div>
        </div>
        
        <!-- Palette Area -->
        <div class="palette-area">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h6 class="fw-bold text-dark m-0"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>Questions</h6>
                <span class="badge bg-light text-secondary border"><?= count($questions) ?> Total</span>
            </div>
            
            <div class="palette-grid flex-grow-1">
                <?php foreach ($questions as $index => $q): 
                    $isAnswered = isset($savedAnswers[$q['id']]) ? 'answered' : '';
                    $isActive = $index === 0 ? 'active' : '';
                ?>
                    <button class="palette-btn <?= $isAnswered ?> <?= $isActive ?>" data-index="<?= $index ?>" id="pal-<?= $index ?>">
                        <?= $index + 1 ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-auto pt-3 border-top small">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center text-muted">
                        <span class="palette-indicator bg-success me-2"></span> Answered
                    </div>
                    <div class="d-flex align-items-center text-muted">
                        <span class="palette-indicator bg-light border me-2"></span> Not Answered
                    </div>
                </div>
                <div class="d-flex align-items-center text-muted">
                    <span class="palette-indicator active-indicator me-2"></span> Current
                </div>
            </div>
        </div>
    </div>

    <script>
        const totalQuestions = <?= count($questions) ?>;
        let currentIndex = 0;
        let remainingSeconds = <?= $remaining_seconds ?>;
        
        function showQuestion(index) {
            if(index < 0 || index >= totalQuestions) return;
            
            document.querySelectorAll('.question-card').forEach(c => c.style.display = 'none');
            document.getElementById('q-section-' + index).style.display = 'block';
            
            document.querySelectorAll('.palette-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('pal-' + index).classList.add('active');
            
            currentIndex = index;
            
            document.getElementById('btnPrev').disabled = (currentIndex === 0);
            if (currentIndex === totalQuestions - 1) {
                document.getElementById('btnNext').style.display = 'none';
            } else {
                document.getElementById('btnNext').style.display = 'block';
            }
        }

        document.getElementById('btnNext').addEventListener('click', () => showQuestion(currentIndex + 1));
        document.getElementById('btnPrev').addEventListener('click', () => showQuestion(currentIndex - 1));
        
        document.querySelectorAll('.palette-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                showQuestion(parseInt(btn.getAttribute('data-index')));
            });
        });

        document.querySelectorAll('.opt-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const qId = this.getAttribute('data-qid');
                const optId = this.value;
                
                document.getElementById('pal-' + currentIndex).classList.add('answered');
                
                const status = document.getElementById('saveStatus');
                status.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Saving...';
                status.classList.remove('text-success', 'text-danger');
                status.classList.add('text-secondary');
                status.style.opacity = 1;

                fetch('/student/exam/save_answer', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ question_id: qId, option_id: optId })
                })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        status.innerHTML = '<i class="bi bi-cloud-check"></i> Saved';
                        status.classList.remove('text-secondary');
                        status.classList.add('text-success');
                        setTimeout(() => status.style.opacity = 0, 2000);
                    }
                });
            });
        });

        function updateTimer() {
            if (remainingSeconds <= 0) {
                submitExam();
                return;
            }
            
            const h = Math.floor(remainingSeconds / 3600).toString().padStart(2, '0');
            const m = Math.floor((remainingSeconds % 3600) / 60).toString().padStart(2, '0');
            const s = (remainingSeconds % 60).toString().padStart(2, '0');
            
            document.getElementById('timeText').innerText = `${h}:${m}:${s}`;
            
            if (remainingSeconds <= 300) {
                document.getElementById('timerDisplay').classList.add('timer-warning');
            }
            
            remainingSeconds--;
        }
        setInterval(updateTimer, 1000);
        updateTimer();

        function submitExam() {
            if (remainingSeconds > 0) {
                if(!confirm("Are you sure you want to submit the exam? You cannot change answers after this.")) {
                    return;
                }
            }
            window.location.href = '/student/exam/submit';
        }

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                fetch('/student/exam/violation', { method: 'POST' });
                alert("WARNING: You switched tabs! This violation has been recorded.");
            }
        });

        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('copy', e => e.preventDefault());
        document.addEventListener('paste', e => e.preventDefault());
    </script>
</body>
</html>
