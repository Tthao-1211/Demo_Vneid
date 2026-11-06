<?php
require_once __DIR__ . '/../models/NguoiDung.php';
require_once __DIR__ . '/../models/NhatKyHeThong.php';
require_once __DIR__ . '/../models/ThongBao.php';

class QuanTriController {
    const SO_DONG_MOI_TRANG = 10;

    private $pdo;
    private $nguoi_dung_model;
    private $nhat_ky_model;
    private $thong_bao_model;

    public function __construct($pdo) {
        yeuCauVaiTro([VAI_TRO_QUAN_TRI]);
        $this->pdo              = $pdo;
        $this->nguoi_dung_model = new NguoiDung($pdo);
        $this->nhat_ky_model    = new NhatKyHeThong($pdo);
        $this->thong_bao_model  = new ThongBao($pdo);
    }

    public function tongQuan() {
        $ds_thong_ke_trang_thai = $this->nguoi_dung_model->thongKeTheoTrangThai();
        $ds_thong_ke_vai_tro    = $this->nguoi_dung_model->thongKeTheoVaiTro();
        $ds_thong_ke_dang_nhap  = $this->nhat_ky_model->thongKeTheoHanhDong();
        require __DIR__ . '/../views/quan_tri/tong_quan.php';
    }

    public function danhSachNguoiDung() {
        $tu_khoa    = trim($_GET['tu_khoa'] ?? '');
        $trang_thai = $_GET['trang_thai'] ?? '';
        $ma_vai_tro = $_GET['ma_vai_tro'] ?? '';
        if (!in_array($trang_thai, ['', 'hoat_dong', 'bi_khoa', 'cho_kich_hoat'], true)) {
            $trang_thai = '';
        }
        if (!in_array($ma_vai_tro, ['', '1', '2', '3'], true)) {
            $ma_vai_tro = '';
        }
        $trang_hien_tai = max(1, (int)($_GET['trang'] ?? 1));

        $tong_so    = $this->nguoi_dung_model->demTimKiem($tu_khoa, $trang_thai, $ma_vai_tro);
        $tong_trang = max(1, (int)ceil($tong_so / self::SO_DONG_MOI_TRANG));
        $trang_hien_tai = min($trang_hien_tai, $tong_trang);
        $vi_tri     = ($trang_hien_tai - 1) * self::SO_DONG_MOI_TRANG;

        $ds_nguoi_dung = $this->nguoi_dung_model->timKiem(
            $tu_khoa, $trang_thai, $ma_vai_tro, $vi_tri, self::SO_DONG_MOI_TRANG);

        $chuoi_loc = http_build_query(array_filter(
            ['tu_khoa' => $tu_khoa, 'trang_thai' => $trang_thai, 'ma_vai_tro' => $ma_vai_tro],
            function ($gia_tri) { return $gia_tri !== ''; }
        ));
        require __DIR__ . '/../views/quan_tri/danh_sach_nguoi_dung.php';
    }

    public function khoa()    { $this->doiTrangThai(NguoiDung::TRANG_THAI_BI_KHOA); }
    public function moKhoa()  { $this->doiTrangThai(NguoiDung::TRANG_THAI_HOAT_DONG); }

    public function doiVaiTro() {
        $this->chiChoPhepPost();
        $ma_nguoi_dung = (int)($_POST['ma_nguoi_dung'] ?? 0);
        $ma_vai_tro    = (int)($_POST['ma_vai_tro'] ?? 0);

        if (!$this->nguoi_dung_model->layTheoMa($ma_nguoi_dung)
            || !in_array($ma_vai_tro, [VAI_TRO_CONG_DAN, VAI_TRO_CAN_BO, VAI_TRO_QUAN_TRI], true)) {
            $this->baoKetQua('Dữ liệu không hợp lệ.');
        }
        if ($ma_nguoi_dung === (int)$_SESSION['ma_nguoi_dung']) {
            $this->baoKetQua('Không thể đổi vai trò của chính bạn.');
        }
        $this->nguoi_dung_model->capNhatVaiTro($ma_nguoi_dung, $ma_vai_tro);
        $ten_vai_tro = [
            1 => 'Công dân',
            2 => 'Cán bộ',
            3 => 'Quản trị'
        ];
        $chuoi_vai_tro = $ten_vai_tro[$ma_vai_tro] ?? 'Khác';
        $this->nhat_ky_model->ghi(
            $_SESSION['ma_nguoi_dung'],
            'doi_vai_tro',
            layDiaChiIp(),
            'Đổi vai trò của người dùng #' . $ma_nguoi_dung . ' thành ' . $chuoi_vai_tro
        );
        $this->baoKetQua('Đã cập nhật vai trò.');
    }

