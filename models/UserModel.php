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

    // Tim kiem nguoi dung theo ID
    public function findById($userId)
    {
        $userId = (int) $userId;

        $sql = "SELECT * FROM {$this->table} WHERE id = $userId LIMIT 1";
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

            // 2. Cập nhật trạng thái kích hoạt và XÓA OTP + Hạn sử dụng
            $updateSql = "UPDATE {$this->table} SET is_verified = 1, status = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = $user_id";
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

        return $user;
    }

    // Cập nhật trạng thái người dùng
    public function updateUserStatus(int $userId, int $newStatus): bool
    {
        $sql = "UPDATE {$this->table} 
                SET status = $newStatus";
        if ($newStatus == 1) { // Nếu kích hoạt lại, đặt is_verified = 1
            $sql .= ", is_verified = 1";
        }
        if ($newStatus == 0 || $newStatus == 2) { // Nếu vô hiệu, đặt is_verified = 0
            $sql .= ", is_verified = 0";
        }
        $sql .= " WHERE id = $userId;";
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

    // Membership levels
    public function getMembershipLevels($revenue): array
    {
        if ($revenue < 500000) {
            return ['level' => 'Bronze', 'class' => 'bg-amber-100 text-amber-800'];
        } elseif ($revenue < 1000000) {
            return ['level' => 'Silver', 'class' => 'bg-gray-400 text-white'];
        } elseif ($revenue < 2000000) {
            return ['level' => 'Gold', 'class' => 'bg-yellow-500 text-gray-900'];
        } else {
            return ['level' => 'Diamond', 'class' => 'bg-blue-600 text-white'];
        }
    }

    // Lấy tổng doanh thu của người dùng
    public function getUserRevenue($userId): int
    {
        $userId = (int) $userId;
        $sql = "SELECT SUM(subtotal) AS total_revenue
                FROM orders
                WHERE user_id = $userId AND status = 'Thành công'";
        $result = $this->execute_query($sql);
        if ($result) {
            $data = $result->fetch_assoc();
            return (int) ($data['total_revenue'] ?? 0);
        }
        return 0;
    }

    // Lấy số lượng đơn hàng đã hoàn thành của người dùng
    public function getCompletedOrderCount($userId): int
    {
        $userId = (int) $userId;
        $sql = "SELECT COUNT(*) AS order_count
                FROM orders
                WHERE user_id = $userId AND status = 'Thành công'";
        $result = $this->execute_query($sql);
        if ($result) {
            $data = $result->fetch_assoc();
            return (int) ($data['order_count'] ?? 0);
        }
        return 0;
    }

    // Lấy số lượng đánh giá đã viết của người dùng
    public function getUserReviewCount($userId): int
    {
        $userId = (int) $userId;
        $sql = "SELECT COUNT(*) AS review_count
                FROM reviews
                WHERE user_id = $userId";
        $result = $this->execute_query($sql);
        if ($result) {
            $data = $result->fetch_assoc();
            return (int) ($data['review_count'] ?? 0);
        }
        return 0;
    }
}