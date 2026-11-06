<?php
class MaOtp {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function taoMoi($email) {
        $this->pdo->prepare('UPDATE ma_otp SET da_dung = 1 WHERE email = ? AND da_dung = 0')
                  ->execute([$email]);

        $ma_otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->pdo->prepare(
            'INSERT INTO ma_otp (email, ma_otp, het_han)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE))'
        )->execute([$email, $ma_otp, THOI_GIAN_OTP_PHUT]);
        return $ma_otp;
    }

    public function xacThuc($email, $ma_otp) {
        $lenh = $this->pdo->prepare(
            'SELECT ma_otp_id FROM ma_otp
             WHERE email = ? AND ma_otp = ? AND da_dung = 0 AND het_han > NOW()
             ORDER BY ma_otp_id DESC LIMIT 1'
        );
        $lenh->execute([$email, $ma_otp]);
        $ma_otp_id = $lenh->fetchColumn();
        if (!$ma_otp_id) {
            return false;
        }
        $this->pdo->prepare('UPDATE ma_otp SET da_dung = 1 WHERE ma_otp_id = ?')->execute([$ma_otp_id]);
        return true;
    }
}