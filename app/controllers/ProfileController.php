<?php

class ProfileController extends Controller
{
    public function index()
    {
        Auth::role(['user', 'faculty']);

        $userModel = $this->model('User');
        $profile = $userModel->getProfile($_SESSION['user_id']);

        // ==========================
        // BMI CALCULATION (user + faculty)
        // ==========================
        $bmi = null;
        $bmiCategory = '';

        if (!empty($profile['height_cm']) && !empty($profile['weight_kg'])) {
            $heightM = $profile['height_cm'] / 100;
            $bmi = $profile['weight_kg'] / ($heightM * $heightM);
            $bmi = number_format($bmi, 1);

            if ($bmi < 18.5) $bmiCategory = 'Underweight';
            elseif ($bmi < 24.9) $bmiCategory = 'Healthy Weight';
            elseif ($bmi < 29.9) $bmiCategory = 'Overweight';
            else $bmiCategory = 'Obese';
        }

        // ==========================
        // ROLE BASED VIEW
        // ==========================
        if ($_SESSION['role'] === 'faculty') {
            $this->view('faculty/profile', [
                'profile' => $profile,
                'bmi' => $bmi,
                'bmiCategory' => $bmiCategory
            ]);
        } else {
            $this->view('user/profile', [
                'profile' => $profile,
                'bmi' => $bmi,
                'bmiCategory' => $bmiCategory
            ]);
        }
    }

    public function edit()
    {
        Auth::role(['user', 'faculty']);

        $profile = $this->model('User')->getProfile($_SESSION['user_id']);

        // ==========================
        // ROLE BASED EDIT VIEW
        // ==========================
        if ($_SESSION['role'] === 'faculty') {
            $this->view('faculty/edit_profile', ['profile' => $profile]);
        } else {
            $this->view('user/edit_profile', ['profile' => $profile]);
        }
    }

    public function update()
    {
        Auth::role(['user', 'faculty']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $role = $_SESSION['role'];

            // ==========================
            // FACULTY PROFILE DATA
            // ==========================
            if ($role === 'faculty') {

                $data = [
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'mobile_number' => $_POST['mobile_number'] ?? '',
                    'height' => $_POST['height'] ?? '',
                    'weight' => $_POST['weight'] ?? '',
                    'fitness_goal' => $_POST['fitness_goal'] ?? '',
                    'department' => $_POST['department'] ?? '',
                    'position' => $_POST['position'] ?? '',
                    'subject_expert' => $_POST['subject_expert'] ?? '',
                    'qualification' => $_POST['qualification'] ?? '',
                    'experience_years' => $_POST['experience_years'] ?? ''
                ];
            }
            // ==========================
            // USER / STUDENT PROFILE DATA
            // ==========================
            else {

                $data = [
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'mobile_number' => $_POST['mobile_number'] ?? '',
                    'college_year' => $_POST['college_year'] ?? '',
                    'semester' => $_POST['semester'] ?? '',
                    'branch' => $_POST['branch'] ?? '',
                    'height' => $_POST['height'] ?? '',
                    'weight' => $_POST['weight'] ?? '',
                    'fitness_goal' => $_POST['fitness_goal'] ?? ''
                ];
            }

            $this->model('User')->updateProfile(
                $_SESSION['user_id'],
                $data,
                $role
            );

            header("Location: " . BASE_URL . "/profile/index");
            exit;
        }
    }
}
