<?php

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: /admin/dashboard");
                } else {
                    header("Location: /user/dashboard");
                }
                exit;
            }

            $error = "Invalid login";
        }

        $this->view('auth/login', compact('error'));
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role'] // user | faculty
            ];

            $this->model('User')->create($data);
            header("Location: /auth/login");
            exit;
        }

        $this->view('auth/register');
    }

    public function logout()
    {
        session_destroy();
        header("Location: /auth/login");
    }
}
