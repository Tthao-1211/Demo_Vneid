<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hồ sơ nâng mức – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi">
      <h2>Hồ sơ nâng mức định danh <small>(hiện tại: mức <?= (int)$nguoi_dung['muc_dinh_danh'] ?>)</small></h2>

      <?php if (!empty($_SESSION['thong_bao'])): ?>
        <div class="hop-thong-tin"><?= e($_SESSION['thong_bao']) ?></div>
        <?php unset($_SESSION['thong_bao']); ?>
      <?php endif; ?>

      <?php if ((int)$nguoi_dung['muc_dinh_danh'] < 2): ?>
        <p><a class="nut-do nut-nho" href="/muc_dinh_danh/nop_ho_so">Nộp hồ sơ mới</a></p>
      <?php endif; ?>

      <div class="bang-cuon">
        <table class="bang">
          <thead><tr><th>Mã hồ sơ</th><th>Ngày nộp</th><th>Trạng thái</th><th>Ghi chú của cán bộ</th></tr></thead>
          <tbody>
            <?php foreach ($ds_ho_so as $ho_so): ?>
              <tr>
                <td>#<?= (int)$ho_so['ma_ho_so'] ?></td>
                <td><?= e($ho_so['ngay_nop']) ?></td>
                <td><span class="nhan nhan-<?= e($ho_so['trang_thai']) ?>"><?= e(NangMucDinhDanh::NHAN_TRANG_THAI[$ho_so['trang_thai']]) ?></span></td>
                <td><?= e($ho_so['ghi_chu'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$ds_ho_so): ?>
              <tr><td colspan="4" class="trong">Bạn chưa nộp hồ sơ nào.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>