<?php
$ds_nhom = [
  'Bảo mật tài khoản' => [
    ['Đổi mật khẩu',           '/nguoi_dung/doi_mat_khau'],
  ],
  'Ứng dụng' => [
    ['Điều khoản sử dụng ứng dụng và dịch vụ', '#'],
    ['Chính sách quyền riêng tư',               '#'],
    ['Phiên bản ứng dụng', null, '2.2.12'],
  ],
  'Hỗ trợ' => [
    ['Hotline hỗ trợ',     'tel:19000368', '1900.0368'],
    ['Hướng dẫn sử dụng',  '#'],
    ['Câu hỏi thường gặp', '#'],
  ],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cài đặt – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/cai-dat.css">
</head>
<body>
  <?php require __DIR__ . '/thanh_menu.php'; ?>

  <section class="banner-cai-dat">
    <div class="chua">
      <div>
        <h1>Cài đặt</h1>
        <p>Quản lý mật khẩu và thông tin bảo mật để tài khoản của bạn an toàn hơn.</p>
      </div>
      <svg viewBox="0 0 64 64" width="96" height="96" aria-hidden="true">
        <path d="M32 4l22 8v16c0 14-9 25-22 32C19 53 10 42 10 28V12z" fill="#22a45d"/>
        <path d="M21 32l8 8 15-16" stroke="#fff" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
  </section>

  <main class="chua than-trang">
    <?php if (!empty($_SESSION['thong_bao'])): ?>
      <div class="hop-thong-tin"><?= e($_SESSION['thong_bao']) ?></div>
      <?php unset($_SESSION['thong_bao']); ?>
    <?php endif; ?>

    <?php foreach ($ds_nhom as $ten_nhom => $ds_dong): ?>
      <section class="khoi nhom-cai-dat">
        <h2><?= e($ten_nhom) ?></h2>
        <?php foreach ($ds_dong as $dong): ?>
          <?php $duong_dan = $dong[1]; $gia_tri = $dong[2] ?? ''; ?>
          <?php if ($duong_dan === null): ?>
            <div class="dong-cai-dat"><span><?= e($dong[0]) ?></span><b><?= e($gia_tri) ?></b></div>
          <?php else: ?>
            <a class="dong-cai-dat" href="<?= e($duong_dan) ?>">
              <span><?= e($dong[0]) ?></span>
              <?php if ($gia_tri !== ''): ?>
                <b class="gia-tri-do"><?= e($gia_tri) ?></b>
              <?php else: ?>
                <svg viewBox="0 0 24 24" class="mui-ten"><path d="M9 6l6 6-6 6"/></svg>
              <?php endif; ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </section>
    <?php endforeach; ?>

    <a class="nut-dang-xuat" href="/nguoi_dung/dang_xuat" onclick="return confirm('Đăng xuất khỏi tài khoản?');">Đăng xuất</a>
  </main>
</body>
</html>