<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;

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
    $planModel = $this->model('Plan');

    $stats = [
        'total_users'      => $userModel->countByRole('user'),
        'total_faculty'    => $userModel->countByRole('faculty'),
        'today_attendance' => $attendanceModel->getTodayCount()
    ];

    // Fetch settings
    $cron_times_json = $planModel->getSetting('cron_reminder_times');
    $cron_times = json_decode($cron_times_json ?? '', true);

    $this->view('admin/dashboard', ['stats' => $stats, 'cron_times' => $cron_times]);
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

    public function faculty()
    {
        Auth::role(['admin']);

        $userModel = $this->model('User');
        
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        if (!in_array($limit, [10, 25, 50, 75, 100])) $limit = 10;

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $faculty = $userModel->getPaginatedFaculty($limit, $offset, $search);
        $totalFaculty = $userModel->getTotalFacultyCount($search);
        $totalPages = ceil($totalFaculty / $limit);

        $this->view('admin/faculty', [
            'users' => $faculty,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit,
            'search' => $search
        ]);
    }

    // ==========================
    // DELETE USER / FACULTY
    // ==========================
    public function deleteUser($id)
    {
        Auth::role(['admin']);

        $this->model('User')->delete($id);
        $_SESSION['flash_success'] = "User deleted successfully.";

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
        $_SESSION['flash_success'] = "User status updated successfully.";

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

        // Fetch existing plan to pre-fill
        $planModel = $this->model('Plan');
        $existingPlan = $planModel->getUserPlan($userId) ?: [];

        $this->view('admin/assign_plan', ['user' => $user, 'existingPlan' => $existingPlan]);
    }

    // Admin: view/edit plans master prices
    public function plans()
    {
        Auth::role(['admin']);
        $planModel = $this->model('Plan');
        $plans = $planModel->getAllMasterPlans();
        $global_upi = $planModel->getSetting('global_upi');
        $cron_days = $planModel->getSetting('cron_reminder_days');
        $this->view('admin/plans', [
            'plans' => $plans,
            'global_upi' => $global_upi,
            'cron_days' => $cron_days
        ]);
    }

    // public function payments()
    // {
    //     Auth::role(['admin']);
    //     $pdo = Database::getInstance();
    //     $stmt = $pdo->prepare("
    //         SELECT p.*, u.name as user_name, u.email as user_email, pm.name as plan_name 
    //         FROM payments p 
    //         JOIN users u ON u.id = p.user_id 
    //         LEFT JOIN plans_master pm ON pm.id = p.plan_id 
    //         ORDER BY p.created_at DESC
    //     ");
    //     $stmt->execute();
    //     $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $this->view('admin/payments', ['payments' => $payments]);
    // }


