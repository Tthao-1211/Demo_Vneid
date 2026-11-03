<?php
// Chuyển hướng rồi dừng chương trình
function chuyenHuong($duong_dan) {
    header('Location: /' . ltrim($duong_dan, '/'));
    exit;
}

function kiemTraDangNhap() {
    return !empty($_SESSION['ma_nguoi_dung']);
}

// Lưu thông tin phiên sau khi đăng nhập thành công
function luuPhienDangNhap($nguoi_dung) {
    session_regenerate_id(true);   // chống session fixation
    $_SESSION['ma_nguoi_dung'] = $nguoi_dung['ma_nguoi_dung'];
    $_SESSION['ho_ten']        = $nguoi_dung['ho_ten'];
    $_SESSION['ma_vai_tro']    = $nguoi_dung['ma_vai_tro'];
}

function layDiaChiIp() {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

// CSRF: tạo và kiểm tra mã cho mọi form POST
function taoMaCsrf() {
    if (empty($_SESSION['ma_csrf'])) {
        $_SESSION['ma_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['ma_csrf'];
}

function kiemTraMaCsrf() {
    $ma_gui = $_POST['ma_csrf'] ?? '';
    if (!hash_equals($_SESSION['ma_csrf'] ?? '', $ma_gui)) {
        http_response_code(403);
        exit('Yêu cầu không hợp lệ');
    }
}

// Escape khi in ra HTML (chống XSS)
function e($chuoi) {
    return htmlspecialchars((string)$chuoi, ENT_QUOTES, 'UTF-8');
}