    public function nhatKyHeThong() {
        $hanh_dong = $_GET['hanh_dong'] ?? '';
        if ($hanh_dong !== '' && !isset(NhatKyHeThong::NHAN_HANH_DONG[$hanh_dong])) {
            $hanh_dong = '';
        }
        $tu_khoa = trim($_GET['tu_khoa'] ?? '');
        $kiem_tra_ngay = function ($ngay) {
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngay) ? $ngay : '';
        };
        $tu_ngay  = $kiem_tra_ngay($_GET['tu_ngay'] ?? '');
        $den_ngay = $kiem_tra_ngay($_GET['den_ngay'] ?? '');

        $so_dong        = 20;
        $tong_so        = $this->nhat_ky_model->dem($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay);
        $tong_trang     = max(1, (int)ceil($tong_so / $so_dong));
        $trang_hien_tai = min($tong_trang, max(1, (int)($_GET['trang'] ?? 1)));
        $ds_nhat_ky     = $this->nhat_ky_model->timKiem(
            $hanh_dong, $tu_khoa, $tu_ngay, $den_ngay, ($trang_hien_tai - 1) * $so_dong, $so_dong);

        $chuoi_loc = http_build_query(array_filter(
            ['hanh_dong' => $hanh_dong, 'tu_khoa' => $tu_khoa, 'tu_ngay' => $tu_ngay, 'den_ngay' => $den_ngay],
            function ($gia_tri) { return $gia_tri !== ''; }
        ));
        require __DIR__ . '/../views/quan_tri/nhat_ky_he_thong.php';
    }

    private function doiTrangThai($trang_thai_moi) {
        $this->chiChoPhepPost();
        $ma_nguoi_dung = (int)($_POST['ma_nguoi_dung'] ?? 0);
        $ly_do         = trim($_POST['ly_do'] ?? '');
        $nguoi_dung    = $this->nguoi_dung_model->layTheoMa($ma_nguoi_dung);

        if (!$nguoi_dung) {
            $this->baoKetQua('Không tìm thấy người dùng.');
        }
        if ($ma_nguoi_dung === (int)$_SESSION['ma_nguoi_dung']) {
            $this->baoKetQua('Không thể khóa chính tài khoản của bạn.');
        }
        if ($nguoi_dung['trang_thai'] === $trang_thai_moi) {
            $this->baoKetQua('Tài khoản đã ở trạng thái này.');
        }

        $this->nguoi_dung_model->capNhatTrangThai($ma_nguoi_dung, $trang_thai_moi);

        if ($trang_thai_moi === NguoiDung::TRANG_THAI_BI_KHOA) {
            $this->thong_bao_model->themMoi($ma_nguoi_dung, 'bao_mat', 'Tài khoản bị khóa',
                'Quản trị viên đã khóa tài khoản của bạn.' . ($ly_do !== '' ? ' Lý do: ' . $ly_do : ''));

            $this->nhat_ky_model->ghi(
                $_SESSION['ma_nguoi_dung'],
                'khoa_tai_khoan',
                layDiaChiIp(),
                'Khóa tài khoản #' . $ma_nguoi_dung . ($ly_do !== '' ? '. Lý do: ' . $ly_do : '')
            );

            $this->baoKetQua('Đã khóa tài khoản ' . $nguoi_dung['ho_ten'] . '.');
        } else {
            $this->thong_bao_model->themMoi($ma_nguoi_dung, 'bao_mat', 'Tài khoản đã được mở',
                'Tài khoản của bạn đã được kích hoạt hoặc mở khóa.');

            $this->nhat_ky_model->ghi(
                $_SESSION['ma_nguoi_dung'],
                'mo_khoa_tai_khoan',
                layDiaChiIp(),
                'Mở khóa tài khoản #' . $ma_nguoi_dung
            );

            $this->baoKetQua('Đã mở khóa tài khoản ' . $nguoi_dung['ho_ten'] . '.');
        }
    }

    private function chiChoPhepPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được hỗ trợ');
        }
        kiemTraMaCsrf();
    }

    private function baoKetQua($thong_bao) {
        $_SESSION['thong_bao'] = $thong_bao;
        $quay_lai = $_POST['quay_lai'] ?? '';
        $chuoi = preg_match('/^[A-Za-z0-9_=&%+.\-]*$/', $quay_lai) ? $quay_lai : '';
        chuyenHuong('quan_tri/danh_sach_nguoi_dung' . ($chuoi !== '' ? '?' . $chuoi : ''));
    }

    public function baoCaoThongKe() {
        $kiem_tra_ngay = function ($ngay) {
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$ngay) ? $ngay : '';
        };
        $tu_ngay  = $kiem_tra_ngay($_GET['tu_ngay'] ?? '')  ?: date('Y-m-01');
        $den_ngay = $kiem_tra_ngay($_GET['den_ngay'] ?? '') ?: date('Y-m-d');

        $den_ngay_full = $den_ngay . ' 23:59:59';

        $dieu_kien_hoat_dong =
            "nd.trang_thai = 'hoat_dong'
            AND nd.ngay_tao BETWEEN :tu_ngay AND :den_ngay";
        $tham_so = [
            ':tu_ngay'  => $tu_ngay . ' 00:00:00',
            ':den_ngay' => $den_ngay_full,
        ];

        $stmt_muc = $this->pdo->prepare(
            "SELECT nd.muc_dinh_danh, COUNT(*) AS so_luong
            FROM nguoi_dung nd
            WHERE $dieu_kien_hoat_dong
            GROUP BY nd.muc_dinh_danh
            ORDER BY nd.muc_dinh_danh"
        );
        $stmt_muc->execute($tham_so);
        $ds_theo_muc = $stmt_muc->fetchAll(PDO::FETCH_ASSOC);

        $ds_theo_tinh = $this->pdo->query(
            "SELECT COALESCE(tc.tinh_thanh, 'Chưa cập nhật') AS tinh_thanh, COUNT(*) AS so_luong
            FROM nguoi_dung nd
            LEFT JOIN the_can_cuoc tc ON tc.ma_nguoi_dung = nd.ma_nguoi_dung
            GROUP BY COALESCE(tc.tinh_thanh, 'Chưa cập nhật')
            ORDER BY so_luong DESC"
        )->fetchAll(PDO::FETCH_ASSOC);

        $stmt_dv = $this->pdo->prepare(
            "SELECT dv.ten_dich_vu, COUNT(hs.ma_ho_so) AS tong,
                    COALESCE(SUM(hs.trang_thai = 'da_duyet'), 0) AS da_duyet,
                    COALESCE(SUM(hs.trang_thai = 'tu_choi'), 0) AS tu_choi,
                    COALESCE(SUM(hs.trang_thai IN ('da_nop','dang_xu_ly','can_bo_sung')), 0) AS dang_cho
             FROM dich_vu dv
             LEFT JOIN ho_so_dich_vu hs ON hs.ma_dich_vu = dv.ma_dich_vu
                  AND DATE(hs.ngay_nop) BETWEEN ? AND ?
             GROUP BY dv.ma_dich_vu, dv.ten_dich_vu
             ORDER BY dv.ma_dich_vu"
        );
        $stmt_dv->execute([$tu_ngay, $den_ngay]);
        $ds_theo_dich_vu = $stmt_dv->fetchAll(PDO::FETCH_ASSOC);

        $stmt_dn = $this->pdo->prepare(
            "SELECT DATE(thoi_gian) AS ngay,
                    SUM(hanh_dong = 'thanh_cong') AS thanh_cong,
                    SUM(hanh_dong = 'that_bai') AS that_bai
             FROM nhat_ky_he_thong
             WHERE DATE(thoi_gian) BETWEEN ? AND ?
             GROUP BY DATE(thoi_gian)
             ORDER BY ngay"
        );
        $stmt_dn->execute([$tu_ngay, $den_ngay]);
        $ds_dang_nhap_7_ngay = $stmt_dn->fetchAll(PDO::FETCH_ASSOC);

        $stmt_ds = $this->pdo->prepare(
            "SELECT nd.ma_nguoi_dung, nd.so_dinh_danh, nd.ho_ten, nd.so_dien_thoai,
                    nd.muc_dinh_danh, nd.ngay_tao,
                    COALESCE(tc.tinh_thanh, 'Chưa cập nhật') AS tinh_thanh
             FROM nguoi_dung nd
             LEFT JOIN the_can_cuoc tc ON tc.ma_nguoi_dung = nd.ma_nguoi_dung
             WHERE $dieu_kien_hoat_dong
             ORDER BY nd.ngay_tao"
        );
        $stmt_ds->execute($tham_so);
        $ds_tai_khoan_hoat_dong = $stmt_ds->fetchAll(PDO::FETCH_ASSOC);

        $dem_loai = $this->pdo
            ->query("SELECT ma_loai, COUNT(*) AS so_luong FROM giay_to_vi GROUP BY ma_loai")
            ->fetchAll(PDO::FETCH_KEY_PAIR);
        $tk_giay_to = [
            'cccd'       => (int)$this->pdo->query("SELECT COUNT(*) FROM the_can_cuoc")->fetchColumn(),
            'gplx'       => (int)($dem_loai[1] ?? 0),
            'bhyt'       => (int)($dem_loai[2] ?? 0),
            'dang_ky_xe' => (int)($dem_loai[3] ?? 0),
            'ho_chieu'   => (int)($dem_loai[4] ?? 0),
        ];

        require __DIR__ . '/../views/quan_tri/bao_cao_thong_ke.php';
    }
}