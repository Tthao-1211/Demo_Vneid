<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hồ sơ nâng mức cần xử lý – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi">
      <h2>Hồ sơ nâng mức định danh</h2>
      <p><a class="nut-quay-lai-duyet" href="/nguoi_dung/trang_chu_can_bo">← Quay lại</a></p>

      <?php if (!empty($_SESSION['thong_bao'])): ?>
        <div class="hop-thong-tin"><?= e($_SESSION['thong_bao']) ?></div>
        <?php unset($_SESSION['thong_bao']); ?>
      <?php endif; ?>

      <form method="get" class="hang-loc">
        <select name="trang_thai" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          <?php foreach (NangMucDinhDanh::NHAN_TRANG_THAI as $khoa => $ten): ?>
            <option value="<?= e($khoa) ?>" <?= $trang_thai === $khoa ? 'selected' : '' ?>><?= e($ten) ?></option>
          <?php endforeach; ?>
        </select>
      </form>

      <div class="bang-cuon">
        <table class="bang">
          <thead><tr><th>Mã</th><th>Công dân</th><th>Số định danh</th><th>Ngày nộp</th><th>Trạng thái</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($ds_ho_so as $ho_so): ?>
              <tr>
                <td>#<?= (int)$ho_so['ma_ho_so'] ?></td>
                <td><?= e($ho_so['ho_ten']) ?></td>
                <td><?= e($ho_so['so_dinh_danh']) ?></td>
                <td><?= e($ho_so['ngay_nop']) ?></td>
                <td><span class="nhan nhan-<?= e($ho_so['trang_thai']) ?>"><?= e(NangMucDinhDanh::NHAN_TRANG_THAI[$ho_so['trang_thai']]) ?></span></td>
                <td><a class="nut-do nut-nho" href="/muc_dinh_danh/chi_tiet?ma_ho_so=<?= (int)$ho_so['ma_ho_so'] ?>">Xem</a></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$ds_ho_so): ?>
              <tr><td colspan="6" class="trong">Không có hồ sơ.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>