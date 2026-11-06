<?php
// Biến từ controller: $ds_thong_bao, $loai, $chi_chua_doc, $trang, $tong_trang
$la_quan_tri = (int) ($_SESSION['ma_vai_tro'] ?? 0) === VAI_TRO_QUAN_TRI;

// Tạo link giữ nguyên bộ lọc hiện tại
$taoLink = function (array $ghi_de) use ($loai, $chi_chua_doc, $trang) {
    $tham_so = array_merge([
        'loai'     => $loai,
        'chua_doc' => $chi_chua_doc ? 1 : '',
        'trang'    => $trang,
    ], $ghi_de);
    return '/thong_bao/danh_sach?' . http_build_query(array_filter($tham_so, function ($v) { return $v !== '' && $v !== null; }));
};
$ds_loc = ['' => 'Tất cả', 'he_thong' => 'Hệ thống', 'dich_vu_cong' => 'Dịch vụ công', 'bao_mat' => 'Bảo mật'];
?>
<div class="dau-thong-bao">
    <h1 class="dau-thong-bao-tieu-de">Thông báo</h1>
    <img class="dau-thong-bao-hinh" src="/public/images/chuong-thong-bao.svg" alt="">
</div>
<div class="tab-thong-bao">
    <?php foreach ($ds_loc as $gia_tri => $nhan): ?>
        <a href="<?= e($taoLink(['loai' => $gia_tri, 'trang' => 1])) ?>" class="<?= $loai === $gia_tri ? 'dang-chon' : '' ?>"><?= e($nhan) ?></a>
    <?php endforeach; ?>
</div>

<div class="thanh-cong-cu">
    <a class="lien-ket-loc" href="<?= e($taoLink(['chua_doc' => $chi_chua_doc ? '' : 1, 'trang' => 1])) ?>">
        <?= $chi_chua_doc ? 'Xem tất cả' : 'Chỉ xem chưa đọc' ?>
    </a>
    <div class="nhom-nut-phai">
        <?php if ($la_quan_tri): ?>
            <a class="lien-ket-loc" href="/thong_bao/gui">+ Gửi thông báo</a>
        <?php endif; ?>
        <form method="post" action="/thong_bao/danh_dau_tat_ca_da_doc" style="margin:0">
            <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
            <button type="submit" class="nut-chu-do">Đánh dấu tất cả đã đọc</button>
        </form>
    </div>
</div>

<?php if (empty($ds_thong_bao)): ?>
    <p class="rong-thong-bao">Không có thông báo nào.</p>
<?php endif; ?>

<?php $ngay_truoc = ''; ?>
<?php foreach ($ds_thong_bao as $thong_bao): ?>
    <?php
        $thoi_diem = strtotime($thong_bao['ngay_tao']);
        $ngay      = date('d-m-Y', $thoi_diem);
    ?>
    <?php if ($ngay !== $ngay_truoc): $ngay_truoc = $ngay; ?>
        <div class="nhom-ngay"><?= e($ngay) ?></div>
    <?php endif; ?>

    <div class="the-thong-bao <?= $thong_bao['da_doc'] ? '' : 'chua-doc' ?>">
        <?php if (!$thong_bao['da_doc']): ?><span class="cham-do"></span><?php endif; ?>

        <a class="tieu-de-the" href="/thong_bao/chi_tiet?ma_thong_bao=<?= (int) $thong_bao['ma_thong_bao'] ?>">
            <?= e($thong_bao['tieu_de']) ?>
        </a>
        <p class="noi-dung-the"><?= e(mb_strimwidth((string) $thong_bao['noi_dung'], 0, 160, '...', 'UTF-8')) ?></p>

        <div class="chan-the">
            <span class="gio">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                <?= e(date('H:i', $thoi_diem)) ?>
            </span>
            <span class="nhan-loai"><?= e(layNhanLoaiThongBao($thong_bao['loai'])) ?></span>

            <div class="hanh-dong-nho">
                <?php if (!$thong_bao['da_doc']): ?>
                    <form method="post" action="/thong_bao/danh_dau_da_doc">
                        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
                        <input type="hidden" name="ma_thong_bao" value="<?= (int) $thong_bao['ma_thong_bao'] ?>">
                        <button type="submit">Đã đọc</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="/thong_bao/xoa" onsubmit="return confirm('Xóa thông báo này?');">
                    <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
                    <input type="hidden" name="ma_thong_bao" value="<?= (int) $thong_bao['ma_thong_bao'] ?>">
                    <button type="submit">Xóa</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($tong_trang > 1): ?>
    <div class="phan-trang">
        <?php if ($trang > 1): ?><a href="<?= e($taoLink(['trang' => $trang - 1])) ?>">&larr; Trước</a><?php endif; ?>
        <span>Trang <?= (int) $trang ?>/<?= (int) $tong_trang ?></span>
        <?php if ($trang < $tong_trang): ?><a href="<?= e($taoLink(['trang' => $trang + 1])) ?>">Sau &rarr;</a><?php endif; ?>
    </div>
<?php endif; ?>