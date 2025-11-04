<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/../PHPMailer/src/Exception.php');
require_once(__DIR__ . '/../PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/../PHPMailer/src/SMTP.php');

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
    }

    // ------------------------- ĐĂNG KÝ (Register) -------------------------

    public function showRegisterForm()
    {
        return $this->view('/auth/Register');
    }

    public function register()
    {
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        //     return $this->showRegisterForm();
        // }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email
            ];
            return $this->redirect('?controller=auth&action=showRegisterForm');
        }

        // KIỂM TRA TRÙNG LẶP & CHUYỂN HƯỚNG ĐẾN OTP NẾU CẦN
        if ($this->userModel->findByEmail($email)) {
            // Nếu user đã tồn tại nhưng chưa verified (is_verified = 0)
            $user = $this->userModel->findByEmail($email);
            $_SESSION['user'] = $user;
            if ($user['is_verified'] == 0) {
                $_SESSION['verify_email'] = $email;
                $_SESSION['error'] = 'Email đã được sử dụng. Vui lòng nhập mã OTP.';
                $_SESSION['verify_email'] = $email;
                $this->userModel->updateOtpByEmail($email);
                return $this->redirect('?controller=auth&action=showVerifyOtpForm');
            }
            $_SESSION['error'] = 'Email đã được sử dụng.';
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email
            ];
            return $this->redirect('?controller=auth&action=showRegisterForm');
        }

        // Tạo User và lấy OTP
        $otp = $this->userModel->createNewUser($name, $email, $password);

        // Lưu thông tin email đang chờ xác thực vào session
        $_SESSION['verify_email'] = $email;

        // Gửi OTP
        try {
            $this->sendEmail($email, "Mã xác thực OTP", $otp);
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: " . $e->getMessage());
            $_SESSION['error'] = 'Không thể gửi mã xác thực. Vui lòng thử lại sau.';
            return $this->redirect('?controller=auth&action=showRegisterForm');
        }

        // 5. Chuyển hướng đến trang nhập OTP
        return $this->redirect('?controller=auth&action=showVerifyOtpForm');
    }

    // ------------------------- XÁC THỰC OTP (Verify OTP) -------------------------

    public function showVerifyOtpForm()
    {
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        //     return $this->showRegisterForm();
        // }
        // Truyền email đang chờ xác thực sang View
        return $this->view('auth/VerifyOTP');
    }

    public function verifyOtp()
    {
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['verify_email'])) {
        //     $_SESSION['error'] = 'Phiên xác thực đã kết thúc.';
        //     return $this->redirect('?controller=auth&action=showRegisterForm');
        // }

        $email = $_SESSION['verify_email'];
        $otp_code = $_POST['otp_code'] ?? '';


        if ($this->userModel->verifyUserOtp($email, $otp_code)) {
            // Xác thực thành công
            unset($_SESSION['verify_email']); // Hủy session xác thực
            $_SESSION['success'] = 'Tài khoản đã được kích hoạt. Vui lòng đăng nhập.';
            return $this->redirect('?controller=auth&action=showLoginForm'); // Chuyển hướng đến trang Login
        } else {
            // Xác thực thất bại
            $_SESSION['error'] = 'Mã OTP không hợp lệ hoặc đã hết hạn.';
            return $this->redirect('?controller=auth&action=showVerifyOtpForm'); // Chuyển hướng lại trang Verify
        }
    }

    // ------------------------- GỬI LẠI OTP (Resend OTP) -------------------------

    public function resendOtp()
    {
        $email = $_SESSION['verify_email'];

        $newOtp = $this->userModel->updateOtpByEmail($email); // Giả định hàm này tồn tại và trả về OTP mới

        try {
            $this->sendEmail($email, "Mã xác thực OTP MỚI", $newOtp);
            $_SESSION['success'] = 'Đã gửi mã OTP mới thành công.';
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: " . $e->getMessage());
            $_SESSION['error'] = 'Không thể gửi mã xác thực mới. Vui lòng kiểm tra lại cấu hình mail.';
        }

        // 3. Chuyển hướng về trang xác thực
        return $this->redirect('?controller=auth&action=showVerifyOtpForm');
    }

    // ------------------------- QUÊN MẬT KHẨU (forgotpassword) -------------------------
    public function showForgotPasswordForm()
    {
        return $this->view('auth/ForgotPassword');
    }

    // Xử lý gửi yêu cầu quên mật khẩu: tạo OTP và gửi email nếu email tồn tại
    public function sendForgotPassword()
    {
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $_SESSION['error'] = 'Vui lòng nhập email.';
            return $this->redirect('?controller=auth&action=showForgotPasswordForm');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            // Để tránh dò tìm email, vẫn hiển thị thông báo thành công chung
            $_SESSION['success'] = 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.';
            return $this->redirect('?controller=auth&action=showLoginForm');
        }

        // Tạo mật khẩu mới ngẫu nhiên
        $newPassword = bin2hex(random_bytes(4)); // 8 hex chars ~ 8 chars password

        // Cập nhật mật khẩu mới (đã hash) trong DB
        $updated = $this->userModel->updatePasswordByEmail($email, $newPassword);

        if (!$updated) {
            error_log("Không thể cập nhật mật khẩu cho email: $email");
            $_SESSION['error'] = 'Đã xảy ra lỗi nội bộ. Vui lòng thử lại sau.';
            return $this->redirect('?controller=auth&action=showForgotPasswordForm');
        }

        try {
            // Gửi mật khẩu mới tới email người dùng
            $this->sendPasswordResetEmail($email, $newPassword);
            $_SESSION['success'] = 'Mật khẩu mới đã được gửi tới email của bạn. Vui lòng kiểm tra hộp thư.';
        } catch (Exception $e) {
            error_log("Lỗi gửi mail (forgot password): " . $e->getMessage());
            $_SESSION['error'] = 'Không thể gửi email. Vui lòng thử lại sau.';
            return $this->redirect('?controller=auth&action=showForgotPasswordForm');
        }

        return $this->redirect('?controller=auth&action=showLoginForm');
    }

    // ------------------------- ĐĂNG NHẬP (Login) -------------------------

    public function showLoginForm()
    {
        return $this->view('/auth/Login');
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // 1. Tìm user bằng email để kiểm tra trạng thái kích hoạt trước
        $user = $this->userModel->findByEmail($email);

        // ----------------------------------------------------------------------
        // PHÂN TÍCH TRƯỜNG HỢP 1: USER TỒN TẠI
        // ----------------------------------------------------------------------
        if ($user) {

            // KIỂM TRA TRẠNG THÁI KÍCH HOẠT
            if ($user['is_verified'] == 0) {
                // Tài khoản tồn tại nhưng CHƯA KÍCH HOẠT

                // TÁI TẠO VÀ GỬI LẠI OTP
                $newOtp = $this->userModel->updateOtpByEmail($email);
                $this->sendEmail($email, "Kích hoạt tài khoản", $newOtp);

                $_SESSION['error'] = 'Tài khoản chưa được kích hoạt. Mã OTP mới đã được gửi đến email của bạn. Vui lòng xác minh.';
                $_SESSION['verify_email'] = $email; // Lưu email để form OTP biết cần xác minh ai

                // Chuyển hướng đến form nhập OTP
                return $this->redirect('?controller=auth&action=showVerifyOtpForm');
            }

            // KIỂM TRA CREDENTIALS (Mật khẩu)
            $authenticatedUser = $this->userModel->findByCredentials($email, $password);

            if ($authenticatedUser) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $authenticatedUser['id'];
                $_SESSION['user_name'] = $authenticatedUser['username'];
                $_SESSION['role'] = $authenticatedUser['role'];
                $revenue = $this->userModel->getUserRevenue($authenticatedUser['id']);
                $_SESSION['membership'] = $this->userModel->getMembershipLevels($revenue);

                $_SESSION['success'] = 'Đăng nhập thành công!';

                if ($authenticatedUser['role'] === 'admin') {
                    return $this->redirect('?controller=admin');
                } else {
                    return $this->redirect('?controller=home');
                }
            }

            // Lỗi chung cho cả user không tồn tại và sai mật khẩu để tránh dò tìm email
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.' . $user['is_verified'];

            // Lưu lại email để người dùng không cần nhập lại
            $_SESSION['old_input'] = ['email' => $email];

            return $this->redirect('?controller=auth&action=showLoginForm');
        }

        // Nếu $user là NULL (email không tồn tại)
        $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';
        $_SESSION['old_input'] = ['email' => $email];
        return $this->redirect('?controller=auth&action=showLoginForm');
    }

    // ------------------------- ĐĂNG XUẤT (Logout) -------------------------

    public function logout()
    {
        // Xóa tất cả các biến session
        session_unset();

        // Hủy session
        session_destroy();

        // Nên đặt session_start() ngay sau session_destroy() nếu muốn giữ lại thông báo này
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['success'] = 'Bạn đã đăng xuất thành công.';

        // Chuyển hướng về trang chính (sử dụng đường dẫn tương đối)
        return $this->redirect('index.php');
    }

    // ----------------------------------------------------------------------
    // PHƯƠNG THỨC GỬI EMAIL (PHPMailer)
    // ----------------------------------------------------------------------

    // Gửi email thông báo mật khẩu mới
    private function sendPasswordResetEmail($to, $newPassword)
    {
        $mailer = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mailer->isSMTP();
            $mailer->Host = 'smtp.gmail.com'; // Sửa host nếu cần
            $mailer->SMTPAuth = true;
            $mailer->Username = 'truongnha474@gmail.com';
            // QUAN TRỌNG: Thay bằng MẬT KHẨU ỨNG DỤNG của Gmail
            $mailer->Password = 'gmsn nejc dtko rhec';
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mailer->Port = 465;
            $mailer->CharSet = 'UTF-8';

            // Thiết lập người gửi và người nhận
            $mailer->setFrom('no-reply@bookshop.com', 'BookShop Hỗ trợ');
            $mailer->addAddress($to);

            // Thiết lập nội dung Email
            $mailer->isHTML(true);
            $mailer->Subject = 'Mật khẩu mới cho tài khoản BookShop';
            $mailer->Body = "<h2>Mật khẩu mới</h2>" .
                "<p>Mật khẩu mới của bạn là: <strong style='font-size:1.1em;'>$newPassword</strong></p>" .
                "<p>Vui lòng đăng nhập và đổi mật khẩu ngay sau khi đăng nhập để bảo mật tài khoản.</p>";
            $mailer->AltBody = "Mật khẩu mới của bạn là: $newPassword";

            $mailer->send();

        } catch (Exception $e) {
            throw new Exception('Lỗi gửi mail: ' . $mailer->ErrorInfo);
        }
    }

    private function sendEmail($to, $subject, $otp_code)
    {
        $mailer = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mailer->isSMTP();
            $mailer->Host = 'smtp.gmail.com'; // Sửa host
            $mailer->SMTPAuth = true;
            $mailer->Username = 'truongnha474@gmail.com';
            // QUAN TRỌNG: Thay bằng MẬT KHẨU ỨNG DỤNG của Gmail
            $mailer->Password = 'gmsn nejc dtko rhec';
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mailer->Port = 465;
            $mailer->CharSet = 'UTF-8';

            // Thiết lập người gửi và người nhận
            $mailer->setFrom('no-reply@bookshop.com', 'BookShop Xác Thực');
            $mailer->addAddress($to);

            // Thiết lập nội dung Email
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = "<h2>Xác thực tài khoản BookShop</h2>" .
                "<p>Mã OTP của bạn là: <strong style='font-size: 1.2em; color: #1e88e5;'>$otp_code</strong></p>" .
                "<p>Mã này có hiệu lực trong 5 phút. Vui lòng không chia sẻ mã này.</p>";
            $mailer->AltBody = "Mã OTP của bạn là: $otp_code. Mã này có hiệu lực trong 5 phút.";

            $mailer->send();

        } catch (Exception $e) {
            // Ném lỗi để hàm gọi (register/resendOtp) bắt được
            throw new Exception("Lỗi gửi mail: " . $mailer->ErrorInfo);
        }
    }
}