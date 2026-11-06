<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu điều chỉnh của tôi</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#111827;padding:28px 16px}
        .box{max-width:980px;margin:auto;background:#fff;border-radius:18px;padding:28px 30px 32px;box-shadow:0 18px 45px rgba(0,0,0,.22)}
        .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
        .back-link{color:#c31818;text-decoration:none;font-weight:800;font-size:14px}
        h1{margin:0 0 22px;text-align:center;font-size:24px;color:#111827}
        .summary{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
        .summary-item{border:1px solid #e5e7eb;border-radius:10px;padding:14px;text-align:center;background:#fafafa}
        .summary-item strong{display:block;font-size:21px;color:#c31818;margin-bottom:4px}
        .summary-item span{font-size:13px;color:#64748b}
        .request-list{display:grid;gap:12px}
        .request-card{border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#fff}
        .request-head{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;border-bottom:1px solid #eef2f7;background:#fffafa}
        .request-code{font-weight:800;color:#111827}
        .request-body{display:grid;grid-template-columns:1fr 180px;gap:16px;padding:16px}
        .reason{color:#334155;line-height:1.55}
        .meta{border-left:1px solid #e2e8f0;padding-left:16px}
        .meta-row{margin-bottom:10px;font-size:13px}
        .meta-row span{display:block;color:#64748b;margin-bottom:3px}
        .meta-row strong{font-weight:700;color:#111827}
        .badge{display:inline-block;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800;background:#fff7ed;color:#c2410c}
        .badge.approved{background:#dcfce7;color:#166534}
        .badge.rejected{background:#fee2e2;color:#991b1b}
        .badge.processing{background:#e0f2fe;color:#0369a1}
        .empty{text-align:center;padding:36px 16px;border:1px dashed #d1d5db;border-radius:12px;color:#64748b;background:#fafafa}
        .empty strong{display:block;font-size:17px;color:#334155;margin-bottom:7px}
        @media(max-width:700px){
            body{padding:0}.box{min-height:100vh;border-radius:0;padding:20px 16px}
            .summary{grid-template-columns:repeat(3,1fr);gap:6px}.summary-item{padding:10px 5px}
            .request-body{grid-template-columns:1fr}.meta{border-left:0;border-top:1px solid #e2e8f0;padding-left:0;padding-top:12px}
        }
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="box">
        <div class="top"><a class="back-link" href="/the_can_cuoc/quan_ly_ho_so_cua_toi">← Quay lại quản lý hồ sơ</a></div>
        <h1>Yêu cầu điều chỉnh của tôi</h1>
        <?php
            $choTiepNhan = 0; $daXuLy = 0; $tuChoi = 0;
            foreach ($dsYeuCau as $yc) {
                if (in_array($yc['trang_thai'], ['cho_tiep_nhan', 'dang_xu_ly'], true)) $choTiepNhan++;
                elseif (in_array($yc['trang_thai'], ['da_duyet', 'hoan_thanh'], true)) $daXuLy++;
                elseif ($yc['trang_thai'] === 'tu_choi') $tuChoi++;
            }
        ?>
        <div class="summary">
            <div class="summary-item"><strong><?php echo count($dsYeuCau); ?></strong><span>Tổng yêu cầu</span></div>
            <div class="summary-item"><strong><?php echo $choTiepNhan; ?></strong><span>Đang xử lý</span></div>
            <div class="summary-item"><strong><?php echo $tuChoi; ?></strong><span>Bị từ chối</span></div>
        </div>

        <?php if (empty($dsYeuCau)): ?>
            <div class="empty"><strong>Chưa có yêu cầu điều chỉnh</strong>Bạn chưa gửi yêu cầu thay đổi thông tin căn cước.</div>
        <?php else: ?>
            <div class="request-list">
                <?php foreach ($dsYeuCau as $yc): ?>
                    <?php
                        $trangThai = $yc['trang_thai'];
                        $nhanTrangThai = 'Chờ tiếp nhận';
                        $lopTrangThai = '';
                        if (in_array($trangThai, ['dang_xu_ly'], true)) { $nhanTrangThai = 'Đang xử lý'; $lopTrangThai = 'processing'; }
                        elseif (in_array($trangThai, ['da_duyet', 'hoan_thanh'], true)) { $nhanTrangThai = 'Đã duyệt'; $lopTrangThai = 'approved'; }
                        elseif ($trangThai === 'tu_choi') { $nhanTrangThai = 'Từ chối'; $lopTrangThai = 'rejected'; }
                        $ngayGui = $yc['ngay_gui'] ?? ($yc['ngay_yeu_cau'] ?? '');
                    ?>
                    <article class="request-card">
                        <div class="request-head">
                            <div class="request-code">Mã yêu cầu: DC-<?php echo e($yc['ma_yeu_cau']); ?></div>
                            <span class="badge <?php echo $lopTrangThai; ?>"><?php echo e($nhanTrangThai); ?></span>
                        </div>
                        <div class="request-body">
                            <div>
                                <div class="meta-row"><span>Lý do điều chỉnh</span><strong class="reason"><?php echo e($yc['ly_do'] ?: 'Không có lý do'); ?></strong></div>
                                <div class="meta-row"><span>Ghi chú cán bộ</span><strong><?php echo e($yc['ghi_chu'] ?: 'Chưa có ghi chú xử lý'); ?></strong></div>
                            </div>
                            <div class="meta">
                                <div class="meta-row"><span>Số CCCD</span><strong><?php echo e($yc['so_cccd'] ?? ''); ?></strong></div>
                                <div class="meta-row"><span>Ngày gửi</span><strong><?php echo $ngayGui ? e(date('d/m/Y H:i', strtotime($ngayGui))) : 'Không có dữ liệu'; ?></strong></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
