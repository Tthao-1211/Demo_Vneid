<!-- app/views/the_can_cuoc/yeu_cau_cap_the.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ công: Yêu cầu cấp thẻ Căn cước - VNeID</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body {
            background-color: #E9EAE8 !important; margin: 0; padding: 20px;
            display: flex; flex-direction: column; align-items: center; min-height: 100vh; font-family: 'Times New Roman', Times, serif;
        }
        .boca-brand { text-align: center; color: #ffffff; margin-top: 20px; margin-bottom: 25px; }
        .boca-title { font-size: 20px; font-weight: 700; text-transform: uppercase; margin: 5px 0; }
        .boca-sub { font-size: 13px; color: #ffca2c; margin: 0; }

        .vneid-form-container { 
            width: 100%; max-width: 760px; background-color: #ffffff; 
            padding: 40px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); 
            box-sizing: border-box; margin-bottom: 50px; position: relative; 
        }
        .btn-back-arrow { 
            position: absolute; left: 40px; top: 40px; font-size: 18px; color: #555; 
            cursor: pointer; background: #f5f5f7; padding: 8px 14px; border-radius: 6px; 
            border: none; text-decoration: none; 
        }
        .vneid-section-title { 
            text-align: center; color: #222222; font-size: 22px; font-weight: 700; 
            margin-top: 10px; margin-bottom: 15px; 
        }
        .vneid-section-sub {
            text-align: center; color: #64748b; font-size: 14px; margin-bottom: 30px; line-height: 1.5;
        }

        /* Biên nhận nộp hồ sơ thành công */
        .receipt-card {
            background: #f0fdf4;
            border: 2px dashed #22c55e;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        .receipt-icon { font-size: 40px; margin-bottom: 10px; }
        .receipt-title { font-size: 18px; font-weight: 700; color: #15803d; margin-bottom: 8px; }
        .receipt-code { 
            display: inline-block; background: #ffffff; border: 1px solid #86efac; 
            color: #166534; font-size: 18px; font-weight: 800; padding: 8px 18px; 
            border-radius: 8px; margin: 10px 0; letter-spacing: 1px;
        }
        .receipt-desc { font-size: 13px; color: #374151; line-height: 1.6; }

        .form-grid-custom { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
        .span-2 { grid-column: span 2; }
        .form-group-custom { margin-bottom: 5px; }
        .form-group-custom label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 8px; }

        input[type="text"], input[type="tel"], select, textarea {
            width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1;
            border-radius: 8px; font-size: 14px; box-sizing: border-box; font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #e3261f; outline: none; box-shadow: 0 0 0 3px rgba(227, 38, 31, 0.15);
        }

        .type-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 5px;
        }
        .type-option {
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 14px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .type-option input { display: none; }
        .type-option.active {
            border-color: #e3261f;
            background-color: #fffafb;
            color: #e3261f;
            font-weight: 700;
        }
        .type-title { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .type-desc { font-size: 11px; color: #64748b; line-height: 1.3; }

        .btn-submit {
            display: block; width: 100%; padding: 14px; background-color: #e3261f; color: #ffffff;
            border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 12px rgba(227, 38, 31, 0.2); transition: background-color 0.2s; margin-top: 15px;
        }
        .btn-submit:hover { background-color: #c91e17; }

        /* Lịch sử yêu cầu */
        .history-section { margin-top: 35px; border-top: 1px solid #e2e8f0; padding-top: 25px; }
        .history-title { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 15px; }
        .history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .history-table th { background: #f8fafc; padding: 10px; text-align: left; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        .history-table td { padding: 10px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .badge-status { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-done { background: #dcfce7; color: #166534; }
        .success-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:20px}
        .success-actions a{display:flex;align-items:center;justify-content:center;padding:13px;border-radius:8px;text-decoration:none;font-weight:700;border:1px solid transparent}
        .btn-success-close{background:#fff;color:#334155;border-color:#cbd5e1!important}
        .btn-success-view{background:#e3261f;color:#fff}
        .readonly-request{display:none;margin-top:18px;border:1px solid #e2e8f0;border-radius:10px;padding:18px;background:#f8fafc;color:#334155}
        .readonly-request.show{display:block}
        .readonly-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .readonly-field{padding:10px;background:#fff;border-radius:7px;border:1px solid #e2e8f0;font-size:13px}
        .readonly-field strong{display:block;color:#64748b;margin-bottom:5px;font-size:12px}
        @media(max-width:600px){.form-grid-custom,.readonly-grid,.success-actions{grid-template-columns:1fr}.span-2{grid-column:auto}.type-selector{grid-template-columns:1fr}}
        /* Bố cục Web quản trị dữ liệu - ghi đè kiểu card/app mobile của trang dịch vụ cũ */
        body {
            display: block;
            padding: 0;
            min-height: 100vh;
            background: #E9EAE8 !important;
        }
        .boca-brand {
            width: min(1180px, calc(100% - 32px));
            margin: 18px auto 10px;
            padding: 14px 20px;
            border: 1px solid #d2d4d2;
            border-top: 5px solid #b91c1c;
            border-radius: 10px 10px 6px 6px;
            background: #fff;
            color: #222;
            text-align: left;
        }
        .boca-title {
            color: #b91c1c;
        }
        .boca-sub {
            color: #64748b;
        }
        .vneid-form-container {
            width: min(1180px, calc(100% - 32px)) !important;
            max-width: 1180px !important;
            min-height: auto !important;
            margin: 10px auto 32px !important;
            padding: 24px 30px 30px !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 24px rgba(36, 40, 38, .10) !important;
        }
        .btn-back-arrow {
            position: static;
            display: inline-flex;
            align-items: center;
            padding: 8px 12px !important;
        }
        .vneid-section-title {
            text-align: left;
            color: #b91c1c;
            font-size: 26px;
            margin: 18px 0 8px;
            border-bottom: 1px solid #e4e6e4;
            padding-bottom: 14px;
        }
        .vneid-section-sub {
            text-align: left;
            margin-bottom: 22px;
        }
        .type-selector {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .type-option {
            border-radius: 8px;
            min-height: 94px;
            background: #fff;
        }
        .history-section {
            margin-top: 24px;
            background: #fff;
        }
        .history-table {
            border: 0;
        }
        .history-table th {
            background: #a31515 !important;
            color: #fff !important;
            text-transform: uppercase;
            letter-spacing: .2px;
        }
        @media(max-width:760px) {
            .boca-brand,
            .vneid-form-container {
                width: calc(100% - 20px) !important;
                margin-left: 10px !important;
                margin-right: 10px !important;
            }
            .vneid-form-container {
                padding: 18px 14px 22px !important;
                border-radius: 8px !important;
            }
            .vneid-section-title {
                font-size: 21px;
            }
            .form-grid-custom,
            .readonly-grid,
            .success-actions {
                grid-template-columns: 1fr !important;
            }
            .span-2 {
                grid-column: auto !important;
            }
            .type-selector {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>

<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="boca-brand">
        <div class="boca-title">Bộ Công An</div>
        <div class="boca-sub">Trung tâm dữ liệu Quốc gia về dân cư</div>
    </div>

    <div class="vneid-form-container">
        <a class="btn-back-arrow" href="/">← Thoát</a>
        <h2 class="vneid-section-title">Yêu cầu Cấp thẻ Căn cước công dân</h2>
        <div class="vneid-section-sub">Dịch vụ công trực tuyến mức độ 4 mô phỏng trên nền tảng VNeID</div>

        <?php if (!empty($thong_bao_ket_qua)): ?>
            <div class="receipt-card">
                <div class="receipt-icon">🎉</div>
                <div class="receipt-title">Hồ sơ đã được tiếp nhận thành công!</div>
                <div>Mã hồ sơ điện tử:</div>
                <div class="receipt-code"><?php echo htmlspecialchars($thong_bao_ket_qua['ma_ho_so']); ?></div>
                <div class="receipt-desc">
                    Hồ sơ của công dân <strong><?php echo htmlspecialchars($thong_bao_ket_qua['ho_ten']); ?></strong> (Số định danh: <?php echo htmlspecialchars($thong_bao_ket_qua['so_cccd']); ?>) đã được chuyển đến Cơ quan Công an thụ lý.<br>
                    Thời gian tiếp nhận: <?php echo htmlspecialchars($thong_bao_ket_qua['ngay_tiep_nhan']); ?>. Thời gian xử lý dự kiến: 7 ngày làm việc.
                </div>
            </div>
            <div class="success-actions">
                <a class="btn-success-close" href="/">Đóng</a>
                <a class="btn-success-view" href="#yeu-cau-da-nop" onclick="hienYeuCauDaNop(); return false;">Xem thay đổi</a>
            </div>
            <div class="readonly-request" id="yeu-cau-da-nop">
                <h3 style="margin-top:0;text-align:center">Thông tin yêu cầu đã gửi</h3>
                <div class="readonly-grid">
                    <div class="readonly-field"><strong>Mã hồ sơ</strong><?php echo e($thong_bao_ket_qua['ma_ho_so']); ?></div>
                    <div class="readonly-field"><strong>Loại thủ tục</strong><?php echo e($thong_bao_ket_qua['loai_yeu_cau'] === 'cap_moi' ? 'Cấp mới' : ($thong_bao_ket_qua['loai_yeu_cau'] === 'cap_doi' ? 'Cấp đổi' : ($thong_bao_ket_qua['loai_yeu_cau'] === 'cap_lai' ? 'Cấp lại' : 'Báo mất thẻ'))); ?></div>
                    <div class="readonly-field"><strong>Họ tên</strong><?php echo e($thong_bao_ket_qua['ho_ten']); ?></div>
                    <div class="readonly-field"><strong>Số CCCD</strong><?php echo e($thong_bao_ket_qua['so_cccd']); ?></div>
                    <div class="readonly-field"><strong>Số điện thoại</strong><?php echo e($thong_bao_ket_qua['so_dien_thoai']); ?></div>
                    <div class="readonly-field"><strong>Hình thức nhận</strong><?php echo e($thong_bao_ket_qua['hinh_thuc_nhan'] === 'buu_dien' ? 'Bưu điện' : 'Trực tiếp'); ?></div>
                    <div class="readonly-field span-2"><strong>Địa chỉ nhận</strong><?php echo e($thong_bao_ket_qua['dia_chi_nhan'] ?: 'Nhận trực tiếp tại cơ quan Công an'); ?></div>
                    <div class="readonly-field span-2"><strong>Lý do đề nghị</strong><?php echo e($thong_bao_ket_qua['ly_do']); ?></div>
                    <div class="readonly-field span-2"><strong>Ghi chú</strong><?php echo e($thong_bao_ket_qua['ghi_chu'] ?: 'Không có ghi chú'); ?></div>
                </div>
            </div>
        <?php else: ?>

        <form method="POST" action="/the_can_cuoc/yeu_cau_cap_the<?php echo $the_can_cuoc ? '?id=' . urlencode((string)$the_can_cuoc['id']) : ''; ?>">
            <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
            <div class="form-grid-custom">

                <!-- 1. Chọn loại yêu cầu cấp thẻ -->
                <div class="form-group-custom span-2">
                    <label>Loại thủ tục đề nghị cấp thẻ (*):</label>
                    <div class="type-selector">
                        <label class="type-option <?php echo ($loaiYeuCauMacDinh === '' || $loaiYeuCauMacDinh === 'cap_moi') ? 'active' : ''; ?>" onclick="selectType(this)">
                            <input type="radio" name="loai_yeu_cau" value="cap_moi" <?php echo $loaiYeuCauMacDinh === 'cap_moi' ? 'checked' : ($loaiYeuCauMacDinh === '' ? 'checked' : ''); ?>>
                            <div class="type-title">Cấp mới</div>
                            <div class="type-desc">Công dân từ đủ 14 tuổi hoặc chưa từng có thẻ CCCD</div>
                        </label>
                        <label class="type-option <?php echo $loaiYeuCauMacDinh === 'cap_doi' ? 'active' : ''; ?>" onclick="selectType(this)">
                            <input type="radio" name="loai_yeu_cau" value="cap_doi" <?php echo $loaiYeuCauMacDinh === 'cap_doi' ? 'checked' : ''; ?>>
                            <div class="type-title">Cấp đổi</div>
                            <div class="type-desc">Thẻ hết hạn, hỏng thẻ, hoặc đổi thông tin cá nhân</div>
                        </label>
                        <label class="type-option <?php echo $loaiYeuCauMacDinh === 'cap_lai' ? 'active' : ''; ?>" onclick="selectType(this)">
                            <input type="radio" name="loai_yeu_cau" value="cap_lai" <?php echo $loaiYeuCauMacDinh === 'cap_lai' ? 'checked' : ''; ?>>
                            <div class="type-title">Cấp lại</div>
                            <div class="type-desc">Bị mất thẻ căn cước (tự động khóa thẻ cũ)</div>
                        </label>
                        <label class="type-option <?php echo $loaiYeuCauMacDinh === 'bao_mat' ? 'active' : ''; ?>" onclick="selectType(this)">
                            <input type="radio" name="loai_yeu_cau" value="bao_mat" <?php echo $loaiYeuCauMacDinh === 'bao_mat' ? 'checked' : ''; ?>>
                            <div class="type-title">Báo mất thẻ</div>
                            <div class="type-desc">Gửi yêu cầu khóa thẻ và báo mất cho cán bộ xử lý</div>
                        </label>
                    </div>
                </div>

                <!-- 2. Thông tin công dân -->
                <div class="form-group-custom">
                    <label>Số định danh cá nhân (12 chữ số) (*):</label>
                    <input type="text" name="so_cccd" maxlength="12" pattern="\d{12}" required 
                           placeholder="Nhập 12 chữ số CCCD" 
                           value="<?php echo htmlspecialchars($the_can_cuoc['so_cccd'] ?? ''); ?>">
                </div>

                <div class="form-group-custom">
                    <label>Họ và tên công dân (*):</label>
                    <input type="text" name="ho_ten" required 
                           placeholder="Họ và tên viết hoa" 
                           value="<?php echo htmlspecialchars($the_can_cuoc['ho_ten'] ?? ''); ?>">
                </div>

                <div class="form-group-custom">
                    <label>Số điện thoại liên hệ (*):</label>
                    <input type="tel" name="so_dien_thoai" required placeholder="Ví dụ: 0912345678">
                </div>

                <div class="form-group-custom">
                    <label>Hình thức nhận thẻ (*):</label>
                    <select name="hinh_thuc_nhan" id="hinhThucNhanSelect" onchange="toggleDiaChiNhan(this.value)">
                        <option value="truc_tiep">Nhận trực tiếp tại Cơ quan Công an</option>
                        <option value="buu_dien">Chuyển phát qua bưu điện về tận nhà</option>
                    </select>
                </div>

                <div class="form-group-custom span-2" id="diaChiWrapper" style="display: none;">
                    <label>Địa chỉ nhận thẻ qua bưu điện (*):</label>
                    <input type="text" name="dia_chi_nhan" id="diaChiInput" 
                           placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                           value="<?php echo htmlspecialchars($the_can_cuoc['noi_thuong_tru'] ?? ''); ?>">
                </div>

                <div class="form-group-custom span-2">
                    <label>Lý do đề nghị cấp thẻ (*):</label>
                    <input type="text" name="ly_do" id="lyDoInput" required value="<?php echo e($lyDoMacDinh); ?>" placeholder="Ví dụ: Thẻ hết hạn sử dụng theo độ tuổi quy định...">
                </div>

                <div class="form-group-custom span-2">
                    <label>Ghi chú thêm (nếu có):</label>
                    <textarea name="ghi_chu" placeholder="Thông tin bổ sung cho cán bộ thụ lý hồ sơ..."></textarea>
                </div>

                <div class="span-2">
                    <button type="submit" class="btn-submit">Nộp hồ sơ trực tuyến</button>
                </div>

            </div>
        </form>

        <?php endif; ?>

        <!-- Lịch sử các yêu cầu đã gửi -->
        <?php if (empty($thong_bao_ket_qua) && !empty($ds_yeu_cau_gan_day)): ?>
            <div class="history-section">
                <div class="history-title">📋 Hồ sơ yêu cầu cấp thẻ gần đây</div>
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
                            <tr>
                                <td style="font-weight: 700; color: #1e40af;"><?php echo htmlspecialchars($yc['ma_ho_so']); ?></td>
                                <td>
                                    <?php 
                                        if ($yc['loai_yeu_cau'] === 'cap_moi') echo 'Cấp mới';
                                        elseif ($yc['loai_yeu_cau'] === 'cap_doi') echo 'Cấp đổi';
                                        elseif ($yc['loai_yeu_cau'] === 'cap_lai') echo 'Cấp lại';
                                        elseif ($yc['loai_yeu_cau'] === 'bao_mat') echo 'Báo mất thẻ';
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($yc['ngay_yeu_cau'])); ?></td>
                                <td><?php echo $yc['hinh_thuc_nhan'] === 'buu_dien' ? 'Bưu điện' : 'Trực tiếp'; ?></td>
                                <td>
                                    <?php if ($yc['trang_thai'] === 'cho_tiep_nhan'): ?>
                                        <span class="badge-status badge-pending">Chờ tiếp nhận</span>
                                    <?php elseif ($yc['trang_thai'] === 'dang_xu_ly'): ?>
                                        <span class="badge-status badge-processing">Đang xử lý</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-done">Hoàn thành</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function hienYeuCauDaNop() {
            const panel = document.getElementById('yeu-cau-da-nop');
            if (panel) {
                panel.classList.add('show');
                panel.scrollIntoView({behavior: 'smooth', block: 'nearest'});
            }
        }

        function selectType(el) {
            document.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('active'));
            el.classList.add('active');
            const radio = el.querySelector('input[type="radio"]');
            radio.checked = true;

            const lyDoInput = document.getElementById('lyDoInput');
            if (radio.value === 'cap_moi') {
                lyDoInput.value = 'Đủ 14 tuổi, đề nghị cấp thẻ căn cước lần đầu.';
            } else if (radio.value === 'cap_doi') {
                lyDoInput.value = 'Thẻ căn cước hết hạn sử dụng / đề nghị cấp đổi.';
            } else if (radio.value === 'cap_lai') {
                lyDoInput.value = 'Bị mất thẻ căn cước công dân, đề nghị cấp lại.';
            } else if (radio.value === 'bao_mat') {
                lyDoInput.value = 'Báo mất thẻ căn cước công dân, đề nghị khóa thẻ.';
            }
        }

        function toggleDiaChiNhan(val) {
            const wrapper = document.getElementById('diaChiWrapper');
            const input = document.getElementById('diaChiInput');
            if (val === 'buu_dien') {
                wrapper.style.display = 'block';
                input.required = true;
            } else {
                wrapper.style.display = 'none';
                input.required = false;
            }
        }
    </script>
</body>
</html>
