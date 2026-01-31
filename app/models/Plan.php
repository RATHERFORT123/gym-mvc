<?php

class Plan extends Model
{
    public function getUserPlan($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM user_plans WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPlan($data)
    {
        $stmt = $this->db->prepare("INSERT INTO user_plans (user_id, plan_name, workout_plan, diet_plan, assigned_by, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['plan_name'],
            $data['workout_plan'],
            $data['diet_plan'],
            $data['assigned_by'],
            $data['start_date'],
            $data['end_date']
        ]);
    }

    // --- Master plans management ---
    public function getAllMasterPlans()
    {
        $stmt = $this->db->prepare("SELECT * FROM plans_master ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMasterPlan($planKey)
    {
        $stmt = $this->db->prepare("SELECT * FROM plans_master WHERE plan_key = ? LIMIT 1");
        $stmt->execute([$planKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMasterPrice($planKey, $priceUser, $priceFaculty)
    {
        $stmt = $this->db->prepare("UPDATE plans_master SET price_user = ?, price_faculty = ? WHERE plan_key = ?");
        return $stmt->execute([$priceUser, $priceFaculty, $planKey]);
    }

    public function updateMasterDetails($planKey, $priceUser, $priceFaculty, $upiId)
    {
        $stmt = $this->db->prepare("UPDATE plans_master SET price_user = ?, price_faculty = ?, upi_id = ? WHERE plan_key = ?");
        return $stmt->execute([$priceUser, $priceFaculty, $upiId, $planKey]);
    }

    public function addMasterPlan($data)
    {
        $stmt = $this->db->prepare("INSERT INTO plans_master (plan_key, name, price_user, price_faculty, upi_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['plan_key'],
            $data['name'],
            $data['price_user'],
            $data['price_faculty'],
            $data['upi_id'] ?? null
        ]);
    }

    public function updateMasterFull($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE plans_master SET plan_key = ?, name = ?, price_user = ?, price_faculty = ?, upi_id = ? WHERE id = ?");
        return $stmt->execute([
            $data['plan_key'],
            $data['name'],
            $data['price_user'],
            $data['price_faculty'],
            $data['upi_id'] ?? null,
            $id
        ]);
    }

    public function deleteMasterPlan($id)
    {
        $stmt = $this->db->prepare("DELETE FROM plans_master WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPriceByRole($planKey, $role = 'user')
    {
        $p = $this->getMasterPlan($planKey);
        if (!$p) return 0;
        if ($role === 'faculty') return $p['price_faculty'];
        return $p['price_user'];
    }

    public function getCurrentSubscription($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT us.*, pm.name AS plan_name, pm.plan_key
             FROM user_subscriptions us
             JOIN plans_master pm ON pm.id = COALESCE(us.plan_master_id, us.plan_id)
             WHERE us.user_id = ? AND us.status = 'active'
             ORDER BY us.end_date DESC
             LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
