<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center align-items-center min-vh-60">
  <div class="col-lg-7 col-md-9">
    <div class="card shadow-lg border-0 bg-dark text-light p-0 overflow-hidden" style="background: linear-gradient(120deg, #1e1e1e 80%, var(--primary-color) 120%);">
      <div class="card-body text-center p-5">
        <div class="mb-4">
          <span style="font-size:3.5rem; color:var(--primary-color); filter: drop-shadow(0 0 8px var(--accent-glow));">&#127947;&#65039;</span>
        </div>
        <h2 class="text-warning mb-2" style="font-family:'Poppins',sans-serif; font-weight:800; letter-spacing:1px;">Payment Successful!</h2>
        <p class="text-white-50 mb-4" style="font-size:1.1rem;">Thanks for paying.<br> Your payment is successfully received.<br>Wait for approval!</p>

        <div class="row justify-content-center mb-4">
          <div class="col-md-10">
            <div class="bg-gradient rounded-4 p-3 px-md-5 mb-2" style="background:rgba(33,37,41,0.95); border:1px solid var(--primary-color);">
              <div class="row g-3 text-start">
                <div class="col-12 col-md-6">
                  <div class="mb-2"><span class="text-white">Plan:</span> <span class="fw-bold text-warning"><?= htmlspecialchars($subscription['plan_name']) ?></span></div>
                  <div class="mb-2"><span class="text-white">Amount:</span> <span class="fw-bold text-success">₹<?= number_format($subscription['amount'],2) ?></span></div>
                  <div class="mb-2"><span class="text-white">Transaction:</span> <span class="fw-bold text-info"><?= htmlspecialchars($subscription['utr_number']) ?></span></div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="mb-2"><span class="text-white">Start Date:</span> <span class="fw-bold text-light"><?= htmlspecialchars($subscription['start_date']) ?></span></div>
                  <div class="mb-2"><span class="text-white">End Date:</span> <span class="fw-bold text-light"><?= htmlspecialchars($subscription['end_date']) ?></span></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <a href="<?= BASE_URL ?>/user/dashboard" class="btn btn-lg btn-success px-5 py-2 fw-bold shadow-sm" style="font-size:1.2rem; letter-spacing:1px;">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>