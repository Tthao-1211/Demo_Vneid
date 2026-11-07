<?php
/**
 * PHÂN TRANG
 * Luôn tính từ $ds_ho_so (danh sách đã lọc theo trạng thái) rồi cắt tại view.
 * Muốn đổi số dòng mỗi trang: gán $so_dong trong controller, hoặc sửa số 10 bên dưới.
 */
$so_dong = isset($so_dong) ? max(1, (int)$so_dong) : 10;

$ds_ho_so       = array_values($ds_ho_so);
$tong_ban_ghi   = count($ds_ho_so);
$tong_trang     = max(1, (int)ceil($tong_ban_ghi / $so_dong));
$trang_hien_tai = min(max(1, (int)($_GET['trang'] ?? 1)), $tong_trang);
$ds_ho_so       = array_slice($ds_ho_so, ($trang_hien_tai - 1) * $so_dong, $so_dong);

$tu_dong  = $tong_ban_ghi ? ($trang_hien_tai - 1) * $so_dong + 1 : 0;
$den_dong = $tong_ban_ghi ? $tu_dong + count($ds_ho_so) - 1 : 0;

// URL giữ nguyên bộ lọc khi chuyển trang
$url_trang = function (int $p) {
    $tham_so = $_GET;
    unset($tham_so['trang']);
    if ($p > 1) $tham_so['trang'] = $p;
    $qs = http_build_query($tham_so);
    return '/muc_dinh_danh/danh_sach_cho_duyet' . ($qs ? '?' . $qs : '');
};

// Danh sách số trang hiển thị (có dấu ...)
$cac_trang = [];
for ($i = 1; $i <= $tong_trang; $i++) {
    if ($i === 1 || $i === $tong_trang || abs($i - $trang_hien_tai) <= 2) {
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
  <title>Hồ sơ nâng mức cần xử lý – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
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
                <td><span class="nhan nhan-<?= e($ho_so['trang_thai']) ?>"><?= e(NangMucDinhDanh::NHAN_TRANG_THAI[$ho_so['trang_thai']] ?? $ho_so['trang_thai']) ?></span></td>
                <td><a class="nut-do nut-nho" href="/muc_dinh_danh/chi_tiet?ma_ho_so=<?= (int)$ho_so['ma_ho_so'] ?>">Xem</a></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$ds_ho_so): ?>
              <tr><td colspan="6" class="trong">Không có hồ sơ.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($tong_ban_ghi > 0): ?>
        <div class="phan-trang-chua">
          <div class="phan-trang-thongtin">
            Hiển thị <?= $tu_dong ?>–<?= $den_dong ?> / <?= $tong_ban_ghi ?> hồ sơ
          </div>

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
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>