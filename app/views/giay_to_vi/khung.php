<?php
// Khung chung. Biến: $tieu_de_trang, $muc_dang_chon, $noi_dung_trang, $so_chua_doc
$phan_hoi    = layPhanHoi();
$trang_chu   = '/' . layTrangChu($_SESSION['ma_vai_tro'] ?? 0);
$muc_dang_chon = $muc_dang_chon ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tieu_de_trang ?? 'VNeID') ?> - VNeID mô phỏng</title>
    <link rel="stylesheet" href="/public/css/khung.css">
    <link rel="stylesheet" href="/public/css/giay-to-vi.css">
    <link rel="stylesheet" href="/public/css/thong-bao.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/can-bo.css">

    <?php foreach (($css_them ?? []) as $tep_css): ?>
        <link rel="stylesheet" href="<?= e($tep_css) ?>">
    <?php endforeach; ?>
</head>
<body>
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
<div class="khung-ung-dung">
    <?php if (empty($an_tieu_de)): ?>
    <h1 class="tieu-de-trang"><?= e($tieu_de_trang ?? '') ?></h1>
    <?php endif; ?>
    <?php if ($phan_hoi): ?>
        <div class="phan-hoi <?= $phan_hoi['loai'] === 'thanh_cong' ? 'phan-hoi-thanh-cong' : 'phan-hoi-loi' ?>">
            <?= e($phan_hoi['noi_dung']) ?>
        </div>
    <?php endif; ?>

    <div class="noi-dung-trang"><?= $noi_dung_trang ?></div>
</div>

<?php foreach (($js_them ?? []) as $tep_js): ?>
    <script src="<?= e($tep_js) ?>"></script>
<?php endforeach; ?>

<script src="/public/js/can-bo.js"></script>
</body>
</html>
