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
                            <tr><td colspan="8" class="text-center text-white">No payments found</td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td><?= date('d M, H:i', strtotime($p['created_at'])) ?></td>
                                    <td>
                                        <?= htmlspecialchars($p['user_name']) ?><br>
                                        <small class="text-white"><?= htmlspecialchars($p['user_email']) ?></small>
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
                                        <?php endif; ?>
                                        <button type="button" 
                                            class="btn btn-sm btn-primary edit-payment-btn btn-mini"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editPaymentModal"
                                            data-id="<?= $p['id'] ?>"
                                            data-utr="<?= htmlspecialchars($p['utr_number'] ?? '') ?>"
                                            data-upi="<?= htmlspecialchars($p['payer_upi'] ?? '') ?>">
                                            Edit
                                        </button>
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

<div class="modal fade" id="editPaymentModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="<?= BASE_URL ?>/admin/editPayment">
      <div class="modal-header">
        <h5 class="modal-title text-black">Edit Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="payment_id" id="edit_payment_id">

        <div class="mb-3">
          <label class="text-black">Transaction ID (UTR)</label>
          <input type="text" name="utr_number" id="edit_utr_number" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="text-black">Payer UPI ID</label>
          <input type="text" name="payer_upi" id="edit_payer_upi" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editBtns = document.querySelectorAll('.edit-payment-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_payment_id').value = this.dataset.id;
            document.getElementById('edit_utr_number').value = this.dataset.utr;
            document.getElementById('edit_payer_upi').value = this.dataset.upi;
        });
    });
});
</script>


<?php include __DIR__ . '/../layouts/footer.php'; ?>
