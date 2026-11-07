<?php
function db(): PDO { global $pdo; return $pdo; }
const TRANG_THAI = [
    'cho_xac_minh' => ['Đang xử lý', 'cam'],
    'da_xac_minh'  => ['Đã duyệt', 'xanh'],
    'bi_tu_choi'   => ['Từ chối', 'do'],
];
const NHAN_KET_QUA_PHU = [
    'yeu_cau_bo_sung' => ['Yêu cầu bổ sung', 'xam'],
];
const NHAN_TRUONG = [
    'hang'             => 'Hạng',
    'noi_dang_ky_kcb'  => 'Nơi đăng ký KCB',
    'muc_huong'        => 'Mức hưởng',
    'bien_so'          => 'Biển số',
    'nhan_hieu'        => 'Nhãn hiệu',
    'so_khung'         => 'Số khung',
    'loai_ho_chieu'    => 'Loại hộ chiếu',
    'quoc_tich'        => 'Quốc tịch',
];
function nhanTruong(string $khoa): string {
    return NHAN_TRUONG[$khoa] ?? ucfirst(str_replace('_', ' ', $khoa));
}
function nhanTrangThai(string $s): string {
    [$t, $c] = (TRANG_THAI + NHAN_KET_QUA_PHU)[$s] ?? [$s, 'xam'];
    return "<span class=\"nhan nhan-$c\">" . e($t) . '</span>';
}
function ngay(?string $d): string { return $d ? date('d/m/Y', strtotime($d)) : '—'; }
function ngayGio(?string $d): string { return $d ? date('H:i d/m/Y', strtotime($d)) : '—'; }
function view(string $ten, array $data = [], bool $khung = true): void {
    extract($data);
    ob_start();
    require ROOT . "/app/views/can_bo/$ten.php";
    $noi_dung_trang = ob_get_clean();
    if ($khung) require ROOT . '/app/views/giay_to_vi/khung.php'; else echo $noi_dung_trang;
}