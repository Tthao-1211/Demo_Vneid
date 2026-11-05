<!-- FORM 2: MÔ PHỎNG ĐĂNG KÝ PHƯƠNG TIỆN (MA_DICH_VU = 2) -->
<div id="form-phuong-tien" class="service-flow" style="display: none;">
    <div class="card border-0 shadow-sm rounded-3 p-3 p-md-4">
        <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data" onsubmit="preparePhuongTienData()">
            <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
            <input type="hidden" name="ma_dich_vu" value="2">
            <input type="hidden" name="noi_dung" id="final_noi_dung_phuong_tien">

            <div class="mb-3">
                <label class="form-label fw-bold small">Loại thông tin *</label>
                <select class="form-select rounded-3" disabled><option selected>Đăng ký xe</option></select>
            </div>

            <div class="mb-3">
                <label for="loai_phuong_tien" class="form-label fw-bold small">Loại phương tiện *</label>
                <select id="loai_phuong_tien" class="form-select rounded-3" required>
                    <option value="Xe máy / Mô tô">Xe máy / Mô tô</option>
                    <option value="Ô tô">Ô tô</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="so_khung" class="form-label fw-bold small">Số khung *</label>
                <input type="text" id="so_khung" class="form-control rounded-3" placeholder="Nhập số khung" required>
            </div>

            <div class="mb-3">
                <label for="bien_so_xe" class="form-label fw-bold small">Biển số xe *</label>
                <input type="text" id="bien_so_xe" class="form-control rounded-3" placeholder="Nhập biển số xe" required>
            </div>

            <div class="mb-3">
                <label for="mau_bien" class="form-label fw-bold small">Màu biển *</label>
                <select id="mau_bien" class="form-select rounded-3" required>
                    <option value="Biển trắng, chữ đen">Biển trắng, chữ đen</option>
                    <option value="Biển vàng, chữ đen">Biển vàng, chữ đen</option>
                    <option value="Biển xanh, chữ trắng">Biển xanh, chữ trắng</option>
                </select>
            </div>

            <div class="form-check p-3 bg-light rounded-3 mb-4 border">
                <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_xac_nhan" required>
                <label class="form-check-label small fw-semibold" for="chk_xac_nhan">Tôi xác nhận các thông tin ở trên là đúng</label>
            </div>

            <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold" style="background-color: #A31D1D;">Gửi yêu cầu</button>
        </form>
    </div>
</div>

<script>
    function preparePhuongTienData() {
        const loaiXe = document.getElementById('loai_phuong_tien').value;
        const soKhung = document.getElementById('so_khung').value;
        const bienSo = document.getElementById('bien_so_xe').value;
        const mauBien = document.getElementById('mau_bien').value;
        document.getElementById('final_noi_dung_phuong_tien').value = `Đăng ký xe [${loaiXe}] - Biển số: ${bienSo} (${mauBien}) - Số khung: ${soKhung}`;
    }
</script>