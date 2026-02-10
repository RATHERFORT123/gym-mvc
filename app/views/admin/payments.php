<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning">Manage Payments</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>
<form method="GET" action="<?= BASE_URL ?>/admin/payments"
      class="card mb-4 shadow"
      style="background:#0b1c2d;border:1px solid #7a1f1f;">
  <div class="card-body">
    <div class="row g-3">

      <!-- Payment Date -->
      <div class="col-md-3 col-sm-6">
        <label class="text-white">Payment From</label>
        <input type="date" name="start_date" class="form-control"
               value="<?= $_GET['start_date'] ?? '' ?>">
      </div>

      <div class="col-md-3 col-sm-6">
        <label class="text-white">Payment To</label>
        <input type="date" name="end_date" class="form-control"
               value="<?= $_GET['end_date'] ?? '' ?>">
      </div>

      <!-- Status -->
      <div class="col-md-3 col-sm-6">
        <label class="text-white">Payment Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <?php foreach (['pending','verified','rejected','failed'] as $s): ?>
            <option value="<?= $s ?>" <?= ($_GET['status'] ?? '')==$s?'selected':'' ?>>
              <?= ucfirst($s) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Membership Expiry -->
      <div class="col-md-3 col-sm-6">
        <label class="text-white">Membership</label>
        <select name="expiry_filter" class="form-select">
          <option value="">All</option>
          <option value="active"  <?= ($_GET['expiry_filter'] ?? '')=='active'?'selected':'' ?>>Active</option>
          <option value="expired" <?= ($_GET['expiry_filter'] ?? '')=='expired'?'selected':'' ?>>Expired</option>
        </select>
      </div>

      <!-- UTR -->
      <div class="col-md-3 col-sm-6">
        <label class="text-white">UTR Number</label>
        <input type="text" name="utr" class="form-control"
               value="<?= $_GET['utr'] ?? '' ?>">
      </div>

      <!-- UPI -->
      <div class="col-md-3 col-sm-6">
        <label class="text-white">Payer UPI</label>
        <input type="text" name="payer_upi" class="form-control"
               value="<?= $_GET['payer_upi'] ?? '' ?>">
      </div>
      <div class="col-md-3 col-sm-6">
          <label class="text-white">Account Holder Name</label>
          <input type="text"
                name="account_holder"
                class="form-control"
                placeholder="e.g. Chetan"
                value="<?= $_GET['account_holder'] ?? '' ?>">
      </div>

      <!-- Buttons -->
      <div class="col-md-6 d-flex align-items-end gap-2">
        <button class="btn btn-danger fw-bold">Apply</button>

        <?php
          $params = $_GET;
          unset($params['url']);
        ?>

        <a
          href="<?= BASE_URL ?>/admin/exportPayments?<?= http_build_query($params) ?>"
          class="btn btn-success"
          target="_blank"
        >
          Download Excel
        </a>

        <a href="<?= BASE_URL ?>/admin/payments"
           class="btn btn-secondary">Reset</a>
      </div>

    </div>
  </div>
