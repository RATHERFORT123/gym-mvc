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
<?php
$data = $profile ?? $user ?? [];
$role = $_SESSION['role'] ?? 'admin';
?>

<div class="container mt-5">
    <div class="row">
        
        <!-- User Stats Panel -->
       
  <div class="col-md-4">
    <div class="card shadow-lg mb-4 border-0">

        <div class="card-header bg-info text-dark text-center">
            <h5 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>
                <?= $role === 'admin' ? 'User Profile' : 'Your Profile' ?>
            </h5>
        </div>

        <div class="card-body">

            <!-- Avatar -->
            <div class="text-center mb-4">
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:70px;height:70px;font-size:1.8rem;">
                    <?= strtoupper(substr($data['email'] ?? 'U', 0, 1)) ?>
                </div>

                <h5 class="mb-0">
                    <?= $data['first_name'] ?? 'User' ?>
                    <?= $data['middle_name'] ?? '' ?>
                    <?= $data['last_name'] ?? '' ?>
                </h5>

                <small class="text-muted"><?= $data['email'] ?? '-' ?></small>
            </div>

            <!-- BASIC INFO -->
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Gender</span>
                    <span><?= $data['gender'] ?? '-' ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>DOB</span>
                    <span><?= $data['birth_date'] ?? '-' ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Blood Group</span>
                    <span><?= $data['blood_group'] ?? '-' ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Mobile</span>
                    <span><?= $data['mobile_number'] ?? '-' ?></span>
                </li>
            </ul>

            <!-- FITNESS -->
            <h6 class="text-uppercase text-muted small mb-2">
                <i class="fas fa-heartbeat me-1"></i>Fitness
            </h6>

            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Goal</span>
                    <span class="badge bg-warning text-dark">
                        <?= $data['fitness_goal'] ?? 'Not Set' ?>
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Height</span>
                    <span><?= $data['height_cm'] ?? '-' ?> cm</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Weight</span>
                    <span><?= $data['weight_kg'] ?? '-' ?> kg</span>
                </li>
            </ul>

            <!-- STUDENT -->
            <?php if (($data['enrollment_number'] ?? false)): ?>
                <h6 class="text-uppercase text-muted small mb-2">
                    <i class="fas fa-university me-1"></i>Academic
                </h6>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Enrollment</span>
                        <span><?= $data['enrollment_number'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Branch</span>
                        <span><?= $data['branch'] ?? '-' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Semester</span>
                        <span><?= $data['semester'] ?? '-' ?></span>
                    </li>
                </ul>
            <?php endif; ?>

            <!-- FACULTY -->
            <?php if (($data['employee_code'] ?? false)): ?>
                <h6 class="text-uppercase text-muted small mb-2">
                    <i class="fas fa-briefcase me-1"></i>Professional
                </h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Employee Code</span>
                        <span><?= $data['employee_code'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Department</span>
                        <span><?= $data['department'] ?? '-' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Position</span>
                        <span><?= $data['position'] ?? '-' ?></span>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Expertise</small><br>
                        <strong><?= $data['subject_expert'] ?? '-' ?></strong>
                    </li>
                </ul>
            <?php endif; ?>

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
                            <input type="text" name="plan_name" class="form-control" placeholder="e.g. Weight Loss Phase 1" value="<?= htmlspecialchars($existingPlan['plan_name'] ?? '') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($existingPlan['start_date'] ?? date('Y-m-d')) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($existingPlan['end_date'] ?? date('Y-m-d', strtotime('+30 days'))) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-warning">🏋️ Workout Plan</label>
                            <textarea name="workout_plan" class="form-control" rows="6" placeholder="- Mon: Chest & Triceps...&#10;- Tue: Back & Biceps..." required><?= htmlspecialchars($existingPlan['workout_plan'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-success">🥗 Diet Plan</label>
                            <textarea name="diet_plan" class="form-control" rows="6" placeholder="- Breakfast: Oats...&#10;- Lunch: Chicken Salad..." required><?= htmlspecialchars($existingPlan['diet_plan'] ?? '') ?></textarea>
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

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
    <?php if (isset($_SESSION['flash_error'])): ?>
        toastr.error("<?= htmlspecialchars($_SESSION['flash_error']) ?>");
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
