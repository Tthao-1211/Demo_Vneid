<?php
// app/controllers/DichVuController.php

require_once __DIR__ . '/../models/DichVu.php';
require_once __DIR__ . '/../models/HoSoDichVu.php';
require_once __DIR__ . '/../models/ThongBao.php';

class DichVuController {
    private $model_dich_vu;
    private $model_ho_so;

    public function __construct() {
        // Kiểm tra đăng nhập bắt buộc đối với tất cả chức năng dịch vụ công
        yeuCauDangNhap();
        $this->model_dich_vu = new DichVu();
        $this->model_ho_so = new HoSoDichVu();
    }

    // Trang hiển thị danh sách hồ sơ đã nộp & danh mục dịch vụ công (/dich_vu/danh_sach)
    public function danhSach() {
        $ma_nguoi_dung = $_SESSION['ma_nguoi_dung'];
        $ds_dich_vu = $this->model_dich_vu->layDanhSach();
        $ds_ho_so = $this->model_ho_so->layTheoNguoiDung($ma_nguoi_dung);

        require_once __DIR__ . '/../views/dich_vu/danh_sach.php';
    }

    // Xử lý nộp hồ sơ dịch vụ công (/dich_vu/nop_ho_so)
    public function nopHoSo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();

            $ma_dich_vu = (int)($_POST['ma_dich_vu'] ?? 0);
            $noi_dung = trim($_POST['noi_dung'] ?? '');
            $ma_nguoi_dung = $_SESSION['ma_nguoi_dung'];
            $tep_dinh_kem = null;

            // Xử lý tải tệp đính kèm (nếu có)
            if (isset($_FILES['tep_dinh_kem']) && $_FILES['tep_dinh_kem']['error'] === UPLOAD_ERR_OK) {
                $thu_muc_upload = __DIR__ . '/../../public/uploads/';
                $ten_tep_goc = basename($_FILES['tep_dinh_kem']['name']);
                $duoi_tep = pathinfo($ten_tep_goc, PATHINFO_EXTENSION);
                
                // Quy tắc đặt tên tệp: loai_manguoidung_thoigian.duoi
                $ten_tep_luu = 'hoso_' . $ma_nguoi_dung . '_' . date('YmdHis') . '.' . $duoi_tep;
                
                if (move_uploaded_file($_FILES['tep_dinh_kem']['tmp_name'], $thu_muc_upload . $ten_tep_luu)) {
                    $tep_dinh_kem = 'uploads/' . $ten_tep_luu;
                }
            }

            $this->model_ho_so->themMoi($ma_nguoi_dung, $ma_dich_vu, $noi_dung, $tep_dinh_kem);
            global $pdo;
            try {
                (new ThongBao($pdo))->guiChoCanBo(
                    ThongBao::LOAI_DICH_VU_CONG,
                    'Có hồ sơ dịch vụ công mới',
                    ($_SESSION['ho_ten'] ?? 'Công dân') . ' vừa nộp hồ sơ, đang chờ xử lý.'
                );
            } catch (PDOException $e) { /* không để lỗi thông báo làm hỏng việc nộp hồ sơ */ }
            chuyenHuong('dich_vu/danh_sach');
        }

        $ds_dich_vu = $this->model_dich_vu->layDanhSach();
        require_once __DIR__ . '/../views/dich_vu/nop_ho_so.php';
    }

    // Trang dành riêng cho Cán bộ xử lý hồ sơ (/dich_vu/ds_ho_so_can_xu_ly)
    public function dsHoSoCanXuLy() {
        yeuCauVaiTro([VAI_TRO_CAN_BO, VAI_TRO_QUAN_TRI]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $ma_ho_so = (int)($_POST['ma_ho_so'] ?? 0);
            $trang_thai = $_POST['trang_thai'] ?? 'dang_xu_ly';
            $ghi_chu = trim($_POST['ghi_chu'] ?? '');
            $ma_can_bo = $_SESSION['ma_nguoi_dung'];

            $this->model_ho_so->capNhatTrangThai($ma_ho_so, $trang_thai, $ghi_chu, $ma_can_bo);
            chuyenHuong('dich_vu/ds_ho_so_can_xu_ly');
        }

        $ds_ho_so = $this->model_ho_so->layDanhSachCanBo();
        require_once __DIR__ . '/../views/dich_vu/ds_ho_so_can_xu_ly.php';
    }
    // Đường dẫn: /dich_vu/khai_bao_tam_tru
    public function khaiBaoTamTru() {
        yeuCauDangNhap();
        require_once __DIR__ . '/../views/dich_vu/khai_bao_tam_tru.php';
    }
}