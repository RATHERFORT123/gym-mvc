<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>User Dashboard</h2>

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
