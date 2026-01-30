<?php

class PaymentController extends Controller
{

    public function index()
    {
        // Allow public viewing of plans. Creating/verifying payments still requires login.
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

    // AJAX: create pending payment and return QR + payment id
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
        $plan_master_id = $planRow['id'];

        // choose upi id for this payment (prioritize global setting)
        $upi_for_payment = $global_upi;

        // 2) create a pending payment (store chosen upi id)
        $stmt = $pdo->prepare("INSERT INTO payments (user_id, plan_master_id, amount, payment_method, upi_id, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $_SESSION['user_id'],
            $plan_master_id,
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

        $payment_id = $_POST['payment_id'] ?? null;
        $utr = trim($_POST['utr'] ?? '');
        $payer_upi = trim($_POST['payer_upi'] ?? '');

        if (!$payment_id || !$utr) {
            $_SESSION['error'] = 'Please provide transaction id.';
            header('Location: ' . BASE_URL . '/payment/index');
            exit;
        }

        $pdo = Database::getInstance();

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

        // Mark payment verified and set times
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("UPDATE payments SET utr_number = ?, payer_upi = ?, status = 'verified', paid_at = ?, verified_at = ? WHERE id = ?");
        $stmt->execute([$utr, $payer_upi, $now, $now, $payment_id]);

        // Get plan master details (support legacy plan_id)
        $planRef = $payment['plan_master_id'] ?? $payment['plan_id'];
        $stmt = $pdo->prepare("SELECT * FROM plans_master WHERE id = ?");
        $stmt->execute([$planRef]);
        $planMaster = $stmt->fetch(PDO::FETCH_ASSOC);

        // Decide duration based on plan_key
        $days = 30;
        if (isset($planMaster['plan_key']) && stripos($planMaster['plan_key'], '3') !== false) $days = 90;
        if (isset($planMaster['plan_key']) && stripos($planMaster['plan_key'], '6') !== false) $days = 180;

        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime("+{$days} days"));

        // Create subscription record referencing plans_master
        $stmt = $pdo->prepare("INSERT INTO user_subscriptions (user_id, plan_master_id, payment_id, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $planMaster['id'], $payment_id, $start, $end]);

        $subscription_id = $pdo->lastInsertId();

        header('Location: ' . BASE_URL . '/payment/success/' . $subscription_id);
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
        $stmt = $pdo->prepare("SELECT us.*, p.amount, p.utr_number, up.name as plan_name FROM user_subscriptions us JOIN payments p ON p.id = us.payment_id JOIN plans_master up ON up.id = COALESCE(us.plan_master_id, us.plan_id) WHERE us.id = ? AND us.user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sub) {
            die('Subscription not found');
        }

        $this->view('subscription/success', ['subscription' => $sub]);
    }
}
