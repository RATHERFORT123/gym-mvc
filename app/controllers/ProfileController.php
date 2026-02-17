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

            $errors = [];
            $data = $_POST; // Capture all input to repopulate form
            
            // Map form fields to DB columns for view compatibility
            $data['height_cm'] = $_POST['height'] ?? '';
            $data['weight_kg'] = $_POST['weight'] ?? '';

            // Validate Names (Alphabets only)
            $fName = $_POST['first_name'] ?? '';
            $lName = $_POST['last_name'] ?? '';
            $mName = $_POST['middle_name'] ?? '';

            if (!preg_match("/^[a-zA-Z\s]+$/", $fName) || !preg_match("/^[a-zA-Z\s]+$/", $lName) || (!empty($mName) && !preg_match("/^[a-zA-Z\s]+$/", $mName))) {
                $errors['first_name'] = "Names must contain only alphabets.";
            }

            // Common Required Fields Validation
            $commonRequired = [
                'gender' => 'Gender',
                'birth_date' => 'Birth Date',
                'blood_group' => 'Blood Group',
                'mobile_number' => 'Mobile Number',
                'height' => 'Height (cm)',
                'weight' => 'Weight (kg)',
                'fitness_goal' => 'Fitness Goal',
                'waist_size' => 'Waist Size'
            ];

            foreach ($commonRequired as $key => $label) {
                if (empty($_POST[$key])) {
                    $errors[$key] = "$label is required.";
                }
            }

            // Numeric & Format Validations
            if (!empty($_POST['height']) && !is_numeric($_POST['height'])) $errors['height'] = "Height must be a number.";
            if (!empty($_POST['weight']) && !is_numeric($_POST['weight'])) $errors['weight'] = "Weight must be a number.";
            if (!empty($_POST['waist_size']) && !is_numeric($_POST['waist_size'])) $errors['waist_size'] = "Waist Size must be a number.";

            if (!empty($_POST['mobile_number']) && !preg_match('/^[0-9]{10}$/', $_POST['mobile_number'])) {
                $errors['mobile_number'] = "Mobile Number must be exactly 10 digits.";
            }


            $role = $_SESSION['role'];
            /// =====================
            // PROFILE PHOTO UPLOAD
            // =====================
            $photoName = null;

            $userModel = $this->model('User');
            $currentUser = $userModel->getById($_SESSION['user_id']);
            $oldPhoto = $currentUser['profile_photo'] ?? null;
            // var_dump($_SESSION['user_id']);
            // var_dump($currentUser);
            // exit;

            if (!empty($_FILES['profile_photo']['name'])) {

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileType = finfo_file($fileInfo, $_FILES['profile_photo']['tmp_name']);

                if (!in_array($fileType, $allowedTypes)) {
                    $errors['profile_photo'] = "Only JPG, JPEG, PNG allowed.";
                } 
                elseif ($_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
                    $errors['profile_photo'] = "Image must be under 2MB.";
                } 
                else {

                $uploadDir = dirname(__DIR__, 2) . "/public/uploads/profile/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }

                $photoName = "user_" . $_SESSION['user_id'] . "_" . time() . ".jpg";
                $uploadPath = $uploadDir . $photoName;

                // Create source
                if ($fileType == 'image/jpeg') {
                    $src = imagecreatefromjpeg($_FILES['profile_photo']['tmp_name']);
                } else {
                    $src = imagecreatefrompng($_FILES['profile_photo']['tmp_name']);
                }

                if ($src) {

                    $width = imagesx($src);
                    $height = imagesy($src);
                    $size = min($width, $height);

                    $dst = imagecreatetruecolor(300, 300);

                    $srcX = (int)(($width - $size) / 2);
                    $srcY = (int)(($height - $size) / 2);

                    imagecopyresampled(
                        $dst, $src,
                        0, 0,
                        $srcX,
                        $srcY,
                        300, 300,
                        $size, $size
                    );


                    // ✅ Save and check success
                    if (imagejpeg($dst, $uploadPath, 90)) {
                    // var_dump($oldPhoto);
                    // exit;

                    // ✅ Delete old photo ONLY after success
                    if (!empty($oldPhoto)) {
                        $oldPhotoPath = $uploadDir . $oldPhoto;
                        // var_dump($oldPhotoPath);
                        // var_dump(file_exists($oldPhotoPath));
                        // exit;
                                        if (is_file($oldPhotoPath)) {
                                            unlink($oldPhotoPath);
                                        }
                                    }

                                    $updateData['profile_photo'] = $photoName;
                                } else {
                                    $errors['profile_photo'] = "Failed to save image. Check folder permission.";
                                }

                                imagedestroy($src);
                                imagedestroy($dst);
                            } else {
                                $errors['profile_photo'] = "Invalid image file.";
                            }
                        }
                    }


                    // ==========================
                    // FACULTY PROFILE DATA
                    // ==========================
                    if ($role === 'faculty') {

                        // Faculty Specific Required Fields
                        $facultyRequired = [
                            'department' => 'Department',
                            'position' => 'Position',
                            'subject_expert' => 'Subject Expert',
                            'employee_code' => 'Employee Code'
                        ];

                        foreach ($facultyRequired as $key => $label) {
                            if (empty($_POST[$key])) {
                                $errors[$key] = "$label is required.";
                            }
                        }

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
                        // if (!empty($photoName)) {
                        //     $updateData['profile_photo'] = $photoName;
                        // }
                        $updateData = [
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
                        if (!empty($photoName)) {
                            $updateData['profile_photo'] = $photoName;
                        }

                    }

                    // ==========================
                    // USER / STUDENT PROFILE DATA
                    // ==========================
                    else {

                        // Student Specific Required Fields
                        $studentRequired = [
                            'enrollment_number' => 'Enrollment Number',
                            'college_year' => 'College Year',
                            'semester' => 'Semester',
                            'branch' => 'Branch'
                        ];

                        foreach ($studentRequired as $key => $label) {
                            if (empty($_POST[$key])) {
                                $errors[$key] = "$label is required.";
                            }
                        }

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

                        if (!empty($photoName)) {
                            $updateData['profile_photo'] = $photoName;
                        }

                        $updateData = [
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
                        if (!empty($photoName)) {
                            $updateData['profile_photo'] = $photoName;
                        }
                    }
                    // var_dump($updateData);
                    // exit;

                    // If there are validation errors, reload view with data and errors
                    if (!empty($errors)) {
                        // Restore email for the view (since disabled inputs aren't posted)
                        $user = $this->model('User')->getById($_SESSION['user_id']);
                        $data['email'] = $user['email'] ?? '';

                        $viewName = ($role === 'faculty') ? 'faculty/edit_profile' : 'user/edit_profile';
                        $this->view($viewName, [
                            'profile' => $data,
                            'errors' => $errors
                        ]);
                        return;
                    }

                    $this->model('User')->updateProfile(
                        $_SESSION['user_id'],
                        $updateData,
                        $role
                    );

                    $_SESSION['flash_success'] = "Profile updated successfully!";
                    header("Location: " . BASE_URL . "/profile/index");
                    exit;
                }
            }
}
