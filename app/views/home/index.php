<?php include __DIR__ . '/../layouts/header.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --dark-bg: #1e272e;
        --glass-white: rgba(255, 255, 255, 0.9);
        --text-main: #2d3436;
        --text-muted: #636e72;
    }

    body {
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
        background-color: #ffffff;
    }

    /* ======================
       HERO SECTION
    ====================== */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                    url("https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 100px 0;
    }

    .hero-badge {
        background: var(--bhagua);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        display: inline-block;
        margin-bottom: 20px;
    }

    .hero-section h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 25px;
    }

    .hero-section h1 span {
        color: var(--bhagua);
        display: block;
    }

    .hero-section .lead {
        font-size: 1.25rem;
        max-width: 600px;
        opacity: 0.9;
        margin-bottom: 40px;
    }

    .btn-premium {
        padding: 18px 40px;
        font-weight: 800;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-bhagua {
        background: var(--bhagua);
        color: white !important;
        border: none;
        box-shadow: 0 10px 20px rgba(255, 153, 51, 0.3);
    }

    .btn-bhagua:hover {
        background: var(--bhagua-dark);
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255, 153, 51, 0.4);
    }

    /* ======================
       FEATURES SECTION
    ====================== */
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        color: var(--dark-bg);
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: var(--bhagua);
        border-radius: 2px;
    }

    .feature-card {
        background: #fff;
        border: none;
        border-radius: 24px;
        padding: 40px 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        background: var(--bhagua-light);
        color: var(--bhagua);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border-radius: 18px;
        margin: 0 auto 25px;
        transition: 0.3s;
    }

    .feature-card:hover .icon-box {
        background: var(--bhagua);
        color: white;
    }

    /* ======================
       ABOUT SECTION
    ====================== */
    .about-image-container {
        position: relative;
        padding: 20px;
    }

    .about-image-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 80%;
        height: 80%;
        border: 10px solid var(--bhagua-light);
        z-index: 0;
        border-radius: 30px;
    }

    .about-img {
        position: relative;
        z-index: 1;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .stat-card {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 15px;
        border-left: 5px solid var(--bhagua);
    }

    .stat-number {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
        color: var(--bhagua);
        display: block;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .hero-section { text-align: center; }
        .hero-section .lead { margin-left: auto; margin-right: auto; }
    }
</style>

<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge">1986</span>
            <h1>GYMNASIUM LEGEND <span>1986</span></h1>
            <p class="lead">The ultimate fitness destination exclusively designed for the students and faculty of SGSITS Indore.</p>
            
            <div class="d-flex flex-wrap gap-3 mt-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php $dashboardLink = ($_SESSION['role'] === 'admin') ? '/admin/dashboard' : '/user/dashboard'; ?>
                    <a href="<?= BASE_URL . $dashboardLink ?>" class="btn btn-premium btn-bhagua btn-lg">
                        <i class="fas fa-th-large me-2"></i>Go to Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/register" class="btn btn-premium btn-bhagua btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Join the Club
                    </a>
                    <a href="<?= BASE_URL ?>/auth/login" class="btn btn-premium btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Member Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: #fcfdfe;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Campus Fitness Excellence</h2>
            <p class="text-muted">High-performance facilities tailored for your academic schedule.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h4>Modern Equipment</h4>
                    <p class="text-muted">Train with professional cardio and strength machines, maintained daily for peak campus performance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Personalized Nutrition</h4>
                    <p class="text-muted">Customized diet charts generated based on your real-time BMI and specific fitness goals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>Expert Coaching</h4>
                    <p class="text-muted">Certified trainers who understand the stress of engineering and provide the right guidance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="about-image-container">
                    <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?q=80&w=1375&auto=format&fit=crop" 
                         class="img-fluid about-img" alt="Gym Interior">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h6 class="text-uppercase fw-bold text-primary mb-2" style="color: var(--bhagua) !important;">Our Mission</h6>
                <h2 class="display-6 fw-bold mb-4">Fitness Meets Academics</h2>
                <p class="text-muted fs-5 mb-4">
                    SGSITS Gymnasium is dedicated to promoting a healthy lifestyle among future engineers. 
                    Strategically located on campus, we provide the physical outlet needed for mental excellence.
                </p>
                
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="stat-card">
                            <span class="stat-number">500+</span>
                            <span class="fw-bold text-dark">Active Athletes</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="stat-card">
                            <span class="stat-number">10+</span>
                            <span class="fw-bold text-dark">Expert Coaches</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="<?= BASE_URL ?>/auth/register" class="btn btn-premium btn-bhagua">Get Started Today</a>
                    <span class="text-muted d-none d-md-inline">|</span>
                    <p class="mb-0 text-muted d-none d-md-inline small">Join the community of campus legends.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>