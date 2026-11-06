<?php $hetHan = $g['ngay_het_han'] && strtotime($g['ngay_het_han']) < time(); ?>
<a class="quay-lai" href="/can_bo/duyet">← Quay lại danh sách</a>
<div class="luoi-2">
  <section class="khoi"><h2>Thông tin công dân</h2>
    <div class="cong-dan">
      <dl class="thong-tin">
        <dt>Họ và tên</dt><dd><?= e($g['ho_ten']) ?></dd>
        <dt>Số CCCD</dt><dd><?= e($g['so_dinh_danh']) ?></dd>
        <dt>Ngày sinh</dt><dd><?= ngay($g['ngay_sinh']) ?></dd>
        <dt>Giới tính</dt><dd><?= e($g['gioi_tinh'] ?? '—') ?></dd>
      </dl>
    </div>
    <dl class="thong-tin">
      <dt>Số điện thoại</dt><dd><?= e($g['so_dien_thoai']) ?></dd>
      <dt>Email</dt><dd><?= e($g['email']) ?></dd>
      <dt>Địa chỉ thường trú</dt><dd><?= e($g['noi_thuong_tru'] ?? '—') ?></dd>
      <dt>Mức định danh</dt><dd><span class="the-muc">Mức <?= e($g['muc_dinh_danh']) ?></span></dd>
    </dl>
  </section>
  <section class="khoi"><h2>Thông tin giấy tờ</h2>
    <dl class="thong-tin">
      <dt>Loại giấy tờ</dt><dd><?= e($g['ten_loai']) ?></dd>
      <dt>Số giấy tờ</dt><dd><?= e($g['so_giay_to']) ?></dd>
      <dt>Tên chủ sở hữu</dt><dd><?= e($g['ten_chu_so_huu']) ?></dd>
      <dt>Ngày cấp</dt><dd><?= ngay($g['ngay_cap']) ?></dd>
      <dt>Ngày hết hạn</dt><dd><?= $g['ngay_het_han'] ? ngay($g['ngay_het_han']) : 'Không thời hạn' ?></dd>
      <dt>Nơi cấp</dt><dd><?= e($g['noi_cap']) ?></dd>
      <dt>Trạng thái</dt><dd><?= nhanTrangThai($g['trang_thai']) ?></dd>
    </dl>
    <div class="thong-tin-rieng"><h3>Thông tin riêng – <?= e($g['ten_loai']) ?></h3>
      <dl class="thong-tin">
        <?php foreach ($truong as $khoa => $nhan): ?><dt><?= e($nhan) ?></dt><dd><?= e($giaTri[$khoa] ?? '—') ?></dd><?php endforeach; ?>
      </dl>
    </div>
    <?php if ($hetHan): ?><div class="thong-diep thong-diep-loi">Lưu ý: giấy tờ đã quá ngày hết hạn.</div><?php endif; ?>
  </section>
</div>
<div class="thanh-nut">
  <button class="nut nut-vien-cam" data-mo="hop-bo-sung">Yêu cầu bổ sung</button>
  <button class="nut nut-vien-do" data-mo="hop-tu-choi">Từ chối</button>
  <button class="nut nut-xanh" data-mo="hop-duyet">Phê duyệt</button>
</div>
<?php foreach ([
  'hop-duyet' => ['da_xac_minh', 'Xác nhận phê duyệt', 'Tôi xác nhận giấy tờ hợp lệ. Hệ thống sẽ cập nhật trạng thái và gửi thông báo đến tài khoản VNeID của công dân.', 'Phê duyệt', 'nut-xanh', false],
  'hop-tu-choi' => ['bi_tu_choi', 'Từ chối xác minh', 'Nhập lý do từ chối. Lý do sẽ được gửi đến công dân.', 'Từ chối', 'nut-do', true],
  'hop-bo-sung' => ['yeu_cau_bo_sung', 'Yêu cầu bổ sung thông tin', 'Nêu rõ thông tin công dân cần bổ sung hoặc chỉnh sửa.', 'Gửi yêu cầu', 'nut-cam', true],
] as $id => [$hd, $tieu, $mo, $nut, $lop, $canGhi]): ?>
<div class="hop-thoai" id="<?= $id ?>">
  <form method="post" action="/can_bo/xu_ly" class="hop">
    <h3><?= $tieu ?></h3>
    <div class="than-hop">
      <p class="mo-ta"><?= $mo ?></p>
      <p><b><?= e($g['ten_loai']) ?></b> số <b><?= e($g['so_giay_to']) ?></b> – <?= e($g['ho_ten']) ?></p>
      <input type="hidden" name="ma_csrf" value="<?= csrf() ?>">
      <input type="hidden" name="ma_giay_to" value="<?= $g['ma_giay_to'] ?>">
      <input type="hidden" name="hanh_dong" value="<?= $hd ?>">
      <?php if ($canGhi): ?><textarea name="ghi_chu" rows="4" required placeholder="Nhập nội dung..."></textarea><?php endif; ?>
    </div>
    <div class="chan-hop"><button type="button" class="nut" data-dong>Hủy</button><button class="nut <?= $lop ?>"><?= $nut ?></button></div>
  </form>
</div>
<?php endforeach; ?>
