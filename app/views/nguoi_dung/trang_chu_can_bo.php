<?php
/**
 * PHÂN TRANG
 * - Nếu controller đã phân trang: truyền vào $trang_hien_tai, $tong_trang, $tong_ban_ghi (và $ds_ho_so chỉ chứa trang hiện tại).
 * - Nếu controller chưa phân trang: view tự cắt mảng $ds_ho_so bằng array_slice (dùng được ngay).
 */
$so_dong = isset($so_dong) ? max(1, (int)$so_dong) : 10;

if (!isset($tong_trang)) {
    $tong_ban_ghi   = count($ds_ho_so);
    $tong_trang     = max(1, (int)ceil($tong_ban_ghi / $so_dong));
    $trang_hien_tai = min(max(1, (int)($_GET['trang'] ?? 1)), $tong_trang);
    $ds_ho_so       = array_slice($ds_ho_so, ($trang_hien_tai - 1) * $so_dong, $so_dong);
} else {
    $tong_ban_ghi   = (int)($tong_ban_ghi ?? count($ds_ho_so));
    $tong_trang     = max(1, (int)ceil($tong_ban_ghi / $so_dong)); // tính lại, không tin giá trị controller
    $trang_hien_tai = min(max(1, (int)($trang_hien_tai ?? 1)), $tong_trang);
}

$tu_dong  = $tong_ban_ghi ? ($trang_hien_tai - 1) * $so_dong + 1 : 0;
$den_dong = $tong_ban_ghi ? $tu_dong + count($ds_ho_so) - 1 : 0;

// Tạo URL giữ nguyên bộ lọc khi chuyển trang
$url_trang = function (int $p) use ($trang_thai, $tu_khoa, $tu_ngay, $den_ngay) {
    $tham_so = array_filter([
        'trang_thai' => $trang_thai,
        'tu_khoa'    => $tu_khoa,
        'tu_ngay'    => $tu_ngay,
        'den_ngay'   => $den_ngay,
        'trang'      => $p > 1 ? $p : null,
    ], fn($v) => $v !== null && $v !== '');
    $qs = http_build_query($tham_so);
    return '/nguoi_dung/trang_chu_can_bo' . ($qs ? '?' . $qs : '');
};

// Danh sách số trang hiển thị (có dấu ...)
$cac_trang = [];
$cua_so = 2;
for ($i = 1; $i <= $tong_trang; $i++) {
    if ($i === 1 || $i === $tong_trang || abs($i - $trang_hien_tai) <= $cua_so) {
        $cac_trang[] = $i;
    } elseif (end($cac_trang) !== '...') {
        $cac_trang[] = '...';
    }
}
?>
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
  <style>
    .phan-trang-chua { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px; }
    .phan-trang-thongtin { font-size: .875rem; color: #6b7280; }
    .phan-trang { display: flex; flex-wrap: wrap; gap: 6px; list-style: none; margin: 0; padding: 0; }
    .phan-trang a, .phan-trang span {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px;
      border: 1px solid #e5e7eb; background: #fff; color: #374151;
      font-size: .875rem; text-decoration: none; transition: .15s;
    }
    .phan-trang a:hover { border-color: #d71f18; color: #d71f18; }
    .phan-trang .dang-chon { background: #d71f18; border-color: #d71f18; color: #fff; font-weight: 600; }
    .phan-trang .vo-hieu { opacity: .45; cursor: not-allowed; }
    .phan-trang .dau-ba-cham { border: 0; background: transparent; min-width: 20px; padding: 0; }
  </style>
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
              <tr><td colspan="7" class="trong">Không có hồ sơ phù hợp.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($tong_ban_ghi > 0): ?>
        <div class="phan-trang-chua">
          <div class="phan-trang-thongtin">
            Hiển thị <?= $tu_dong ?>–<?= $den_dong ?> / <?= $tong_ban_ghi ?> hồ sơ
          </div>

          <?php if (true): ?>
            <nav aria-label="Phân trang">
              <ul class="phan-trang">
                <li>
                  <?php if ($trang_hien_tai > 1): ?>
                    <a href="<?= e($url_trang($trang_hien_tai - 1)) ?>" aria-label="Trang trước">&laquo;</a>
                  <?php else: ?>
                    <span class="vo-hieu">&laquo;</span>
                  <?php endif; ?>
                </li>

                <?php foreach ($cac_trang as $p): ?>
                  <li>
                    <?php if ($p === '...'): ?>
                      <span class="dau-ba-cham">…</span>
                    <?php elseif ($p === $trang_hien_tai): ?>
                      <span class="dang-chon" aria-current="page"><?= $p ?></span>
                    <?php else: ?>
                      <a href="<?= e($url_trang($p)) ?>"><?= $p ?></a>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>

                <li>
                  <?php if ($trang_hien_tai < $tong_trang): ?>
                    <a href="<?= e($url_trang($trang_hien_tai + 1)) ?>" aria-label="Trang sau">&raquo;</a>
                  <?php else: ?>
                    <span class="vo-hieu">&raquo;</span>
                  <?php endif; ?>
                </li>
              </ul>
            </nav>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="chan-trang">
    <div class="chua"><span>© 2026 VNeID mô phỏng – Đồ án học phần</span></div>
  </footer>
</body>
</html>