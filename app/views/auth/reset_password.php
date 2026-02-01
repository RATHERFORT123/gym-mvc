<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<div class="row justify-content-center">
    <div class="col-md-5">

        <h3 class="text-center mb-4">Reset Password</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            OTP has been sent to your email.
        </div>

        <form method="post" action="<?= BASE_URL ?>/auth/verifyResetOtp">

            <div class="mb-3">
                <label class="form-label">Enter OTP</label>
                <input type="number" name="otp" class="form-control" placeholder="6-digit OTP" required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    Reset Password
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
