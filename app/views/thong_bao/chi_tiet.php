<?php // Biến từ controller: $thong_bao ?>
<a class="lien-ket-quay-lai" href="/thong_bao/danh_sach">&larr; Quay lại thông báo</a>

<div class="khoi nen-xam">
    <article class="the-khoi chi-tiet-thong-bao">
        <div class="chi-tiet-dau">
            <div class="bieu-tuong-lon">🔔</div>
            <div>
                <h2 class="chi-tiet-tieu-de"><?= e($thong_bao['tieu_de']) ?></h2>
                <div class="chi-tiet-meta">
                    <span class="nhan-loai"><?= e(layNhanLoaiThongBao($thong_bao['loai'])) ?></span>
                    <span><?= e(date('d/m/Y H:i', strtotime($thong_bao['ngay_tao']))) ?></span>
                </div>
            </div>
        </div>

        <div class="noi-dung-thong-bao"><?= nl2br(e($thong_bao['noi_dung'])) ?></div>

        <form method="post" action="/thong_bao/xoa" onsubmit="return confirm('Xóa thông báo này?');">
            <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
            <input type="hidden" name="ma_thong_bao" value="<?= (int) $thong_bao['ma_thong_bao'] ?>">
            <button type="submit" class="nut nut-nguy-hiem nut-rong">Xóa thông báo</button>
        </form>
    </article>
</div>