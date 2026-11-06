<!-- FORM 1: ĐĂNG KÝ THƯỜNG TRÚ / TẠM TRÚ (MA_DICH_VU = 1) -->
<div id="form-cu-tru" class="service-flow">
    
    <!-- Thanh chọn loại thủ tục Thường trú / Tạm trú -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-2 d-flex gap-2">
            <button type="button" id="btn-tab-thuong-tru" class="btn btn-vneid-red w-50 py-2 fw-bold" onclick="selectLoaiCuTru('thuong_tru')">
                Đăng ký thường trú
            </button>
            <button type="button" id="btn-tab-tam-tru" class="btn btn-outline-secondary w-50 py-2 fw-bold" onclick="selectLoaiCuTru('tam_tru')">
                Đăng ký tạm trú
            </button>
        </div>
    </div>

    <!-- BƯỚC 0: MÀN HÌNH KHỞI TẠO -->
    <div id="step-cu-tru-0" class="step-panel active">
        <div class="form-card text-start">
            <h5 class="fw-bold text-dark" id="cu_tru_title">Đăng ký thường trú</h5>
            <p class="text-muted small" id="cu_tru_desc">
                Nơi thường trú là nơi công dân sinh sống ổn định, lâu dài và đã được đăng ký thường trú. Việc thực hiện đăng ký thường trú trên ứng dụng VNeID được hướng dẫn tại <em>Thông tư số 66/2023/TT-BCA</em>.
            </p>
            <button type="button" class="btn btn-vneid-red px-4 py-2 mt-1" style="background-color: #A31D1D;" onclick="openBottomSheet('sheetOverlayCuTru', 'sheetContentCuTru')">
                Tạo mới yêu cầu
            </button>
        </div>
        <div class="text-center py-5">
            <i class="bi bi-person-badge text-secondary fs-1 opacity-25"></i>
            <p class="text-muted small mt-2" id="cu_tru_empty_label">Chưa có yêu cầu đăng ký thường trú</p>
        </div>
    </div>

    <!-- BƯỚC 1: KIỂM TRA THÔNG TIN ĐĂNG KÝ -->
    <div id="step-cu-tru-1" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3" id="step1_header_title">Kiểm tra thông tin đăng ký thường trú</h6>
            
            <label class="form-label small fw-bold text-muted">Loại đăng ký <span id="lbl_sub_type_text">thường trú</span> *</label>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="loai_dang_ky_cu_tru" id="loai_ho_co" value="Đăng ký vào hộ đã có" checked onchange="toggleCuTruHomenType()">
                <label class="form-check-label small fw-semibold" for="loai_ho_co">Đăng ký vào hộ đã có</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="loai_dang_ky_cu_tru" id="loai_ho_moi" value="Đăng ký lập hộ mới" onchange="toggleCuTruHomenType()">
                <label class="form-check-label small fw-semibold" for="loai_ho_moi">Đăng ký lập hộ mới</label>
            </div>

            <label class="form-label small fw-bold text-muted">Hình thức xin xác nhận đồng ý của chủ hộ, chủ sở hữu chỗ ở hợp pháp *</label>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="hinh_thuc_xac_nhan" id="xn_vneid" value="Xin xác nhận qua VNeID" checked onchange="toggleNoteCT01()">
                <label class="form-check-label small" for="xn_vneid">Xin xác nhận qua VNeID</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="hinh_thuc_xac_nhan" id="xn_ct01" value="Xin xác nhận bằng văn bản giấy hoặc trên bản giấy (CT01)" onchange="toggleNoteCT01()">
                <label class="form-check-label small" for="xn_ct01">Xin xác nhận bằng văn bản giấy hoặc trên bản giấy của Tờ khai thay đổi thông tin cư trú (CT01)</label>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Trường hợp đăng ký *</label>
                <select id="truong_hop_dang_ky" class="form-select">
                    <option value="Đăng ký thường trú cho nhân khẩu">Đăng ký cho nhân khẩu</option>
                    <option value="Đăng ký cho diện khác">Đăng ký cho diện khác</option>
                </select>
            </div>

            <!-- Thông tin chủ hộ -->
            <div class="border-top pt-3 mt-3">
                <h6 class="fw-bold text-danger small">Thông tin chủ hộ</h6>
                <p class="text-muted fs-7 mb-2" id="note_ho_moi" style="display:none;">Trường hợp Đăng ký lập hộ mới thì người đề nghị cư trú (người thay đổi chính) sẽ là Chủ hộ.</p>
                <div class="mb-2">
                    <label class="form-label small text-muted">Họ tên *</label>
                    <input type="text" id="chu_ho_ho_ten" class="form-control" value="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Số định danh cá nhân *</label>
                    <input type="text" id="chu_ho_cccd" class="form-control" value="" required>
                </div>
            </div>

            <!-- Thông tin chủ sở hữu chỗ ở -->
            <div class="border-top pt-3 mt-2">
                <h6 class="fw-bold text-primary small">Thông tin chủ sở hữu chỗ ở hợp pháp</h6>
                <div class="mb-2">
                    <label class="form-label small text-muted">Họ tên *</label>
                    <input type="text" id="chu_so_huu_ho_ten" class="form-control" value="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Số định danh cá nhân *</label>
                    <input type="text" id="chu_so_huu_cccd" class="form-control" value="">
                </div>
            </div>

            <!-- Lưu ý văn bản giấy CT01 -->
            <div id="box_note_ct01" class="alert alert-warning p-2 small mt-2" style="display:none; font-size: 0.8rem;">
                <strong>Lưu ý:</strong> Để thực hiện xin xác nhận bằng văn bản giấy hoặc trên tờ khai CT01 thì người yêu cầu phải thực hiện đính kèm thành phần hồ sơ là bản chụp văn bản đồng ý của <strong>Chủ hộ, Chủ sở hữu chỗ ở hợp pháp, Cha/mẹ/người giám hộ</strong> HOẶC bản chụp tờ khai CT01 có đầy đủ chữ ký.
            </div>

            <button type="button" class="btn btn-vneid-red w-100 py-2 fs-6 mt-3" style="background-color: #A31D1D;" onclick="goToStepCuTru(2)">Kiểm tra</button>
        </div>
    </div>

    <!-- BƯỚC 2: THÔNG TIN CHI TIẾT CƯ TRÚ -->
    <div id="step-cu-tru-2" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3" id="step2_header_title">Thông tin đăng ký thường trú</h6>
            
            <div class="border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-secondary small">Thông tin người đề nghị</h6>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Họ tên người yêu cầu:</span>
                    <strong class="text-dark" id="cu_tru_req_name"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật', ENT_QUOTES, 'UTF-8') ?></strong>
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
                <input type="text" id="cu_tru_sdt" class="form-control" value="" required>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Mối quan hệ với chủ hộ *</label>
                <select id="cu_tru_quan_he_chu_ho" class="form-select">
                    <option value="Anh">Anh</option>
                    <option value="Cháu">Cháu</option>
                    <option value="Con">Con</option>
                    <option value="Em">Em</option>
                    <option value="Vợ">Vợ</option>
                    <option value="Chồng">Chồng</option>
                    <option value="Chủ hộ">Chủ hộ</option>
                </select>
            </div>

            <h6 class="fw-bold text-secondary small mt-4 border-top pt-3">Thông tin nơi đăng ký</h6>
            
            <div class="mb-3">
                <label class="form-label small text-muted">Nơi đề nghị *</label>
                <input type="text" id="cu_tru_noi_de_nghi" class="form-control" value="" oninput="capNhatCongAnChuyenDen(this.value)">
                <small class="text-danger fs-7 d-block mt-1">Hồ sơ sẽ được chuyển đến: <strong id="lbl_cong_an_chuyen_den">Chưa xác định</strong></small>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Chi tiết nơi cư trú *</label>
                <input type="text" id="cu_tru_chi_tiet_dia_chi" class="form-control" value="" placeholder="Số nhà, ngõ, đường...">
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">Nội dung đề nghị *</label>
                <textarea id="cu_tru_noi_dung_de_nghi" class="form-control" rows="2" placeholder="Nhập nội dung đề nghị..."></textarea>
            </div>

            <div id="box_thoihan_tamtru" class="mb-3" style="display:none;">
                <label class="form-label small text-muted">Thời hạn tạm trú đề nghị đến ngày *</label>
                <input type="date" id="cu_tru_thoi_han_tam_tru" class="form-control" value="">
            </div>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-pill" onclick="goToStepCuTru(1)">Quay lại</button>
            <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6 rounded-pill" style="background-color: #A31D1D;" onclick="goToStepCuTru(3)">Tiếp tục</button>
        </div>
    </div>

    <!-- BƯỚC 3: THÀNH VIÊN CÙNG THAY ĐỔI -->
    <div id="step-cu-tru-3" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-1">Thông tin thành viên cùng thay đổi</h6>
            <p class="text-muted fs-7 mb-3">Bỏ qua nếu không có thành viên nào khác trong hộ gia đình cùng thay đổi nơi cư trú</p>

            <div id="ds_thanh_vien_container">
                <!-- Danh sách thành viên -->
            </div>

            <button type="button" class="btn btn-light w-100 py-2 border rounded-3 fw-bold text-dark my-2" onclick="showFormThemThanhVien()">
                + Thêm thành viên
            </button>

            <!-- Form Thêm thành viên -->
            <div id="form_them_thanh_vien_box" class="border p-3 rounded-3 bg-light mt-3" style="display:none;">
                <h6 class="fw-bold text-danger mb-2">Thêm thành viên mới</h6>
                <div class="mb-2"><label class="small text-muted">Họ tên *</label><input type="text" id="tv_ho_ten" class="form-control"></div>
                <div class="mb-2"><label class="small text-muted">Ngày sinh *</label><input type="date" id="tv_ngay_sinh" class="form-control"></div>
                <div class="mb-2">
                    <label class="small text-muted">Giới tính *</label>
                    <select id="tv_gioi_tinh" class="form-select"><option value="Nam">Nam</option><option value="Nữ">Nữ</option></select>
                </div>
                <div class="mb-2"><label class="small text-muted">Số định danh cá nhân *</label><input type="text" id="tv_cccd" class="form-control"></div>
                <div class="mb-3"><label class="small text-muted">Mối quan hệ với chủ hộ *</label><input type="text" id="tv_quan_he" class="form-control" placeholder="Vd: Em, Cháu, Con..."></div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm w-50 rounded-pill" onclick="hideFormThemThanhVien()">Hủy</button>
                    <button type="button" class="btn btn-vneid-red btn-sm w-50 rounded-pill" style="background-color: #A31D1D;" onclick="saveThanhVien()">Lưu</button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="small text-muted fw-bold"><span id="lbl_so_luong_tv">0</span> Thành viên</span>
            <button type="button" class="btn btn-vneid-red px-4 py-2 fs-6 rounded-pill" style="background-color: #A31D1D;" onclick="goToStepCuTru(4)">Tiếp tục</button>
        </div>
    </div>

    <!-- BƯỚC 4: XÁC NHẬN THÔNG TIN HỒ SƠ & LỆ PHÍ -->
    <div id="step-cu-tru-4" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3">Xác nhận thông tin hồ sơ</h6>

            <div class="border p-3 rounded-3 bg-light mb-3 text-center">
                <i class="bi bi-file-earmark-arrow-up text-secondary fs-2"></i>
                <div class="fw-bold small mt-1">Thành phần hồ sơ đính kèm</div>
                <input type="file" id="cu_tru_tep_ho_so" class="form-control form-control-sm mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill mt-2">+ Thêm thành phần hồ sơ khác</button>
            </div>

            <div class="alert alert-light border small text-muted p-3 mb-3 fs-7">
                <strong class="text-danger">Lưu ý:</strong>
                <ol class="ps-3 mb-0 mt-1">
                    <li>Hệ thống sẽ gửi yêu cầu Xác nhận thông tin đến tài khoản VNeID của <strong>Chủ hộ</strong> và <strong>Chủ sở hữu chỗ ở hợp pháp</strong>.</li>
                    <li>Sau khi hoàn thành xác nhận, hồ sơ sẽ được chuyển đến cơ quan công an để xử lý.</li>
                    <li>Thời hạn Xác nhận tối đa là <strong>7 ngày</strong> kể từ khi nộp hồ sơ.</li>
                </ol>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Đối tượng nộp lệ phí *</label>
                <select id="cu_tru_doi_tuong_phi" class="form-select" onchange="capNhatLePhiCuTru()">
                    <option value="Khác">Khác</option>
                    <option value="Đối tượng miễn phí theo quy định">Đối tượng miễn phí theo quy định</option>
                </select>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 border">
                <div class="d-flex justify-content-between small text-muted">
                    <span>Lệ phí đăng ký cư trú:</span>
                    <strong id="lbl_le_phi_cu_tru">10.000 VND</strong>
                </div>
                <div class="d-flex justify-content-between fw-bold text-danger fs-6 mt-1">
                    <span>Tổng lệ phí:</span>
                    <span id="lbl_tong_le_phi_cu_tru">10.000 VND</span>
                </div>
            </div>

            <div class="form-check p-3 bg-light rounded-3 mb-4 border border-warning">
                <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_cu_tru_cam_doan" required checked>
                <label class="form-check-label small fw-semibold text-dark" for="chk_cu_tru_cam_doan">
                    Tôi xin cam đoan những lời khai trên là đúng sự thật và chịu trách nhiệm về lời khai của mình
                </label>
            </div>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-vneid-red btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="showPopupThanhToan()">
                    Gửi hồ sơ
                </button>
                <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepCuTru(0)">Hủy hồ sơ</button>
            </div>
        </div>
    </div>

    <!-- BƯỚC 5: POPUP XÁC NHẬN CHIA SẺ DỮ LIỆU NAPAS -->
    <div id="step-cu-tru-5" class="step-panel">
        <div class="form-card">
            <h6 class="fw-bold text-dark mb-3">Xác nhận chia sẻ thông tin</h6>
            
            <p class="text-danger small mb-3">
                Hệ thống định danh và xác thực điện tử sẽ chia sẻ, xử lý dữ liệu sau với <strong>Napas</strong>:
            </p>

            <div class="p-3 bg-light rounded-3 mb-3 border fs-7">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Họ tên:</span>
                    <strong class="text-dark" id="lbl_napas_name"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật', ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Số CCCD:</span>
                    <strong class="text-dark">Chưa cập nhật</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Nơi thường trú:</span>
                    <strong class="text-dark text-end">Chưa cập nhật</strong>
                </div>
            </div>

            <p class="small text-muted mb-3">
                <strong>Mục đích chia sẻ, xử lý dữ liệu:</strong><br>
                Các trường thông tin trên được chia sẻ nhằm thực hiện thanh toán lệ phí hồ sơ dịch vụ công.
            </p>

            <div class="form-check p-3 bg-light rounded-3 mb-4 border">
                <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_napas_share" checked>
                <label class="form-check-label small fw-semibold" for="chk_napas_share">
                    Tôi đã đọc Mục đích chia sẻ, xử lý dữ liệu, Quyền, nghĩa vụ của chủ thể dữ liệu và đồng ý chia sẻ, xử lý dữ liệu cá nhân
                </label>
            </div>

            <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                <input type="hidden" name="ma_dich_vu" value="1">
                <input type="hidden" name="noi_dung" id="final_noi_dung_cu_tru">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-vneid-red btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="prepareCuTruFinalData()">
                        Xác nhận chia sẻ dữ liệu
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepCuTru(4)">Hủy bỏ</button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- BOTTOM SHEET CHỌN ĐỐI TƯỢNG -->
<div id="sheetOverlayCuTru" class="custom-sheet-overlay" onclick="closeBottomSheet('sheetOverlayCuTru', 'sheetContentCuTru')"></div>
<div id="sheetContentCuTru" class="custom-sheet">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h6 class="fw-bold mb-0" id="lbl_sheet_cu_tru_title">Đăng ký thường trú cho</h6>
        <button type="button" class="btn-close" onclick="closeBottomSheet('sheetOverlayCuTru', 'sheetContentCuTru')"></button>
    </div>
    
    <div class="p-3 border-bottom cursor-pointer hover-bg-light" onclick="selectDoiTuongCuTru('Bản thân')">
        <div class="fw-bold text-dark">Bản thân</div>
        <div class="text-muted fs-7">Đề nghị đăng ký cư trú cho bản thân</div>
    </div>
    
    <div class="p-3 cursor-pointer hover-bg-light" onclick="selectDoiTuongCuTru('Khai hộ')">
        <div class="fw-bold text-dark">Khai hộ</div>
        <div class="text-muted fs-7">Đề nghị đăng ký cư trú cho người khác</div>
    </div>
