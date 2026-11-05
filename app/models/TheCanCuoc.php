<?php
// Model thẻ căn cước: ví chỉ cần đọc để hiển thị thẻ CCCD ở đầu trang
class TheCanCuoc {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function layTheoNguoiDung($ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'SELECT t.*, n.ho_ten, n.muc_dinh_danh
             FROM the_can_cuoc t
             JOIN nguoi_dung n ON n.ma_nguoi_dung = t.ma_nguoi_dung
             WHERE t.ma_nguoi_dung = ?
             ORDER BY t.ngay_cap DESC LIMIT 1'
        );
        $cau_lenh->execute([$ma_nguoi_dung]);
        return $cau_lenh->fetch() ?: null;
    }

    // 056200000001 -> 0********001 (giống VNeID: giữ 1 số đầu, 3 số cuối)
    public function cheSoThe($so_the) {
        return substr($so_the, 0, 1) . str_repeat('*', max(0, strlen($so_the) - 4)) . substr($so_the, -3);
    }

    // Xác nhận mật khẩu trước khi xem thông tin đầy đủ (giống bước nhập passcode)
    public function xacNhanMatKhau($ma_nguoi_dung, $mat_khau) {
        $cau_lenh = $this->pdo->prepare('SELECT mat_khau FROM nguoi_dung WHERE ma_nguoi_dung = ?');
        $cau_lenh->execute([$ma_nguoi_dung]);
        $mat_khau_bam = $cau_lenh->fetchColumn();
        return $mat_khau_bam !== false && password_verify($mat_khau, $mat_khau_bam);
    }
}