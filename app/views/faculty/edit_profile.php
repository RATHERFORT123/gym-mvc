<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --bhagua-light: rgba(255, 153, 51, 0.1);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --text-dark: #1e272e;
        --input-border: #e2e8f0;
    }

    body {
        background-color: #f4f7f6;
        font-family: 'Inter', sans-serif;
        color: var(--text-dark);
    }

    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-card {
        background: var(--glass-bg);
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.06);
    }

    .card-header-premium {
        background: linear-gradient(135deg, #1e272e 0%, #2d3436 100%);
        padding: 40px;
        text-align: center;
        border: none;
        position: relative;
    }

    .card-header-premium h2 {
        color: var(--bhagua);
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .card-header-premium p {
        color: rgba(255,255,255,0.6);
        font-size: 0.9rem;
        margin: 0;
    }

    .form-section {
        padding: 30px 40px;
        border-bottom: 1px solid #f1f1f1;
    }

    .form-section:last-of-type { border-bottom: none; }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .section-title i {
        color: var(--bhagua);
        background: var(--bhagua-light);
        padding: 10px;
        border-radius: 12px;
        font-size: 1rem;
    }

    /* Modern Input Styling */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #636e72;
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        border: 2px solid var(--input-border);
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--bhagua);
        box-shadow: 0 0 0 4px var(--bhagua-light);
        outline: none;
    }

    /* Saffron Button Effect */
    .btn-save {
        background: var(--bhagua);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 14px;
        font-weight: 700;
        font-family: 'Montserrat';
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        box-shadow: 0 8px 20px rgba(255, 153, 51, 0.3);
    }

    .btn-save:hover {
        background: var(--bhagua-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(255, 153, 51, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #636e72;
        border: 2px solid #eee;
        padding: 14px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #eee;
        color: #2d3436;
    }

    hr { opacity: 0.05; margin: 0; }

    @media (max-width: 768px) {
        .form-section { padding: 25px 20px; }
        .card-header-premium { padding: 30px 20px; }
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

<div class="container profile-container">
    <div class="profile-card">
        <div class="mb-4 text-center">
    <form method="post" action="<?= BASE_URL ?>/profile/update" enctype="multipart/form-data" class="p-0">
    
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
        <div class="card-header-premium">
            <h2><i class="fas fa-id-card me-2"></i>Edit Profile</h2>
            <p>Maintain your fitness identity at SGSITS Gymnasium</p>
        </div>

       
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    <span>Basic Information</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" 
                               value="<?= $profile['first_name'] ?? '' ?>" required pattern="[A-Za-z\s]+" title="Only alphabets allowed" placeholder="Enter first name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" 
                               value="<?= $profile['middle_name'] ?? '' ?>" pattern="[A-Za-z\s]+" title="Only alphabets allowed" placeholder="Optional">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" 
                               value="<?= $profile['last_name'] ?? '' ?>" required pattern="[A-Za-z\s]+" title="Only alphabets allowed" placeholder="Enter last name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($profile['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($profile['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($profile['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birth Date</label>
                        <input type="date" name="birth_date" class="form-control" 
                               value="<?= $profile['birth_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            <?php 
                            $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                            foreach($groups as $g): ?>
                                <option value="<?= $g ?>" <?= ($profile['blood_group'] ?? '') == $g ? 'selected' : '' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Mobile Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-2 border-end-0" style="border-radius: 12px 0 0 12px;">+91</span>
                            <input type="text" name="mobile_number" class="form-control" 
                                   value="<?= $profile['mobile_number'] ?? '' ?>" required style="border-radius: 0 12px 12px 0;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section bg-light-subtle">
                <div class="section-title">
                    <i class="fas fa-dumbbell"></i>
                    <span>Fitness Biometrics</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Height (cm)</label>
                        <input type="number" step="0.01" name="height" class="form-control" 
                               value="<?= $profile['height_cm'] ?? '' ?>" placeholder="e.g. 175">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control" 
                               value="<?= $profile['weight_kg'] ?? '' ?>" placeholder="e.g. 70">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Waist (inch)</label>
                        <input type="number" step="0.01" name="waist_size" class="form-control" 
                               value="<?= $profile['waist_size'] ?? '' ?>" placeholder="Optional">
                    </div>
                    <div class="col-md-3">
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
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i>
                    <span>Professional Information</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Employee Code</label>
                        <input type="text" name="employee_code" class="form-control" 
                               value="<?= $profile['employee_code'] ?? '' ?>" placeholder="e.g. EMP001">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" 
                               value="<?= $profile['department'] ?? '' ?>" required placeholder="e.g. IT Department">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Position</label>
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
                    <div class="col-12">
                        <label class="form-label">Subject/Area Expertise</label>
                        <input type="text" name="subject_expert" class="form-control" 
                               value="<?= $profile['subject_expert'] ?? '' ?>" required placeholder="e.g. Data Structures, Maintenance">
                    </div>
                </div>
            </div>

            <div class="form-section bg-light d-flex flex-column gap-3">
                <button type="submit" class="btn btn-save w-100">
                    <i class="fas fa-save me-2"></i>Save Fitness Profile
                </button>
                <a href="<?= BASE_URL ?>/profile/index" class="btn btn-cancel text-center">
                    Cancel and Return
                </a>
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