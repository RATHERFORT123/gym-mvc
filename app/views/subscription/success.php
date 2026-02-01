<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-lg border-0 bg-dark text-light">
      <div class="card-body text-center p-4">
        <div class="mb-3">✅</div>
        <h3 class="text-warning">Payment Successful</h3>
        <p class="text-muted">Your subscription is now active.</p>

        <ul class="list-group list-group-flush mb-3 text-start">
          <li class="list-group-item bg-dark border-0"> <strong>Plan:</strong> <?= htmlspecialchars($subscription['plan_name']) ?> </li>
          <li class="list-group-item bg-dark border-0"> <strong>Amount:</strong> ₹<?= number_format($subscription['amount'],2) ?> </li>
          <li class="list-group-item bg-dark border-0"> <strong>Transaction:</strong> <?= htmlspecialchars($subscription['utr_number']) ?> </li>
          <li class="list-group-item bg-dark border-0"> <strong>Start:</strong> <?= htmlspecialchars($subscription['start_date']) ?> </li>
          <li class="list-group-item bg-dark border-0"> <strong>End:</strong> <?= htmlspecialchars($subscription['end_date']) ?> </li>
        </ul>

        <a href="<?= BASE_URL ?>/user/dashboard" class="btn btn-success">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>