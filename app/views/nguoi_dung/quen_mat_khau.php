<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quên mật khẩu – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/dang-nhap.css">
</head>
<body>
  <header class="thanh-tren">
    <a class="nut-quay-lai" href="/nguoi_dung/dang_nhap" aria-label="Quay lại">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
    </a>
    <span class="ngon-ngu"><span class="co-viet-nam">★</span> Tiếng Việt</span>
  </header>

  <main class="noi-dung">
    <section class="bang-dang-nhap">
      <h1>Quên mật khẩu</h1>
      <p style="color:#4b5563;font-size:15px;margin:0 0 18px">
        Nhập số định danh và email đã đăng ký. Mã OTP sẽ được gửi để bạn đặt lại mật khẩu.
      </p>

      <?php if ($ds_loi): ?>
        <div class="thong-bao-loi"><?php foreach ($ds_loi as $loi): ?><div><?= e($loi) ?></div><?php endforeach; ?></div>
      <?php endif; ?>

      <form id="form-quen-mat-khau" method="post" action="/nguoi_dung/quen_mat_khau" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <label class="nhan-truong" for="so_dinh_danh">Số định danh cá nhân</label>
        <div class="o-nhap">
          <input type="text" id="so_dinh_danh" name="so_dinh_danh" maxlength="12" inputmode="numeric"
                 placeholder="Nhập số định danh cá nhân" value="<?= e($du_lieu['so_dinh_danh']) ?>"
                 style="padding-left:16px" required autofocus>
        </div>

        <label class="nhan-truong" for="email">Email đã đăng ký</label>
        <div class="o-nhap">
          <input type="email" id="email" name="email" placeholder="Nhập email"
                 value="<?= e($du_lieu['email']) ?>" style="padding-left:16px" required>
        </div>

        <button type="submit" class="nut-dang-nhap">Gửi mã OTP</button>
      </form>

      <p class="dong-chuyen-trang">Nhớ ra mật khẩu rồi? <a href="/nguoi_dung/dang_nhap">Đăng nhập</a></p>
    </section>
  </main>
</body>
</html>