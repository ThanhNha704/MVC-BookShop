<?php
// models/UserModel.php

class UserModel extends BaseModel {
    protected $table = 'users'; // Tên bảng người dùng
    
    // Đã loại bỏ hàm private function escape($data) như bạn yêu cầu
    
    private function executeQuery($sql) {
        $result = $this->db->query($sql);
        
        if (!$result) {
            // Log lỗi SQL chi tiết nếu INSERT/UPDATE thất bại
            error_log("Lỗi SQL: " . $this->db->error . " | SQL: " . $sql);
        }
        return $result;
    }


    // -----------------------------------------------------------------
    // Chức năng Đăng ký: Tạo User và lưu mã OTP + Hạn sử dụng
    // -----------------------------------------------------------------
    public function createNewUser($name, $email, $rawPassword) {
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $otp = random_int(100000, 999999); 
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        // ĐÃ SỬA: Sử dụng trực tiếp các biến cục bộ (KHÔNG DÙNG $this->)
        $clean_name = $name; 
        $clean_email = $email;
        $clean_pass = $hashedPassword;
        
        $sql = "INSERT INTO {$this->table} (username, email, password, otp_code, otp_expires_at, is_verified, created_at) 
                VALUES ('$clean_name', '$clean_email', '$clean_pass', '$otp', '$expires_at', 0, NOW())";
        
        $this->executeQuery($sql);
        
        return $otp; 
    }

    // -----------------------------------------------------------------
    // Chức năng Đăng nhập
    // -----------------------------------------------------------------
    public function findByCredentials($email, $password) {
        // ĐÃ SỬA: Sử dụng trực tiếp biến cục bộ
        $clean_email = $email;
        
        $sql = "SELECT * FROM {$this->table} WHERE email = '$clean_email' LIMIT 1";
        $query = $this->db->query($sql);

        if (!$query || $query->num_rows === 0) {
            return false;
        }

        $user = $query->fetch_assoc();

        if ($user['is_verified'] == 1 && password_verify($password, $user['password'])) {
            return $user; 
        }
        return false;
    }

    // -----------------------------------------------------------------
    // Chức năng Tìm kiếm Email (Kiểm tra trùng lặp)
    // -----------------------------------------------------------------
    public function findByEmail($email) {
        // ĐÃ SỬA: Sử dụng trực tiếp biến cục bộ
        $clean_email = $email;
        
        $sql = "SELECT id FROM {$this->table} WHERE email = '$clean_email' LIMIT 1";
        $query = $this->db->query($sql);

        if ($query && $query->num_rows > 0) {
            // echo 'findbyemail'; // Xóa debug
            return $query->fetch_assoc();
        }
        // echo 'khongg findbyemail'; // Xóa debug
        return null;
    }
    
    // -----------------------------------------------------------------
    // Chức năng Xác thực OTP và kích hoạt tài khoản
    // -----------------------------------------------------------------
    public function verifyUserOtp($email, $otp_code) {
        $otp_code = trim($otp_code); // Tẩy khoảng trắng
        
        // ĐÃ SỬA: Sử dụng trực tiếp biến cục bộ
        $clean_email = $email;
        $clean_otp = $otp_code;

        // echo $otp_code . '====' . $clean_email; // Xóa debug

        // 1. Kiểm tra Email, OTP, trạng thái CHƯA KÍCH HOẠT VÀ CHƯA HẾT HẠN
        $sql = "SELECT id FROM {$this->table} 
                WHERE email = '$clean_email' 
                AND otp_code = '$clean_otp' 
                AND is_verified = 0 
                AND otp_expires_at > NOW() LIMIT 1"; 
        $query = $this->db->query($sql);
        
        if ($query && $query->num_rows > 0) { // Sửa != 0 thành > 0
            $user = $query->fetch_assoc();
            $user_id = (int)$user['id'];

            // echo $user_id; // Xóa debug
            
            // 2. Cập nhật trạng thái kích hoạt và XÓA OTP + Hạn sử dụng
            $updateSql = "UPDATE {$this->table} SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = $user_id";
            $this->executeQuery($updateSql);
            
            return true;
        }
        return false;
    }
}