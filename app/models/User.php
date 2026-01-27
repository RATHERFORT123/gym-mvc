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

}
