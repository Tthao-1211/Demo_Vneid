<!-- FORM 4: CẤP LẠI GIẤY TỜ (MA_DICH_VU = 4) -->
<div id="form-cap-lai-giay-to" class="service-flow">

    <!-- KHỐI CHỌN LOẠI GIẤY TỜ ĐỀ NGHỊ CẤP LẠI -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <label class="form-label fw-bold text-danger small mb-2">Chọn loại giấy tờ đề nghị cấp lại</label>
            <select id="select_loai_giay_to_sub" class="form-select border-primary" onchange="switchSubFormCapGiayTo(this.value)">
                <option value="lltp">1. Cấp phiếu lý lịch tư pháp</option>
                <option value="ho_chieu">2. Cấp hộ chiếu phổ thông trong nước</option>
            </select>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- SUB-FORM 1: CẤP PHIẾU LÝ LỊCH TƯ PHÁP                             -->
    <!-- ================================================================= -->
    <div id="sub-form-lltp">
        <!-- BƯỚC 0: MÀN HÌNH KHỞI TẠO -->
        <div id="step-lltp-0" class="step-panel active">
            <div class="form-card text-start">
                <h5 class="fw-bold text-dark">Cấp phiếu lý lịch tư pháp</h5>
                <p class="text-muted small">
                    Phiếu lý lịch tư pháp là phiếu do cơ quan quản lý cơ sở dữ liệu lý lịch tư pháp cấp có giá trị chứng minh cá nhân có hay không có án tích; bị cấm hay không bị cấm đảm nhiệm chức vụ, thành lập, quản lý doanh nghiệp, hợp tác xã trong trường hợp doanh nghiệp, hợp tác xã bị Tòa án tuyên bố phá sản.
                </p>
                <button type="button" class="btn btn-vneid-red px-4 py-2 mt-1" style="background-color: #A31D1D;" onclick="openBottomSheet('sheetOverlayLLTP', 'sheetContentLLTP')">
                    Tạo mới yêu cầu
                </button>
            </div>
            <div class="text-center py-5">
                <i class="bi bi-card-heading text-secondary fs-1 opacity-25"></i>
                <p class="text-muted small mt-2">Chưa có yêu cầu cấp phiếu lý lịch tư pháp</p>
            </div>
        </div>

        <!-- BƯỚC 1: THÔNG TIN THỦ TỤC HÀNH CHÍNH & TRẢ KẾT QUẢ -->
        <div id="step-lltp-1" class="step-panel">
            <div class="form-card">
                <h6 class="fw-bold text-dark mb-3">Thông tin thủ tục hành chính</h6>

                <!-- Cơ quan thực hiện -->
                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Cơ quan thực hiện *</label>
                    <input type="text" id="lltp_co_quan_thuc_hien" class="form-control" placeholder="Nhập cơ quan thực hiện">
                </div>

                <!-- Yêu cầu cấp phiếu + Icon Tooltip (?) -->
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-1 mb-1">
                        <label class="form-label small text-muted fw-bold mb-0">Yêu cầu cấp phiếu lý lịch tư pháp *</label>
                        <button type="button" class="btn btn-link p-0 text-secondary" onclick="showPopupLuatLLTP()">
                            <i class="bi bi-question-circle-fill"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lltp_loai_phieu" id="phieu_so_1" value="Số 1" checked>
                            <label class="form-check-label small fw-semibold" for="phieu_so_1">Số 1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lltp_loai_phieu" id="phieu_so_2" value="Số 2">
                            <label class="form-check-label small fw-semibold" for="phieu_so_2">Số 2</label>
                        </div>
                    </div>
                </div>

                <!-- Yêu cầu xác nhận phá sản -->
                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Yêu cầu xác nhận về nội dung cấm đảm nhiệm chức vụ, thành lập, quản lý doanh nghiệp, hợp tác xã theo quyết định tuyên bố phá sản *</label>
                    <div class="d-flex gap-4 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lltp_xac_nhan_pha_san" id="xn_cam_co" value="Có">
                            <label class="form-check-label small fw-semibold" for="xn_cam_co">Có</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lltp_xac_nhan_pha_san" id="xn_cam_khong" value="Không" checked>
                            <label class="form-check-label small fw-semibold" for="xn_cam_khong">Không</label>
                        </div>
                    </div>
                </div>

                <!-- Mục đích yêu cầu -->
                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Mục đích yêu cầu *</label>
                    <select id="lltp_muc_dich" class="form-select" required>
                        <option value="" selected disabled>Chọn mục đích yêu cầu</option>
                        <option value="Cư trú nước ngoài">Cư trú nước ngoài</option>
                        <option value="Xin việc làm trong nước">Xin việc làm trong nước</option>
                        <option value="Đi du học">Đi du học</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <!-- Thông tin về án tích -->
                <div class="mb-3">
                    <label class="form-label small text-muted">Thông tin về án tích, cấm đảm nhiệm chức vụ, thành lập, quản lý doanh nghiệp, hợp tác xã</label>
                    <textarea id="lltp_thong_tin_an_tich" class="form-control" rows="2" placeholder="Nhập thông tin nếu có..."></textarea>
                </div>

                <div class="border-top pt-3 mt-3">
                    <h6 class="fw-bold text-dark mb-2">Thông tin trả kết quả</h6>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Nhận thêm bản giấy phiếu lý lịch tư pháp</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lltp_nhan_ban_giay" id="ban_giay_co" value="Có" onchange="toggleBoxBanGiay(true)">
                                <label class="form-check-label small fw-semibold" for="ban_giay_co">Có</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lltp_nhan_ban_giay" id="ban_giay_khong" value="Không" checked onchange="toggleBoxBanGiay(false)">
                                <label class="form-check-label small fw-semibold" for="ban_giay_khong">Không</label>
                            </div>
                        </div>
                    </div>

                    <div id="box_so_ban_giay" class="mb-3" style="display:none;">
                        <label class="form-label small text-muted">Số bản giấy phiếu lý lịch tư pháp *</label>
                        <input type="number" id="lltp_so_ban_giay" class="form-control mb-2" value="1" min="1" max="20" onchange="capNhatLePhiLLTP()">
                        <div class="alert alert-light border small text-muted p-2 fs-7">
                            <em>Trường hợp người yêu cầu cấp đề nghị trên 02 phiếu lý lịch tư pháp trong một lần yêu cầu thì kể từ phiếu thứ 3 trở đi sẽ thu phí 5.000 đồng/phiếu...</em>
                        </div>

                        <label class="form-label small text-muted fw-bold mt-2">Hình thức nhận bản giấy phiếu lý lịch tư pháp</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="lltp_ht_nhan_giay" id="ht_truc_tiep" value="Trả trực tiếp tại bộ phận 1 cửa" checked>
                            <label class="form-check-label small" for="ht_truc_tiep">Trả trực tiếp tại bộ phận 1 cửa</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="lltp_ht_nhan_giay" id="ht_buu_chinh" value="Qua dịch vụ bưu chính công ích">
                            <label class="form-check-label small" for="ht_buu_chinh">Qua dịch vụ bưu chính công ích</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-50 py-2" onclick="goToStepLLTP(0)">Quay lại</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6" style="background-color: #A31D1D;" onclick="goToStepLLTP(2)">Tiếp tục</button>
                </div>
            </div>
        </div>

        <!-- BƯỚC 2: KÊ KHAI THÔNG TIN NGƯỜI YÊU CẦU VÀ NHÂN THÂN -->
        <div id="step-lltp-2" class="step-panel">
            <div class="form-card">
                <h6 class="fw-bold text-dark mb-3">Thông tin người yêu cầu & Nhân thân</h6>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Giới tính *</label>
                        <select id="lltp_gioi_tinh" class="form-select" required>
                            <option value="" selected disabled>Chọn giới tính</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Ngày sinh *</label>
                        <input type="text" id="lltp_ngay_sinh" class="form-control" placeholder="DD-MM-YYYY">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small text-muted">Nơi sinh *</label>
                    <input type="text" id="lltp_noi_sinh" class="form-control" placeholder="Nhập nơi sinh">
                </div>

                <div class="mb-2">
                    <label class="form-label small text-muted">Nơi thường trú *</label>
                    <input type="text" id="lltp_thuong_tru" class="form-control" placeholder="Nhập nơi thường trú">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">Nơi tạm trú</label>
                    <input type="text" id="lltp_tam_tru" class="form-control" placeholder="Nhập nơi tạm trú nếu có">
                </div>

                <h6 class="fw-bold text-secondary small border-top pt-2 mt-2">Thông tin nhân thân</h6>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Họ tên cha</label>
                        <input type="text" id="lltp_ho_ten_cha" class="form-control" placeholder="Nhập họ tên cha nếu có">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Số CCCD/CMND cha</label>
                        <input type="text" id="lltp_cccd_cha" class="form-control" placeholder="Nhập số CCCD/CMND cha nếu có">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Họ tên mẹ</label>
                        <input type="text" id="lltp_ho_ten_me" class="form-control" placeholder="Nhập họ tên mẹ nếu có">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Số CCCD/CMND mẹ</label>
                        <input type="text" id="lltp_cccd_me" class="form-control" placeholder="Nhập số CCCD/CMND mẹ nếu có">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Họ tên vợ/chồng</label>
                        <input type="text" id="lltp_ho_ten_vo_chong" class="form-control" placeholder="Nhập họ tên vợ/chồng nếu có">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Số CCCD/CMND vợ/chồng</label>
                        <input type="text" id="lltp_cccd_vo_chong" class="form-control" placeholder="Nhập số CCCD/CMND vợ/chồng nếu có">
                    </div>
                </div>

                <h6 class="fw-bold text-secondary small border-top pt-2 mt-2">Thông tin liên hệ</h6>

                <div class="mb-2">
                    <label class="form-label small text-muted">Email *</label>
                    <input type="email" id="lltp_email" class="form-control" placeholder="Nhập email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">Số điện thoại *</label>
                    <input type="text" id="lltp_sdt" class="form-control" placeholder="Nhập số điện thoại" required>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary w-50 py-2" onclick="goToStepLLTP(1)">Quay lại</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 fs-6" style="background-color: #A31D1D;" onclick="goToStepLLTP(3)">Tiếp tục</button>
                </div>
            </div>
        </div>

        <!-- BƯỚC 3: XÁC NHẬN THÔNG TIN HỒ SƠ & LỆ PHÍ -->
        <div id="step-lltp-3" class="step-panel">
            <div class="form-card">
                <h6 class="fw-bold text-dark mb-3">Xác nhận thông tin hồ sơ</h6>

                <!-- 1. Thông tin thủ tục hành chính -->
                <div class="border p-3 rounded-3 bg-light mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                        <strong class="small text-dark fw-bold">Thông tin thủ tục hành chính</strong>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Cơ quan thực hiện:</span>
                        <strong class="text-dark" id="lbl_review_co_quan">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Yêu cầu cấp phiếu lý lịch tư pháp:</span>
                        <strong class="text-dark" id="lbl_review_loai_phieu">Số 1</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Mục đích yêu cầu:</span>
                        <strong class="text-dark" id="lbl_review_muc_dich">Chưa chọn</strong>
                    </div>
                </div>

                <!-- 2. Thông tin trả kết quả -->
                <div class="border p-3 rounded-3 bg-light mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                        <strong class="small text-dark fw-bold">Thông tin trả kết quả</strong>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Nhận thêm bản giấy phiếu lý lịch tư pháp:</span>
                        <strong class="text-dark" id="lbl_review_nhan_giay">Không</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số bản giấy:</span>
                        <strong class="text-dark" id="lbl_review_so_ban">0</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Hình thức nhận:</span>
                        <strong class="text-dark" id="lbl_review_ht_nhan">Không nhận bản giấy</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Địa chỉ nhận:</span>
                        <strong class="text-dark text-end" id="lbl_review_dia_chi_nhan">Bản điện tử</strong>
                    </div>
                </div>

                <!-- 3. Thông tin người yêu cầu -->
                <div class="border p-3 rounded-3 bg-light mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                        <strong class="small text-dark fw-bold">Thông tin người yêu cầu</strong>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Họ tên người yêu cầu:</span>
                        <strong class="text-dark"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Giới tính:</span>
                        <strong class="text-dark" id="lbl_review_gioi_tinh">Nam</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Ngày sinh:</span>
                        <strong class="text-dark" id="lbl_review_ngay_sinh">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Nơi sinh:</span>
                        <strong class="text-dark text-end" id="lbl_review_noi_sinh">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Nơi thường trú:</span>
                        <strong class="text-dark text-end" id="lbl_review_thuong_tru">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Nơi tạm trú:</span>
                        <strong class="text-dark" id="lbl_review_tam_tru">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số định danh cá nhân:</span>
                        <strong class="text-dark"><?= htmlspecialchars($_SESSION['so_cccd'] ?? $_SESSION['cccd'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Họ tên cha:</span>
                        <strong class="text-dark" id="lbl_review_ho_ten_cha">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số CCCD/CMND cha:</span>
                        <strong class="text-dark" id="lbl_review_cccd_cha">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Họ tên mẹ:</span>
                        <strong class="text-dark" id="lbl_review_ho_ten_me">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số CCCD/CMND mẹ:</span>
                        <strong class="text-dark" id="lbl_review_cccd_me">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Họ tên vợ/chồng:</span>
                        <strong class="text-dark" id="lbl_review_ho_ten_vo_chong">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số CCCD/CMND vợ/chồng:</span>
                        <strong class="text-dark" id="lbl_review_cccd_vo_chong">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Số điện thoại:</span>
                        <strong class="text-dark" id="lbl_review_sdt">Chưa cập nhật</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Email:</span>
                        <strong class="text-dark" id="lbl_review_email">Chưa cập nhật</strong>
                    </div>
                </div>

                <!-- 4. Lệ phí & Thanh toán -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Đối tượng nộp phí *</label>
                    <select id="lltp_doi_tuong_phi" class="form-select" onchange="capNhatLePhiLLTP()">
                        <option value="Khac">Khác (200.000đ)</option>
                        <option value="MienPhi">Đối tượng miễn phí theo quy định (0đ)</option>
                        <option value="TreEm">Trẻ em theo quy định tại Luật bảo vệ, chăm sóc và giáo dục trẻ em (0đ)</option>
                        <option value="SinhVien">Sinh viên, người có công với cách mạng (100.000đ)</option>
                    </select>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="fw-bold text-dark mb-2">Phí</div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Phí cung cấp thông tin lý lịch tư pháp:</span>
                        <strong id="lbl_phi_lltp">200.000đ</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Phí cấp thêm bản giấy:</span>
                        <strong id="lbl_phi_ban_giay_them">0đ</strong>
                    </div>
                    <div class="d-flex justify-content-between fw-bold text-danger fs-6 mt-2 border-top pt-2">
                        <span>Tổng:</span>
                        <span id="lbl_tong_phi_lltp">200.000đ</span>
                    </div>
                </div>

                <div class="form-check p-3 bg-light rounded-3 mb-4 border border-warning">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_lltp_cam_doan" required checked>
                    <label class="form-check-label small fw-semibold text-dark" for="chk_lltp_cam_doan">
                        Tôi xin cam đoan những lời khai trên là đúng sự thật và chịu trách nhiệm về lời khai của mình
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-vneid-red btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="showPopupThanhToanLLTP()">
                        Gửi hồ sơ
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepLLTP(0)">Xóa hồ sơ</button>
                </div>
            </div>
        </div>

        <!-- BƯỚC 4: POPUP CHIA SẺ DỮ LIỆU NAPAS -->
        <div id="step-lltp-4" class="step-panel">
            <div class="form-card">
                <h6 class="fw-bold text-dark mb-3">Xác nhận thông tin chia sẻ</h6>
                <p class="text-danger small mb-3">Hệ thống định danh và xác thực điện tử sẽ chia sẻ, xử lý dữ liệu sau với <strong>Napas</strong>:</p>

                <div class="p-3 bg-light rounded-3 mb-3 border fs-7">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Họ tên:</span>
                        <strong class="text-dark"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Số CCCD:</span>
                        <strong class="text-dark"><?= htmlspecialchars($_SESSION['so_cccd'] ?? $_SESSION['cccd'] ?? 'Chưa cập nhật') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nơi thường trú:</span>
                        <strong class="text-dark text-end">Theo thông tin cư trú trong hồ sơ</strong>
                    </div>
                </div>

                <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                    <input type="hidden" name="ma_dich_vu" value="4">
                    <input type="hidden" name="noi_dung" id="final_noi_dung_lltp">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-vneid-red btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="prepareLLTPFinalData()">
                            Xác nhận
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepLLTP(3)">Hủy bỏ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ================================================================= -->
    <!-- SUB-FORM 2: CẤP HỘ CHIẾU PHỔ THÔNG TRONG NƯỚC (CHO PHÉP NHẬP LIỆU) -->
    <!-- ================================================================= -->
    <div id="sub-form-ho-chieu" style="display: none;">
        
        <!-- BƯỚC 0: MÀN HÌNH KHỞI TẠO -->
        <div id="step-hc-0" class="step-panel active">
            <div class="form-card text-start">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold text-dark mb-0">Cấp hộ chiếu phổ thông trong nước</h5>
                    <button type="button" class="btn btn-sm btn-light text-secondary rounded-pill border">
                        <i class="bi bi-clock-history me-1"></i>Lịch sử yêu cầu
                    </button>
                </div>
                <p class="text-muted small">
                    Công dân thực hiện thủ tục Cấp hộ chiếu phổ thông trong nước lần đầu, cấp lại trong các trường hợp hộ chiếu bị hỏng, hết hạn, sai lệch thông tin cá nhân, bị mất hoặc có nhu cầu bổ sung theo quy định tại <em>Điều 15 Luật Xuất cảnh, nhập cảnh của công dân Việt Nam</em>.
                </p>
                <button type="button" class="btn btn-vneid-red px-4 py-2 mt-1" style="background-color: #A31D1D;" onclick="openBottomSheet('sheetOverlayHC', 'sheetContentHC')">
                    Tạo mới yêu cầu
                </button>
            </div>
            <div class="text-center py-5">
                <i class="bi bi-journal-bookmark-fill text-secondary fs-1 opacity-25"></i>
                <p class="text-muted small mt-2">Chưa có yêu cầu</p>
            </div>
        </div>

        <!-- BƯỚC 1: THÔNG TIN NGƯỜI CẦN CẤP HỘ CHIẾU & Ô NHẬP THÔNG TIN CÁ NHÂN -->
        <div id="step-hc-1" class="step-panel">
            <div class="form-card">
                <div class="d-flex align-items-center mb-3">
                    <button type="button" class="btn btn-link p-0 text-dark me-2" onclick="goToStepHC(0)">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </button>
                    <h6 class="fw-bold text-dark mb-0">Cấp hộ chiếu phổ thông trong nước</h6>
                </div>

                <!-- TẢI ÂNH CHÂN DỤNG -->
                <div class="text-center mb-3 p-3 rounded-3 bg-light">
                    <div class="d-inline-block border rounded mb-2 overflow-hidden position-relative" style="width: 150px; height: 180px; background-color: #FFF9E6;">
                        <img id="img_preview_hc" src="" class="w-100 h-100 object-fit-cover" style="display:none;">
                        <div id="img_placeholder_hc" class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-person-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                    <div class="fw-semibold small text-dark mb-2">Ảnh chân dung</div>
                    <input type="file" id="file_anh_4x6" accept="image/*" class="d-none" onchange="previewAnhChanDung(this)">
                    <button type="button" class="btn btn-warning text-dark fw-bold px-4 py-1 btn-sm rounded-pill" style="background-color: #D4A017; border: none;" onclick="document.getElementById('file_anh_4x6').click()">
                        Tải ảnh
                    </button>
                </div>

                <div class="text-danger small mb-3 fs-7">
                    <strong>Lưu ý:</strong> Tải ảnh chân dung 4x6 nền trắng (Kiểu ảnh .jpg, kích thước &lt; 2MB) Xem hướng dẫn quy chuẩn ảnh 4x6 của thủ tục Cấp hộ chiếu phổ thông <a href="#" class="text-primary text-decoration-none">Tại đây</a>
                </div>

                <h6 class="fw-bold text-dark mb-3">Thông tin người cần cấp hộ chiếu trong nước</h6>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Họ *</label>
                        <input type="text" id="hc_ho" class="form-control" placeholder="Nhập họ" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Chữ đệm và tên khai sinh *</label>
                        <input type="text" id="hc_ten" class="form-control" placeholder="Nhập chữ đệm và tên khai sinh" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Giới tính *</label>
                        <select id="hc_gioi_tinh" class="form-select" required>
                            <option value="" selected disabled>Chọn giới tính</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Ngày, tháng, năm sinh *</label>
                        <input type="text" id="hc_ngay_sinh" class="form-control" placeholder="DD-MM-YYYY" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Số định danh cá nhân *</label>
                        <input type="text" id="hc_so_cccd" class="form-control" value="<?= htmlspecialchars($_SESSION['so_cccd'] ?? $_SESSION['cccd'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập số định danh cá nhân" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-bold">Ngày cấp *</label>
                        <input type="text" id="hc_ngay_cap" class="form-control" placeholder="DD-MM-YYYY" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Dân tộc *</label>
                    <input type="text" id="hc_dan_toc" class="form-control" placeholder="Nhập dân tộc">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nơi thường trú *</label>
                    <input type="text" id="hc_thuong_tru" class="form-control" placeholder="Nhập nơi thường trú">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nơi tạm trú</label>
                    <input type="text" id="hc_tam_tru" class="form-control" placeholder="Nhập nơi tạm trú nếu có">
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-pill" onclick="goToStepHC(0)">Quay lại</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="goToStepHC(2)">Tiếp tục</button>
                </div>
            </div>
        </div>

        <!-- BƯỚC 2: CẤP THỰC HIỆN & NỘI DUNG ĐỀ NGHỊ CẤP HỘ CHIẾU -->
        <div id="step-hc-2" class="step-panel">
            <div class="form-card">
                <div class="d-flex align-items-center mb-3">
                    <button type="button" class="btn btn-link p-0 text-dark me-2" onclick="goToStepHC(1)">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </button>
                    <h6 class="fw-bold text-dark mb-0">Cấp hộ chiếu phổ thông trong nước</h6>
                </div>

                <h6 class="fw-bold text-dark mb-2">Cấp thực hiện</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="hc_cap_thuc_hien" id="cap_tinh" value="Cấp tỉnh" checked onchange="updateCoQuanHC('tinh')">
                    <label class="form-check-label small fw-semibold" for="cap_tinh">Cấp tỉnh</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="hc_cap_thuc_hien" id="cap_trung_uong" value="Cấp trung ương" onchange="updateCoQuanHC('trung_uong')">
                    <label class="form-check-label small fw-semibold" for="cap_trung_uong">Cấp trung ương</label>
                </div>

                <div class="text-danger small mb-3 fs-7">
                    <strong>Lưu ý:</strong> Thời hạn xử lý hồ sơ là 08 ngày làm việc kể từ ngày hồ sơ được tiếp nhận.
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Cơ quan tiếp nhận *</label>
                    <input type="text" id="hc_co_quan_tiep_nhan" class="form-control" placeholder="Nhập cơ quan tiếp nhận" required oninput="toggleDiaChiNhanHC(document.getElementById('ht_buu_chinh_hc').checked)">
                </div>

                <h6 class="fw-bold text-dark border-top pt-3 mt-3 mb-3">Thông tin đề nghị cấp hộ chiếu phổ thông trong nước</h6>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nội dung đề nghị cấp *</label>
                    <select id="hc_noi_dung_de_nghi" class="form-select">
                        <option value="Cấp hộ chiếu lần đầu">Cấp hộ chiếu lần đầu</option>
                        <option value="Cấp lại do hết hạn">Cấp lại do hết hạn</option>
                        <option value="Cấp lại do bị hỏng/mất">Cấp lại do bị hỏng/mất</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">Chi tiết nội dung đề nghị cấp</label>
                    <input type="text" id="hc_chi_tiet_noi_dung" class="form-control" placeholder="Nhập chi tiết nội dung đề nghị nếu có">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Loại hộ chiếu đề nghị cấp *</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="hc_loai_chip" id="chip_co" value="Cấp hộ chiếu gắn chip điện tử" checked>
                        <label class="form-check-label small fw-semibold" for="chip_co">Cấp hộ chiếu gắn chip điện tử</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="hc_loai_chip" id="chip_khong" value="Cấp hộ chiếu không gắn chip điện tử">
                        <label class="form-check-label small fw-semibold" for="chip_khong">Cấp hộ chiếu không gắn chip điện tử</label>
                    </div>
                </div>

                <h6 class="fw-bold text-dark border-top pt-3 mt-3 mb-3">Hình thức nhận kết quả</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="hc_ht_nhan" id="ht_truc_tiep_hc" value="Nhận tại cơ quan công an tiếp nhận" checked onchange="toggleDiaChiNhanHC(false)">
                    <label class="form-check-label small fw-semibold" for="ht_truc_tiep_hc">Nhận tại cơ quan công an tiếp nhận</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="hc_ht_nhan" id="ht_buu_chinh_hc" value="Nhận qua dịch vụ bưu chính" onchange="toggleDiaChiNhanHC(true)">
                    <label class="form-check-label small fw-semibold" for="ht_buu_chinh_hc">Nhận qua dịch vụ bưu chính</label>
                </div>

                <div class="d-flex justify-content-between small text-muted p-2 bg-light rounded mb-3">
                    <span>Địa chỉ nơi nhận:</span>
                    <strong class="text-dark text-end" id="lbl_hc_dia_chi_noi_nhan">Chưa xác định</strong>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-pill" onclick="goToStepHC(1)">Quay lại</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="goToStepHC(3)">Tiếp tục</button>
                </div>
            </div>
        </div>

        <!-- BƯỚC 3: XÁC NHẬN THÔNG TIN HỒ SƠ & NỘP -->
        <div id="step-hc-3" class="step-panel">
            <div class="form-card">
                <div class="d-flex align-items-center mb-3">
                    <button type="button" class="btn btn-link p-0 text-dark me-2" onclick="goToStepHC(2)">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </button>
                    <h6 class="fw-bold text-dark mb-0">Xác nhận thông tin hồ sơ</h6>
                </div>

                <p class="text-muted small mb-3">
                    Thành phần hồ sơ kèm theo đối với người mất năng lực hành vi dân sự, người chưa đủ 14 tuổi; trường hợp bản chụp không có chứng thực thì xuất trình bản chính để kiểm tra đối chiếu.
                </p>

                <!-- KHỐI TẢI GIẤY TỜ ĐÍNH KÈM -->
                <div class="border p-3 rounded-3 bg-light mb-3">
                    <h6 class="fw-bold text-dark small mb-2">Hình thức giấy tờ</h6>
                    <select class="form-select mb-3">
                        <option value="Bản sao">Bản sao</option>
                        <option value="Bản chụp">Bản chụp</option>
                    </select>

                    <div class="text-center p-3 border rounded bg-white cursor-pointer" onclick="document.getElementById('file_dinh_kem_hc').click()">
                        <i class="bi bi-file-earmark-plus text-secondary fs-2"></i>
                        <div class="text-muted small mt-1">Chọn giấy tờ</div>
                        <input type="file" id="file_dinh_kem_hc" class="d-none">
                    </div>
                </div>

                <!-- LỆ PHÍ -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="fw-bold text-dark mb-1">Lệ phí</div>
                    <div class="text-muted small">Hệ thống sẽ thông báo yêu cầu nộp lệ phí đến công dân (nếu có)</div>
                </div>

                <!-- CAM ĐOAN -->
                <div class="form-check p-3 bg-light rounded-3 mb-4 border border-warning">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="chk_hc_cam_doan" required checked>
                    <label class="form-check-label small fw-semibold text-dark" for="chk_hc_cam_doan">
                        Tôi xin cam đoan những thông tin kê khai trên là đúng sự thật
                    </label>
                </div>

                <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                    <input type="hidden" name="ma_dich_vu" value="4">
                    <input type="hidden" name="noi_dung" id="final_noi_dung_ho_chieu">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-vneid-red btn-lg rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="prepareHoChieuFinalData()">
                            Gửi yêu cầu
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToStepHC(0)">Hủy yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>

<!-- BOTTOM SHEET LÝ LỊCH TƯ PHÁP -->
<div id="sheetOverlayLLTP" class="custom-sheet-overlay" onclick="closeBottomSheet('sheetOverlayLLTP', 'sheetContentLLTP')"></div>
<div id="sheetContentLLTP" class="custom-sheet">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h6 class="fw-bold mb-0">Cấp phiếu lý lịch tư pháp cho</h6>
        <button type="button" class="btn-close" onclick="closeBottomSheet('sheetOverlayLLTP', 'sheetContentLLTP')"></button>
    </div>
    <div class="p-3 border-bottom cursor-pointer hover-bg-light" onclick="selectDoiTuongLLTP('Bản thân')">
        <div class="fw-bold text-dark"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Chưa cập nhật') ?></div>
        <div class="text-muted fs-7"><?= htmlspecialchars($_SESSION['so_cccd'] ?? $_SESSION['cccd'] ?? 'Chưa cập nhật') ?></div>
    </div>
</div>

<!-- BOTTOM SHEET HỘ CHIẾU -->
<div id="sheetOverlayHC" class="custom-sheet-overlay" onclick="closeBottomSheet('sheetOverlayHC', 'sheetContentHC')"></div>
<div id="sheetContentHC" class="custom-sheet">
    <div class="d-flex justify-content-end pb-2 mb-1">
        <button type="button" class="btn-close" onclick="closeBottomSheet('sheetOverlayHC', 'sheetContentHC')"></button>
    </div>
    <div class="p-3 border-bottom cursor-pointer hover-bg-light" onclick="selectDoTuoiHC('NguoiDu14')">
        <div class="fw-bold text-dark">Cấp hộ chiếu phổ thông cho người đủ từ 14 tuổi</div>
    </div>
    <div class="p-3 cursor-pointer hover-bg-light" onclick="selectDoTuoiHC('TreEmDuoi14')">
        <div class="fw-bold text-dark">Cấp hộ chiếu phổ thông cho trẻ em dưới 14 tuổi</div>
    </div>
</div>

<!-- POPUP MODAL HƯỚNG DẪN LUẬT LLTP -->
<div class="modal fade" id="modalLuatLLTP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-4">
                <div class="text-muted fst-italic small mb-2">(Luật Lý lịch tư pháp số 28/2009/QH12)</div>
                <h6 class="fw-bold text-danger">Đối tượng cấp phiếu lý lịch tư pháp số 1:</h6>
                <ul class="small text-secondary ps-3 mb-3">
                    <li>Công dân Việt Nam, người nước ngoài đã hoặc đang cư trú tại Việt Nam;</li>
                    <li>Cơ quan nhà nước, tổ chức chính trị, tổ chức chính trị - xã hội để phục vụ công tác quản lý nhân sự, hoạt động đăng ký kinh doanh, thành lập, quản lý doanh nghiệp, hợp tác xã.</li>
                </ul>

                <h6 class="fw-bold text-danger">Đối tượng cấp phiếu lý lịch tư pháp số 2:</h6>
                <ul class="small text-secondary ps-3 mb-3">
                    <li>Cơ quan tiến hành tố tụng có quyền yêu cầu cấp Phiếu lý lịch tư pháp để phục vụ công tác điều tra, truy tố, xét xử;</li>
                    <li>Cấp theo yêu cầu của cá nhân để người đó biết được nội dung về lý lịch tư pháp của mình.</li>
                </ul>
                <div class="text-end">
                    <button type="button" class="btn btn-vneid-red rounded-pill px-4" style="background-color: #A31D1D;" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP THANH TOÁN LLTP -->
<div class="modal fade" id="modalThanhToanLLTP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Thông báo</h5>
                <p class="text-muted small mb-4">Vui lòng thực hiện thanh toán phí hồ sơ để tiếp tục</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-vneid-red w-50 py-2 rounded-pill fw-bold" style="background-color: #A31D1D;" onclick="acceptThanhToanLLTP()">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchSubFormCapGiayTo(type) {
        document.getElementById('sub-form-lltp').style.display = (type === 'lltp') ? 'block' : 'none';
        document.getElementById('sub-form-ho-chieu').style.display = (type === 'ho_chieu') ? 'block' : 'none';
    }

    function showPopupLuatLLTP() {
        new bootstrap.Modal(document.getElementById('modalLuatLLTP')).show();
    }

    function toggleBoxBanGiay(isCheck) {
        document.getElementById('box_so_ban_giay').style.display = isCheck ? 'block' : 'none';
        capNhatLePhiLLTP();
    }

    let currentLLTPTarget = 'Bản thân';
    function selectDoiTuongLLTP(target) {
        currentLLTPTarget = target;
        closeBottomSheet('sheetOverlayLLTP', 'sheetContentLLTP');
        goToStepLLTP(1);
    }

    function goToStepLLTP(step) {
        document.querySelectorAll('#sub-form-lltp .step-panel').forEach(el => el.classList.remove('active'));
        document.getElementById('step-lltp-' + step).classList.add('active');

        if (step === 3) {
            const getValue = (id) => document.getElementById(id)?.value?.trim() || '';
            const setReview = (id, value, fallback = 'Chưa cập nhật') => {
                const element = document.getElementById(id);
                if (element) element.textContent = value || fallback;
            };

            const loaiPhieu = document.querySelector('input[name="lltp_loai_phieu"]:checked')?.value || '';
            const coQuan = getValue('lltp_co_quan_thuc_hien');
            const mucDich = getValue('lltp_muc_dich');
            const isNhanGiay = document.getElementById('ban_giay_co')?.checked || false;
            const soBan = getValue('lltp_so_ban_giay') || '0';
            const htNhan = isNhanGiay
                ? (document.querySelector('input[name="lltp_ht_nhan_giay"]:checked')?.value || '')
                : 'Không nhận bản giấy';
            const diaChiNhan = !isNhanGiay
                ? 'Bản điện tử'
                : (htNhan === 'Qua dịch vụ bưu chính công ích'
                    ? 'Theo địa chỉ bưu chính đã đăng ký'
                    : (coQuan ? `Tại cơ quan: ${coQuan}` : 'Chưa cập nhật'));

            setReview('lbl_review_co_quan', coQuan);
            setReview('lbl_review_loai_phieu', loaiPhieu, 'Chưa chọn');
            setReview('lbl_review_muc_dich', mucDich, 'Chưa chọn');
            setReview('lbl_review_nhan_giay', isNhanGiay ? 'Có' : 'Không');
            setReview('lbl_review_so_ban', isNhanGiay ? soBan : '0');
            setReview('lbl_review_ht_nhan', htNhan);
            setReview('lbl_review_dia_chi_nhan', diaChiNhan);

            setReview('lbl_review_gioi_tinh', getValue('lltp_gioi_tinh'));
            setReview('lbl_review_ngay_sinh', getValue('lltp_ngay_sinh'));
            setReview('lbl_review_noi_sinh', getValue('lltp_noi_sinh'));
            setReview('lbl_review_thuong_tru', getValue('lltp_thuong_tru'));
            setReview('lbl_review_tam_tru', getValue('lltp_tam_tru'));
            setReview('lbl_review_ho_ten_cha', getValue('lltp_ho_ten_cha'));
            setReview('lbl_review_cccd_cha', getValue('lltp_cccd_cha'));
            setReview('lbl_review_ho_ten_me', getValue('lltp_ho_ten_me'));
            setReview('lbl_review_cccd_me', getValue('lltp_cccd_me'));
            setReview('lbl_review_ho_ten_vo_chong', getValue('lltp_ho_ten_vo_chong'));
            setReview('lbl_review_cccd_vo_chong', getValue('lltp_cccd_vo_chong'));
            setReview('lbl_review_email', getValue('lltp_email'));
            setReview('lbl_review_sdt', getValue('lltp_sdt'));
        }
        window.scrollTo(0, 0);
    }

    function capNhatLePhiLLTP() {
        const valDoiTuong = document.getElementById('lltp_doi_tuong_phi').value;
        const isNhanGiay = document.getElementById('ban_giay_co')?.checked;
        const soBanInput = parseInt(document.getElementById('lltp_so_ban_giay')?.value || '0', 10);
        
        let phiBanDau = 200000;
        if (valDoiTuong === 'MienPhi' || valDoiTuong === 'TreEm') phiBanDau = 0;
        else if (valDoiTuong === 'SinhVien') phiBanDau = 100000;

        let phiBanGiayThem = 0;
        if (isNhanGiay && soBanInput > 2) {
            phiBanGiayThem = (soBanInput - 2) * 5000;
        }

        const tongPhi = phiBanDau + phiBanGiayThem;

        document.getElementById('lbl_phi_lltp').innerText = phiBanDau.toLocaleString('vi-VN') + 'đ';
        document.getElementById('lbl_phi_ban_giay_them').innerText = phiBanGiayThem.toLocaleString('vi-VN') + 'đ';
        document.getElementById('lbl_tong_phi_lltp').innerText = tongPhi.toLocaleString('vi-VN') + 'đ';
    }

    function showPopupThanhToanLLTP() {
        new bootstrap.Modal(document.getElementById('modalThanhToanLLTP')).show();
    }

    function acceptThanhToanLLTP() {
        const m = bootstrap.Modal.getInstance(document.getElementById('modalThanhToanLLTP'));
        if (m) m.hide();
        goToStepLLTP(4);
    }

    function prepareLLTPFinalData() {
        const loaiPhieu = document.querySelector('input[name="lltp_loai_phieu"]:checked')?.value || 'Số 1';
        const coQuan = document.getElementById('lltp_co_quan_thuc_hien')?.value || '';
        const xnPhaSan = document.querySelector('input[name="lltp_xac_nhan_pha_san"]:checked')?.value || 'Không';
        const mucDich = document.getElementById('lltp_muc_dich')?.value || '';
        const anTich = document.getElementById('lltp_thong_tin_an_tich')?.value.trim() || '';

        const gioiTinh = document.getElementById('lltp_gioi_tinh')?.value || '';
        const ngaySinh = document.getElementById('lltp_ngay_sinh')?.value || '';
        const noiSinh = document.getElementById('lltp_noi_sinh')?.value || '';
        const thuongTru = document.getElementById('lltp_thuong_tru')?.value || '';
        const tamTru = document.getElementById('lltp_tam_tru')?.value || '';

        const hoTenCha = document.getElementById('lltp_ho_ten_cha')?.value || '';
        const cccdCha = document.getElementById('lltp_cccd_cha')?.value || '';
        const hoTenMe = document.getElementById('lltp_ho_ten_me')?.value || '';
        const cccdMe = document.getElementById('lltp_cccd_me')?.value || '';
        const hoTenVoChong = document.getElementById('lltp_ho_ten_vo_chong')?.value || '';
        const cccdVoChong = document.getElementById('lltp_cccd_vo_chong')?.value || '';

        const sdt = document.getElementById('lltp_sdt')?.value || '';
        const email = document.getElementById('lltp_email')?.value || '';

        const isNhanGiay = document.getElementById('ban_giay_co')?.checked;
        const soBan = isNhanGiay ? (document.getElementById('lltp_so_ban_giay')?.value || '0') : '0';
        const htNhan = isNhanGiay ? (document.querySelector('input[name="lltp_ht_nhan_giay"]:checked')?.value || '') : 'Không nhận bản giấy';
        const doiTuongSelect = document.getElementById('lltp_doi_tuong_phi');
        const doiTuongText = doiTuongSelect ? doiTuongSelect.options[doiTuongSelect.selectedIndex]?.text : '';
        const tongPhi = document.getElementById('lbl_tong_phi_lltp')?.innerText || '';

        let details = [];
        details.push(`Yêu cầu: Cấp phiếu lý lịch tư pháp (${currentLLTPTarget})`);
        details.push(`Loại phiếu: ${loaiPhieu} | Mục đích: ${mucDich}`);
        details.push(`Cơ quan tiếp nhận: ${coQuan}`);
        details.push(`Xác nhận cấm đảm nhiệm/phá sản: ${xnPhaSan}`);
        details.push(`Thông tin án tích: ${anTich}`);
        details.push(`Giới tính: ${gioiTinh} | Ngày sinh: ${ngaySinh}`);
        details.push(`Nơi sinh: ${noiSinh}`);
        details.push(`Thường trú: ${thuongTru} | Tạm trú: ${tamTru}`);
        details.push(`Cha: ${hoTenCha} (CCCD: ${cccdCha})`);
        details.push(`Mẹ: ${hoTenMe} (CCCD: ${cccdMe})`);
        details.push(`Vợ/Chồng: ${hoTenVoChong} (CCCD: ${cccdVoChong})`);
        details.push(`SĐT: ${sdt} | Email: ${email}`);
        details.push(`Bản giấy: ${isNhanGiay ? 'Có' : 'Không'} (${soBan} bản - ${htNhan})`);
        details.push(`Đối tượng phí: ${doiTuongText} | Tổng tiền: ${tongPhi}`);

        document.getElementById('final_noi_dung_lltp').value = details.join(' | ');
    }

    // LOGIC CẤP HỘ CHIẾU PHỔ THÔNG
    function openBottomSheet(overlayId, contentId) {
        document.getElementById(overlayId).classList.add('active');
        document.getElementById(contentId).classList.add('active');
    }
    function closeBottomSheet(overlayId, contentId) {
        document.getElementById(overlayId).classList.remove('active');
        document.getElementById(contentId).classList.remove('active');
    }

    let currentHCTarget = 'NguoiDu14';
    function selectDoTuoiHC(target) {
        currentHCTarget = target;
        closeBottomSheet('sheetOverlayHC', 'sheetContentHC');
        goToStepHC(1);
    }

    function goToStepHC(step) {
        document.querySelectorAll('#sub-form-ho-chieu .step-panel').forEach(el => el.classList.remove('active'));
        document.getElementById('step-hc-' + step).classList.add('active');
        window.scrollTo(0, 0);
    }

    function previewAnhChanDung(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img_preview_hc').src = e.target.result;
                document.getElementById('img_preview_hc').style.display = 'block';
                document.getElementById('img_placeholder_hc').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateCoQuanHC(level) {
        const cqInput = document.getElementById('hc_co_quan_tiep_nhan');
        if (cqInput) {
            if (level === 'tinh') {
                cqInput.value = '';
                cqInput.placeholder = 'Nhập cơ quan tiếp nhận cấp tỉnh';
            } else {
                cqInput.value = 'Cục Quản lý xuất nhập cảnh - Bộ Công an';
                cqInput.placeholder = 'Cơ quan tiếp nhận';
            }
        }
        toggleDiaChiNhanHC(document.getElementById('ht_buu_chinh_hc')?.checked || false);
    }

    function toggleDiaChiNhanHC(isBuuChinh) {
        const lbl = document.getElementById('lbl_hc_dia_chi_noi_nhan');
        if (!lbl) return;

        if (isBuuChinh) {
            lbl.innerText = 'Theo địa chỉ bưu chính do công dân cung cấp';
        } else {
            const coQuan = document.getElementById('hc_co_quan_tiep_nhan')?.value.trim();
            lbl.innerText = coQuan || 'Chưa cập nhật cơ quan tiếp nhận';
        }
    }

    function prepareHoChieuFinalData() {
        const doTuoiText = (currentHCTarget === 'NguoiDu14') ? 'Người đủ từ 14 tuổi' : 'Trẻ em dưới 14 tuổi';
        const ho = document.getElementById('hc_ho')?.value || '';
        const ten = document.getElementById('hc_ten')?.value || '';
        const hoTen = (ho + ' ' + ten).trim().toUpperCase();
        
        const gioiTinh = document.getElementById('hc_gioi_tinh')?.value || '';
        const ngaySinh = document.getElementById('hc_ngay_sinh')?.value || '';
        const soCccd = document.getElementById('hc_so_cccd')?.value || '';
        const ngayCap = document.getElementById('hc_ngay_cap')?.value || '';
        const danToc = document.getElementById('hc_dan_toc')?.value || '';
        const thuongTru = document.getElementById('hc_thuong_tru')?.value || '';
        const tamTru = document.getElementById('hc_tam_tru')?.value || '';

        const capThucHien = document.querySelector('input[name="hc_cap_thuc_hien"]:checked')?.value || 'Cấp tỉnh';
        const coQuan = document.getElementById('hc_co_quan_tiep_nhan')?.value || '';
        const noiDungDeNghi = document.getElementById('hc_noi_dung_de_nghi')?.value || 'Cấp hộ chiếu lần đầu';
        const chiTietNoiDung = document.getElementById('hc_chi_tiet_noi_dung')?.value.trim() || '';
        const loaiChip = document.querySelector('input[name="hc_loai_chip"]:checked')?.value || 'Cấp hộ chiếu gắn chip điện tử';
        const htNhan = document.querySelector('input[name="hc_ht_nhan"]:checked')?.value || 'Nhận tại cơ quan công an tiếp nhận';

        let details = [];
        details.push(`Yêu cầu: Cấp hộ chiếu phổ thông trong nước (${doTuoiText})`);
        details.push(`Họ và tên: ${hoTen}`);
        details.push(`Giới tính: ${gioiTinh} | Ngày sinh: ${ngaySinh}`);
        details.push(`Số CCCD: ${soCccd} | Ngày cấp: ${ngayCap}`);
        details.push(`Dân tộc: ${danToc}`);
        details.push(`Thường trú: ${thuongTru} | Tạm trú: ${tamTru}`);
        details.push(`Cấp thực hiện: ${capThucHien}`);
        details.push(`Cơ quan tiếp nhận: ${coQuan}`);
        details.push(`Nội dung đề nghị: ${noiDungDeNghi} (${chiTietNoiDung})`);
        details.push(`Loại hộ chiếu: ${loaiChip}`);
        details.push(`Hình thức nhận: ${htNhan}`);

        document.getElementById('final_noi_dung_ho_chieu').value = details.join(' | ');
    }
</script>