<?php
$ds_nguoi_dung = $ds_nguoi_dung ?? [];
$loi = $loi ?? '';
$cu = fn(string $k, string $mac = '') => htmlspecialchars($_POST[$k] ?? $mac);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm mới Thẻ căn cước - VNeID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-danh-sach.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-form.css">
</head>
<body>
<?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

<div class="app-shell">
    <main class="main">
        <div class="page-head">
            <div>
                <h1 class="page-title">Thêm mới thẻ căn cước</h1>
                <p class="page-sub">Nhập thông tin hồ sơ cho tài khoản công dân chưa có thẻ</p>
            </div>
            <a href="/the_can_cuoc/danh_sach" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Danh sách</a>
        </div>

        <section class="panel">
            <?php if ($loi !== ''): ?>
                <div class="alert-error">⚠️ <?= htmlspecialchars($loi) ?></div>
            <?php endif; ?>

            <form action="/the_can_cuoc/them_moi" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <!-- Nhóm 1 -->
                <div class="form-section">
                    <h3 class="form-section-title">Thông tin định danh</h3>
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label>Tài khoản công dân<span class="req">*</span></label>
                            <select name="ma_nguoi_dung" required>
                                <option value="">-- Chọn tài khoản chưa có thẻ --</option>
                                <?php foreach ($ds_nguoi_dung as $nd): ?>
                                    <option value="<?= (int)$nd['ma_nguoi_dung'] ?>"
                                        <?= ((int)($_POST['ma_nguoi_dung'] ?? 0) === (int)$nd['ma_nguoi_dung']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($nd['ho_ten']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-hint">Họ tên lấy theo tài khoản đã chọn.</div>
                        </div>

                        <div class="form-group span-2">
                            <label>Số định danh cá nhân (12 chữ số)<span class="req">*</span></label>
                            <input type="text" name="so_the" maxlength="12" pattern="\d{12}" inputmode="numeric"
                                   required placeholder="Nhập đủ 12 chữ số" value="<?= $cu('so_the') ?>">
                        </div>

                        <div class="form-group">
                            <label>Ngày, tháng, năm sinh<span class="req">*</span></label>
                            <input type="date" name="ngay_sinh" required value="<?= $cu('ngay_sinh') ?>">
                        </div>

                        <div class="form-group">
                            <label>Giới tính<span class="req">*</span></label>
                            <select name="gioi_tinh" required>
                                <?php foreach (['Nam', 'Nữ'] as $gt): ?>
                                    <option value="<?= $gt ?>" <?= ($_POST['gioi_tinh'] ?? 'Nam') === $gt ? 'selected' : '' ?>><?= $gt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quốc tịch<span class="req">*</span></label>
                            <input type="text" name="quoc_tich" required value="<?= $cu('quoc_tich', 'Việt Nam') ?>">
                        </div>

                        <div class="form-group">
                            <label>Dân tộc</label>
                            <input type="text" name="dan_toc" value="<?= $cu('dan_toc', 'Kinh') ?>">
                        </div>

                        <div class="form-group">
                            <label>Tôn giáo</label>
                            <input type="text" name="ton_giao" value="<?= $cu('ton_giao', 'Không') ?>">
                        </div>

                        <div class="form-group">
                            <label>Đặc điểm nhận dạng</label>
                            <input type="text" name="dac_diem_nhan_dang" placeholder="Ví dụ: sẹo chấm cạnh mắt trái"
                                   value="<?= $cu('dac_diem_nhan_dang') ?>">
                        </div>
                    </div>
                </div>

                <!-- Nhóm 2 -->
                <div class="form-section">
                    <h3 class="form-section-title">Nơi sinh sống</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Quê quán<span class="req">*</span></label>
                            <select name="que_quan" required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                <?php foreach (DS_TINH_THANH as $tinh): ?>
                                    <option value="<?= htmlspecialchars($tinh) ?>" <?= ($_POST['que_quan'] ?? '') === $tinh ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tinh) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tỉnh/Thành phố cư trú</label>
                            <select name="tinh_thanh">
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                <?php foreach (DS_TINH_THANH as $tinh): ?>
                                    <option value="<?= htmlspecialchars($tinh) ?>" <?= ($_POST['tinh_thanh'] ?? '') === $tinh ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tinh) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group span-2">
                            <label>Nơi thường trú<span class="req">*</span></label>
                            <input type="text" name="noi_thuong_tru" required placeholder="Địa chỉ thường trú"
                                   value="<?= $cu('noi_thuong_tru') ?>">
                        </div>
                    </div>
                </div>

                <!-- Nhóm 3 -->
                <div class="form-section">
                    <h3 class="form-section-title">Thông tin cấp thẻ</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Ngày cấp thẻ<span class="req">*</span></label>
                            <input type="date" name="ngay_cap" required value="<?= $cu('ngay_cap') ?>">
                        </div>

                        <div class="form-group">
                            <label>Ngày hết hạn<span class="req">*</span></label>
                            <input type="date" name="ngay_het_han" required value="<?= $cu('ngay_het_han') ?>">
                        </div>

                        <div class="form-group">
                            <label>Nơi cấp<span class="req">*</span></label>
                            <input type="text" name="noi_cap" required
                                   value="<?= $cu('noi_cap', 'Cục Cảnh sát QLHC về TTXH') ?>">
                        </div>

                        <div class="form-group">
                            <label>Trạng thái<span class="req">*</span></label>
                            <select name="trang_thai" required>
                                <?php foreach (['con_hieu_luc' => 'Còn hiệu lực', 'het_han' => 'Hết hạn',
                                                'bao_mat' => 'Báo mất', 'thu_hoi' => 'Thu hồi'] as $gt => $nhan): ?>
                                    <option value="<?= $gt ?>" <?= ($_POST['trang_thai'] ?? 'con_hieu_luc') === $gt ? 'selected' : '' ?>><?= $nhan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/the_can_cuoc/danh_sach" class="btn-cancel">Hủy bỏ</a>
                    <button type="submit" class="btn-submit">Lưu hồ sơ căn cước</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>