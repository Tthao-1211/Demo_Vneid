<?php
// Gọi ở đầu mỗi phương thức cần đăng nhập
function yeuCauDangNhap() {
    if (!kiemTraDangNhap()) {
        chuyenHuong('nguoi_dung/dang_nhap');
    }
}

// $ds_vai_tro_duoc_phep: mảng các hằng số VAI_TRO_*
function yeuCauVaiTro(array $ds_vai_tro_duoc_phep) {
    yeuCauDangNhap();
    if (!in_array((int)$_SESSION['ma_vai_tro'], $ds_vai_tro_duoc_phep, true)) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập');
    }
}

// Trang đích sau đăng nhập theo vai trò
function layTrangChu($ma_vai_tro) {
    switch ((int)$ma_vai_tro) {
        case VAI_TRO_QUAN_TRI: return 'quan_tri/tong_quan';
        case VAI_TRO_CAN_BO:   return 'dich_vu/ds_ho_so_can_xu_ly';
        default:               return 'the_can_cuoc/chi_tiet';
    }
}