<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <h1>Transform Your Body<br>At <span>SGSIT GYM</span></h1>
        <p class="lead text-light mb-4">The ultimate fitness destination for students and faculty of SGSIT Indore.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php $dashboardLink = ($_SESSION['role'] === 'admin') ? '/admin/dashboard' : '/user/dashboard'; ?>
            <a href="<?= BASE_URL . $dashboardLink ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/auth/register" class="btn btn-primary btn-lg">Join the Club</a>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section class="container py-5">
    <div class="row text-center mb-5">
        <div class="col-md-8 mx-auto">
            <h2 class="display-5 mb-3">Why Choose Us?</h2>
            <p class="text-white">We provide state-of-the-art facilities right inside the campus.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 p-4 text-center">
                <div class="card-body">
                    <div class="feature-icon">💪</div>
                    <h4>Modern Equipment</h4>
                    <p class="text-white">Train with the latest cardio and strength training equipment maintained for peak performance.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card h-100 p-4 text-center">
                <div class="card-body">
                    <div class="feature-icon">🥗</div>
                    <h4>Diet Plans</h4>
                    <p class="text-white">Get personalized diet charts based on your BMI and fitness goals (Weight Loss, Muscle Gain).</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card h-100 p-4 text-center">
                <div class="card-body">
                    <div class="feature-icon">🎓</div>
                    <h4>Expert Guidance</h4>
                    <p class="text-white">Certified trainers to guide students and faculty through their fitness journey.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5" style="background-color: #1a1a1a;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?q=80&w=1375&auto=format&fit=crop" class="img-fluid rounded shadow-lg border border-secondary" alt="Gym Interior">
            </div>
            <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
                <h2 class="mb-4">Fitness Meets Academics</h2>
                <p class="mb-4 text-white">
                    SGSIT Gym is dedicated to promoting a healthy lifestyle among future engineers and leaders. 
                    Located centrally within the campus, we offer flexible timings that fit your class schedule.
                </p>
                <div class="row">
                    <div class="col-6 mb-3">
                        <h3 class="text-white">500+</h3>
                        <small class="text-white">Active Students</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h3 class="text-white">10+</h3>
                        <small class="text-white">Certified Trainers</small>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-outline-light mt-3">Member Login</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
