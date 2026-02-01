<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?? 'Manage Faculty' ?></h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Fitness</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($user['name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                </td>

                                <td>
                                    <?= $user['department'] ?? '<span class="text-muted">Not set</span>' ?>
                                </td>

                                <td>
                                    <?php if($user['height_cm'] && $user['weight_kg']): ?>
                                        H: <?= $user['height_cm'] ?>cm<br>
                                        W: <?= $user['weight_kg'] ?>kg
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                   <div class="btn-group btn-group-sm">

    <!-- ASSIGN PLAN (NOW ADDED FOR FACULTY) -->
    <a href="<?= BASE_URL ?>/admin/assignPlan/<?= $user['id'] ?>"
       class="btn btn-primary btn-sm mb-3"
       title="Assign Plan">
        📋 Plan
    </a>
<!-- <a href="<?= BASE_URL ?>/admin/attendance/<?= $user['id'] ?>"
   class="btn btn-info btn-sm mb-3"
   title="View Attendance">
    🗓️ Attendance
</a> -->
<a href="<?= BASE_URL ?>/admin/attendanceCalendar/<?= $user['id'] ?>"
   class="btn btn-info btn-sm mb-3">
    📅 View
</a>

    <?php if ($user['is_active']): ?>
        <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=0"
           class="btn btn-warning btn-sm mb-3"
           onclick="return confirm('Deactivate this faculty?')">
            🚫 Block
        </a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=1"
           class="btn btn-success btn-sm mb-3">
            ✅ Activate
        </a>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>/admin/deleteUser/<?= $user['id'] ?>"
       class="btn btn-danger btn-sm mb-3"
       onclick="return confirm('Are you sure?')">
        🗑️
    </a>

</div>

                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
