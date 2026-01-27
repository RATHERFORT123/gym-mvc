<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<div class="row justify-content-center">
<div class="col-md-6">

<h3 class="text-center mb-4">User Registration</h3>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if (empty($_SESSION['otp_sent'])): ?>

<form method="post" action="<?= BASE_URL ?>/auth/sendOtp">

    <div class="mb-3">
        <label>Name</label>
        <input class="form-control" name="name" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select class="form-control" name="role">
            <option value="user">User</option>
            <option value="faculty">Faculty</option>
        </select>
    </div>

    <button class="btn btn-primary w-100">Send OTP</button>
</form>

<?php else: ?>

<form method="post" action="<?= BASE_URL ?>/auth/verifyOtp">

    <div class="mb-3">
        <label>Enter OTP</label>
        <input class="form-control" name="otp" required>
    </div>

    <button class="btn btn-success w-100">Verify OTP</button>
</form>

<div class="text-center mt-3">
    <a href="<?= BASE_URL ?>/auth/resendOtp">Resend OTP</a> |
    <a href="<?= BASE_URL ?>/auth/resetRegister">Change Email</a>
</div>

<?php endif; ?>

<hr>

<div class="text-center">
    Already have an account?
    <a href="<?= BASE_URL ?>/auth/login">Login</a>
</div>

</div>
</div>

</body>
<script>
document.querySelectorAll('form').forEach(f => {
    f.addEventListener('submit', () => {
        f.querySelector('button').disabled = true;
    });
});
</script>

</html>
