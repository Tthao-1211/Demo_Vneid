<?php
// app/controllers/TheCanCuocController.php

require_once __DIR__ . '/../models/TheCanCuoc.php';

class TheCanCuocController {
    private $pdo;
    private $the_can_cuoc_model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->the_can_cuoc_model = new TheCanCuoc($pdo);
    }

    // 1. Chức năng: Xem thông tin chi tiết thẻ căn cước
    public function chiTiet() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vaiTro = layVaiTroDemo();

        try {
            if ($vaiTro === 'cong_dan') {
                $the_can_cuoc = $this->the_can_cuoc_model->layBanGhiMoiNhat();
            } elseif ($id > 0) {
                $the_can_cuoc = $this->the_can_cuoc_model->layTheoMa($id);
            } else {
                $the_can_cuoc = $this->the_can_cuoc_model->layBanGhiMoiNhat();
            }

            if (!$the_can_cuoc) {
                die("<div style='background-color: #7a0c0a; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: sans-serif; color: white;'>
                        <div style='background: white; color: #222; padding: 40px; border-radius: 12px; text-align: center; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);'>
                            <h3 style='color: #e3261f; margin-top: 0;'>⚠️ Hệ thống thông báo</h3>
                            <p style='color: #666; line-height: 1.5;'>Chưa có dữ liệu căn cước công dân trong hệ thống cơ sở dữ liệu quốc gia.</p>
                            <a href='/the_can_cuoc/them_moi' style='display: inline-block; margin-top: 15px; padding: 12px 24px; background: #e3261f; color: white; text-decoration: none; font-weight: bold; border-radius: 6px;'>Đi tới Khởi tạo hồ sơ</a>
                        </div>
                     </div>");
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể tải dữ liệu thẻ căn cước.");
        }

        require_once __DIR__ . '/../views/the_can_cuoc/chi_tiet.php';
    }

    // Alias tương thích ngược
    public function xemThongTin() {
        $this->chiTiet();
    }

    // 2. Chức năng: Thêm mới hồ sơ thẻ căn cước
    public function themMoi() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được thêm hồ sơ căn cước.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('quan_ly_ho_so', 'Cán bộ không có quyền quản lý hồ sơ.');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $so_cccd        = trim($_POST['so_cccd'] ?? '');
            $ho_ten         = trim($_POST['ho_ten'] ?? '');
            $ngay_sinh      = $_POST['ngay_sinh'] ?? '';
            $gioi_tinh      = $_POST['gioi_tinh'] ?? '';
            $que_quan       = trim($_POST['que_quan'] ?? '');
            $noi_thuong_tru = trim($_POST['noi_thuong_tru'] ?? '');
            $ngay_cap       = $_POST['ngay_cap'] ?? '';
            $noi_cap        = trim($_POST['noi_cap'] ?? '');
            $ngay_het_han   = $_POST['ngay_het_han'] ?? '';

            if (!preg_match('/^\d{12}$/', $so_cccd)) {
                echo "<script>alert('Số định danh cá nhân phải nhập đúng và đủ 12 chữ số.'); history.back();</script>";
                exit;
            }

            try {
                $du_lieu = [
                    'so_cccd'        => $so_cccd,
                    'ho_ten'         => $ho_ten,
                    'ngay_sinh'      => $ngay_sinh,
                    'gioi_tinh'      => $gioi_tinh,
                    'que_quan'       => $que_quan,
                    'noi_thuong_tru' => $noi_thuong_tru,
                    'ngay_cap'       => $ngay_cap,
                    'noi_cap'        => $noi_cap,
                    'ngay_het_han'   => $ngay_het_han
                ];

                $this->the_can_cuoc_model->themMoi($du_lieu);
                echo "<script>alert('Khởi tạo hồ sơ dữ liệu thành công!'); window.location.href='/the_can_cuoc/danh_sach';</script>";
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "<script>alert('Lỗi nghiêm trọng: Số định danh cá nhân này đã tồn tại trên hệ thống!'); history.back();</script>";
                } else {
                    http_response_code(500);
                    die("Không thể lưu hồ sơ thẻ căn cước. Vui lòng thử lại.");
                }
                exit;
            }
        }

        require_once __DIR__ . '/../views/the_can_cuoc/them_moi.php';
    }

    // Alias tương thích ngược
    public function kichHoat() {
        $this->themMoi();
    }

    // 3. Chức năng: Hệ thống tra cứu hồ sơ thẻ căn cước (Bảo mật: Không tự động hiện toàn bộ CSDL)
    public function danhSach() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được tra cứu danh sách hồ sơ.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('quan_ly_ho_so', 'Cán bộ không có quyền quản lý hồ sơ.');
        }
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $ds_the_can_cuoc = [];
        $search_error = '';
        $search_empty = false;

        try {
            if ($search !== '') {
                // Kiểm tra nếu người dùng nhập toàn chữ số -> Tra cứu theo Số định danh (CCCD)
                if (ctype_digit($search)) {
                    $so_ky_tu = strlen($search);
                    if ($so_ky_tu !== 12) {
                        $search_error = "Số định danh cá nhân (CCCD) phải bao gồm đúng 12 chữ số. Bạn vừa nhập $so_ky_tu chữ số ('$search'). Vui lòng nhập đủ 12 số để tra cứu.";
                        $ds_the_can_cuoc = [];
                    } else {
                        // Nhập đúng 12 số: tra cứu chính xác số thẻ CCCD
                        $ds_the_can_cuoc = $this->the_can_cuoc_model->timTheoSoThe($search);
                        if (empty($ds_the_can_cuoc)) {
                            $search_empty = true;
                        }
                    }
                } else {
                    // Người dùng nhập ký tự chữ: tìm kiếm theo Họ và tên
                    $ds_the_can_cuoc = $this->the_can_cuoc_model->timTheoHoTen($search);
                    if (empty($ds_the_can_cuoc)) {
                        $search_empty = true;
                    }
                }
            } else {
                // BẢO MẬT: Mặc định không hiển thị toàn bộ hồ sơ trong cơ sở dữ liệu
                $ds_the_can_cuoc = [];
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể truy vấn cơ sở dữ liệu.");
        }

        require_once __DIR__ . '/../views/the_can_cuoc/danh_sach.php';
    }

    // 4. Chức năng: Cập nhật thông tin thẻ căn cước
    public function capNhat() {
        yeuCauVaiTroDemo(['quan_tri'], 'Chỉ quản trị viên được cập nhật hồ sơ căn cước.');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        try {
            $the_can_cuoc = $this->the_can_cuoc_model->layTheoMa($id);
            if (!$the_can_cuoc) {
                die("Hồ sơ công dân không tồn tại trên hệ thống!");
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể tải hồ sơ thẻ căn cước.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $ho_ten         = trim($_POST['ho_ten'] ?? '');
            $ngay_sinh      = $_POST['ngay_sinh'] ?? '';
            $gioi_tinh      = $_POST['gioi_tinh'] ?? '';
            $noi_thuong_tru = trim($_POST['noi_thuong_tru'] ?? '');
            $trang_thai     = $_POST['trang_thai'] ?? '';
            $cac_trang_thai_hop_le = ['con_hieu_luc', 'het_han', 'bao_mat', 'thu_hoi'];
            if (!in_array($trang_thai, $cac_trang_thai_hop_le, true)) {
                http_response_code(400);
                die("Trạng thái hồ sơ không hợp lệ.");
            }

            try {
                $du_lieu = [
                    'ho_ten'         => $ho_ten,
                    'ngay_sinh'      => $ngay_sinh,
                    'gioi_tinh'      => $gioi_tinh,
                    'noi_thuong_tru' => $noi_thuong_tru,
                    'trang_thai'     => $trang_thai
                ];

                $this->the_can_cuoc_model->capNhat($id, $du_lieu);
                echo "<script>alert('Cập nhật dữ liệu thay đổi hồ sơ thành công!'); window.location.href='/the_can_cuoc/chi_tiet?id=" . urlencode((string)$id) . "';</script>";
                exit;
            } catch (PDOException $e) {
                http_response_code(500);
                die("Không thể cập nhật hồ sơ thẻ căn cước.");
            }
        }

        require_once __DIR__ . '/../views/the_can_cuoc/cap_nhat.php';
    }

    // Alias tương thích ngược
    public function dieuChinh() {
        $this->capNhat();
    }

    // 5. Chức năng mới: Quản lý trạng thái thẻ (còn hiệu lực, hết hạn, báo mất, thu hồi)
    public function quanLyTrangThai() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được quản lý trạng thái thẻ.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('quan_ly_trang_thai', 'Cán bộ không có quyền quản lý trạng thái thẻ.');
        }
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            $ds_the_can_cuoc = $this->the_can_cuoc_model->layTatCa();
            require_once __DIR__ . '/../views/the_can_cuoc/danh_sach_trang_thai.php';
            return;
        }

        try {
            $the_can_cuoc = $this->the_can_cuoc_model->layTheoMa($id);
            if (!$the_can_cuoc) {
                die("Không tìm thấy hồ sơ thẻ căn cước cần quản lý trạng thái!");
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể tải hồ sơ thẻ căn cước.");
        }

        $thong_bao_thanh_cong = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $trang_thai_moi = $_POST['trang_thai'] ?? '';
            $cac_trang_thai_hop_le = ['con_hieu_luc', 'het_han', 'bao_mat', 'thu_hoi'];

            if (!in_array($trang_thai_moi, $cac_trang_thai_hop_le, true)) {
                die("Trạng thái thẻ yêu cầu không hợp lệ!");
            }

            try {
                $this->the_can_cuoc_model->capNhatTrangThai($id, $trang_thai_moi);
                $the_can_cuoc['trang_thai'] = $trang_thai_moi;
                $thong_bao_thanh_cong = "Đã cập nhật trạng thái thẻ thành công!";
            } catch (PDOException $e) {
                http_response_code(500);
                die("Không thể cập nhật trạng thái thẻ.");
            }
        }

        $lich_su_trang_thai = $this->the_can_cuoc_model->layLichSuTrangThaiThe($id);

        require_once __DIR__ . '/../views/the_can_cuoc/quan_ly_trang_thai.php';
    }

    // 6. Chức năng mới: Yêu cầu cấp thẻ (Mô phỏng cấp mới, cấp đổi, cấp lại)
    public function yeuCauCapThe() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chỉ công dân được gửi yêu cầu cấp thẻ.');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $so_cccd_param = isset($_GET['so_cccd']) ? trim($_GET['so_cccd']) : '';
        $loaiYeuCauMacDinh = trim($_GET['loai'] ?? '');
        $lyDoMacDinh = trim($_GET['ly_do'] ?? '');
        if (!in_array($loaiYeuCauMacDinh, ['cap_moi', 'cap_doi', 'cap_lai', 'bao_mat'], true)) {
            $loaiYeuCauMacDinh = '';
        }
        
        $the_can_cuoc = null;
        if ($id > 0) {
            $the_can_cuoc = $this->the_can_cuoc_model->layTheoMa($id);
        } elseif (!empty($so_cccd_param)) {
            $the_can_cuoc = $this->the_can_cuoc_model->layTheoSoCccd($so_cccd_param);
        } elseif (laCongDanDemo()) {
            $the_can_cuoc = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }

        $thong_bao_ket_qua = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $so_cccd        = trim($_POST['so_cccd'] ?? '');
            $ho_ten         = trim($_POST['ho_ten'] ?? '');
            $loai_yeu_cau   = $_POST['loai_yeu_cau'] ?? 'cap_moi';
            if (!in_array($loai_yeu_cau, ['cap_moi', 'cap_doi', 'cap_lai', 'bao_mat'], true)) {
                http_response_code(400);
                die('Loại yêu cầu cấp thẻ không hợp lệ.');
            }
            $ly_do          = trim($_POST['ly_do'] ?? '');
            $hinh_thuc_nhan = $_POST['hinh_thuc_nhan'] ?? 'truc_tiep';
            $dia_chi_nhan   = trim($_POST['dia_chi_nhan'] ?? '');
            $so_dien_thoai  = trim($_POST['so_dien_thoai'] ?? '');
            $ghi_chu        = trim($_POST['ghi_chu'] ?? '');

            if (!preg_match('/^\d{12}$/', $so_cccd)) {
                echo "<script>alert('Số định danh cá nhân phải đủ đúng 12 chữ số.'); history.back();</script>";
                exit;
            }

            // Sinh mã hồ sơ dịch vụ công: YCCCD-YYYYMM-XXXXX
            $ma_ho_so = 'YCCCD-' . date('Ym') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));

            try {
                $du_lieu = [
                    'ma_ho_so'       => $ma_ho_so,
                    'so_cccd'        => $so_cccd,
                    'ho_ten'         => $ho_ten,
                    'loai_yeu_cau'   => $loai_yeu_cau,
                    'ly_do'          => $ly_do,
                    'hinh_thuc_nhan' => $hinh_thuc_nhan,
                    'dia_chi_nhan'   => $dia_chi_nhan,
                    'so_dien_thoai'  => $so_dien_thoai,
                    'trang_thai'     => 'cho_tiep_nhan',
                    'ghi_chu'        => $ghi_chu
                ];

                $this->the_can_cuoc_model->themYeuCauCapThe($du_lieu);

                $thong_bao_ket_qua = [
                    'ma_ho_so'       => $ma_ho_so,
                    'ho_ten'         => $ho_ten,
                    'so_cccd'        => $so_cccd,
                    'loai_yeu_cau'   => $loai_yeu_cau,
                    'hinh_thuc_nhan' => $hinh_thuc_nhan,
                    'dia_chi_nhan'   => $dia_chi_nhan,
                    'so_dien_thoai'  => $so_dien_thoai,
                    'ly_do'          => $ly_do,
                    'ghi_chu'        => $ghi_chu,
                    'ngay_tiep_nhan' => date('d/m/Y H:i')
                ];
            } catch (PDOException $e) {
                http_response_code(500);
                die("Không thể lưu yêu cầu cấp thẻ.");
            }
        }

        // Lấy lịch sử các yêu cầu đã gửi
        $ds_yeu_cau_gan_day = [];
        try {
            $ds_yeu_cau_gan_day = $this->the_can_cuoc_model->layDanhSachYeuCauCapThe($the_can_cuoc['so_cccd'] ?? '');
        } catch (Exception $e) {
            // Không làm gián đoạn nếu bảng chưa có
        }

        require_once __DIR__ . '/../views/the_can_cuoc/yeu_cau_cap_the.php';
    }

    // Danh sách yêu cầu cấp thẻ dành cho cán bộ mô phỏng khu vực xử lý
    public function danhSachYeuCau() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xem danh sách yêu cầu cấp thẻ.');
        try {
            $ds_yeu_cau = $this->the_can_cuoc_model->layDanhSachYeuCauChoCanBo();
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể tải danh sách yêu cầu cấp thẻ.");
        }

        require_once __DIR__ . '/../views/the_can_cuoc/danh_sach_yeu_cau.php';
    }

    // Xem và duyệt/từ chối một yêu cầu cấp thẻ
    public function xuLyYeuCau() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xử lý yêu cầu cấp thẻ.');
        $maYeuCau = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $thongBao = '';

        try {
            $yeuCau = $this->the_can_cuoc_model->layYeuCauTheoMa($maYeuCau);
            if (!$yeuCau) {
                http_response_code(404);
                die("Không tìm thấy yêu cầu cấp thẻ.");
            }
        } catch (PDOException $e) {
            http_response_code(500);
            die("Không thể tải chi tiết yêu cầu.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $ketQua = $_POST['ket_qua'] ?? '';
            $ghiChu = trim($_POST['ghi_chu'] ?? '');

            if (!in_array($ketQua, ['duyet', 'tu_choi'], true)) {
                http_response_code(400);
                die("Kết quả xử lý yêu cầu không hợp lệ.");
            }
            if ($ketQua === 'tu_choi' && $ghiChu === '') {
                http_response_code(400);
                die("Vui lòng nhập lý do khi từ chối yêu cầu.");
            }

            try {
                $this->the_can_cuoc_model->xuLyYeuCauCapThe(
                    $maYeuCau,
                    $ketQua,
                    $ghiChu !== '' ? $ghiChu : ($ketQua === 'duyet' ? 'Yêu cầu đã được cán bộ duyệt.' : '')
                );
                $thongBao = $ketQua === 'duyet'
                    ? 'Đã duyệt yêu cầu và cập nhật trạng thái thẻ phù hợp.'
                    : 'Đã từ chối yêu cầu; trạng thái thẻ không thay đổi.';
                $yeuCau = $this->the_can_cuoc_model->layYeuCauTheoMa($maYeuCau);
            } catch (InvalidArgumentException $e) {
                http_response_code(409);
                die("Yêu cầu đã được cán bộ xử lý trước đó.");
            } catch (Exception $e) {
                http_response_code(500);
                die("Không thể xử lý yêu cầu cấp thẻ.");
            }
        }

        $theCanCuoc = $this->the_can_cuoc_model->layTheoSoCccd($yeuCau['so_cccd']);
        $lichSu = $theCanCuoc
            ? $this->the_can_cuoc_model->layLichSuTrangThaiThe($theCanCuoc['id'])
            : [];

        require_once __DIR__ . '/../views/the_can_cuoc/xu_ly_yeu_cau.php';
    }

    public function xemCuaToi() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        $maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the_can_cuoc = $this->the_can_cuoc_model->layTheoNguoiDung($maNguoiDung);
        } else {
            $the_can_cuoc = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }
        if (!$the_can_cuoc) {
            http_response_code(404);
            die('Không tìm thấy căn cước gắn với tài khoản công dân.');
        }
        require_once __DIR__ . '/../views/the_can_cuoc/chi_tiet.php';
    }

    public function quanLyHoSoCuaToi() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        $maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the = $this->the_can_cuoc_model->layTheoNguoiDung($maNguoiDung);
        } else {
            $the = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }
        if (!$the) {
            http_response_code(404);
            die('Không tìm thấy hồ sơ căn cước của công dân.');
        }
        require_once __DIR__ . '/../views/the_can_cuoc/quan_ly_ho_so_cua_toi.php';
    }

    public function trangThaiCuaToi() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        $maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the = $this->the_can_cuoc_model->layTheoNguoiDung($maNguoiDung);
        } else {
            $the = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }
        if (!$the) {
            http_response_code(404);
            die('Không tìm thấy thẻ căn cước của công dân.');
        }

        $lichSuTrangThai = $this->the_can_cuoc_model->layLichSuTrangThaiThe($the['id']);
        $dsYeuCau = $this->the_can_cuoc_model->layDanhSachYeuCauCapThe($the['so_cccd']);
        $dsYeuCauBaoMat = array_values(array_filter($dsYeuCau, function ($yeuCau) {
            return ($yeuCau['loai_yeu_cau'] ?? '') === 'bao_mat';
        }));

        require_once __DIR__ . '/../views/the_can_cuoc/trang_thai_cua_toi.php';
    }

    public function guiYeuCauThayDoiTrangThai() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /the_can_cuoc/trang_thai_cua_toi');
            exit;
        }
        kiemTraMaCsrf();

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $trangThaiMoi = trim($_POST['trang_thai_moi'] ?? '');
        $lyDo = trim($_POST['ly_do'] ?? '');
        if (!in_array($trangThaiMoi, ['het_han', 'bao_mat', 'thu_hoi'], true)) {
            http_response_code(400);
            die('Trạng thái yêu cầu thay đổi không hợp lệ.');
        }
        if ($lyDo === '') {
            http_response_code(422);
            die('Vui lòng nhập lý do yêu cầu thay đổi trạng thái.');
        }

        $the = $this->the_can_cuoc_model->layTheoMa($id);
        if (!$the) {
            http_response_code(404);
            die('Không tìm thấy thẻ căn cước.');
        }

        $maHoSo = 'TCT-' . date('Ym') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));
        $loaiYeuCau = $trangThaiMoi === 'bao_mat' ? 'bao_mat' : 'cap_doi';
        $this->the_can_cuoc_model->themYeuCauCapThe([
            'ma_ho_so' => $maHoSo,
            'so_cccd' => $the['so_cccd'],
            'ho_ten' => $the['ho_ten'],
            'loai_yeu_cau' => $loaiYeuCau,
            'ly_do' => $lyDo,
            'hinh_thuc_nhan' => 'truc_tiep',
            'dia_chi_nhan' => null,
            'so_dien_thoai' => '',
            'trang_thai' => 'cho_tiep_nhan',
            'ghi_chu' => 'THAY_DOI_TRANG_THAI:' . $trangThaiMoi
        ]);

        $yeuCau = [
            'ma_ho_so' => $maHoSo,
            'trang_thai' => 'cho_tiep_nhan',
            'trang_thai_moi' => $trangThaiMoi,
            'ly_do' => $lyDo,
            'ngay_tiep_nhan' => date('d/m/Y H:i')
        ];
        require_once __DIR__ . '/../views/the_can_cuoc/cho_duyet_trang_thai.php';
    }

    public function deNghiDieuChinh() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        $maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the = $this->the_can_cuoc_model->layTheoNguoiDung($maNguoiDung);
        } else {
            $the = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }
        if (!$the) {
            http_response_code(404);
            die('Không tìm thấy hồ sơ căn cước của tài khoản công dân.');
        }
        $thongBao = '';
        $yeuCauDaGui = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $lyDo = trim($_POST['ly_do'] ?? '');
            if ($lyDo === '') {
                http_response_code(400);
                die('Vui lòng nhập lý do điều chỉnh.');
            }
            $duongDanAnhMoi = null;
            if (isset($_FILES['anh_chan_dung_moi']) && $_FILES['anh_chan_dung_moi']['error'] === UPLOAD_ERR_OK) {
                $tepAnh = $_FILES['anh_chan_dung_moi'];
                $loaiChoPhep = ['image/jpeg', 'image/png', 'image/webp'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $loaiThuc = $finfo->file($tepAnh['tmp_name']);
                if (!in_array($loaiThuc, $loaiChoPhep, true) || $tepAnh['size'] > 5 * 1024 * 1024) {
                    http_response_code(400);
                    die('Ảnh chân dung phải là JPEG, PNG hoặc WebP và không vượt quá 5 MB.');
                }
                $duongDanThuMuc = dirname(__DIR__, 2) . '/public/uploads/anh';
                if (!is_dir($duongDanThuMuc) && !mkdir($duongDanThuMuc, 0775, true)) {
                    http_response_code(500);
                    die('Không thể tạo thư mục lưu ảnh chân dung.');
                }
                $phanMoRong = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'][$loaiThuc];
                $tenTep = uniqid('cccd_', true) . '.' . $phanMoRong;
                if (!move_uploaded_file($tepAnh['tmp_name'], $duongDanThuMuc . '/' . $tenTep)) {
                    http_response_code(500);
                    die('Không thể lưu ảnh chân dung mới.');
                }
                $duongDanAnhMoi = 'uploads/anh/' . $tenTep;
            }
            $duLieuYeuCau = [
                'ma_the' => $the['id'],
                'so_cccd' => $the['so_cccd'],
                'ho_ten_cu' => $the['ho_ten'],
                'ho_ten_moi' => trim($_POST['ho_ten_moi'] ?? '') ?: null,
                'ngay_sinh_cu' => $the['ngay_sinh'],
                'ngay_sinh_moi' => trim($_POST['ngay_sinh_moi'] ?? '') ?: null,
                'gioi_tinh_cu' => $the['gioi_tinh'],
                'gioi_tinh_moi' => trim($_POST['gioi_tinh_moi'] ?? '') ?: null,
                'quoc_tich_cu' => $the['quoc_tich'] ?? null,
                'quoc_tich_moi' => trim($_POST['quoc_tich_moi'] ?? '') ?: null,
                'dan_toc_cu' => $the['dan_toc'] ?? null,
                'dan_toc_moi' => trim($_POST['dan_toc_moi'] ?? '') ?: null,
                'ton_giao_cu' => $the['ton_giao'] ?? null,
                'ton_giao_moi' => trim($_POST['ton_giao_moi'] ?? '') ?: null,
                'que_quan_cu' => $the['que_quan'] ?? null,
                'que_quan_moi' => trim($_POST['que_quan_moi'] ?? '') ?: null,
                'noi_thuong_tru_cu' => $the['noi_thuong_tru'],
                'noi_thuong_tru_moi' => trim($_POST['noi_thuong_tru_moi'] ?? '') ?: null,
                'anh_chan_dung_cu' => $the['anh_chan_dung'] ?? null,
                'anh_chan_dung_moi' => $duongDanAnhMoi,
                'ly_do' => $lyDo
            ];
            $luuThanhCong = $this->the_can_cuoc_model->themYeuCauDieuChinh($duLieuYeuCau);
            if (!$luuThanhCong) {
                http_response_code(500);
                die('Không thể lưu yêu cầu điều chỉnh. Vui lòng thử lại.');
            }
            $thongBao = 'Đã gửi yêu cầu điều chỉnh. Trạng thái hiện tại: Chờ tiếp nhận.';
            $yeuCauDaGui = $duLieuYeuCau;
        }
        require_once __DIR__ . '/../views/the_can_cuoc/de_nghi_dieu_chinh.php';
    }

    public function yeuCauCuaToi() {
        yeuCauVaiTroDemo(['cong_dan'], 'Chức năng này chỉ dành cho công dân.');
        $maNguoiDung = (int)($_SESSION['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the = $this->the_can_cuoc_model->layTheoNguoiDung($maNguoiDung);
        } else {
            $the = $this->the_can_cuoc_model->layBanGhiMoiNhat();
        }
        $dsYeuCau = $the
            ? $this->the_can_cuoc_model->layDanhSachYeuCauDieuChinh($the['so_cccd'])
            : [];
        require_once __DIR__ . '/../views/the_can_cuoc/yeu_cau_cua_toi.php';
    }

    public function danhSachYeuCauDieuChinh() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xem yêu cầu điều chỉnh.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('duyet_dieu_chinh', 'Cán bộ không có quyền duyệt yêu cầu điều chỉnh.');
        }
        $dsYeuCau = $this->the_can_cuoc_model->layDanhSachYeuCauDieuChinh();
        require_once __DIR__ . '/../views/the_can_cuoc/danh_sach_yeu_cau_dieu_chinh.php';
    }

    public function chiTietYeuCauDieuChinh() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xử lý yêu cầu điều chỉnh.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('duyet_dieu_chinh', 'Cán bộ không có quyền duyệt yêu cầu điều chỉnh.');
        }
        $maYeuCau = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $yeuCau = $this->the_can_cuoc_model->layYeuCauDieuChinhTheoMa($maYeuCau);
        if (!$yeuCau) {
            http_response_code(404);
            die('Không tìm thấy yêu cầu điều chỉnh.');
        }
        $thongBao = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $ketQua = $_POST['ket_qua'] ?? '';
            $ghiChu = trim($_POST['ghi_chu'] ?? '');
            if (!in_array($ketQua, ['duyet', 'tu_choi'], true)) {
                http_response_code(400);
                die('Kết quả xử lý không hợp lệ.');
            }
            if ($ketQua === 'tu_choi' && $ghiChu === '') {
                http_response_code(400);
                die('Vui lòng nhập lý do từ chối.');
            }
            $this->the_can_cuoc_model->xuLyYeuCauDieuChinh(
                $maYeuCau,
                $ketQua,
                $ghiChu !== '' ? $ghiChu : 'Yêu cầu đã được cán bộ duyệt.'
            );
            $yeuCau = $this->the_can_cuoc_model->layYeuCauDieuChinhTheoMa($maYeuCau);
            $thongBao = $ketQua === 'duyet'
                ? 'Đã duyệt và cập nhật hồ sơ gốc.'
                : 'Đã từ chối yêu cầu điều chỉnh.';
        }
        require_once __DIR__ . '/../views/the_can_cuoc/chi_tiet_yeu_cau_dieu_chinh.php';
    }

    public function danhSachYeuCauTrangThai() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xem yêu cầu trạng thái thẻ.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('quan_ly_trang_thai', 'Cán bộ không có quyền quản lý trạng thái thẻ.');
        }
        $dsYeuCau = $this->the_can_cuoc_model->layDanhSachYeuCauTrangThaiThe();
        require_once __DIR__ . '/../views/the_can_cuoc/danh_sach_yeu_cau_trang_thai.php';
    }

    public function chiTietYeuCauTrangThai() {
        yeuCauVaiTroDemo(['can_bo', 'quan_tri'], 'Chỉ cán bộ hoặc quản trị viên được xử lý yêu cầu trạng thái thẻ.');
        if (laCanBoDemo()) {
            yeuCauQuyenCanBo('quan_ly_trang_thai', 'Cán bộ không có quyền quản lý trạng thái thẻ.');
        }
        $this->xuLyYeuCau();
    }
}
