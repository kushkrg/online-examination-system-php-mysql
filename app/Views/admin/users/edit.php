<?php
$roleMeta = [
    'admin'    => ['label'=>'Admin',    'grad'=>'linear-gradient(135deg,#6366f1,#8b5cf6)', 'icon'=>'bi-shield-fill-check'],
    'student'  => ['label'=>'Student',  'grad'=>'linear-gradient(135deg,#16a34a,#22c55e)', 'icon'=>'bi-mortarboard-fill'],
    'examiner' => ['label'=>'Examiner', 'grad'=>'linear-gradient(135deg,#d97706,#f59e0b)', 'icon'=>'bi-pencil-square'],
];
$rm = $roleMeta[$user['role']] ?? $roleMeta['student'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark"><i class="bi bi-person-gear text-primary me-2"></i>Edit User</h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Users</a>
</div>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i> Update failed. Email may already be in use.</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="card border-0 shadow-sm">
            <!-- Gradient Header -->
            <div class="py-4 text-center text-white" style="background:<?= $rm['grad'] ?>;border-radius:.375rem .375rem 0 0;">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-2"
                     style="width:72px;height:72px;font-size:26px;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.4);">
                    <?= strtoupper(substr($user['name'],0,1)) ?>
                </div>
                <div class="fw-bold fs-5"><?= htmlspecialchars($user['name']) ?></div>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-1 opacity-85">
                    <i class="bi <?= $rm['icon'] ?>"></i>
                    <span class="small"><?= $rm['label'] ?> Account</span>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Quick Stats for Students -->
                <?php if($user['role']==='student'): ?>
                <div class="row g-3 mb-4">
                    <div class="col-4"><div class="bg-light rounded p-3 text-center">
                        <div class="fw-bold fs-3 text-primary"><?=$user['exams_taken']??0?></div>
                        <div class="text-muted small">Exams Taken</div>
                    </div></div>
                    <div class="col-4"><div class="bg-light rounded p-3 text-center">
                        <div class="fw-bold fs-3 text-success"><?=$user['avg_score']!==null?$user['avg_score'].'%':'—'?></div>
                        <div class="text-muted small">Avg Score</div>
                    </div></div>
                    <div class="col-4"><div class="bg-light rounded p-3 text-center">
                        <div class="fw-bold fs-3 <?=($user['total_violations']??0)>0?'text-danger':'text-success'?>"><?=$user['total_violations']??0?></div>
                        <div class="text-muted small">Violations</div>
                    </div></div>
                </div>
                <?php endif; ?>

                <form action="/admin/users/update" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">City</label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city']??'') ?>" placeholder="e.g. Delhi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="pw-field" class="form-control" placeholder="••••••••" minlength="6" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePw()"><i class="bi bi-eye" id="pw-icon"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Role</label>
                            <select name="role" class="form-select">
                                <option value="student"  <?= $user['role']==='student'  ?'selected':'' ?>>🎓 Student</option>
                                <option value="examiner" <?= $user['role']==='examiner' ?'selected':'' ?>>✏️ Examiner</option>
                                <option value="admin"    <?= $user['role']==='admin'    ?'selected':'' ?>>🛡️ Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   <?= $user['status']==='active'  ?'selected':'' ?>>Active</option>
                                <option value="inactive" <?= $user['status']==='inactive'?'selected':'' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary fw-bold py-2 flex-grow-1 shadow-sm">
                            <i class="bi bi-floppy-fill me-2"></i>Save Changes
                        </button>
                        <a href="/admin/users" class="btn btn-outline-secondary py-2 px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card border-0 shadow-sm mt-4" style="border-top:3px solid #ef4444 !important;">
            <div class="card-body py-3 d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold text-danger small">Danger Zone</div>
                    <div class="text-muted small">Permanently delete this user and all their data.</div>
                </div>
                <button class="btn btn-sm btn-outline-danger delete-user-btn" data-id="<?= $user['id'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>">
                    <i class="bi bi-trash me-1"></i> Delete User
                </button>
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

document.querySelectorAll('.delete-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id   = this.dataset.id;
        const name = this.dataset.name;
        if (!confirm(`Permanently delete "${name}"? This cannot be undone.`)) return;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        this.disabled = true;
        fetch('/admin/users/delete?id=' + id)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/admin/users?success=deleted';
                } else {
                    alert(data.error || 'Could not delete this user.');
                    this.innerHTML = '<i class="bi bi-trash me-1"></i> Delete User';
                    this.disabled = false;
                }
            });
    });
});
</script>
