<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Quản lý trạng thái thẻ</title><link rel="stylesheet" href="/css/vneid-theme.css"><link rel="stylesheet" href="/css/professional-form.css">
<style>body{margin:0;background:#E9EAE8;font-family:'Times New Roman',Times,serif;padding:30px}.box{max-width:1100px;margin:auto;background:#fff;border-radius:16px;padding:32px}table{width:100%;border-collapse:collapse}th,td{padding:12px;border-bottom:1px solid #e2e8f0;text-align:left}.btn{padding:8px 12px;background:#e3261f;color:#fff;text-decoration:none;border-radius:6px}</style></head>
<body><div class="box"><a href="/">← Trang chủ</a> <a href="/the_can_cuoc/danh_sach_yeu_cau_trang_thai" style="float:right">Danh sách yêu cầu liên quan</a><h1>Quản lý trạng thái thẻ căn cước</h1>
<table><thead><tr><th>Họ tên</th><th>Số CCCD</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
<?php foreach ($ds_the_can_cuoc as $the): ?><tr><td><?php echo e($the['ho_ten']); ?></td><td><?php echo e($the['so_cccd']); ?></td><td><?php echo e($the['trang_thai']); ?></td><td><a class="btn" href="/the_can_cuoc/quan_ly_trang_thai?id=<?php echo e($the['id']); ?>">Quản lý</a></td></tr><?php endforeach; ?>
</tbody></table></div></body></html>
