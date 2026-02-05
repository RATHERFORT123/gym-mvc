<?php

class UserController extends Controller
{
    public function dashboard()
    {
        Auth::role(['user', 'faculty']);

        $showProfileAlert = false;

        // Show profile completion alert for non-admin users every time they reach the dashboard
        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
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

        // Fetch active events
        $eventModel = $this->model('Event');
        $activeEvents = $eventModel->getActive();

        $this->view('user/dashboard', [
            'showProfileAlert' => $showProfileAlert,
            'currentPlan' => $currentPlan,
            'daysLeft' => $daysLeft,
            'activeEvents' => $activeEvents
        ]);
    }

    public function dismissAlert() 
    {
        // Keep this endpoint for UX (hides modal immediately) but do NOT persist dismissal
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['status' => 'success']);
            exit;
        }

        header("Location: " . BASE_URL . "/user/dashboard");
        exit;
    }
}
