<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h4 class="mb-0">Faculty Profile</h4>
                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-light btn-sm">Edit Profile</a>
            </div>

            <div class="card-body">

                <!-- BASIC INFO -->
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                         style="width:100px;height:100px;font-size:2rem;">
                        <?= strtoupper(substr($profile['email'], 0, 1)) ?>
                    </div>

                    <h4>
                        <?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['middle_name'] ? $profile['middle_name'] . ' ' : '') . ($profile['last_name'] ?? '')) ?>
                    </h4>
                    <p class="text-muted mb-2"><?= htmlspecialchars($profile['email']) ?></p>
                    <?php if(!empty($profile['blood_group'])): ?>
                        <span class="badge bg-danger">Blood Group: <?= htmlspecialchars($profile['blood_group']) ?></span>
                    <?php endif; ?>
                </div>

                <hr>

                <!-- PERSONAL DETAILS -->
                <h5 class="text-primary">Personal Details</h5>
                <p><strong>Mobile:</strong> <?= htmlspecialchars($profile['mobile_number'] ?? '-') ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($profile['gender'] ?? '-') ?></p>
                <p><strong>Birth Date:</strong> <?= htmlspecialchars($profile['birth_date'] ?? '-') ?></p>

                <hr>

                <!-- FITNESS DETAILS -->
                <h5 class="text-primary">Fitness Details</h5>
                <p><strong>Height:</strong> <?= htmlspecialchars($profile['height_cm'] ?? '-') ?> cm</p>
                <p><strong>Weight:</strong> <?= htmlspecialchars($profile['weight_kg'] ?? '-') ?> kg</p>
                <p><strong>Waist:</strong> <?= htmlspecialchars($profile['waist_size'] ?? '-') ?> cm</p>
                <p><strong>BMI:</strong> <?= htmlspecialchars($profile['bmi_index'] ?? $bmi ?? '-') ?> 
                    <?php if(isset($bmiCategory)): ?>
                        <span class="badge bg-info text-dark ms-2"><?= $bmiCategory ?></span>
                    <?php endif; ?>
                </p>
                <p><strong>Fitness Goal:</strong> <?= htmlspecialchars($profile['fitness_goal'] ?? '-') ?></p>

                <hr>

                <!-- FACULTY ACADEMIC DETAILS -->
                <h5 class="text-primary">Academic / Professional Details</h5>
                <p><strong>Department:</strong> <?= htmlspecialchars($profile['department'] ?? '-') ?></p>
                <p><strong>Position:</strong> <?= htmlspecialchars($profile['position'] ?? '-') ?></p>
                <p><strong>Subject Expert:</strong> <?= htmlspecialchars($profile['subject_expert'] ?? '-') ?></p>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