</div>

<!-- MODAL POPUP THÔNG BÁO THANH TOÁN -->
<div class="modal fade" id="modalThanhToanCuTru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Thông báo</h5>
                <p class="text-muted small mb-4">Vui lòng thực hiện thanh toán phí hồ sơ để tiếp tục</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="acceptThanhToan()">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentCuTruType = 'thuong_tru';
    let currentCuTruTarget = 'Bản thân';
    let danhSachThanhVien = [];

    // HÀM CẬP NHẬT CƠ QUAN CÔNG AN TIẾP NHẬN (ĐỘC LẬP VỚI NỘI DUNG ĐỀ NGHỊ)
    function capNhatCongAnChuyenDen(val) {
        const valTrimmed = val.trim();
        const lbl = document.getElementById('lbl_cong_an_chuyen_den');
        if (!lbl) return;

        if (valTrimmed === '') {
            lbl.innerText = 'Chưa xác định';
        } else if (valTrimmed.toLowerCase().startsWith('công an')) {
            lbl.innerText = valTrimmed;
        } else {
            lbl.innerText = 'Công an ' + valTrimmed;
        }
    }

    // HÀM TÍNH TOÁN LỆ PHÍ ĐĂNG KÝ CƯ TRÚ
    function capNhatLePhiCuTru() {
        const doiTuong = document.getElementById('cu_tru_doi_tuong_phi')?.value;
        const lblLePhi = document.getElementById('lbl_le_phi_cu_tru');
        const lblTongLePhi = document.getElementById('lbl_tong_le_phi_cu_tru');

        if (doiTuong === 'Đối tượng miễn phí theo quy định') {
            if (lblLePhi) lblLePhi.innerText = '0 VND';
            if (lblTongLePhi) lblTongLePhi.innerText = '0 VND';
        } else {
            if (lblLePhi) lblLePhi.innerText = '10.000 VND';
            if (lblTongLePhi) lblTongLePhi.innerText = '10.000 VND';
        }
    }

    function selectLoaiCuTru(type) {
        currentCuTruType = type;
        const btnTT = document.getElementById('btn-tab-thuong-tru');
        const btnTamT = document.getElementById('btn-tab-tam-tru');
        const title = document.getElementById('cu_tru_title');
        const desc = document.getElementById('cu_tru_desc');
        const emptyLbl = document.getElementById('cu_tru_empty_label');
        const sheetTitle = document.getElementById('lbl_sheet_cu_tru_title');
        const subTypeText = document.getElementById('lbl_sub_type_text');
        const step1Title = document.getElementById('step1_header_title');
        const step2Title = document.getElementById('step2_header_title');
        const boxThoiHan = document.getElementById('box_thoihan_tamtru');

        if (type === 'thuong_tru') {
            btnTT.className = 'btn btn-vneid-red w-50 py-2 fw-bold';
            btnTamT.className = 'btn btn-outline-secondary w-50 py-2 fw-bold';
            title.innerText = 'Đăng ký thường trú';
            desc.innerHTML = 'Nơi thường trú là nơi công dân sinh sống ổn định, lâu dài và đã được đăng ký thường trú. Việc thực hiện đăng ký thường trú trên ứng dụng VNeID được hướng dẫn tại <em>Thông tư số 66/2023/TT-BCA</em>.';
            emptyLbl.innerText = 'Chưa có yêu cầu đăng ký thường trú';
            sheetTitle.innerText = 'Đăng ký thường trú cho';
            subTypeText.innerText = 'thường trú';
            step1Title.innerText = 'Kiểm tra thông tin đăng ký thường trú';
            step2Title.innerText = 'Thông tin đăng ký thường trú';
            boxThoiHan.style.display = 'none';
        } else {
            btnTamT.className = 'btn btn-vneid-red w-50 py-2 fw-bold';
            btnTT.className = 'btn btn-outline-secondary w-50 py-2 fw-bold';
            title.innerText = 'Đăng ký tạm trú';
            desc.innerHTML = 'Tạm trú là nơi công dân sinh sống từ 30 ngày trở lên ngoài nơi thường trú và đã được đăng ký tạm trú... <em>Thông tư số 66/2023/TT-BCA</em>.';
            emptyLbl.innerText = 'Chưa có yêu cầu đăng ký tạm trú';
            sheetTitle.innerText = 'Đăng ký tạm trú cho';
            subTypeText.innerText = 'tạm trú';
            step1Title.innerText = 'Kiểm tra thông tin đăng ký tạm trú';
            step2Title.innerText = 'Thông tin đăng ký tạm trú';
            boxThoiHan.style.display = 'block';
        }
    }

    function selectDoiTuongCuTru(target) {
        currentCuTruTarget = target;
        closeBottomSheet('sheetOverlayCuTru', 'sheetContentCuTru');
        goToStepCuTru(1);
    }

    function goToStepCuTru(step) {
        document.querySelectorAll('#form-cu-tru .step-panel').forEach(el => el.classList.remove('active'));
        document.getElementById('step-cu-tru-' + step).classList.add('active');
        window.scrollTo(0, 0);
    }

    function toggleCuTruHomenType() {
        const isHoMoi = document.getElementById('loai_ho_moi').checked;
        document.getElementById('note_ho_moi').style.display = isHoMoi ? 'block' : 'none';
    }

    function toggleNoteCT01() {
        const isCT01 = document.getElementById('xn_ct01').checked;
        document.getElementById('box_note_ct01').style.display = isCT01 ? 'block' : 'none';
    }

    /* Quản lý Thành viên cùng thay đổi */
    function showFormThemThanhVien() {
        document.getElementById('form_them_thanh_vien_box').style.display = 'block';
    }
    function hideFormThemThanhVien() {
        document.getElementById('form_them_thanh_vien_box').style.display = 'none';
    }
    function saveThanhVien() {
        const hoTen = document.getElementById('tv_ho_ten').value.trim();
        const ngaySinh = document.getElementById('tv_ngay_sinh').value;
        const gioiTinh = document.getElementById('tv_gioi_tinh').value;
        const cccd = document.getElementById('tv_cccd').value.trim();
        const quanHe = document.getElementById('tv_quan_he').value.trim();

        if (!hoTen || !cccd) {
            alert('Vui lòng nhập đầy đủ Họ tên và Số định danh!');
            return;
        }

        danhSachThanhVien.push({ hoTen, ngaySinh, gioiTinh, cccd, quanHe });
        renderDanhSachThanhVien();
        hideFormThemThanhVien();
        
        document.getElementById('tv_ho_ten').value = '';
        document.getElementById('tv_cccd').value = '';
        document.getElementById('tv_quan_he').value = '';
    }

    function renderDanhSachThanhVien() {
        const container = document.getElementById('ds_thanh_vien_container');
        document.getElementById('lbl_so_luong_tv').innerText = danhSachThanhVien.length;
        
        if (danhSachThanhVien.length === 0) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        danhSachThanhVien.forEach((item, index) => {
            html += `
                <div class="border rounded-3 p-3 mb-2 bg-white position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="xoaThanhVien(${index})"></button>
                    <div class="fw-bold text-dark small">${item.hoTen.toUpperCase()}</div>
                    <div class="fs-7 text-muted">Ngày sinh: ${item.ngaySinh} | Giới tính: ${item.gioiTinh}</div>
                    <div class="fs-7 text-muted">CCCD: ${item.cccd} | Quan hệ: ${item.quanHe}</div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function xoaThanhVien(index) {
        danhSachThanhVien.splice(index, 1);
        renderDanhSachThanhVien();
    }

    function showPopupThanhToan() {
        const modal = new bootstrap.Modal(document.getElementById('modalThanhToanCuTru'));
        modal.show();
    }

    function acceptThanhToan() {
        const modalEl = document.getElementById('modalThanhToanCuTru');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
        goToStepCuTru(5);
    }

    function prepareCuTruFinalData() {
        const loaiThuTuc = currentCuTruType === 'thuong_tru' ? 'Đăng ký thường trú' : 'Đăng ký tạm trú';
        const doiTuong = currentCuTruTarget;
        const loaiHo = document.querySelector('input[name="loai_dang_ky_cu_tru"]:checked')?.value;
        const chuHo = document.getElementById('chu_ho_ho_ten').value;
        const diaChi = document.getElementById('cu_tru_noi_de_nghi').value;
        const chiTiet = document.getElementById('cu_tru_chi_tiet_dia_chi').value;
        const noiDung = document.getElementById('cu_tru_noi_dung_de_nghi').value;
        const doiTuongPhi = document.getElementById('cu_tru_doi_tuong_phi')?.value || 'Khác';
        const tongLePhi = document.getElementById('lbl_tong_le_phi_cu_tru')?.innerText || '10.000 VND';

        let details = [];
        details.push(`Thủ tục: ${loaiThuTuc} (${doiTuong})`);
        details.push(`Loại hộ: ${loaiHo}`);
        details.push(`Chủ hộ: ${chuHo}`);
        details.push(`Địa chỉ: ${chiTiet}, ${diaChi}`);
        details.push(`Nội dung: ${noiDung}`);
        details.push(`Số thành viên cùng thay đổi: ${danhSachThanhVien.length}`);
        details.push(`Đối tượng phí: ${doiTuongPhi} | Tổng tiền: ${tongLePhi}`);

        document.getElementById('final_noi_dung_cu_tru').value = details.join(' | ');
    }
</script>