<?php
$loi = $loi ?? '';
$t = $the_can_cuoc;

// Từ trang chi tiết yêu cầu điều chỉnh: các giá trị công dân đề nghị + mã yêu cầu
$de_xuat    = $de_xuat ?? [];
$yeu_cau_id = (int)($yeu_cau_id ?? 0);

// Ưu tiên: dữ liệu vừa nhập (lưu lỗi) > giá trị đề nghị > dữ liệu trong DB
$v = fn(string $k) => htmlspecialchars($_POST[$k] ?? ($de_xuat[$k] ?? ($t[$k] ?? '')));
$chon = fn(string $k, string $gt) => (($_POST[$k] ?? ($de_xuat[$k] ?? ($t[$k] ?? ''))) === $gt) ? 'selected' : '';

// ----- Tìm các trường khác bản gốc -----
$truongSoSanh = ['ho_ten', 'ngay_sinh', 'gioi_tinh', 'quoc_tich', 'dan_toc', 'ton_giao',
                 'dac_diem_nhan_dang', 'que_quan', 'tinh_thanh', 'noi_thuong_tru'];
$chuan = function ($k, $x) {
    $x = trim((string)$x);
    if ($k === 'ngay_sinh' && $x !== '' && ($ts = strtotime($x)) !== false) return date('Y-m-d', $ts);
    return $x;
};
$doi = []; // [tên trường => giá trị bản gốc]
foreach ($truongSoSanh as $k) {
    if (!isset($de_xuat[$k]) || trim((string)$de_xuat[$k]) === '') continue;
    if ($chuan($k, $de_xuat[$k]) !== $chuan($k, $t[$k] ?? '')) {
        $doi[$k] = trim((string)($t[$k] ?? ''));
    }
}
$cls  = fn(string $k) => isset($doi[$k]) ? ' is-changed' : '';
$goc  = function (string $k) use ($doi) {
    if (!isset($doi[$k])) return '';
    $g = $doi[$k];
    if ($k === 'ngay_sinh' && $g !== '' && ($ts = strtotime($g)) !== false) $g = date('d/m/Y', $ts);
    return '<small class="goc-hint"><i class="fa-solid fa-clock-rotate-left"></i> Bản gốc: <s>'
         . htmlspecialchars($g !== '' ? $g : 'Trống') . '</s></small>';
};
$moi_nhan = fn(string $k) => isset($doi[$k]) ? '<span class="tag-moi">Thay đổi</span>' : '';