public function payments()
{
    Auth::role(['admin']);
    $pdo = Database::getInstance();

    // Filters
    $startDate      = $_GET['start_date'] ?? null;
    $endDate        = $_GET['end_date'] ?? null;
    $status         = $_GET['status'] ?? null;
    $expiryFilter   = $_GET['expiry_filter'] ?? null;
    $utr            = $_GET['utr'] ?? null;
    $payerUpi       = $_GET['payer_upi'] ?? null;
    $accountHolder  = $_GET['account_holder'] ?? null;

    // Pagination
    $page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10; // records per page
    $offset = ($page - 1) * $limit;

    /* ================= COUNT QUERY ================= */
    $countSql = "
        SELECT COUNT(*)
        FROM payments p
        JOIN users u ON u.id = p.user_id
        LEFT JOIN user_subscriptions us ON us.payment_id = p.id
        WHERE 1=1
    ";

    $countParams = [];

    if ($startDate && $endDate) {
        $countSql .= " AND DATE(p.transaction_date) BETWEEN :sd AND :ed";
        $countParams[':sd'] = $startDate;
        $countParams[':ed'] = $endDate;
    }

    if ($status) {
        $countSql .= " AND p.status = :status";
        $countParams[':status'] = $status;
    }

    if ($utr) {
        $countSql .= " AND p.utr_number LIKE :utr";
        $countParams[':utr'] = "%$utr%";
    }

    if ($payerUpi) {
        $countSql .= " AND p.payer_upi LIKE :payer_upi";
        $countParams[':payer_upi'] = "%$payerUpi%";
    }

    if ($accountHolder) {
        $countSql .= " AND p.account_holder_name LIKE :account_holder";
        $countParams[':account_holder'] = "%$accountHolder%";
    }

    if ($expiryFilter === 'expired') {
        $countSql .= " AND us.end_date < CURRENT_DATE";
    } elseif ($expiryFilter === 'active') {
        $countSql .= " AND us.end_date >= CURRENT_DATE";
    }

    $stmt = $pdo->prepare($countSql);
    $stmt->execute($countParams);
    $totalRecords = (int)$stmt->fetchColumn();
    $totalPages = max(1, ceil($totalRecords / $limit));

    /* ================= MAIN DATA QUERY ================= */
    $sql = "
        SELECT 
            p.*,
            u.name AS user_name,
            u.email AS user_email,
            pm.name AS plan_name,
            us.end_date AS expiry_date
        FROM payments p
        JOIN users u ON u.id = p.user_id
        LEFT JOIN plans_master pm ON pm.id = p.plan_id
        LEFT JOIN user_subscriptions us ON us.payment_id = p.id
        WHERE 1=1
    ";

    $params = [];

    if ($startDate && $endDate) {
        $sql .= " AND DATE(p.transaction_date) BETWEEN :sd AND :ed";
        $params[':sd'] = $startDate;
        $params[':ed'] = $endDate;
    }

    if ($status) {
        $sql .= " AND p.status = :status";
        $params[':status'] = $status;
    }

    if ($utr) {
        $sql .= " AND p.utr_number LIKE :utr";
        $params[':utr'] = "%$utr%";
    }

    if ($payerUpi) {
        $sql .= " AND p.payer_upi LIKE :payer_upi";
        $params[':payer_upi'] = "%$payerUpi%";
    }

    if ($accountHolder) {
        $sql .= " AND p.account_holder_name LIKE :account_holder";
        $params[':account_holder'] = "%$accountHolder%";
    }

    if ($expiryFilter === 'expired') {
        $sql .= " AND us.end_date < CURRENT_DATE";
    } elseif ($expiryFilter === 'active') {
        $sql .= " AND us.end_date >= CURRENT_DATE";
    }

    $sql .= " ORDER BY p.transaction_date DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    // Bind normal params
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }

    // Bind pagination params
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->view('admin/payments', [
        'payments'    => $payments,
        'page'        => $page,
        'totalPages'  => $totalPages
    ]);
}



// public function payments()
// {
//     Auth::role(['admin']);
//     $pdo = Database::getInstance();
// $accountHolder = $_GET['account_holder'] ?? null;



//     $startDate    = $_GET['start_date'] ?? null;
//     $endDate      = $_GET['end_date'] ?? null;
//     $status       = $_GET['status'] ?? null;
//     $expiryFilter = $_GET['expiry_filter'] ?? null;
//     $utr          = $_GET['utr'] ?? null;
//     $payerUpi     = $_GET['payer_upi'] ?? null;

//     $sql = "
//         SELECT 
//             p.*,
//             u.name AS user_name,
//             u.email AS user_email,
//             pm.name AS plan_name,
//             us.end_date AS expiry_date
//         FROM payments p
//         JOIN users u ON u.id = p.user_id
//         LEFT JOIN plans_master pm ON pm.id = p.plan_id
//         LEFT JOIN user_subscriptions us ON us.payment_id = p.id
//         WHERE 1=1
//     ";

//     $params = [];

//     if ($startDate && $endDate) {
//         $sql .= " AND DATE(p.transaction_date) BETWEEN :sd AND :ed";
//         $params[':sd'] = $startDate;
//         $params[':ed'] = $endDate;
//     }

//     if ($status) {
//         $sql .= " AND p.status = :status";
//         $params[':status'] = $status;
//     }

