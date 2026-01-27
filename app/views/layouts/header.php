<!DOCTYPE html>
<html>
<head>
    <title>Gym System</title>
</head>
<body>

<nav>
    <?php if (isset($_SESSION['role'])): ?>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>/admin/dashboard">Admin Dashboard</a>

        <?php elseif ($_SESSION['role'] === 'faculty'): ?>
            <a href="<?= BASE_URL ?>/user/dashboard">Faculty Dashboard</a>

        <?php elseif ($_SESSION['role'] === 'user'): ?>
            <a href="<?= BASE_URL ?>/user/dashboard">User Dashboard</a>

        <?php endif; ?>

        |
        <a href="<?= BASE_URL ?>/auth/logout">Logout</a>

    <?php else: ?>
        <a href="<?= BASE_URL ?>/auth/login">Login</a>
    <?php endif; ?>
</nav>

<hr>
