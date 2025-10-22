<?php
// models/UserModel.php

class UserModel extends BaseModel
{
    protected $table = 'users'; // Tên bảng người dùng

    // Đã loại bỏ hàm private function escape($data) như bạn yêu cầu

    private function executeQuery($sql)
    {
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
    public function createNewUser($name, $email, $rawPassword)
    {
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $otp = random_int(100000, 999999);

        // ĐÃ SỬA: Sử dụng trực tiếp các biến cục bộ (KHÔNG DÙNG $this->)
        $clean_name = $name;
        $clean_email = $email;
        $clean_pass = $hashedPassword;

        $sql = "INSERT INTO {$this->table} (username, email, password, otp_code, otp_expires_at, is_verified, created_at) 
                VALUES ('$clean_name', '$clean_email', '$clean_pass', '$otp', NOW(), 0, NOW())";

        $this->executeQuery($sql);

        return $otp;
    }

    // -----------------------------------------------------------------
    // Chức năng Đăng nhập
    // -----------------------------------------------------------------
    public function findByCredentials($email, $password)
    {
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
    public function findByEmail($email)
    {
        // ĐÃ SỬA: Sử dụng trực tiếp biến cục bộ
        $clean_email = $email;

        $sql = "SELECT * FROM {$this->table} WHERE email = '$clean_email' LIMIT 1";
        $query = $this->executeQuery($sql);

        if ($query && $query->num_rows > 0) {
            return $query->fetch_assoc();
        }
        return null;
    }

    // Chức năng Xác thực OTP và kích hoạt tài khoản
    public function verifyUserOtp($email, $otp_code)
    {
        $otp_code = trim($otp_code);

        $clean_email = $email;
        $clean_otp = $otp_code;

        // 1. Kiểm tra Email, OTP, trạng thái CHƯA KÍCH HOẠT VÀ CHƯA HẾT HẠN
        $sql = "SELECT id FROM {$this->table} 
                WHERE email = '$clean_email' 
                AND otp_code = '$clean_otp'
                AND is_verified = 0 
                AND TIMESTAMPDIFF(MINUTE, otp_expires_at, NOW()) < 5
                LIMIT 1";
        $query = $this->executeQuery($sql);

        if ($query && $query->num_rows > 0) {
            $user = $query->fetch_assoc();
            $user_id = (int) $user['id'];

            // echo $user_id; // Xóa debug

            // 2. Cập nhật trạng thái kích hoạt và XÓA OTP + Hạn sử dụng
            $updateSql = "UPDATE {$this->table} SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = $user_id";
            $this->executeQuery($updateSql);

            return true;
        }
        return false;
    }

    // Gửi lại mã OTP
    public function updateOtpByEmail($email)
    {
        $otp = random_int(100000, 999999);
        $sql = "UPDATE {$this->table}
                SET otp_code = $otp,
                    otp_expires_at = NOW()
                WHERE email = '$email'  ;";
        $this->executeQuery($sql);
        return $otp;
    }

    // Lấy số lượng người dùng mới
    public function getTotalNewUsers(): int
    {
        $sql = "
            SELECT 
                COUNT(id) AS total_users 
            FROM 
                users 
        ";
        
        $stmt = $this->db->query($sql);
        
        $result = $stmt->fetch_assoc();
        
        // Trả về tổng số người dùng (0 nếu không có kết quả)
        return (int)($result['total_users'] ?? 0);
    }
}