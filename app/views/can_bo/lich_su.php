<div class="dau-trang"><div><h1>Lịch sử xử lý</h1><p>Các thông báo đã gửi đến công dân sau khi xử lý giấy tờ</p></div></div>
<section class="khoi">
  <div class="cuon-ngang">
    <table class="bang">
      <thead>
        <tr><th>Thời gian</th><th>Công dân</th><th>CCCD</th><th>Kết quả</th><th>Nội dung</th><th>Công dân đã đọc</th></tr>
      </thead>
      <tbody>
        <?php foreach ($ds as $t):
          $kq = str_contains($t['tieu_de'], 'đã được xác minh') ? 'da_xac_minh'
              : (str_contains($t['tieu_de'], 'từ chối') ? 'bi_tu_choi' : 'yeu_cau_bo_sung'); ?>
          <tr>
            <td><?= ngayGio($t['ngay_tao']) ?></td>
            <td><b><?= e($t['ho_ten']) ?></b></td>
            <td><?= e($t['so_dinh_danh']) ?></td>
            <td><?= nhanTrangThai($kq) ?></td>
            <td class="mo"><?= e($t['noi_dung']) ?></td>
            <td><span class="<?= $t['da_doc'] ? 'chu-xanh' : 'chu-cam' ?>"><?= $t['da_doc'] ? 'Đã đọc' : 'Chưa đọc' ?></span></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$ds): ?>
          <tr><td colspan="6" class="trong">Chưa có lịch sử.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<style>
.khoi { width: 100%; max-width: none; margin: 0; box-sizing: border-box; }
.cuon-ngang { overflow-x: auto; }
.bang { width: 100%; border-collapse: collapse; }
.bang th, .bang td { padding: 12px 14px; vertical-align: middle; text-align: left; }
.bang th { white-space: nowrap; }
.bang td { white-space: nowrap; }
.bang td:nth-child(2) { white-space: normal; min-width: 130px; }
.bang td.mo { white-space: normal; min-width: 280px; max-width: 460px; }
.nhan { white-space: nowrap; }
</style>