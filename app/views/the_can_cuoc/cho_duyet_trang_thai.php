<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu đang chờ duyệt</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;background:#E9EAE8;font-family:'Times New Roman',Times,serif;padding:20px;color:#222}
        .pending-page{max-width:760px;margin:30px auto;background:#fff;border:1px solid #d2d4d2;border-top:5px solid #b91c1c;border-radius:16px;padding:32px;box-shadow:0 14px 34px rgba(36,40,38,.14)}
        h1{text-align:center;color:#b91c1c;margin:0 0 18px;font-size:28px}
        .pending-box{text-align:center;border:1px solid #e2e8f0;background:#fffafa;border-radius:12px;padding:24px;margin-bottom:20px}
        .status-badge{display:inline-block;border-radius:999px;padding:8px 14px;background:#fef3c7;color:#92400e;font-weight:800}
        .detail-row{display:grid;grid-template-columns:40% 60%;gap:12px;padding:12px;border-bottom:1px solid #e2e8f0}
        .detail-row span{color:#64748b}.detail-row strong{font-size:16px}
        .actions{display:flex;justify-content:center;margin-top:22px}
        .back-btn{display:inline-block;text-decoration:none;background:#fff;color:#334155;border:1px solid #cbd5e1;border-radius:8px;padding:12px 18px;font-weight:800}
        @media(max-width:700px){body{padding:0}.pending-page{margin:0;min-height:100vh;border-radius:0;padding:20px 16px}.detail-row{grid-template-columns:1fr}.actions,.back-btn{width:100%}}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <main class="pending-page">
        <h1>Yêu cầu đang chờ duyệt</h1>
        <div class="pending-box">
            <span class="status-badge">Chờ tiếp nhận</span>
        </div>
        <?php
            $tenTrangThai = ['het_han'=>'Hết hạn','bao_mat'=>'Báo mất','thu_hoi'=>'Thu hồi'];
        ?>
        <div class="detail-row"><span>Mã hồ sơ</span><strong><?php echo e($yeuCau['ma_ho_so']); ?></strong></div>
        <div class="detail-row"><span>Trạng thái đề nghị</span><strong><?php echo e($tenTrangThai[$yeuCau['trang_thai_moi']] ?? $yeuCau['trang_thai_moi']); ?></strong></div>
        <div class="detail-row"><span>Lý do</span><strong><?php echo e($yeuCau['ly_do']); ?></strong></div>
        <div class="detail-row"><span>Thời gian gửi</span><strong><?php echo e($yeuCau['ngay_tiep_nhan']); ?></strong></div>
        <div class="actions">
            <a class="back-btn" href="/the_can_cuoc/trang_thai_cua_toi">Quay lại trạng thái thẻ</a>
        </div>
    </main>
</body>
</html>
