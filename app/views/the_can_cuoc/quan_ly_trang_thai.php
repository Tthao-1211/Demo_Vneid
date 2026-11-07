<?php
/** @var array $the_can_cuoc Biến chứa dữ liệu một bản ghi thẻ căn cước truyền từ Controller */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>VNeID - Quản lý Trạng thái Thẻ căn cước</title>
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <style>
        body {
            background-color: #E9EAE8 !important;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
            color: #222222;
        }

        .vneid-form-container {
            width: 100%;
            max-width: 680px;
            background-color: #ffffff !important;
            margin: 30px auto 50px auto;
            padding: 35px 40px;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            position: relative;
        }

        .btn-back-arrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            background: #f3f4f6;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background 0.2s;
        }
        .btn-back-arrow:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .vneid-section-title {
            text-align: center;
            color: #1f2937 !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            margin-top: 0;
            margin-bottom: 30px;
        }

        .citizen-card-summary {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .citizen-name { font-size: 17px; font-weight: 700; color: #1e293b; text-transform: uppercase; margin-bottom: 4px; }
        .citizen-cccd { font-size: 14px; color: #64748b; font-weight: 600; }

        .badge { padding: 6px 14px; border-radius: 50px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-active { background-color: #e6f4ea; color: #137333; }
        .badge-expired { background-color: #fce8e6; color: #c5221f; }
        .badge-lost { background-color: #fef7e0; color: #b06000; }
        .badge-revoked { background-color: #f1f5f9; color: #475569; }

        /* Khung chọn trạng thái */
        .status-option-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .status-radio-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
        }
        .status-radio-card:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }
        .status-radio-card.selected {
            border-color: #e3261f;
            background-color: #fffafb;
        }

        .status-radio-card input[type="radio"] {
            position: absolute;
            top: 15px;
            right: 15px;
            accent-color: #e3261f;
            width: 18px;
            height: 18px;
        }
        .status-title { font-size: 15px; font-weight: 700; }
        .status-desc { font-size: 12px; color: #64748b; line-height: 1.4; }

        .status-con_hieu_luc .status-title { color: #15803d; }
        .status-het_han .status-title { color: #b91c1c; }
        .status-bao_mat .status-title { color: #b45309; }
        .status-thu_hoi .status-title { color: #475569; }

        .form-group-custom { margin-bottom: 20px; }
        .form-group-custom label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 8px; }
        
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
            background: #fff;
            color: #222;
        }
        textarea:focus { outline: none; border-color: #e3261f; box-shadow: 0 0 0 3px rgba(227, 38, 31, 0.15); }

        .btn-vneid-submit {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #e3261f;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(227, 38, 31, 0.2);
            transition: background-color 0.2s;
        }
        .btn-vneid-submit:hover { background-color: #c91e17; }

        .alert-success {
            padding: 12px 18px;
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

<body>

    <div class="vneid-form-container">
        <a class="btn-back-arrow" href="/the_can_cuoc/danh_sach">← Danh sách</a>
        <h2 class="vneid-section-title">Quản lý Trạng thái Thẻ căn cước</h2>

        <?php if (!empty($thong_bao)): ?>
    <div class="alert-success">✅ <?= htmlspecialchars($thong_bao) ?></div>
<?php endif; ?>
<?php if (!empty($loi)): ?>
    <div class="alert-success" style="background:#fff6f6;color:#b91c1c;border-color:#fecaca;">⚠️ <?= htmlspecialchars($loi) ?></div>
<?php endif; ?>

        <!-- Thông tin công dân đang xử lý -->
        <div class="citizen-card-summary">
            <div>
                <div class="citizen-name"><?php echo htmlspecialchars($the_can_cuoc['ho_ten']); ?></div>
                <div class="citizen-cccd">Số định danh: <span style="color: #e3261f;"><?php echo htmlspecialchars($the_can_cuoc['so_the']); ?></span></div>
            </div>
            <div>
    <?php
    $nhanTrangThai = [
        'con_hieu_luc' => ['Còn hiệu lực', 'badge-active'],
        'het_han'      => ['Hết hạn',      'badge-expired'],
        'bao_mat'      => ['Báo mất',      'badge-lost'],
        'thu_hoi'      => ['Thu hồi',      'badge-revoked'],
    ];
    [$nhan, $lop] = $nhanTrangThai[$the_can_cuoc['trang_thai']] ?? [$the_can_cuoc['trang_thai'], 'badge-lost'];
    ?>
    <span class="badge <?= $lop ?>"><?= htmlspecialchars($nhan) ?></span>
</div>
        </div>

        <?php if (!empty($lich_su_trang_thai)): ?>
            <div style="margin: 20px 0; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background: #fafafa;">
                <strong style="font-size: 14px; color: #333;">Lịch sử thay đổi trạng thái thẻ</strong>
                <?php foreach ($lich_su_trang_thai as $ls): ?>
                    <div style="display:flex; justify-content:space-between; gap:12px; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:13px;">
                        <span style="color: #666;"><?php echo date('d/m/Y H:i', strtotime($ls['ngay_thay_doi'])); ?></span>
                        <span style="font-weight: 600; color: #444;"><?php echo htmlspecialchars(($ls['trang_thai_truoc'] ?? '-') . ' → ' . $ls['trang_thai_sau']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/the_can_cuoc/quan_ly_trang_thai?id=<?php echo $the_can_cuoc['ma_the']; ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <label style="display: block; font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                Chọn trạng thái mới cần áp dụng cho thẻ:
            </label>

            <div class="status-option-grid">
                <!-- 1. Còn hiệu lực -->
                <label class="status-radio-card status-con_hieu_luc <?php echo $the_can_cuoc['trang_thai'] === 'con_hieu_luc' ? 'selected' : ''; ?>">
                    <input type="radio" name="trang_thai" value="con_hieu_luc" <?php echo $the_can_cuoc['trang_thai'] === 'con_hieu_luc' ? 'checked' : ''; ?> required>
                    <div class="status-title">Còn hiệu lực</div>
                    <div class="status-desc">Thẻ đang hoạt động bình thường, đầy đủ giá trị pháp lý trong mọi giao dịch.</div>
                </label>

                <!-- 2. Hết hạn -->
                <label class="status-radio-card status-het_han <?php echo $the_can_cuoc['trang_thai'] === 'het_han' ? 'selected' : ''; ?>">
                    <input type="radio" name="trang_thai" value="het_han" <?php echo $the_can_cuoc['trang_thai'] === 'het_han' ? 'checked' : ''; ?> required>
                    <div class="status-title">Hết hạn</div>
                    <div class="status-desc">Thẻ đã quá thời hạn sử dụng ghi trên mặt thẻ, công dân cần làm thủ tục cấp đổi.</div>
                </label>

                <!-- 3. Báo mất -->
                <label class="status-radio-card status-bao_mat <?php echo $the_can_cuoc['trang_thai'] === 'bao_mat' ? 'selected' : ''; ?>">
                    <input type="radio" name="trang_thai" value="bao_mat" <?php echo $the_can_cuoc['trang_thai'] === 'bao_mat' ? 'checked' : ''; ?> required>
                    <div class="status-title">Báo mất</div>
                    <div class="status-desc">Khóa thẻ tạm thời để bảo vệ quyền lợi công dân, vô hiệu hóa giao dịch xác thực thẻ.</div>
                </label>

                <!-- 4. Thu hồi -->
                <label class="status-radio-card status-thu_hoi <?php echo $the_can_cuoc['trang_thai'] === 'thu_hoi' ? 'selected' : ''; ?>">
                    <input type="radio" name="trang_thai" value="thu_hoi" <?php echo $the_can_cuoc['trang_thai'] === 'thu_hoi' ? 'checked' : ''; ?> required>
                    <div class="status-title">Thu hồi</div>
                    <div class="status-desc">Thu hồi thẻ theo quy định pháp luật (thay đổi quốc tịch, hủy thẻ cũ khi cấp đổi...).</div>
                </label>
            </div>

            <div class="form-group-custom">
                <label>Ghi chú / Lý do điều chỉnh trạng thái (tùy chọn):</label>
                <textarea name="ly_do" placeholder="Ví dụ: Công dân báo rơi ví ngày 03/11, yêu cầu tạm khóa thẻ..."></textarea>
            </div>

            <button type="submit" class="btn-vneid-submit">Xác nhận cập nhật trạng thái</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.status-radio-card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        });
    </script>
</body>
</html>