<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">Manage Payments</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card shadow bg-dark text-white border-secondary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Payer UPI (Manual Feed)</th>
                            <th>Transaction ID (UTR)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="8" class="text-center text-muted">No payments found</td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td><?= date('d M, H:i', strtotime($p['created_at'])) ?></td>
                                    <td>
                                        <?= htmlspecialchars($p['user_name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($p['user_email']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($p['plan_name']) ?></td>
                                    <td class="fw-bold text-success">₹<?= number_format($p['amount'], 2) ?></td>
                                    <td>
                                        <span class="text-info"><?= htmlspecialchars($p['payer_upi'] ?: '-') ?></span>
                                    </td>
                                    <td><code><?= htmlspecialchars($p['utr_number'] ?: '-') ?></code></td>
                                    <td>
                                        <?php if ($p['status'] === 'verified'): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php elseif ($p['status'] === 'failed'): ?>
                                            <span class="badge bg-danger">Failed</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] === 'pending'): ?>
                                            <a href="<?= BASE_URL ?>/admin/verifyPayment/<?= $p['id'] ?>" class="btn btn-sm btn-success">Verify</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/header.php'; ?>
