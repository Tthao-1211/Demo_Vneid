<?php
// Biến từ controller: $the_can_cuoc, $so_the_che, $ds_giay_to, $da_xac_nhan_the
$so_cho_xac_minh = 0;
foreach ($ds_giay_to as $muc) {
    if ($muc['trang_thai'] === GiayToVi::TRANG_THAI_CHO_XAC_MINH) {
        $so_cho_xac_minh++;
    }
}
?>
<div class="vung-the">
    <?php if ($the_can_cuoc): ?>
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
                <div class="vn-so"><?= e($so_the_che) ?></div>

                <small>Họ, chữ đệm và tên <i>/Full name:</i></small>
                <div class="vn-ho-ten">**** **** ****</div>

                <div class="vn-the-hang">
                    <div>
                        <small>Ngày, tháng, năm sinh <i>/Date of birth:</i></small>
                        <div class="vn-ngay-sinh">**/**/****</div>
                    </div>
                    <div class="vn-gioi-tinh">
                        <small>Giới tính <i>/Sex:</i></small>
                        <b>***</b>
                    </div>
                </div>

                <div class="vn-quoc-tich">
                    <small>Quốc tịch <i>/Nationality:</i></small>
                    <b>**** ****</b>
                </div>
            </div>
        </div>
        <a class="nut-xem-chi-tiet" id="nut-mo-otp"
           data-da-xac-nhan="<?= !empty($da_xac_nhan_the) ? '1' : '0' ?>"
           href="/giay_to_vi/xem_the_can_cuoc">Xem thông tin chi tiết</a>
    <?php else: ?>
        <p class="khoi">Bạn chưa có thẻ căn cước trong hệ thống.</p>
    <?php endif; ?>
</div>

<?php if ($the_can_cuoc): ?>
    <div class="muc-tai-khoan">
        <div class="muc-tai-khoan-tieu-de">
            Tài khoản mức <?= (int) $the_can_cuoc['muc_dinh_danh'] ?>
            <span class="bieu-tuong-xac-minh">🛡️</span>
        </div>

        <div class="muc-tai-khoan-mo-ta">
            Tài khoản của bạn đã được định danh điện tử mức
            <?= (int) $the_can_cuoc['muc_dinh_danh'] ?>
        </div>
    </div>
<?php endif; ?>

<div class="luoi-giay-to">
    <?php foreach ($ds_giay_to as $giay_to): ?>
        <?php $nhan = layNhanTrangThaiGiayTo($giay_to['trang_thai']); ?>
        <a class="o-giay-to" href="/giay_to_vi/chi_tiet?ma_giay_to=<?= (int) $giay_to['ma_giay_to'] ?>">
            <div class="bieu-tuong"><?= layBieuTuongGiayTo($giay_to['ten_loai']) ?></div>
            <?= e($giay_to['ten_loai']) ?><br>
            <span class="nhan-trang-thai <?= e($nhan['lop']) ?>"><?= e($nhan['nhan']) ?></span>
        </a>
    <?php endforeach; ?>

    <a class="o-giay-to o-them-moi" href="/giay_to_vi/them_moi">
        <div class="bieu-tuong">+</div>
        Thêm giấy tờ
    </a>
</div>

<?php if ($the_can_cuoc && empty($da_xac_nhan_the)): ?>
<div class="otp-nen-mo" id="otp-popup" hidden>
    <form method="post" action="/giay_to_vi/xem_the_can_cuoc" id="form-mk" class="otp-hop">
        <input type="hidden" name="ma_csrf" value="<?= e(taoMaCsrf()) ?>">

        <div class="otp-container">
            <div class="otp-tieu-de">
                <strong>Nhập mật khẩu</strong>
                <button type="button" class="otp-dong" id="otp-dong">&times;</button>
            </div>

            <div class="mk-khoi">
                <input type="password" name="mat_khau" id="mat_khau" class="mk-o-nhap"
                       placeholder="Mật khẩu đăng nhập" autocomplete="current-password" required>
            </div>

            <div class="otp-thong-bao" id="otp-thong-bao">Nhập mật khẩu đăng nhập để xem thông tin chi tiết</div>

            <button type="submit" class="otp-xac-nhan" id="otp-submit" disabled>Xác nhận</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var nutMo = document.getElementById('nut-mo-otp');
    var popup = document.getElementById('otp-popup');
    if (!nutMo || !popup || nutMo.dataset.daXacNhan === '1') return;

    var form = document.getElementById('form-mk');
    var oMatKhau = document.getElementById('mat_khau');
    var nutSubmit = document.getElementById('otp-submit');
    var nutDong = document.getElementById('otp-dong');
    var oThongBao = document.getElementById('otp-thong-bao');
    var dangGui = false;
    var THONG_BAO_MAC_DINH = 'Nhập mật khẩu đăng nhập để xem thông tin chi tiết';

    function capNhat() {
        nutSubmit.disabled = oMatKhau.value === '' || dangGui;
    }

    function baoLoi(noiDung) {
        oThongBao.textContent = noiDung;
        oThongBao.classList.add('otp-loi');
    }

    function mo() {
        dangGui = false;
        oMatKhau.value = '';
        oThongBao.classList.remove('otp-loi');
        oThongBao.textContent = THONG_BAO_MAC_DINH;
        capNhat();
        popup.hidden = false;
        document.body.style.overflow = 'hidden';
        oMatKhau.focus();
    }

    function dong() {
        popup.hidden = true;
        oMatKhau.value = '';
        document.body.style.overflow = '';
    }

    nutMo.addEventListener('click', function (e) { e.preventDefault(); mo(); });
    nutDong.addEventListener('click', dong);
    popup.addEventListener('click', function (e) { if (e.target === popup) dong(); });
    oMatKhau.addEventListener('input', capNhat);
    document.addEventListener('keydown', function (e) {
        if (!popup.hidden && e.key === 'Escape') dong();
    });

    // Gửi mật khẩu để xác nhận
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (oMatKhau.value === '' || dangGui) return;
        dangGui = true;
        capNhat();

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin',
            body: new FormData(form)
        })
        .then(function (r) {
            return r.text().then(function (txt) {
                try { return JSON.parse(txt); }
                catch (err) { throw new Error('HTTP ' + r.status + ' - ' + txt.substring(0, 150)); }
            });
        })
        .then(function (d) {
            if (d.thanh_cong) {
                window.location.href = d.chuyen_huong;
                return;
            }
            dangGui = false;
            oMatKhau.value = '';
            capNhat();
            oMatKhau.focus();
            baoLoi(d.thong_bao || 'Có lỗi xảy ra, vui lòng thử lại.');
        })
        .catch(function (err) {
            dangGui = false;
            capNhat();
            baoLoi(err.message || 'Không kết nối được máy chủ.');
        });
    });
});
</script>
<?php endif; ?>