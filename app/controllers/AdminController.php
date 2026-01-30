<?php

class AdminController extends Controller
{
    // ==========================
    // ADMIN DASHBOARD
    // ==========================
    // public function dashboard()
    // {
    //     Auth::role(['admin']);
    //     $this->view('admin/dashboard');
    // }
public function dashboard()
{
    Auth::role(['admin']);

    $userModel = $this->model('User');
    $attendanceModel = $this->model('Attendance');

    $stats = [
        'total_users'      => $userModel->countByRole('user'),
        'total_faculty'    => $userModel->countByRole('faculty'),
        'today_attendance' => $attendanceModel->getTodayCount()
    ];

    $this->view('admin/dashboard', ['stats' => $stats]);
}

    // ==========================
    // MANAGE USERS (STUDENTS ONLY)
    // ==========================
    public function users()
    {
        Auth::role(['admin']);

        $userModel = $this->model('User');
        // ONLY role = user
        $users = $userModel->getUsersByRole('user');

        $this->view('admin/users', ['users' => $users]);
    }

    // ==========================
    // MANAGE FACULTY
    // ==========================
    public function faculty()
    {
        Auth::role(['admin']);

        $userModel = $this->model('User');
        // ONLY role = faculty
        $faculty = $userModel->getUsersByRole('faculty');

        $this->view('admin/faculty', ['users' => $faculty]);
    }

    // ==========================
    // DELETE USER / FACULTY
    // ==========================
    public function deleteUser($id)
    {
        Auth::role(['admin']);

        $this->model('User')->delete($id);

        // Redirect back safely
        $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . "/admin/dashboard";
        header("Location: " . $redirect);
        exit;
    }

    // ==========================
    // TOGGLE ACTIVE / INACTIVE
    // ==========================
    public function toggleStatus($id)
    {
        Auth::role(['admin']);

        $status = $_GET['status'] ?? 1;
        $this->model('User')->updateStatus($id, $status);

        // Redirect back safely
        $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . "/admin/dashboard";
        header("Location: " . $redirect);
        exit;
    }

    // ==========================
    // ASSIGN PLAN (USERS ONLY)
    // ==========================
    public function assignPlan($userId)
    {
        Auth::role(['admin']);

        $userModel = $this->model('User');
        $user = $userModel->getProfile($userId);

        // Optional safety: prevent assigning plan to faculty
        if ($user && ($user['role'] ?? '') === 'faculty') {
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $this->view('admin/assign_plan', ['user' => $user]);
    }

    // ==========================
    // STORE PLAN
    // ==========================
    public function storePlan()
    {
        Auth::role(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'user_id' => $_POST['user_id'],
                'plan_name' => $_POST['plan_name'],
                'workout_plan' => $_POST['workout_plan'],
                'diet_plan' => $_POST['diet_plan'],
                'assigned_by' => 'Admin',
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date']
            ];

            $this->model('Plan')->createPlan($data);

            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
    }


   public function attendance($userId)
{
    Auth::role(['admin']);

    $attendanceModel = $this->model('Attendance');
    $userModel = $this->model('User');

    // basic user info
    $user = $userModel->getById($userId);

    // profile info (optional use)
    $profile = $userModel->getProfile($userId);

    // attendance records
    $records = $attendanceModel->getByUser($userId);

    $this->view('admin/attendance', [
        'user' => $user,
        'profile' => $profile,
        'records' => $records
    ]);
}
public function facultyAttendance()
{
    Auth::role(['admin']);

    $attendanceModel = $this->model('Attendance');
    $userModel = $this->model('User');

    $faculty = $userModel->getUsersByRole('faculty');

    $this->view('admin/faculty_attendance', [
        'faculty' => $faculty
    ]);
}

public function exportAttendance($userId)
{
    Auth::role(['admin']);

    $attendanceModel = $this->model('Attendance');
    $userModel = $this->model('User');

    $records = $attendanceModel->getByUser($userId);
    $user = $userModel->getById($userId);

    // Excel
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header row
    $sheet->setCellValue('A1', 'Date');
    $sheet->setCellValue('B1', 'Role');
    $sheet->setCellValue('C1', 'Marked At');
    $sheet->setCellValue('D1', 'Status');

    // Data rows
    $row = 2;
    foreach ($records as $record) {
        $sheet->setCellValue('A' . $row, $record['attendance_date']);
        $sheet->setCellValue('B' . $row, ucfirst($record['role']));
        $sheet->setCellValue('C' . $row, $record['created_at']);
        $sheet->setCellValue('D' . $row, 'Present');
        $row++;
    }

    // File name
    $filename = 'attendance_' . $user['name'] . '.xlsx';

    // Download headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function attendanceCalendar($userId)
{
    Auth::role(['admin']);

    $month = $_GET['month'] ?? date('Y-m');

    $attendanceModel = $this->model('Attendance');
    $userModel = $this->model('User');

    $dates = $attendanceModel->getMonthlyByUser($userId, $month);
    $user  = $userModel->getById($userId);

    $this->view('admin/attendance_calendar', [
        'user' => $user,
        'month' => $month,
        'dates' => $dates
    ]);
}


// public function exportAttendance($userId)
// {
//     Auth::role(['admin']);

//     $attendanceModel = $this->model('Attendance');
//     $records = $attendanceModel->getByUser($userId);

//     header('Content-Type: text/csv');
//     header('Content-Disposition: attachment; filename="attendance_'.$userId.'.csv"');

//     $output = fopen('php://output', 'w');

//     fputcsv($output, ['Date', 'Role', 'Marked At']);

//     foreach ($records as $row) {
//         fputcsv($output, [
//             $row['attendance_date'],
//             $row['role'],
//             $row['created_at']
//         ]);
//     }

//     fclose($output);
//     exit;
// }



}
