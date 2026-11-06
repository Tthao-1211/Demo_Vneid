
<div class="dau-trang"><div><h1>Quản lý loại giấy tờ</h1><p>Định nghĩa các trường thông tin riêng (cac_truong) cho từng loại</p></div>
  <button class="nut nut-chinh" data-mo="hop-loai" data-loai='{"ma_loai":"","ten_loai":"","cac_truong":[]}'>+ Thêm loại</button></div>
<div class="luoi-3">
  <?php foreach ($ds as $l): $tr = CanBoLoaiGiayTo::giaiMaTruong($l['cac_truong']); ?>
    <section class="khoi">
      <small class="mo">Mã loại #<?= $l['ma_loai'] ?></small><h3 class="ten-loai"><?= e($l['ten_loai']) ?></h3>
      <div class="the-truong"><?php foreach ($tr as $k => $n): ?><span><?= e($n) ?> <code><?= e($k) ?></code></span><?php endforeach; ?></div>
      <small class="mo"><?= $l['so_giay_to'] ?> giấy tờ</small>
      <div class="hang-nut">
        <button class="nut nut-vien" data-mo="hop-loai" data-loai='<?= e(json_encode(['ma_loai' => $l['ma_loai'], 'ten_loai' => $l['ten_loai'], 'cac_truong' => array_map(fn($k, $n) => ['khoa' => $k, 'nhan' => $n], array_keys($tr), $tr)], JSON_UNESCAPED_UNICODE)) ?>'>Sửa</button>
        <form method="post" action="/can_bo/xoa_loai" onsubmit="return confirm('Xóa loại giấy tờ này?')">
          <input type="hidden" name="ma_csrf" value="<?= csrf() ?>"><input type="hidden" name="ma_loai" value="<?= $l['ma_loai'] ?>">
          <button class="nut nut-vien-do">Xóa</button>
        </form>
      </div>
    </section>
  <?php endforeach; ?>
</div>
<div class="hop-thoai" id="hop-loai">
  <form method="post" action="/can_bo/luu_loai" class="hop">
    <h3>Loại giấy tờ</h3>
    <div class="than-hop">
      <input type="hidden" name="ma_csrf" value="<?= csrf() ?>"><input type="hidden" name="ma_loai">
      <input name="ten_loai" placeholder="Tên loại giấy tờ" required>
      <h4>Các trường thông tin</h4>
      <div id="ds-truong"></div>
      <button type="button" class="nut-chu" id="them-truong">+ Thêm trường</button>
    </div>
    <div class="chan-hop"><button type="button" class="nut" data-dong>Hủy</button><button class="nut nut-chinh">Lưu</button></div>
  </form>
</div>
