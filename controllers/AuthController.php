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

        // 1. Xác thực (Validation)
        // if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        //     $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
        //     return $this->redirect('?controller=auth&action=showRegistorForm');
        // }
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

        // 2. Xử lý nghiệp vụ: Tạo User và lấy OTP
        // Chú ý: Hàm này phải hash password trước khi lưu
        $otp = $this->userModel->createNewUser($name, $email, $password);

        // 3. Lưu thông tin email đang chờ xác thực vào session
        $_SESSION['verify_email'] = $email;

        // 4. Gửi OTP (Sử dụng try-catch để xử lý lỗi gửi mail)
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
        return $this->view('/auth/verifyOtp');
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
        // if (!isset($_SESSION['verify_email'])) {
        //     $_SESSION['error'] = 'Phiên xác thực đã kết thúc. Vui lòng đăng ký lại.';
        //     return $this->redirect('/register');
        // }

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

    // ------------------------- ĐĂNG NHẬP (Login) -------------------------

    public function showLoginForm()
    {
        return $this->view('/auth/Login');
    }

    public function login()
    {
        // Kiểm tra phải là phương thức POST không (Nếu cần)
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        //     return $this->showLoginForm();
        // }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // 1. Tìm user bằng email để kiểm tra trạng thái kích hoạt trước
        $user = $this->userModel->findByEmail($email);

        // ----------------------------------------------------------------------
        // PHÂN TÍCH TRƯỜNG HỢP 1: USER TỒN TẠI
        // ----------------------------------------------------------------------
        if ($user) {

            // 1A. KIỂM TRA TRẠNG THÁI KÍCH HOẠT
            if ($user['is_verified'] === 0) {
                // Tài khoản tồn tại nhưng CHƯA KÍCH HOẠT

                // ⚠️ TÁI TẠO VÀ GỬI LẠI OTP
                $newOtp = $this->userModel->updateOtpByEmail($email);
                // Thêm logic gửi email ở đây: $this->sendOtpEmail($email, $newOtp);

                $_SESSION['error'] = 'Tài khoản chưa được kích hoạt. Mã OTP mới đã được gửi đến email của bạn. Vui lòng xác minh.';
                $_SESSION['verify_email'] = $email; // Lưu email để form OTP biết cần xác minh ai

                // Chuyển hướng đến form nhập OTP
                return $this->redirect('?controller=auth&action=showVerifyOtpForm');
            }

            // 1B. KIỂM TRA CREDENTIALS (Mật khẩu)
            // Gọi hàm kiểm tra mật khẩu (Giả định findByCredentials() sẽ kiểm tra hash)
            $authenticatedUser = $this->userModel->findByCredentials($email, $password);

            if ($authenticatedUser) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $authenticatedUser['id'];
                $_SESSION['user_name'] = $authenticatedUser['username'];
                $_SESSION['role'] = $authenticatedUser['role'];

                $_SESSION['success'] = 'Đăng nhập thành công!';

                if ($authenticatedUser['role'] === 'admin') {
                    return $this->redirect('?controller=admin');
                } else {
                    return $this->redirect('?controller=home');
                }
            }

            // Nếu $authenticatedUser là NULL (sai mật khẩu)
            // ----------------------------------------------------------------------
            // PHÂN TÍCH TRƯỜNG HỢP 2: TÀI KHOẢN KHÔNG TỒN TẠI HOẶC SAI MẬT KHẨU
            // ----------------------------------------------------------------------

            // Lỗi chung cho cả user không tồn tại và sai mật khẩu để tránh dò tìm email
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';

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

        // Đặt thông báo thành công (sẽ mất ngay sau chuyển hướng nếu không dùng session)
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