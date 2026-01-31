<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center" style="margin-top: 80px; margin-bottom: 50px;">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-header text-center bg-primary text-white">
                 <h3 class="mb-0">Create Account</h3>
            </div>
            
            <div class="card-body p-4">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['otp_sent'])): ?>
                    <!-- OTP VERIFICATION FORM -->
                     <form method="post" action="<?= BASE_URL ?>/auth/verifyOtp">
                        <div class="mb-3">
                            <label class="form-label">Enter OTP sent to your email</label>
                            <input type="number" name="otp" class="form-control text-center" style="font-size: 1.5rem; letter-spacing: 5px;" placeholder="######" required>
                        </div>
                         <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Verify OTP</button>
                        </div>
                    </form>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <a href="<?= BASE_URL ?>/auth/resendOtp" class="btn btn-link btn-sm">Resend OTP</a>
                        <a href="<?= BASE_URL ?>/auth/resetRegister" class="btn btn-link btn-sm text-danger">Change Email</a>
                    </div>

                <?php else: ?>
                    <!-- REGISTRATION FORM -->
                    <form method="post" action="<?= BASE_URL ?>/auth/sendOtp">
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">I am a:</label>
                            <select name="role" class="form-select">
                                <option value="user">Student</option>
                                <option value="faculty">Faculty</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">Send OTP</button>
                        </div>

                        <div class="text-center text-white">
                            Already have an account? 
                            <a href="<?= BASE_URL ?>/auth/login" class="text-primary text-decoration-none">Login Here</a>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
