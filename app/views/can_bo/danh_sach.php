<?php $q = fn(array $them) => '/can_bo/' . $menu . '?' . http_build_query($them + $loc); ?>
<div class="dau-trang"><div><h1><?= e($tieuDe) ?></h1><p>Tìm thấy <?= $kq['tong'] ?> giấy tờ</p></div></div>
<section class="khoi">
  <form class="bo-loc" method="get" action="/can_bo/<?= $menu ?>">
    <input name="tu_khoa" value="<?= e($loc['tu_khoa']) ?>" placeholder="Tìm theo họ tên, số CCCD hoặc số giấy tờ">
    <select name="ma_loai"><option value="">Tất cả loại giấy tờ</option>
      <?php foreach ($loai as $l): ?><option value="<?= $l['ma_loai'] ?>" <?= $loc['ma_loai'] == $l['ma_loai'] ? 'selected' : '' ?>><?= e($l['ten_loai']) ?></option><?php endforeach; ?>
    </select>
    <select name="trang_thai"><option value="">Tất cả trạng thái</option>
      <?php foreach (TRANG_THAI as $k => [$t]): ?><option value="<?= $k ?>" <?= $loc['trang_thai'] === $k ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?>
    </select>
    <select name="sap_xep"><option value="moi">Mới nhất trước</option><option value="cu" <?= $loc['sap_xep'] === 'cu' ? 'selected' : '' ?>>Cũ nhất trước</option></select>
    <button class="nut nut-chinh">Lọc</button>
  </form>
  <div class="cuon-ngang">
  <table class="bang">
    <thead><tr><th>STT</th><th>Họ tên công dân</th><th>CCCD</th><th>Loại giấy tờ</th><th>Số giấy tờ</th><th>Ngày cấp</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
      <?php foreach ($kq['dong'] as $i => $g): ?>
        <tr>
          <td><?= ($p - 1) * 10 + $i + 1 ?></td><td><b><?= e($g['ho_ten']) ?></b></td><td><?= e($g['so_dinh_danh']) ?></td>
          <td><?= e($g['ten_loai']) ?></td><td><?= e($g['so_giay_to']) ?></td><td><?= ngayGio($g['ngay_cap']) ?></td>
          <td><?= nhanTrangThai($g['trang_thai']) ?></td>
          <td><a class="nut nut-vien" href="/can_bo/chi_tiet?id=<?= $g['ma_giay_to'] ?>">Xem chi tiết</a></td>
        </tr>
      <?php endforeach; if (!$kq['dong']): ?><tr><td colspan="8" class="trong">Không có giấy tờ phù hợp.</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
  <div class="phan-trang">
    <?php for ($i = 1; $i <= $kq['so_trang']; $i++): ?>
      <a href="<?= e($q(['p' => $i])) ?>" class="<?= $i === $p ? 'dang-chon' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
</section>
