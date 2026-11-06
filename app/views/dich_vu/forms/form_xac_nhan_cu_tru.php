<!-- FORM 3: MÔ PHỎNG XÁC NHẬN THÔNG TIN VỀ CƯ TRÚ (MA_DICH_VU = 3) -->
<div id="form-xac-nhan-cu-tru" class="service-flow" style="display: none;">
    
    <!-- BƯỚC 0: MÀN HÌNH KHỞI ĐẦU (Ảnh 1 - image_8eadd8.png) -->
    <div id="step-xn-0" class="step-panel active">
        <div class="form-card text-start">
            <h5 class="fw-bold text-dark">Xác nhận thông tin về cư trú</h5>
            <p class="text-muted small">
                Công dân thực hiện thủ tục này khi có nhu cầu xác nhận thông tin về cư trú. Công dân có thể gửi yêu cầu tới cơ quan đăng ký cư trú trong cả nước không phụ thuộc vào nơi cư trú. Xác nhận thông tin về cư trú có giá trị sử dụng 01 năm kể từ ngày cấp.
            </p>
            <button type="button" class="btn btn-vneid-red px-4 py-2 mt-1" style="background-color: #A31D1D;" onclick="goToStepXN(1)">
                Tạo mới yêu cầu
            </button>
        </div>
        <div class="text-center py-5">
            <i class="bi bi-person-vcard text-secondary fs-1 opacity-25"></i>
            <p class="text-muted small mt-2">Chưa có yêu cầu xác nhận thông tin về cư trú</p>
        </div>
    </div>

    <!-- BƯỚC 1: KÊ KHAI THÔNG TIN VÀ NỘI DUNG ĐỀ NGHỊ (Ảnh 2, 3, 4, 5, 6) -->
    <div id="step-xn-1" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3">Thông tin người đề nghị xác nhận thông tin về cư trú</h6>
            
            <div class="border-bottom pb-2 mb-3">
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Họ tên người yêu cầu:</span>
                    <strong class="text-dark"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật', ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Ngày sinh:</span>
                    <strong class="text-dark">Chưa cập nhật</strong>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Giới tính:</span>
                    <strong class="text-dark">Chưa cập nhật</strong>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Số định danh cá nhân:</span>
                    <strong class="text-dark">Chưa cập nhật</strong>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Số điện thoại *</label>
                <input type="text" id="xn_sdt" class="form-control" value="" placeholder="Nhập số điện thoại" required>
            </div>

            <!-- Checkbox Là chủ hộ (Ảnh 2 & 3) -->
            <div class="form-check p-3 bg-light rounded-3 mb-3 border border-warning">
                <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_is_chu_ho" checked onchange="toggleIsChuHo(this.checked)">
                <label class="form-check-label small fw-bold text-dark" for="chk_is_chu_ho">
                    Là chủ hộ
                </label>
            </div>

            <!-- Khối thông tin chủ hộ khi bỏ tích "Là chủ hộ" (Ảnh 3, 4) -->
            <div id="box_thong_tin_chu_ho_other" style="display: none;" class="border-top pt-3 mb-3">
                <div class="mb-3">
                    <label class="form-label small text-muted">Mối quan hệ với chủ hộ *</label>
                    <select id="xn_quan_he_chu_ho" class="form-select">
                        <option value="Con">Con</option>
                        <option value="Vợ">Vợ</option>
                        <option value="Chồng">Chồng</option>
                        <option value="Em">Em</option>
                        <option value="Cháu">Cháu</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Họ tên chủ hộ *</label>
                    <input type="text" id="xn_ten_chu_ho" class="form-control" placeholder="Nhập thông tin">
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Ngày sinh *</label>
                    <input type="date" id="xn_ngay_sinh_chu_ho" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Số định danh cá nhân chủ hộ *</label>
                    <input type="text" id="xn_cccd_chu_ho" class="form-control" placeholder="Nhập thông tin">
                </div>
            </div>

            <!-- Trường hợp xác nhận (Ảnh 3, 4, 5, 6) -->
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Trường hợp xác nhận *</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="truong_hop_xn" id="th_tren_dia_ban" value="Cấp cho nhân khẩu thường trú trên địa bàn quản lý" checked onchange="toggleTruongHopXN('tren_dia_ban')">
                    <label class="form-check-label small" for="th_tren_dia_ban">Cấp cho nhân khẩu thường trú trên địa bàn quản lý</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="truong_hop_xn" id="th_khac_dia_ban" value="Cấp cho nhân khẩu thường trú khác địa bàn quản lý" onchange="toggleTruongHopXN('khac_dia_ban')">
                    <label class="form-check-label small" for="th_khac_dia_ban">Cấp cho nhân khẩu thường trú khác địa bàn quản lý</label>
                </div>
            </div>

            <!-- Địa chỉ cơ quan thực hiện (Ảnh 5 & 6) -->
            <div class="mb-3">
                <label class="form-label small text-muted fw-bold">Địa chỉ cơ quan thực hiện *</label>
                <input type="text" id="xn_co_quan_thuc_hien" class="form-control" value="" placeholder="Nhập địa chỉ cơ quan thực hiện" oninput="capNhatCoQuanXN(this.value)" required>
                <small class="text-danger fs-7 d-block mt-1" id="lbl_note_chuyen_den_co_quan">
                    Hồ sơ đề nghị xác nhận thông tin về cư trú sẽ được chuyển đến: <strong id="lbl_xn_co_quan_hien_thi">Chưa xác định</strong>
                </small>
            </div>

            <!-- Nội dung đề nghị xác nhận (Ảnh 6) -->
            <div class="mb-3">
                <label class="form-label small text-muted fw-bold">Nội dung đề nghị xác nhận *</label>
                <textarea id="xn_noi_dung_de_nghi" class="form-control" rows="3" placeholder="Nhập thông tin" required></textarea>
            </div>

            <div class="alert alert-light border p-2 small text-muted mb-3 fs-7">
                <strong class="text-danger">Lưu ý:</strong> Nội dung đề nghị ghi rõ nhu cầu cần xác nhận để cơ quan công an tiến hành giải quyết hồ sơ. Ví dụ: Nhu cầu cần xác nhận các nơi cư trú trước đây hay thời gian sinh sống tại từng nơi cư trú hoặc hình thức đăng ký cư trú và các thông tin về cư trú khác,...
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2" onclick="goToStepXN(0)">Quay lại</button>
                <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6" style="background-color: #A31D1D;" onclick="goToStepXN(2)">Tiếp tục</button>
            </div>
        </div>
    </div>

    <!-- BƯỚC 2: HÌNH THỨC NHẬN KẾT QUẢ (Ảnh 7 - image_8e5362.png) -->
    <div id="step-xn-2" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-2">Hình thức nhận kết quả</h6>
            <p class="text-muted small mb-3">Khi có kết quả xử lý thành công thì bản điện tử kết quả sẽ được mặc định hiển thị trên ứng dụng VNeID.</p>

            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="xn_hinh_thuc_nhan" id="xn_nhan_co_quan" value="Nhận tại cơ quan công an tiếp nhận" checked onchange="toggleHinhThucNhanXN('co_quan')">
                <label class="form-check-label small fw-semibold" for="xn_nhan_co_quan">Nhận tại cơ quan công an tiếp nhận</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="xn_hinh_thuc_nhan" id="xn_nhan_buu_chinh" value="Nhận qua bưu chính" onchange="toggleHinhThucNhanXN('buu_chinh')">
                <label class="form-check-label small fw-semibold" for="xn_nhan_buu_chinh">Nhận qua bưu chính</label>
            </div>

            <!-- Khối nhận qua bưu chính -->
            <div id="box_buu_chinh_xn" style="display: none;" class="border-top pt-3 mt-2">
                <div class="mb-2">
                    <label class="form-label small text-muted">Đơn vị chuyển phát *</label>
                    <select id="xn_don_vi_cp" class="form-select">
                        <option value="Bưu điện VNPost">Bưu điện VNPost</option>
                        <option value="Viettel Post">Viettel Post</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Tên người nhận *</label>
                    <input type="text" id="xn_ten_nguoi_nhan" class="form-control" value="<?= htmlspecialchars($_SESSION['ho_ten'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập tên người nhận">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Địa chỉ nhận *</label>
                    <input type="text" id="xn_dia_chi_nhan" class="form-control" value="" placeholder="Nhập địa chỉ nhận">
                </div>
                <div class="mb-2">
                    <label class="form-label small text-muted">Chi tiết địa chỉ nhận *</label>
                    <input type="text" id="xn_chi_tiet_dia_chi_nhan" class="form-control" placeholder="Số nhà, đường...">
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Số điện thoại liên hệ *</label>
                    <input type="text" id="xn_sdt_lien_he" class="form-control" value="" placeholder="Nhập số điện thoại liên hệ">
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-outline-secondary w-50 py-2" onclick="goToStepXN(1)">Quay lại</button>
            <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6" style="background-color: #A31D1D;" onclick="goToStepXN(3)">Tiếp tục</button>
        </div>
    </div>

    <!-- BƯỚC 3: THÀNH VIÊN CÙNG XÁC NHẬN (Ảnh 8 & 9 - image_8e5342.png & image_8e4fa1.png) -->
    <div id="step-xn-3" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-1">Thông tin thành viên cùng xác nhận</h6>
            <p class="text-muted fs-7 mb-3">Bỏ qua nếu không có thành viên nào khác trong hộ cần xác nhận thông tin về cư trú.</p>

            <div id="ds_thanh_vien_xn_container">
                <!-- Nơi render danh sách thành viên -->
            </div>

            <div class="text-center my-3">
                <button type="button" class="btn btn-light rounded-pill px-4 py-2 border shadow-sm fw-bold text-dark" onclick="showFormThemThanhVienXN()">
                    <i class="bi bi-plus-lg me-1"></i> Thêm thông tin
                </button>
            </div>

            <!-- Form Thêm thành viên xác nhận -->
            <div id="form_them_tv_xn_box" class="border p-3 rounded-3 bg-light mt-3" style="display:none;">
                <h6 class="fw-bold text-danger mb-3">Thêm thành viên</h6>
                <div class="mb-2"><label class="small text-muted">Họ tên *</label><input type="text" id="tv_xn_ho_ten" class="form-control" placeholder="Nhập thông tin"></div>
                <div class="mb-2"><label class="small text-muted">Ngày sinh *</label><input type="date" id="tv_xn_ngay_sinh" class="form-control"></div>
                <div class="mb-2">
                    <label class="small text-muted">Giới tính *</label>
                    <select id="tv_xn_gioi_tinh" class="form-select"><option value="Nam">Nam</option><option value="Nữ">Nữ</option></select>
                </div>
                <div class="mb-2"><label class="small text-muted">Số định danh cá nhân *</label><input type="text" id="tv_xn_cccd" class="form-control" placeholder="Nhập thông tin"></div>
                <div class="mb-3">
                    <label class="small text-muted">Mối quan hệ với chủ hộ *</label>
                    <select id="tv_xn_quan_he" class="form-select">
                        <option value="Con">Con</option>
                        <option value="Vợ">Vợ</option>
                        <option value="Chồng">Chồng</option>
                        <option value="Em">Em</option>
                        <option value="Cháu">Cháu</option>
                    </select>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm w-50" onclick="hideFormThemThanhVienXN()">Hủy</button>
                    <button type="button" class="btn btn-vneid-red btn-sm w-50" style="background-color: #A31D1D;" onclick="saveThanhVienXN()">Lưu</button>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-outline-secondary w-50 py-2" onclick="goToStepXN(2)">Quay lại</button>
            <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6" style="background-color: #A31D1D;" onclick="goToStepXN(4)">Tiếp tục</button>
        </div>
    </div>

    <!-- BƯỚC 4: XÁC NHẬN THÔNG TIN HỒ SƠ & NỘP (Ảnh 10 - image_8e4fa1.png) -->
    <div id="step-xn-4" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3">Xác nhận thông tin hồ sơ</h6>

            <div class="p-3 bg-light rounded-3 mb-3 border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-dark">Thông tin thành viên cùng xác nhận</span>
                    <span class="badge bg-secondary rounded-pill">Danh sách thành viên (<span id="lbl_count_tv_xn">0</span>)</span>
                </div>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 border">
                <h6 class="fw-bold text-secondary small mb-2">Thông tin trả kết quả</h6>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Hình thức nhận kết quả:</span>
                    <strong class="text-dark" id="lbl_final_ht_nhan_xn">Nhận tại cơ quan công an tiếp nhận</strong>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Địa chỉ nơi nhận:</span>
                    <strong class="text-dark" id="lbl_final_dc_nhan_xn">Chưa xác định</strong>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Số lượng phiếu xác nhận:</span>
                    <strong class="text-dark">1 bản</strong>
                </div>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 border">
                <h6 class="fw-bold text-secondary small mb-2">Thành phần hồ sơ</h6>
                <div class="d-flex align-items-center justify-content-between p-2 border bg-white rounded-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>
                        <div>
                            <div class="small fw-bold text-dark">Tờ khai thay đổi thông tin về cư trú (CT01)</div>
                            <small class="text-muted fs-7">Giấy tờ điện tử: <strong>Chưa có tệp đính kèm</strong></small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" disabled>Chưa có tệp</button>
                </div>
            </div>

            <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                <input type="hidden" name="ma_dich_vu" value="3">
                <input type="hidden" name="noi_dung" id="final_noi_dung_xac_nhan_cu_tru">

                <div class="mb-3">
                    <label for="tep_xn_dinh_kem" class="form-label small text-muted">Đính kèm thêm giấy tờ khác (nếu có)</label>
                    <input type="file" name="tep_dinh_kem" id="tep_xn_dinh_kem" class="form-control">
                </div>

                <div class="form-check p-3 bg-light rounded-3 mb-4 border border-warning">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_xn_cam_doan" required checked>
                    <label class="form-check-label small fw-semibold text-dark" for="chk_xn_cam_doan">
                        Tôi xin cam đoan những lời khai trên là đúng sự thật và chịu trách nhiệm về lời khai của mình
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="prepareXacNhanCuTruData()">
                        Gửi yêu cầu
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepXN(0)">Hủy yêu cầu</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    let danhSachThanhVienXN = [];

    function goToStepXN(step) {
        document.querySelectorAll('#form-xac-nhan-cu-tru .step-panel').forEach(el => el.classList.remove('active'));
        document.getElementById('step-xn-' + step).classList.add('active');

        if (step === 4) {
            const isBuuChinh = document.getElementById('xn_nhan_buu_chinh')?.checked;
            const htText = isBuuChinh ? 'Nhận qua bưu chính' : 'Nhận tại cơ quan công an tiếp nhận';
            const coQuan = document.getElementById('xn_co_quan_thuc_hien')?.value.trim() || '';
            const chiTietDiaChi = document.getElementById('xn_chi_tiet_dia_chi_nhan')?.value.trim() || '';
            const diaChiNhan = document.getElementById('xn_dia_chi_nhan')?.value.trim() || '';
            const dcText = isBuuChinh
                ? ([chiTietDiaChi, diaChiNhan].filter(Boolean).join(', ') || 'Chưa cập nhật')
                : (coQuan ? (/^công an/i.test(coQuan) ? coQuan : `Công an ${coQuan}`) : 'Chưa xác định');

            document.getElementById('lbl_final_ht_nhan_xn').innerText = htText;
            document.getElementById('lbl_final_dc_nhan_xn').innerText = dcText;
            document.getElementById('lbl_count_tv_xn').innerText = danhSachThanhVienXN.length;
        }
        window.scrollTo(0, 0);
    }

    function toggleIsChuHo(isCheck) {
        const box = document.getElementById('box_thong_tin_chu_ho_other');
        if (box) box.style.display = isCheck ? 'none' : 'block';
    }

    function capNhatCoQuanXN(value) {
        const label = document.getElementById('lbl_xn_co_quan_hien_thi');
        const tenCoQuan = value.trim();
        if (!label) return;

        if (!tenCoQuan) {
            label.innerText = 'Chưa xác định';
        } else {
            label.innerText = /^công an/i.test(tenCoQuan) ? tenCoQuan : `Công an ${tenCoQuan}`;
        }
    }

    function toggleTruongHopXN(type) {
        const input = document.getElementById('xn_co_quan_thuc_hien');
        const noteEl = document.getElementById('lbl_note_chuyen_den_co_quan');

        if (type === 'tren_dia_ban') {
            input.placeholder = 'Nhập địa chỉ cơ quan thực hiện';
            if (noteEl) noteEl.style.display = 'block';
            capNhatCoQuanXN(input.value);
        } else {
            input.value = '';
            input.placeholder = 'Nhập thông tin cơ quan thực hiện';
            if (noteEl) noteEl.style.display = 'none';
            capNhatCoQuanXN('');
        }
    }

    function toggleHinhThucNhanXN(type) {
        const box = document.getElementById('box_buu_chinh_xn');
        if (box) box.style.display = (type === 'buu_chinh') ? 'block' : 'none';
    }

    /* Quản lý Thành viên cùng xác nhận */
    function showFormThemThanhVienXN() {
        document.getElementById('form_them_tv_xn_box').style.display = 'block';
    }
    function hideFormThemThanhVienXN() {
        document.getElementById('form_them_tv_xn_box').style.display = 'none';
    }
    function saveThanhVienXN() {
        const hoTen = document.getElementById('tv_xn_ho_ten').value.trim();
        const ngaySinh = document.getElementById('tv_xn_ngay_sinh').value;
        const gioiTinh = document.getElementById('tv_xn_gioi_tinh').value;
        const cccd = document.getElementById('tv_xn_cccd').value.trim();
        const quanHe = document.getElementById('tv_xn_quan_he').value;

        if (!hoTen || !cccd) {
            alert('Vui lòng nhập đầy đủ Họ tên và Số định danh!');
            return;
        }

        danhSachThanhVienXN.push({ hoTen, ngaySinh, gioiTinh, cccd, quanHe });
        renderDanhSachThanhVienXN();
        hideFormThemThanhVienXN();
        
        // Reset inputs
        document.getElementById('tv_xn_ho_ten').value = '';
        document.getElementById('tv_xn_cccd').value = '';
    }

    function renderDanhSachThanhVienXN() {
        const container = document.getElementById('ds_thanh_vien_xn_container');
        if (danhSachThanhVienXN.length === 0) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        danhSachThanhVienXN.forEach((item, index) => {
            html += `
                <div class="border rounded-3 p-3 mb-2 bg-white position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="xoaThanhVienXN(${index})"></button>
                    <div class="fw-bold text-dark small">${item.hoTen.toUpperCase()}</div>
                    <div class="fs-7 text-muted">Ngày sinh: ${item.ngaySinh} | Giới tính: ${item.gioiTinh}</div>
                    <div class="fs-7 text-muted">CCCD: ${item.cccd} | Quan hệ với chủ hộ: ${item.quanHe}</div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function xoaThanhVienXN(index) {
        danhSachThanhVienXN.splice(index, 1);
        renderDanhSachThanhVienXN();
    }

    function prepareXacNhanCuTruData() {
        const sdt = document.getElementById('xn_sdt')?.value.trim();
        const isChuHo = document.getElementById('chk_is_chu_ho')?.checked;
        const truongHop = document.querySelector('input[name="truong_hop_xn"]:checked')?.value || 'Cấp cho nhân khẩu thường trú trên địa bàn quản lý';
        const coQuan = document.getElementById('xn_co_quan_thuc_hien')?.value.trim();
        const noiDung = document.getElementById('xn_noi_dung_de_nghi')?.value.trim();
        const hinhThucNhan = document.querySelector('input[name="xn_hinh_thuc_nhan"]:checked')?.value || 'Nhận tại cơ quan công an tiếp nhận';

        let details = [];
        details.push(`Yêu cầu: Xác nhận thông tin về cư trú`);
        details.push(`Người yêu cầu: là chủ hộ (${isChuHo ? 'Có' : 'Không'})`);
        if (!isChuHo) {
            const tenChuHo = document.getElementById('xn_ten_chu_ho')?.value.trim();
            const quanHe = document.getElementById('xn_quan_he_chu_ho')?.value;
            details.push(`Chủ hộ: ${tenChuHo} (${quanHe})`);
        }
        details.push(`Trường hợp: ${truongHop}`);
        details.push(`Cơ quan tiếp nhận: ${coQuan}`);
        details.push(`Nội dung đề nghị: ${noiDung}`);
        details.push(`Hình thức nhận kết quả: ${hinhThucNhan}`);
        details.push(`Số lượng thành viên cùng xác nhận: ${danhSachThanhVienXN.length}`);

        document.getElementById('final_noi_dung_xac_nhan_cu_tru').value = details.join(' | ');
    }
</script>