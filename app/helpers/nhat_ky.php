<?php
require_once __DIR__ . '/../models/NhatKyHeThong.php';

function ghiNhatKyHeThong($hanh_dong, $noi_dung, $doi_tuong = null, $ma_doi_tuong = null, $ma_nguoi_thuc_hien = null) {
    global $pdo;
    try {
        $ma = $ma_nguoi_thuc_hien ?? ($_SESSION['ma_nguoi_dung'] ?? null);
        (new NhatKyHeThong($pdo))->ghi($ma, $hanh_dong, layDiaChiIp(), mb_substr($noi_dung, 0, 500));
    } catch (Exception $loi) {
        error_log('Lỗi ghi nhật ký hệ thống: ' . $loi->getMessage());
    }
}