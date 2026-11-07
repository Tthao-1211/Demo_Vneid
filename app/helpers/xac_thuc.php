<?php
function chuyenHuong($duong_dan) {
    header('Location: /' . ltrim($duong_dan, '/'));
    exit;
}

function kiemTraDangNhap() {
    return !empty($_SESSION['ma_nguoi_dung']);
}

function luuPhienDangNhap($nguoi_dung) {
    session_regenerate_id(true);
    $_SESSION['ma_nguoi_dung'] = $nguoi_dung['ma_nguoi_dung'];
    $_SESSION['ho_ten']        = $nguoi_dung['ho_ten'];
    $_SESSION['ma_vai_tro']    = $nguoi_dung['ma_vai_tro'];

    global $pdo;
    if ($pdo) {
        // Tìm hồ sơ nâng mức đã duyệt của người dùng này
        $stmt = $pdo->prepare("
            SELECT ma_ho_so, tep_dinh_kem 
            FROM ho_so_dich_vu 
            WHERE ma_nguoi_dung = ? AND trang_thai = 'da_duyet' 
            ORDER BY ma_ho_so DESC LIMIT 1
        ");
        $stmt->execute([$nguoi_dung['ma_nguoi_dung']]);
        $ho_so = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ho_so && !empty($ho_so['tep_dinh_kem'])) {
            // Tách các tệp đính kèm bằng dấu chấm phẩy để lấy ảnh chân dung (vị trí số 1)
            $ds_tep = explode(';', $ho_so['tep_dinh_kem']);
            if (isset($ds_tep[1]) && trim($ds_tep[1]) !== '') {
                // Gán đường dẫn chuẩn để gọi hàm xemTep
                $_SESSION['anh_chan_dung'] = '/muc_dinh_danh/xem_tep?ma_ho_so=' . $ho_so['ma_ho_so'] . '&vi_tri=1';
            } else {
                $_SESSION['anh_chan_dung'] = '';
            }
        } else {
            // Nếu không có hồ sơ duyệt, kiểm tra trong bảng the_can_cuoc
            $stmt2 = $pdo->prepare("SELECT anh_chan_dung FROM the_can_cuoc WHERE ma_nguoi_dung = ? LIMIT 1");
            $stmt2->execute([$nguoi_dung['ma_nguoi_dung']]);
            $_SESSION['anh_chan_dung'] = $stmt2->fetchColumn() ?: '';
        }
    }
}

function layDiaChiIp() {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function taoMaCsrf() {
    if (empty($_SESSION['ma_csrf'])) {
        $_SESSION['ma_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['ma_csrf'];
}

function kiemTraCsrf() {
    $ma_gui = (string) ($_POST['ma_csrf'] ?? '');
    $ma_phien = (string) ($_SESSION['ma_csrf'] ?? '');
    if ($ma_phien === '' || !hash_equals($ma_phien, $ma_gui)) {
        http_response_code(403);
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(
                ['thanh_cong' => false, 'thong_bao' => 'Phiên hết hạn, hãy tải lại trang.'],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            echo 'Yêu cầu không hợp lệ';
        }
        exit;
    }
}

// Giữ tên cũ để code nào đang gọi kiemTraMaCsrf() vẫn chạy
function kiemTraMaCsrf() {
    kiemTraCsrf();
}

function e($chuoi) {
    return htmlspecialchars((string)$chuoi, ENT_QUOTES, 'UTF-8');
}