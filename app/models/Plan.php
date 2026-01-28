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
}
