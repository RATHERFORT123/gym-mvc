<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Faculty Attendance Overview</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Attendance %</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($faculty as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['name']) ?></td>
                    <td>
                        <?= $this->model('Attendance')->getAttendancePercentage($f['id']) ?> %
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/attendance/<?= $f['id'] ?>"
                           class="btn btn-info btn-sm">
                            🗓️ View
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
