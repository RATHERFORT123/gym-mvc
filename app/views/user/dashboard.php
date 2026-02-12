<?php include __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --bhagua: #FF9933;
        --bhagua-dark: #E67E22;
        --glass: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.4);
        --text-main: #1e272e;
    }

    body {
        background: #f0f2f5;
        font-family: 'Inter', sans-serif;
    }

    /* ===== DASHBOARD SLIDER ===== */
    .hero-swiper {
        width: 100%;
        height: 250px;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .swiper-slide {
        position: relative;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.7), transparent);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 40px;
        color: white;
    }

    .slide-overlay h2 { font-family: 'Montserrat'; font-weight: 800; color: var(--bhagua); }

    /* Event Slide Styles */
.event-slide {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1e272e 0%, #2f3640 50%, #0fbcf9 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 30px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-slide:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

.event-content {
    text-align: center;
    color: white;
    max-width: 600px;
    animation: fadeInUp 1s ease forwards;
}

/* Event Header (Icon + Title inline) */
.event-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px; /* space between icon and title */
    margin-bottom: 20px;
}

.event-icon {
    font-size: 3.5rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.event-content h2 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    color: #0fbcf9;
    font-size: 2rem;
    margin: 0; /* remove default margin so icon aligns perfectly */
    text-transform: uppercase;
    letter-spacing: 1px;
}

.event-content p {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 25px;
    color: rgba(255, 255, 255, 0.95);
}

.event-date {
    display: inline-block;
    background: rgba(255, 255, 255, 0.15);
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    transition: background 0.3s ease, transform 0.3s ease;
}

.event-date:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
}


    /* ===== UI CARDS & GLASS ===== */
    .dashboard-container { animation: fadeInUp 0.8s ease; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-panel {
        background: var(--glass);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.05);
    }

    /* Active Plan Card */
    .premium-status-card {
        background: linear-gradient(135deg, #1e272e 0%, #2f3640 100%);
        color: white;
        border-radius: 24px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .premium-status-card::after {
        content: '\f44b'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: -20px; bottom: -20px;
        font-size: 8rem; color: rgba(255,255,255,0.03); transform: rotate(-15deg);
    }

    .days-circle {
        width: 80px;
        height: 80px;
        border: 4px solid var(--bhagua);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255, 153, 51, 0.1);
    }

    /* Quick Action Tiles */
    .action-tile {
        background: white;
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #edf2f7;
        display: block;
        color: var(--text-main);
    }

    .action-tile:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--bhagua);
    }

    .tile-icon {
        width: 60px;
        height: 60px;
        background: #fff5eb;
        color: var(--bhagua);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 15px;
    }

    .btn-bhagua {
        background: var(--bhagua);
        color: white !important;
        font-weight: 700;
        border-radius: 12px;
        padding: 10px 20px;
        border: none;
        transition: 0.3s;
    }

    .btn-bhagua:hover { background: var(--bhagua-dark); transform: scale(1.05); }

</style>

