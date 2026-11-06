<?php if (!$da_xac_nhan): ?>
<a class="lien-ket-quay-lai" href="/giay_to_vi/danh_sach">&larr; Quay lại ví giấy tờ</a>
<?php endif; ?>
<div class="khoi">
<?php if (!$da_xac_nhan): ?>
    <h2>Nhập mật khẩu</h2>
    <?php if ($thong_bao_loi !== ''): ?>
        <div class="phan-hoi phan-hoi-loi"><?= e($thong_bao_loi) ?></div>
    <?php endif; ?>
    <form method="post" action="/giay_to_vi/xem_the_can_cuoc" id="form-mk">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">
        <div class="otp-container">
            <div class="otp-tieu-de">
                <strong>Nhập mật khẩu</strong>
                <a href="/giay_to_vi/danh_sach" class="otp-dong">&times;</a>
            </div>
            <div class="mk-khoi">
                <input type="password" name="mat_khau" class="mk-o-nhap"
                       placeholder="Mật khẩu đăng nhập" autocomplete="current-password" required autofocus>
            </div>
            <div class="otp-thong-bao">Nhập mật khẩu đăng nhập để xem thông tin chi tiết</div>
            <button type="submit" class="otp-xac-nhan">Xác nhận</button>
        </div>
    </form>
<?php else: ?>
    <?php
    $v = function (string $k, string $mac_dinh = '---') use ($the_can_cuoc) {
        $gia_tri = trim((string) ($the_can_cuoc[$k] ?? ''));
        return $gia_tri !== '' ? $gia_tri : $mac_dinh;
    };
    $ds_trang_thai = ['con_hieu_luc' => 'Còn hiệu lực', 'het_han' => 'Hết hạn', 'bi_khoa' => 'Bị khóa'];
    $trang_thai = $ds_trang_thai[$the_can_cuoc['trang_thai']] ?? $the_can_cuoc['trang_thai'];
    $noi_o_hien_tai = $v('noi_o_hien_tai', $v('noi_thuong_tru'));
    ?>
    <div class="vn-man-hinh">
        <div class="vn-thanh-tieu-de">
            <a href="/giay_to_vi/danh_sach" class="vn-quay-lai" aria-label="Quay lại ví giấy tờ">&larr;</a>
            <span>Căn cước điện tử</span>
        </div>
        <div class="vn-the">
            <img class="vn-quoc-huy" src="/public/images/quochuy.png" alt="Quốc huy">
            <div class="vn-the-quoc-hieu">
                <strong>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong>
                <span>Độc lập - Tự do - Hạnh phúc</span>
            </div>
            <div class="vn-the-ten">CĂN CƯỚC ĐIỆN TỬ</div>
            <img class="vn-anh" src="/public/images/avatar.png" alt="Ảnh chân dung">
            <div class="vn-the-chu">
                <small>Số định danh cá nhân <i>/Personal Identification number:</i></small>
                <div class="vn-so"><?= e($the_can_cuoc['so_the']) ?></div>
                <small>Họ, chữ đệm và tên <i>/Full name:</i></small>
                <div class="vn-ho-ten"><?= e(mb_strtoupper($the_can_cuoc['ho_ten'], 'UTF-8')) ?></div>
                <div class="vn-the-hang">
                    <div>
                        <small>Ngày, tháng, năm sinh <i>/Date of birth:</i></small>
                        <div class="vn-ngay-sinh"><?= e(dinhDangNgay($the_can_cuoc['ngay_sinh'])) ?></div>
                    </div>
                    <div class="vn-gioi-tinh">
                        <small>Giới tính <i>/Sex:</i></small>
                        <b><?= e($the_can_cuoc['gioi_tinh']) ?></b>
                    </div>
                </div>
                <div class="vn-quoc-tich">
                    <small>Quốc tịch <i>/Nationality:</i></small>
                    <b><?= e($the_can_cuoc['quoc_tich']) ?></b>
                </div>
            </div>
        </div>
        <div class="vn-chi-tiet">
            <div class="vn-dong"><b>Nơi sinh:</b> <?= e($v('noi_sinh')) ?></div>
            <div class="vn-dong"><b>Nơi đăng ký khai sinh:</b> <?= e($v('noi_dang_ky_khai_sinh')) ?></div>
            <div class="vn-dong"><b>Quê quán:</b> <?= e($v('que_quan')) ?></div>

            <div class="vn-dong vn-dong-doi">
                <span><b>Dân tộc:</b> <?= e($v('dan_toc', 'Không')) ?></span>
                <span><b>Tôn giáo:</b> <?= e($v('ton_giao', 'Không')) ?></span>
            </div>
            <div class="vn-dong vn-dong-khoi"><b>Nơi thường trú:</b><span><?= e($v('noi_thuong_tru')) ?></span></div>
            <div class="vn-dong vn-dong-khoi"><b>Nơi tạm trú:</b><span><?= e($v('noi_tam_tru')) ?></span></div>
            <div class="vn-dong vn-dong-khoi"><b>Nơi ở hiện tại:</b><span><?= e($noi_o_hien_tai) ?></span></div>
            <div class="vn-dong vn-dong-ke"><b>Đặc điểm nhận dạng:</b> <?= e($v('dac_diem_nhan_dang')) ?></div>
            <div class="vn-dong vn-dong-doi">
                <span><b>Ngày cấp:</b> <?= e(dinhDangNgay($the_can_cuoc['ngay_cap'])) ?></span>
                <span><b>Hết hạn:</b> <?= e(dinhDangNgay($the_can_cuoc['ngay_het_han'])) ?></span>
            </div>
            <div class="vn-dong"><b>Nơi cấp:</b> <?= e($v('noi_cap')) ?></div>
            <div class="vn-dong"><b>Trạng thái:</b> <?= e($trang_thai) ?></div>
        </div>
    </div>
<?php endif; ?>
</div>