$action = '/the_can_cuoc/cap_nhat?id=' . (int)$t['ma_the'] . ($yeu_cau_id > 0 ? '&yeu_cau=' . $yeu_cau_id : '');
$quay_lai = $yeu_cau_id > 0 ? '/the_can_cuoc/chi_tiet_yeu_cau_dieu_chinh?id=' . $yeu_cau_id : '/the_can_cuoc/danh_sach';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cập nhật Thẻ căn cước - VNeID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-danh-sach.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-form.css">
    <style>
        .form-group input[readonly]{ background:#f3f4f6; color:#6b7280; cursor:not-allowed; }

        /* Banner yêu cầu điều chỉnh */
        .yc-banner{display:flex;gap:12px;align-items:flex-start;padding:14px 16px;margin-bottom:20px;border-radius:12px;background:#fffaf0;border:1px solid #f3dfb3;color:#6b4a00;font-size:14px;line-height:1.5}
        .yc-banner i{margin-top:3px;color:#c58a00}
        .yc-banner strong{color:#8e2a30}

        /* Ô bị thay đổi so với bản gốc */
        .form-group.is-changed input,
        .form-group.is-changed select{background:#fffaf0;border-color:#e0a800;box-shadow:0 0 0 3px rgba(224,168,0,.18);font-weight:600}
        .form-group.is-changed label{color:#8e2a30}
        .tag-moi{display:inline-block;margin-left:8px;padding:2px 8px;border-radius:999px;background:#8e2a30;color:#fff;font-size:11px;font-weight:700;vertical-align:middle}
        .goc-hint{display:block;margin-top:6px;font-size:12.5px;color:#8a7f7f}
        .goc-hint i{margin-right:4px}
        .goc-hint s{color:#6b6060;text-decoration-thickness:1.5px}
    </style>
</head>
<body>
<?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

<div class="app-shell">
    <main class="main">
        <div class="page-head">
            <div>
                <h1 class="page-title">Cập nhật thẻ căn cước</h1>
                <p class="page-sub"><?= htmlspecialchars($t['ho_ten'] ?? '') ?> · <?= htmlspecialchars($t['so_the'] ?? '') ?></p>
            </div>
            <a href="<?= htmlspecialchars($quay_lai) ?>" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
        </div>

        <section class="panel">
            <?php if ($loi !== ''): ?>
                <div class="alert-error">⚠️ <?= htmlspecialchars($loi) ?></div>
            <?php endif; ?>

            <?php if ($yeu_cau_id > 0): ?>
                <div class="yc-banner">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>
                        Đang xử lý yêu cầu điều chỉnh <strong>#<?= $yeu_cau_id ?></strong>.
                        Các ô đã được điền sẵn theo đề nghị của công dân.
                        <?php if ($doi): ?>
                            Có <strong><?= count($doi) ?></strong> thông tin thay đổi, được tô nổi bên dưới kèm giá trị bản gốc.
                        <?php else: ?>
                            Không có thông tin nào khác so với bản gốc.
                        <?php endif; ?>
                        Sau khi lưu, yêu cầu sẽ tự chuyển sang <strong>Đã duyệt</strong>.
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars($action) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="form-section">
                    <h3 class="form-section-title">Thông tin định danh</h3>
                    <div class="form-grid">
                        <div class="form-group<?= $cls('ho_ten') ?>">
                            <label>Họ và tên<span class="req">*</span><?= $moi_nhan('ho_ten') ?></label>
                            <input type="text" name="ho_ten" required maxlength="100" value="<?= $v('ho_ten') ?>">
                            <?= $goc('ho_ten') ?>
                            <small class="goc-hint">Đổi họ tên sẽ cập nhật cả tài khoản của công dân.</small>
                        </div>

                        <div class="form-group">
                            <label>Số định danh cá nhân</label>
                            <input type="text" name="so_the" value="<?= htmlspecialchars($t['so_the']) ?>" readonly>
                        </div>

                        <div class="form-group<?= $cls('ngay_sinh') ?>">
                            <label>Ngày, tháng, năm sinh<span class="req">*</span><?= $moi_nhan('ngay_sinh') ?></label>
                            <input type="date" name="ngay_sinh" required value="<?= $v('ngay_sinh') ?>">
                            <?= $goc('ngay_sinh') ?>
                        </div>

                        <div class="form-group<?= $cls('gioi_tinh') ?>">
                            <label>Giới tính<span class="req">*</span><?= $moi_nhan('gioi_tinh') ?></label>
                            <select name="gioi_tinh" required>
                                <option value="Nam" <?= $chon('gioi_tinh', 'Nam') ?>>Nam</option>
                                <option value="Nữ"  <?= $chon('gioi_tinh', 'Nữ') ?>>Nữ</option>
                            </select>
                            <?= $goc('gioi_tinh') ?>
                        </div>

                        <div class="form-group<?= $cls('quoc_tich') ?>">
                            <label>Quốc tịch<span class="req">*</span><?= $moi_nhan('quoc_tich') ?></label>
                            <input type="text" name="quoc_tich" required value="<?= $v('quoc_tich') ?>">
                            <?= $goc('quoc_tich') ?>
                        </div>

                        <div class="form-group<?= $cls('dan_toc') ?>">
                            <label>Dân tộc<?= $moi_nhan('dan_toc') ?></label>
                            <input type="text" name="dan_toc" value="<?= $v('dan_toc') ?>">
                            <?= $goc('dan_toc') ?>
                        </div>

                        <div class="form-group<?= $cls('ton_giao') ?>">
                            <label>Tôn giáo<?= $moi_nhan('ton_giao') ?></label>
                            <input type="text" name="ton_giao" value="<?= $v('ton_giao') ?>">
                            <?= $goc('ton_giao') ?>
                        </div>

                        <div class="form-group<?= $cls('dac_diem_nhan_dang') ?>">
                            <label>Đặc điểm nhận dạng<?= $moi_nhan('dac_diem_nhan_dang') ?></label>
                            <input type="text" name="dac_diem_nhan_dang" value="<?= $v('dac_diem_nhan_dang') ?>">
                            <?= $goc('dac_diem_nhan_dang') ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Nơi sinh sống</h3>
                    <div class="form-grid">
                        <div class="form-group<?= $cls('que_quan') ?>">
                            <label>Quê quán<span class="req">*</span><?= $moi_nhan('que_quan') ?></label>
                            <input type="text" name="que_quan" required placeholder="Ví dụ: Khánh Hòa" value="<?= $v('que_quan') ?>">
                            <?= $goc('que_quan') ?>
                        </div>

                        <div class="form-group<?= $cls('tinh_thanh') ?>">
                            <label>Tỉnh/Thành phố cư trú<?= $moi_nhan('tinh_thanh') ?></label>
                            <input type="text" name="tinh_thanh" placeholder="Ví dụ: Khánh Hòa" value="<?= $v('tinh_thanh') ?>">
                            <?= $goc('tinh_thanh') ?>
                        </div>

                        <div class="form-group span-2<?= $cls('noi_thuong_tru') ?>">
                            <label>Nơi thường trú<span class="req">*</span><?= $moi_nhan('noi_thuong_tru') ?></label>
                            <input type="text" name="noi_thuong_tru" required value="<?= $v('noi_thuong_tru') ?>">
                            <?= $goc('noi_thuong_tru') ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Thông tin cấp thẻ</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Ngày cấp<span class="req">*</span></label>
                            <input type="date" name="ngay_cap" required value="<?= htmlspecialchars($_POST['ngay_cap'] ?? ($t['ngay_cap'] ?? '')) ?>">
                        </div>

                        <div class="form-group">
                            <label>Ngày hết hạn<span class="req">*</span></label>
                            <input type="date" name="ngay_het_han" required value="<?= htmlspecialchars($_POST['ngay_het_han'] ?? ($t['ngay_het_han'] ?? '')) ?>">
                        </div>

                        <div class="form-group">
                            <label>Nơi cấp<span class="req">*</span></label>
                            <input type="text" name="noi_cap" required value="<?= htmlspecialchars($_POST['noi_cap'] ?? ($t['noi_cap'] ?? '')) ?>">
                        </div>

                        <div class="form-group">
                            <label>Trạng thái<span class="req">*</span></label>
                            <select name="trang_thai" required>
                                <?php foreach (['con_hieu_luc' => 'Còn hiệu lực', 'het_han' => 'Hết hạn',
                                                'bao_mat' => 'Báo mất', 'thu_hoi' => 'Thu hồi'] as $gt => $nhan): ?>
                                    <option value="<?= $gt ?>" <?= (($_POST['trang_thai'] ?? ($t['trang_thai'] ?? '')) === $gt) ? 'selected' : '' ?>><?= $nhan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cc-actions">
                    <a href="<?= htmlspecialchars($quay_lai) ?>" class="cc-btn-cancel">Hủy bỏ</a>
                    <button type="submit" class="cc-btn-save"><i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>