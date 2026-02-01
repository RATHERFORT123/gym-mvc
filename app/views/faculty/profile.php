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
                        <?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?>
                    </h4>

                    <p class="text-muted"><?= htmlspecialchars($profile['email']) ?></p>
                </div>

                <hr>

                <!-- PERSONAL DETAILS -->
                <h5 class="text-primary">Personal Details</h5>
                <p><strong>Mobile:</strong> <?= $profile['mobile_number'] ?? '-' ?></p> 

                <hr>

                <!-- FITNESS DETAILS -->
                <h5 class="text-primary">Fitness Details</h5>
                <p><strong>Height:</strong> <?= $profile['height_cm'] ?? '-' ?> cm</p>
                <p><strong>Weight:</strong> <?= $profile['weight_kg'] ?? '-' ?> kg</p>
                <p><strong>Fitness Goal:</strong> <?= $profile['fitness_goal'] ?? '-' ?></p>

                <hr>

                <!-- FACULTY ACADEMIC DETAILS -->
                <h5 class="text-primary">Academic / Professional Details</h5>
                <p><strong>Department:</strong> <?= $profile['department'] ?? '-' ?></p>
                <p><strong>Position:</strong> <?= $profile['position'] ?? '-' ?></p>
                <p><strong>Subject Expert:</strong> <?= $profile['subject_expert'] ?? '-' ?></p>
                <p><strong>Qualification:</strong> <?= $profile['qualification'] ?? '-' ?></p>
                <p><strong>Experience:</strong> <?= $profile['experience_years'] ?? '-' ?> years</p>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
