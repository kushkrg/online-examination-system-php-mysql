<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark"><?= htmlspecialchars($exam['title']) ?> - Questions</h4>
    <a href="/admin/exams" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Exams</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <?php 
        $msg = "Question added successfully!";
        if ($_GET['success'] == 'updated') $msg = "Question updated successfully!";
        if ($_GET['success'] == 'deleted') $msg = "Question deleted successfully!";
    ?>
    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i> <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<?php if (isset($_GET['import_msg'])): ?>
    <div class="alert alert-info border-0 shadow-sm alert-dismissible fade show"><i class="bi bi-cloud-upload-fill me-2"></i> <?= htmlspecialchars($_GET['import_msg']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row">
    <!-- Left Column: Add Question + CSV Import -->
    <div class="col-md-5 mb-4">
        <!-- Tabs Nav -->
        <ul class="nav nav-pills mb-3 gap-2" id="left-tabs">
            <li class="nav-item">
                <button class="nav-link active px-3 py-2" data-bs-toggle="pill" data-bs-target="#tab-add">
                    <i class="bi bi-plus-circle me-1"></i>Add Question
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-3 py-2" data-bs-toggle="pill" data-bs-target="#tab-import">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Import CSV
                </button>
            </li>
        </ul>
        <div class="tab-content">

        <!-- Tab: Add Question -->
        <div class="tab-pane fade show active" id="tab-add">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-plus-circle"></i> Add New MCQ</h6>
            </div>
            <div class="card-body bg-light rounded-bottom">
                <form action="/admin/questions/store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Question Text</label>
                        <textarea name="question_text" class="form-control" rows="3" required placeholder="Type your question here..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Marks</label>
                        <input type="number" step="0.01" name="marks" class="form-control" value="1.00" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Reference Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <label class="form-label text-muted small fw-bold mb-2">Options (Select the correct one)</label>
                    <div class="mb-2 p-3 bg-white rounded border border-light shadow-sm" id="options-container">
                        <div class="input-group mb-2 option-row">
                            <div class="input-group-text bg-white border-end-0">
                                <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="0" required checked>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="Option" required>
                            <button type="button" class="btn btn-outline-danger remove-opt-btn" disabled><i class="bi bi-x"></i></button>
                        </div>
                        <div class="input-group mb-2 option-row">
                            <div class="input-group-text bg-white border-end-0">
                                <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="1" required>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="Option" required>
                            <button type="button" class="btn btn-outline-danger remove-opt-btn" disabled><i class="bi bi-x"></i></button>
                        </div>
                        <div class="input-group mb-2 option-row">
                            <div class="input-group-text bg-white border-end-0">
                                <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="2" required>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="Option" required>
                            <button type="button" class="btn btn-outline-danger remove-opt-btn"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="input-group option-row">
                            <div class="input-group-text bg-white border-end-0">
                                <input class="form-check-input mt-0 correct-radio" type="radio" name="correct_option" value="3" required>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="Option" required>
                            <button type="button" class="btn btn-outline-danger remove-opt-btn"><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mb-4 w-100 border-dashed" id="add-option-btn"><i class="bi bi-plus-circle"></i> Add Another Option</button>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Save Question</button>
                </form>
            </div>
        </div>
        </div><!-- /.tab-pane#tab-add -->

        <!-- Tab: Import CSV -->
        <div class="tab-pane fade" id="tab-import">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 fw-bold text-success"><i class="bi bi-file-earmark-spreadsheet"></i> Import Questions via CSV</h6>
            </div>
            <div class="card-body bg-light rounded-bottom">
                <!-- Format Guide -->
                <div class="alert alert-info border-0 mb-3 p-3">
                    <div class="fw-bold small mb-2"><i class="bi bi-info-circle-fill me-1"></i>CSV Format Required:</div>
                    <code class="small d-block bg-white p-2 rounded border">
                        question_text, marks, option_a, option_b, option_c, option_d, correct_option
                    </code>
                    <ul class="small mb-0 mt-2 ps-3">
                        <li><strong>correct_option</strong>: use A, B, C, or D</li>
                        <li>option_c and option_d can be empty (for True/False)</li>
                        <li>First row is the header — it will be skipped</li>
                    </ul>
                </div>

                <form action="/admin/questions/import-csv" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    </div>

                    <!-- Drag & drop visual hint -->
                    <div class="border-2 border-dashed rounded p-3 text-center text-muted small mb-3" style="border:2px dashed #d1d5db;">
                        <i class="bi bi-cloud-upload fs-3 d-block mb-1 text-primary opacity-75"></i>
                        Bulk upload up to hundreds of questions at once
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-cloud-upload-fill me-2"></i>Upload & Import
                    </button>
                </form>

                <hr class="my-3">
                <div class="text-center">
                    <a href="/admin/questions/sample-csv" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download me-1"></i>Download Sample CSV
                    </a>
                    <p class="text-muted small mt-2 mb-0">Use this template to format your questions correctly.</p>
                </div>
            </div>
        </div>
        </div><!-- /.tab-pane#tab-import -->

        </div><!-- /.tab-content -->
    </div>
    
    <!-- Existing Questions List -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark">Existing Questions <span id="total-questions-badge" class="badge bg-primary rounded-pill ms-2">0</span></h6>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-questions" class="form-control border-start-0 ps-0" placeholder="Search questions...">
                </div>
            </div>
            <div class="card-body p-0 d-flex flex-column" style="min-height: 400px;">
                
                <!-- Loading Spinner -->
                <div id="loading-spinner" class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Questions Container -->
                <div id="questions-container" class="flex-grow-1" style="display: none;">
                    <ul class="list-group list-group-flush" id="questions-list"></ul>
                </div>

                <!-- Pagination Controls -->
                <div class="card-footer bg-white border-0 py-3 mt-auto d-none" id="pagination-footer">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0" id="pagination-controls"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Question Modal -->
<div class="modal fade" id="viewQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 bg-light">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-eye"></i> Question Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="text-dark" id="modal-question-text" style="line-height: 1.5;"></h5>
                    <span class="badge bg-light text-dark border ms-3" id="modal-question-marks"></span>
                </div>
                
                <div id="modal-question-image-container" class="mb-4 text-center d-none">
                    <img id="modal-question-image" src="" alt="Question Image" class="img-fluid rounded border shadow-sm" style="max-height: 300px;">
                </div>

                <h6 class="text-muted fw-bold small mb-3">Options:</h6>
                <div class="row g-3" id="modal-options-container">
                    <!-- Options injected here -->
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Dynamic Options Logic
    const optionsContainer = document.getElementById('options-container');
    const addOptionBtn = document.getElementById('add-option-btn');

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

    updateOptions();

    // AJAX Questions Logic
    const examId = <?= $exam['id'] ?>;
    let currentPage = 1;
    let currentSearch = '';
    let searchTimeout = null;

    document.getElementById('search-questions').addEventListener('input', function(e) {
        currentSearch = e.target.value;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadQuestions(currentPage);
        }, 300);
    });

    function loadQuestions(page) {
        document.getElementById('loading-spinner').style.display = 'block';
        document.getElementById('questions-container').style.display = 'none';
        document.getElementById('pagination-footer').classList.add('d-none');

        const searchParam = encodeURIComponent(currentSearch);
        fetch(`/admin/questions/ajax?exam_id=${examId}&page=${page}&search=${searchParam}`)
            .then(response => response.json())
            .then(data => {
                renderQuestions(data.questions, page, data.limit);
                renderPagination(data.page, data.total_pages);
                document.getElementById('total-questions-badge').textContent = data.total;
                
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('questions-container').style.display = 'block';
                if(data.total_pages > 1) {
                    document.getElementById('pagination-footer').classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error("Error loading questions:", error);
                document.getElementById('loading-spinner').innerHTML = '<div class="text-danger p-4">Failed to load questions.</div>';
            });
    }

    function renderQuestions(questions, page, limit) {
        const list = document.getElementById('questions-list');
        list.innerHTML = '';

        if (questions.length === 0) {
            if (currentSearch !== '') {
                list.innerHTML = `
                    <div class="text-center p-5">
                        <i class="bi bi-search fs-1 text-muted opacity-50 mb-3 d-block"></i>
                        <p class="text-muted m-0">No questions found matching "<b>${currentSearch}</b>".</p>
                    </div>
                `;
            } else {
                list.innerHTML = `
                    <div class="text-center p-5">
                        <i class="bi bi-inbox fs-1 text-muted opacity-50 mb-3 d-block"></i>
                        <p class="text-muted m-0">No questions added to this exam yet.</p>
                    </div>
                `;
            }
            return;
        }

        let startIndex = (page - 1) * limit + 1;

        questions.forEach((q, index) => {
            const li = document.createElement('li');
            li.className = 'list-group-item p-4';
            
            // Truncate text for list view
            let snippet = q.question_text.length > 80 ? q.question_text.substring(0, 80) + '...' : q.question_text;
            let hasImageBadge = q.image_url ? `<span class="badge bg-info text-white ms-2" style="font-size: 0.65rem;"><i class="bi bi-image"></i> Image</span>` : '';

            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3" style="max-width: 70%;">
                        <strong class="text-dark fs-6 d-block mb-1">
                            <span class="text-primary me-1">Q${startIndex + index}.</span> 
                            ${snippet} ${hasImageBadge}
                        </strong>
                        <span class="badge bg-light text-dark border whitespace-nowrap">${q.marks} Marks</span>
                    </div>
                    <div class="d-flex flex-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 me-1 view-btn" data-question='${JSON.stringify(q).replace(/'/g, "&apos;")}' title="View"><i class="bi bi-eye"></i> View</button>
                        <a href="/admin/questions/edit?id=${q.id}" class="btn btn-sm btn-outline-primary py-1 px-2 me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 delete-btn" data-id="${q.id}" title="Delete"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
            list.appendChild(li);
        });

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const q = JSON.parse(this.getAttribute('data-question'));
                openViewModal(q);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this question?')) {
                    const qid = this.getAttribute('data-id');
                    const ogHtml = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    this.disabled = true;

                    fetch(`/admin/questions/delete?id=${qid}&exam_id=${examId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loadQuestions(currentPage);
                            } else {
                                alert('Error deleting question: ' + (data.error || 'Unknown error'));
                                this.innerHTML = ogHtml;
                                this.disabled = false;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('A network error occurred while deleting.');
                            this.innerHTML = ogHtml;
                            this.disabled = false;
                        });
                }
            });
        });
    }

    function renderPagination(currentPage, totalPages) {
        const controls = document.getElementById('pagination-controls');
        controls.innerHTML = '';

        if (totalPages <= 1) return;

        // Prev
        let prevClass = currentPage === 1 ? 'disabled' : '';
        controls.innerHTML += `<li class="page-item ${prevClass}"><a class="page-link shadow-sm" href="#" data-page="${currentPage - 1}">Previous</a></li>`;

        // Pages
        for (let i = 1; i <= totalPages; i++) {
            let activeClass = i === currentPage ? 'active' : '';
            controls.innerHTML += `<li class="page-item ${activeClass}"><a class="page-link shadow-sm" href="#" data-page="${i}">${i}</a></li>`;
        }

        // Next
        let nextClass = currentPage === totalPages ? 'disabled' : '';
        controls.innerHTML += `<li class="page-item ${nextClass}"><a class="page-link shadow-sm" href="#" data-page="${currentPage + 1}">Next</a></li>`;

        // Attach clicks
        controls.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let p = parseInt(this.getAttribute('data-page'));
                if(p > 0 && p <= totalPages && p !== currentPage) {
                    loadQuestions(p);
                }
            });
        });
    }

    function openViewModal(q) {
        document.getElementById('modal-question-text').innerHTML = q.question_text.replace(/\n/g, '<br>');
        document.getElementById('modal-question-marks').textContent = q.marks + ' Marks';
        
        const imgContainer = document.getElementById('modal-question-image-container');
        if (q.image_url) {
            document.getElementById('modal-question-image').src = q.image_url;
            imgContainer.classList.remove('d-none');
        } else {
            imgContainer.classList.add('d-none');
        }

        const optsContainer = document.getElementById('modal-options-container');
        optsContainer.innerHTML = '';
        if (q.options && q.options.length > 0) {
            q.options.forEach((opt, index) => {
                let badge = opt.is_correct ? 'bg-success text-white border-success' : 'bg-light text-muted';
                let icon = opt.is_correct ? '<i class="bi bi-check-circle-fill float-end mt-1"></i>' : '';
                let char = String.fromCharCode(65 + index);
                optsContainer.innerHTML += `
                    <div class="col-md-6">
                        <div class="p-3 rounded border ${badge} shadow-sm" style="font-size: 14px;">
                            <span class="fw-bold me-2 opacity-75">${char}.</span> ${opt.option_text}
                            ${icon}
                        </div>
                    </div>
                `;
            });
        }

        var viewModal = new bootstrap.Modal(document.getElementById('viewQuestionModal'));
        viewModal.show();
    }

    // Initial load
    loadQuestions(currentPage);
});
</script>
</div>
