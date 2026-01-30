<?php

class AttendanceController extends Controller
{
    public function mark()
    {
        Auth::role(['user', 'faculty']);

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        $today  = date('Y-m-d');

        // Ensure user has an active subscription
        $planModel = $this->model('Plan');
        $currentSub = $planModel->getCurrentSubscription($userId);
        if (!$currentSub || empty($currentSub['end_date']) || strtotime($currentSub['end_date']) < strtotime($today)) {
            echo json_encode(['status' => 'no_subscription']);
            return;
        }

        $attendanceModel = $this->model('Attendance');

        if ($attendanceModel->isMarked($userId, $today)) {
            echo json_encode(['status' => 'already_marked']);
            return;
        }

        $attendanceModel->markPresent($userId, $role, $today);

        echo json_encode(['status' => 'success']);
    }
}