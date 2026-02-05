<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGSITS Gym – Premium Fitness</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
:root{
    --bhagua:#FF9933;
    --bhagua-dark:#E67E22;
    --glass:rgba(255,255,255,.88);
    --text-dark:#1e272e;
    --shadow:0 10px 36px rgba(0,0,0,.08);
}

body{
    font-family:'Inter',sans-serif;
    background:#f8f9fa;
    color:var(--text-dark);
}

/* ===== NAVBAR ===== */
.navbar{
    background:var(--glass)!important;
    backdrop-filter:blur(14px);
    box-shadow:var(--shadow);
    padding:14px 0;
    transition:.3s ease;
}

.navbar.scrolled{
    padding:8px 0;
    border-bottom:3px solid var(--bhagua);
}

/* ===== BRAND ===== */
.navbar-brand{
    display:flex;
    align-items:center;
    gap:12px;
    font-family:'Montserrat',sans-serif;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.3px;
    color:var(--text-dark)!important;
}

.navbar-brand img{
    height:46px;
    width:46px;
    border-radius:50%;
    border:2px solid var(--bhagua);
    padding:3px;
    background:#fff;
}

.brand-text{
    display:flex;
    flex-direction:column;
    line-height:1.05;
}

.brand-title{
    font-size:1rem;
}

.brand-gym{
    font-size:.65rem;
    font-weight:700;
    letter-spacing:2px;
    color:var(--bhagua);
}

/* ===== LINKS ===== */
.nav-link{
    color:var(--text-dark)!important;
    font-weight:600;
    font-size:.9rem;
    padding:10px 14px!important;
    border-radius:8px;
    transition:.2s;
}

.nav-link:hover{
    background:rgba(255,153,51,.12);
    color:var(--bhagua)!important;
}

/* ===== BUTTONS ===== */
.btn-bhagua{
    background:var(--bhagua);
    color:#fff!important;
    font-weight:700;
    font-size:.8rem;
    padding:10px 22px;
    border-radius:10px;
    border:none;
    box-shadow:0 6px 20px rgba(255,153,51,.35);
}

.btn-bhagua:hover{
    background:var(--bhagua-dark);
    transform:translateY(-2px);
}

.btn-outline-bhagua{
    border:2px solid var(--bhagua);
    color:var(--bhagua)!important;
    font-weight:700;
    padding:8px 20px;
    border-radius:10px;
}

.btn-outline-bhagua:hover{
    background:var(--bhagua);
    color:#fff!important;
}

/* ===== NOTIFICATIONS ===== */
.fa-bell{font-size:1.2rem;}
.badge-notification{
    position:absolute;
    top:0;
    right:6px;
    font-size:.65rem;
    border-radius:50%;
}
.notification-dropdown{
    width:320px;
    border-radius:14px;
    box-shadow:var(--shadow);
    border:none;
}
.notification-item{
    padding:12px 16px;
    display:block;
    border-bottom:1px solid #f1f1f1;
    text-decoration:none;
    color:inherit;
}
.notification-item:hover{
    background:#f8f9fa;
    color:var(--bhagua);
}

/* ===== MOBILE ===== */
@media(max-width:991px){
    .navbar-collapse{
        background:#fff;
        margin-top:16px;
        padding:18px;
        border-radius:18px;
        box-shadow:0 20px 50px rgba(0,0,0,.15);
    }
    .navbar-nav .nav-item{
        border-bottom:1px solid #eee;
        padding:6px 0;
    }
    .navbar-nav .nav-item:last-child{border-bottom:none;}
    .btn-bhagua,.btn-outline-bhagua{
        width:100%;
        margin-top:10px;
    }
    .brand-title{font-size:.9rem;}
    .brand-gym{font-size:.6rem;}
}
</style>
</head>

<body class="d-flex flex-column min-vh-100">

<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top">
<div class="container">

<!-- BRAND -->
<a class="navbar-brand" href="<?= BASE_URL ?>/home/index">
    <img src="https://image-static.collegedunia.com/public/college_data/images/logos/1408347301SGSITS_Indore.png" alt="SGSITS Logo">
    <div class="brand-text">
        <span class="brand-title">Gymnasium Legend</span>
        <span class="brand-gym">ESTABLISHED. 1986</span>
    </div>
