<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (php_sapi_name() === 'cli-server') {
    $tep_tinh = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($tep_tinh) && $tep_tinh !== __FILE__) {
        return false;
    }
}

session_start();
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once __DIR__ . '/config/hang_so.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers/xac_thuc.php';
require_once __DIR__ . '/app/helpers/phan_quyen.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/helpers/gui_mail.php';
require_once __DIR__ . '/app/helpers/nhat_ky.php';
require_once __DIR__ . '/app/helpers/canBoGiaoDien.php';
require_once __DIR__ . '/app/helpers/canBoXacThuc.php';
require_once __DIR__ . '/app/models/CanBoGiayTo.php';
require_once __DIR__ . '/app/models/CanBoLoaiGiayTo.php';
require_once __DIR__ . '/app/models/CanBoThongBao.php';
require_once __DIR__ . '/app/helpers/giaoDien.php';
require_once __DIR__ . '/app/helpers/giayToVi.php';
require_once __DIR__ . '/app/helpers/thongBao.php';

if (kiemTraDangNhap()) {
    $lenh = $pdo->prepare('SELECT trang_thai FROM nguoi_dung WHERE ma_nguoi_dung = ?');
    $lenh->execute([$_SESSION['ma_nguoi_dung']]);
    if ($lenh->fetchColumn() !== 'hoat_dong') {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['thong_bao'] = 'Tài khoản của bạn đã bị khóa hoặc không còn hiệu lực.';
        chuyenHuong('nguoi_dung/dang_nhap');
    }
}

$duong_dan = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($duong_dan === '') {
    $duong_dan = 'nguoi_dung/trang_dau';
}
$ds_phan       = explode('/', $duong_dan);
$ten_module    = $ds_phan[0];
$ten_hanh_dong = $ds_phan[1] ?? 'dang_nhap';

if (!preg_match('/^[a-z_]+$/', $ten_module . $ten_hanh_dong)) {
    http_response_code(404);
    exit('Không tìm thấy trang');
}

if($ten_module==='can_cuoc'||$ten_module==='the_can_cuoc'){
    $ten_class='TheCanCuocController';
}else{
    $ten_class=str_replace(' ','',ucwords(str_replace('_',' ',$ten_module))).'Controller';
}
$ten_ham=lcfirst(str_replace(' ','',ucwords(str_replace('_',' ',$ten_hanh_dong))));
$tep=__DIR__."/app/controllers/$ten_class.php";

if (!is_file($tep)) {
    http_response_code(404);
    exit('Không tìm thấy trang');
}
require_once $tep;

$controller = new $ten_class($pdo);
if (!is_callable([$controller, $ten_ham])) {
    http_response_code(404);
    exit('Không tìm thấy trang');
}
$controller->$ten_ham();