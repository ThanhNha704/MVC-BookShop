<?php
// UserModel.php
class UserModel extends BaseModel
{
    protected $table = 'users';

    public function getUserByEmail($email)
    {
        // RẤT QUAN TRỌNG: Dùng Prepared Statements để ngăn SQL Injection
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email); // "s" là string
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function createUser($email, $password, $name = null, $role = 'user')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu
        
        $stmt = $this->db->prepare("INSERT INTO " . $this->table . " (email, password, name, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashedPassword, $name, $role);
        
        return $stmt->execute();
    }
}