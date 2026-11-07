<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đổi mật khẩu – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/cai-dat.css">
</head>
<body>
  <?php require __DIR__ . '/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi khoi-hep">
      <h2>Đổi mật khẩu</h2>
      <p class="ghi-chu">Sau khi nhập mật khẩu mới, mã OTP sẽ được gửi về email của bạn để xác nhận.</p>

      <?php if ($ds_loi): ?>
        <div class="hop-loi"><?php foreach ($ds_loi as $loi): ?><div><?= e($loi) ?></div><?php endforeach; ?></div>
      <?php endif; ?>

      <form method="post" action="/nguoi_dung/doi_mat_khau" class="form-ho-so" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <label for="mat_khau_cu">Mật khẩu hiện tại</label>
        <input type="password" id="mat_khau_cu" name="mat_khau_cu" required autofocus>

        <label for="mat_khau_moi">Mật khẩu mới (tối thiểu 8 ký tự)</label>
        <input type="password" id="mat_khau_moi" name="mat_khau_moi" required>

        <label for="nhap_lai_mat_khau">Nhập lại mật khẩu mới</label>
        <input type="password" id="nhap_lai_mat_khau" name="nhap_lai_mat_khau" required>

        <button type="submit" class="nut-do">Tiếp tục – gửi mã OTP</button>
      </form>
      <p><a href="/nguoi_dung/cai_dat">← Quay lại cài đặt</a></p>
    </section>
  </main>
</body>
</html>