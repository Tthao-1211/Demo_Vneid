<?php
function yeuCauCanBo(): array {
    if (empty($_SESSION['ma_nguoi_dung'])
        || (int)($_SESSION['ma_vai_tro'] ?? 0) !== VAI_TRO_CAN_BO) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập khu vực cán bộ.');
    }
    $_SESSION['can_bo'] = [
        'ma_nguoi_dung' => (int) $_SESSION['ma_nguoi_dung'],
        'ho_ten'        => $_SESSION['ho_ten'] ?? '',
    ];
    return $_SESSION['can_bo'];
}

function chuyenTrang(string $trang, array $q = []) {
    header('Location: /can_bo/' . $trang . ($q ? '?' . http_build_query($q) : ''));
    exit;
}
function csrf(): string {
    return taoMaCsrf();
}

function flash(?string $msg = null, string $loai = 'ok'): ?array {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $loai]; return null; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}