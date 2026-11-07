
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nhận dữ liệu từ Controller, đồng thời hỗ trợ tên biến cũ.
$the = $the ?? $the_can_cuoc ?? [];
$thong_bao_ket_qua = $thong_bao_ket_qua ?? null;
$ds_yeu_cau_gan_day = $ds_yeu_cau_gan_day ?? [];

$loaiYeuCauMacDinh = $loaiYeuCauMacDinh ?? '';
$lyDoMacDinh = $lyDoMacDinh ?? '';

$csrf_token = $csrf_token ?? '';

$e = function ($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$loaiYeuCauMap = [
    'cap_moi' => 'Cấp mới',
    'cap_doi' => 'Cấp đổi',
    'cap_lai' => 'Cấp lại',
    'bao_mat' => 'Báo mất thẻ'
];

$maVaiTro = (int)($_SESSION['ma_vai_tro'] ?? 0);
$maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VNeID - Yêu cầu cấp thẻ căn cước</title>

    <!-- CSS dùng chung với trang chi tiết căn cước -->
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">

    <style>
        .vneid-form-container {
            max-width: 850px;
        }

        .form-description {
            text-align: center;
            color: #8e8e93;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .form-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #e3261f;
            margin: 25px 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f2f2f7;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            min-width: 0;
        }

        .form-group.span-2 {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #4b5563;
            font-size: 14px;
            font-weight: 600;
        }

        .required {
            color: #e3261f;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
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

        .form-group textarea {
            min-height: 85px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e3261f;
            box-shadow: 0 0 0 2px rgba(227, 38, 31, .1);
        }

        .form-group input[readonly] {
            background: #f3f4f6;
            color: #6b7280;
        }

        .form-note {
            margin-top: 6px;
            color: #8e8e93;
            font-size: 12px;
        }

        .type-selector {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .type-option {
            display: block;
            padding: 16px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: .2s;
            background: #fff;
        }

        .type-option:hover {
            border-color: #e3261f;
        }

        .type-option.active {
            border: 2px solid #e3261f;
            background: #fff8f7;
        }

        .type-option input {
            margin-right: 6px;
            accent-color: #e3261f;
        }

        .type-title {
            display: inline;
            font-size: 14px;
            font-weight: 700;
            color: #222;
        }

        .type-desc {
            display: block;
            margin-top: 8px;
            color: #8e8e93;
            font-size: 12px;
            line-height: 1.5;
        }

        .notice {
            padding: 13px 15px;
            margin: 18px 0;
            border-radius: 8px;
            background: #fef7e0;
            color: #92400e;
            font-size: 13px;
            line-height: 1.6;
        }

        .btn-submit {
            background: #e3261f;
            color: #fff;
        }

        .btn-submit:hover {
            background: #c91e17;
            color: #fff;
        }

        .receipt-card {
            padding: 24px;
            margin: 20px 0;
            text-align: center;
            border: 1px dashed #22c55e;
            border-radius: 10px;
            background: #f0fdf4;
        }

        .receipt-icon {
            font-size: 36px;
            margin-bottom: 8px;
        }

        .receipt-title {
            color: #15803d;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .receipt-code {
            display: inline-block;
            padding: 9px 18px;
            margin: 10px 0;
            border: 1px solid #86efac;
            border-radius: 7px;
            background: #fff;
            color: #166534;
            font-size: 18px;
            font-weight: 800;
        }

        .receipt-desc {
            color: #374151;
            font-size: 13px;
            line-height: 1.7;
        }

        .readonly-request {
            display: none;
            margin-top: 20px;
            padding: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .readonly-request.show {
            display: block;
        }

        .readonly-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .readonly-field {
            padding: 11px;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            background: #fff;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .readonly-field.span-2 {
            grid-column: span 2;
        }

        .readonly-field strong {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 12px;
        }

        .history-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .history-title {
            margin-bottom: 15px;
            color: #1f2937;
            font-size: 16px;
            font-weight: 700;
        }

        .history-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .history-table th {
            padding: 11px;
            background: #f3f4f6;
            color: #4b5563;
            text-align: left;
            white-space: nowrap;
        }

        .history-table td {
            padding: 11px;
            border-bottom: 1px solid #e5e7eb;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-done {
            background: #dcfce7;
            color: #166534;
        }

        .badge-rejected {
            background: #fce8e6;
            color: #c5221f;
        }

        @media (max-width: 600px) {
            .form-grid,
            .type-selector,
            .readonly-grid {
                grid-template-columns: 1fr;
            }

            .form-group.span-2,
            .readonly-field.span-2 {
                grid-column: auto;
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

        <h2 class="vneid-section-title">
            Yêu cầu cấp thẻ căn cước
        </h2>

        <p class="form-description">
            Dịch vụ công trực tuyến mô phỏng trên nền tảng VNeID
        </p>

        <?php if (!empty($thong_bao_ket_qua)): ?>

            <!-- Biên nhận sau khi nộp hồ sơ -->
            <div class="receipt-card">
                <div class="receipt-icon">🎉</div>

                <div class="receipt-title">
                    Hồ sơ đã được tiếp nhận thành công!
                </div>

                <div>Mã hồ sơ điện tử:</div>

                <div class="receipt-code">
                    <?= $e($thong_bao_ket_qua['ma_ho_so'] ?? '') ?>
                </div>

                <div class="receipt-desc">
                    Hồ sơ của công dân
                    <strong>
                        <?= $e($thong_bao_ket_qua['ho_ten'] ?? '') ?>
                    </strong>
                    (Số định danh:
                    <?= $e($thong_bao_ket_qua['so_dinh_danh']
                        ?? $thong_bao_ket_qua['so_cccd']
                        ?? '') ?>)
                    đã được chuyển đến cơ quan Công an thụ lý.
                    <br>

                    Thời gian tiếp nhận:
                    <?= $e($thong_bao_ket_qua['ngay_tiep_nhan'] ?? '') ?>.
                </div>
            </div>

            <div class="vneid-action-bar">
                <a href="/the_can_cuoc/chiTiet"
                   class="btn-action btn-close-home">
                    Quay lại
                </a>

                <button type="button"
                        class="btn-action btn-request-card"
                        onclick="hienYeuCauDaNop()">
                    Xem thông tin đã gửi
                </button>
            </div>

            <div class="readonly-request" id="yeu-cau-da-nop">
                <h3 style="margin-top:0;text-align:center">
                    Thông tin yêu cầu đã gửi
                </h3>

                <div class="readonly-grid">

                    <div class="readonly-field">
                        <strong>Mã hồ sơ</strong>
                        <?= $e($thong_bao_ket_qua['ma_ho_so'] ?? '') ?>
                    </div>

                    <div class="readonly-field">
                        <strong>Loại thủ tục</strong>
                        <?= $e(
                            $loaiYeuCauMap[
                                $thong_bao_ket_qua['loai_yeu_cau'] ?? ''
                            ] ?? 'Chưa xác định'
                        ) ?>
                    </div>

                    <div class="readonly-field">
                        <strong>Họ tên</strong>
                        <?= $e($thong_bao_ket_qua['ho_ten'] ?? '') ?>
                    </div>

                    <div class="readonly-field">
                        <strong>Số định danh</strong>
                        <?= $e(
                            $thong_bao_ket_qua['so_dinh_danh']
                            ?? $thong_bao_ket_qua['so_cccd']
                            ?? ''
                        ) ?>
                    </div>

                    <div class="readonly-field">
                        <strong>Số điện thoại</strong>
                        <?= $e($thong_bao_ket_qua['so_dien_thoai'] ?? '') ?>
                    </div>

                    <div class="readonly-field">
                        <strong>Hình thức nhận</strong>
                        <?= ($thong_bao_ket_qua['hinh_thuc_nhan'] ?? '') === 'buu_dien'
                            ? 'Bưu điện'
                            : 'Trực tiếp' ?>
                    </div>

                    <div class="readonly-field span-2">
                        <strong>Địa chỉ nhận</strong>
                        <?= $e(
                            $thong_bao_ket_qua['dia_chi_nhan']
                            ?? 'Nhận trực tiếp tại cơ quan Công an'
                        ) ?>
                    </div>

                    <div class="readonly-field span-2">
                        <strong>Lý do đề nghị</strong>
                        <?= $e($thong_bao_ket_qua['ly_do'] ?? '') ?>
                    </div>

                    <div class="readonly-field span-2">
                        <strong>Ghi chú</strong>
                        <?= $e(
                            $thong_bao_ket_qua['ghi_chu']
                            ?? 'Không có ghi chú'
                        ) ?>
                    </div>

                </div>
            </div>

        <?php else: ?>

            <div class="notice">
                <strong>Lưu ý:</strong>
                Vui lòng chọn đúng loại thủ tục và cung cấp thông tin liên hệ
                chính xác. Hồ sơ sẽ được chuyển đến cán bộ có thẩm quyền
                để xem xét và xử lý.
            </div>

            <form method="POST"
                  action="/the_can_cuoc/yeu_cau_cap_the"
                  id="formCapThe">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= $e($csrf_token) ?>">

                <h3 class="form-section-title">
                    I. Loại thủ tục đề nghị
                </h3>

                <div class="form-grid">

                    <div class="form-group span-2">
                        <label>
                            Loại thủ tục đề nghị cấp thẻ
                            <span class="required">*</span>
                        </label>

                        <div class="type-selector">

                            <label class="type-option <?= ($loaiYeuCauMacDinh === '' || $loaiYeuCauMacDinh === 'cap_moi') ? 'active' : '' ?>">
                                <input type="radio"
                                       name="loai_yeu_cau"
                                       value="cap_moi"
                                       <?= ($loaiYeuCauMacDinh === '' || $loaiYeuCauMacDinh === 'cap_moi') ? 'checked' : '' ?>
                                       onchange="selectType(this)">

                                <span class="type-title">Cấp mới</span>

                                <span class="type-desc">
                                    Công dân chưa từng được cấp thẻ căn cước.
                                </span>
                            </label>

                            <label class="type-option <?= $loaiYeuCauMacDinh === 'cap_doi' ? 'active' : '' ?>">
                                <input type="radio"
                                       name="loai_yeu_cau"
                                       value="cap_doi"
                                       <?= $loaiYeuCauMacDinh === 'cap_doi' ? 'checked' : '' ?>
                                       onchange="selectType(this)">

                                <span class="type-title">Cấp đổi</span>

                                <span class="type-desc">
                                    Thẻ hết hạn, hư hỏng hoặc cần thay đổi thông tin.
                                </span>
                            </label>

                            <label class="type-option <?= $loaiYeuCauMacDinh === 'cap_lai' ? 'active' : '' ?>">
                                <input type="radio"
                                       name="loai_yeu_cau"
                                       value="cap_lai"
                                       <?= $loaiYeuCauMacDinh === 'cap_lai' ? 'checked' : '' ?>
                                       onchange="selectType(this)">

                                <span class="type-title">Cấp lại</span>

                                <span class="type-desc">
                                    Đề nghị cấp lại do bị mất thẻ căn cước.
                                </span>
                            </label>

                            <label class="type-option <?= $loaiYeuCauMacDinh === 'bao_mat' ? 'active' : '' ?>">
                                <input type="radio"
                                       name="loai_yeu_cau"
                                       value="bao_mat"
                                       <?= $loaiYeuCauMacDinh === 'bao_mat' ? 'checked' : '' ?>
                                       onchange="selectType(this)">

                                <span class="type-title">Báo mất thẻ</span>

                                <span class="type-desc">
                                    Thông báo mất thẻ để cán bộ xem xét xử lý.
                                </span>
                            </label>

                        </div>
                    </div>

                </div>

                <h3 class="form-section-title">
                    II. Thông tin công dân
                </h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="so_dinh_danh">
                            Số định danh cá nhân
                        </label>

                        <input type="text"
                               id="so_dinh_danh"
                               name="so_dinh_danh"
                               maxlength="12"
                               value="<?= $e($the['so_dinh_danh'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="ho_ten">
                            Họ và tên công dân
                        </label>

                        <input type="text"
                               id="ho_ten"
                               name="ho_ten"
                               value="<?= $e($the['ho_ten'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label for="so_dien_thoai">
                            Số điện thoại liên hệ
                            <span class="required">*</span>
                        </label>

                        <input type="tel"
                               id="so_dien_thoai"
                               name="so_dien_thoai"
                               maxlength="15"
                               pattern="[0-9]{10,15}"
                               placeholder="Ví dụ: 0912345678"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="hinh_thuc_nhan">
                            Hình thức nhận thẻ
                            <span class="required">*</span>
                        </label>

                        <select name="hinh_thuc_nhan"
                                id="hinh_thuc_nhan"
                                onchange="toggleDiaChiNhan(this.value)"
                                required>

                            <option value="truc_tiep">
                                Nhận trực tiếp tại cơ quan Công an
                            </option>

                            <option value="buu_dien">
                                Chuyển phát qua bưu điện
                            </option>

                        </select>
                    </div>

                    <div class="form-group span-2"
                         id="diaChiWrapper"
                         style="display:none">

                        <label for="dia_chi_nhan">
                            Địa chỉ nhận thẻ qua bưu điện
                            <span class="required">*</span>
                        </label>

                        <textarea name="dia_chi_nhan"
                                  id="dia_chi_nhan"
                                  placeholder="Số nhà, đường, phường/xã, tỉnh/thành phố"><?= $e($the['noi_thuong_tru'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group span-2">
                        <label for="ly_do">
                            Lý do đề nghị
                            <span class="required">*</span>
                        </label>

                        <textarea name="ly_do"
                                  id="ly_do"
                                  required
                                  placeholder="Trình bày lý do đề nghị cấp thẻ..."><?= $e($lyDoMacDinh) ?></textarea>
                    </div>

                    <div class="form-group span-2">
                        <label for="ghi_chu">
                            Ghi chú thêm
                        </label>

                        <textarea name="ghi_chu"
                                  id="ghi_chu"
                                  placeholder="Thông tin bổ sung cho cán bộ xử lý..."></textarea>
                    </div>

                </div>

                <div class="vneid-action-bar">

                    <button type="submit"
                            class="btn-action btn-submit">
                        Nộp hồ sơ trực tuyến
                    </button>
                </div>

            </form>

        <?php endif; ?>

        <!-- Lịch sử yêu cầu cấp thẻ -->
        <?php if (empty($thong_bao_ket_qua) && !empty($ds_yeu_cau_gan_day)): ?>

            <div class="history-section">

                <h3 class="history-title">
                    Lịch sử yêu cầu cấp thẻ
                </h3>

                <div class="history-table-wrapper">
                    <table class="history-table">

                        <thead>
                            <tr>
                                <th>Mã hồ sơ</th>
                                <th>Loại thủ tục</th>
                                <th>Ngày nộp</th>
                                <th>Hình thức</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($ds_yeu_cau_gan_day as $yc): ?>

                                <?php
                                $loai = $yc['loai_yeu_cau'] ?? '';
                                $trangThai = $yc['trang_thai'] ?? '';

                                $classTrangThai = 'badge-pending';

                                if ($trangThai === 'dang_xu_ly') {
                                    $classTrangThai = 'badge-processing';
                                } elseif ($trangThai === 'hoan_thanh') {
                                    $classTrangThai = 'badge-done';
                                } elseif ($trangThai === 'tu_choi') {
                                    $classTrangThai = 'badge-rejected';
                                }

                                $tenTrangThai = [
                                    'cho_tiep_nhan' => 'Chờ tiếp nhận',
                                    'dang_xu_ly' => 'Đang xử lý',
                                    'hoan_thanh' => 'Hoàn thành',
                                    'tu_choi' => 'Từ chối'
                                ][$trangThai] ?? 'Chưa xác định';
                                ?>

                                <tr>
                                    <td>
                                        <strong>
                                            <?= $e($yc['ma_ho_so'] ?? '') ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= $e($loaiYeuCauMap[$loai] ?? 'Chưa xác định') ?>
                                    </td>

                                    <td>
                                        <?php
                                        $ngay = $yc['ngay_yeu_cau'] ?? '';

                                        echo $ngay && strtotime($ngay)
                                            ? date('d/m/Y H:i', strtotime($ngay))
                                            : 'Chưa cập nhật';
                                        ?>
                                    </td>

                                    <td>
                                        <?= ($yc['hinh_thuc_nhan'] ?? '') === 'buu_dien'
                                            ? 'Bưu điện'
                                            : 'Trực tiếp' ?>
                                    </td>

                                    <td>
                                        <span class="badge-status <?= $classTrangThai ?>">
                                            <?= $e($tenTrangThai) ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>

            </div>

        <?php endif; ?>

    </div>

    <script>
        function hienYeuCauDaNop() {
            const panel = document.getElementById('yeu-cau-da-nop');

            if (panel) {
                panel.classList.add('show');

                panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }
        function selectType(radio) {
            document.querySelectorAll('.type-option').forEach(function(option) {
                option.classList.remove('active');
            });

            radio.closest('.type-option').classList.add('active');

            const lyDoInput = document.getElementById('ly_do');

            const lyDoMacDinh = {
                cap_moi: 'Đề nghị cấp thẻ căn cước lần đầu.',
                cap_doi: 'Đề nghị cấp đổi thẻ căn cước.',
                cap_lai: 'Bị mất thẻ căn cước, đề nghị cấp lại.',
                bao_mat: 'Thông báo mất thẻ căn cước.'
            };

            if (lyDoInput) {
                lyDoInput.value = lyDoMacDinh[radio.value] || '';
            }
        }

        function toggleDiaChiNhan(value) {
            const wrapper = document.getElementById('diaChiWrapper');
            const input = document.getElementById('dia_chi_nhan');

            if (value === 'buu_dien') {
                wrapper.style.display = 'block';
                input.required = true;
            } else {
                wrapper.style.display = 'none';
                input.required = false;
            }
        }

        // Khởi tạo trạng thái địa chỉ nhận khi tải trang.
        document.addEventListener('DOMContentLoaded', function() {
            const hinhThuc = document.getElementById('hinh_thuc_nhan');

            if (hinhThuc) {
                toggleDiaChiNhan(hinhThuc.value);
            }
        });
    </script>

</body>
</html>
