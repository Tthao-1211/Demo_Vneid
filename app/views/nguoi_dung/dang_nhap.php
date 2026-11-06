<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập VNeID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/dang-nhap.css">
</head>
<body>
  <header class="thanh-tren">
    <a class="nut-quay-lai" href="/nguoi_dung/trang_dau" aria-label="Quay lại">
      <svg viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
    </a>
    <span class="ngon-ngu"><span class="co-viet-nam">★</span> Tiếng Việt</span>
  </header>

  <main class="noi-dung">
    <section class="bang-dang-nhap">
      <h1>Vui lòng nhập thông tin đăng nhập để tiếp tục</h1>

      <?php if ($thong_bao_loi): ?>
        <div class="thong-bao-loi"><?= e($thong_bao_loi) ?></div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['thong_bao'])): ?>
        <div class="thong-bao-thanh-cong"><?= e($_SESSION['thong_bao']) ?></div>
        <?php unset($_SESSION['thong_bao']); ?>
      <?php endif; ?>

      <form id="form-dang-nhap" method="post" action="/nguoi_dung/dang_nhap" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <label class="the-tai-khoan" for="so_dinh_danh">
          <span class="the-tai-khoan-chu">
            <small>Số định danh cá nhân</small>
            <input type="text" id="so_dinh_danh" name="so_dinh_danh" maxlength="12"
                   inputmode="numeric" placeholder="Nhập 12 chữ số" required autofocus>
          </span>
          <img src="/public/images/logo-vneid.png" alt="VNeID">
        </label>

        <label class="nhan-truong" for="mat_khau">Mật khẩu</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/><circle cx="12" cy="15.5" r="1"/></svg>
          <input type="password" id="mat_khau" name="mat_khau" placeholder="Nhập mật khẩu" required>
          <button type="button" class="nut-hien-mat-khau" id="nut-hien-mat-khau" aria-label="Hiện mật khẩu">
            <svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>

        <button type="submit" class="nut-dang-nhap">Đăng nhập</button>
      </form>

      <div class="hang-lien-ket">
        <a class="quen-mat-khau" href="/nguoi_dung/quen_mat_khau">Quên mật khẩu</a>
        <a class="doi-tai-khoan" href="/nguoi_dung/dang_ky">Tạo tài khoản mới
          <svg viewBox="0 0 24 24"><path d="M20 12a8 8 0 11-2.3-5.6M20 4v5h-5"/></svg>
        </a>
      </div>

      <ul class="ho-tro">
        <li>
          <a href="#">
            <svg viewBox="0 0 24 24"><path d="M3 5.5C5.5 4 9 4 12 6c3-2 6.5-2 9-.5V19c-2.5-1.5-6-1.5-9 .5-3-2-6.5-2-9-.5z"/><path d="M12 6v13.5"/></svg>
            <span>Hướng dẫn<br>sử dụng</span>
          </a>
        </li>
        <li>
          <a href="#">
            <svg viewBox="0 0 24 24"><path d="M5 5h14a2 2 0 012 2v8a2 2 0 01-2 2h-7l-5 4v-4H5a2 2 0 01-2-2V7a2 2 0 012-2z"/><path d="M10 9.5a2 2 0 114 0c0 1.5-2 1.5-2 3M12 15v.01"/></svg>
            <span>Câu hỏi<br>thường gặp</span>
          </a>
        </li>
        <li>
          <a href="tel:19000368">
            <svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>
            <span>Hotline<br>hỗ trợ</span>
          </a>
        </li>
      </ul>
    </section>
  </main>

  <footer class="chan-trang">
    <a href="#">Chính sách quyền riêng tư</a>
    <span>Phiên bản 2.2.12</span>
  </footer>

  <script>
    // Nút hiện/ẩn mật khẩu
    const oMatKhau = document.getElementById('mat_khau');
    document.getElementById('nut-hien-mat-khau').addEventListener('click', function () {
      const dangAn = oMatKhau.type === 'password';
      oMatKhau.type = dangAn ? 'text' : 'password';
      this.classList.toggle('dang-hien', dangAn);
    });
  </script>
</body>
</html>