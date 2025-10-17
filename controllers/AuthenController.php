<?php
// Sử dụng thư viện gửi mail (Ví dụ: PHPMailer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthenController extends BaseController
{
    private $userModel;
    
    public function __construct()
    {
        // Nạp UserModel (sẽ tạo ở bước tiếp theo)
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
    }

    // Phương thức để nạp View (Sửa lại từ render thành view chuẩn)
    protected function render($viewPath, array $data = [])
    {
        // View cho Auth thường không cần Header/Footer của trang chính
        $viewPath = './views/frontend/' . $viewPath . '.php';
        if (file_exists($viewPath)) {
            extract($data);
            include $viewPath;
        } else {
            die("View file '{$viewPath}' not found.");
        }
    }

    public function register()
    {
        // 1. Hiển thị Form Đăng Ký
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->render('auth/Register');
        }

        // 2. Xử lý Form Đăng Ký (Bước 1: Lấy thông tin)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
            $email = $_POST['email'] ?? '';
            
            // a. Kiểm tra email đã tồn tại chưa
            if ($this->userModel->getUserByEmail($email)) {
                return $this->render('auth/Register', ['error' => 'Email này đã được đăng ký.']);
            }
            
            // b. Tạo OTP và Gửi Mail (Cần phải có logic gửi mail)
            $otp = rand(100000, 999999);
            $_SESSION['register_otp'] = $otp; // Lưu tạm OTP vào Session
            $_SESSION['register_email'] = $email;
            
            // TODO: Triển khai hàm sendOtpEmail (sử dụng PHPMailer)
            // $this->sendOtpEmail($email, $otp); 
            
            // c. Chuyển sang trang Xác nhận OTP
            return $this->render('auth/VerifyOTP', ['message' => 'Mã OTP đã được gửi đến email của bạn.']);
        }
    }
    
    public function verifyOtp()
    {
        // Xử lý xác nhận OTP
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify') {
            $inputOtp = $_POST['otp'] ?? '';
            
            if ($inputOtp == $_SESSION['register_otp']) {
                $email = $_SESSION['register_email'];
                
                // 3. ĐĂNG KÝ THÀNH CÔNG: TẠO NGƯỜI DÙNG MỚI (Mật khẩu cần được xử lý/mã hóa)
                $this->userModel->createUser($email, 'default_password'); // TODO: Thêm form nhập Mật khẩu
                
                // Thiết lập Session đăng nhập
                $user = $this->userModel->getUserByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'] ?? $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'user';
                
                // Xóa session tạm
                unset($_SESSION['register_otp']);
                unset($_SESSION['register_email']);
                
                // Chuyển hướng về trang chủ
                header('Location: index.php');
                exit;
            } else {
                return $this->render('auth/VerifyOTP', ['error' => 'Mã OTP không đúng.']);
            }
        }
    }

    public function login()
{
    // 1. Hiển thị Form Đăng Nhập
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return $this->render('auth/Login');
    }

    // 2. Xử lý Form Đăng Nhập
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? ''; // Lấy mật khẩu từ form
        
        $user = $this->userModel->getUserByEmail($email);
        
        // a. Kiểm tra người dùng có tồn tại không VÀ mật khẩu có khớp không
        if ($user && password_verify($password, $user['password'])) {
            
            // Thiết lập Session đăng nhập
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'] ?? $user['email'];
            $_SESSION['role'] = $user['role']; // Lấy vai trò (user/admin)
            
            // b. PHÂN QUYỀN
            if ($user['role'] === 'admin') {
                // Chuyển hướng đến trang Admin
                header('Location: index.php?controller=admin');
            } else {
                // Chuyển hướng về trang chủ
                header('Location: index.php');
            }
            exit;
        } else if (!$user) {
             // Nếu chưa đăng ký
             return $this->render('auth/Login', ['error' => 'Email chưa được đăng ký. Vui lòng đăng ký.']);
        } else {
            // Mật khẩu sai
            return $this->render('auth/Login', ['error' => 'Mật khẩu không chính xác.']);
        }
    }
}

    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    // TODO: Triển khai hàm sendOtpEmail
    /* private function sendOtpEmail($email, $otp) { ... } */
}