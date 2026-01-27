<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>User Dashboard</h2>

<p>Role: <?= $_SESSION['role'] ?></p>

<ul>
    <li>Profile</li>
    <li>Workout Plan</li>
    <li>Diet Plan</li>
    <li>Attendance</li>
</ul>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
