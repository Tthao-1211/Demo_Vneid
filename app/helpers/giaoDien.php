<?php
if (!function_exists('layMaNguoiDungHienTai')) {
    function layMaNguoiDungHienTai() {
        return (int) ($_SESSION['ma_nguoi_dung'] ?? 0);
    }
}
if (!function_exists('datPhanHoi')) {
    function datPhanHoi($loai, $noi_dung) {
        $_SESSION['phan_hoi'] = ['loai' => $loai, 'noi_dung' => $noi_dung];
    }
}
if (!function_exists('layPhanHoi')) {
    function layPhanHoi() {
        $phan_hoi = $_SESSION['phan_hoi'] ?? null;
        unset($_SESSION['phan_hoi']);
        return $phan_hoi;
    }
}
// Định dạng Y-m-d -> d/m/Y
if (!function_exists('dinhDangNgay')) {
    function dinhDangNgay($ngay, $mac_dinh = '') {
        if (empty($ngay)) return $mac_dinh;
        $thoi_diem = strtotime($ngay);
        return $thoi_diem ? date('d/m/Y', $thoi_diem) : $mac_dinh;
    }
}
if (!function_exists('hienThiTrang')) {
    function hienThiTrang($ten_view, array $du_lieu = []) {
        global $pdo;
        $tep_view = __DIR__ . '/../views/' . $ten_view . '.php';
        if (!is_file($tep_view)) {
            http_response_code(500);
            exit('Không tìm thấy giao diện: ' . e($ten_view));
        }
        // Số thông báo chưa đọc hiện trên thanh điều hướng
        $so_chua_doc = 0;
        if (isset($pdo) && $pdo instanceof PDO && layMaNguoiDungHienTai() > 0) {
            try {
                require_once __DIR__ . '/../models/ThongBao.php';
                $so_chua_doc = (new ThongBao($pdo))->demChuaDoc(layMaNguoiDungHienTai());
            } catch (Throwable $loi) {
                $so_chua_doc = 0;
            }
        }
        extract($du_lieu, EXTR_SKIP);
        ob_start();
        require $tep_view;
        $noi_dung_trang = ob_get_clean();
        require __DIR__ . '/../views/layout/khung.php';
    }
}
