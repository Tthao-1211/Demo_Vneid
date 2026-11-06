<div class="dau-trang"><div><h1>Lịch sử xử lý</h1><p>Các thông báo kết quả xác minh đã gửi đến công dân</p></div></div>
<section class="khoi"><div class="cuon-ngang">
  <table class="bang">
    <thead><tr><th>Thời gian</th><th>Công dân</th><th>CCCD</th><th>Kết quả</th><th>Nội dung</th><th>Công dân</th></tr></thead>
    <tbody>
      <?php foreach ($ds as $t):
        $kq = str_contains($t['tieu_de'], 'đã được xác minh') ? 'da_xac_minh' : (str_contains($t['tieu_de'], 'từ chối') ? 'bi_tu_choi' : 'yeu_cau_bo_sung'); ?>
        <tr><td><?= ngayGio($t['ngay_tao']) ?></td><td><?= e($t['ho_ten']) ?></td><td><?= e($t['so_dinh_danh']) ?></td>
          <td><?= nhanTrangThai($kq) ?></td><td class="mo"><?= e($t['noi_dung']) ?></td>
          <td><span class="<?= $t['da_doc'] ? 'chu-xanh' : 'chu-cam' ?>"><?= $t['da_doc'] ? 'Đã đọc' : 'Chưa đọc' ?></span></td></tr>
      <?php endforeach; if (!$ds): ?><tr><td colspan="6" class="trong">Chưa có lịch sử.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div></section>