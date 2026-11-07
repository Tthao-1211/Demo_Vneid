
<?php
/**
 * Trang chi tiết thẻ căn cước
 *
 * Dữ liệu từ Controller:
 * @var array|null $the_can_cuoc
 * @var string|null $che_so_the
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$maVaiTro = (int)($_SESSION['ma_vai_tro'] ?? 0);
$maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);

// Phân quyền:
// 1: Công dân
// 2: Cán bộ xử lý
// 3: Quản trị viên
$laCanBo = ($maVaiTro === 2);

// Kiểm tra đây có phải thẻ của tài khoản đang đăng nhập không.
$laTheCuaToi = isset($the_can_cuoc['ma_nguoi_dung'])
    && (int)$the_can_cuoc['ma_nguoi_dung'] === $maNguoiDung;

// Hàm hiển thị dữ liệu an toàn.
$hienThi = function ($giaTri) {
    return htmlspecialchars(
        (string)($giaTri ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

// Hàm định dạng ngày tháng.
$dinhDangNgay = function ($ngay) {
    if (empty($ngay) || $ngay === '0000-00-00') {
        return 'Chưa cập nhật';
    }

    $timestamp = strtotime($ngay);

    return $timestamp !== false
        ? date('d/m/Y', $timestamp)
        : 'Chưa cập nhật';
};

// Thông tin trạng thái thẻ.
$trangThai = $the_can_cuoc['trang_thai'] ?? '';

$thongTinTrangThai = [
    'con_hieu_luc' => [
        'ten' => 'Còn hiệu lực',
        'class' => 'badge-active'
    ],
    'het_han' => [
        'ten' => 'Hết hạn',
        'class' => 'badge-expired'
    ],
    'bao_mat' => [
        'ten' => 'Báo mất',
        'class' => 'badge-lost'
    ],
    'thu_hoi' => [
        'ten' => 'Thu hồi',
        'class' => 'badge-revoked'
    ]
];

$trangThaiHienThi = $thongTinTrangThai[$trangThai] ?? [
    'ten' => $trangThai !== '' ? $trangThai : 'Chưa xác định',
    'class' => 'badge-unknown'
];

// Số thẻ: ưu tiên số đã được che từ Controller.
$soThe = $che_so_the ?? ($the_can_cuoc['so_the'] ?? '');

// Khóa chính của thẻ.
$maThe = (int)($the_can_cuoc['ma_the'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VNeID - Chi tiết thẻ căn cước</title>

    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">

</head>

<body>

    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <?php if (empty($the_can_cuoc)): ?>

        <div class="empty-container">
            <h2>Chưa có thông tin thẻ căn cước</h2>
            <p>Tài khoản hiện tại chưa có dữ liệu thẻ căn cước trong hệ thống.</p>

            <a href="/nguoi_dung/trang_chu"
               class="btn-action btn-close-home">
                Quay về trang chủ
            </a>
        </div>

    <?php else: ?>

        <div class="vneid-form-container">

            <h2 class="vneid-section-title">
                Chi tiết thẻ căn cước
            </h2>

            <p class="section-description">
                Thông tin căn cước công dân được lưu trữ trên hệ thống VNeID
            </p>

            <div class="info-section-title">
                THÔNG TIN CÁ NHÂN
            </div>

            <div class="info-row">
                <div class="info-label">Số định danh cá nhân</div>
                <div class="info-value identity-number">
                    <?= $hienThi($the_can_cuoc['so_dinh_danh'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Họ và tên</div>
                <div class="info-value" style="text-transform: uppercase;">
                    <?= $hienThi($the_can_cuoc['ho_ten'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Ngày sinh</div>
                <div class="info-value">
                    <?= $hienThi($dinhDangNgay($the_can_cuoc['ngay_sinh'] ?? null)) ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Giới tính</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['gioi_tinh'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Quốc tịch</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['quoc_tich'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Dân tộc</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['dan_toc'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Tôn giáo</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['ton_giao'] ?? '') ?>
                </div>
            </div>

            <div class="info-section-title">
                THÔNG TIN CƯ TRÚ
            </div>

            <div class="info-row">
                <div class="info-label">Quê quán</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['que_quan'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Nơi thường trú</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['noi_thuong_tru'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Tỉnh/Thành phố</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['tinh_thanh'] ?? '') ?>
                </div>
            </div>

            <div class="info-section-title">
                THÔNG TIN THẺ CĂN CƯỚC
            </div>

            <div class="info-row">
                <div class="info-label">Số thẻ</div>
                <div class="info-value">
                    <?= $hienThi($soThe) ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Ngày cấp thẻ</div>
                <div class="info-value">
                    <?= $hienThi($dinhDangNgay($the_can_cuoc['ngay_cap'] ?? null)) ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Nơi cấp</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['noi_cap'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Ngày hết hạn</div>
                <div class="info-value">
                    <?= $hienThi($dinhDangNgay($the_can_cuoc['ngay_het_han'] ?? null)) ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Đặc điểm nhận dạng</div>
                <div class="info-value">
                    <?= $hienThi($the_can_cuoc['dac_diem_nhan_dang'] ?? '') ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Trạng thái thẻ</div>
                <div class="info-value">
                    <span class="badge <?= $hienThi($trangThaiHienThi['class']) ?>">
                        <?= $hienThi($trangThaiHienThi['ten']) ?>
                    </span>
                </div>
            </div>

            <div class="vneid-action-bar">

                <?php if ($laTheCuaToi): ?>

                    <!-- Công dân, cán bộ và quản trị viên đều được yêu cầu cấp thẻ của mình. -->
                    <a href="/the_can_cuoc/yeu_cau_cap_the"
                       class="btn-action btn-request-card">
                        📄 Yêu cầu cấp thẻ
                    </a>

                    <!-- Cả ba vai trò đều được gửi yêu cầu điều chỉnh thông tin của mình. -->
                    <a href="/the_can_cuoc/yeu_cau_dieu_chinh"
                       class="btn-action btn-request-update">
                        ✏ Yêu cầu điều chỉnh
                    </a>

                    <a href="/nguoi_dung/trang_chu"
                       class="btn-action btn-close-home">
                        Đóng
                    </a>

                <?php elseif ($laCanBo): ?>

                    <!-- Chỉ cán bộ được quản lý thẻ của người khác. -->
                    <a href="/the_can_cuoc/quan_ly_trang_thai?id=<?= $maThe ?>"
                       class="btn-action btn-manage-status">
                        ⚙ Quản lý trạng thái
                    </a>

                    <a href="/the_can_cuoc/cap_nhat?id=<?= $maThe ?>"
                       class="btn-action btn-request-update">
                        ✏ Cập nhật hồ sơ
                    </a>

                    <a href="/the_can_cuoc/danh_sach"
                       class="btn-action btn-close-home">
                        Đóng
                    </a>

                <?php else: ?>

                    <a href="/nguoi_dung/trang_chu"
                       class="btn-action btn-close-home">
                        Đóng
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>

</body>
</html>
