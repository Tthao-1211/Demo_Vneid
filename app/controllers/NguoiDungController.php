<?php
require_once __DIR__ . '/../models/NguoiDung.php';
require_once __DIR__ . '/../models/MaOtp.php';
require_once __DIR__ . '/../models/NhatKyHeThong.php';
require_once __DIR__ . '/../models/NangMucDinhDanh.php';
require_once __DIR__ . '/../models/HoSoDichVu.php';

class NguoiDungController {
    const SO_LAN_NHAP_OTP_TOI_DA = 5;
    const GIAY_CHO_GUI_LAI_OTP   = 60;

    private $pdo;
    private $nguoi_dung_model;
    private $ma_otp_model;
    private $nhat_ky_model;

    public function __construct($pdo) {
        $this->pdo              = $pdo;
        $this->nguoi_dung_model = new NguoiDung($pdo);
        $this->ma_otp_model     = new MaOtp($pdo);
        $this->nhat_ky_model    = new NhatKyHeThong($pdo);
    }

    private function guiOtpQuaEmail($email) {
        $_SESSION['otp_email']      = $email;
        $_SESSION['otp_so_lan_sai'] = 0;
        $_SESSION['otp_gui_luc']    = time();
        $ma_otp = $this->ma_otp_model->taoMoi($email);
        return guiMailOtp($email, $ma_otp);
    }

    private function xoaPhienOtp() {
        unset($_SESSION['otp_email'], $_SESSION['otp_muc_dich'], $_SESSION['otp_ma_nguoi_dung'],
              $_SESSION['otp_so_lan_sai'], $_SESSION['otp_gui_luc'], $_SESSION['mat_khau_moi_da_bam']);
    }

    private function pdo_thong_ke_ho_so() {
        $ket_qua = ['da_nop' => 0, 'dang_xu_ly' => 0, 'can_bo_sung' => 0, 'da_duyet' => 0, 'tu_choi' => 0];
        $lenh = $this->pdo->query('SELECT trang_thai, COUNT(*) AS so_luong FROM ho_so_dich_vu GROUP BY trang_thai');
        foreach ($lenh->fetchAll() as $dong) {
            $ket_qua[$dong['trang_thai']] = (int)$dong['so_luong'];
        }
        return $ket_qua;
    }

    public function trangDau() {
        if (kiemTraDangNhap()) {
            chuyenHuong(layTrangChu($_SESSION['ma_vai_tro']));
        }
        require __DIR__ . '/../views/nguoi_dung/trang_dau.php';
    }

