
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$the = $the ?? [];
$yeuCauDaGui = $yeuCauDaGui ?? null;
$csrf_token = $csrf_token ?? '';

$e = function ($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$ngayHienTai = date('d/m/Y');

$trangThaiMap = [
    'cho_tiep_nhan' => 'Chờ tiếp nhận',
    'cho_xu_ly' => 'Chờ xử lý',
    'da_duyet' => 'Đã duyệt',
    'tu_choi' => 'Từ chối'
];

$trangThai = $yeuCauDaGui['trang_thai'] ?? '';
$tenTrangThai = $trangThaiMap[$trangThai] ?? 'Chưa xác định';

$maVaiTro = (int)($_SESSION['ma_vai_tro'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đề nghị điều chỉnh thông tin căn cước</title>
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">

    <style>
        /* Chỉ bổ sung những kiểu chưa có trong CSS chi tiết */
        .adjustment-form .form-group {
            margin-bottom: 18px;
        }

        .adjustment-form label {
            display: block;
            margin-bottom: 7px;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }

        .adjustment-form input,
        .adjustment-form select,
        .adjustment-form textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            font-family: inherit;
            color: #222;
            background: #fff;
        }

        .adjustment-form input:focus,
        .adjustment-form select:focus,
        .adjustment-form textarea:focus {
            outline: none;
            border-color: #e3261f;
            box-shadow: 0 0 0 2px rgba(227, 38, 31, 0.1);
        }

        .adjustment-form textarea {
            min-height: 100px;
            resize: vertical;
        }

        .adjustment-form .required {
            color: #e3261f;
        }

        .adjustment-form .form-note {
            margin-top: 6px;
            color: #8e8e93;
            font-size: 12px;
        }

        .adjustment-form .readonly-field {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .adjustment-form .btn-submit {
            background: #e3261f;
            color: #fff;
        }

        .adjustment-form .btn-submit:hover {
            background: #c91e17;
        }

        .adjustment-form .notice {
            padding: 13px 15px;
            margin: 18px 0;
            border-radius: 8px;
            background: #fef3c7;
            color: #92400e;
            font-size: 13px;
            line-height: 1.6;
        }

        .adjustment-form .receipt-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            background: #fef3c7;
            color: #92400e;
            font-size: 13px;
            font-weight: 600;
        }

        .adjustment-form .receipt-status.approved {
            background: #e6f4ea;
            color: #137333;
        }

        .adjustment-form .receipt-status.rejected {
            background: #fce8e6;
            color: #c5221f;
        }

        .adjustment-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 600px) {
            .adjustment-form .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

<div class="vneid-form-container">

    <a href="/the_can_cuoc/chi_tiet" class="btn-back-arrow">
        ← Quay lại thông tin căn cước
    </a>

    <h1 class="vneid-section-title">
        ĐỀ NGHỊ ĐIỀU CHỈNH THÔNG TIN
    </h1>

    <p class="section-description">
        Gửi yêu cầu cập nhật thông tin trên thẻ căn cước
    </p>

    <?php if (!empty($yeuCauDaGui)): ?>

        <?php
        $classTrangThai = '';

        if ($trangThai === 'da_duyet') {
            $classTrangThai = 'approved';
        } elseif ($trangThai === 'tu_choi') {
            $classTrangThai = 'rejected';
        }
        ?>

        <h3 class="info-section-title">
            Thông tin đề nghị đã gửi
        </h3>

        <div class="info-row">
            <span class="info-label">Số định danh</span>
            <span class="info-value identity-number">
                <?= $e($yeuCauDaGui['so_dinh_danh']
                    ?? $yeuCauDaGui['so_cccd']
                    ?? $the['so_dinh_danh']
                    ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Họ và tên mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['ho_ten_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Ngày sinh mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['ngay_sinh_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Giới tính mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['gioi_tinh_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Quốc tịch mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['quoc_tich_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Dân tộc mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['dan_toc_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Tôn giáo mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['ton_giao_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Quê quán mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['que_quan_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Nơi thường trú mới</span>
            <span class="info-value">
                <?= $e($yeuCauDaGui['noi_thuong_tru_moi'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Lý do điều chỉnh</span>
            <span class="info-value">
                <?= nl2br($e($yeuCauDaGui['ly_do'] ?? '')) ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Trạng thái</span>
            <span class="info-value">
                <span class="receipt-status <?= $classTrangThai ?>">
                    <?= $e($tenTrangThai) ?>
                </span>
            </span>
        </div>

        <div class="vneid-action-bar">
            <a href="/the_can_cuoc/chiTiet"
               class="btn-action btn-close-home">
                Quay lại
            </a>
        </div>

    <?php else: ?>

        <div class="notice">
            <strong>Lưu ý:</strong>
            Vui lòng nhập thông tin mới mà bạn muốn điều chỉnh.
            Yêu cầu sẽ được chuyển đến cán bộ có thẩm quyền để xem xét.
            Thông tin trên thẻ hiện tại không bị thay đổi ngay khi gửi đề nghị.
        </div>

        <h3 class="info-section-title">
            I. Thông tin cá nhân
        </h3>

        <div class="info-row">
            <span class="info-label">Số định danh cá nhân</span>
            <span class="info-value identity-number">
                <?= $e($the['so_dinh_danh'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Họ và tên hiện tại</span>
            <span class="info-value">
                <?= $e($the['ho_ten'] ?? '') ?>
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Ngày gửi đề nghị</span>
            <span class="info-value">
                <?= $ngayHienTai ?>
            </span>
        </div>

        <h3 class="info-section-title">
            II. Nội dung đề nghị điều chỉnh
        </h3>

        <form class="adjustment-form"
              method="POST"
              action="/the_can_cuoc/yeu_cau_dieu_chinh"
              enctype="multipart/form-data">
            <input type="hidden"
                   name="csrf_token"
                   value="<?= $e($csrf_token) ?>">

            <div class="form-group">
                <label for="ho_ten_moi">
                    Họ và tên mới <span class="required">*</span>
                </label>
                <input type="text"
                       id="ho_ten_moi"
                       name="ho_ten_moi"
                       maxlength="100"
                       placeholder="Nhập họ và tên muốn điều chỉnh"
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ngay_sinh_moi">
                        Ngày sinh mới
                    </label>
                    <input type="date"
                           id="ngay_sinh_moi"
                           name="ngay_sinh_moi">
                </div>

                <div class="form-group">
                    <label for="gioi_tinh_moi">
                        Giới tính mới
                    </label>
                    <select id="gioi_tinh_moi"
                            name="gioi_tinh_moi">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="quoc_tich_moi">Quốc tịch mới</label>
                <input type="text"
                       id="quoc_tich_moi"
                       name="quoc_tich_moi"
                       placeholder="Nhập quốc tịch mới">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="dan_toc_moi">Dân tộc mới</label>
                    <input type="text"
                           id="dan_toc_moi"
                           name="dan_toc_moi"
                           placeholder="Nhập dân tộc mới">
                </div>

                <div class="form-group">
                    <label for="ton_giao_moi">Tôn giáo mới</label>
                    <input type="text"
                           id="ton_giao_moi"
                           name="ton_giao_moi"
                           placeholder="Nhập tôn giáo mới">
                </div>
            </div>

            <div class="form-group">
                <label for="que_quan_moi">Quê quán mới</label>
                <textarea id="que_quan_moi"
                          name="que_quan_moi"
                          placeholder="Nhập quê quán mới"></textarea>
            </div>

            <div class="form-group">
                <label for="noi_thuong_tru_moi">
                    Nơi thường trú mới
                </label>
                <textarea id="noi_thuong_tru_moi"
                          name="noi_thuong_tru_moi"
                          placeholder="Nhập nơi thường trú mới"></textarea>
            </div>

            <div class="form-group">
                <label for="anh_chan_dung_moi">
                    Ảnh chân dung mới (nếu có)
                </label>
                <input type="file"
                       id="anh_chan_dung_moi"
                       name="anh_chan_dung_moi"
                       accept="image/jpeg,image/png,image/webp">

                <p class="form-note">
                    Chỉ chọn ảnh JPG, PNG hoặc WEBP.
                </p>
            </div>

            <div class="form-group">
                <label for="ly_do">
                    Lý do đề nghị điều chỉnh
                    <span class="required">*</span>
                </label>
                <textarea id="ly_do"
                          name="ly_do"
                          required
                          placeholder="Trình bày rõ lý do cần điều chỉnh thông tin..."></textarea>
            </div>

            <div class="vneid-action-bar">
                <button type="submit"
                        class="btn-action btn-submit">
                    Gửi đề nghị điều chỉnh
                </button>
            </div>
        </form>

    <?php endif; ?>

</div>

</body>
</html>