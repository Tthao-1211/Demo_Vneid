<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Yêu cầu trạng thái thẻ</title><link rel="stylesheet" href="/css/vneid-theme.css"><link rel="stylesheet" href="/css/professional-form.css">
<style>body{margin:0;background:#E9EAE8;font-family:'Times New Roman',Times,serif;padding:30px}.box{max-width:1100px;margin:auto;background:#fff;border-radius:16px;padding:32px}table{width:100%;border-collapse:collapse}th,td{padding:12px;border-bottom:1px solid #e2e8f0;text-align:left}.btn{padding:8px 12px;background:#e3261f;color:#fff;text-decoration:none;border-radius:6px}</style></head>
<body><div class="box"><a href="/the_can_cuoc/quan_ly_trang_thai">← Quản lý thẻ</a><h1>Danh sách yêu cầu liên quan đến trạng thái thẻ</h1>
<table><thead><tr><th>Mã hồ sơ</th><th>Số CCCD</th><th>Loại yêu cầu</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
<?php foreach ($dsYeuCau as $yc):
    if (strpos((string)($yc['ghi_chu'] ?? ''), 'THAY_DOI_TRANG_THAI:') === 0) {
        $trangThaiMoi = substr((string)$yc['ghi_chu'], strlen('THAY_DOI_TRANG_THAI:'));
        $loai = $trangThaiMoi === 'bao_mat' ? 'Báo mất thẻ'
            : ($trangThaiMoi === 'thu_hoi' ? 'Thu hồi thẻ' : 'Hết hạn thẻ');
    } else {
        $loai = $yc['loai_yeu_cau'] === 'bao_mat' ? 'Báo mất thẻ' : 'Cấp lại thẻ';
    }
?>
<tr><td><?php echo e($yc['ma_ho_so']); ?></td><td><?php echo e($yc['so_cccd']); ?></td><td><?php echo e($loai); ?></td><td><?php echo e($yc['trang_thai']); ?></td><td><a class="btn" href="/the_can_cuoc/chi_tiet_yeu_cau_trang_thai/<?php echo e($yc['ma_yeu_cau']); ?>">Xem và xử lý</a></td></tr><?php endforeach; ?>
</tbody></table></div></body></html>