</form>


    <div class="card shadow bg-dark text-white border-secondary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expiry Date</th>

                            <th>User</th>
                            <th>Mobile number</th>
                            <th>Plan</th>
                            <th>Amount</th> 
                            <th>Payer UPI (Manual Feed)</th>
                            <th>Transaction ID (UTR)</th>
                            <th>Rejected Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="11" class="text-center text-white">No payments found</td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td><?= date('d M, H:i', strtotime($p['created_at'])) ?></td>
                                    <td>
                                    <?= !empty($p['expiry_date'])
                                          ? date('d M Y', strtotime($p['expiry_date']))
                                          : '—' ?>
                                  </td>
                                    <td>
                                        <?= htmlspecialchars($p['user_name']) ?><br>
                                        <small class="text-white"><?= htmlspecialchars($p['user_email']) ?></small>
                                    </td>
                                    <td>
                                        <?= $p['mobile_number'] ?? 'N/A' ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['plan_name']) ?></td>
                                    <td class="fw-bold text-success">₹<?= number_format($p['amount'], 2) ?></td>
                                    <td>
                                        <span class="text-info"><?= htmlspecialchars($p['payer_upi'] ?: '-') ?></span>
                                    </td>
                                    <td><code><?= htmlspecialchars($p['utr_number'] ?: '---') ?></code></td>
                                    <td>
                                        <span class="<?= ($p['status'] === 'rejected' && !empty($p['declined_reason']))
                                            ? 'text-danger'
                                            : 'text-white text-center d-block' ?>">
                                            
                                            <?= ($p['status'] === 'rejected' && !empty($p['declined_reason']))
                                                ? htmlspecialchars($p['declined_reason'])
                                                : '----' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] === 'verified'): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php elseif ($p['status'] === 'failed'): ?>
                                            <span class="badge bg-danger">Failed</span>
                                        <?php elseif ($p['status'] === 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] !== 'verified'): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-dark">
                                                    <?php if ($p['status'] === 'pending'): ?>
                                                        <li>
                                                            <form
                                                                id="verify-form-<?= (int)$p['id'] ?>"
                                                                action="<?= BASE_URL ?>/admin/verifyPayment/<?= (int)$p['id'] ?>"
                                                                method="post"
                                                                class="d-block">
                                                                <button
                                                                    type="button"
                                                                    id="verify-btn-<?= (int)$p['id'] ?>"
                                                                    class="dropdown-item text-success verify-payment-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#verifyPaymentModal"
                                                                    data-form-id="verify-form-<?= (int)$p['id'] ?>">
                                                                    <i class="fas fa-check me-2"></i> Verify
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <button type="button" 
                                                                class="dropdown-item text-danger reject-payment-btn"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#rejectPaymentModal"
                                                                data-id="<?= $p['id'] ?>">
                                                                <i class="fas fa-times me-2"></i> Reject
                                                            </button>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <button type="button" 
                                                            class="dropdown-item text-primary edit-payment-btn"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editPaymentModal"
                                                            data-id="<?= $p['id'] ?>"
                                                            data-utr="<?= htmlspecialchars($p['utr_number'] ?? '') ?>"
                                                            data-upi="<?= htmlspecialchars($p['payer_upi'] ?? '') ?>"
                                                            data-status="<?= $p['status'] ?>">
                                                            <i class="fas fa-edit me-2"></i> Edit
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small"><i class="fas fa-check-circle"></i> Done</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($totalPages > 1): ?>
<nav class="mt-4">
  <ul class="pagination justify-content-center">

    <?php
      $params = $_GET;
      unset($params['page'], $params['url']);
    ?>

    <!-- Previous -->
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link"
         href="?<?= http_build_query(array_merge($params, ['page' => $page - 1])) ?>">
        Previous
      </a>
    </li>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link"
           href="?<?= http_build_query(array_merge($params, ['page' => $i])) ?>">
          <?= $i ?>
        </a>
      </li>
    <?php endfor; ?>

    <!-- Next -->
    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
      <a class="page-link"
         href="?<?= http_build_query(array_merge($params, ['page' => $page + 1])) ?>">
        Next
      </a>
    </li>

  </ul>
</nav>
<?php endif; ?>

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

        <div class="mb-3">
          <label class="text-black">Status</label>
          <select name="status" id="edit_status" class="form-select">
              <option value="pending">Pending</option>
              <option value="rejected">Rejected</option>
              <option value="verified">Verified</option>
          </select>
          <small class="text-muted">Set to <strong>Pending</strong> to allow verification again.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Verify Payment Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-black">Confirm Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-black">Are you sure you want to verify this payment and activate the subscription?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirmVerifyBtn">Yes, Verify</button>
      </div>
    </div>
  </div>
</div>

<!-- Reject Payment Modal -->
<div class="modal fade" id="rejectPaymentModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="<?= BASE_URL ?>/admin/rejectPayment">
      <div class="modal-header">
        <h5 class="modal-title text-black">Reject Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="payment_id" id="reject_payment_id">
        <p class="text-black">Are you sure you want to reject this payment?</p>
        <div class="mb-3">
            <label class="text-black fw-bold">Reason for Rejection</label>
            <textarea name="declined_reason" class="form-control" rows="3" required placeholder="e.g. Invalid Transaction ID or Amount mismatch"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Confirm Reject</button>
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
            document.getElementById('edit_status').value = this.dataset.status;
        });
    });

    const rejectBtns = document.querySelectorAll('.reject-payment-btn');
    rejectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('reject_payment_id').value = this.dataset.id;
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let verifyFormId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Handle Verify Button Click
    document.querySelectorAll('.verify-payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            verifyFormId = this.dataset.formId;
        });
    });

    // Handle Confirm Verify Click
    document.getElementById('confirmVerifyBtn').addEventListener('click', function() {
        if (verifyFormId) {
            const form = document.getElementById(verifyFormId);
            if (form) {
                form.submit();
            }
        }
    });
});
</script>


<?php include __DIR__ . '/../layouts/footer.php'; ?>
