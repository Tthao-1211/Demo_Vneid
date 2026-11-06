<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trang chủ – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
</head>
<body>
  <?php require __DIR__ . '/thanh_menu.php'; ?>

  <section class="nen-do">
    <div class="chua hero">
      <div class="hero-trai">
        <div class="anh-dai-dien" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
          <?php if (!empty($anh_chan_dung)): ?>
            <img src="<?= e($anh_chan_dung) ?>" alt="Ảnh chân dung" style="width: 100%; height: 100%; object-fit: cover;">
          <?php else: ?>
            <?= e($chu_cai_dau) ?>
          <?php endif; ?>
        </div>
        <div>
          <p class="loi-chao">Xin chào,</p>
          <h1><?= e(mb_strtoupper($ho_ten, 'UTF-8')) ?></h1>
          <span class="the-muc">Định danh mức <?= $muc_dinh_danh ?> <b>★</b></span>
          <?php if ($muc_dinh_danh < 2): ?>
            <a class="the-muc" style="background:#ffd23f;margin-left:8px" href="/muc_dinh_danh/nop_ho_so">Nâng lên mức 2 →</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="hero-phai">
        <a class="the-qr" href="javascript:void(0)"
          onclick="hienThiQR('cccd')">
            <span>Mã QR<br><strong>Thẻ CCCD</strong></span>
        </a>

        <a class="the-qr" href="javascript:void(0)"
          onclick="hienThiQR('dinhdanh')">
            <img src="/public/images/logo-vneid.png" alt="">
            <span>Mã QR<br><strong>Định danh điện tử</strong></span>
        </a>
      </div>
    </div>
  </section>

  <div id="modalQR" class="modal-qr">
    <div class="modal-qr-content">
      <h3 id="qrTitle">Mã QR</h3>
      <div id="qrcode-box"></div>
      <p id="qrDesc" style="font-size: 13px; color: #666; margin-bottom: 16px;"></p>
      <button class="nut-dong" onclick="dongQR()">Đóng</button>
    </div>
  </div>

  <main class="chua than-trang">

    <section class="khoi">
      <h2>Nhóm dịch vụ</h2>
      <div class="luoi luoi-4">
        <a class="the-dich-vu" href="#"><i><svg viewBox="0 0 24 24"><path d="M3 10l9-6 9 6M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18"/></svg></i><span>Thủ tục hành chính</span><b>Mới</b></a>
        <a class="the-dich-vu" href="#"><i><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.6A4 4 0 0119 11c0 5.5-7 10-7 10z"/></svg></i><span>An sinh xã hội</span><b>Mới</b></a>
        <a class="the-dich-vu" href="#"><i><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M12 8v6M9 11h6"/></svg></i><span>Hồ sơ sức khỏe</span><b>Mới</b></a>
        <a class="the-dich-vu" href="#"><i><svg viewBox="0 0 24 24"><path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z"/></svg></i><span>Dịch vụ khác</span><b>Mới</b></a>
      </div>
    </section>

    <section class="banner">
      <div class="banner-chu">
        <small>NGÀY CÔNG DÂN SỐ</small>
        <strong>VIỆT NAM 2026</strong>
        <p>Khám phá các tiện ích công dân số, chính phủ số, xã hội số.</p>
        <a href="#" class="nut-vang">Khám phá ngay</a>
      </div>
      <img src="/public/images/logo-vneid.png" alt="">
    </section>

    <section class="khoi">
      <div class="tieu-de-hang">
        <h2>Tiện ích yêu thích</h2>
      </div>
      <div class="luoi luoi-3">
        <a class="the-tien-ich" href="#"><i><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M14 10h4M14 14h4M6 16c.7-1.5 4.3-1.5 6 0"/></svg></i><span>Thẻ Căn cước/CCCD</span></a>
        <a class="the-tien-ich" href="#"><i><svg viewBox="0 0 24 24"><path d="M3 15l2-6h14l2 6v4H3zM7 19v2M17 19v2"/></svg></i><span>Ví điện tử</span></a>
        <a class="the-tien-ich" href="/dich_vu/danh_sach"><i><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M12 8v7M8.5 11.5h7"/></svg></i><span>Dịch vụ công</span></a>
      </div>
    </section>
  </main>

  <footer class="chan-trang">
    <div class="chua">
      <span>© 2026 VNeID mô phỏng – Đồ án học phần</span>
      <span><a href="#">Chính sách quyền riêng tư</a> · Hotline 1900.0368 · Phiên bản 2.2.12</span>
    </div>
  </footer>

  <!-- Thư viện tạo QR -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>

  <!-- Truyền dữ liệu từ PHP sang JavaScript -->
  <script>
      window.qrData = {
          cccd: <?= json_encode(
              $qr_cccd_data,
              JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
          ) ?>,

          dinhdanh: <?= json_encode(
              $qr_dinh_danh_data,
              JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
          ) ?>
      };
  </script>

  <script src="/public/js/qr_modal.js"></script>

</body>
</html>