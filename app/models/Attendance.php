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

    public function getByUser($userId)
{
    $stmt = $this->db->prepare(
        "SELECT attendance_date, role, created_at
         FROM attendance
         WHERE user_id = ?
         ORDER BY attendance_date DESC"
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getAttendancePercentage($userId)
{
    // total days user has attendance records
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) FROM attendance WHERE user_id = ?"
    );
    $stmt->execute([$userId]);
    $presentDays = (int) $stmt->fetchColumn();

    // total days since first attendance
    $stmt = $this->db->prepare(
        "SELECT MIN(attendance_date) FROM attendance WHERE user_id = ?"
    );
    $stmt->execute([$userId]);
    $startDate = $stmt->fetchColumn();

    if (!$startDate) {
        return 0;
    }

    $totalDays = (new DateTime($startDate))
        ->diff(new DateTime(date('Y-m-d')))
        ->days + 1;

    return round(($presentDays / $totalDays) * 100, 2);
}
public function getByUserWithFilter($userId, $from = null, $to = null)
{
    $sql = "SELECT attendance_date, role, created_at
            FROM attendance
            WHERE user_id = ?";

    $params = [$userId];

    if ($from && $to) {
        $sql .= " AND attendance_date BETWEEN ? AND ?";
        $params[] = $from;
        $params[] = $to;
    }

    $sql .= " ORDER BY attendance_date DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTodayCount()
{
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE()"
    );
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}
public function getMonthlyByUser($userId, $month)
{
    $stmt = $this->db->prepare(
        "SELECT attendance_date
         FROM attendance
         WHERE user_id = ?
         AND DATE_FORMAT(attendance_date, '%Y-%m') = ?"
    );
    $stmt->execute([$userId, $month]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


}