//     if ($utr) {
//         $sql .= " AND p.utr_number LIKE :utr";
//         $params[':utr'] = "%$utr%";
//     }

//     if ($payerUpi) {
//         $sql .= " AND p.payer_upi LIKE :payer_upi";
//         $params[':payer_upi'] = "%$payerUpi%";
//     }   
// if ($accountHolder) {
//     $sql .= " AND p.account_holder_name LIKE :account_holder";
//     $params[':account_holder'] = "%$accountHolder%";
// }
//     if ($expiryFilter === 'expired') {
//         $sql .= " AND us.end_date < CURRENT_DATE";
//     } elseif ($expiryFilter === 'active') {
//         $sql .= " AND us.end_date >= CURRENT_DATE";
//     }

//     $sql .= " ORDER BY p.transaction_date DESC";

//     $stmt = $pdo->prepare($sql);
//     $stmt->execute($params);

//     $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $this->view('admin/payments', compact('payments'));
// }
public function exportPayments()
{
    Auth::role(['admin']);

    $pdo = Database::getInstance();

    // Filters
    $startDate     = $_GET['start_date'] ?? null;
    $endDate       = $_GET['end_date'] ?? null;
    $status        = $_GET['status'] ?? null;
    $expiryFilter  = $_GET['expiry_filter'] ?? null;
    $utr           = $_GET['utr'] ?? null;
    $payerUpi      = $_GET['payer_upi'] ?? null;
    $accountHolder = $_GET['account_holder'] ?? null;
    $sql = "
    SELECT 
        u.name AS member_name,
        up.mobile_number,
        p.amount,
        p.transaction_date,
        p.status,
        p.utr_number,
        p.payer_upi,
        p.account_holder_name,
        us.end_date AS expiry_date  
    FROM payments p
    JOIN users u ON u.id = p.user_id
    LEFT JOIN user_profiles up ON up.user_id = u.id
    LEFT JOIN user_subscriptions us ON us.payment_id = p.id
    WHERE 1=1
";

 

    $params = [];

    if ($startDate && $endDate) {
        $sql .= " AND DATE(p.transaction_date) BETWEEN :sd AND :ed";
        $params[':sd'] = $startDate;
        $params[':ed'] = $endDate;
    }

    if ($status) {
        $sql .= " AND p.status = :status";
        $params[':status'] = $status;
    }

    if ($utr) {
        $sql .= " AND p.utr_number LIKE :utr";
        $params[':utr'] = "%$utr%";
    }

    if ($payerUpi) {
        $sql .= " AND p.payer_upi LIKE :payer_upi";
        $params[':payer_upi'] = "%$payerUpi%";
    }

    if ($accountHolder) {
        $sql .= " AND p.account_holder_name LIKE :account_holder";
        $params[':account_holder'] = "%$accountHolder%";
    }

    if ($expiryFilter === 'expired') {
        $sql .= " AND us.end_date < CURRENT_DATE";
    } elseif ($expiryFilter === 'active') {
        $sql .= " AND us.end_date >= CURRENT_DATE";
    }

    $sql .= " ORDER BY p.transaction_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Clear output buffer (important)
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Create Excel (SAME STYLE AS exportAttendance)
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header row
    $sheet->setCellValue('A1', 'Member Name');
    $sheet->setCellValue('C1', 'Amount');
    $sheet->setCellValue('B1', 'Mobile Number');
    $sheet->setCellValue('D1', 'Payment Date');
    $sheet->setCellValue('E1', 'Expiry Date');
    $sheet->setCellValue('F1', 'Status');
    $sheet->setCellValue('G1', 'UTR');
    $sheet->setCellValue('H1', 'Payer UPI');
    $sheet->setCellValue('I1', 'Account Holder');
    
    // Data rows

    $row = 2;
    foreach ($rows as $r) {

    $sheet->setCellValue('A' . $row, $r['member_name']);

    $sheet->setCellValueExplicit(
        'B' . $row,
        $r['mobile_number'] ?? '',
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('C' . $row, $r['amount']);
    $sheet->setCellValue('D' . $row, $r['transaction_date']);
    $sheet->setCellValue('E' . $row, $r['expiry_date']);
    $sheet->setCellValue('F' . $row, ucfirst($r['status']));

    $sheet->setCellValueExplicit(
        'G' . $row,
        $r['utr_number'] ?? '',
        DataType::TYPE_STRING
    );

    $sheet->setCellValue('H' . $row, $r['payer_upi']);
    $sheet->setCellValue('I' . $row, $r['account_holder_name']);

    $row++;
}


    // Auto-size columns
    foreach (range('A', 'I') as $col)
 {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Download (EXACT SAME AS exportAttendance)
    $filename = 'payments_' . date('Ymd_His') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function verifyPayment($id)
{
    Auth::role(['admin']);
    $pdo = Database::getInstance();
    $now = date('Y-m-d H:i:s');

    // 1. Fetch payment details with plan and user info
    $stmt = $pdo->prepare("
        SELECT p.*, pm.duration_days, pm.name AS plan_name,
               u.name AS user_name, u.email AS user_email
        FROM payments p
        JOIN plans_master pm ON pm.id = p.plan_id
        JOIN users u ON u.id = p.user_id
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        header("Location: " . BASE_URL . "/admin/payments");
        exit;
    }

    // 2. Check if already verified
    if ($payment['status'] === 'verified') {
        header("Location: " . BASE_URL . "/admin/payments");
        exit;
    }

    // 3. Prevent duplicate subscription
    $stmt = $pdo->prepare("SELECT id FROM user_subscriptions WHERE payment_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        // Subscription exists, just update payment status
        $stmt = $pdo->prepare("UPDATE payments SET status='verified', verified_at=? WHERE id=?");
        $stmt->execute([$now, $id]);
        header("Location: " . BASE_URL . "/admin/payments");
        exit;
    }

    // 4. Update Payment Status
    $stmt = $pdo->prepare("UPDATE payments SET status='verified', verified_at=? WHERE id=?");
    $stmt->execute([$now, $id]);

    // 5. Create Subscription
    $startDate = date('Y-m-d');
    $duration  = $payment['duration_days'] ?? 30;
    $endDate   = date('Y-m-d', strtotime("+$duration days"));

    $stmt = $pdo->prepare("
        INSERT INTO user_subscriptions
        (user_id, plan_id, payment_id, start_date, end_date, status)
        VALUES (?, ?, ?, ?, ?, 'active')
    ");
    $stmt->execute([
        $payment['user_id'],
        $payment['plan_id'],
        $id,
        $startDate,
        $endDate
    ]);

    // 6. Send Email
    try {
        $subject = "Membership Activated! Welcome to SGSIT Gym";
        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
            <h2 style='color: #198754;'>Payment Verified!</h2>
            <p>Hello " . htmlspecialchars($payment['user_name']) . ",</p>
            <p>Great news! Your payment for the <strong>" . htmlspecialchars($payment['plan_name']) . "</strong> has been verified and your membership is now <strong>Active</strong>.</p>
            <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <p style='margin: 5px 0;'><strong>Plan:</strong> " . htmlspecialchars($payment['plan_name']) . "</p>
                <p style='margin: 5px 0;'><strong>Start Date:</strong> " . date('M d, Y', strtotime($startDate)) . "</p>
                <p style='margin: 5px 0;'><strong>End Date:</strong> " . date('M d, Y', strtotime($endDate)) . "</p>
                <p style='margin: 5px 0;'><strong>Status:</strong> Active</p>
            </div>
            <p>You now have full access to the gym facilities, personalized diet charts, and workout plans.</p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='" . BASE_URL . "/user/dashboard' style='background-color: #198754; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>View My Dashboard</a>
            </div>
            <hr style='border: 0; border-top: 1px solid #eee; margin-top: 30px;'>
            <p style='font-size: 0.8rem; color: #999; text-align: center;'>SGSIT Gym Management System</p>
        </div>
        ";
        Mailer::send($payment['user_email'], $subject, $message);
    } catch (\Throwable $e) {
        // Log error but continue
        error_log("Email failed: " . $e->getMessage());
    }

    $_SESSION['flash_success'] = "Payment verified and subscription activated!";
    header("Location: " . BASE_URL . "/admin/payments");
    exit;
}

    public function rejectPayment()
    {
        Auth::role(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['payment_id'] ?? null;
            $reason = trim($_POST['declined_reason'] ?? '');

            if ($id) {
                $pdo = Database::getInstance();
                
                // Update status to rejected and save reason
                $stmt = $pdo->prepare("UPDATE payments SET status = 'rejected', declined_reason = ? WHERE id = ?");
                $stmt->execute([$reason, $id]);

                // Notify User
                $stmt = $pdo->prepare("SELECT p.*, u.name, u.email, pm.name as plan_name FROM payments p JOIN users u ON u.id = p.user_id LEFT JOIN plans_master pm ON pm.id = p.plan_id WHERE p.id = ?");
                $stmt->execute([$id]);
                $payment = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($payment) {
                    $subject = "Payment Rejected - SGSIT Gym";
                    $message = "Hello " . htmlspecialchars($payment['name']) . ",<br><br>Your payment for <strong>" . htmlspecialchars($payment['plan_name']) . "</strong> has been rejected.<br><br><strong>Reason:</strong> " . nl2br(htmlspecialchars($reason)) . "<br><br>Please check your transaction details and try again.";
                    Mailer::send($payment['email'], $subject, $message);
                }
            }
            $_SESSION['flash_success'] = "Payment rejected successfully.";
        }
        
        header("Location: " . BASE_URL . "/admin/payments");
        exit;
    }

    public function editPayment()
    {
        Auth::role(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['payment_id'] ?? null;
            $utr = trim($_POST['utr_number'] ?? '');
            $payer_upi = trim($_POST['payer_upi'] ?? '');
            $status = $_POST['status'] ?? null;

            if ($id) {
                $this->model('Payment')->updatePaymentDetails($id, $utr, $payer_upi, $status);
                $_SESSION['flash_success'] = "Payment details updated successfully.";
            }
        }
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
            $price_female = $_POST['price_female'] ?? 0;

            if ($plan_key) {
                $upi_id = $_POST['upi_id'] ?? null;
                $this->model('Plan')->updateMasterDetails($plan_key, $price_user, $price_faculty, $upi_id, $price_female);
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
            $reminder_times = $_POST['reminder_times'] ?? null; // From dashboard modal
            
            $planModel = $this->model('Plan');

            if ($global_upi) {
                $planModel->updateSetting('global_upi', $global_upi);
            }

            if ($reminder_times !== null) {
                // Handle array from modal
                $times = array_filter($reminder_times, function($v) { return !empty($v); });
                $times = array_unique($times);
                sort($times); // Sort ascending
                $planModel->updateSetting('cron_reminder_times', json_encode(array_values($times)));
            }
            $_SESSION['flash_success'] = "Settings updated successfully!";
        }
        $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . "/admin/plans";
        header("Location: " . $redirect);
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
                'price_female' => $_POST['price_female'] ?? 0,
                'duration_days' => $_POST['duration_days'] ?? 30,
                'upi_id' => !empty($_POST['upi_id']) ? $_POST['upi_id'] : null
            ];

            $planModel = $this->model('Plan');
            if ($id) {
                $planModel->updateMasterFull($id, $data);
                $_SESSION['flash_success'] = "Plan updated successfully!";
            } else {
                $planModel->addMasterPlan($data);
                $_SESSION['flash_success'] = "Plan added successfully!";
            }
        }
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

    public function deletePlanMaster($id)
    {
        Auth::role(['admin']);
        $this->model('Plan')->deleteMasterPlan($id);
        $_SESSION['flash_success'] = "Plan deleted successfully!";
        header("Location: " . BASE_URL . "/admin/plans");
        exit;
    }

    public function storePlan()
    {
        Auth::role(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userId = $_POST['user_id'] ?? null;

            if (empty($userId)) {
                $_SESSION['flash_error'] = "Please select a user to assign the plan.";
                header("Location: " . BASE_URL . "/admin/users");
                exit;
            }

            // Validate Profile Completion
            $userModel = $this->model('User');
            $user = $userModel->getById($userId);
            $profile = $userModel->getProfile($userId);

            $missingFields = [];
            if (empty($profile['height_cm'])) $missingFields[] = "Height";
            if (empty($profile['weight_kg'])) $missingFields[] = "Weight";
            if (empty($profile['fitness_goal'])) $missingFields[] = "Fitness Goal";

            // Check Branch/Sem only for students (role='user')
            if ($user && $user['role'] === 'user') {
                if (empty($profile['branch'])) $missingFields[] = "Branch";
                if (empty($profile['semester'])) $missingFields[] = "Semester";
            }

            if (!empty($missingFields)) {
                $_SESSION['flash_error'] = "Cannot assign plan. User profile is incomplete: " . implode(', ', $missingFields);
                header("Location: " . BASE_URL . "/admin/assignPlan/" . $userId);
                exit;
            }

            $data = [
                'user_id' => $userId,
                'plan_name' => $_POST['plan_name'],
                'workout_plan' => $_POST['workout_plan'],
                'diet_plan' => $_POST['diet_plan'],
                'assigned_by' => 'Admin',
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date']
            ];

            $this->model('Plan')->createPlan($data);

            $_SESSION['flash_success'] = "Plan assigned successfully!";
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



    public function qr_attendance()
    {
        Auth::role(['admin']);
        $this->view('admin/qr_attendance');
    }
    public function getNotifications()
    {
        Auth::role(['admin']);
        header('Content-Type: application/json');
        
        $paymentModel = $this->model('Payment');
        $unreadCount = $paymentModel->getUnreadCount();
        $totalCount = $paymentModel->getPendingCount();
        $latest = $paymentModel->getPendingPayments();
        
        // Only return latest 5 for the dropdown
        $latest = array_slice($latest, 0, 5);
        
        echo json_encode([
            'status' => 'success',
            'count' => $unreadCount,
            'total' => $totalCount,
            'notifications' => $latest
        ]);
        exit;
    }

    public function markNotificationsRead()
    {
        Auth::role(['admin']);
        header('Content-Type: application/json');
        
        $paymentModel = $this->model('Payment');
        $success = $paymentModel->markAllAsRead();
        
        echo json_encode([
            'status' => $success ? 'success' : 'error'
        ]);
        exit;
    }

    // ==========================
    // EVENT MANAGEMENT
    // ==========================
    public function events()
    {
        Auth::role(['admin']);
        $eventModel = $this->model('Event');
        $events = $eventModel->getAll();
        $this->view('admin/events', ['events' => $events]);
    }

    public function createEvent()
    {
        Auth::role(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 'inactive'
            ];
            
            $eventModel = $this->model('Event');
            $eventModel->create($data);
            
            $_SESSION['flash_success'] = "Event created successfully!";
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }
        
        $this->view('admin/create_event');
    }

    public function editEvent($id)
    {
        Auth::role(['admin']);
        
        $eventModel = $this->model('Event');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 'inactive'
            ];
            
            $eventModel->update($id, $data);
            
            $_SESSION['flash_success'] = "Event updated successfully!";
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }
        
        $event = $eventModel->getById($id);
        $this->view('admin/edit_event', ['event' => $event]);
    }

    public function toggleEventStatus($id)
    {
        Auth::role(['admin']);
        
        $status = $_GET['status'] ?? 'inactive';
        $eventModel = $this->model('Event');
        $eventModel->toggleStatus($id, $status);
        
        $_SESSION['flash_success'] = "Event status updated successfully!";
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }

    public function deleteEvent($id)
    {
        Auth::role(['admin']);
        
        $eventModel = $this->model('Event');
        $eventModel->delete($id);
        
        $_SESSION['flash_success'] = "Event deleted successfully!";
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }
}
