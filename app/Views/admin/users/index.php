<?php
$roleMeta = [
    'admin'    => ['label'=>'Admin',    'color'=>'#6366f1','bg'=>'rgba(99,102,241,.12)','icon'=>'bi-shield-fill-check'],
    'student'  => ['label'=>'Student',  'color'=>'#22c55e','bg'=>'rgba(34,197,94,.12)', 'icon'=>'bi-mortarboard-fill'],
    'examiner' => ['label'=>'Examiner', 'color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)','icon'=>'bi-pencil-square'],
];
?>

<?php if (isset($_GET['success'])): ?>
<?php $msgs=['created'=>'User created successfully!','updated'=>'User updated successfully!']; ?>
<div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
    <i class="bi bi-check-circle-fill me-2"></i><?= $msgs[$_GET['success']] ?? 'Done!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <?php
    $statCards = [
        ['label'=>'Total Users',      'value'=>$stats['total']??0,        'color'=>'#6366f1','icon'=>'bi-people-fill'],
        ['label'=>'Admins',           'value'=>$stats['admins']??0,       'color'=>'#8b5cf6','icon'=>'bi-shield-fill-check'],
        ['label'=>'Students',         'value'=>$stats['students']??0,     'color'=>'#22c55e','icon'=>'bi-mortarboard-fill'],
        ['label'=>'Examiners',        'value'=>$stats['examiners']??0,    'color'=>'#f59e0b','icon'=>'bi-pencil-square'],
        ['label'=>'Active',           'value'=>$stats['active']??0,       'color'=>'#06b6d4','icon'=>'bi-person-check-fill'],
        ['label'=>'New This Month',   'value'=>$stats['new_this_month']??0,'color'=>'#ec4899','icon'=>'bi-person-plus-fill'],
    ];
    foreach($statCards as $card): ?>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm h-100" style="border-top:3px solid <?= $card['color'] ?> !important;">
            <div class="card-body py-3 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                     style="width:42px;height:42px;background:<?= str_replace(')','.15)',$card['color']) ?>;">
                    <i class="bi <?= $card['icon'] ?>" style="color:<?= $card['color'] ?>;font-size:18px;"></i>
                </div>
                <div class="fw-bold fs-3 lh-1"><?= $card['value'] ?></div>
                <div class="text-muted small mt-1"><?= $card['label'] ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter + Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-shield-lock text-primary me-2"></i>All Users <span class="badge bg-primary rounded-pill ms-1"><?= count($users) ?></span></h6>
            </div>
            <div class="col-md-8">
                <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
                    <form method="GET" action="/admin/users" class="d-flex flex-wrap gap-2" id="filter-form">
                        <select name="role" class="form-select form-select-sm" style="width:auto;" onchange="document.getElementById('filter-form').submit()">
                            <option value="">All Roles</option>
                            <option value="admin"    <?= ($_GET['role']??'')==='admin'    ?'selected':'' ?>>Admin</option>
                            <option value="student"  <?= ($_GET['role']??'')==='student'  ?'selected':'' ?>>Student</option>
                            <option value="examiner" <?= ($_GET['role']??'')==='examiner' ?'selected':'' ?>>Examiner</option>
                        </select>
                        <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="document.getElementById('filter-form').submit()">
                            <option value="">All Status</option>
                            <option value="active"   <?= ($_GET['status']??'')==='active'   ?'selected':'' ?>>Active</option>
                            <option value="inactive" <?= ($_GET['status']??'')==='inactive' ?'selected':'' ?>>Inactive</option>
                        </select>
                        <div class="input-group input-group-sm" style="width:200px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search name, email..." value="<?= htmlspecialchars($_GET['search']??'') ?>">
                        </div>
                        <?php if(!empty($_GET['search'])||!empty($_GET['role'])||!empty($_GET['status'])): ?>
                            <a href="/admin/users" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                        <?php endif; ?>
                    </form>
                    <a href="/admin/users/create" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-person-plus-fill me-1"></i>Add User</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>City</th>
                        <th>Exam Activity</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>No users found.
                    </td></tr>
                    <?php else: ?>
                    <?php foreach($users as $i => $u):
                        $rm = $roleMeta[$u['role']] ?? $roleMeta['student'];
                        $isSelf = ($u['id'] == ($_SESSION['user_id'] ?? 0));
                    ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?= $i+1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:38px;height:38px;background:hsl(<?=(crc32($u['name'])%360+360)%360?>,60%,50%);">
                                    <?= strtoupper(substr($u['name'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark d-flex align-items-center gap-1">
                                        <?= htmlspecialchars($u['name']) ?>
                                        <?php if($isSelf): ?><span class="badge bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25 ms-1" style="font-size:10px;">You</span><?php endif; ?>
                                    </div>
                                    <div class="text-muted small"><?= htmlspecialchars($u['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                  style="background:<?=$rm['bg']?>;color:<?=$rm['color']?>;font-size:12px;border:1px solid <?=$rm['color']?>22;">
                                <i class="bi <?=$rm['icon']?>"></i> <?=$rm['label']?>
                            </span>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($u['city'] ?: '—') ?></td>
                        <td>
                            <?php if($u['role']==='student'): ?>
                                <span class="text-primary fw-semibold"><?= $u['exams_taken']??0 ?></span>
                                <span class="text-muted small">exams</span>
                                <?php if($u['avg_score']!==null): ?>
                                    <span class="badge bg-light text-muted border ms-1"><?=$u['avg_score']?>% avg</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <span class="badge user-status-badge-<?=$u['id']?> <?= $u['status']==='active'
                                ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'
                                : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25' ?>">
                                <?= ucfirst($u['status']) ?>
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="/admin/users/edit?id=<?=$u['id']?>" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                <button class="btn btn-sm btn-outline-warning py-1 px-2 toggle-status-btn" data-id="<?=$u['id']?>" title="Toggle Status" <?=$isSelf?'disabled':''?>><i class="bi bi-toggle-on"></i></button>
                                <button class="btn btn-sm btn-outline-danger py-1 px-2 delete-user-btn" data-id="<?=$u['id']?>" data-name="<?=htmlspecialchars($u['name'])?>" title="Delete" <?=$isSelf?'disabled':''?>><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white text-muted small py-2 px-4">Showing <?= count($users) ?> user(s)</div>
</div>

<script>
// Toggle Status
document.querySelectorAll('.toggle-status-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        this.disabled = true;
        fetch('/admin/users/toggle-status?id=' + id)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const badge = document.querySelector('.user-status-badge-' + id);
                    if (badge.classList.contains('text-success')) {
                        badge.className = `badge user-status-badge-${id} bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25`;
                        badge.textContent = 'Inactive';
                    } else {
                        badge.className = `badge user-status-badge-${id} bg-success bg-opacity-10 text-success border border-success border-opacity-25`;
                        badge.textContent = 'Active';
                    }
                }
                this.disabled = false;
            });
    });
});

// Delete
document.querySelectorAll('.delete-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id   = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        if (!confirm(`Permanently delete "${name}"? This cannot be undone.`)) return;
        const row  = this.closest('tr');
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        this.disabled = true;
        fetch('/admin/users/delete?id=' + id)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                } else {
                    alert(data.error || 'Could not delete this user.');
                    this.innerHTML = '<i class="bi bi-trash"></i>';
                    this.disabled = false;
                }
            });
    });
});
</script>
