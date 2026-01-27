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
}
