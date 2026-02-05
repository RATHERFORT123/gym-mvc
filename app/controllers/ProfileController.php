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
            if ($heightM > 0) {
                $bmi = $profile['weight_kg'] / ($heightM * $heightM);
                $bmi = number_format($bmi, 1);

                if ($bmi < 18.5) $bmiCategory = 'Underweight';
                elseif ($bmi < 24.9) $bmiCategory = 'Healthy Weight';
                elseif ($bmi < 29.9) $bmiCategory = 'Overweight';
                else $bmiCategory = 'Obese';
            }
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

            // Validate Names (Alphabets only)
            $fName = $_POST['first_name'] ?? '';
            $lName = $_POST['last_name'] ?? '';
            $mName = $_POST['middle_name'] ?? '';

            if (!preg_match("/^[a-zA-Z\s]+$/", $fName) || !preg_match("/^[a-zA-Z\s]+$/", $lName) || (!empty($mName) && !preg_match("/^[a-zA-Z\s]+$/", $mName))) {
                $_SESSION['error'] = "Names must contain only alphabets.";
                header("Location: " . BASE_URL . "/profile/edit");
                exit;
            }

            $role = $_SESSION['role'];

            // ==========================
            // FACULTY PROFILE DATA
            // ==========================
            if ($role === 'faculty') {

                $height = $_POST['height'] ?? '';
                $weight = $_POST['weight'] ?? '';
                $bmiIndex = null;

                if (!empty($height) && !empty($weight)) {
                    $heightM = $height / 100;
                    if ($heightM > 0) {
                        $bmiIndex = $weight / ($heightM * $heightM);
                        $bmiIndex = number_format($bmiIndex, 2);
                    }
                }

                $data = [
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'middle_name' => $_POST['middle_name'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'birth_date' => $_POST['birth_date'] ?? '',
                    'blood_group' => $_POST['blood_group'] ?? '',
                    'mobile_number' => $_POST['mobile_number'] ?? '',
                    'height' => $height,
                    'weight' => $weight,
                    'fitness_goal' => $_POST['fitness_goal'] ?? '',
                    'department' => $_POST['department'] ?? '',
                    'position' => $_POST['position'] ?? '',
                    'subject_expert' => $_POST['subject_expert'] ?? '',
                    'bmi_index' => $bmiIndex,
                    'waist_size' => $_POST['waist_size'] ?? '',
                    'employee_code' => $_POST['employee_code'] ?? ''
                ];
            }
            // ==========================
            // USER / STUDENT PROFILE DATA
            // ==========================
            else {

                $height = $_POST['height'] ?? '';
                $weight = $_POST['weight'] ?? '';
                $bmiIndex = null;

                if (!empty($height) && !empty($weight)) {
                    $heightM = $height / 100;
                    if ($heightM > 0) {
                        $bmiIndex = $weight / ($heightM * $heightM);
                        $bmiIndex = number_format($bmiIndex, 2);
                    }
                }

                $data = [
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'middle_name' => $_POST['middle_name'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'birth_date' => $_POST['birth_date'] ?? '',
                    'blood_group' => $_POST['blood_group'] ?? '',
                    'mobile_number' => $_POST['mobile_number'] ?? '',
                    'enrollment_number' => $_POST['enrollment_number'] ?? '',
                    'college_year' => $_POST['college_year'] ?? '',
                    'semester' => $_POST['semester'] ?? '',
                    'branch' => $_POST['branch'] ?? '',
                    'height' => $height,
                    'weight' => $weight,
                    'fitness_goal' => $_POST['fitness_goal'] ?? '',
                    'bmi_index' => $bmiIndex,
                    'waist_size' => $_POST['waist_size'] ?? ''
                ];
            }

            $this->model('User')->updateProfile(
                $_SESSION['user_id'],
                $data,
                $role
            );

            $_SESSION['flash_success'] = "Profile updated successfully!";
            header("Location: " . BASE_URL . "/profile/index");
            exit;
        }
    }
}
