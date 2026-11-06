<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Duyệt hồ sơ – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/quan-tri.css">
</head>
<body>
  <?php require __DIR__ . '/thanh_menu.php'; ?>

  <main class="chua than-trang">
    <section class="khoi">
      <h2>Hồ sơ</h2>

      <div class="luoi luoi-4">
        <?php foreach ($NHAN_CHUNG as $khoa => $ten_tt): ?>
          <a class="the-so" href="/nguoi_dung/trang_chu_can_bo?trang_thai=<?= e($khoa) ?>"
             style="<?= $trang_thai === $khoa ? 'outline:2px solid #d71f18' : '' ?>">
            <strong><?= (int)($ds_thong_ke[$khoa] ?? 0) ?></strong>
            <span><?= e($ten_tt) ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <form method="get" action="/nguoi_dung/trang_chu_can_bo" class="thanh-loc" style="margin-top:22px">
        <select name="trang_thai">
          <option value="">Tất cả trạng thái</option>
          <?php foreach (NangMucDinhDanh::NHAN_TRANG_THAI as $khoa => $ten_tt): ?>
            <option value="<?= e($khoa) ?>" <?= $trang_thai === $khoa ? 'selected' : '' ?>><?= e($ten_tt) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="tu_khoa" value="<?= e($tu_khoa) ?>" placeholder="Họ tên, số định danh">
        <input type="date" name="tu_ngay" value="<?= e($tu_ngay) ?>">
        <input type="date" name="den_ngay" value="<?= e($den_ngay) ?>">
        <button type="submit" class="nut-do">Lọc</button>
        <a href="/nguoi_dung/trang_chu_can_bo" class="nut-vien" title="Xóa bộ lọc">Xóa</a>
      </form>

      <div class="bang-cuon">
        <?php if (!empty($canh_bao)): ?><p class="trong"><?= e($canh_bao) ?></p><?php endif; ?>
        <table class="bang">
          <thead>
            <tr><th>Mã</th><th>Nguồn</th><th>Loại</th><th>Công dân</th><th>Số định danh</th><th>Ngày</th><th>Trạng thái</th></tr>
          </thead>
          <tbody>
            <?php foreach ($ds_ho_so as $h): ?>
              <tr>
                <td><?= e($h['ma']) ?></td>
                <td><?= e($h['nguon']) ?></td>
                <td><?= e($h['loai']) ?></td>
                <td><?= e($h['ho_ten']) ?></td>
                <td><?= e($h['so_dinh_danh']) ?></td>
                <td><?= $h['ngay'] ? date('d/m/Y', strtotime($h['ngay'])) : '—' ?></td>
                <td><span class="nhan nhan-<?= e($h['trang_thai']) ?>"><?= e($NHAN_CHUNG[$h['trang_thai']] ?? $h['trang_thai']) ?></span></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$ds_ho_so): ?>
              <tr><td colspan="8" class="trong">Không có hồ sơ phù hợp.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <footer class="chan-trang">
    <div class="chua"><span>© 2026 VNeID mô phỏng – Đồ án học phần</span></div>
  </footer>
</body>
</html>