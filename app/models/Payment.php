<?php

class Payment extends Model
{
    /**
     * Create a new pending payment record
     * 
     * @param array $data
     * @return int|bool The inserted payment ID or false on failure
     */
    public function create($data)
    {
        $sql = "INSERT INTO payments (user_id, plan_id, amount, payment_method, upi_id, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $data['user_id'],
            $data['plan_id'],
            $data['amount'],
            $data['payment_method'] ?? 'upi',
            $data['upi_id']
        ]);

        return $success ? $this->db->lastInsertId() : false;
    }

    /**
     * Retrieve a single payment record by ID
     * 
     * @param int $id
     * @return array|bool
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a UTR number is already used by another payment
     * 
     * @param string $utr
     * @param int|null $excludeId ID to exclude from the check
     * @return array|bool
     */
    public function getByUtr($utr, $excludeId = null)
    {
        $sql = "SELECT id FROM payments WHERE utr_number = ?";
        $params = [$utr];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment status and details (UTR, payer UPI)
     * 
     * @param int $id
     * @param array $data Contains utr_number, payer_upi, status, etc.
     * @return bool
     */
    public function updateStatus($id, $data)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE payments SET 
                    utr_number = ?, 
                    payer_upi = ?, 
                    status = ?, 
                    paid_at = ?, 
                    verified_at = ? 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['utr_number'],
            $data['payer_upi'] ?? null,
            $data['status'],
            $data['paid_at'] ?? $now,
            $data['verified_at'] ?? $now,
            $id
        ]);
    }

    /**
     * Fetch all payments for a specific user
     * 
     * @param int $userId
     * @return array
     */
    public function getUserPayments($userId)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, pm.name as plan_name 
            FROM payments p
            JOIN plans_master pm ON pm.id = p.plan_id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all pending payments (for admin)
     * 
     * @return array
     */
    public function getPendingPayments()
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as user_name, u.email as user_email, pm.name as plan_name
            FROM payments p
            JOIN users u ON u.id = p.user_id
            JOIN plans_master pm ON pm.id = p.plan_id
            WHERE p.status = 'pending'
            ORDER BY p.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentSubscription($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                us.*,
                pm.name AS plan_name,
                pm.plan_key,
                p.status AS payment_status,
                p.status -- Including this for backward compat if view uses ['status']
            FROM user_subscriptions us
            JOIN plans_master pm ON pm.id = us.plan_id
            JOIN payments p ON p.id = us.payment_id
            WHERE us.user_id = ?
            AND us.status = 'active'
            ORDER BY us.end_date DESC
            LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLatestPayment($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment details (UTR and Payer UPI)
     * 
     * @param int $id
     * @param string $utr
     * @param string $payerUpi
     */
    public function updatePaymentDetails($id, $utr, $payerUpi)
    {
        $sql = "UPDATE payments SET utr_number = ?, payer_upi = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$utr, $payerUpi, $id]);
    }
}