</a>

<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto align-items-lg-center">

<li class="nav-item">
    <a class="nav-link" href="<?= BASE_URL ?>/home/index">Home</a>
</li>

<?php if (isset($_SESSION['role'])): ?>

<?php if ($_SESSION['role'] === 'admin'): ?>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
    </li>

    <li class="nav-item dropdown me-2">
        <a class="nav-link position-relative" data-bs-toggle="dropdown">
            <i class="fas fa-bell"></i>
            <span id="notificationBadge" class="badge bg-danger badge-notification d-none">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
            <h6 class="dropdown-header">Notifications</h6>
            <div id="notificationItems" class="text-center text-muted py-3">No new notifications</div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center small text-primary" href="<?= BASE_URL ?>/admin/payments">View All Payments</a>
        </div>
    </li>

<?php else: ?>

<li class="nav-item">
    <a class="nav-link" href="<?= BASE_URL ?>/user/dashboard">Dashboard</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="<?= BASE_URL ?>/profile/index">My Profile</a>
</li>

<li class="nav-item">
    <a class="nav-link fw-bold" style="color:var(--bhagua)!important" href="<?= BASE_URL ?>/payment/index">Membership</a>
</li>

<?php if ($attendanceAllowed): ?>
<?php if (!$attendanceMarkedToday): ?>
<li class="nav-item ms-lg-2">
    <button id="markAttendanceBtn" class="btn btn-bhagua">Mark Attendance</button>
</li>
<?php endif; ?>
<?php else: ?>
<li class="nav-item ms-lg-2">
    <button class="btn btn-secondary" disabled>Locked</button>
</li>
<?php endif; ?>

<?php endif; ?>

<li class="nav-item ms-lg-3">
    <a class="btn btn-outline-bhagua" href="<?= BASE_URL ?>/auth/logout">Logout</a>
</li>

<?php else: ?>

<li class="nav-item">
    <a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a>
</li>

<li class="nav-item ms-lg-2">
    <a class="btn btn-bhagua" href="<?= BASE_URL ?>/auth/register">Join Now</a>
</li>

<?php endif; ?>

</ul>
</div>
</div>
</nav>

<div style="margin-top:90px;"></div>

<script>
/* Scroll Animation – UNCHANGED */
window.addEventListener('scroll',()=>{
const nav=document.getElementById('mainNavbar');
nav.classList.toggle('scrolled',window.scrollY>30);
});
</script>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const badge = document.getElementById('notificationBadge');
    const itemsContainer = document.getElementById('notificationItems');
    const bellLink = badge.parentElement;
    const baseUrl = '<?= BASE_URL ?>';

    function fetchNotifications() {
        fetch(`${baseUrl}/admin/getNotifications`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update badge
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }

                    // Update items
                    if (data.notifications && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(n => {
                            const amount = parseFloat(n.amount).toFixed(2);
                            const message = `₹${amount} paid by ${n.user_name} for ${n.plan_name || 'Plan'}`;
                            html += `
                                <a href="${baseUrl}/admin/payments" class="notification-item">
                                    <div class="text-dark">${message}</div>
                                    <div class="small text-muted mt-1">${new Date(n.created_at).toLocaleString('en-IN', {dateStyle: 'short', timeStyle: 'short'})}</div>
                                </a>
                            `;
                        });
                        itemsContainer.innerHTML = html;
                        itemsContainer.classList.remove('text-center', 'text-muted', 'py-3');
                    } else {
                        itemsContainer.innerHTML = 'No new notifications';
                        itemsContainer.classList.add('text-center', 'text-muted', 'py-3');
                    }
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
    }

    // Mark as read when clicking the bell
    bellLink.addEventListener('click', function() {
        if (!badge.classList.contains('d-none')) {
            fetch(`${baseUrl}/admin/markNotificationsRead`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        badge.classList.add('d-none');
                        badge.textContent = '0';
                    }
                });
        }
    });

    // Initial fetch and poll every 30 seconds
    fetchNotifications();
    setInterval(fetchNotifications, 30000);
});
</script>
<?php endif; ?>
