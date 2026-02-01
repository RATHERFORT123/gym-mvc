<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>User Dashboard</h2>

<!-- Subscription Alerts moved from plans page -->
<?php if (!empty($currentPlan)): ?>
    
    <?php if (is_int($daysLeft) && $daysLeft <= 2 && $daysLeft >= 0): ?>
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="fas fa-hourglass-half me-2"></i>
            <strong>Heads up:</strong> Your plan <em><?= htmlspecialchars($currentPlan['plan_name']) ?></em> expires in <strong><?= $daysLeft === 0 ? 'today' : $daysLeft . ' day' . ($daysLeft > 1 ? 's' : '') ?></strong>.
            <a class="btn btn-sm btn-success ms-3" href="<?= BASE_URL ?>/payment/index?plan=<?= urlencode($currentPlan['plan_key']) ?>">Renew now</a>
        </div>

    <?php elseif (is_int($daysLeft) && $daysLeft < 0 && $currentPlan['payment_status'] !== 'pending'): ?>
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i>
            Your previous plan <em><?= htmlspecialchars($currentPlan['plan_name']) ?></em> has expired.
            <a class="btn btn-sm btn-success ms-3" href="<?= BASE_URL ?>/payment/index">View Plans</a>
        </div>
    <?php endif; ?>

    <?php if ($currentPlan['payment_status'] == 'verified'): ?>
        <div class="card mb-4 bg-dark border-warning shadow-lg">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-success mb-2">Active Plan</span>
                    <h5 class="text-warning mb-0"><?= htmlspecialchars($currentPlan['plan_name']) ?></h5>
                    <div class="small text-light">Valid until: <?= date('M d, Y', strtotime($currentPlan['end_date'])) ?></div>
                </div>
                <div class="text-end">
                    <h4 class="text-white mb-0"><?= $daysLeft ?></h4>
                    <div class="small text-muted">Days Left</div>
                </div>
            </div>
        </div>

    <?php elseif ($currentPlan['payment_status'] == 'pending'): ?>
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
            <div class="spinner-grow spinner-grow-sm text-info me-3" role="status"></div>
            <div>
                <strong>Payment Under Review:</strong> We've received your transaction details for the <em><?= htmlspecialchars($currentPlan['plan_name']) ?></em>. 
                Our team is verifying the UTR. Your access will be restored shortly.
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-secondary text-center border-0 shadow-sm py-4">
        <h5 class="mb-2">No Active Subscription</h5>
        <p class="text-muted small">Choose a plan to access workouts, diets, and attendance tracking.</p>
        <a class="btn btn-warning" href="<?= BASE_URL ?>/payment/index">Browse Plans</a>
    </div>
<?php endif; ?>

<ul>
    <li><a href="<?= BASE_URL ?>/profile/index">Profile</a></li>
    <li><a href="<?= BASE_URL ?>/plan/index">Diet and Workout Plan</a></li>
    <li>Attendance</li>
</ul>

<!-- Profile Completion Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Complete Your Profile</h5>
      </div>
      <div class="modal-body">
        <p>Please complete your profile to get personalized Diet and Workout plans from your instructor.</p>
        <p class="small text-muted">We need your BMI and Fitness Goals!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnLater">Complete Later</button>
        <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary">Complete Now</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($showProfileAlert) && $showProfileAlert): ?>
        var myModal = new bootstrap.Modal(document.getElementById('profileModal'));
        myModal.show();

        document.getElementById('btnLater').addEventListener('click', function() {
            // Call dismiss endpoint
            fetch('<?= BASE_URL ?>/user/dismissAlert', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                myModal.hide();
            });
        });
    <?php endif; ?>
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
