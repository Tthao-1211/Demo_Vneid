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
        require __DIR__ . '/../views/giay_to_vi/khung.php';
    }
}

// ===== Lớp tương thích cho CanBoController và các model CanBo* =====

// Các model CanBo* gọi db() để lấy kết nối PDO
if (!function_exists('db')) {
    function db() {
        global $pdo;
        if (!($pdo instanceof PDO)) {
            throw new RuntimeException('Chưa có kết nối CSDL ($pdo).');
        }
        return $pdo;
    }
}

// view('lich_su', [...]) -> hienThiTrang('can_bo/lich_su', [...])
if (!function_exists('view')) {
    function view($ten_view, array $du_lieu = []) {
        $tieu_de = [
            'danh_sach'    => 'Duyệt giấy tờ',
            'chi_tiet'     => 'Chi tiết giấy tờ',
            'loai_giay_to' => 'Quản lý loại giấy tờ',
            'lich_su'      => 'Lịch sử xử lý',
        ];
        hienThiTrang('can_bo/' . $ten_view, $du_lieu + [
            'tieu_de_trang' => $tieu_de[$ten_view] ?? 'Cán bộ',
            'muc_dang_chon' => 'giay_to_vi',
            'an_tieu_de'    => true,
            'css_them'      => ['/public/css/can-bo.css'],
            'js_them'       => ['/public/js/can-bo.js'],
        ]);
    }
}

// flash('nội dung', 'loi' | 'ok')
if (!function_exists('flash')) {
    function flash($noi_dung, $loai = 'thanh_cong') {
        datPhanHoi($loai === 'loi' ? 'loi' : 'thanh_cong', $noi_dung);
    }
}

// chuyenTrang('chi_tiet', ['id' => 5]) -> /can_bo/chi_tiet?id=5
if (!function_exists('chuyenTrang')) {
    function chuyenTrang($hanh_dong, array $tham_so = []) {
        chuyenHuong('can_bo/' . $hanh_dong . ($tham_so ? '?' . http_build_query($tham_so) : ''));
    }
}

if (!function_exists('nhanTruong')) {
    function nhanTruong($ten_truong) {
        return layNhanTruong($ten_truong);
    }
}