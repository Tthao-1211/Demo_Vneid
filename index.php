<?php
// Máy chủ tích hợp của PHP: để tệp tĩnh (css, js, ảnh) tự được phục vụ
if (php_sapi_name() === 'cli-server') {
    $tep_tinh = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($tep_tinh) && $tep_tinh !== __FILE__) {
        return false;
    }
}

session_start();
require_once __DIR__ . '/config/hang_so.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/helpers/xac_thuc.php';
require_once __DIR__ . '/app/helpers/phan_quyen.php';

$duong_dan = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($duong_dan === '') {
    $duong_dan = 'nguoi_dung/dang_nhap';
}
$ds_phan       = explode('/', $duong_dan);
$ten_module    = $ds_phan[0];
$ten_hanh_dong = $ds_phan[1] ?? 'dang_nhap';

// Chỉ cho phép chữ thường và gạch dưới để tránh tên file bất thường
if (!preg_match('/^[a-z_]+$/', $ten_module . $ten_hanh_dong)) {
    http_response_code(404);
    exit('Không tìm thấy trang');
}

$ten_class = str_replace(' ', '', ucwords(str_replace('_', ' ', $ten_module))) . 'Controller';
$ten_ham   = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $ten_hanh_dong))));
$tep       = __DIR__ . "/app/controllers/$ten_class.php";

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