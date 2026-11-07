<?php
function yeuCauDangNhap() {
    if (!kiemTraDangNhap()) {
        chuyenHuong('nguoi_dung/dang_nhap');
    }
}

function yeuCauVaiTro(array $ds_vai_tro_duoc_phep) {
    yeuCauDangNhap();
    if (!in_array((int)$_SESSION['ma_vai_tro'], $ds_vai_tro_duoc_phep, true)) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập');
    }
}

function layTrangChu($ma_vai_tro) {
    return 'nguoi_dung/trang_chu';
}