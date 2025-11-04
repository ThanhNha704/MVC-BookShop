<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactusController extends BaseController {
    
    public function index() {
        // Hiển thị trang liên hệ
        $this->view('pages/ContactUs');
    }

    public function sendMail() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        $mail = new PHPMailer(true);

        try {
            // 🔹 Cấu hình SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dunglamvieccanhan@gmail.com'; // Gmail dùng để GỬI
            $mail->Password = 'cfzs widt oyqk jpvp'; // App Password chứ không phải mật khẩu thật!
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // 🔹 Thông tin người gửi và người nhận
            $mail->setFrom('dunglamvieccanhan@gmail.com', 'Website Liên Hệ');
            $mail->addReplyTo($email, $name); // để admin có thể bấm "Trả lời" cho người gửi
            $mail->addAddress('baobang21032004@gmail.com', 'Admin'); // Gmail của admin nhận thư

            // 🔹 Nội dung email
            $mail->isHTML(true);
            $mail->Subject = '📩 Liên hệ từ khách hàng';
            $mail->Body = nl2br("
                <b>Tên:</b> $name<br>
                <b>Email:</b> $email<br><br>
                <b>Nội dung:</b><br>$message
            ");

            // 🔹 Gửi mail
            $mail->send();
            echo "<script>alert('✅ Gửi mail thành công!'); window.location='?controller=contactus';</script>";
        } catch (Exception $e) {
            echo "<script>alert('❌ Gửi thất bại: {$mail->ErrorInfo}'); window.location='?controller=contactus';</script>";
        }
    }
}
}