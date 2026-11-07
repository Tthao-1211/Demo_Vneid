<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chi tiết hồ sơ #<?= (int)$ho_so['ma_ho_so'] ?> – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi">
      <h2>Hồ sơ #<?= (int)$ho_so['ma_ho_so'] ?> – <?= e($ho_so['ho_ten']) ?></h2>

      <?php if (!empty($_SESSION['thong_bao'])): ?>
        <div class="hop-loi"><?= e($_SESSION['thong_bao']) ?></div>
        <?php unset($_SESSION['thong_bao']); ?>
      <?php endif; ?>

      <dl class="thong-tin">
        <dt>Số định danh tài khoản</dt><dd><?= e($ho_so['so_dinh_danh']) ?></dd>
        <dt>Số thẻ khai báo</dt><dd><?= e($thong_tin['so_the'] ?? '') ?></dd>
        <dt>Nơi cấp</dt><dd><?= e($thong_tin['noi_cap'] ?? '') ?></dd>
        <dt>Ghi chú của công dân</dt><dd><?= e($thong_tin['ghi_chu'] ?? '') ?></dd>
        <dt>Mức hiện tại</dt><dd>Mức <?= (int)$ho_so['muc_dinh_danh'] ?></dd>
        <dt>Trạng thái</dt><dd><span class="nhan nhan-<?= e($ho_so['trang_thai']) ?>"><?= e(NangMucDinhDanh::NHAN_TRANG_THAI[$ho_so['trang_thai']]) ?></span></dd>
      </dl>

      <div class="hang-anh">
        <figure><img src="/muc_dinh_danh/xem_tep?ma_ho_so=<?= (int)$ho_so['ma_ho_so'] ?>&vi_tri=0" alt="Mặt trước CCCD"><figcaption>Ảnh mặt trước CCCD</figcaption></figure>
        <figure><img src="/muc_dinh_danh/xem_tep?ma_ho_so=<?= (int)$ho_so['ma_ho_so'] ?>&vi_tri=1" alt="Ảnh chân dung"><figcaption>Ảnh chân dung</figcaption></figure>
      </div>

      <?php if ($ho_so['trang_thai'] === 'dang_xu_ly'): ?>
        <form method="post" class="form-ho-so">
          <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
          <input type="hidden" name="ma_ho_so" value="<?= (int)$ho_so['ma_ho_so'] ?>">
          <label for="ghi_chu">Ghi chú / lý do (bắt buộc khi từ chối hoặc yêu cầu bổ sung)</label>
          <textarea id="ghi_chu" name="ghi_chu" rows="3"></textarea>
          <div class="hang-nut">
            <button type="submit" formaction="/muc_dinh_danh/duyet" class="nut-do"
                    onclick="return confirm('Duyệt và nâng công dân này lên mức 2?');">Duyệt</button>
            <button type="submit" formaction="/muc_dinh_danh/yeu_cau_bo_sung" class="nut-vien">Yêu cầu bổ sung</button>
            <button type="submit" formaction="/muc_dinh_danh/tu_choi" class="nut-vien"
                    onclick="return confirm('Từ chối hồ sơ này?');">Từ chối</button>
          </div>
        </form>
      <?php else: ?>
        <p class="ghi-chu">Hồ sơ đã được xử lý. Ghi chú: <?= e($ho_so['ghi_chu'] ?? '(không có)') ?></p>
      <?php endif; ?>

      <p><a href="/muc_dinh_danh/danh_sach_cho_duyet">← Quay lại danh sách</a></p>
    </section>
  </main>
</body>
</html>