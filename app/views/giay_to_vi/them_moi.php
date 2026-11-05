<?php
// Biến từ controller: $ds_loai, $ds_loi, $du_lieu_cu
// Dữ liệu cho JS: ma_loai => [ {ten, nhan}, ... ]
$du_lieu_loai = [];
foreach ($ds_loai as $loai) {
    $ds_truong = [];
    foreach ($loai['cac_truong'] as $ten_truong) {
        $ds_truong[] = ['ten' => $ten_truong, 'nhan' => layNhanTruong($ten_truong)];
    }
    $du_lieu_loai[$loai['ma_loai']] = $ds_truong;
}
?>
<a class="lien-ket-quay-lai" href="/giay_to_vi/danh_sach">&larr; Quay lại ví giấy tờ</a>

<div class="khoi nen-xam">
    <div class="the-khoi">
        <h2 class="tieu-de-form">Thêm giấy tờ vào ví</h2>
        <p class="mo-ta-form">Chọn loại giấy tờ và nhập thông tin để thêm vào ví điện tử.</p>

        <?php if (!empty($ds_loi)): ?>
            <ul class="danh-sach-loi">
                <?php foreach ($ds_loi as $loi): ?><li><?= e($loi) ?></li><?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="/giay_to_vi/them_moi">
            <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

            <div class="nhom-nhap">
                <label for="ma_loai">Loại giấy tờ</label>
                <select name="ma_loai" id="ma_loai" required>
                    <option value="">-- Chọn loại --</option>
                    <?php foreach ($ds_loai as $loai): ?>
                        <option value="<?= (int) $loai['ma_loai'] ?>" <?= (int) $du_lieu_cu['ma_loai'] === (int) $loai['ma_loai'] ? 'selected' : '' ?>>
                            <?= e($loai['ten_loai']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="luoi-hai-cot">
                <div class="nhom-nhap">
                    <label for="so_giay_to">Số giấy tờ</label>
                    <input type="text" name="so_giay_to" id="so_giay_to" maxlength="30" value="<?= e($du_lieu_cu['so_giay_to']) ?>" required>
                </div>
                <div class="nhom-nhap">
                    <label for="ten_chu_so_huu">Tên chủ sở hữu</label>
                    <input type="text" name="ten_chu_so_huu" id="ten_chu_so_huu" maxlength="100" value="<?= e($du_lieu_cu['ten_chu_so_huu']) ?>" required>
                </div>
                <div class="nhom-nhap">
                    <label for="ngay_cap">Ngày cấp</label>
                    <input type="date" name="ngay_cap" id="ngay_cap" value="<?= e($du_lieu_cu['ngay_cap']) ?>">
                </div>
                <div class="nhom-nhap">
                    <label for="ngay_het_han">Ngày hết hạn <small>(trống = không thời hạn)</small></label>
                    <input type="date" name="ngay_het_han" id="ngay_het_han" value="<?= e($du_lieu_cu['ngay_het_han']) ?>">
                </div>
            </div>

            <div class="nhom-nhap">
                <label for="noi_cap">Nơi cấp</label>
                <input type="text" name="noi_cap" id="noi_cap" maxlength="150" value="<?= e($du_lieu_cu['noi_cap']) ?>">
            </div>

            <!-- Các ô riêng theo loại giấy tờ do giayToVi.js tạo -->
            <div id="cac-truong-rieng"
                 data-loai="<?= e(json_encode($du_lieu_loai, JSON_UNESCAPED_UNICODE)) ?>"
                 data-gia-tri="<?= e(json_encode((object) $du_lieu_cu['thong_tin'], JSON_UNESCAPED_UNICODE)) ?>"></div>

            <button type="submit" class="nut nut-chinh nut-rong">Thêm vào ví</button>
        </form>
    </div>
</div>
<script src="/public/js/giayToVi.js"></script>