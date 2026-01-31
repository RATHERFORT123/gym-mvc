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
        
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        if (!in_array($limit, [10, 25, 50, 75, 100])) $limit = 10;
        
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $users = $userModel->getPaginatedUsers($limit, $offset, $search);
        $totalUsers = $userModel->getTotalUserCount($search);
        $totalPages = ceil($totalUsers / $limit);

        $this->view('admin/users', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'search' => $search
        ]);
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

    // Admin: view/edit plans master prices
    public function plans()
    {
        Auth::role(['admin']);
        $planModel = $this->model('Plan');
        $plans = $planModel->getAllMasterPlans();
        $global_upi = $planModel->getSetting('global_upi');
        $this->view('admin/plans', [
            'plans' => $plans,
            'global_upi' => $global_upi
        ]);
    }

    public function payments()
    {
        Auth::role(['admin']);
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT p.*, u.name as user_name, u.email as user_email, pm.name as plan_name 
            FROM payments p 
            JOIN users u ON u.id = p.user_id 
            JOIN plans_master pm ON pm.id = p.plan_id 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin/payments', ['payments' => $payments]);
    }

    public function verifyPayment($id)
    {
        Auth::role(['admin']);
        $pdo = Database::getInstance();
        $now = date('Y-m-d H:i:s');
        
        // 1. Get payment and plan details
        $stmt = $pdo->prepare("
            SELECT p.*, pm.plan_key 
            FROM payments p 
            JOIN plans_master pm ON pm.id = p.plan_id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            header("Location: " . BASE_URL . "/admin/payments");
            exit;
        }

        // 2. Mark payment as verified
        $stmt = $pdo->prepare("UPDATE payments SET status = 'verified', verified_at = ? WHERE id = ?");
        $stmt->execute([$now, $id]);

        // 3. Calculate subscription duration
        $startDate = date('Y-m-d');
        $duration = 30; // default 30 days
        
        $key = $payment['plan_key'];
        if (strpos($key, '1m') !== false) $duration = 30;
        elseif (strpos($key, '3m') !== false) $duration = 90;
        elseif (strpos($key, '6m') !== false) $duration = 180;
        elseif (strpos($key, '1y') !== false || strpos($key, '12m') !== false) $duration = 365;

        $endDate = date('Y-m-d', strtotime("+$duration days"));

        // 4. Create User Subscription
        $stmt = $pdo->prepare("INSERT INTO user_subscriptions (user_id, plan_id, payment_id, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([
            $payment['user_id'],
            $payment['plan_id'],
            $id,
            $startDate,
            $endDate
        ]);
        
        header("Location: " . BASE_URL . "/admin/payments");
        exit;
    }

    public function updatePlan()
    {
        Auth::role(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plan_key = $_POST['plan_key'] ?? null;
            $price_user = $_POST['price_user'] ?? 0;
            $price_faculty = $_POST['price_faculty'] ?? 0;

            if ($plan_key) {
                $upi_id = $_POST['upi_id'] ?? null;
                $this->model('Plan')->updateMasterDetails($plan_key, $price_user, $price_faculty, $upi_id);
            }
        }
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

    public function saveSettings()
    {
        Auth::role(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $global_upi = $_POST['global_upi'] ?? null;
            if ($global_upi) {
                $this->model('Plan')->updateSetting('global_upi', $global_upi);
            }
        }
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

    public function saveMasterPlan()
    {
        Auth::role(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $data = [
                'plan_key' => $_POST['plan_key'],
                'name' => $_POST['name'],
                'price_user' => $_POST['price_user'],
                'price_faculty' => $_POST['price_faculty'],
                'upi_id' => !empty($_POST['upi_id']) ? $_POST['upi_id'] : null
            ];

            $planModel = $this->model('Plan');
            if ($id) {
                $planModel->updateMasterFull($id, $data);
            } else {
                $planModel->addMasterPlan($data);
            }
        }
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

    public function deletePlanMaster($id)
    {
        Auth::role(['admin']);
        $this->model('Plan')->deleteMasterPlan($id);
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

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
