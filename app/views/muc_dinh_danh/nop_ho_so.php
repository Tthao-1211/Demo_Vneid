<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nâng mức định danh – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi khoi-hep">
      <h2>Nâng mức định danh lên mức 2</h2>

      <?php if ($da_muc_hai): ?>
        <div class="hop-thong-tin">Tài khoản của bạn đã ở định danh mức 2.</div>
      <?php elseif ($dang_cho): ?>
        <div class="hop-thong-tin">Bạn đã có hồ sơ đang chờ xử lý. <a href="/muc_dinh_danh/danh_sach_cua_toi">Xem tình trạng hồ sơ</a></div>
      <?php else: ?>
        <p class="ghi-chu">Chuẩn bị ảnh chụp rõ nét mặt trước thẻ CCCD và ảnh chân dung (JPG hoặc PNG, tối đa 3 MB mỗi ảnh).</p>

        <?php if ($ds_loi): ?>
          <div class="hop-loi"><?php foreach ($ds_loi as $loi): ?><div><?= e($loi) ?></div><?php endforeach; ?></div>
        <?php endif; ?>

        <form method="post" action="/muc_dinh_danh/nop_ho_so" enctype="multipart/form-data" class="form-ho-so">
          <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

          <label for="so_the">Số thẻ căn cước</label>
          <input type="text" id="so_the" name="so_the" maxlength="12" value="<?= e($du_lieu['so_the']) ?>" required>

          <label for="noi_cap">Nơi cấp</label>
          <input type="text" id="noi_cap" name="noi_cap" placeholder="Cục Cảnh sát QLHC về TTXH" value="<?= e($du_lieu['noi_cap']) ?>" required>

          <label for="anh_mat_truoc">Ảnh mặt trước CCCD</label>
          <input type="file" id="anh_mat_truoc" name="anh_mat_truoc" accept="image/jpeg,image/png" required>

          <label for="anh_chan_dung">Ảnh chân dung</label>
          <input type="file" id="anh_chan_dung" name="anh_chan_dung" accept="image/jpeg,image/png" required>

          <label for="ghi_chu">Ghi chú (không bắt buộc)</label>
          <textarea id="ghi_chu" name="ghi_chu" rows="3"><?= e($du_lieu['ghi_chu']) ?></textarea>

          <button type="submit" class="nut-do">Gửi hồ sơ</button>
        </form>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>