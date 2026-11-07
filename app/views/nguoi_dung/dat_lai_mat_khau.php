<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đặt lại mật khẩu – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/dang-nhap.css">
</head>
<body>
  <header class="thanh-tren">
    <a class="nut-quay-lai" href="/nguoi_dung/dang_nhap" aria-label="Về đăng nhập">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
    </a>
    <span class="ngon-ngu"><span class="co-viet-nam">★</span> Tiếng Việt</span>
  </header>

  <main class="noi-dung">
    <section class="bang-dang-nhap">
      <h1>Đặt lại mật khẩu</h1>

      <?php if ($ds_loi): ?>
        <div class="thong-bao-loi"><?php foreach ($ds_loi as $loi): ?><div><?= e($loi) ?></div><?php endforeach; ?></div>
      <?php endif; ?>

      <form id="form-dat-lai-mat-khau" method="post" action="/nguoi_dung/dat_lai_mat_khau" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <label class="nhan-truong" for="mat_khau_moi">Mật khẩu mới (tối thiểu 8 ký tự)</label>
        <div class="o-nhap">
          <input type="password" id="mat_khau_moi" name="mat_khau_moi" placeholder="Nhập mật khẩu mới"
                 style="padding-left:16px" required autofocus>
        </div>

        <label class="nhan-truong" for="nhap_lai_mat_khau">Nhập lại mật khẩu mới</label>
        <div class="o-nhap">
          <input type="password" id="nhap_lai_mat_khau" name="nhap_lai_mat_khau" placeholder="Nhập lại mật khẩu"
                 style="padding-left:16px" required>
        </div>

        <button type="submit" class="nut-dang-nhap">Đặt lại mật khẩu</button>
      </form>
    </section>
  </main>
</body>
</html>