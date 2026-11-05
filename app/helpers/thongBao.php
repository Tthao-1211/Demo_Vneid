<?php
// Hàm riêng của module thông báo
if (!function_exists('layNhanLoaiThongBao')) {
    function layNhanLoaiThongBao($loai) {
        $ds_nhan = [
            'he_thong'     => 'Hệ thống',
            'dich_vu_cong' => 'Dịch vụ công',
            'bao_mat'      => 'Bảo mật',
        ];
        return $ds_nhan[$loai] ?? $loai;
    }
}
