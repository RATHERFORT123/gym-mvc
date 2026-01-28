<?php

class UserController extends Controller
{
    public function dashboard()
    {
        Auth::role(['user', 'faculty']);
        
        $showProfileAlert = false;
        
        // Check if profile is incomplete and not dismissed
        if (!isset($_SESSION['dismiss_profile_alert'])) {
            $userModel = $this->model('User');
            if (!$userModel->isProfileComplete($_SESSION['user_id'])) {
                $showProfileAlert = true;
            }
        }

        $this->view('user/dashboard', ['showProfileAlert' => $showProfileAlert]);
    }

    public function dismissAlert() 
    {
        $_SESSION['dismiss_profile_alert'] = true;
        
        // Return JSON for AJAX or redirect
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
             echo json_encode(['status' => 'success']);
             exit;
        }
        
        header("Location: " . BASE_URL . "/user/dashboard");
        exit;
    }
}