<div class="container py-4 dashboard-container">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-0">Hello, Athlete! 👋</h2>
            <p class="text-muted">Welcome back to SGSITS Gym</p>
        </div>
    </div>

    <?php if (isset($isProfileComplete) && !$isProfileComplete): ?>
        <div class="alert alert-warning shadow-sm border-warning mb-4" role="alert">
            <h4 class="alert-heading"><i class="fas fa-user-edit me-2"></i>Profile Incomplete</h4>
            <p>Please complete your profile details (Height, Weight, Goal) to get personalized workout plans.</p>
            <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-warning text-dark fw-bold">Complete Profile Now</a>
        </div>
    <?php endif; ?>

    <?php if (!empty($paymentDeclined)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-danger mb-4" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Payment Declined</h4>
            <p>Your recent payment transaction was declined by the administrator.</p>
            <hr>
            <p class="mb-0"><strong>Reason:</strong> <?= htmlspecialchars($declinedReason ?? 'Verification Failed') ?></p>
            <!-- <div class="mt-3">
                <a href="<?= BASE_URL ?>/payment/index" class="btn btn-danger btn-sm">View Plans</a>
            </div> -->
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <?php if (!empty($activeEvents)): ?>
                <!-- Display Active Events -->
                <?php foreach ($activeEvents as $event): ?>
                    <div class="swiper-slide">
                        <div class="event-slide">
                            <div class="event-content">
                                <div class="event-header">
                                    <span class="event-icon">📢</span>
                                    <h2><?= htmlspecialchars($event['title']) ?></h2>
                                </div>
                                <p><?= htmlspecialchars($event['description'] ?? '') ?></p>
                                <small class="event-date">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    Posted on <?= date('M d, Y', strtotime($event['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default Slides -->
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop" alt="Gym">
                    <div class="slide-overlay">
                        <h2>Push Your Limits</h2>
                        <p>New supplements and equipment arriving this week.</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?q=80&w=1470&auto=format&fit=crop" alt="Workout">
                    <div class="slide-overlay">
                        <h2>Stay Consistent</h2>
                        <p>Track your attendance daily to win 'Member of the Month'.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <?php if (!empty($currentPlan)): ?>
        
        <?php if (is_int($daysLeft) && $daysLeft <= 3 && $daysLeft >= 0): ?>
            <div class="glass-panel p-3 mb-4 d-flex align-items-center justify-content-between animate__animated animate__headShake" style="border-left: 5px solid var(--bhagua);">
                <div class="d-flex align-items-center">
                    <div class="tile-icon mb-0 me-3"><i class="fas fa-hourglass-half"></i></div>
                    <div>
                        <strong class="d-block">Plan Expiring!</strong>
                        <small>Your <?= htmlspecialchars($currentPlan['plan_name']) ?> expires in <?= $daysLeft ?> days.</small>
                    </div>
                </div>
                <a class="btn btn-bhagua btn-sm" href="<?= BASE_URL ?>/payment/index?plan=<?= urlencode($currentPlan['plan_key']) ?>">Renew now</a>
            </div>

        <?php elseif (is_int($daysLeft) && $daysLeft < 0 && $currentPlan['payment_status'] !== 'pending'): ?>
            <div class="glass-panel p-3 mb-4 d-flex align-items-center justify-content-between" style="border-left: 5px solid #e74c3c;">
                <div class="d-flex align-items-center">
                    <div class="tile-icon mb-0 me-3 text-danger" style="background: #fee2e2;"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <strong class="d-block">Membership Expired</strong>
                        <small>Renew your plan to access gym features.</small>
                    </div>
                </div>
                <a class="btn btn-bhagua btn-sm" href="<?= BASE_URL ?>/payment/index">View Plans</a>
            </div>
        <?php endif; ?>

        <?php if ($currentPlan['payment_status'] == 'verified'): ?>
            <div class="premium-status-card mb-4 shadow-lg">
                <div class="row align-items-center">
                    <div class="col-8">
                        <span class="badge bg-success mb-2 px-3 py-2 rounded-pill">ACTIVE MEMBER</span>
                        <h3 class="fw-bold mb-1"><?= htmlspecialchars($currentPlan['plan_name']) ?></h3>
                        <p class="opacity-75 small mb-0"><i class="far fa-calendar-alt me-1"></i> Valid until <?= date('M d, Y', strtotime($currentPlan['end_date'])) ?></p>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <div class="days-circle">
                            <h4 class="fw-bold mb-0 text-white"><?= $daysLeft ?></h4>
                            <span style="font-size: 0.6rem; letter-spacing: 1px;">DAYS</span>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($currentPlan['payment_status'] == 'pending'): ?>
            <div class="glass-panel p-4 mb-4 text-center">
                <!-- <div class="spinner-border text-warning mb-3" role="status"></div> -->
                <h5 class="fw-bold">Payment Will verify By Admin</h5>
                <p class="text-muted small">We are verifying your UTR for <strong><?= htmlspecialchars($currentPlan['plan_name']) ?></strong>. Hang tight!</p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="glass-panel text-center py-5 mb-4 border-0">
            <div class="tile-icon mx-auto" style="width: 80px; height: 80px; font-size: 2rem;"><i class="fas fa-lock"></i></div>
            <h4 class="fw-bold">No Active Plan</h4>
            <p class="text-muted">Join the legend. Start your fitness journey today.</p>
            <a class="btn btn-bhagua px-4" href="<?= BASE_URL ?>/payment/index">Browse All Plans</a>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="<?= BASE_URL ?>/profile/index" class="action-tile shadow-sm">
                <div class="tile-icon"><i class="fas fa-user-ninja"></i></div>
                <h5 class="fw-bold">My Profile</h5>
                <p class="text-muted small mb-0">Edit your fitness bio & stats</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= BASE_URL ?>/plan/index" class="action-tile shadow-sm">
                <div class="tile-icon"><i class="fas fa-clipboard-check"></i></div>
                <h5 class="fw-bold">Workout & Diet</h5>
                <p class="text-muted small mb-0">View assigned training plans</p>
            </a>
        </div>
        <div class="col-md-4">
            <div class="action-tile shadow-sm opacity-75" style="cursor: default;">
                <div class="tile-icon"><i class="fas fa-calendar-day"></i></div>
                <h5 class="fw-bold"><a href="<?= BASE_URL ?>/attendance/myAttendance">My Attendance</a>
</h5>
                <p class="text-muted small mb-0">Log your daily gym presence</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-panel border-0 shadow-2xl" style="background: white; border-radius: 30px;">
      <div class="modal-body p-5 text-center">
        <div class="tile-icon mx-auto mb-4" style="width: 80px; height: 80px; background: #fff5eb; color: var(--bhagua); font-size: 2rem;">
            <i class="fas fa-user-edit"></i>
        </div>
        <h3 class="fw-bold mb-3">Almost There!</h3>
        <p class="text-muted">Please complete your profile details (BMI, Goals) so our instructors can create your custom plans.</p>
        <div class="d-grid gap-2 mt-4">
            <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-bhagua py-3">Complete Now</a>
            <button type="button" class="btn btn-link text-muted" id="btnLater">I'll do it later</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Slider
    const swiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: { delay: 4000 },
        pagination: { el: '.swiper-pagination', clickable: true },
    });

    // Profile Modal Logic (UNCHANGED)
    <?php if (!empty($showProfileAlert) && $showProfileAlert): ?>
        var myModal = new bootstrap.Modal(document.getElementById('profileModal'));
        myModal.show();

        document.getElementById('btnLater').addEventListener('click', function() {
            fetch('<?= BASE_URL ?>/user/dismissAlert', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(response => {
                myModal.hide();
            });
        });
    <?php endif; ?>
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>