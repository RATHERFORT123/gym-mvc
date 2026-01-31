<?php

class Attendance extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isMarked($userId, $date)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM attendance WHERE user_id = :uid AND attendance_date = :date"
        );
        $stmt->execute([
            ':uid' => $userId,
            ':date' => $date
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function markPresent($userId, $role, $date)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO attendance (user_id, role, attendance_date)
            VALUES (:uid, :role, :date)"
        );
        return $stmt->execute([
            ':uid' => $userId,
            ':role' => $role,
            ':date' => $date
        ]);
    }

}
