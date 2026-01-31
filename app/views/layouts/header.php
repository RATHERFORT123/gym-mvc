<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGSIT Gym - Premium Fitness</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/public/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/home/index">
        ⚡ SGSIT GYM
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        ...
      </ul>
    </div>
  </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="margin-top: 80px;"></div>

<!-- 🔥 MAIN CONTENT START -->
<main class="flex-grow-1 container py-4">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/home/index">
        ⚡ SGSIT GYM
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>/home/index">Home</a>
        </li>

        <?php if (isset($_SESSION['role'])): ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
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
                    <a class="nav-link text-warning fw-semibold" href="<?= BASE_URL ?>/payment/index">
                        Membership
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'): ?>
                    <?php if ($attendanceAllowed): ?>
                        <?php if (!$attendanceMarkedToday): ?>
                            <li class="nav-item">
                                <button
                                    id="markAttendanceBtn"
                                    class="btn btn-success btn-sm mt-1">
                                    Mark Attendance
                                </button>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <button class="btn btn-secondary btn-sm mt-1" disabled title="No active subscription">Mark Attendance</button>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endif; ?>

            <li class="nav-item ms-2">
                <a class="btn btn-outline-light btn-sm mt-1" href="<?= BASE_URL ?>/auth/logout">Logout</a>
            </li>

        <?php else: ?>
            <li class="nav-item ms-2">
                <a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary ms-2" href="<?= BASE_URL ?>/auth/register">Join Now</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="margin-top: 80px;"></div>

<script>
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
</script>
