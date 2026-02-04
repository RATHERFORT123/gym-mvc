<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Profile</h4>
            </div>

            <div class="card-body">
                <form method="post" action="<?= BASE_URL ?>/profile/update">

                    <!-- BASIC INFO -->
                    <h5 class="text-secondary">Basic Information</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control"
                                   value="<?= $profile['first_name'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                   value="<?= $profile['middle_name'] ?? '' ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control"
                                   value="<?= $profile['last_name'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($profile['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($profile['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= ($profile['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Birth Date</label>
                            <input type="date" name="birth_date" class="form-control"
                                   value="<?= $profile['birth_date'] ?? '' ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Blood Group</label>
                            <input type="text" name="blood_group" class="form-control"
                                   value="<?= $profile['blood_group'] ?? '' ?>" placeholder="e.g. A+">
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
                        <div class="col-md-3 mb-3">
                            <label>Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control"
                                   value="<?= $profile['height_cm'] ?? '' ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control"
                                   value="<?= $profile['weight_kg'] ?? '' ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Waist Size (cm)</label>
                            <input type="number" step="0.01" name="waist_size" class="form-control"
                                   value="<?= $profile['waist_size'] ?? '' ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fitness Goal</label>
                            <select name="fitness_goal" class="form-select" required>
                                <option value="">Select Goal</option>
                                <option value="Weight Loss" <?= ($profile['fitness_goal'] ?? '') == 'Weight Loss' ? 'selected' : '' ?>>Weight Loss</option>
                                <option value="Muscle Gain" <?= ($profile['fitness_goal'] ?? '') == 'Muscle Gain' ? 'selected' : '' ?>>Muscle Gain</option>
                                <option value="Weight Gain" <?= ($profile['fitness_goal'] ?? '') == 'Weight Gain' ? 'selected' : '' ?>>Weight Gain</option>
                                <option value="General Fitness" <?= ($profile['fitness_goal'] ?? '') == 'General Fitness' ? 'selected' : '' ?>>General Fitness</option>
                                <option value="Endurance" <?= ($profile['fitness_goal'] ?? '') == 'Endurance' ? 'selected' : '' ?>>Endurance</option>
                            </select>
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
                        <select name="position" class="form-select" required>
                            <option value="">Select Position</option>
                            <?php 
                            $positions = [
                                "Professor", "Associate Professor", "Assistant Professor",
                                "Assistant Grade 3", "Grade 4", "Technician",
                                "LDC (Lower Division Clerk)", "UDC (Upper Division Clerk)",
                                "Instructor", "Junior Instructor", "Attendant",
                                "Lab Technician", "Engineer", "Sub Engineer"
                            ];
                            foreach ($positions as $pos): ?>
                                <option value="<?= $pos ?>" <?= ($profile['position'] ?? '') == $pos ? 'selected' : '' ?>>
                                    <?= $pos ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Subject Expert</label>
                        <input type="text" name="subject_expert" class="form-control"
                               value="<?= $profile['subject_expert'] ?? '' ?>" required>
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
