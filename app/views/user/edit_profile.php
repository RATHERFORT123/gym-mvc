<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --bhagua-light: rgba(255, 153, 51, 0.1);
        --dark-charcoal: #1e272e;
        --input-focus: #ff993344;
        --glass-bg: rgba(255, 255, 255, 0.98);
    }

    body {
        background-color: #f3f4f7;
        font-family: 'Inter', sans-serif;
    }

    .edit-profile-wrapper {
        max-width: 950px;
        margin: 40px auto;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: var(--glass-bg);
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.07);
    }

    /* Header Section */
    .header-banner {
        background: linear-gradient(135deg, var(--dark-charcoal) 0%, #2c3e50 100%);
        padding: 40px;
        text-align: center;
        color: white;
        position: relative;
    }

    .header-banner h2 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--bhagua);
        margin-bottom: 5px;
    }

    .header-banner p {
        font-size: 0.9rem;
        opacity: 0.7;
        margin-bottom: 0;
    }

    /* Form Sectioning */
    .form-group-section {
        padding: 35px 45px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-group-section:last-child {
        border-bottom: none;
    }

    .section-headline {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 30px;
    }

    .section-headline i {
        background: var(--bhagua-light);
        color: var(--bhagua);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.1rem;
    }

    .section-headline span {
        font-weight: 700;
        color: var(--dark-charcoal);
        font-size: 1.1rem;
    }

    /* Input Styling */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #57606f;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-control, .form-select {
        border: 2px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background-color: #fcfdfe;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--bhagua);
        box-shadow: 0 0 0 4px var(--input-focus);
        background-color: #fff;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #a0aec0;
        cursor: not-allowed;
        border-style: dashed;
    }

    /* Action Buttons */
    .btn-save-profile {
        background: var(--bhagua);
        border: none;
        color: white;
        padding: 16px 30px;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px rgba(255, 153, 51, 0.3);
    }

    .btn-save-profile:hover {
        background: var(--bhagua-dark);
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(255, 153, 51, 0.4);
        color: white;
    }

    .btn-cancel-profile {
        padding: 14px;
        border-radius: 14px;
        font-weight: 600;
        color: #718096;
        transition: 0.3s;
    }

    .btn-cancel-profile:hover {
        background: #f8f9fa;
        color: var(--dark-charcoal);
    }

    @media (max-width: 768px) {
        .form-group-section { padding: 25px 20px; }
        .header-banner { padding: 30px 20px; }
    }
    .profile-photo-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
}

