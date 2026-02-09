<?php

class AttendanceController extends Controller
{    
    public function myAttendance()
    {
        Auth::role(['user', 'faculty']);

        $userId = $_SESSION['user_id'];
        if (!$userId) {
            die('Not logged in');
        }

        $attendanceModel = $this->model('Attendance');
        $userModel       = $this->model('User');

        $user = $userModel->getById($userId);
        if (!$user) {
            die('User not found');
        }

        $month = $_GET['month'] ?? date('Y-m');

        $records = $attendanceModel->getByUserAndMonth($userId, $month);

        // ✅ FIXED LINE
        $dates = array_column($records, 'attendance_date');

        $this->view('attendance/my_attendance', [
            'user'  => $user,
            'dates' => $dates,
            'month' => $month
        ]);
    }

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

    public function qrMark()
    {
        Auth::role(['user', 'faculty']);

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        $today  = date('Y-m-d');

        // Ensure user has an active subscription
        $planModel = $this->model('Plan');
        $currentSub = $planModel->getCurrentSubscription($userId);
        
        // Also check if verified (as seen in Controller.php logic)
        $paymentModel = $this->model('Payment');
        $latestPayment = $paymentModel->getLatestPayment($userId);
        $isVerified = ($latestPayment && $latestPayment['status'] === 'verified');

        if (!$currentSub || empty($currentSub['end_date']) || strtotime($currentSub['end_date']) < strtotime($today) || !$isVerified) {
            $_SESSION['flash_error'] = 'You need an active and verified subscription to mark attendance.';
            header("Location: " . BASE_URL . "/user/dashboard");
            return;
        }

        $attendanceModel = $this->model('Attendance');

        if ($attendanceModel->isMarked($userId, $today)) {
            $_SESSION['flash_info'] = 'Attendance already marked today.';
            header("Location: " . BASE_URL . "/user/dashboard");
            return;
        }

        $attendanceModel->markPresent($userId, $role, $today);

        $_SESSION['flash_success'] = 'Attendance marked successfully via QR!';
        header("Location: " . BASE_URL . "/user/dashboard");
    }
}