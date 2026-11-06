<?php
// Hàm riêng của module ví giấy tờ
if (!function_exists('layNhanTruong')) {
    function layNhanTruong($ten_truong) {
        $ds_nhan = [
            'hang'             => 'Hạng',
            'noi_dang_ky_kcb'  => 'Nơi đăng ký khám chữa bệnh',
            'muc_huong'        => 'Mức hưởng',
            'bien_so'          => 'Biển số',
            'nhan_hieu'        => 'Nhãn hiệu',
            'so_khung'         => 'Số khung',
            'loai_ho_chieu'    => 'Loại hộ chiếu',
            'quoc_tich'        => 'Quốc tịch',
        ];
        return $ds_nhan[$ten_truong] ?? ucfirst(str_replace('_', ' ', $ten_truong));
    }
}

// Nhãn + màu của trạng thái giấy tờ
if (!function_exists('layNhanTrangThaiGiayTo')) {
    function layNhanTrangThaiGiayTo($trang_thai) {
        switch ($trang_thai) {
            case 'da_xac_minh': return ['nhan' => 'Đã xác minh',  'lop' => 'trang-thai-tot'];
            case 'bi_tu_choi':  return ['nhan' => 'Bị từ chối',   'lop' => 'trang-thai-xau'];
            default:            return ['nhan' => 'Chờ xác minh', 'lop' => 'trang-thai-cho'];
        }
    }
}

if (!function_exists('layBieuTuongGiayTo')) {
    function layBieuTuongGiayTo($ten_loai) {
        $ds_bieu_tuong = [
            'Giấy phép lái xe'   => '🚗',
            'Thẻ bảo hiểm y tế'  => '🛡️',
            'Giấy đăng ký xe'    => '🛵',
            'Hộ chiếu'           => '✈️',
        ];
        return $ds_bieu_tuong[$ten_loai] ?? '📄';
    }
}
