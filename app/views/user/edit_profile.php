<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Profile</h4>
            </div>
            <div class="card-body">
                
                <form method="post" action="<?= BASE_URL ?>/profile/update">
                    
                    <!-- Read-Only Email -->
                    <div class="mb-3">
                        <label class="form-label">Email Address (Cannot be changed)</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($profile['email']) ?>" readonly disabled>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" value="<?= htmlspecialchars($profile['mobile_number'] ?? '') ?>" required>
                    </div>

                    <h5 class="text-secondary mt-3">Academic Info</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">College Year</label>
                            <select name="college_year" class="form-select" required>
                                <option value="">Select Year</option>
                                <option value="1" <?= ($profile['college_year'] ?? '') == '1' ? 'selected' : '' ?>>1st Year</option>
                                <option value="2" <?= ($profile['college_year'] ?? '') == '2' ? 'selected' : '' ?>>2nd Year</option>
                                <option value="3" <?= ($profile['college_year'] ?? '') == '3' ? 'selected' : '' ?>>3rd Year</option>
                                <option value="4" <?= ($profile['college_year'] ?? '') == '4' ? 'selected' : '' ?>>4th Year</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Semester</label>
                            <input type="number" name="semester" class="form-control" min="1" max="8" value="<?= htmlspecialchars($profile['semester'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Branch</label>
                            <input type="text" name="branch" class="form-control" placeholder="e.g. CS, IT" value="<?= htmlspecialchars($profile['branch'] ?? '') ?>" required>
                        </div>
                    </div>

                    <h5 class="text-secondary mt-3">Fitness Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control" value="<?= htmlspecialchars($profile['height_cm'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="<?= htmlspecialchars($profile['weight_kg'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
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
