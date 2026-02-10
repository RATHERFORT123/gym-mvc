<?php

class AuthController extends Controller
{
    // 🔐 Login
    public function login()
    {
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
             $redirect = ($_SESSION['role'] === 'admin') ? '/admin/dashboard' : '/user/dashboard';
             header("Location: " . BASE_URL . $redirect);
             exit;
        }

        // ✅ Always define error
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                // ✅ Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];

                // ✅ Role-based redirect or intended URL
                if (isset($_SESSION['intended_url'])) {
                    $redirectUrl = $_SESSION['intended_url'];
                    unset($_SESSION['intended_url']); // Clear it after use
                    header("Location: " . $redirectUrl);
                } elseif ($user['role'] === 'admin') {
                    header("Location: " . BASE_URL . "/admin/dashboard");
                } else {
                    // user + faculty
                    header("Location: " . BASE_URL . "/user/dashboard");
                }
                exit;
            }

            // ❌ Invalid login
            $error = "Invalid email or password";
        }

        // ✅ Load view safely
        $this->view('/auth/login', ['error' => $error]);
    }

    // 📝 Register
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
             $redirect = ($_SESSION['role'] === 'admin') ? '/admin/dashboard' : '/user/dashboard';
             header("Location: " . BASE_URL . $redirect);
             exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'] ?? '';
            // Validate Name (Alphabets only)
            if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
                $this->view('auth/register', ['error' => 'Name must contain only alphabets']);
                return;
            }

            // Check if email already exists
            if ($this->model('User')->findByEmail($_POST['email'] ?? '')) {
                $this->view('auth/register', ['error' => 'Email already registered. Please login.']);
                return;
            }

            $data = [
                'name'     => $_POST['name'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
                'role'     => $_POST['role'] ?? 'user' // user | faculty
            ];

            $this->model('User')->create($data);

            header("Location: " . BASE_URL . "/auth/login");
            exit;

        }

        $this->view('auth/register');
    }

    // 🚪 Logout
    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "/auth/login");
        exit;

        
    }
    public function sendOtp()
{
    // Check if email already exists
    $userModel = $this->model('User');
    if ($userModel->findByEmail($_POST['email'] ?? '')) {
        $this->view('auth/register', ['error' => 'Email already registered. Please login.']);
        return;
    }

    $otp = rand(100000, 999999);

    $_SESSION['register'] = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'role' => $_POST['role'],
        'otp' => $otp,
        'expiry' => time() + 300
    ];

    $_SESSION['otp_sent'] = true;

    Mailer::send(
        $_POST['email'],
        "Gym Registration OTP",
        "<h3>Your OTP: $otp</h3><p>Valid for 5 minutes</p>"
    );

    $this->view('auth/register', ['success' => 'OTP sent to your email']);
}

public function verifyOtp()
{
    $error = null;

    if ($_POST['otp'] == $_SESSION['register']['otp']
        && time() <= $_SESSION['register']['expiry']) {

        $userModel = $this->model('User');

        $userModel->create([
            'name' => $_SESSION['register']['name'],
            'email' => $_SESSION['register']['email'],
            'password' => $_SESSION['register']['password'],
            'role' => $_SESSION['register']['role'],
            'is_verified' => 1
        ]);

        // Auto-login
        $newUser = $userModel->findByEmail($_SESSION['register']['email']);
        if ($newUser) {
            $_SESSION['user_id'] = $newUser['id'];
            $_SESSION['role'] = $newUser['role'];
        }

        // Send Welcome Email
        $userName = $_SESSION['register']['name'] ?? 'Guest';
        $userEmail = $_SESSION['register']['email'];
        $subject = "Welcome to SGSIT Gym!";
        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #0d6efd;'>Welcome to the Family, $userName!</h2>
                <p>We are thrilled to have you join the <strong>SGSIT Gym</strong>. Your account has been successfully verified and created.</p>
                <p>You can now log in to:</p>
                <ul style='color: #444;'>
                    <li>Access your personalized Diet and Workout plans.</li>
                    <li>Mark your daily attendance via QR code.</li>
                    <li>Track your fitness progress and membership details.</li>
                </ul>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . BASE_URL . "/user/dashboard' style='background-color: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Go to My Dashboard</a>
                </div>
                <p style='color: #666;'>We're excited to be part of your fitness journey!</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin-top: 30px;'>
                <p style='font-size: 0.8rem; color: #999; text-align: center;'>SGSIT Gym Management System</p>
            </div>
        ";
        Mailer::send($userEmail, $subject, $message);

        unset($_SESSION['register'], $_SESSION['otp_sent']);

        // Redirect to profile edit to complete details
        header("Location: " . BASE_URL . "/profile/edit");
        exit;
    }

    $error = "Invalid or expired OTP";
    $this->view('auth/register', ['error' => $error]);
}
public function resendOtp()
{
    if (!isset($_SESSION['register'])) {
        header("Location: " . BASE_URL . "/auth/register");
        exit;
    }

    $otp = rand(100000, 999999);

    $_SESSION['register']['otp'] = $otp;
    $_SESSION['register']['expiry'] = time() + 300;

    Mailer::send(
        $_SESSION['register']['email'],
        "Gym Registration OTP (Resent)",
        "<h3>Your OTP: $otp</h3><p>Valid for 5 minutes</p>"
    );

    $_SESSION['otp_sent'] = true;

    $this->view('auth/register', ['success' => 'New OTP sent to your email']);
}
public function resetRegister()
{
    // Clear registration session data
    unset($_SESSION['register'], $_SESSION['otp_sent']);

    // Go back to register page
    header("Location: " . BASE_URL . "/auth/register");
    exit;
}

    // 🔑 Forgot Password - View
    public function forgotPassword()
    {
        $this->view('auth/forgot_password');
    }

    // 🔑 Forgot Password - Send OTP
    public function sendResetOtp()
    {
        $email = $_POST['email'] ?? '';
        $userModel = $this->model('User');
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->view('auth/forgot_password', ['error' => 'Email not found']);
            return;
        }

        $otp = rand(100000, 999999);

        $_SESSION['reset'] = [
            'email' => $email,
            'otp' => $otp,
            'expiry' => time() + 300
        ];

        $_SESSION['reset_otp_sent'] = true;

        Mailer::send(
            $email,
            "Password Reset OTP",
            "<h3>Your OTP: $otp</h3><p>Valid for 5 minutes</p>"
        );

        header("Location: " . BASE_URL . "/auth/resetPassword");
        exit;
    }

    // 🔑 Reset Password - View
    public function resetPassword()
    {
        if (!isset($_SESSION['reset_otp_sent'])) {
             header("Location: " . BASE_URL . "/auth/forgotPassword");
             exit;
        }
        $this->view('auth/reset_password');
    }

    // 🔑 Reset Password - Verify OTP & Update Password
    public function verifyResetOtp()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
             $this->resetPassword();
             return;
        }

        $otp = $_POST['otp'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($otp == $_SESSION['reset']['otp'] && time() <= $_SESSION['reset']['expiry']) {
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->model('User')->updatePassword($_SESSION['reset']['email'], $hashedPassword);

            unset($_SESSION['reset'], $_SESSION['reset_otp_sent']);

            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $this->view('auth/reset_password', ['error' => 'Invalid or expired OTP']);
    }

}
