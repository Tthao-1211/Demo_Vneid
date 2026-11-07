<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function guiMailOtp($email_nhan, $ma_otp) {
    $cau_hinh = require __DIR__ . '/../../config/mail.php';
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $cau_hinh['may_chu'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cau_hinh['tai_khoan'];
        $mail->Password   = $cau_hinh['mat_khau_ung_dung'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $cau_hinh['cong'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($cau_hinh['email_gui'], $cau_hinh['ten_hien_thi']);
        $mail->addAddress($email_nhan);
        $mail->isHTML(true);
        $mail->Subject = 'Mã xác thực VNeID';
        $mail->Body    = '<p>Mã OTP của bạn là:</p>'
                       . '<h2 style="letter-spacing:4px">' . htmlspecialchars($ma_otp) . '</h2>'
                       . '<p>Mã có hiệu lực ' . THOI_GIAN_OTP_PHUT . ' phút. Không chia sẻ mã này cho bất kỳ ai.</p>';
        $mail->AltBody = "Mã OTP của bạn là: $ma_otp (hiệu lực " . THOI_GIAN_OTP_PHUT . ' phút)';
        $mail->send();
        return true;
    } catch (Exception $loi) {
        error_log('Lỗi gửi mail: ' . $mail->ErrorInfo);
        return false;
    }
}

function guiMailSoDinhDanh($email_nhan, $ho_ten, $so_dinh_danh) {
    $cau_hinh = require __DIR__ . '/../../config/mail.php';
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $cau_hinh['may_chu'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cau_hinh['tai_khoan'];
        $mail->Password   = $cau_hinh['mat_khau_ung_dung'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $cau_hinh['cong'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($cau_hinh['email_gui'], $cau_hinh['ten_hien_thi']);
        $mail->addAddress($email_nhan, $ho_ten);
        $mail->isHTML(true);
        $mail->Subject = 'Đăng ký VNeID thành công - Số định danh của bạn';
        $mail->Body    = '<p>Xin chào <strong>' . htmlspecialchars($ho_ten) . '</strong>,</p>'
                       . '<p>Tài khoản của bạn đã được kích hoạt thành công. Số định danh dùng để đăng nhập là:</p>'
                       . '<h2 style="letter-spacing:4px">' . htmlspecialchars($so_dinh_danh) . '</h2>'
                       . '<p>Vui lòng không chia sẻ số định danh và mật khẩu cho bất kỳ ai.</p>';
        $mail->AltBody = "Xin chào $ho_ten, số định danh của bạn là: $so_dinh_danh";
        $mail->send();
        return true;
    } catch (Exception $loi) {
        error_log('Lỗi gửi mail số định danh: ' . $mail->ErrorInfo);
        return false;
    }
}

function cheEmail($email) {
    [$ten, $mien] = explode('@', $email, 2) + ['', ''];
    return substr($ten, 0, 2) . str_repeat('*', max(1, strlen($ten) - 2)) . '@' . $mien;
}