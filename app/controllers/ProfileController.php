<?php

class ProfileController extends Controller
{
    public function index()
    {
        Auth::role(['user', 'faculty']); // Allow both
        
        $userModel = $this->model('User');
        $profile = $userModel->getProfile($_SESSION['user_id']);

        // Calculate BMI if height and weight exist
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

        $this->view('user/profile', [
            'profile' => $profile,
            'bmi' => $bmi,
            'bmiCategory' => $bmiCategory
        ]);
    }

    public function edit()
    {
        Auth::role(['user', 'faculty']);
        
        $userModel = $this->model('User');
        $profile = $userModel->getProfile($_SESSION['user_id']);

        $this->view('user/edit_profile', ['profile' => $profile]);
    }

    public function update()
    {
        Auth::role(['user', 'faculty']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
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

            $this->model('User')->updateProfile($_SESSION['user_id'], $data);
            
            header("Location: " . BASE_URL . "/profile/index");
            exit;
        }
    }
}
