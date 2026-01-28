<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Users</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Fitness Goal</th>
                            <th>BMI Stats</th>
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
                                <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
                                <td>
                                    <?php if($user['fitness_goal']): ?>
                                        <span class="badge bg-info text-dark"><?= $user['fitness_goal'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user['weight_kg'] && $user['height_cm']): ?>
                                        <small>
                                            H: <?= $user['height_cm'] ?>cm<br>
                                            W: <?= $user['weight_kg'] ?>kg
                                        </small>
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
                                        <a href="<?= BASE_URL ?>/admin/assignPlan/<?= $user['id'] ?>" class="btn btn-primary" title="Assign Plan">
                                            📋 Plan
                                        </a>
                                        
                                        <?php if ($user['is_active']): ?>
                                            <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=0" class="btn btn-warning" onclick="return confirm('Deactivate this user?')">
                                                🚫 Block
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/admin/toggleStatus/<?= $user['id'] ?>?status=1" class="btn btn-success">
                                                ✅ Activate
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= BASE_URL ?>/admin/deleteUser/<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">
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
