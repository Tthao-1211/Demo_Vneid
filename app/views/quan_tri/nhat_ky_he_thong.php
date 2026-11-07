<?php
$mau = ['thanh_cong' => 'xanh', 'that_bai' => 'do', 'dang_xuat' => 'xam',
        'khoa_tu_dong' => 'do', 'khoa_tai_khoan' => 'do', 'mo_khoa_tai_khoan' => 'xanh',
        'duyet_ho_so' => 'xanh', 'tu_choi_ho_so' => 'do'];
$nhan_doi_tuong = [
    'nguoi_dung'    => 'Người dùng',
    'ho_so_dich_vu' => 'Hồ sơ',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nhật ký hệ thống – VNeID</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/quan-tri.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

  <main class="chua than-trang">
    <section class="khoi">
      <h2>Nhật ký hệ thống <small>(<?= (int)$tong_so ?> bản ghi)</small></h2>

      <form method="get" action="/quan_tri/nhat_ky_he_thong" class="thanh-loc">
        <select name="hanh_dong">
          <option value="">Tất cả hành động</option>
          <?php foreach (NhatKyHeThong::NHAN_HANH_DONG as $khoa => $nhan): ?>
            <option value="<?= e($khoa) ?>" <?= $hanh_dong === $khoa ? 'selected' : '' ?>><?= e($nhan) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="tu_khoa" value="<?= e($tu_khoa) ?>" placeholder="Người dùng, nội dung, IP">
        <input type="date" name="tu_ngay" value="<?= e($tu_ngay) ?>">
        <input type="date" name="den_ngay" value="<?= e($den_ngay) ?>">
        <button type="submit" class="nut-do">Lọc</button>
        <a href="/quan_tri/nhat_ky_he_thong" class="nut-vien">
          <i class="fa-solid fa-rotate-left"></i>
        </a>
      </form>

      <div class="bang-cuon">
        <table class="bang">
          <thead>
            <tr><th>Thời gian</th><th>Người dùng</th><th>Hành động</th><th>Nội dung</th><th>IP</th></tr>
          </thead>
          <tbody>
            <?php foreach ($ds_nhat_ky as $nhat_ky): ?>
            <tr>
              <td style="white-space:nowrap"><?= e($nhat_ky['thoi_gian']) ?></td>
              <td><?= e($nhat_ky['ho_ten'] ?? '(không xác định)') ?><br><small><?= e($nhat_ky['so_dinh_danh'] ?? '') ?></small></td>
              <td><span class="nhan nhan-<?= $mau[$nhat_ky['hanh_dong']] ?? 'xam' ?>">
                <?= e(NhatKyHeThong::NHAN_HANH_DONG[$nhat_ky['hanh_dong']] ?? $nhat_ky['hanh_dong']) ?></span></td>
              <td><?= e($nhat_ky['noi_dung'] ?? '') ?></td>
              <td><?= e($nhat_ky['dia_chi_ip']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$ds_nhat_ky): ?>
              <tr><td colspan="6" class="trong">Chưa có nhật ký.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($tong_trang > 1): ?>
      <div class="phan-trang">
        <?php for ($i = 1; $i <= $tong_trang; $i++): ?>
          <a class="<?= $i === $trang_hien_tai ? 'dang-chon' : '' ?>"
             href="?<?= e(($chuoi_loc !== '' ? $chuoi_loc . '&' : '') . 'trang=' . $i) ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>