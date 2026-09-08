<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark">Edit Question</h4>
    <a href="/admin/questions?exam_id=<?= $question['exam_id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Questions</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-pencil-square"></i> Edit MCQ</h6>
            </div>
            <div class="card-body bg-light">
                <form action="/admin/questions/update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $question['id'] ?>">
                    <input type="hidden" name="exam_id" value="<?= $question['exam_id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Question Text</label>
                        <textarea name="question_text" class="form-control" rows="3" required><?= htmlspecialchars($question['question_text']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-bold">Marks</label>
                            <input type="number" step="0.01" name="marks" class="form-control" value="<?= $question['marks'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-muted small fw-bold">Reference Image (Optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <?php if (!empty($question['image_url'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Current Image:</small>
                                    <img src="<?= htmlspecialchars($question['image_url']) ?>" alt="Current" class="img-thumbnail" style="max-height: 80px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <label class="form-label text-muted small fw-bold mb-2">Options (Select the correct one)</label>
                    <div class="mb-2 p-3 bg-white rounded border border-light shadow-sm" id="edit-options-container">
                        <?php 
                        $opts = $question['options'] ?? [];
                        while (count($opts) < 2) {
                            $opts[] = ['text' => '', 'is_correct' => 0];
                        }
                        foreach($opts as $i => $opt) {
                            $checked = $opt['is_correct'] ? 'checked' : '';
                        ?>
                            <div class="input-group mb-2 option-row">
                                <div class="input-group-text bg-white border-end-0">
                                    <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="<?= $i ?>" required <?= $checked ?>>
                                </div>
                                <input type="text" name="options[]" class="form-control" placeholder="Option" value="<?= htmlspecialchars($opt['text'] ?? $opt['option_text'] ?? '') ?>" required>
                                <button type="button" class="btn btn-outline-danger remove-opt-btn" <?= count($opts) <= 2 ? 'disabled' : '' ?>><i class="bi bi-x"></i></button>
                            </div>
                        <?php } ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mb-4 w-100 border-dashed" id="add-edit-option-btn"><i class="bi bi-plus-circle"></i> Add Another Option</button>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Update Question</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const optionsContainer = document.getElementById('edit-options-container');
    const addOptionBtn = document.getElementById('add-edit-option-btn');

    function updateOptions() {
        const rows = optionsContainer.querySelectorAll('.option-row');
        rows.forEach((row, index) => {
            row.querySelector('.correct-radio').value = index;
            const removeBtn = row.querySelector('.remove-opt-btn');
            if (rows.length <= 2) {
                removeBtn.disabled = true;
            } else {
                removeBtn.disabled = false;
            }
        });
    }

    addOptionBtn.addEventListener('click', function() {
        const rows = optionsContainer.querySelectorAll('.option-row');
        const index = rows.length;
        const div = document.createElement('div');
        div.className = 'input-group mb-2 option-row';
        div.innerHTML = `
            <div class="input-group-text bg-white border-end-0">
                <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="${index}" required>
            </div>
            <input type="text" name="options[]" class="form-control" placeholder="Option" required>
            <button type="button" class="btn btn-outline-danger remove-opt-btn"><i class="bi bi-x"></i></button>
        `;
        optionsContainer.appendChild(div);
        updateOptions();
    });

    optionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-opt-btn')) {
            const row = e.target.closest('.option-row');
            row.remove();
            updateOptions();
        }
    });
});
</script>
