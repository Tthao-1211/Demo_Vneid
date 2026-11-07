<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Xác thực OTP</title>
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
      <h1>Nhập mã xác thực OTP</h1>
      <p style="color:#4b5563;font-size:15px;margin:0 0 18px">
        Mã gồm 6 chữ số đã được gửi tới <strong><?= e($email_che) ?></strong>,
        hiệu lực <?= THOI_GIAN_OTP_PHUT ?> phút.
      </p>

      <?php if ($thong_bao_loi): ?>
        <div class="thong-bao-loi"><?= e($thong_bao_loi) ?></div>
      <?php endif; ?>

      <form id="form-xac-thuc-otp" method="post" action="/nguoi_dung/xac_thuc_otp" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
        <div class="o-nhap">
          <input type="text" name="ma_otp" maxlength="6" inputmode="numeric"
                 autocomplete="one-time-code" placeholder="••••••" required autofocus
                 style="text-align:center;font-size:24px;letter-spacing:10px">
        </div>
        <button type="submit" class="nut-dang-nhap">Xác nhận</button>
      </form>

      <form method="post" action="/nguoi_dung/gui_lai_otp" style="margin-top:14px;text-align:center">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
        <button type="submit" style="border:0;background:none;color:#d71f18;font:inherit;cursor:pointer">
          Gửi lại mã
        </button>
      </form>
    </section>
  </main>
</body>
</html>