.profile-photo-img,
.profile-photo-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--bhagua);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.profile-photo-placeholder {
    background: #2c3e50;
    color: white;
    font-size: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-edit-icon {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--bhagua);
    color: white;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.photo-edit-icon:hover {
    background: var(--bhagua-dark);
    transform: scale(1.1);
}

</style>

<div class="mb-4 text-center">
    <form method="post" action="<?= BASE_URL ?>/profile/update" enctype="multipart/form-data">
    
    <label class="form-label d-block mb-3">Profile Photo</label>

    <div class="profile-photo-wrapper mx-auto">
        <?php if (!empty($profile['profile_photo'])): ?>
            <img id="photoPreview"
                 src="<?= BASE_URL ?>/public/uploads/profile/<?= htmlspecialchars($profile['profile_photo']) ?>"
                 class="profile-photo-img">
        <?php else: ?>
            <div id="photoPreview" class="profile-photo-placeholder">
                <?= strtoupper(substr($profile['email'] ?? 'U', 0, 1)) ?>
            </div>
        <?php endif; ?>

        <label for="profile_photo" class="photo-edit-icon">
            <i class="fas fa-camera"></i>
        </label>

        <input type="file" name="profile_photo" id="profile_photo"
               accept="image/png,image/jpeg,image/jpg" hidden>
    </div>

</div>


        <div class="header-banner">
            <h2><i class="fas fa-user-circle me-2"></i>Edit Profile</h2>
            <p>Update your personal, academic, and fitness metrics</p>
        </div>

        <!-- <form method="post" action="<?= BASE_URL ?>/profile/update"> -->
            
            <div class="form-group-section">
                <div class="section-headline">
                    <i class="fas fa-id-badge"></i>
                    <span>Primary Identity</span>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-envelope-open text-muted"></i> Registered Email</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" readonly disabled>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
                        <?php if (isset($errors['first_name'])): ?><div class="invalid-feedback"><?= $errors['first_name'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control <?= isset($errors['middle_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['middle_name'] ?? '') ?>">
                        <?php if (isset($errors['middle_name'])): ?><div class="invalid-feedback"><?= $errors['middle_name'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" required>
                        <?php if (isset($errors['last_name'])): ?><div class="invalid-feedback"><?= $errors['last_name'] ?></div><?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($profile['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($profile['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($profile['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <?php if (isset($errors['gender'])): ?><div class="invalid-feedback"><?= $errors['gender'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control <?= isset($errors['birth_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['birth_date'] ?? '') ?>" required>
                        <?php if (isset($errors['birth_date'])): ?><div class="invalid-feedback"><?= $errors['birth_date'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select <?= isset($errors['blood_group']) ? 'is-invalid' : '' ?>" required>
                            <option value="">Select</option>
                            <?php foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg): ?>
                                <option value="<?= $bg ?>" <?= ($profile['blood_group'] ?? '') == $bg ? 'selected' : '' ?>><?= $bg ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['blood_group'])): ?><div class="invalid-feedback"><?= $errors['blood_group'] ?></div><?php endif; ?>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <label class="form-label"><i class="fas fa-phone-alt"></i> Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control <?= isset($errors['mobile_number']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['mobile_number'] ?? '') ?>" required>
                        <?php if (isset($errors['mobile_number'])): ?><div class="invalid-feedback"><?= $errors['mobile_number'] ?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group-section bg-light-subtle">
                <div class="section-headline">
                    <i class="fas fa-university"></i>
                    <span>College Records</span>
                </div>

                <div class="mb-4">
                    <label class="form-label">Enrollment Number</label>
                    <input type="text" name="enrollment_number" class="form-control <?= isset($errors['enrollment_number']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['enrollment_number'] ?? '') ?>" placeholder="e.g. 0801CS211001" required>
                    <?php if (isset($errors['enrollment_number'])): ?><div class="invalid-feedback"><?= $errors['enrollment_number'] ?></div><?php endif; ?>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">College Year</label>
                        <select name="college_year" class="form-select <?= isset($errors['college_year']) ? 'is-invalid' : '' ?>" required>
                            <option value="">Select Year</option>
                            <?php for($i=1; $i<=4; $i++): ?>
                                <option value="<?= $i ?>" <?= ($profile['college_year'] ?? '') == $i ? 'selected' : '' ?>><?= $i . ($i==1?'st':($i==2?'nd':($i==3?'rd':'th'))) ?> Year</option>
                            <?php endfor; ?>
                        </select>
                        <?php if (isset($errors['college_year'])): ?><div class="invalid-feedback"><?= $errors['college_year'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control <?= isset($errors['semester']) ? 'is-invalid' : '' ?>" min="1" max="8" value="<?= htmlspecialchars($profile['semester'] ?? '') ?>" required>
                        <?php if (isset($errors['semester'])): ?><div class="invalid-feedback"><?= $errors['semester'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <input type="text" name="branch" class="form-control <?= isset($errors['branch']) ? 'is-invalid' : '' ?>" placeholder="e.g. CS, IT" value="<?= htmlspecialchars($profile['branch'] ?? '') ?>" required>
                        <?php if (isset($errors['branch'])): ?><div class="invalid-feedback"><?= $errors['branch'] ?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group-section">
                <div class="section-headline">
                    <i class="fas fa-heartbeat"></i>
                    <span>Fitness Biometrics</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Height (cm)</label>
                        <input type="number" step="0.01" name="height" class="form-control <?= isset($errors['height']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['height_cm'] ?? '') ?>" required>
                        <?php if (isset($errors['height'])): ?><div class="invalid-feedback"><?= $errors['height'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control <?= isset($errors['weight']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['weight_kg'] ?? '') ?>" required>
                        <?php if (isset($errors['weight'])): ?><div class="invalid-feedback"><?= $errors['weight'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Waist (cm)</label>
                        <input type="number" step="0.01" name="waist_size" class="form-control <?= isset($errors['waist_size']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($profile['waist_size'] ?? '') ?>" required>
                        <?php if (isset($errors['waist_size'])): ?><div class="invalid-feedback"><?= $errors['waist_size'] ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fitness Goal</label>
                        <select name="fitness_goal" class="form-select <?= isset($errors['fitness_goal']) ? 'is-invalid' : '' ?>" required>
                            <option value="">Select Goal</option>
                            <option value="Weight Loss" <?= ($profile['fitness_goal'] ?? '') == 'Weight Loss' ? 'selected' : '' ?>>Weight Loss</option>
                            <option value="Muscle Gain" <?= ($profile['fitness_goal'] ?? '') == 'Muscle Gain' ? 'selected' : '' ?>>Muscle Gain</option>
                            <option value="Weight Gain" <?= ($profile['fitness_goal'] ?? '') == 'Weight Gain' ? 'selected' : '' ?>>Weight Gain</option>
                            <option value="General Fitness" <?= ($profile['fitness_goal'] ?? '') == 'General Fitness' ? 'selected' : '' ?>>General Fitness</option>
                            <option value="Endurance" <?= ($profile['fitness_goal'] ?? '') == 'Endurance' ? 'selected' : '' ?>>Endurance</option>
                        </select>
                        <?php if (isset($errors['fitness_goal'])): ?><div class="invalid-feedback"><?= $errors['fitness_goal'] ?></div><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group-section bg-light text-center">
                <div class="d-grid gap-3 col-lg-6 mx-auto">
                    <button type="submit" class="btn btn-save-profile">
                        <i class="fas fa-check-circle me-2"></i>Save Fitness Profile
                    </button>
                    <a href="<?= BASE_URL ?>/profile/index" class="btn btn-cancel-profile text-decoration-none">
                        Cancel Changes
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        const preview = document.getElementById('photoPreview');

        if (preview.tagName === "DIV") {
            const img = document.createElement("img");
            img.id = "photoPreview";
            img.className = "profile-photo-img";
            img.src = event.target.result;
            preview.replaceWith(img);
        } else {
            preview.src = event.target.result;
        }
    };

    reader.readAsDataURL(file);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>