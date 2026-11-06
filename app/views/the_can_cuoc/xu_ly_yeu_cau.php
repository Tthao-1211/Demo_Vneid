<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xử lý yêu cầu cấp thẻ - VNeID</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;padding:20px;background:#E9EAE8;font-family:'Times New Roman',Times,serif;box-sizing:border-box}
        .brand{text-align:center;color:white;margin:20px 0}.brand strong{font-size:20px;text-transform:uppercase;display:block}.brand span{font-size:13px;color:#ffca2c}
        .container{max-width:760px;margin:auto;background:white;border-radius:16px;padding:35px;box-sizing:border-box}
        h1{text-align:center;font-size:23px}.back{color:#475569;text-decoration:none}
        .row{display:flex;padding:11px 0;border-bottom:1px solid #eee}.label{width:40%;color:#64748b}.value{width:60%;font-weight:bold}
        .notice{padding:14px;border-radius:8px;background:#dcfce7;color:#166534;margin-bottom:20px}
        textarea{width:100%;min-height:90px;padding:12px;border:1px solid #cbd5e1;border-radius:8px;box-sizing:border-box}
        .actions{display:flex;gap:12px;margin-top:16px}.actions button{flex:1;padding:13px;border:0;border-radius:8px;color:white;font-weight:bold;cursor:pointer}
        .approve{background:#15803d}.reject{background:#b91c1c}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="brand"><strong>Bộ Công An</strong><span>Trung tâm dữ liệu Quốc gia về dân cư</span></div>
    <div class="container">
        <a class="back" href="/the_can_cuoc/danh_sach_yeu_cau">← Danh sách yêu cầu</a>
        <h1>Xử lý yêu cầu cấp thẻ</h1>
        <?php if ($thongBao !== ''): ?><div class="notice"><?php echo htmlspecialchars($thongBao); ?></div><?php endif; ?>
        <div class="row"><div class="label">Mã hồ sơ</div><div class="value"><?php echo htmlspecialchars($yeuCau['ma_ho_so']); ?></div></div>
        <div class="row"><div class="label">Số định danh</div><div class="value"><?php echo htmlspecialchars($yeuCau['so_cccd']); ?></div></div>
        <div class="row"><div class="label">Họ tên</div><div class="value"><?php echo htmlspecialchars($yeuCau['ho_ten']); ?></div></div>
        <div class="row"><div class="label">Loại yêu cầu</div><div class="value"><?php echo htmlspecialchars($yeuCau['loai_yeu_cau']); ?></div></div>
        <div class="row"><div class="label">Lý do</div><div class="value"><?php echo htmlspecialchars($yeuCau['ly_do']); ?></div></div>
        <div class="row"><div class="label">Số điện thoại</div><div class="value"><?php echo htmlspecialchars($yeuCau['so_dien_thoai']); ?></div></div>
        <div class="row"><div class="label">Trạng thái yêu cầu</div><div class="value"><?php echo htmlspecialchars($yeuCau['trang_thai']); ?></div></div>
        <div class="row"><div class="label">Trạng thái thẻ hiện tại</div><div class="value"><?php echo htmlspecialchars($theCanCuoc['trang_thai'] ?? 'Chưa có thẻ'); ?></div></div>

        <?php if (!empty($lichSu)): ?>
        <h3>Lịch sử thay đổi trạng thái thẻ</h3>
        <?php foreach ($lichSu as $ls): ?>
            <div class="row">
                <div class="label"><?php echo date('d/m/Y H:i', strtotime($ls['ngay_thay_doi'])); ?></div>
                <div class="value"><?php echo htmlspecialchars(($ls['trang_thai_truoc'] ?? '-') . ' → ' . $ls['trang_thai_sau']); ?></div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!in_array($yeuCau['trang_thai'], ['hoan_thanh', 'tu_choi'], true)): ?>
        <form method="POST" style="margin-top:24px">
            <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
            <label><strong>Ghi chú xử lý / lý do từ chối</strong></label>
            <textarea name="ghi_chu" placeholder="Nhập ghi chú hoặc lý do xử lý"></textarea>
            <div class="actions">
                <button class="approve" type="submit" name="ket_qua" value="duyet">Duyệt yêu cầu</button>
                <button class="reject" type="submit" name="ket_qua" value="tu_choi">Từ chối yêu cầu</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
