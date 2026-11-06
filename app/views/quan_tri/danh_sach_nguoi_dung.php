<?php
$mau_tt  = ['hoat_dong' => 'xanh', 'bi_khoa' => 'do', 'cho_kich_hoat' => 'can_bo_sung'];
$nhan_tt = ['hoat_dong' => 'Hoạt động', 'bi_khoa' => 'Bị khóa', 'cho_kich_hoat' => 'Chờ kích hoạt'];
$quay_lai = $chuoi_loc . ($trang_hien_tai > 1 ? ($chuoi_loc !== '' ? '&' : '') . 'trang=' . $trang_hien_tai : '');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý người dùng – Quản trị</title>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/quan-tri.css">
</head>
<body>
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>
  <main class="chua than-trang">
    <section class="khoi">
      <h2>Quản lý người dùng <small>(<?= (int)$tong_so ?> kết quả)</small></h2>

      <?php if (!empty($_SESSION['thong_bao'])): ?>
        <div class="hop-thong-tin"><?= e($_SESSION['thong_bao']) ?></div>
        <?php unset($_SESSION['thong_bao']); ?>
      <?php endif; ?>

      <form method="get" action="/quan_tri/danh_sach_nguoi_dung" class="thanh-loc" style="grid-template-columns:1.6fr 1fr 1fr auto auto">
        <input type="text" name="tu_khoa" value="<?= e($tu_khoa) ?>" placeholder="Tên, số định danh, SĐT, email">
        <select name="trang_thai">
          <option value="">Tất cả trạng thái</option>
          <?php foreach ($nhan_tt as $khoa => $nhan_hien): ?>
            <option value="<?= $khoa ?>" <?= $trang_thai === $khoa ? 'selected' : '' ?>><?= $nhan_hien ?></option>
          <?php endforeach; ?>
        </select>
        <select name="ma_vai_tro">
          <option value="">Tất cả vai trò</option>
          <option value="1" <?= $ma_vai_tro === '1' ? 'selected' : '' ?>>Công dân</option>
          <option value="2" <?= $ma_vai_tro === '2' ? 'selected' : '' ?>>Cán bộ xử lý</option>
          <option value="3" <?= $ma_vai_tro === '3' ? 'selected' : '' ?>>Quản trị viên</option>
        </select>
        <button type="submit" class="nut-do">Tìm</button>
        <a href="/quan_tri/danh_sach_nguoi_dung" class="nut-vien">Xóa</a>
      </form>

      <div class="bang-cuon">
        <table class="bang">
          <thead><tr><th>Họ tên</th><th>Số định danh</th><th>SĐT / Email</th><th>Mức</th><th>Vai trò</th><th>Trạng thái</th><th>Sai</th><th>Thao tác</th></tr></thead>
          <tbody>
            <?php foreach ($ds_nguoi_dung as $nguoi_dung): ?>
              <?php $la_chinh_minh = (int)$nguoi_dung['ma_nguoi_dung'] === (int)$_SESSION['ma_nguoi_dung']; ?>
              <tr>
                <td><?= e($nguoi_dung['ho_ten']) ?><?= $la_chinh_minh ? ' <small>(Bạn)</small>' : '' ?></td>
                <td><?= e($nguoi_dung['so_dinh_danh']) ?></td>
                <td><?= e($nguoi_dung['so_dien_thoai']) ?><br><small><?= e($nguoi_dung['email'] ?? '') ?></small></td>
                <td><?= (int)$nguoi_dung['muc_dinh_danh'] ?></td>
                <td>
                  <?php if ($la_chinh_minh): ?>
                    <?= e($nguoi_dung['ten_vai_tro']) ?>
                  <?php else: ?>
                    <form method="post" action="/quan_tri/doi_vai_tro" style="display:flex;gap:6px">
                      <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
                      <input type="hidden" name="quay_lai" value="<?= e($quay_lai) ?>">
                      <input type="hidden" name="ma_nguoi_dung" value="<?= (int)$nguoi_dung['ma_nguoi_dung'] ?>">
                      <select name="ma_vai_tro" style="padding:6px;border:1px solid #e5e7eb;border-radius:8px">
                        <option value="1" <?= $nguoi_dung['ma_vai_tro'] == 1 ? 'selected' : '' ?>>Công dân</option>
                        <option value="2" <?= $nguoi_dung['ma_vai_tro'] == 2 ? 'selected' : '' ?>>Cán bộ</option>
                        <option value="3" <?= $nguoi_dung['ma_vai_tro'] == 3 ? 'selected' : '' ?>>Quản trị</option>
                      </select>
                      <button type="submit" class="nut-vien nut-nho">Lưu</button>
                    </form>
                  <?php endif; ?>
                </td>
                <td><span class="nhan nhan-<?= $mau_tt[$nguoi_dung['trang_thai']] ?? 'xam' ?>"><?= e($nhan_tt[$nguoi_dung['trang_thai']] ?? $nguoi_dung['trang_thai']) ?></span></td>
                <td><?= (int)$nguoi_dung['so_lan_sai'] ?></td>
                <td>
                  <?php if (!$la_chinh_minh): ?>
                    <form method="post" style="display:flex;gap:6px"
                          action="/quan_tri/<?= $nguoi_dung['trang_thai'] === 'hoat_dong' ? 'khoa' : 'mo_khoa' ?>"
                          onsubmit="return confirm('Xác nhận thao tác?');">
                      <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
                      <input type="hidden" name="quay_lai" value="<?= e($quay_lai) ?>">
                      <input type="hidden" name="ma_nguoi_dung" value="<?= (int)$nguoi_dung['ma_nguoi_dung'] ?>">
                      <?php if ($nguoi_dung['trang_thai'] === 'hoat_dong'): ?>
                        <input type="text" name="ly_do" placeholder="Lý do" style="width:110px;padding:6px;border:1px solid #e5e7eb;border-radius:8px">
                        <button type="submit" class="nut-do nut-nho">Khóa</button>
                      <?php else: ?>
                        <button type="submit" class="nut-vien nut-nho"><?= $nguoi_dung['trang_thai'] === 'bi_khoa' ? 'Mở khóa' : 'Kích hoạt' ?></button>
                      <?php endif; ?>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$ds_nguoi_dung): ?>
              <tr><td colspan="8" class="trong">Không có tài khoản phù hợp.</td></tr>
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