<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đề nghị điều chỉnh căn cước</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
    *{box-sizing:border-box}
    body{margin:0;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#111827;padding:28px 16px}
    .box{max-width:720px;margin:auto;background:#fff;border-radius:20px;padding:26px 28px 30px;box-shadow:0 18px 45px rgba(0,0,0,.22)}
    .back-link{display:inline-flex;align-items:center;color:#c31818;text-decoration:none;font-weight:800;font-size:14px;margin-bottom:18px}
    .page-title{text-align:center;font-size:23px;margin:0 0 22px;color:#111827}
    .section{border-top:1px solid #e5e7eb;margin-top:18px;padding-top:18px}
    .section-title{font-size:15px;font-weight:800;margin-bottom:14px;color:#111827}
    .field{margin-bottom:15px}
    .field label{display:block;font-size:13px;font-weight:800;color:#a52a2a;margin-bottom:7px}
    .field input,.field textarea,.field select{width:100%;border:1px solid #d1d5db;border-radius:8px;background:#fff;padding:12px 13px;color:#111827;font-size:14px;font-family:inherit;outline:none}
    .field input:focus,.field textarea:focus,.field select:focus{border-color:#df2424;box-shadow:0 0 0 3px rgba(227,38,31,.08)}
    .field textarea{min-height:82px;resize:vertical}
    .readonly{background:#f8fafc!important;color:#6b7280!important}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .required{color:#e3261f}
    .note{border-left:4px solid #e3261f;background:#fff7f7;color:#6b7280;padding:12px 14px;font-size:12px;line-height:1.55;margin:18px 0}
    .btn{width:100%;border:0;border-radius:8px;padding:14px;background:#df2424;color:#fff;font-size:15px;font-weight:800;cursor:pointer;box-shadow:0 4px 10px rgba(223,36,36,.2)}
    .btn:hover{background:#c81e1e}
    .notice{padding:12px;background:#ecfdf3;color:#166534;border-radius:8px;margin-bottom:16px;text-align:center;border:1px solid #bbf7d0}
    .receipt-title{text-align:center;font-size:22px;font-weight:800;color:#111827;margin:0 0 20px}
    .detail-row{display:grid;grid-template-columns:42% 58%;min-height:44px;border-bottom:1px solid #e2e8f0;padding:8px 0;align-items:center}
    .detail-row span{color:#64748b}.detail-row strong{font-weight:600}
    .back-btn{display:block;text-align:center;margin-top:20px;padding:13px;border-radius:8px;background:#fff;color:#334155;border:1px solid #cbd5e1;text-decoration:none;font-weight:800}
    @media(max-width:600px){body{padding:0}.box{min-height:100vh;border-radius:0;padding:20px 18px}.row{grid-template-columns:1fr}.detail-row{grid-template-columns:38% 62%}}
    </style>
</head>
<body>
<div class="box">
    <a class="back-link" href="/the_can_cuoc/quan_ly_ho_so_cua_toi">← Quay lại quản lý hồ sơ</a>
    <?php if ($yeuCauDaGui === null): ?>
        <h1 class="page-title">Đề nghị điều chỉnh căn cước</h1>
    <?php endif; ?>
    <?php if ($yeuCauDaGui !== null): ?>
        <h1 class="receipt-title">Chi tiết yêu cầu điều chỉnh</h1>
        <div class="detail-row"><span>Số CCCD</span><strong><?php echo e($yeuCauDaGui['so_cccd']); ?></strong></div>
        <div class="detail-row"><span>Họ và tên</span><strong><?php echo e($yeuCauDaGui['ho_ten_moi'] ?: $yeuCauDaGui['ho_ten_cu']); ?></strong></div>
        <div class="detail-row"><span>Ngày sinh</span><strong><?php echo e($yeuCauDaGui['ngay_sinh_moi'] ?: $yeuCauDaGui['ngay_sinh_cu']); ?></strong></div>
        <div class="detail-row"><span>Giới tính</span><strong><?php echo e($yeuCauDaGui['gioi_tinh_moi'] ?: $yeuCauDaGui['gioi_tinh_cu']); ?></strong></div>
        <div class="detail-row"><span>Quốc tịch</span><strong><?php echo e($yeuCauDaGui['quoc_tich_moi'] ?: $yeuCauDaGui['quoc_tich_cu']); ?></strong></div>
        <div class="detail-row"><span>Dân tộc</span><strong><?php echo e($yeuCauDaGui['dan_toc_moi'] ?: $yeuCauDaGui['dan_toc_cu']); ?></strong></div>
        <div class="detail-row"><span>Tôn giáo</span><strong><?php echo e($yeuCauDaGui['ton_giao_moi'] ?: $yeuCauDaGui['ton_giao_cu']); ?></strong></div>
        <div class="detail-row"><span>Quê quán</span><strong><?php echo e($yeuCauDaGui['que_quan_moi'] ?: $yeuCauDaGui['que_quan_cu']); ?></strong></div>
        <div class="detail-row"><span>Nơi thường trú</span><strong><?php echo e($yeuCauDaGui['noi_thuong_tru_moi'] ?: $yeuCauDaGui['noi_thuong_tru_cu']); ?></strong></div>
        <div class="detail-row"><span>Ảnh chân dung</span><strong><?php echo e($yeuCauDaGui['anh_chan_dung_moi'] ?: $yeuCauDaGui['anh_chan_dung_cu']); ?></strong></div>
        <div class="detail-row"><span>Lý do điều chỉnh</span><strong><?php echo nl2br(e($yeuCauDaGui['ly_do'])); ?></strong></div>
        <div class="detail-row"><span>Trạng thái</span><strong>Chờ tiếp nhận</strong></div>
        <a class="back-btn" href="/the_can_cuoc/quan_ly_ho_so_cua_toi">Quay về trang trước đó</a>
    <?php else: ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
        <div class="field"><label>Số căn cước công dân <span class="required">*</span></label><input class="readonly" value="<?php echo e($the['so_cccd']); ?>" readonly></div>
        <div class="section"><div class="section-title">Thông tin đề nghị điều chỉnh</div></div>
        <div class="field"><label>Họ và tên</label><input name="ho_ten_moi" value="<?php echo e($the['ho_ten']); ?>"></div>
        <div class="row">
            <div class="field"><label>Ngày sinh</label><input type="date" name="ngay_sinh_moi" value="<?php echo e($the['ngay_sinh']); ?>"></div>
            <div class="field"><label>Giới tính</label><select name="gioi_tinh_moi"><option <?php echo $the['gioi_tinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option><option <?php echo $the['gioi_tinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option><option <?php echo $the['gioi_tinh'] === 'Khác' ? 'selected' : ''; ?>>Khác</option></select></div>
        </div>
        <div class="row">
            <div class="field"><label>Quốc tịch</label><input name="quoc_tich_moi" value="<?php echo e($the['quoc_tich'] ?? 'Việt Nam'); ?>"></div>
            <div class="field"><label>Dân tộc</label><input name="dan_toc_moi" value="<?php echo e($the['dan_toc'] ?? ''); ?>"></div>
        </div>
        <div class="row">
            <div class="field"><label>Tôn giáo</label><input name="ton_giao_moi" value="<?php echo e($the['ton_giao'] ?? ''); ?>"></div>
            <div class="field"><label>Quê quán</label><input name="que_quan_moi" value="<?php echo e($the['que_quan']); ?>"></div>
        </div>
        <div class="field"><label>Nơi thường trú</label><textarea name="noi_thuong_tru_moi"><?php echo e($the['noi_thuong_tru']); ?></textarea></div>
        <div class="field"><label>Ảnh chân dung</label><input type="file" name="anh_chan_dung_moi" accept="image/jpeg,image/png,image/webp"></div>
        <div class="field"><label>Lý do điều chỉnh <span class="required">*</span></label><textarea name="ly_do" required placeholder="Nhập lý do đề nghị điều chỉnh"></textarea></div>
        <div class="note">Lưu ý: Liên hệ đăng ký cấp đổi thẻ căn cước sẽ được chuyển đến cơ quan Công an khi yêu cầu được tiếp nhận. Công dân chỉ có thể đề nghị các thông tin trong phạm vi cho phép.</div>
        <button class="btn">Gửi yêu cầu</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
