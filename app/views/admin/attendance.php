<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Attendance Records</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    <!-- USER INFO -->
    <div class="card mb-4 shadow">
    <div class="card-body">
        <strong><?= htmlspecialchars($user['name'] ?? 'N/A') ?></strong><br>
        <small class="text-muted">
            <?= htmlspecialchars($user['email'] ?? '') ?>
        </small><br>
        <span class="badge bg-secondary mt-2">
            <?= ucfirst($user['role'] ?? 'user') ?>
        </span>
    </div>


    <a href="<?= BASE_URL ?>/admin/exportAttendance/<?= $user['id'] ?>"
   class="btn btn-success btn-sm mb-3">
    ⬇️ Export CSV
</a>

</div>


    <!-- ATTENDANCE TABLE -->
    <div class="card shadow">
        <div class="card-body">

            <?php if (empty($records)): ?>
                <div class="alert alert-info text-center">
                    No attendance records found.
                </div>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Role</th>
                                <th>Marked At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($row['attendance_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= ucfirst($row['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-success">Present</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
