<?php

class User extends Model
{
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
{
    $stmt = $this->db->prepare(
        "INSERT INTO users (name,email,password,role,is_verified)
         VALUES (?,?,?,?,?)"
    );

    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['password'],
        $data['role'],
        1
    ]);
}

    public function updatePassword($email, $newPassword)
    {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([
            $newPassword,
            $email
        ]);
    }

    public function getProfile($userId)
    {
        $stmt = $this->db->prepare("
            SELECT u.email, p.* 
            FROM users u 
            LEFT JOIN user_profiles p ON u.id = p.user_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($userId, $data)
    {
        // Check if profile exists
        $stmt = $this->db->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $exists = $stmt->fetch();

        if ($exists) {
            $sql = "UPDATE user_profiles SET 
                    first_name = ?, last_name = ?, mobile_number = ?, 
                    college_year = ?, semester = ?, branch = ?, 
                    height_cm = ?, weight_kg = ?, fitness_goal = ?
                    WHERE user_id = ?";
        } else {
            $sql = "INSERT INTO user_profiles 
                    (first_name, last_name, mobile_number, college_year, semester, branch, height_cm, weight_kg, fitness_goal, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['mobile_number'],
            $data['college_year'],
            $data['semester'],
            $data['branch'],
            $data['height'],
            $data['weight'],
            $data['fitness_goal'],
            $userId
        ]);
    }

    public function isProfileComplete($userId)
    {
        $profile = $this->getProfile($userId);
        
        if (!$profile) return false;

        // Check essential fields
        if (empty($profile['first_name']) || 
            empty($profile['mobile_number']) || 
            empty($profile['fitness_goal'])) {
            return false;
        }

        return true;
    }

    // --- Admin Methods ---

    public function getAllUsers()
    {
        // Get user details joined with profile for the list
        $sql = "SELECT u.id, u.name, u.email, u.role, u.is_active, 
                       p.fitness_goal, p.height_cm, p.weight_kg 
                FROM users u 
                LEFT JOIN user_profiles p ON u.id = p.user_id 
                WHERE u.role != 'admin'
                ORDER BY u.created_at DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginatedUsers($limit, $offset, $search = '')
    {
        $sql = "
            SELECT u.id, u.name, u.email, u.role, u.is_active, 
                   p.fitness_goal, p.height_cm, p.weight_kg 
            FROM users u 
            LEFT JOIN user_profiles p ON u.id = p.user_id 
            WHERE u.role != 'admin'
        ";

        $params = [];
        if (!empty($search)) {
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        
        $paramCount = count($params);
        for ($i = 0; $i < $paramCount; $i++) {
            $stmt->bindValue($i + 1, $params[$i]);
        }
        
        $stmt->bindValue($paramCount + 1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue($paramCount + 2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalUserCount($search = '')
    {
        $sql = "SELECT COUNT(*) FROM users u WHERE u.role != 'admin'";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ?)";
            $params = ["%$search%", "%$search%"];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

}
