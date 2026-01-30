<?php

class AttendanceController extends Controller
{
    public function mark()
    {
        Auth::role(['user', 'faculty']);

        $userId = $_SESSION['user_id'];
        $role   = $_SESSION['role'];
        $today  = date('Y-m-d');

        $attendanceModel = $this->model('Attendance');

        if ($attendanceModel->isMarked($userId, $today)) {
            echo json_encode(['status' => 'already_marked']);
            return;
        }

        $attendanceModel->markPresent($userId, $role, $today);

        echo json_encode(['status' => 'success']);
    }
}