<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
/* ======================
   THEME VARIABLES
====================== */
:root {
    --bg: #f6f8fb;
    --card: #ffffff;
    --text: #0f172a;
    --muted: #475569;
    --primary: #0072ff;
}

body.dark {
    --bg: #0b1220;
    --card: #111827;
    --text: #e5e7eb;
    --muted: #9ca3af;
}

/* ======================
   GLOBAL
====================== */
body {
    background: var(--bg);
    color: var(--text);
    transition: background .4s ease, color .4s ease;
    overflow-x: hidden;
}

/* ======================
   FLOATING SHAPES
====================== */
.shape {
    position: fixed;
    border-radius: 50%;
    filter: blur(60px);
    opacity: .45;
    z-index: -1;
    animation: float 12s infinite ease-in-out;
}
.shape.one {
    width: 260px;
    height: 260px;
    background: #00c6ff;
    top: 10%;
    left: -100px;
}
.shape.two {
    width: 320px;
    height: 320px;
    background: #7c3aed;
    bottom: 10%;
    right: -120px;
}
@keyframes float {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-40px); }
}

/* ======================
   ANIMATIONS
====================== */
@keyframes fadeUp {
    from { opacity:0; transform: translateY(40px); }
    to { opacity:1; transform: translateY(0); }
}

@keyframes shake {
    0%,100% { transform: translateX(0); }
    25% { transform: translateX(-6px); }
    75% { transform: translateX(6px); }
}

/* ======================
   WRAPPER
====================== */
.register-wrapper {
    margin-top: 80px;
    margin-bottom: 50px;
    animation: fadeUp .8s ease;
}

/* ======================
   CARD
====================== */
.card {
    background: var(--card);
    border-radius: 20px;
    border: none;
    box-shadow: 0 30px 70px rgba(0,0,0,.15);
    transition: background .4s ease;
}

.card-header {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    border: none;
}

.card-header h3 {
    font-weight: 800;
    letter-spacing: .5px;
}

.card-body p {
    color: var(--muted);
}

/* ======================
   FORM
====================== */
.form-label {
    font-weight: 600;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 12px 14px;
    transition: all .25s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(0,114,255,.15);
}

/* ======================
   PASSWORD TOGGLE
====================== */
.password-wrap {
    position: relative;
}
.toggle-pass {
    position: absolute;
    top: 67%;
    right: 14px;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 14px;
    color: var(--muted);
}

/* ======================
   OTP INPUT
====================== */
.otp-input {
    font-size: 1.6rem;
    letter-spacing: 6px;
    text-align: center;
    font-weight: 700;
}

/* ======================
   BUTTON
====================== */
.btn-primary {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    border: none;
    border-radius: 12px;
    font-weight: 700;
    padding: 14px;
    transition: transform .2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

/* ======================
   ALERT
====================== */
.alert-danger {
    border-radius: 12px;
    animation: shake .4s ease;
}
.alert-success {
    border-radius: 12px;
}

/* ======================
   THEME TOGGLE
====================== */
.theme-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--card);
    border-radius: 50px;
    padding: 8px 14px;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    font-size: 14px;
}

/* ======================
   MOBILE
====================== */
@media (max-width: 768px) {
    .register-wrapper { margin-top: 60px;   margin-left: 20px;
        margin-right: 20px;}
}
</style>

<!-- FLOATING SHAPES -->
<div class="shape one"></div>
<div class="shape two"></div>

<!-- THEME TOGGLE -->
<div class="theme-toggle" id="themeToggle">🌙 Dark</div>

<div class="row justify-content-center register-wrapper">
    <div class="col-md-6 col-sm-11">

        <div class="card">
            <div class="card-header text-center text-white">
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
                        <div class="mb-4">
                            <label class="form-label">
                                Enter OTP sent to your email
                            </label>
                            <input type="number" name="otp"
                                   class="form-control otp-input"
                                   placeholder="######" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Verify OTP
                            </button>
                        </div>
                    </form>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="<?= BASE_URL ?>/auth/resendOtp"
                           class="btn btn-link btn-sm">
                            Resend OTP
                        </a>
                        <a href="<?= BASE_URL ?>/auth/resetRegister"
                           class="btn btn-link btn-sm text-danger">
                            Change Email
                        </a>
                    </div>

                <?php else: ?>
                    <!-- REGISTRATION FORM -->
                    <form method="post" action="<?= BASE_URL ?>/auth/sendOtp">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3 password-wrap">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   id="password"
                                   class="form-control" required>
                            <span class="toggle-pass" id="togglePass">
                                Show
                            </span>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">I am a:</label>
                            <select name="role" class="form-select">
                                <option value="user">Student</option>
                                <option value="faculty">Faculty</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit"
                                    class="btn btn-primary btn-lg">
                                Send OTP
                            </button>
                        </div>

                        <div class="text-center">
                            Already have an account?
                            <a href="<?= BASE_URL ?>/auth/login"
                               class="text-primary text-decoration-none">
                                Login Here
                            </a>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<script>
/* PASSWORD TOGGLE */
const pass = document.getElementById('password');
const toggle = document.getElementById('togglePass');
if (toggle) {
    toggle.onclick = () => {
        pass.type = pass.type === 'password' ? 'text' : 'password';
        toggle.textContent = pass.type === 'password' ? 'Show' : 'Hide';
    };
}

/* THEME TOGGLE */
const body = document.body;
const themeBtn = document.getElementById('themeToggle');
themeBtn.onclick = () => {
    body.classList.toggle('dark');
    themeBtn.textContent = body.classList.contains('dark')
        ? '☀️ Light'
        : '🌙 Dark';
};
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
