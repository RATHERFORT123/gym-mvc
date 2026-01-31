<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Calculate BMI for reference
$bmi = 'N/A';
$bmiCategory = '';
if(!empty($user['height_cm']) && !empty($user['weight_kg'])) {
    $h = $user['height_cm'] / 100;
    $bmi = number_format($user['weight_kg'] / ($h * $h), 1);
     if ($bmi < 18.5) $bmiCategory = 'Underweight';
    elseif ($bmi < 24.9) $bmiCategory = 'Healthy';
    elseif ($bmi < 29.9) $bmiCategory = 'Overweight';
    else $bmiCategory = 'Obese';
}
?>

<div class="container mt-5">
    <div class="row">
        
        <!-- User Stats Panel -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-dark">
                    <h5 class="mb-0">User Profile</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                         <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <?= strtoupper(substr($user['email'], 0, 1)) ?>
                        </div>
                        <h5><?= $user['first_name'] ?? 'User' ?> <?= $user['last_name'] ?? '' ?></h5>
                        <small><?= $user['email'] ?></small>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-dark text-light d-flex justify-content-between">
                            <span>Goal:</span>
                            <span class="badge bg-warning text-dark"><?= $user['fitness_goal'] ?? 'Not Set' ?></span>
                        </li>
                        <li class="list-group-item bg-dark text-light d-flex justify-content-between">
                            <span>Height:</span>
                            <span><?= $user['height_cm'] ?? '-' ?> cm</span>
                        </li>
                        <li class="list-group-item bg-dark text-light d-flex justify-content-between">
                            <span>Weight:</span>
                            <span><?= $user['weight_kg'] ?? '-' ?> kg</span>
                        </li>
                        <li class="list-group-item bg-dark text-light d-flex justify-content-between">
                            <span>BMI:</span>
                            <span><strong><?= $bmi ?></strong> <small>(<?= $bmiCategory ?>)</small></span>
                        </li>
                         <li class="list-group-item bg-dark text-light">
                            <small>Branch: <?= $user['branch'] ?? '-' ?> | Sem: <?= $user['semester'] ?? '-' ?></small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Assignment Form -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Assign Diet & Workout Plan</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= BASE_URL ?>/admin/storePlan">
                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?? $user['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Plan Name</label>
                            <input type="text" name="plan_name" class="form-control" placeholder="e.g. Weight Loss Phase 1" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-warning">🏋️ Workout Plan</label>
                            <textarea name="workout_plan" class="form-control" rows="6" placeholder="- Mon: Chest & Triceps...&#10;- Tue: Back & Biceps..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">🥗 Diet Plan</label>
                            <textarea name="diet_plan" class="form-control" rows="6" placeholder="- Breakfast: Oats...&#10;- Lunch: Chicken Salad..." required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                             <button type="submit" class="btn btn-primary btn-lg">Assign Plan</button>
                             <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
