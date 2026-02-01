<?php

class PaymentController extends Controller
{

    public function index()
    {
        // Only allow logged-in users and faculty to view the plans page
        Auth::role(['user', 'faculty']);

        $planModel = $this->model('Plan');
        $currentPlan = isset($_SESSION['user_id']) ? $planModel->getCurrentSubscription($_SESSION['user_id']) : null;
        $preselect = $_GET['plan'] ?? null;

        // compute days left for active subscription (if any)
        $daysLeft = null;
        if ($currentPlan && !empty($currentPlan['end_date'])) {
            $end = strtotime($currentPlan['end_date']);
            $today = strtotime(date('Y-m-d'));
            $diff = $end - $today;
            $daysLeft = (int) floor($diff / 86400);
        }

        // Fetch master plans from DB
        $plans = $planModel->getAllMasterPlans();

        $this->view('subscription/plans', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'daysLeft' => $daysLeft,
            'preselect' => $preselect
        ]);
    }

    // AJAX: Get QR code for plan without creating payment record
    public function get_qr()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $key = $data['plan'] ?? null;

        $planModel = $this->model('Plan');
        $planRow = $planModel->getMasterPlan($key);

        if (!$key || !$planRow) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid plan']);
            exit;
        }

        // determine amount based on role
        $role = $_SESSION['role'] ?? 'user';
        $price = $planModel->getPriceByRole($key, $role);
        
        $global_upi = $planModel->getSetting('global_upi') ?: UPI_ID;

        // Generate UPI link and QR without payment_id (generic transaction note)
        $upi = $global_upi;
        $amount = number_format($price, 2, '.', '');
        $upi_link = "upi://pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-SUBSCRIPTION");
        $phonepe_link = "phonepe://pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-SUBSCRIPTION");
        $gpay_link = "tez://upi/pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-SUBSCRIPTION");
        
        // Generate QR code URL using external service (since we don't have payment_id yet)
        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($upi_link);

        echo json_encode([
            'status' => 'success',
            'qr_url' => $qr_url,
            'upi_link' => $upi_link,
            'phonepe_link' => $phonepe_link,
            'gpay_link' => $gpay_link,
            'amount' => $amount,
            'upi_id' => $upi,
            'plan_id' => $planRow['id'],
            'plan_key' => $key
        ]);

        exit;
    }

    // AJAX: create pending payment and return payment id (called when user clicks "I Paid")
    public function create()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $key = $data['plan'] ?? null;

        $planModel = $this->model('Plan');
        $planRow = $planModel->getMasterPlan($key);

        if (!$key || !$planRow) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid plan']);
            exit;
        }

        // determine amount based on role (guest => user)
        $role = $_SESSION['role'] ?? 'user';
        $price = $planModel->getPriceByRole($key, $role);
        
        $global_upi = $planModel->getSetting('global_upi') ?: UPI_ID;

        $pdo = Database::getInstance();

        // Use plans_master id as plan reference for payments
        $plan_id = $planRow['id'];

        // choose upi id for this payment (prioritize global setting)
        $upi_for_payment = $global_upi;

        // 2) create a pending payment (store chosen upi id)
        // Use only plan_id for compatibility
        $stmt = $pdo->prepare("INSERT INTO payments (user_id, plan_id, amount, payment_method, upi_id, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $_SESSION['user_id'],
            $plan_id,
            $price,
            'upi',
            $upi_for_payment
        ]);

        $payment_id = $pdo->lastInsertId();

        // 3) generate UPI link and QR image (Google Charts)
        $upi = $global_upi;
        $amount = number_format($price, 2, '.', '');
        $upi_link = "upi://pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-PAY-" . $payment_id);
        $phonepe_link = "phonepe://pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-PAY-" . $payment_id);
        $gpay_link = "tez://upi/pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode("GYM-PAY-" . $payment_id);
        
        // Provide server-side QR endpoint URL (will generate PNG dynamically)
        $qr_url = BASE_URL . '/payment/qr/' . $payment_id;

        echo json_encode([
            'status' => 'success',
            'payment_id' => $payment_id,
            'qr_url' => $qr_url,
            'upi_link' => $upi_link,
            'phonepe_link' => $phonepe_link,
            'gpay_link' => $gpay_link,
            'amount' => $amount,
            'upi_id' => $upi
        ]);

        exit;
    }

    // Form POST from user: they enter UTR/txn id to verify
    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $payment_id = $_POST['payment_id'] ?? '';
        $utr = trim($_POST['utr'] ?? '');
        $payer_upi = trim($_POST['payer_upi'] ?? '');
        $plan_key = $_POST['plan_key'] ?? '';
        $plan_id = $_POST['plan_id'] ?? '';

        if (!$utr) {
            $_SESSION['error'] = 'Please provide transaction id.';
            header('Location: ' . BASE_URL . '/payment/index');
            exit;
        }

        $pdo = Database::getInstance();
        
        // If payment_id exists, update existing payment
        if ($payment_id) {
            // Ensure UTR is not already used by another payment
            $stmt = $pdo->prepare("SELECT id FROM payments WHERE utr_number = ? AND id != ? LIMIT 1");
            $stmt->execute([$utr, $payment_id]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $_SESSION['error'] = 'This transaction id has already been used for another payment.';
                header('Location: ' . BASE_URL . '/payment/index');
                exit;
            }

            $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ? AND user_id = ?");
            $stmt->execute([$payment_id, $_SESSION['user_id']]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment) {
                $_SESSION['error'] = 'Payment not found.';
                header('Location: ' . BASE_URL . '/payment/index');
                exit;
            }

            if ($payment['status'] === 'verified') {
                header('Location: ' . BASE_URL . '/payment/success/' . $payment['id']);
                exit;
            }

            // Update existing payment with UTR
            $now = date('Y-m-d H:i:s');
            try {
                $stmt = $pdo->prepare("UPDATE payments SET utr_number = ?, payer_upi = ?, status = 'pending', paid_at = ? WHERE id = ?");
                $stmt->execute([$utr, $payer_upi, $now, $payment_id]);
            } catch (PDOException $e) {
                // Handle duplicate key / constraint violation gracefully
                if ($e->getCode() === '23000') {
                    $_SESSION['error'] = 'Transaction id already exists (unique constraint).';
                    header('Location: ' . BASE_URL . '/payment/index');
                    exit;
                }
                throw $e;
            }
        } else {
            // No payment_id - create new payment record WITH UTR and payer_upi
            if (!$plan_key && !$plan_id) {
                $_SESSION['error'] = 'Plan information missing.';
                header('Location: ' . BASE_URL . '/payment/index');
                exit;
            }

            $planModel = $this->model('Plan');
            
            // Get plan details
            if ($plan_key) {
                $planRow = $planModel->getMasterPlan($plan_key);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM plans_master WHERE id = ?");
                $stmt->execute([$plan_id]);
                $planRow = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if (!$planRow) {
                $_SESSION['error'] = 'Invalid plan.';
                header('Location: ' . BASE_URL . '/payment/index');
                exit;
            }

            // Ensure UTR is not already used
            $stmt = $pdo->prepare("SELECT id FROM payments WHERE utr_number = ? LIMIT 1");
            $stmt->execute([$utr]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $_SESSION['error'] = 'This transaction id has already been used for another payment.';
                header('Location: ' . BASE_URL . '/payment/index');
                exit;
            }

            // Determine amount based on role
            $role = $_SESSION['role'] ?? 'user';
            $price = $planModel->getPriceByRole($planRow['plan_key'], $role);
            $global_upi = $planModel->getSetting('global_upi') ?: UPI_ID;

            // Create payment record WITH UTR and payer_upi in one INSERT
            $now = date('Y-m-d H:i:s');
            try {
                $stmt = $pdo->prepare("INSERT INTO payments (user_id, plan_id, amount, payment_method, upi_id, utr_number, payer_upi, status, paid_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $planRow['id'],
                    $price,
                    'upi',
                    $global_upi,
                    $utr,
                    $payer_upi,
                    $now
                ]);
                $payment_id = $pdo->lastInsertId();
            } catch (PDOException $e) {
                // Handle duplicate key / constraint violation gracefully
                if ($e->getCode() === '23000') {
                    $_SESSION['error'] = 'Transaction id already exists (unique constraint).';
                    header('Location: ' . BASE_URL . '/payment/index');
                    exit;
                }
                throw $e;
            }
        }

        // Get plan master details for subscription creation
        if (isset($planRow)) {
            // Payment was just created, use planRow
            $planMaster = $planRow;
        } else {
            // Payment was updated, get plan from payment record
            if (isset($payment)) {
                $planRef = $payment['plan_master_id'] ?? $payment['plan_id'];
                if ($planRef) {
                    $stmt = $pdo->prepare("SELECT * FROM plans_master WHERE id = ?");
                    $stmt->execute([$planRef]);
                    $planMaster = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $planMaster = null;
                }
            } else {
                // Fallback: try to get from POST
                $planRef = $plan_id ?: null;
                if ($planRef) {
                    $stmt = $pdo->prepare("SELECT * FROM plans_master WHERE id = ?");
                    $stmt->execute([$planRef]);
                    $planMaster = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $planMaster = null;
                }
            }
        }

        if (!$planMaster) {
            $_SESSION['error'] = 'Plan information not found.';
            header('Location: ' . BASE_URL . '/payment/index');
            exit;
        }

        // Decide duration based on plan_key
        $days = 30;
        if (isset($planMaster['plan_key']) && stripos($planMaster['plan_key'], '3') !== false) $days = 90;
        if (isset($planMaster['plan_key']) && stripos($planMaster['plan_key'], '6') !== false) $days = 180;

        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime("+{$days} days"));

        // Use plan_id from planMaster
        $final_plan_id = $planMaster['id'];
        if (!$final_plan_id) {
            $_SESSION['error'] = 'Plan ID missing for subscription.';
            header('Location: ' . BASE_URL . '/payment/index');
            exit;
        }

        // Create subscription record referencing plans_master
        $stmt = $pdo->prepare("INSERT INTO user_subscriptions (user_id, payment_id, plan_id, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $payment_id, $final_plan_id, $start, $end]);

        $subscription_id = $pdo->lastInsertId();

        header('Location: ' . BASE_URL . '/payment/success/' . $subscription_id);
        exit;
    }

    // AJAX: check if a UTR is available (not already used)
    public function check_utr()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
            exit;
        }

        $utr = trim($_POST['utr'] ?? '');
        $payment_id = $_POST['payment_id'] ?? null;

        if ($utr === '') {
            echo json_encode(['status' => 'error', 'message' => 'No UTR provided']);
            exit;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT id FROM payments WHERE utr_number = ? AND id != ? LIMIT 1");
        $stmt->execute([$utr, $payment_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'available' => $existing ? false : true]);
        exit;
    }

    public function qr($paymentId = null)
    {
        if (!$paymentId) {
            http_response_code(404);
            echo 'Not found';
            exit;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            http_response_code(404);
            echo 'Not found';
            exit;
        }

        // Build UPI link (prioritize global setting)
        $planModel = $this->model('Plan');
        $upi = $planModel->getSetting('global_upi') ?: (!empty($payment['upi_id']) ? $payment['upi_id'] : UPI_ID);
        $amount = number_format($payment['amount'], 2, '.', '');
        $upi_link = "upi://pay?pa={$upi}&pn=" . urlencode('SGSIT Gym') . "&am={$amount}&cu=INR&tn=" . urlencode('GYM-PAY-' . $payment['id']);

        // Generate QR using endroid/qr-code
        try {
            // Using direct classes instead of Builder for maximum compatibility
            $qrCode = new \Endroid\QrCode\QrCode(
                data: $upi_link,
                size: 200,
                margin: 10
            );

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            header('Content-Type: ' . $result->getMimeType());
            echo $result->getString();
            exit;
        } catch (Throwable $e) {
            // Log the error for debugging
            error_log("QR Generation Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

            // fallback to a different QR service as Google Charts is unreliable
            $qr = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($upi_link);
            header('Location: ' . $qr);
            exit;
        }
    }

    public function success($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT us.*, p.amount, p.utr_number, up.name as plan_name FROM user_subscriptions us JOIN payments p ON p.id = us.payment_id JOIN plans_master up ON up.id = us.plan_id WHERE us.id = ? AND us.user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sub) {
            die('Subscription not found');
        }

        $this->view('subscription/success', ['subscription' => $sub]);
    }
}
