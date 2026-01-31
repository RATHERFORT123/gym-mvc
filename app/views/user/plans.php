<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <h2 class="mb-4 text-primary">My Fitness Plan</h2>
        
        <?php if ($plan): ?>
            <div class="card shadow mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
        <span>
            <strong>Plan:</strong> <?= htmlspecialchars($plan['plan_name'] ?? 'Custom Plan') ?>
        </span>
        <small>
            Assigned by: <?= htmlspecialchars($plan['assigned_by']) ?>
        </small>
    </div>

    <div class="card-body">
        <div class="row">
            
            <!-- Workout Section -->
            <div class="col-md-6 border-end border-secondary">
                <h4 class="text-warning mb-3">🏋️ Workout Plan</h4>

                <div class="p-3 bg-dark rounded text-light text-start"
                     style="min-height: 200px;">
                    <?= nl2br(htmlspecialchars($plan['workout_plan'])) ?>
                </div>
            </div>

            <!-- Diet Section -->
            <div class="col-md-6">
                <h4 class="text-success mb-3">🥗 Diet Plan</h4>

                <div class="p-3 bg-dark rounded text-light text-start"
                     style="min-height: 200px;">
                    <?= nl2br(htmlspecialchars($plan['diet_plan'])) ?>
                </div>
            </div>

        </div>
    </div>

    <div class="card-footer text-white text-center">
        Plan Validity:
        <?= $plan['start_date'] ? date('d M Y', strtotime($plan['start_date'])) : 'N/A' ?>
        -
        <?= $plan['end_date'] ? date('d M Y', strtotime($plan['end_date'])) : 'N/A' ?>
    </div>
</div>


        <?php else: ?>
            <div class="alert alert-info text-center p-5">
                <h4>No Fitness Plan Assigned Yet</h4>
                <p>Your instructor is reviewing your profile and goals.</p>
                <p>Please complete your profile details (Height, Weight, Goal) to speed up the process.</p>
                <a href="<?= BASE_URL ?>/profile/index" class="btn btn-outline-primary">Check Profile</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
