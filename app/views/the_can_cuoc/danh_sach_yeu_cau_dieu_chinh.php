<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách yêu cầu điều chỉnh</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#222}
        .request-shell{max-width:1180px;margin:28px auto;background:#fff;border:1px solid #d2d4d2;border-top:5px solid #b91c1c;border-radius:14px;box-shadow:0 14px 34px rgba(36,40,38,.12);overflow:hidden}
        .page-header{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:24px 30px;background:#fff;border-bottom:1px solid #e3e5e3}
        .page-header-main{border-left:5px solid #b91c1c;padding-left:15px}
        .page-header h1{margin:0;color:#b91c1c;font-size:30px;font-weight:800;line-height:1.15}
        .page-header p{margin:7px 0 0;color:#64748b;font-size:15px}
        .back-home{flex:none;text-decoration:none;color:#334155;background:#fff;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px;font-weight:700}
        .back-home:hover{border-color:#b91c1c;color:#b91c1c}
        .request-content{padding:24px 30px 30px}
        .summary-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:22px}
        .summary-card{min-height:74px;border:1px solid #e2e4e2;border-left:4px solid #b91c1c;border-radius:10px;padding:14px 16px;background:#fafafa;box-sizing:border-box}
        .summary-card span{display:block;color:#64748b;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.3px}
        .summary-card strong{display:block;color:#b91c1c;font-size:27px;margin-top:5px}
        .table-title{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
        .table-title h2{margin:0;color:#222;font-size:20px}
        .table-title span{color:#64748b;font-size:13px}
        .table-wrap{border:1px solid #dfe3e7;border-radius:10px;overflow:hidden}
        table{width:100%;border-collapse:collapse;table-layout:auto}
        th{background:#f3f4f3;color:#39414a;text-align:left;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:.35px;padding:13px 14px;border-bottom:1px solid #dfe3e7}
        td{padding:15px 14px;border-bottom:1px solid #edf0f3;font-size:14px;vertical-align:middle}
        tr:last-child td{border-bottom:none}tbody tr:hover{background:#fffafa}
        .code{font-weight:800;color:#b91c1c}.cccd{font-weight:800;color:#222;white-space:nowrap}
        .info-change{line-height:1.45;max-width:260px}.info-change strong{display:block;color:#1f2937}.info-change span{display:block;color:#64748b;font-size:12px;margin-top:3px}
        .reason{max-width:220px;line-height:1.45;color:#475569}
        .status{display:inline-block;border-radius:999px;padding:7px 10px;font-size:12px;font-weight:800;background:#fff1e6;color:#9a3412;white-space:nowrap}.status-approved{background:#dcfce7;color:#166534}.status-rejected{background:#fee2e2;color:#991b1b}
        .btn-detail{display:inline-flex;justify-content:center;text-decoration:none;background:#b91c1c;color:#fff;border-radius:7px;padding:9px 12px;font-weight:800;white-space:nowrap;box-sizing:border-box}
        .btn-detail:hover{background:#991b1b}
        .empty{padding:42px;text-align:center;color:#64748b}
        @media(max-width:760px){
            body{background:#fff}.request-shell{margin:0;border:0;border-radius:0;min-height:100vh}.page-header{align-items:flex-start;padding:18px 16px}.page-header-main{padding-left:12px}.page-header h1{font-size:24px}.page-header p{font-size:13px}.back-home{padding:8px 10px;font-size:13px}.request-content{padding:18px 16px}.summary-grid{grid-template-columns:1fr}.summary-card{min-height:auto}.table-title{align-items:flex-end}.table-wrap{overflow-x:auto}table{min-width:820px}
        }
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <main class="request-shell">
        <header class="page-header">
            <div class="page-header-main"><h1>Yêu cầu điều chỉnh thông tin</h1><p>Danh sách hồ sơ đề nghị thay đổi thông tin căn cước</p></div>
            <a class="back-home" href="/">← Trang chủ</a>
        </header>
        <div class="request-content">
        <?php
            $tongChoDuyet = 0;
            $daXuLy = 0;
            $daDuyet = 0;
            foreach ($dsYeuCau as $yc) {
                $trangThaiDem = $yc['trang_thai'] ?? '';
                if ($trangThaiDem === 'cho_tiep_nhan') {
                    $tongChoDuyet++;
                }
                if (in_array($trangThaiDem, ['da_duyet', 'tu_choi', 'hoan_thanh'], true)) {
                    $daXuLy++;
                }
                if (in_array($trangThaiDem, ['da_duyet', 'hoan_thanh'], true)) {
                    $daDuyet++;
                }
            }
        ?>
        <section class="summary-grid">
            <div class="summary-card"><span>Tổng yêu cầu chờ duyệt</span><strong><?php echo e($tongChoDuyet); ?></strong></div>
            <div class="summary-card"><span>Chờ xử lý</span><strong><?php echo e($daXuLy); ?></strong></div>
            <div class="summary-card"><span>Đã xử lý</span><strong><?php echo e($daDuyet); ?></strong></div>
        </section>
        <div class="table-title">
            <h2>Danh sách hồ sơ</h2>
            <span><?php echo e(count($dsYeuCau)); ?> yêu cầu</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Mã hồ sơ</th><th>Số CCCD</th><th>Thông tin đề nghị</th><th>Lý do</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                <tbody>
                <?php if (empty($dsYeuCau)): ?>
                    <tr><td colspan="6" class="empty">Hiện chưa có yêu cầu điều chỉnh thông tin nào.</td></tr>
                <?php endif; ?>
                <?php foreach ($dsYeuCau as $yc):
                    $thongTinMoi = trim((string)($yc['ho_ten_moi'] ?? ''));
                    if ($thongTinMoi === '') {
                        $thongTinMoi = trim((string)($yc['ngay_sinh_moi'] ?? ''));
                    }
                    if ($thongTinMoi === '') {
                        $thongTinMoi = trim((string)($yc['noi_thuong_tru_moi'] ?? ($yc['que_quan_moi'] ?? '')));
                    }
                    if ($thongTinMoi === '') {
                        $thongTinMoi = 'Xem chi tiết hồ sơ';
                    }
                    $trangThai = $yc['trang_thai'] ?? 'cho_tiep_nhan';
                    $statusClass = in_array($trangThai, ['da_duyet','hoan_thanh'], true) ? 'status-approved' : (in_array($trangThai, ['tu_choi'], true) ? 'status-rejected' : '');
                    $labelTrangThai = ['cho_duyet'=>'Chờ duyệt','da_duyet'=>'Đã duyệt','tu_choi'=>'Từ chối','hoan_thanh'=>'Hoàn thành'][$trangThai] ?? $trangThai;
                ?>
                    <tr>
                        <td class="code"><?php echo e($yc['ma_yeu_cau'] ?? '-'); ?></td>
                        <td><span class="cccd"><?php echo e($yc['so_cccd'] ?? '-'); ?></span></td>
                        <td class="info-change"><strong><?php echo e($thongTinMoi); ?></strong><span>Thông tin sau điều chỉnh</span></td>
                        <td class="reason"><?php echo e($yc['ly_do'] ?? 'Không có lý do'); ?></td>
                        <td><span class="status <?php echo e($statusClass); ?>"><?php echo e($labelTrangThai); ?></span></td>
                        <td><a class="btn-detail" href="/the_can_cuoc/chi_tiet_yeu_cau_dieu_chinh/<?php echo e($yc['ma_yeu_cau']); ?>">Xem chi tiết</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </main>
</body>
</html>
