<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="col-md-5">

        <div class="card shadow">
            <div class="card-header text-center bg-primary text-white">
                 <h3 class="mb-0">Member Login</h3>
            </div>
            
            <div class="card-body p-4">
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post">

                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        <div class="text-end mt-2">
                             <a href="<?= BASE_URL ?>/auth/forgotPassword" class="text-decoration-none small text-muted">Forgot Password?</a>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Login
                        </button>
                    </div>
                    
                    <div class="text-center text-muted">
                        Don't have an account? 
                        <a href="<?= BASE_URL ?>/auth/register" class="text-primary text-decoration-none">Register Here</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
