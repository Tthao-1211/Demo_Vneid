<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hồ sơ căn cước</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;background:#E9EAE8;font-family:'Times New Roman',Times,serif;padding:20px;color:#1e293b}
        .box{max-width:760px;margin:20px auto;background:#fff;border-radius:16px;padding:32px;box-sizing:border-box;box-shadow:0 15px 35px rgba(0,0,0,.35)}
        .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
        .back{color:#475569;text-decoration:none;font-weight:700;background:#f1f5f9;padding:9px 13px;border-radius:8px}
        h1{text-align:center;font-size:22px;margin:0 0 24px}
        .options{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px}
        .option{border:1px solid #e2e8f0;background:#fff;border-radius:14px;padding:18px 14px;text-align:center;font-weight:800;color:#334155;cursor:pointer;box-shadow:0 8px 22px rgba(15,23,42,.08)}
        .option:hover,.option.active{border-color:#e3261f;background:#fff5f4;color:#b91c1c;transform:translateY(-1px)}
        .option-icon{display:block;font-size:24px;margin-bottom:8px}
        .panel{display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:24px}
        .panel.active{display:block}
        .panel h2{margin:0 0 18px;font-size:22px;color:#111827}
        .identity{display:block}
        .profile-grid{display:block}
        .profile-row{display:grid;grid-template-columns:42% 58%;align-items:center;min-height:42px;padding:9px 0;border-bottom:1px solid #e2e8f0;font-size:15px;box-sizing:border-box}
        .profile-row span{color:#64748b}.profile-row strong{color:#111827;font-weight:500}
        .card-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
        .card-item{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px}
        .card-item span{display:block;color:#64748b;font-size:13px;margin-bottom:6px}
        .badge{display:inline-block;padding:6px 10px;border-radius:999px;font-size:13px;font-weight:800;background:#dcfce7;color:#166534}
        .badge.expired{background:#fee2e2;color:#991b1b}.badge.lost{background:#fff7ed;color:#c2410c}.badge.revoked{background:#e2e8f0;color:#334155}
        .request-btn,.adjust-btn{display:inline-block;margin-top:16px;padding:12px 18px;border-radius:8px;text-decoration:none;font-weight:800}
        .request-btn{background:#e3261f;color:#fff}.adjust-btn{background:#fff;color:#334155;border:1px solid #cbd5e1}
        .adjust-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .adjust-grid label{font-size:13px;font-weight:700;color:#334155}
        .adjust-grid input,.adjust-grid select,.adjust-grid textarea{width:100%;box-sizing:border-box;margin-top:6px;padding:11px;border:1px solid #cbd5e1;border-radius:8px;background:#fff}
        .adjust-grid textarea{min-height:86px}.span-2{grid-column:span 2}
        .submit-adjust{border:0;background:#e3261f;color:#fff;padding:12px 18px;border-radius:8px;font-weight:800}
        @media(max-width:700px){.options,.card-grid,.profile-grid,.adjust-grid{grid-template-columns:1fr}.identity{grid-template-columns:1fr}.portrait{width:150px;height:185px}.span-2{grid-column:span 1}}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="box">
        <div class="top">
            <a class="back" href="/">← Thoát</a>
        </div>
        <h1>Quản lý hồ sơ căn cước</h1>
        <div class="options">
            <button class="option active" type="button" data-panel="can-cuoc"><span class="option-icon">👤</span>Thông tin căn cước</button>
            <button class="option" type="button" data-panel="the"><span class="option-icon">💳</span>Thông tin thẻ</button>
            <button class="option" type="button" data-panel="dieu-chinh"><span class="option-icon">✏️</span>Yêu cầu điều chỉnh</button>
        </div>

        <div class="panel active" id="can-cuoc">
            <h2>Thông tin thẻ căn cước</h2>
            <div class="identity">
                <div class="profile-grid">
                    <div class="profile-row"><span>Số CCCD</span><strong><?php echo e($the['so_cccd']); ?></strong></div>
                    <div class="profile-row"><span>Họ và tên</span><strong><?php echo e($the['ho_ten']); ?></strong></div>
                    <div class="profile-row"><span>Ngày sinh</span><strong><?php echo e(date('d-m-Y', strtotime($the['ngay_sinh']))); ?></strong></div>
                    <div class="profile-row"><span>Giới tính</span><strong><?php echo e($the['gioi_tinh']); ?></strong></div>
                    <div class="profile-row"><span>Quốc tịch</span><strong><?php echo e($the['quoc_tich'] ?: 'Việt Nam'); ?></strong></div>
                    <div class="profile-row"><span>Dân tộc</span><strong><?php echo e($the['dan_toc'] ?: 'Chưa cập nhật'); ?></strong></div>
                    <div class="profile-row"><span>Tôn giáo</span><strong><?php echo e($the['ton_giao'] ?: 'Không'); ?></strong></div>
                    <div class="profile-row"><span>Quê quán</span><strong><?php echo e($the['que_quan'] ?: 'Chưa cập nhật'); ?></strong></div>
                    <div class="profile-row"><span>Nơi thường trú</span><strong><?php echo e($the['noi_thuong_tru'] ?: 'Chưa cập nhật'); ?></strong></div>
                    <div class="profile-row"><span>Nơi ở hiện tại</span><strong><?php echo e($the['noi_thuong_tru'] ?: 'Chưa cập nhật'); ?></strong></div>
                    <div class="profile-row"><span>Đặc điểm nhận dạng</span><strong><?php echo e($the['dac_diem_nhan_dang'] ?: 'Không có'); ?></strong></div>
                    <div class="profile-row"><span>Nơi cấp</span><strong><?php echo e($the['noi_cap'] ?: 'Chưa cập nhật'); ?></strong></div>
                </div>
            </div>
        </div>

        <div class="panel" id="the">
            <div class="card-grid">
                <div class="card-item"><span>Ngày cấp thẻ</span><strong><?php echo e(date('d-m-Y', strtotime($the['ngay_cap']))); ?></strong></div>
                <div class="card-item"><span>Ngày hết hạn</span><strong><?php echo e(date('d-m-Y', strtotime($the['ngay_het_han']))); ?></strong></div>
                <div class="card-item">
                    <span>Trạng thái thẻ</span>
                    <?php
                        $labels = ['con_hieu_luc'=>'Còn hiệu lực','het_han'=>'Hết hạn','bao_mat'=>'Báo mất','thu_hoi'=>'Thu hồi'];
                        $class = $the['trang_thai'] === 'het_han' ? 'expired' : ($the['trang_thai'] === 'bao_mat' ? 'lost' : ($the['trang_thai'] === 'thu_hoi' ? 'revoked' : ''));
                    ?>
                    <strong class="badge <?php echo $class; ?>"><?php echo e($labels[$the['trang_thai']] ?? $the['trang_thai']); ?></strong>
                </div>
            </div>
            <a class="request-btn" href="/the_can_cuoc/yeu_cau_cap_the?id=<?php echo e($the['id']); ?>">📄 Yêu cầu cấp thẻ</a>
        </div>

        <div class="panel" id="dieu-chinh">
            <form method="POST" action="/the_can_cuoc/de_nghi_dieu_chinh" enctype="multipart/form-data">
                <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
                <div class="adjust-grid">
                    <label>Số CCCD <input value="<?php echo e($the['so_cccd']); ?>" readonly></label>
                    <label>Họ và tên <input name="ho_ten_moi" value="<?php echo e($the['ho_ten']); ?>"></label>
                    <label>Ngày sinh <input type="date" name="ngay_sinh_moi" value="<?php echo e($the['ngay_sinh']); ?>"></label>
                    <label>Giới tính
                        <select name="gioi_tinh_moi"><option <?php echo $the['gioi_tinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option><option <?php echo $the['gioi_tinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option><option <?php echo $the['gioi_tinh'] === 'Khác' ? 'selected' : ''; ?>>Khác</option></select>
                    </label>
                    <label>Quốc tịch <input name="quoc_tich_moi" value="<?php echo e($the['quoc_tich'] ?: 'Việt Nam'); ?>"></label>
                    <label>Dân tộc <input name="dan_toc_moi" value="<?php echo e($the['dan_toc'] ?: ''); ?>"></label>
                    <label>Tôn giáo <input name="ton_giao_moi" value="<?php echo e($the['ton_giao'] ?: ''); ?>"></label>
                    <label>Quê quán <input name="que_quan_moi" value="<?php echo e($the['que_quan']); ?>"></label>
                    <label class="span-2">Nơi thường trú <textarea name="noi_thuong_tru_moi"><?php echo e($the['noi_thuong_tru']); ?></textarea></label>
                    <label class="span-2">Ảnh chân dung <input type="file" name="anh_chan_dung_moi" accept="image/jpeg,image/png,image/webp"></label>
                    <label class="span-2">Lý do điều chỉnh <textarea name="ly_do" required></textarea></label>
                    <div class="span-2"><button class="submit-adjust" type="submit">Gửi yêu cầu điều chỉnh</button></div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.option').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.option').forEach(function(item) { item.classList.remove('active'); });
                document.querySelectorAll('.panel').forEach(function(panel) { panel.classList.remove('active'); });
                button.classList.add('active');
                document.getElementById(button.dataset.panel).classList.add('active');
            });
        });
    </script>
</body>
</html>