    public function dangNhap() {
        if (kiemTraDangNhap()) {
            chuyenHuong(layTrangChu($_SESSION['ma_vai_tro']));
        }
        $thong_bao_loi = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $so_dinh_danh = trim($_POST['so_dinh_danh'] ?? '');
            $mat_khau     = $_POST['mat_khau'] ?? '';
            $dia_chi_ip   = layDiaChiIp();
            $nguoi_dung   = $this->nguoi_dung_model->layTheoSoDinhDanh($so_dinh_danh);

            if (!$nguoi_dung) {
                $this->nhat_ky_model->ghi(null, 'that_bai', $dia_chi_ip);
                $thong_bao_loi = 'Số định danh hoặc mật khẩu không đúng.';
            } elseif ($nguoi_dung['trang_thai'] === NguoiDung::TRANG_THAI_BI_KHOA) {
                $thong_bao_loi = 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.';
            } elseif ($nguoi_dung['trang_thai'] === NguoiDung::TRANG_THAI_CHO_KICH_HOAT) {
                $thong_bao_loi = 'Tài khoản chưa được kích hoạt.';
            } elseif (!password_verify($mat_khau, $nguoi_dung['mat_khau'])) {
                $so_lan_sai = $this->nguoi_dung_model->tangSoLanSai($nguoi_dung['ma_nguoi_dung']);
                if ($so_lan_sai >= SO_LAN_SAI_TOI_DA) {
                    ghiNhatKyHeThong('khoa_tu_dong', 'Tài khoản bị khóa do nhập sai mật khẩu ' . $so_lan_sai . ' lần',
                        'nguoi_dung', $nguoi_dung['ma_nguoi_dung'], $nguoi_dung['ma_nguoi_dung']);
                }
                $this->nhat_ky_model->ghi($nguoi_dung['ma_nguoi_dung'], 'that_bai', $dia_chi_ip);
                $thong_bao_loi = $so_lan_sai >= SO_LAN_SAI_TOI_DA
                    ? 'Tài khoản đã bị khóa do nhập sai quá nhiều lần.'
                    : 'Số định danh hoặc mật khẩu không đúng.';
            } elseif (empty($nguoi_dung['email'])) {
                $thong_bao_loi = 'Tài khoản chưa có email để nhận OTP. Vui lòng liên hệ quản trị viên.';
            } else {
                $this->nguoi_dung_model->datLaiSoLanSai($nguoi_dung['ma_nguoi_dung']);
                if ($this->guiOtpQuaEmail($nguoi_dung['email'])) {
                    $_SESSION['otp_muc_dich']      = 'dang_nhap';
                    $_SESSION['otp_ma_nguoi_dung'] = $nguoi_dung['ma_nguoi_dung'];
                    chuyenHuong('nguoi_dung/xac_thuc_otp');
                }
                $this->xoaPhienOtp();
                $thong_bao_loi = 'Không gửi được email OTP. Vui lòng thử lại sau.';
            }
        }
        require __DIR__ . '/../views/nguoi_dung/dang_nhap.php';
    }

    public function trangChu() {
        yeuCauDangNhap();
        $thongTin      = $this->nguoi_dung_model->layTheoMa($_SESSION['ma_nguoi_dung']);
        $ho_ten        = $thongTin['ho_ten'];
        $muc_dinh_danh = (int)$thongTin['muc_dinh_danh'];
        $chu_cai_dau   = mb_strtoupper(mb_substr(trim($ho_ten), -1, 1, 'UTF-8'), 'UTF-8');

        // Lấy thông tin CCCD hoặc hồ sơ nâng mức đã duyệt gần nhất để lấy ảnh
        $stmt_cccd = $this->pdo->prepare("
            SELECT t.so_the, t.ngay_sinh, t.gioi_tinh, t.noi_thuong_tru, t.anh_chan_dung, h.ma_ho_so
            FROM the_can_cuoc t
            LEFT JOIN ho_so_dich_vu h ON h.ma_nguoi_dung = t.ma_nguoi_dung AND h.trang_thai = 'da_duyet'
            WHERE t.ma_nguoi_dung = ?
            ORDER BY h.ma_ho_so DESC
            LIMIT 1
        ");
        $stmt_cccd->execute([$_SESSION['ma_nguoi_dung']]);
        $cccd = $stmt_cccd->fetch(PDO::FETCH_ASSOC);

        $so_dinh_danh   = $cccd['so_the'] ?? $thongTin['so_dinh_danh'];
        $ngay_sinh      = $cccd['ngay_sinh'] ?? '';
        $gioi_tinh      = $cccd['gioi_tinh'] ?? '';
        $noi_thuong_tru = $cccd['noi_thuong_tru'] ?? '';
        
        // Ưu tiên lấy đường dẫn xem tệp từ hồ sơ đã duyệt trong uploads_rieng, nếu không có mới dùng ảnh mặc định
        $anh_chan_dung = '';
        if (!empty($cccd['ma_ho_so'])) {
            $anh_chan_dung = '/muc_dinh_danh/xem_tep?ma_ho_so=' . $cccd['ma_ho_so'] . '&vi_tri=1';
        } else {
            $anh_chan_dung = $cccd['anh_chan_dung'] ?? '';
        }
        
        $qr_cccd_data = implode('|', [
            trim((string)$so_dinh_danh),
            trim((string)$ho_ten),
            trim((string)$ngay_sinh),
            trim((string)$gioi_tinh),
            trim((string)$noi_thuong_tru)
        ]);
        
        $qr_dinh_danh_data = 'VNEID|' . $so_dinh_danh . '|MUC' . $muc_dinh_danh . '|' . time();

        require __DIR__ . '/../views/nguoi_dung/trang_chu.php';
    }

    public function trangChuCanBo() {
        yeuCauVaiTro([VAI_TRO_CAN_BO]);
        $nang_muc_model = new NangMucDinhDanh($this->pdo);
        $ma_dich_vu_nang_muc = $nang_muc_model->layMaDichVu();

        // Bộ trạng thái chung cho mọi loại hồ sơ
        $NHAN_CHUNG = [
            'cho_xu_ly' => 'Chờ xử lý',
            'bo_sung'   => 'Cần bổ sung',
            'da_duyet'  => 'Đã duyệt',
            'tu_choi'   => 'Từ chối',
        ];
        $QUY_DOI = [
            'dang_xu_ly' => 'cho_xu_ly', 'can_bo_sung' => 'bo_sung',
            'da_duyet' => 'da_duyet',    'tu_choi' => 'tu_choi',
            'cho_xac_minh' => 'cho_xu_ly', 'yeu_cau_bo_sung' => 'bo_sung',
            'da_xac_minh' => 'da_duyet',   'bi_tu_choi' => 'tu_choi',
        ];
        $chuan = fn($s) => $QUY_DOI[$s] ?? 'cho_xu_ly';

        $trang_thai = $_GET['trang_thai'] ?? '';
        if ($trang_thai !== '' && !isset($NHAN_CHUNG[$trang_thai])) {
            $trang_thai = '';
        }
        $tu_khoa = trim($_GET['tu_khoa'] ?? '');
        $kiem_tra_ngay = function ($ngay) {
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngay) ? $ngay : '';
        };
        $tu_ngay  = $kiem_tra_ngay($_GET['tu_ngay'] ?? '');
        $den_ngay = $kiem_tra_ngay($_GET['den_ngay'] ?? '');

        $tat_ca = [];
        $canh_bao = '';

        // 1) Hồ sơ dịch vụ công (gồm cả hồ sơ nâng mức nếu cùng bảng ho_so_dich_vu)
        $model_ho_so = new HoSoDichVu();
        foreach ($model_ho_so->layDanhSachCanBo() as $r) {
            $la_nang_muc = isset($r['ma_dich_vu']) && (int)$r['ma_dich_vu'] === (int)$ma_dich_vu_nang_muc;
            $tat_ca[] = [
                'nguon' => $la_nang_muc ? 'Nâng mức định danh' : 'Dịch vụ công',
                'ma' => '#DV' . $r['ma_ho_so'],
                'loai' => $r['ten_dich_vu'], 'ho_ten' => $r['ho_ten'],
                'so_dinh_danh' => $r['so_dinh_danh'],
                'ngay' => $r['ngay_nop'] ?? $r['ngay_tao'] ?? null,   // sửa theo tên cột thật
                'trang_thai' => $chuan($r['trang_thai']),
                'link' => $la_nang_muc
                    ? '/muc_dinh_danh/chi_tiet?ma_ho_so=' . $r['ma_ho_so']
                    : '/dich_vu/ds_ho_so_can_xu_ly',
            ];
        }

        // 2) Giấy tờ ví (tên bảng/cột là đoán, sửa cho khớp DB)
        try {
            $sql = 'SELECT g.ma_giay_to, g.ngay_cap, g.trang_thai, l.ten_loai, n.ho_ten, n.so_dinh_danh
                    FROM giay_to_vi g
                    JOIN loai_giay_to l ON l.ma_loai = g.ma_loai
                    JOIN nguoi_dung n ON n.ma_nguoi_dung = g.ma_nguoi_dung';
            foreach ($this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $tat_ca[] = [
                    'nguon' => 'Giấy tờ ví', 'ma' => '#GT' . $r['ma_giay_to'],
                    'loai' => $r['ten_loai'], 'ho_ten' => $r['ho_ten'],
                    'so_dinh_danh' => $r['so_dinh_danh'], 'ngay' => $r['ngay_cap'],
                    'trang_thai' => $chuan($r['trang_thai']),
                    'link' => '/can_bo/chi_tiet?id=' . $r['ma_giay_to'],
                ];
            }
        } catch (PDOException $e) {
            $canh_bao = 'Chưa đọc được giấy tờ ví: ' . $e->getMessage();
        }

        // Thống kê (trước khi lọc)
        $ds_thong_ke = array_count_values(array_column($tat_ca, 'trang_thai'));

        // Lọc
        $tat_ca = array_filter($tat_ca, function ($h) use ($trang_thai, $tu_khoa, $tu_ngay, $den_ngay) {
            if ($trang_thai && $h['trang_thai'] !== $trang_thai) return false;
            if ($tu_khoa && stripos($h['ho_ten'] . ' ' . $h['so_dinh_danh'], $tu_khoa) === false) return false;
            $d = $h['ngay'] ? date('Y-m-d', strtotime($h['ngay'])) : null;
            if ($tu_ngay && $d && $d < $tu_ngay) return false;
            if ($den_ngay && $d && $d > $den_ngay) return false;
            return true;
        });

        // Mới nhất trước
        usort($tat_ca, fn($a, $b) => strtotime($b['ngay'] ?? '') <=> strtotime($a['ngay'] ?? ''));
        $ds_hoso = $ds_ho_so = $tat_ca;

        require __DIR__ . '/../views/nguoi_dung/trang_chu_can_bo.php';
    }

    public function dangXuat() {
        if (kiemTraDangNhap()) {
            $this->nhat_ky_model->ghi($_SESSION['ma_nguoi_dung'], 'dang_xuat', layDiaChiIp());
        }
        $_SESSION = [];
        session_destroy();
        chuyenHuong('nguoi_dung/dang_nhap');
    }

    private function taoSoDinhDanh(): string
    {
        do {
            $soDinhDanh = sprintf(
                '%012d',
                random_int(0, 999999999999)
            );

            $stmt = $this->pdo->prepare("
                SELECT 1
                FROM nguoi_dung
                WHERE so_dinh_danh = ?
                UNION
                SELECT 1
                FROM the_can_cuoc
                WHERE so_the = ?
                LIMIT 1
            ");

            $stmt->execute([
                $soDinhDanh,
                $soDinhDanh
            ]);

            $daTonTai = $stmt->fetchColumn() !== false;

        } while ($daTonTai);

        return $soDinhDanh;
    }

    public function dangKy()
    {
        $ds_loi = [];

        $du_lieu = [
            'so_dinh_danh' => '',
            'ho_ten' => '',
            'so_dien_thoai' => '',
            'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            kiemTraMaCsrf();

            $du_lieu['ho_ten'] = trim($_POST['ho_ten'] ?? '');
            $du_lieu['so_dien_thoai'] = trim($_POST['so_dien_thoai'] ?? '');
            $du_lieu['email'] = trim($_POST['email'] ?? '');

            $mat_khau = $_POST['mat_khau'] ?? '';
            $nhap_lai_mat_khau = $_POST['nhap_lai_mat_khau'] ?? '';

            if ($du_lieu['ho_ten'] === '') {
                $ds_loi[] = 'Vui lòng nhập họ tên.';
            } elseif (mb_strlen($du_lieu['ho_ten']) < 2) {
                $ds_loi[] = 'Họ tên phải có ít nhất 2 ký tự.';
            }

            if ($du_lieu['so_dien_thoai'] === '') {
                $ds_loi[] = 'Vui lòng nhập số điện thoại.';
            } elseif (!preg_match('/^0[0-9]{9}$/', $du_lieu['so_dien_thoai'])) {
                $ds_loi[] = 'Số điện thoại không hợp lệ.';
            }

            if ($du_lieu['email'] === '') {
                $ds_loi[] = 'Vui lòng nhập email.';
            } elseif (!filter_var($du_lieu['email'], FILTER_VALIDATE_EMAIL)) {
                $ds_loi[] = 'Địa chỉ email không hợp lệ.';
            }

            if ($mat_khau === '') {
                $ds_loi[] = 'Vui lòng nhập mật khẩu.';
            } elseif (strlen($mat_khau) < 8) {
                $ds_loi[] = 'Mật khẩu phải có ít nhất 8 ký tự.';
            }

            if ($mat_khau !== $nhap_lai_mat_khau) {
                $ds_loi[] = 'Mật khẩu nhập lại không khớp.';
            }

            if (empty($ds_loi)) {

                $daTonTai = $this->nguoi_dung_model->kiemTraTonTai(
                    '',
                    $du_lieu['so_dien_thoai'],
                    $du_lieu['email']
                );

                if ($daTonTai) {
                    $ds_loi[] = 'Số điện thoại hoặc email đã được đăng ký.';
                }
            }

            if (empty($ds_loi)) {

                try {
                    $du_lieu['so_dinh_danh'] = $this->taoSoDinhDanh();

                    $ma_moi = $this->nguoi_dung_model->themMoi(
                        $du_lieu['so_dinh_danh'],
                        $du_lieu['ho_ten'],
                        $du_lieu['so_dien_thoai'],
                        $du_lieu['email'],
                        $mat_khau
                    );

                    if (!$ma_moi) {
                        $ds_loi[] = 'Không thể tạo tài khoản. Vui lòng thử lại.';
                    } else {

                        ghiNhatKyHeThong(
                            'dang_ky', 
                            'Người dùng đăng ký tài khoản mới.', 
                            'nguoi_dung', 
                            $ma_moi, 
                            $ma_moi
                        );

                        $da_gui = $this->guiOtpQuaEmail(
                            $du_lieu['email']
                        );

                        if ($da_gui) {
                            $_SESSION['otp_muc_dich'] = 'dang_ky';
                            chuyenHuong('nguoi_dung/xac_thuc_otp');
                            return;
                        } else {

                            $ds_loi[] = 'Tài khoản đã được tạo nhưng không thể gửi OTP. Vui lòng thử lại hoặc liên hệ quản trị viên.';
                        }
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() === '23000') {
                        $ds_loi[] = 'Thông tin đăng ký đã tồn tại. Vui lòng kiểm tra lại.';
                    } else {
                        $ds_loi[] = 'Đã xảy ra lỗi khi đăng ký tài khoản.';
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/nguoi_dung/dang_ky.php';
    }

    public function xacThucOtp() {
        $email    = $_SESSION['otp_email'] ?? '';
        $muc_dich = $_SESSION['otp_muc_dich'] ?? '';
        if ($email === '' || $muc_dich === '') {
            chuyenHuong('nguoi_dung/dang_nhap');
        }
        $thong_bao_loi = $_SESSION['loi_gui_mail'] ?? '';
        unset($_SESSION['loi_gui_mail']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $ma_otp = trim($_POST['ma_otp'] ?? '');
            $bi_chan = $muc_dich === 'quen_mat_khau' && empty($_SESSION['otp_ma_nguoi_dung']);

            if (!$bi_chan && $this->ma_otp_model->xacThuc($email, $ma_otp)) {
                $ma_nguoi_dung = (int)($_SESSION['otp_ma_nguoi_dung'] ?? 0);

                if ($muc_dich === 'dang_ky') {
                    $nguoi_dung = $this->nguoi_dung_model->layTheoEmail($email);
                    $this->nguoi_dung_model->capNhatTrangThai(
                        $nguoi_dung['ma_nguoi_dung'], NguoiDung::TRANG_THAI_HOAT_DONG);
                    ghiNhatKyHeThong('kich_hoat', 'Kích hoạt tài khoản qua OTP', 'nguoi_dung',
                    $nguoi_dung['ma_nguoi_dung'], $nguoi_dung['ma_nguoi_dung']);
                    try {
                        guiMailSoDinhDanh($nguoi_dung['email'], $nguoi_dung['ho_ten'], $nguoi_dung['so_dinh_danh']);
                    } catch (Throwable $e) {
                        error_log('Gửi mail số định danh thất bại: ' . $e->getMessage());
                    }
                    $this->xoaPhienOtp();
                    chuyenHuong('nguoi_dung/dang_nhap');
                }
                if ($muc_dich === 'quen_mat_khau') {
                    $this->xoaPhienOtp();
                    $_SESSION['cho_dat_lai_mat_khau'] = $ma_nguoi_dung;
                    chuyenHuong('nguoi_dung/dat_lai_mat_khau');
                }
                if ($muc_dich === 'doi_mat_khau') {
                    if (!kiemTraDangNhap() || $ma_nguoi_dung !== (int)$_SESSION['ma_nguoi_dung']) {
                        $this->xoaPhienOtp();
                        chuyenHuong('nguoi_dung/dang_nhap');
                    }
                    $mat_khau_da_bam = $_SESSION['mat_khau_moi_da_bam'];
                    $this->xoaPhienOtp();
                    $this->nguoi_dung_model->capNhatMatKhau($ma_nguoi_dung, $mat_khau_da_bam);
                    ghiNhatKyHeThong('doi_mat_khau', 'Đổi mật khẩu', 'nguoi_dung', $ma_nguoi_dung);
                    session_regenerate_id(true);
                    $_SESSION['thong_bao'] = 'Đổi mật khẩu thành công.';
                    chuyenHuong('nguoi_dung/cai_dat');
                }
                $nguoi_dung = $this->nguoi_dung_model->layTheoMa($ma_nguoi_dung);
                $this->xoaPhienOtp();
                $this->nhat_ky_model->ghi($nguoi_dung['ma_nguoi_dung'], 'thanh_cong', layDiaChiIp());
                luuPhienDangNhap($nguoi_dung);
                chuyenHuong(layTrangChu($nguoi_dung['ma_vai_tro']));
            }

            $_SESSION['otp_so_lan_sai']++;
            if ($_SESSION['otp_so_lan_sai'] >= self::SO_LAN_NHAP_OTP_TOI_DA) {
                if ($muc_dich === 'dang_nhap') {
                    $this->nhat_ky_model->ghi($_SESSION['otp_ma_nguoi_dung'], 'that_bai', layDiaChiIp());
                }
                $this->xoaPhienOtp();
                chuyenHuong(kiemTraDangNhap() ? 'nguoi_dung/cai_dat' : 'nguoi_dung/dang_nhap');
            }
            $thong_bao_loi = 'Mã OTP không đúng hoặc đã hết hạn.';
        }
        $email_che = cheEmail($email);
        require __DIR__ . '/../views/nguoi_dung/xac_thuc_otp.php';
    }

    public function guiLaiOtp() {
        $email     = $_SESSION['otp_email'] ?? '';
        $khong_gui = ($_SESSION['otp_muc_dich'] ?? '') === 'quen_mat_khau' && empty($_SESSION['otp_ma_nguoi_dung']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email !== '' && !$khong_gui) {
            kiemTraMaCsrf();
            $da_cho = time() - ($_SESSION['otp_gui_luc'] ?? 0);
            if ($da_cho < self::GIAY_CHO_GUI_LAI_OTP) {
                $_SESSION['loi_gui_mail'] = 'Vui lòng đợi ' . (self::GIAY_CHO_GUI_LAI_OTP - $da_cho) . ' giây để gửi lại.';
            } elseif (!$this->guiOtpQuaEmail($email)) {
                $_SESSION['loi_gui_mail'] = 'Chưa gửi được email OTP. Vui lòng thử lại.';
            }
        }
        chuyenHuong('nguoi_dung/xac_thuc_otp');
    }

        public function caiDat() {
        yeuCauVaiTro([VAI_TRO_CONG_DAN, VAI_TRO_CAN_BO, VAI_TRO_QUAN_TRI]);
        require __DIR__ . '/../views/nguoi_dung/cai_dat.php';
    }

    public function doiMatKhau() {
        yeuCauVaiTro([VAI_TRO_CONG_DAN, VAI_TRO_CAN_BO, VAI_TRO_QUAN_TRI]);
        $ds_loi = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $mat_khau_cu       = $_POST['mat_khau_cu'] ?? '';
            $mat_khau_moi      = $_POST['mat_khau_moi'] ?? '';
            $nhap_lai_mat_khau = $_POST['nhap_lai_mat_khau'] ?? '';
            $nguoi_dung        = $this->nguoi_dung_model->layTheoMa($_SESSION['ma_nguoi_dung']);

            if (!password_verify($mat_khau_cu, $nguoi_dung['mat_khau'])) {
                if ($this->nguoi_dung_model->tangSoLanSai($nguoi_dung['ma_nguoi_dung']) >= SO_LAN_SAI_TOI_DA) {
                    $_SESSION = [];
                    session_destroy();
                    chuyenHuong('nguoi_dung/dang_nhap');
                }
                $ds_loi[] = 'Mật khẩu hiện tại không đúng.';
            }
            if (strlen($mat_khau_moi) < 8) {
                $ds_loi[] = 'Mật khẩu mới tối thiểu 8 ký tự.';
            }
            if ($mat_khau_moi !== $nhap_lai_mat_khau) {
                $ds_loi[] = 'Mật khẩu nhập lại không khớp.';
            }
            if (!$ds_loi && $mat_khau_moi === $mat_khau_cu) {
                $ds_loi[] = 'Mật khẩu mới phải khác mật khẩu hiện tại.';
            }
            if (!$ds_loi && empty($nguoi_dung['email'])) {
                $ds_loi[] = 'Tài khoản chưa có email để nhận OTP.';
            }

            if (!$ds_loi) {
                if (!$this->guiOtpQuaEmail($nguoi_dung['email'])) {
                    $this->xoaPhienOtp();
                    $ds_loi[] = 'Không gửi được email OTP. Vui lòng thử lại sau.';
                } else {
                    $_SESSION['mat_khau_moi_da_bam'] = password_hash($mat_khau_moi, PASSWORD_DEFAULT);
                    $_SESSION['otp_muc_dich']        = 'doi_mat_khau';
                    $_SESSION['otp_ma_nguoi_dung']   = $nguoi_dung['ma_nguoi_dung'];
                    chuyenHuong('nguoi_dung/xac_thuc_otp');
                }
            }
        }
        require __DIR__ . '/../views/nguoi_dung/doi_mat_khau.php';
    }

    public function quenMatKhau() {
        if (kiemTraDangNhap()) {
            chuyenHuong(layTrangChu($_SESSION['ma_vai_tro']));
        }
        $ds_loi  = [];
        $du_lieu = ['so_dinh_danh' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            foreach ($du_lieu as $khoa => $_) {
                $du_lieu[$khoa] = trim($_POST[$khoa] ?? '');
            }
            if (!preg_match('/^\d{12}$/', $du_lieu['so_dinh_danh'])
                || !filter_var($du_lieu['email'], FILTER_VALIDATE_EMAIL)) {
                $ds_loi[] = 'Vui lòng nhập đúng số định danh (12 chữ số) và email.';
            } else {
                $nguoi_dung = $this->nguoi_dung_model->layTheoSoDinhDanh($du_lieu['so_dinh_danh']);
                $khop = $nguoi_dung
                    && $nguoi_dung['trang_thai'] === NguoiDung::TRANG_THAI_HOAT_DONG
                    && strcasecmp((string)$nguoi_dung['email'], $du_lieu['email']) === 0;

                if ($khop) {
                    $this->guiOtpQuaEmail($du_lieu['email']);
                } else {
                    $_SESSION['otp_email']      = $du_lieu['email'];
                    $_SESSION['otp_so_lan_sai'] = 0;
                    $_SESSION['otp_gui_luc']    = time();
                }
                $_SESSION['otp_muc_dich']      = 'quen_mat_khau';
                $_SESSION['otp_ma_nguoi_dung'] = $khop ? $nguoi_dung['ma_nguoi_dung'] : 0;
                chuyenHuong('nguoi_dung/xac_thuc_otp');
            }
        }
        require __DIR__ . '/../views/nguoi_dung/quen_mat_khau.php';
    }

    public function datLaiMatKhau() {
        $ma_nguoi_dung = (int)($_SESSION['cho_dat_lai_mat_khau'] ?? 0);
        if ($ma_nguoi_dung === 0) {
            chuyenHuong('nguoi_dung/quen_mat_khau');
        }
        $ds_loi = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraMaCsrf();
            $mat_khau_moi      = $_POST['mat_khau_moi'] ?? '';
            $nhap_lai_mat_khau = $_POST['nhap_lai_mat_khau'] ?? '';
            if (strlen($mat_khau_moi) < 8) {
                $ds_loi[] = 'Mật khẩu mới tối thiểu 8 ký tự.';
            }
            if ($mat_khau_moi !== $nhap_lai_mat_khau) {
                $ds_loi[] = 'Mật khẩu nhập lại không khớp.';
            }
            if (!$ds_loi) {
                $this->nguoi_dung_model->capNhatMatKhau($ma_nguoi_dung, password_hash($mat_khau_moi, PASSWORD_DEFAULT));
                ghiNhatKyHeThong('dat_lai_mat_khau', 'Đặt lại mật khẩu qua quên mật khẩu',
                'nguoi_dung', $ma_nguoi_dung, $ma_nguoi_dung);
                unset($_SESSION['cho_dat_lai_mat_khau']);
                $_SESSION['thong_bao'] = 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.';
                chuyenHuong('nguoi_dung/dang_nhap');
            }
        }
        require __DIR__ . '/../views/nguoi_dung/dat_lai_mat_khau.php';
    }
}