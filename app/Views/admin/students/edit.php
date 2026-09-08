<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark"><i class="bi bi-person-gear text-primary me-2"></i>Edit Student</h4>
    <a href="/admin/students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Students</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i> Failed to update. Email may already be in use.</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <!-- Avatar Header -->
            <div class="py-4 text-center text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius: .375rem .375rem 0 0;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold fs-1 mb-2"
                     style="width:72px;height:72px;background:rgba(255,255,255,.25);border:3px solid rgba(255,255,255,.5);">
                    <?= strtoupper(substr($student['name'],0,1)) ?>
                </div>
                <div class="fw-bold fs-5"><?= htmlspecialchars($student['name']) ?></div>
                <div class="opacity-75 small"><?= htmlspecialchars($student['email']) ?></div>
            </div>

            <div class="card-body p-4">
                <form action="/admin/students/update" method="POST">
                    <input type="hidden" name="id" value="<?= $student['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">City</label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($student['city'] ?? '') ?>" placeholder="e.g. Mumbai">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   <?= $student['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="pw-field" class="form-control" placeholder="••••••••" minlength="6" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePw()"><i class="bi bi-eye" id="pw-icon"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row g-3 mt-2">
                        <div class="col-4">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="fw-bold fs-4 text-primary"><?= $student['total_attempts'] ?? 0 ?></div>
                                <div class="text-muted small">Attempts</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="fw-bold fs-4 text-success"><?= $student['completed_exams'] ?? 0 ?></div>
                                <div class="text-muted small">Completed</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="fw-bold fs-4 text-info"><?= $student['avg_score'] !== null ? $student['avg_score'] . '%' : '—' ?></div>
                                <div class="text-muted small">Avg Score</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                            <i class="bi bi-floppy-fill me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw() {
    const f = document.getElementById('pw-field');
    const icon = document.getElementById('pw-icon');
    f.type = f.type === 'password' ? 'text' : 'password';
    icon.className = f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
