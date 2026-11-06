<?php
require_once __DIR__ . '/../models/NangMucDinhDanh.php';
require_once __DIR__ . '/../models/NguoiDung.php';
require_once __DIR__ . '/../models/ThongBao.php';

class MucDinhDanhController {
    const KICH_THUOC_TOI_DA = 3145728;
    const THU_MUC_TEP = __DIR__ . '/../../uploads_rieng/';

    private $nang_muc_model;
    private $nguoi_dung_model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; 
        $this->nang_muc_model   = new NangMucDinhDanh($pdo);
        $this->nguoi_dung_model = new NguoiDung($pdo);
    }

    public function nopHoSo() {
        yeuCauVaiTro([VAI_TRO_CONG_DAN]);
        $ma_nguoi_dung = (int)$_SESSION['ma_nguoi_dung'];
        $nguoi_dung    = $this->nguoi_dung_model->layTheoMa($ma_nguoi_dung);
        $da_muc_hai    = (int)$nguoi_dung['muc_dinh_danh'] >= 2;
        $dang_cho      = $this->nang_muc_model->coHoSoDangCho($ma_nguoi_dung);
        $ds_loi        = [];
        $du_lieu       = ['so_the' => $nguoi_dung['so_dinh_danh'], 'noi_cap' => '', 'ghi_chu' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$da_muc_hai && !$dang_cho) {
            kiemTraMaCsrf();
            foreach ($du_lieu as $khoa => $_) {
                $du_lieu[$khoa] = trim($_POST[$khoa] ?? '');
            }
            if ($du_lieu['so_the'] !== $nguoi_dung['so_dinh_danh']) {
                $ds_loi[] = 'Số thẻ phải trùng số định danh của tài khoản.';
            }
            if ($du_lieu['noi_cap'] === '') {
                $ds_loi[] = 'Vui lòng nhập nơi cấp thẻ.';
            }
            $duong_dan_mat_truoc = $this->luuTep('anh_mat_truoc', 'cccd', $ma_nguoi_dung, $ds_loi);
            $duong_dan_chan_dung = $this->luuTep('anh_chan_dung', 'chandung', $ma_nguoi_dung, $ds_loi);

            if (!$ds_loi) {
                $this->nang_muc_model->themMoi($ma_nguoi_dung, json_encode($du_lieu, JSON_UNESCAPED_UNICODE),
                    $duong_dan_mat_truoc . ';' . $duong_dan_chan_dung);
                try {
                    (new ThongBao($this->pdo))->guiChoCanBo(
                        ThongBao::LOAI_DICH_VU_CONG,
                        'Có hồ sơ nâng mức định danh mới',
                        ($_SESSION['ho_ten'] ?? 'Công dân') . ' vừa gửi hồ sơ nâng mức định danh.'
                    );
                } catch (PDOException $e) {}
                $_SESSION['thong_bao'] = 'Đã gửi hồ sơ nâng mức. Vui lòng chờ cán bộ xử lý.';
                chuyenHuong('muc_dinh_danh/danh_sach_cua_toi');
            }
        }
        require __DIR__ . '/../views/muc_dinh_danh/nop_ho_so.php';
    }

    public function danhSachCuaToi() {
        yeuCauVaiTro([VAI_TRO_CONG_DAN]);
        $nguoi_dung    = $this->nguoi_dung_model->layTheoMa($_SESSION['ma_nguoi_dung']);
        $ds_ho_so      = $this->nang_muc_model->layDanhSachTheoNguoiDung($_SESSION['ma_nguoi_dung']);
        require __DIR__ . '/../views/muc_dinh_danh/danh_sach_cua_toi.php';
    }

    public function danhSachChoDuyet() {
        yeuCauVaiTro([VAI_TRO_CAN_BO]);
        $trang_thai = $_GET['trang_thai'] ?? '';
        if ($trang_thai !== '' && !isset(NangMucDinhDanh::NHAN_TRANG_THAI[$trang_thai])) {
            $trang_thai = '';
        }
        $ds_ho_so = $this->nang_muc_model->layDanhSachTheoTrangThai($trang_thai);
        require __DIR__ . '/../views/muc_dinh_danh/ds_cho_duyet.php';
    }

    public function chiTiet() {
        yeuCauVaiTro([VAI_TRO_CAN_BO]);
        $ho_so = $this->nang_muc_model->layTheoMa((int)($_GET['ma_ho_so'] ?? 0));
        if (!$ho_so) {
            http_response_code(404);
            exit('Không tìm thấy hồ sơ');
        }
        if ($ho_so['trang_thai'] === 'da_nop') {
            $this->nang_muc_model->capNhatTrangThai($ho_so['ma_ho_so'], 'dang_xu_ly', null, $_SESSION['ma_nguoi_dung']);
            $ho_so['trang_thai'] = 'dang_xu_ly';
        }
        $thong_tin = json_decode($ho_so['noi_dung'], true) ?: [];
        require __DIR__ . '/../views/muc_dinh_danh/chi_tiet.php';
    }

    public function duyet()          { $this->xuLy('da_duyet'); }
    public function tuChoi()         { $this->xuLy('tu_choi'); }
    public function yeuCauBoSung()   { $this->xuLy('can_bo_sung'); }

    public function xemTep() {
        yeuCauDangNhap();
        $ho_so = $this->nang_muc_model->layTheoMa((int)($_GET['ma_ho_so'] ?? 0));
        $la_chu = $ho_so && (int)$ho_so['ma_nguoi_dung'] === (int)$_SESSION['ma_nguoi_dung'];
        $la_can_bo = in_array((int)$_SESSION['ma_vai_tro'], [VAI_TRO_CAN_BO, VAI_TRO_QUAN_TRI], true);
        if (!$ho_so || (!$la_chu && !$la_can_bo)) {
            http_response_code(403);
            exit('Bạn không có quyền xem tệp này');
        }
        $ds_tep = explode(';', (string)$ho_so['tep_dinh_kem']);
        $ten    = basename($ds_tep[(int)($_GET['vi_tri'] ?? 0)] ?? '');
        $duong_dan = self::THU_MUC_TEP . $ten;
        if ($ten === '' || !is_file($duong_dan)) {
            http_response_code(404);
            exit('Không tìm thấy tệp');
        }
        header('Content-Type: ' . mime_content_type($duong_dan));
        header('X-Content-Type-Options: nosniff');
        readfile($duong_dan);
        exit;
    }

    private function xuLy($trang_thai_moi) {
        yeuCauVaiTro([VAI_TRO_CAN_BO]);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được hỗ trợ');
        }
        kiemTraMaCsrf();
        $ho_so   = $this->nang_muc_model->layTheoMa((int)($_POST['ma_ho_so'] ?? 0));
        $ghi_chu = trim($_POST['ghi_chu'] ?? '');

        if (!$ho_so || !in_array($ho_so['trang_thai'], ['da_nop', 'dang_xu_ly'], true)) {
            $_SESSION['thong_bao'] = 'Hồ sơ không tồn tại hoặc đã được xử lý.';
            chuyenHuong('muc_dinh_danh/danh_sach_cho_duyet');
        }
        if ($trang_thai_moi !== 'da_duyet' && $ghi_chu === '') {
            $_SESSION['thong_bao'] = 'Vui lòng nhập lý do khi từ chối hoặc yêu cầu bổ sung.';
            chuyenHuong('muc_dinh_danh/chi_tiet?ma_ho_so=' . $ho_so['ma_ho_so']);
        }

        if ($trang_thai_moi === 'da_duyet') {
    // Model tự cập nhật trạng thái hồ sơ, nâng mức định danh lên 2,
    // cập nhật/tạo thẻ căn cước kèm ảnh chân dung và gửi thông báo cho công dân.
            $this->nang_muc_model->duyet($ho_so, $_SESSION['ma_nguoi_dung'], $ghi_chu);
        } else {
            $this->nang_muc_model->capNhatTrangThai($ho_so['ma_ho_so'], $trang_thai_moi, $ghi_chu, $_SESSION['ma_nguoi_dung']);
            $tieu_de = $trang_thai_moi === 'tu_choi' ? 'Hồ sơ nâng mức bị từ chối' : 'Hồ sơ nâng mức cần bổ sung';
            $this->nang_muc_model->guiThongBao($ho_so['ma_nguoi_dung'], $tieu_de, $ghi_chu);
        }
        $_SESSION['thong_bao'] = 'Đã cập nhật hồ sơ.';
        chuyenHuong('muc_dinh_danh/danh_sach_cho_duyet');
    }

    private function luuTep($ten_truong, $loai, $ma_nguoi_dung, array &$ds_loi) {
        $tep = $_FILES[$ten_truong] ?? null;
        if (!$tep || $tep['error'] !== UPLOAD_ERR_OK) {
            $ds_loi[] = 'Vui lòng chọn ảnh cho mục "' . ($loai === 'cccd' ? 'Ảnh mặt trước CCCD' : 'Ảnh chân dung') . '".';
            return '';
        }
        if ($tep['size'] > self::KICH_THUOC_TOI_DA) {
            $ds_loi[] = 'Mỗi ảnh tối đa 3 MB.';
            return '';
        }
        $ds_duoi = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $kieu    = mime_content_type($tep['tmp_name']);
        if (!isset($ds_duoi[$kieu])) {
            $ds_loi[] = 'Ảnh phải là JPG hoặc PNG.';
            return '';
        }
        $ten = $loai . '_' . $ma_nguoi_dung . '_' . date('YmdHis') . '.' . $ds_duoi[$kieu];
        if (!move_uploaded_file($tep['tmp_name'], self::THU_MUC_TEP . $ten)) {
            $ds_loi[] = 'Không lưu được ảnh, vui lòng thử lại.';
            return '';
        }
        return $ten;
    }
}