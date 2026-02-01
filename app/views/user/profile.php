<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">My Profile</h4>
                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-light btn-sm">Edit Profile</a>
            </div>
            <div class="card-body">
                
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                         <!-- Placeholder Avatar -->
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                            <?= strtoupper(substr($profile['email'], 0, 1)) ?>
                        </div>
                        <h3><?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? 'Guest')) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($profile['email']) ?></p>
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($profile['fitness_goal'] ?? 'No Goal Set') ?></span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <!-- Personal Info -->
                    <div class="col-md-6 mb-3">
                        <h5 class="text-primary">Personal Details</h5>
                        <p><strong>Mobile:</strong> <?= htmlspecialchars($profile['mobile_number'] ?? '-') ?></p>
                    </div>

                    <!-- Academic Info -->
                    <div class="col-md-6 mb-3">
                        <h5 class="text-primary">Academic Details</h5>
                        <p><strong>Year:</strong> <?= htmlspecialchars($profile['college_year'] ?? '-') ?></p>
                        <p><strong>Semester:</strong> <?= htmlspecialchars($profile['semester'] ?? '-') ?></p>
                        <p><strong>Branch:</strong> <?= htmlspecialchars($profile['branch'] ?? '-') ?></p>
                    </div>
                </div>

                <hr>

                <!-- Fitness Info -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-primary">Fitness Metrics</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Height (cm)</th>
                                        <th>Weight (kg)</th>
                                        <th>BMI</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= htmlspecialchars($profile['height_cm'] ?? '-') ?> cm</td>
                                        <td><?= htmlspecialchars($profile['weight_kg'] ?? '-') ?> kg</td>
                                        <td><strong><?= $bmi ?? '-' ?></strong></td>
                                        <td>
                                            <?php if($bmi): ?>
                                                <span class="badge 
                                                    <?= $bmiCategory == 'Healthy Weight' ? 'bg-success' : 
                                                       ($bmiCategory == 'Overweight' ? 'bg-warning' : 'bg-danger') ?>">
                                                    <?= $bmiCategory ?>
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
