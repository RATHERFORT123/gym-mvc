<?php

class CronController extends Controller
{
    /**
     * This method can be called by a server cron job or manually by admin.
     * URL: /cron/notifyExpiringPlans?key=YOUR_SECRET_KEY
     */
    public function notifyExpiringPlans()
    {
        // Security check: Either admin session OR a secret key from config/env
        $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
        $provided_key = $_GET['key'] ?? '';
        $secret_key = 'gym_secure_cron_123'; // In production, move to config.php

        if (!$is_admin && $provided_key !== $secret_key) {
            die("Unauthorized access.");
        }

        $planModel = $this->model('Plan');
        $notificationsSent = 0;

        // Notify for 3 days and 1 day remaining
        foreach ([3, 1] as $days) {
            $expiring = $planModel->getExpiringSubscriptions($days);

            foreach ($expiring as $sub) {
                $subject = "Gym Membership Expiration Reminder - $days Day(s) Left";
                
                $message = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                        <h2 style='color: #0d6efd;'>Hello, " . htmlspecialchars($sub['name']) . "!</h2>
                        <p>This is a friendly reminder that your gym membership for the plan <strong>" . htmlspecialchars($sub['plan_name']) . "</strong> is set to expire in <strong>$days day(s)</strong> on " . date('M d, Y', strtotime($sub['end_date'])) . ".</p>
                        <p>To ensure uninterrupted access to the gym, workouts, and diet plans, please renew your membership soon.</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . BASE_URL . "/payment/index' style='background-color: #198754; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Renew Membership Now</a>
                        </div>
                        <p style='color: #666; font-size: 0.9rem;'>If you have already renewed, please ignore this email.</p>
                        <hr style='border: 0; border-top: 1px solid #eee; margin-top: 30px;'>
                        <p style='font-size: 0.8rem; color: #999; text-align: center;'>Gym Management System</p>
                    </div>
                ";

                if (Mailer::send($sub['email'], $subject, $message)) {
                    $notificationsSent++;
                }
            }
        }

        if (isset($_GET['manual'])) {
            $_SESSION['flash_success'] = "Expiration check complete. $notificationsSent emails sent.";
            header("Location: " . BASE_URL . "/admin/dashboard");
            exit;
        }

        echo "Success: $notificationsSent notifications sent.";
    }
}
