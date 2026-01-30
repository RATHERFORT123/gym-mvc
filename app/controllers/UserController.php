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

        // Fetch current subscription and days left
        $planModel = $this->model('Plan');
        $currentPlan = $planModel->getCurrentSubscription($_SESSION['user_id']);
        $daysLeft = null;
        if ($currentPlan && !empty($currentPlan['end_date'])) {
            $end = strtotime($currentPlan['end_date']);
            $today = strtotime(date('Y-m-d'));
            $daysLeft = (int) floor(($end - $today) / 86400);
        }

        $this->view('user/dashboard', [
            'showProfileAlert' => $showProfileAlert,
            'currentPlan' => $currentPlan,
            'daysLeft' => $daysLeft
        ]);
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
