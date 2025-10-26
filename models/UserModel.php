<?php
// models/UserModel.php

class UserModel extends BaseModel
{
    protected $table = 'users'; // Tên bảng người dùng

    // Constants for user status
    const STATUS_UNVERIFIED = 0;    // Chưa xác minh
    const STATUS_ACTIVE = 1;        // Đang hoạt động
    const STATUS_DISABLED = 2;      // Vô hiệu
    const STATUS_BANNED = 3;        // Đã chặn

    public static $STATUS_LABELS = [
        self::STATUS_UNVERIFIED => 'Chưa xác minh',
        self::STATUS_ACTIVE => 'Đang hoạt động',
        self::STATUS_DISABLED => 'Vô hiệu',
        self::STATUS_BANNED => 'Đã chặn'
    ];

    // -----------------------------------------------------------------
    // Chức năng Đăng ký: Tạo User và lưu mã OTP + Hạn sử dụng
    // -----------------------------------------------------------------
    public function createNewUser($name, $email, $rawPassword)
    {
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $otp = random_int(100000, 999999);

        $sql = "INSERT INTO {$this->table} (username, email, password, otp_code, otp_expires_at, is_verified, created_at) 
                VALUES ('$name', '$email', '$hashedPassword', '$otp', NOW(), 0, NOW())";

        $this->execute_query($sql);

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
        $query = $this->execute_query($sql);

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
        $query = $this->execute_query($sql);

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
                AND (is_verified = 0 OR is_verified = 1)
                AND TIMESTAMPDIFF(MINUTE, otp_expires_at, NOW()) < 5
                LIMIT 1";
        $query = $this->execute_query($sql);
        echo 'dsfdsfsdfds';
        if ($query && $query->num_rows > 0) {
            $user = $query->fetch_assoc();
            $user_id = (int) $user['id'];

            // echo $user_id; // Xóa debug

            // 2. Cập nhật trạng thái kích hoạt và XÓA OTP + Hạn sử dụng
            $updateSql = "UPDATE {$this->table} SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = $user_id";
            $this->execute_query($updateSql);

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
        $this->execute_query($sql);
        return $otp;
    }

    // Cập nhật mật khẩu người dùng theo email (hash mật khẩu mới)
    public function updatePasswordByEmail(string $email, string $rawPassword): bool
    {
        $hashed = password_hash($rawPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE {$this->table}
                SET password = '$hashed', otp_code = NULL, otp_expires_at = NULL
                WHERE email = '$email'";

        return (bool) $this->execute_query($sql);
    }

    // Lấy danh sách tất cả người dùng
    public function getAllUsers(): array
    {
        $sql = "SELECT 
                    id,
                    username,
                    email,
                    role,
                    status,
                    is_verified,
                    created_at
                FROM {$this->table}
                ORDER BY created_at DESC";

        $result = $this->execute_query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin chi tiết của một người dùng theo ID
    public function getUserById(int $userId): ?array
    {
        // Làm sạch dữ liệu đầu vào
        $userId = (int) $userId;

        // Lấy thông tin người dùng
        $sql = "SELECT u.*
                FROM {$this->table} u
                WHERE u.id = $userId
                LIMIT 1";

        $result = $this->execute_query($sql);

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        $user = $result->fetch_assoc();
        $result->free();

        // Không truy vấn bảng delivery_addresses tại đây (đã chuyển sang nhập địa chỉ trực tiếp ở checkout)
        $user['addresses'] = [];

        return $user;
    }

    // Cập nhật trạng thái người dùng
    public function updateUserStatus(int $userId, int $newStatus): bool
    {
        $sql = "UPDATE {$this->table} 
                SET status = $newStatus
                WHERE id = $userId";
        return (bool) $this->execute_query($sql);
    }

    // Lấy tất cả địa chỉ giao hàng của người dùng
    public function getDeliveryAddresses($userId)
    {
        $userId = (int) $userId;
        $sql = "SELECT * FROM delivery_addresses 
                WHERE user_id = $userId 
                ORDER BY is_default DESC, created_at DESC";

        $result = $this->execute_query($sql);
        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Thêm địa chỉ giao hàng mới
    public function addDeliveryAddress($userId, $recipientName, $phone, $address, $isDefault = 0)
    {
        if ($isDefault) {
            $this->unsetDefaultAddresses($userId);
        }

        $userId = (int) $userId;
        $isDefault = (int) $isDefault;

        // Làm sạch dữ liệu đầu vào
        $recipientName = $this->db->real_escape_string($recipientName);
        $phone = $this->db->real_escape_string($phone);
        $address = $this->db->real_escape_string($address);

        $sql = "INSERT INTO delivery_addresses (user_id, recipient_name, phone, address, is_default) 
                VALUES ($userId, '$recipientName', '$phone', '$address', $isDefault)";

        return (bool) $this->execute_query($sql);
    }

    // Lấy một địa chỉ giao hàng cụ thể
    public function getDeliveryAddress($addressId, $userId)
    {
        $addressId = (int) $addressId;
        $userId = (int) $userId;

        $sql = "SELECT * FROM delivery_addresses 
                WHERE id = $addressId AND user_id = $userId
                LIMIT 1";

        $result = $this->execute_query($sql);
        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    // Bỏ đặt mặc định tất cả địa chỉ của người dùng
    private function unsetDefaultAddresses($userId)
    {
        $userId = (int) $userId;
        $sql = "UPDATE delivery_addresses SET is_default = 0 WHERE user_id = $userId";
        return (bool) $this->execute_query($sql);
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

        $stmt = $this->execute_query($sql);

        $result = $stmt->fetch_assoc();

        // Trả về tổng số người dùng (0 nếu không có kết quả)
        return (int) ($result['total_users'] ?? 0);
    }
}