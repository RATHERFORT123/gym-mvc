<?php

class AuthController extends Controller
{
    // 🔐 Login
    public function login()
    {
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

                // ✅ Role-based redirect
                if ($user['role'] === 'admin') {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

        $this->model('User')->create([
            'name' => $_SESSION['register']['name'],
            'email' => $_SESSION['register']['email'],
            'password' => $_SESSION['register']['password'],
            'role' => $_SESSION['register']['role'],
            'is_verified' => 1
        ]);

        unset($_SESSION['register'], $_SESSION['otp_sent']);

        header("Location: " . BASE_URL . "/auth/login");
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

}
