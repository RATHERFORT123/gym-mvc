<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>User Dashboard</h2>

<!-- Subscription Alerts moved from plans page -->
<?php if (!empty($currentPlan)): ?>
    <?php if (is_int($daysLeft) && $daysLeft <= 2 && $daysLeft >= 0): ?>
        <div class="alert alert-warning">
            <strong>Heads up:</strong> Your plan <em><?= htmlspecialchars($currentPlan['plan_name']) ?></em> expires in <strong><?= $daysLeft === 0 ? 'today' : $daysLeft . ' day' . ($daysLeft > 1 ? 's' : '') ?></strong>.
            <a class="btn btn-sm btn-success ms-3" href="<?= BASE_URL ?>/payment/index?plan=<?= urlencode($currentPlan['plan_key']) ?>">Renew now</a>
        </div>
    <?php elseif (is_int($daysLeft) && $daysLeft < 0): ?>
        <div class="alert alert-danger">
            Your previous plan <em><?= htmlspecialchars($currentPlan['plan_name']) ?></em> has expired. Please purchase a new plan.
            <a class="btn btn-sm btn-success ms-3" href="<?= BASE_URL ?>/payment/index">View Plans</a>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between">
            <div>
                <strong>Current Plan:</strong> <?= htmlspecialchars($currentPlan['plan_name']) ?>
                <div class="text-white">Expires: <?= htmlspecialchars($currentPlan['end_date'] ?? 'N/A') ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning text-center">
        <strong>No Active Subscription</strong> — You don't have an active plan. Choose a plan to get started.
        <a class="btn btn-sm btn-success ms-3" href="<?= BASE_URL ?>/payment/index">View Plans</a>
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
