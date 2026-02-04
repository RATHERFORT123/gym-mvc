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
        :root {
            --bhagua: #FF9933;
            --bhagua-dark: #E67E22;
            --white-glass: rgba(255, 255, 255, 0.75);
            --text-dark: #1e272e;
            --soft-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-dark);
        }

        /* ===== LIGHT GLASS NAVBAR ===== */
        .navbar {
            background: var(--white-glass) !important;
            backdrop-filter: blur(15px) saturate(150%);
            -webkit-backdrop-filter: blur(15px) saturate(150%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 0;
            transition: all 0.3s ease-in-out;
            box-shadow: var(--soft-shadow);
        }

        .navbar.scrolled {
            padding: 8px 0;
            background: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 2px solid var(--bhagua);
        }

        /* ===== BRANDING & LOGO ===== */
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dark) !important;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            border-radius: 50%;
            border: 2px solid var(--bhagua);
            padding: 2px;
            background: white;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.1) rotate(5deg);
        }

        .brand-gym {
            color: var(--bhagua);
        }

        /* ===== NAVIGATION LINKS ===== */
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0 8px;
            padding: 8px 15px !important;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 153, 51, 0.1);
            color: var(--bhagua) !important;
        }

        /* ===== BHAGUA ACTION BUTTONS ===== */
        .btn-bhagua {
            background: var(--bhagua);
            color: white !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 10px 22px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 153, 51, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-bhagua:hover {
            background: var(--bhagua-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 153, 51, 0.4);
        }

        .btn-outline-bhagua {
            border: 2px solid var(--bhagua);
            color: var(--bhagua) !important;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-outline-bhagua:hover {
            background: var(--bhagua);
            color: white !important;
        }

        /* ===== MOBILE MENU ===== */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                margin-top: 15px;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            }
            .nav-item {
                border-bottom: 1px solid #f1f1f1;
            }
            .btn-bhagua, .btn-outline-bhagua {
                width: 100%;
                margin: 10px 0;
            }
            .brand-gym {
  font-size: 0.6em;
  font-weight: 600;
  letter-spacing: 1px;
  vertical-align: baseline;
}

        }

        /* ===== NOTIFICATION BELL ===== */
        .nav-item.dropdown .fa-bell {
            font-size: 1.2rem;
            color: var(--text-dark);
            transition: color 0.3s;
        }
        .nav-item.dropdown:hover .fa-bell {
            color: var(--bhagua);
        }
        .badge-notification {
            position: absolute;
            top: 0;
            right: 5px;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 50%;
        }
        .notification-dropdown {
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 10px 0;
        }
        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f8f9fa;
            transition: background 0.2s;
            cursor: pointer;
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
            color: var(--bhagua);
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-item .time {
            font-size: 0.75rem;
            color: #adb5bd;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">

        <a class="navbar-brand" href="<?= BASE_URL ?>/home/index">
            <img src="https://image-static.collegedunia.com/public/college_data/images/logos/1408347301SGSITS_Indore.png" 
                 alt="SGSITS Logo">
            <span>
  Gymnasium Legend 
  <span class="brand-gym">1986</span>
</span>

        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/home/index">Home</a>
                </li>

                <?php if (isset($_SESSION['role'])): ?>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span id="notificationBadge" class="badge bg-danger badge-notification d-none">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" id="notificationList">
                                <h6 class="dropdown-header">Notifications</h6>
                                <div id="notificationItems">
                                    <div class="text-center py-3 text-muted">No new notifications</div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center small text-primary" href="<?= BASE_URL ?>/admin/payments">View All Payments</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/user/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/profile/index">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" style="color: var(--bhagua) !important;" href="<?= BASE_URL ?>/payment/index">
                                Membership
                            </a>
                        </li>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
                            <?php if ($attendanceAllowed): ?>
                                <?php if (!$attendanceMarkedToday): ?>
                                    <li class="nav-item ms-lg-2">
                                        <button id="markAttendanceBtn" class="btn btn-success btn-bhagua">
                                            Mark Attendance
                                        </button>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li class="nav-item ms-lg-2">
                                    <button class="btn btn-secondary btn-bhagua" style="background: #bdc3c7; box-shadow: none;" disabled>
                                        Locked
                                    </button>
                                </li>
                            <?php endif; ?>
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

<div style="margin-top: 30px;"></div>

<main class="flex-grow-1 container py-4">
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_info'])): ?>
        <div class="alert alert-info alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <?= $_SESSION['flash_info']; unset($_SESSION['flash_info']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

<script>
/* Scroll Animation */
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNavbar');
    if (window.scrollY > 30) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

/* Attendance Logic - 100% UNCHANGED */
document.getElementById('markAttendanceBtn')?.addEventListener('click', function () {
    fetch('<?= BASE_URL ?>/attendance/mark', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Attendance marked successfully!');
            document.getElementById('markAttendanceBtn').remove();
        } else if (data.status === 'no_subscription') {
            alert('You need an active subscription to mark attendance.');
            window.location = '<?= BASE_URL ?>/payment/index';
        } else if (data.status === 'already_marked') {
            alert('Attendance already marked today.');
        } else {
            alert('Unable to mark attendance.');
        }
    });
});

/* Notification Logic for Admin */
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
function fetchNotifications() {
    fetch('<?= BASE_URL ?>/admin/getNotifications')
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const badge = document.getElementById('notificationBadge');
            const itemsContainer = document.getElementById('notificationItems');
            
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
            
            if (data.total > 0) {
                let html = '';
                data.notifications.forEach(notif => {
                    html += `
                        <a href="<?= BASE_URL ?>/admin/payments" class="notification-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>New Payment</strong>
                                <span class="time">${notif.created_at}</span>
                            </div>
                            <div class="small text-truncate">
                                ${notif.user_name} paid ₹${parseFloat(notif.amount).toLocaleString()} for ${notif.plan_name}
                            </div>
                        </a>
                    `;
                });
                itemsContainer.innerHTML = html;
            } else {
                itemsContainer.innerHTML = '<div class="text-center py-3 text-muted">No new notifications</div>';
            }
        }
    })
    .catch(err => console.error('Error fetching notifications:', err));
}

// Mark as read when dropdown is opened
document.getElementById('notificationDropdown')?.addEventListener('show.bs.dropdown', function () {
    const badge = document.getElementById('notificationBadge');
    if (!badge.classList.contains('d-none')) {
        fetch('<?= BASE_URL ?>/admin/markNotificationsRead')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                badge.classList.add('d-none');
            }
        });
    }
});

// Fetch immediately and then every 30 seconds
fetchNotifications();
setInterval(fetchNotifications, 30000);
<?php endif; ?>
</script>

