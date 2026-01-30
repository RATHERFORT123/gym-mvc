<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Faculty Profile</h4>
            </div>

            <div class="card-body">
                <form method="post" action="<?= BASE_URL ?>/profile/update">

                    <!-- BASIC INFO -->
                    <h5 class="text-secondary">Basic Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control"
                                   value="<?= $profile['first_name'] ?? '' ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control"
                                   value="<?= $profile['last_name'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control"
                               value="<?= $profile['mobile_number'] ?? '' ?>" required>
                    </div>

                    <hr>

                    <!-- FITNESS INFO -->
                    <h5 class="text-secondary">Fitness Details</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control"
                                   value="<?= $profile['height_cm'] ?? '' ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control"
                                   value="<?= $profile['weight_kg'] ?? '' ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Fitness Goal</label>
                            <input type="text" name="fitness_goal" class="form-control"
                                   value="<?= $profile['fitness_goal'] ?? '' ?>">
                        </div>
                    </div>

                    <hr>

                    <!-- FACULTY ACADEMIC INFO -->
                    <h5 class="text-secondary">Academic / Professional Information</h5>

                    <div class="mb-3">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control"
                               value="<?= $profile['department'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Position</label>
                        <input type="text" name="position" class="form-control"
                               value="<?= $profile['position'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Subject Expert</label>
                        <input type="text" name="subject_expert" class="form-control"
                               value="<?= $profile['subject_expert'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control"
                               value="<?= $profile['qualification'] ?? '' ?>">
                    </div>

                    <div class="mb-3">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control"
                               value="<?= $profile['experience_years'] ?? '' ?>">
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save Profile</button>
                        <a href="<?= BASE_URL ?>/profile/index" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
