<?php if (isset($_GET['success'])): ?>
    <?php $msg = $_GET['success'] === 'updated' ? 'Student updated successfully!' : 'Action completed!'; ?>
    <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i> <?= $msg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:rgba(99,102,241,.12);">
                    <i class="bi bi-people-fill fs-4" style="color:#6366f1;"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Students</div>
                    <div class="fw-bold fs-3 lh-1"><?= $stats['total'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #22c55e !important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:rgba(34,197,94,.12);">
                    <i class="bi bi-person-check-fill fs-4" style="color:#22c55e;"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Active</div>
                    <div class="fw-bold fs-3 lh-1"><?= $stats['active'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:rgba(239,68,68,.12);">
                    <i class="bi bi-person-dash-fill fs-4" style="color:#ef4444;"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Inactive</div>
                    <div class="fw-bold fs-3 lh-1"><?= $stats['inactive'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;background:rgba(245,158,11,.12);">
                    <i class="bi bi-person-plus-fill fs-4" style="color:#f59e0b;"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">New This Week</div>
                    <div class="fw-bold fs-3 lh-1"><?= $stats['new_this_week'] ?? 0 ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-5">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Student Management</h6>
            </div>
            <div class="col-md-7">
                <form method="GET" action="/admin/students" class="row g-2 justify-content-end" id="filter-form">
                    <div class="col-auto">
                        <select name="status" class="form-select form-select-sm" onchange="document.getElementById('filter-form').submit()">
                            <option value="" <?= ($_GET['status'] ?? '') === '' ? 'selected' : '' ?>>All Status</option>
                            <option value="active"   <?= ($_GET['status'] ?? '') === 'active'   ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search name, email, city..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="min-width:200px;">
                        </div>
                    </div>
                    <?php if (!empty($_GET['search']) || !empty($_GET['status'])): ?>
                    <div class="col-auto">
                        <a href="/admin/students" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                    </div>
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
                        <th>City</th>
                        <th>Exams Taken</th>
                        <th>Avg Score</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2 opacity-50"></i>
                                No students found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $i => $s): ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                             style="width:38px;height:38px;background:hsl(<?= (crc32($s['name']) % 360 + 360) % 360 ?>,60%,50%);">
                                            <?= strtoupper(substr($s['name'],0,1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?= htmlspecialchars($s['name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($s['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted"><?= htmlspecialchars($s['city'] ?: '—') ?></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">
                                        <?= $s['completed_exams'] ?? 0 ?> / <?= $s['total_attempts'] ?? 0 ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $avg = $s['avg_score'] ?? null; ?>
                                    <?php if ($avg !== null): ?>
                                        <span class="fw-semibold <?= $avg >= 50 ? 'text-success' : 'text-danger' ?>"><?= $avg ?>%</span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= date('M d, Y', strtotime($s['created_at'])) ?></td>
                                <td>
                                    <span class="badge status-badge-<?= $s['id'] ?> <?= $s['status'] === 'active' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25' ?>">
                                        <?= ucfirst($s['status']) ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-sm btn-outline-secondary py-1" onclick="viewProfile(<?= $s['id'] ?>)" title="View Profile"><i class="bi bi-eye"></i></button>
                                        <a href="/admin/students/edit?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary py-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <button class="btn btn-sm btn-outline-warning py-1 toggle-status-btn" data-id="<?= $s['id'] ?>" title="Toggle Status"><i class="bi bi-toggle-on"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 delete-student-btn" data-id="<?= $s['id'] ?>" data-name="<?= htmlspecialchars($s['name']) ?>" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white text-muted small py-2 px-4">
        Showing <?= count($students) ?> student(s)
    </div>
</div>

<!-- Student Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 py-3" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-person-circle me-2"></i><span id="modal-student-name"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="profile-modal-body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewProfile(id) {
    document.getElementById('modal-student-name').textContent = 'Loading...';
    document.getElementById('profile-modal-body').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal(document.getElementById('profileModal')).show();

    fetch('/admin/students/profile?id=' + id)
        .then(r => r.json())
        .then(data => {
            const s = data.student;
            const history = data.history;
            document.getElementById('modal-student-name').textContent = s.name;
            
            let historyRows = history.length === 0
                ? '<tr><td colspan="6" class="text-center text-muted py-3">No exam history found.</td></tr>'
                : history.map((h, i) => {
                    const passed = parseFloat(h.score) >= parseFloat(h.passing_marks);
                    return `<tr>
                        <td>${i+1}</td>
                        <td class="fw-semibold">${h.title}</td>
                        <td>${h.start_time ? new Date(h.start_time).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}) : '—'}</td>
                        <td><span class="fw-bold ${passed ? 'text-success' : 'text-danger'}">${h.score} / ${h.total_marks}</span></td>
                        <td><span class="badge ${passed ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'}">${passed ? 'Pass' : 'Fail'}</span></td>
                        <td>${h.tab_switches > 0 ? '<span class="badge bg-warning text-dark">'+h.tab_switches+' switch(es)</span>' : '<span class="text-muted">Clean</span>'}</td>
                    </tr>`;
                }).join('');

            document.getElementById('profile-modal-body').innerHTML = `
                <div class="row g-4 mb-4">
                    <div class="col-md-4 text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3 fs-1"
                             style="width:90px;height:90px;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                            ${s.name.charAt(0).toUpperCase()}
                        </div>
                        <h5 class="fw-bold mb-1">${s.name}</h5>
                        <p class="text-muted small mb-2">${s.email}</p>
                        <span class="badge ${s.status === 'active' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'} px-3 py-2">
                            ${s.status.charAt(0).toUpperCase() + s.status.slice(1)}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light text-center py-3">
                                    <div class="fw-bold fs-2 text-primary">${s.total_attempts ?? 0}</div>
                                    <div class="text-muted small">Attempts</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light text-center py-3">
                                    <div class="fw-bold fs-2 text-success">${s.completed_exams ?? 0}</div>
                                    <div class="text-muted small">Completed</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light text-center py-3">
                                    <div class="fw-bold fs-2 text-info">${s.avg_score != null ? s.avg_score + '%' : '—'}</div>
                                    <div class="text-muted small">Avg Score</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light text-center py-3">
                                    <div class="fw-bold fs-2 ${parseInt(s.total_violations) > 0 ? 'text-danger' : 'text-success'}">${s.total_violations ?? 0}</div>
                                    <div class="text-muted small">Violations</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-6"><small class="text-muted d-block">City</small><span class="fw-semibold">${s.city || '—'}</span></div>
                            <div class="col-6"><small class="text-muted d-block">Joined On</small><span class="fw-semibold">${new Date(s.created_at).toLocaleDateString('en-IN',{day:'2-digit',month:'long',year:'numeric'})}</span></div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6 class="fw-bold mb-3"><i class="bi bi-journal-text me-2 text-primary"></i>Exam History</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>#</th><th>Exam</th><th>Date</th><th>Score</th><th>Result</th><th>Integrity</th></tr>
                        </thead>
                        <tbody>${historyRows}</tbody>
                    </table>
                </div>
            `;
        });
}

// Toggle Status
document.querySelectorAll('.toggle-status-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        this.disabled = true;
        fetch('/admin/students/toggle-status?id=' + id)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Update badge
                    const badge = document.querySelector('.status-badge-' + id);
                    if (badge.classList.contains('text-success')) {
                        badge.className = `badge status-badge-${id} bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25`;
                        badge.textContent = 'Inactive';
                    } else {
                        badge.className = `badge status-badge-${id} bg-success bg-opacity-10 text-success border border-success border-opacity-25`;
                        badge.textContent = 'Active';
                    }
                }
                this.disabled = false;
            });
    });
});

// Delete Student
document.querySelectorAll('.delete-student-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        if (confirm(`Are you sure you want to permanently delete "${name}"? This cannot be undone.`)) {
            const row = this.closest('tr');
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            this.disabled = true;
            fetch('/admin/students/delete?id=' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        alert('Could not delete this student.');
                        this.innerHTML = '<i class="bi bi-trash"></i>';
                        this.disabled = false;
                    }
                });
        }
    });
});
</script>
