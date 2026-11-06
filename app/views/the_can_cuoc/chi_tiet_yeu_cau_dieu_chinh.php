<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết yêu cầu điều chỉnh</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#222}
        .detail-shell{max-width:980px;margin:28px auto;background:#fff;border:1px solid #d2d4d2;border-top:5px solid #b91c1c;border-radius:14px;box-shadow:0 14px 34px rgba(36,40,38,.12);overflow:hidden}
        .detail-header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:22px 28px;border-bottom:1px solid #e2e5e2}
        .detail-title{border-left:5px solid #b91c1c;padding-left:14px}.detail-title h1{margin:0;color:#b91c1c;font-size:28px}.detail-title p{margin:6px 0 0;color:#64748b}
        .back-link{text-decoration:none;color:#334155;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-weight:700;white-space:nowrap}
        .detail-content{padding:24px 28px 28px}.notice{padding:12px 14px;margin-bottom:18px;border-radius:8px;border:1px solid #bbf7d0;background:#dcfce7;color:#166534;font-weight:700}
        .request-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}.meta-card{border:1px solid #e2e5e2;border-radius:10px;background:#fafafa;padding:14px}.meta-card span{display:block;color:#64748b;font-size:13px}.meta-card strong{display:block;margin-top:5px;color:#222;font-size:15px}
        .compare-table{border:1px solid #dfe3e7;border-radius:10px;overflow:hidden}.compare-row{display:grid;grid-template-columns:180px 1fr 1fr;border-bottom:1px solid #edf0f3}.compare-row:last-child{border-bottom:none}.compare-row.header{background:#f4f5f4;font-weight:800;color:#475569}.compare-row>div{padding:13px 14px}.compare-row>div:not(:last-child){border-right:1px solid #edf0f3}.old-value{color:#64748b}.new-value{font-weight:800;color:#991b1b}
        .reason-box{margin-top:18px;border:1px solid #e2e5e2;border-radius:10px;padding:16px;background:#fffafa}.reason-box span{display:block;color:#64748b;font-size:13px}.reason-box strong{display:block;margin-top:6px;line-height:1.45}
        .result-box{margin-top:18px;border-left:4px solid #b91c1c;background:#f8faf8;padding:14px 16px;border-radius:8px}.result-box strong{display:block;margin-bottom:5px}
        .action-form{margin-top:18px;border-top:1px solid #e2e5e2;padding-top:18px}.action-form label{display:block;font-weight:800;margin-bottom:8px}.action-form textarea{width:100%;min-height:90px;border:1px solid #cbd5e1;border-radius:8px;padding:12px;box-sizing:border-box;font-family:'Times New Roman',Times,serif;font-size:15px}.action-buttons{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px}.action-buttons button{border:none;border-radius:8px;padding:12px;font-family:'Times New Roman',Times,serif;font-size:15px;font-weight:800;cursor:pointer}.approve{background:#b91c1c;color:#fff}.reject{background:#fff;color:#334155;border:1px solid #cbd5e1!important}
        @media(max-width:760px){body{background:#fff}.detail-shell{margin:0;border:0;border-radius:0;min-height:100vh}.detail-header{align-items:flex-start;padding:18px 16px}.detail-title h1{font-size:24px}.detail-content{padding:18px 16px}.request-meta{grid-template-columns:1fr}.compare-table{overflow-x:auto}.compare-row{min-width:680px}.action-buttons{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <?php
        $lay = function($key, $macDinh = '') use ($yeuCau) {
            return trim((string)($yeuCau[$key] ?? $macDinh));
        };
        $trangThai = $lay('trang_thai', 'cho_tiep_nhan');
        $nhanTrangThai = [
            'cho_tiep_nhan' => 'Chờ tiếp nhận',
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'hoan_thanh' => 'Hoàn thành',
            'tu_choi' => 'Từ chối'
        ];
        $truocSau = [
            'Họ và tên' => ['ho_ten_cu', 'ho_ten_moi'],
            'Ngày sinh' => ['ngay_sinh_cu', 'ngay_sinh_moi'],
            'Giới tính' => ['gioi_tinh_cu', 'gioi_tinh_moi'],
            'Quốc tịch' => ['quoc_tich_cu', 'quoc_tich_moi'],
            'Dân tộc' => ['dan_toc_cu', 'dan_toc_moi'],
            'Tôn giáo' => ['ton_giao_cu', 'ton_giao_moi'],
            'Quê quán' => ['que_quan_cu', 'que_quan_moi'],
            'Nơi thường trú' => ['noi_thuong_tru_cu', 'noi_thuong_tru_moi']
        ];
    ?>
    <main class="detail-shell">
        <header class="detail-header">
            <div class="detail-title"><h1>Chi tiết yêu cầu điều chỉnh</h1><p>Thông tin trước và sau khi đề nghị thay đổi</p></div>
            <a class="back-link" href="/the_can_cuoc/danh_sach_yeu_cau_dieu_chinh">← Danh sách</a>
        </header>
        <div class="detail-content">
            <?php if (!empty($thongBao)): ?><div class="notice"><?php echo e($thongBao); ?></div><?php endif; ?>
            <section class="request-meta">
                <div class="meta-card"><span>Mã hồ sơ</span><strong><?php echo e($lay('ma_ho_so', 'YC-'.$lay('ma_yeu_cau'))); ?></strong></div>
                <div class="meta-card"><span>Số CCCD</span><strong><?php echo e($lay('so_cccd', '-')); ?></strong></div>
                <div class="meta-card"><span>Trạng thái</span><strong><?php echo e($nhanTrangThai[$trangThai] ?? $trangThai); ?></strong></div>
            </section>
            <section class="compare-table">
                <div class="compare-row header"><div>Thông tin</div><div>Thông tin hiện tại</div><div>Thông tin đề nghị</div></div>
                <?php foreach ($truocSau as $ten => $keys):
                    $cu = $lay($keys[0], 'Không có dữ liệu');
                    $moi = $lay($keys[1], '');
                    if ($ten === 'Ngày sinh') {
                        if ($cu !== '' && $cu !== 'Không có dữ liệu') {
                            $timestamp = strtotime($cu);
                            if ($timestamp !== false) { $cu = date('d/m/Y', $timestamp); }
                        }
                        if ($moi !== '') {
                            $timestamp = strtotime($moi);
                            if ($timestamp !== false) { $moi = date('d/m/Y', $timestamp); }
                        }
                    }
                    if ($moi === '') { $moi = $cu; }
                ?>
                    <div class="compare-row"><div><strong><?php echo e($ten); ?></strong></div><div class="old-value"><?php echo e($cu); ?></div><div class="new-value"><?php echo e($moi); ?></div></div>
                <?php endforeach; ?>
            </section>
            <div class="reason-box"><span>Lý do công dân</span><strong><?php echo e($lay('ly_do', 'Không có lý do')); ?></strong></div>
            <?php if (!in_array($trangThai, ['da_duyet','hoan_thanh','tu_choi'], true)): ?>
                <form method="POST" class="action-form">
                    <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
                    <label for="ghi_chu">Lý do duyệt hoặc từ chối</label>
                    <textarea id="ghi_chu" name="ghi_chu" placeholder="Nhập ghi chú xử lý"></textarea>
                    <div class="action-buttons">
                        <button class="approve" name="ket_qua" value="duyet">Duyệt yêu cầu</button>
                        <button class="reject" name="ket_qua" value="tu_choi">Từ chối</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="result-box">
                    <strong>Kết quả: <?php echo e($nhanTrangThai[$trangThai] ?? $trangThai); ?></strong>
                    <span>Ghi chú: <?php echo e($lay('ghi_chu', 'Không có ghi chú')); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
