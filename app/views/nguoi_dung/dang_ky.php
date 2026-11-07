<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng ký VNeID</title>
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
      <h1>Vui lòng nhập thông tin đăng ký để tiếp tục</h1>

      <?php if ($ds_loi): ?>
        <div class="thong-bao-loi">
          <?php foreach ($ds_loi as $loi): ?>
            <div><?= e($loi) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form id="form-dang-ky" method="post" action="/nguoi_dung/dang_ky" autocomplete="off">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <div class="nhan-hang">
            <label class="nhan-truong" for="so_dinh_danh">
                Số định danh cá nhân
            </label>
        </div>

        <div class="o-nhap">
            <svg class="icon-trai" viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <circle cx="9" cy="11" r="2"/>
                <path d="M14 10h4M14 14h4M6 16c.7-1.5 4.3-1.5 6 0"/>
            </svg>

            <input
                type="text"
                id="so_dinh_danh"
                value="Hệ thống tự động cấp sau khi đăng ký"
                readonly
            >
        </div>

        <small class="ghi-chu">
            Số định danh sẽ được hệ thống tự động cấp và không thể thay đổi.
        </small>

        <label class="nhan-truong" for="ho_ten">Họ và tên</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>
          <input type="text" id="ho_ten" name="ho_ten" placeholder="Nhập họ và tên"
                 value="<?= e($du_lieu['ho_ten']) ?>" required>
        </div>

        <label class="nhan-truong" for="so_dien_thoai">Số điện thoại</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/></svg>
          <input type="text" id="so_dien_thoai" name="so_dien_thoai" maxlength="10" inputmode="numeric"
                 placeholder="Nhập số điện thoại" value="<?= e($du_lieu['so_dien_thoai']) ?>" required>
        </div>

        <label class="nhan-truong" for="email">Email (nhận mã OTP)</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
          <input type="email" id="email" name="email" placeholder="Nhập email"
                 value="<?= e($du_lieu['email']) ?>" required>
        </div>

        <label class="nhan-truong" for="mat_khau">Mật khẩu (tối thiểu 8 ký tự)</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/><circle cx="12" cy="15.5" r="1"/></svg>
          <input type="password" id="mat_khau" name="mat_khau" placeholder="Nhập mật khẩu" required>
          <button type="button" class="nut-hien-mat-khau" data-muc-tieu="mat_khau" aria-label="Hiện mật khẩu">
            <svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>

        <label class="nhan-truong" for="nhap_lai_mat_khau">Nhập lại mật khẩu</label>
        <div class="o-nhap">
          <svg class="icon-trai" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/><circle cx="12" cy="15.5" r="1"/></svg>
          <input type="password" id="nhap_lai_mat_khau" name="nhap_lai_mat_khau" placeholder="Nhập lại mật khẩu" required>
          <button type="button" class="nut-hien-mat-khau" data-muc-tieu="nhap_lai_mat_khau" aria-label="Hiện mật khẩu">
            <svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>

        <button type="submit" class="nut-dang-nhap">Đăng ký</button>
      </form>

      <p class="dong-chuyen-trang">Bạn đã có tài khoản? <a href="/nguoi_dung/dang_nhap">Đăng nhập</a></p>

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
    document.querySelectorAll('.nut-hien-mat-khau').forEach(function (nut) {
      nut.addEventListener('click', function () {
        const o = document.getElementById(nut.dataset.mucTieu);
        const dangAn = o.type === 'password';
        o.type = dangAn ? 'text' : 'password';
        nut.classList.toggle('dang-hien', dangAn);
      });
    });
  </script>
</body>
</html>