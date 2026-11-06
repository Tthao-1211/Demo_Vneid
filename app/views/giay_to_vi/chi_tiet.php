<?php
// Biến từ controller: $giay_to (kèm ten_loai, cac_truong, thong_tin dạng mảng)
$nhan = layNhanTrangThaiGiayTo($giay_to['trang_thai']);
?>
<a class="lien-ket-quay-lai" href="/giay_to_vi/danh_sach">&larr; Quay lại ví giấy tờ</a>

<div class="khoi nen-xam">
    <div class="the-khoi">
        <div class="chi-tiet-dau">
            <div class="bieu-tuong-lon"><?= layBieuTuongGiayTo($giay_to['ten_loai']) ?></div>
            <div>
                <h2 class="chi-tiet-tieu-de"><?= e($giay_to['ten_loai']) ?></h2>
                <span class="nhan-trang-thai <?= e($nhan['lop']) ?>"><?= e($nhan['nhan']) ?></span>
            </div>
        </div>

        <dl class="ds-thong-tin">
            <div class="thong-tin-dong"><dt>Số giấy tờ</dt><dd><?= e($giay_to['so_giay_to']) ?></dd></div>
            <div class="thong-tin-dong"><dt>Chủ sở hữu</dt><dd><?= e($giay_to['ten_chu_so_huu']) ?></dd></div>
            <?php foreach ($giay_to['cac_truong'] as $ten_truong): ?>
                <div class="thong-tin-dong">
                    <dt><?= e(layNhanTruong($ten_truong)) ?></dt>
                    <dd><?= e($giay_to['thong_tin'][$ten_truong] ?? '') ?></dd>
                </div>
            <?php endforeach; ?>
            <div class="thong-tin-dong"><dt>Ngày cấp</dt><dd><?= e(dinhDangNgay($giay_to['ngay_cap'], 'Không có')) ?></dd></div>
            <div class="thong-tin-dong"><dt>Ngày hết hạn</dt><dd><?= e(dinhDangNgay($giay_to['ngay_het_han'], 'Không thời hạn')) ?></dd></div>
            <div class="thong-tin-dong"><dt>Nơi cấp</dt><dd><?= e($giay_to['noi_cap'] ?: 'Không có') ?></dd></div>
        </dl>

        <form class="vung-hanh-dong" method="post" action="/giay_to_vi/xoa"
              onsubmit="return confirm('Xóa giấy tờ này khỏi ví?');">
            <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
            <input type="hidden" name="ma_giay_to" value="<?= (int) $giay_to['ma_giay_to'] ?>">
            <button type="submit" class="nut nut-nguy-hiem nut-rong">Xóa khỏi ví</button>
        </form>
    </div>
</div>