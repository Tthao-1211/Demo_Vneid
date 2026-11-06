<?php
/** @var array $the_can_cuoc Biến chứa dữ liệu một bản ghi thẻ căn cước truyền từ Controller */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>VNeID - Chi tiết Thẻ căn cước công dân</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body {
            background-color: #E9EAE8 !important;
            margin: 0; padding: 20px;
            display: flex; flex-direction: column; align-items: center;
            min-height: 100vh; font-family: 'Times New Roman', Times, serif;
        }

        .boca-brand { text-align: center; color: #ffffff; margin-top: 20px; margin-bottom: 25px; }
        .boca-title { font-size: 24px; font-weight: 800; text-transform: uppercase; margin: 5px 0; color: #b91c1c; letter-spacing: .5px; }
        .boca-sub { font-size: 13px; color: #4b5563; margin: 0; }

        .vneid-form-container {
            width: 100%; max-width: 680px;
            background-color: #ffffff !important;
            padding: 45px; border-radius: 16px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); box-sizing: border-box; margin-bottom: 50px;
            position: relative;
        }

        .btn-back-arrow { 
            position: absolute; left: 40px; top: 40px; font-size: 18px; color: #555; 
            cursor: pointer; background: #f5f5f7; padding: 8px 14px; border-radius: 6px; 
            border: none; text-decoration: none; 
        }

        .vneid-section-title {
            text-align: center; color: #222222 !important;
            font-size: 24px !important; font-weight: 700 !important;
            margin-top: 0; margin-bottom: 30px;
        }

        .info-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 15px 0; border-bottom: 1px solid #f2f2f7; font-size: 15px;
        }

        .info-label { color: #8e8e93; width: 40%; line-height: 1.4; }
        .info-value { color: #222222; font-weight: 600; text-align: right; width: 60%; word-wrap: break-word; }

        .badge { padding: 5px 12px; border-radius: 50px; font-size: 13px; font-weight: bold; display: inline-block; }
        .badge-active { background-color: #e6f4ea; color: #137333; }
        .badge-expired { background-color: #fce8e6; color: #c5221f; }
        .badge-lost { background-color: #fef7e0; color: #b06000; }
        .badge-revoked { background-color: #f1f5f9; color: #475569; }

        .vneid-action-bar {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 35px; padding-top: 20px; border-top: 1px solid #e5e5ea;
        }

        .btn-action {
            padding: 13px 18px; font-size: 14px; font-weight: 700;
            border-radius: 8px; cursor: pointer; text-decoration: none;
            box-sizing: border-box; border: none; text-align: center;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-close-home { background-color: #f1f5f9; color: #475569; }
        .btn-close-home:hover { background-color: #e2e8f0; }

        .btn-manage-status { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .btn-manage-status:hover { background-color: #fde68a; }

        .btn-request-card { background-color: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; }
        .btn-request-card:hover { background-color: #ddd6fe; }

        .btn-request-card-red { background-color: #e3261f; color: #fff; border: 1px solid #e3261f; }
        .btn-request-card-red:hover { background-color: #c91e17; }

        .btn-request-update {
            background-color: #e3261f; color: #ffffff;
            box-shadow: 0 4px 12px rgba(227, 38, 31, 0.2);
        }
        .btn-request-update:hover { background-color: #c91e17; }
    </style>
</head>

<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>

    <div class="boca-brand">
        <div class="boca-title">Bộ Công An</div>
        <div class="boca-sub">Trung tâm dữ liệu Quốc gia về dân cư</div>
    </div>

    <div class="vneid-form-container">
        <?php if (laCongDanDemo()): ?>
            <a class="btn-back-arrow" href="/">← Thoát</a>
        <?php else: ?>
            <a class="btn-back-arrow" href="/the_can_cuoc/danh_sach?search=<?php echo urlencode($the_can_cuoc['so_cccd']); ?>">← Danh sách</a>
        <?php endif; ?>
        <h2 class="vneid-section-title">Chi tiết Thẻ căn cước công dân</h2>

        <div class="info-row">
            <div class="info-label">Số định danh cá nhân</div>
            <div class="info-value" style="color: #e3261f; font-size: 16px;"><?php echo htmlspecialchars($the_can_cuoc['so_cccd']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Họ, chữ đệm và tên khai sinh</div>
            <div class="info-value" style="text-transform: uppercase;"><?php echo htmlspecialchars($the_can_cuoc['ho_ten']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Ngày, tháng, năm sinh</div>
            <div class="info-value"><?php echo date('d-m-Y', strtotime($the_can_cuoc['ngay_sinh'])); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Giới tính</div>
            <div class="info-value"><?php echo htmlspecialchars($the_can_cuoc['gioi_tinh']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Quê quán (Tỉnh/Thành phố)</div>
            <div class="info-value"><?php echo htmlspecialchars($the_can_cuoc['que_quan']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Nơi cư trú hiện tại</div>
            <div class="info-value"><?php echo htmlspecialchars($the_can_cuoc['noi_thuong_tru']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Ngày cấp thẻ</div>
            <div class="info-value"><?php echo date('d-m-Y', strtotime($the_can_cuoc['ngay_cap'])); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Nơi cấp</div>
            <div class="info-value"><?php echo htmlspecialchars($the_can_cuoc['noi_cap']); ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Thời hạn sử dụng</div>
            <div class="info-value"><?php echo date('d-m-Y', strtotime($the_can_cuoc['ngay_het_han'])); ?></div>
        </div>

        <div class="info-row" style="border-bottom: none;">
            <div class="info-label">Trạng thái thẻ</div>
            <div class="info-value">
                <?php if ($the_can_cuoc['trang_thai'] === 'con_hieu_luc'): ?>
                    <span class="badge badge-active">Còn hiệu lực</span>
                <?php elseif ($the_can_cuoc['trang_thai'] === 'het_han'): ?>
                    <span class="badge badge-expired">Hết hạn</span>
                <?php elseif ($the_can_cuoc['trang_thai'] === 'bao_mat'): ?>
                    <span class="badge badge-lost">Báo mất</span>
                <?php elseif ($the_can_cuoc['trang_thai'] === 'thu_hoi'): ?>
                    <span class="badge badge-revoked">Thu hồi</span>
                <?php else: ?>
                    <span class="badge badge-lost"><?php echo htmlspecialchars($the_can_cuoc['trang_thai']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="vneid-action-bar">
            <?php if (laCongDanDemo()): ?>
                <a href="/" class="btn-action btn-close-home">Đóng</a>
                <a href="/the_can_cuoc/yeu_cau_cap_the?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action btn-request-card-red">📄 Yêu cầu cấp thẻ</a>
            <?php else: ?>
                <?php if (laCanBoDemo() || laQuanTriDemo()): ?>
                <a href="/the_can_cuoc/quan_ly_trang_thai?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action btn-manage-status">⚙ Đổi trạng thái thẻ</a>
                <?php endif; ?>
                <?php if (laQuanTriDemo()): ?>
                <a href="/the_can_cuoc/cap_nhat?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action btn-request-update">✏ Cập nhật hồ sơ</a>
                <?php endif; ?>
                <a href="/the_can_cuoc/danh_sach<?php echo !empty($the_can_cuoc['so_cccd']) ? '?search=' . urlencode($the_can_cuoc['so_cccd']) : ''; ?>" class="btn-action btn-close-home">Đóng</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
