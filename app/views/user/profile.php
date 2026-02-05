<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --bhagua-light: rgba(255, 153, 51, 0.1);
        --dark-surface: #1e272e;
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Inter', sans-serif;
    }

    .profile-wrapper {
        max-width: 1000px;
        margin: 2rem auto;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .main-card {
        border: none;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.08);
        background: white;
    }

    /* Top Profile Header */
    .profile-header {
        background: linear-gradient(135deg, var(--dark-surface) 0%, #2d3436 100%);
        padding: 60px 40px;
        text-align: center;
        position: relative;
        color: white;
    }

    .edit-btn-top {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 10px 20px;
        border-radius: 14px;
        font-weight: 600;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .edit-btn-top:hover {
        background: var(--bhagua);
        transform: scale(1.05);
        color: white;
        box-shadow: 0 10px 20px rgba(255, 153, 51, 0.3);
    }

    .avatar-container {
        width: 130px;
        height: 130px;
        background: var(--bhagua);
        border: 6px solid rgba(255,255,255,0.15);
        font-size: 3.5rem;
        font-weight: 800;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-family: 'Montserrat';
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .profile-name {
        font-family: 'Montserrat';
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .status-badges {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 15px;
    }

    /* Fitness Metric Tiles */
    .metrics-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding: 30px 40px;
        background: #fdfdfd;
        margin-top: -30px;
        position: relative;
        z-index: 2;
    }

    .metric-tile {
        background: white;
        border: 1px solid #f1f1f1;
        padding: 20px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        transition: 0.3s;
    }

    .metric-tile:hover {
        transform: translateY(-5px);
        border-color: var(--bhagua);
    }

    .metric-icon {
        font-size: 1.2rem;
        color: var(--bhagua);
        margin-bottom: 10px;
        background: var(--bhagua-light);
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 12px;
        display: inline-block;
    }

    .metric-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #888;
        display: block;
        margin-bottom: 5px;
    }

    .metric-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--dark-surface);
    }

    /* Data Sections */
    .details-section {
        padding: 20px 40px 40px;
    }

    .section-title {
        font-family: 'Montserrat';
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark-surface);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 3px solid var(--bhagua);
        display: inline-block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .info-item {
        background: #fcfcfc;
        padding: 15px 20px;
        border-radius: 16px;
        border-left: 4px solid #eee;
    }

    .info-label {
        display: block;
        font-size: 0.8rem;
        color: #888;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-text {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-surface);
    }

    .bmi-status {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
        .metrics-container { grid-template-columns: 1fr 1fr; padding: 20px; }
        .profile-header { padding: 40px 20px; }
    }
</style>

<div class="container profile-wrapper">
    <div class="main-card">
        
        <div class="profile-header">
            <a href="<?= BASE_URL ?>/profile/edit" class="edit-btn-top text-decoration-none">
                <i class="fas fa-user-edit me-2"></i>Edit Profile
            </a>
            
            <div class="avatar-container">
                <?= strtoupper(substr($profile['email'], 0, 1)) ?>
            </div>

            <h2 class="profile-name">
                <?= htmlspecialchars(($profile['first_name'] ?? '') . ' ' . ($profile['middle_name'] ? $profile['middle_name'] . ' ' : '') . ($profile['last_name'] ?? 'Guest')) ?>
            </h2>
            <p class="opacity-75 mb-0"><?= htmlspecialchars($profile['email']) ?></p>

            <div class="status-badges">
                <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-bullseye me-1 text-primary"></i> <?= htmlspecialchars($profile['fitness_goal'] ?? 'No Goal Set') ?>
                </span>
                <?php if(!empty($profile['blood_group'])): ?>
                    <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-tint me-1"></i> Blood Group: <?= htmlspecialchars($profile['blood_group']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="metrics-container">
            <div class="metric-tile">
                <div class="metric-icon"><i class="fas fa-ruler-vertical"></i></div>
                <span class="metric-label">Height</span>
                <span class="metric-value"><?= htmlspecialchars($profile['height_cm'] ?? '-') ?> <small>cm</small></span>
            </div>
            <div class="metric-tile">
                <div class="metric-icon"><i class="fas fa-weight"></i></div>
                <span class="metric-label">Weight</span>
                <span class="metric-value"><?= htmlspecialchars($profile['weight_kg'] ?? '-') ?> <small>kg</small></span>
            </div>
            <div class="metric-tile">
                <div class="metric-icon"><i class="fas fa-compress-alt"></i></div>
                <span class="metric-label">Waist</span>
                <span class="metric-value"><?= htmlspecialchars($profile['waist_size'] ?? '-') ?> <small>cm</small></span>
            </div>
            <div class="metric-tile">
                <div class="metric-icon"><i class="fas fa-heartbeat"></i></div>
                <span class="metric-label">BMI Score</span>
                <span class="metric-value"><?= $bmi ?? '-' ?></span>
                <div class="mt-2">
                    <?php if($bmi): ?>
                        <span class="bmi-status <?= $bmiCategory == 'Healthy Weight' ? 'bg-success text-white' : ($bmiCategory == 'Overweight' ? 'bg-warning text-dark' : 'bg-danger text-white') ?>">
                            <?= $bmiCategory ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        

        <div class="details-section">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="section-title">Academic Identity</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Enrollment No</span>
                            <span class="info-text"><?= htmlspecialchars($profile['enrollment_number'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Branch</span>
                            <span class="info-text"><?= htmlspecialchars($profile['branch'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Current Year</span>
                            <span class="info-text"><?= htmlspecialchars($profile['college_year'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Semester</span>
                            <span class="info-text"><?= htmlspecialchars($profile['semester'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <h5 class="section-title">Personal Information</h5>
                    <div class="info-grid">
                        <div class="info-item" style="border-left-color: var(--bhagua);">
                            <span class="info-label">Mobile Number</span>
                            <span class="info-text"><?= htmlspecialchars($profile['mobile_number'] ?? '-') ?></span>
                        </div>
                        <div class="info-item" style="border-left-color: var(--bhagua);">
                            <span class="info-label">Gender</span>
                            <span class="info-text"><?= htmlspecialchars($profile['gender'] ?? '-') ?></span>
                        </div>
                        <div class="info-item" style="border-left-color: var(--bhagua);">
                            <span class="info-label">Birth Date</span>
                            <span class="info-text"><?= htmlspecialchars($profile['birth_date'] ?? '-') ?></span>
                        </div>
                        <div class="info-item" style="border-left-color: var(--bhagua);">
                            <span class="info-label">Member Since</span>
                            <span class="info-text"><?= date('M Y') ?></span> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>