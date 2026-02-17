<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --bhagua-light: rgb(255, 255, 255);
        --dark-surface: #ffffff;
        --glass-bg: rgb(255, 255, 255);
    }

    body {
        background-color: #ffffff;
        font-family: 'Inter', sans-serif;
    }

    .profile-wrapper {
        max-width: 900px;
        margin: 2rem auto;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .main-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgb(226, 216, 216);
        background: white;
    }

    /* Top Profile Header */
    .profile-header {
        background: white;
        padding: 50px 30px;
        text-align: center;
        position: relative;
        color: black;
    }

    .edit-btn-top {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgb(19, 19, 19);
        backdrop-filter: blur(10px);
        border: 1px solid rgb(20, 17, 17);
        color: white;
        padding: 8px 18px;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .edit-btn-top:hover {
        background: var(--bhagua);
        color: white;
        border-color: var(--bhagua);
    }

    .avatar-wrapper {
        width: 120px;
        height: 120px;
        background: var(--bhagua);
        border: 5px solid rgb(255, 255, 255);
        font-size: 3rem;
        font-weight: 800;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-family: 'Montserrat';
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .profile-name {
        font-family: 'Montserrat';
        font-weight: 800;
        margin-bottom: 5px;
    }

    /* Biometric Grid (Height/Weight/BMI) */
    .biometric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        padding: 25px 40px;
        background: #9c9c9c;
        border-bottom: 1px solid #3b3b3b;
    }

    .bio-box {
        background: white;
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 16px;
        text-align: center;
        transition: 0.3s;
    }

    .bio-box:hover {
        border-color: var(--bhagua);
        transform: translateY(-3px);
    }

    .bio-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #888;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .bio-value {
        font-size: 1.1rem;
        font-weight: 800;
        color: black;
    }

    /* Detail Sections */
    .section-container {
        padding: 30px 40px;
    }

    .section-title {
        font-family: 'Montserrat';
        font-weight: 700;
        font-size: 1rem;
        color: var(--bhagua);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #eee;
    }

    .info-row:last-child { border-bottom: none; }

    .info-label {
        color: #636e72;
        font-weight: 500;
    }

    .info-value {
        color: black;
        font-weight: 700;
    }

    .pro-details-card {
        background: var(--bhagua-light);
        border-radius: 18px;
        padding: 20px;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .biometric-grid { padding: 20px; grid-template-columns: 1fr 1fr; }
        .section-container { padding: 20px; }
    }
</style>

<div class="container profile-wrapper">
    <div class="main-card">
        <div class="profile-header">
            <a href="<?= BASE_URL ?>/profile/edit" class="edit-btn-top text-decoration-none">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <div class="avatar-container">
              <?php if (!empty($profile['profile_photo']) && file_exists(__DIR__ . "/../../../public/uploads/profile/" . $profile['profile_photo'])): ?>
                <img src="<?= BASE_URL ?>/public/uploads/profile/<?= htmlspecialchars($profile['profile_photo']) ?>"
                    class="rounded-circle shadow"
                    style="width:120px;height:120px;object-fit:cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                        style="width:120px;height:120px;font-size:2rem;">
                        <?= strtoupper(substr($profile['email'] ?? 'U', 0, 1)) ?>
                    </div>
            <?php endif; ?>
            
            <h2 class="profile-name">
                <?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['middle_name'] ? $profile['middle_name'] . ' ' : '') . ($profile['last_name'] ?? '')) ?>
            </h2>
            <p class="opacity-75 mb-3"><?= htmlspecialchars($profile['email']) ?></p>
  <?php if (!empty($profile['user_login_id'])): ?>
                <p class="opacity-75 mb-0"><?= htmlspecialchars($profile['user_login_id']) ?? '' ?></p>
            <?php endif; ?>
            <?php if(!empty($profile['blood_group'])): ?>
                <span class="badge bg-danger px-3 py-2 rounded-pill mb-2">
                    <i class="fas fa-tint me-1"></i> Blood Group: <?= htmlspecialchars($profile['blood_group']) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="biometric-grid">
            <div class="bio-box">
                <div class="bio-label">Height</div>
                <div class="bio-value"><?= htmlspecialchars($profile['height_cm'] ?? '-') ?> <small>cm</small></div>
            </div>
            <div class="bio-box">
                <div class="bio-label">Weight</div>
                <div class="bio-value"><?= htmlspecialchars($profile['weight_kg'] ?? '-') ?> <small>kg</small></div>
            </div>
            <div class="bio-box">
                <div class="bio-label">BMI Index</div>
                <div class="bio-value"><?= htmlspecialchars($profile['bmi_index'] ?? $bmi ?? '-') ?></div>
                <?php if(isset($bmiCategory)): ?>
                    <div class="badge bg-info text-dark mt-1" style="font-size: 0.65rem;"><?= $bmiCategory ?></div>
                <?php endif; ?>
            </div>
            <div class="bio-box">
                <div class="bio-label">Waist</div>
                <div class="bio-value"><?= htmlspecialchars($profile['waist_size'] ?? '-') ?> <small>cm</small></div>
            </div>
        </div>

        <div class="section-container">
            <div class="section-title">
                <i class="fas fa-id-card"></i> Personal Details
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Mobile</span>
                        <span class="info-value"><?= htmlspecialchars($profile['mobile_number'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender</span>
                        <span class="info-value"><?= htmlspecialchars($profile['gender'] ?? '-') ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">Birth Date</span>
                        <span class="info-value"><?= htmlspecialchars($profile['birth_date'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fitness Goal</span>
                        <span class="info-value text-success"><?= htmlspecialchars($profile['fitness_goal'] ?? '-') ?></span>
                    </div>
                </div>
            </div>

            <div class="section-title mt-5">
                <i class="fas fa-graduation-cap"></i> Professional Details
            </div>
            <div class="pro-details-card">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0 text-center text-md-start">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Department</small>
                        <span class="fw-bold"><?= htmlspecialchars($profile['department'] ?? '-') ?></span>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0 text-center text-md-start">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Current Position</small>
                        <span class="fw-bold"><?= htmlspecialchars($profile['position'] ?? '-') ?></span>
                    </div>
                    <div class="col-md-4 text-center text-md-start">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Expertise</small>
                        <span class="fw-bold"><?= htmlspecialchars($profile['subject_expert'] ?? '-') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-4"></div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>