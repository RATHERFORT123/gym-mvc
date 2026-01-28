<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<div class="row justify-content-center">
    <div class="col-md-5">

        <h3 class="text-center mb-4">Forgot Password</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/auth/sendResetOtp">

            <div class="mb-3">
                <label class="form-label">Enter your Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    Send OTP
                </button>

                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-outline-secondary">
                    Back to Login
                </a>
            </div>

        </form>

    </div>
</div>

</body>
</html>
