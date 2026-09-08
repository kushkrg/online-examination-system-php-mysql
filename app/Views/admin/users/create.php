<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark"><i class="bi bi-person-plus-fill text-primary me-2"></i>Create New User</h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Users</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= $_GET['error'] === 'email_exists' ? 'This email address is already registered.' : 'Failed to create user. Please try again.' ?>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="py-4 text-center text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:.375rem .375rem 0 0;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                     style="width:64px;height:64px;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.4);">
                    <i class="bi bi-person-plus-fill fs-2"></i>
                </div>
                <div class="fw-bold fs-5">New User Account</div>
                <div class="opacity-75 small">Fill in the details below to create an account</div>
            </div>
            <div class="card-body p-4">
                <form action="/admin/users/store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="pw-field" class="form-control" placeholder="Min. 6 characters" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePw()"><i class="bi bi-eye" id="pw-icon"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Mumbai">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="student" selected>🎓 Student</option>
                                <option value="examiner">✏️ Examiner</option>
                                <option value="admin">🛡️ Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Role Descriptions -->
                    <div class="mt-3 p-3 bg-light rounded border">
                        <div class="text-muted small">
                            <strong>🛡️ Admin</strong> — Full system access including user management.<br>
                            <strong>✏️ Examiner</strong> — Can create and manage exams.<br>
                            <strong>🎓 Student</strong> — Can take exams and view their results.
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                            <i class="bi bi-person-plus-fill me-2"></i>Create User
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
