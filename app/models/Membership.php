<?php

class Membership extends Model
{
    /**
     * Create a new user subscription
     * 
     * @param array $data
     * @return int|bool
     */
    public function createSubscription($data)
    {
        $sql = "INSERT INTO user_subscriptions (user_id, plan_id, payment_id, start_date, end_date, status) 
                VALUES (?, ?, ?, ?, ?, 'active')";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $data['user_id'],
            $data['plan_id'],
            $data['payment_id'],
            $data['start_date'],
            $data['end_date']
        ]);

        return $success ? $this->db->lastInsertId() : false;
    }

    /**
     * Get active subscription for a user
     * 
     * @param int $userId
     * @return array|bool
     */
    public function getActiveSubscription($userId)
    {
        $sql = "SELECT us.*, pm.name as plan_name, pm.plan_key, p.status as payment_status, p.status
                FROM user_subscriptions us
                JOIN plans_master pm ON pm.id = us.plan_id
                JOIN payments p ON p.id = us.payment_id
                WHERE us.user_id = ? AND us.status = 'active'
                ORDER BY us.end_date DESC LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update subscription status
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE user_subscriptions SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Get all subscriptions for a user
     * 
     * @param int $userId
     * @return array
     */
    public function getUserSubscriptions($userId)
    {
        $sql = "SELECT us.*, pm.name as plan_name 
                FROM user_subscriptions us
                JOIN plans_master pm ON pm.id = us.plan_id
                WHERE us.user_id = ?
                ORDER BY us.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
