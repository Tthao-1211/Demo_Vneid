<?php
require_once DUONG_DAN_APP . '/models/GiayToVi.php';
require_once DUONG_DAN_APP . '/models/TheCanCuoc.php';
require_once DUONG_DAN_APP . '/models/ThongBao.php';
require_once __DIR__ . '/../helpers/giaoDien.php';
require_once __DIR__ . '/../helpers/giayToVi.php';

class GiayToViController {
    const THOI_GIAN_XEM_THE = 120;
    const SO_LAN_SAI_TOI_DA = 5;      
    const THOI_GIAN_KHOA_GIAY = 300;  

    private $mo_hinh_giay_to_vi;
    private $mo_hinh_the_can_cuoc;
    private $ket_noi;

    public function __construct(PDO $pdo) {
        yeuCauDangNhap();
        $this->ket_noi = $pdo;
        $this->mo_hinh_giay_to_vi = new GiayToVi($pdo);
        $this->mo_hinh_the_can_cuoc = new TheCanCuoc($pdo);
    }

    // Danh sách giấy tờ
    public function danhSach() {
        $ma_nguoi_dung = layMaNguoiDungHienTai();
        unset($_SESSION['da_xac_nhan_the']);
        $the_can_cuoc = $this->mo_hinh_the_can_cuoc->layTheoNguoiDung($ma_nguoi_dung);
        hienThiTrang('giay_to_vi/danh_sach', [
            'tieu_de_trang' => 'Ví giấy tờ',
            'muc_dang_chon' => 'giay_to_vi',
            'the_can_cuoc' => $the_can_cuoc,
            'so_the_che' => $the_can_cuoc
                ? $this->mo_hinh_the_can_cuoc->cheSoThe($the_can_cuoc['so_the'])
                : '',
            'ds_giay_to' => $this->mo_hinh_giay_to_vi->layDanhSachTheoNguoiDung($ma_nguoi_dung),
            'da_xac_nhan_the' => !empty($_SESSION['da_xac_nhan_the']),
        ]);
    }

    // Chi tiết giấy tờ
    public function chiTiet() {
        $giay_to = $this->mo_hinh_giay_to_vi->layTheoMa(
            (int) ($_GET['ma_giay_to'] ?? 0),
            layMaNguoiDungHienTai()
        );
        if (!$giay_to) {
            datPhanHoi('loi', 'Không tìm thấy giấy tờ.');
            chuyenHuong('giay_to_vi/danh_sach');
        }
        hienThiTrang('giay_to_vi/chi_tiet', [
            'tieu_de_trang' => $giay_to['ten_loai'],
            'muc_dang_chon' => 'giay_to_vi',
            'giay_to' => $giay_to,
        ]);
    }
    // Thêm giấy tờ
    public function themMoi() {
        $ma_nguoi_dung = layMaNguoiDungHienTai();
        $ds_loai = $this->mo_hinh_giay_to_vi->layDanhSachLoai();
        $ds_loi = [];
        $du_lieu_cu = [
            'ma_loai' => '',
            'so_giay_to' => '',
            'ten_chu_so_huu' => $_SESSION['ho_ten'] ?? '',
            'ngay_cap' => '',
            'ngay_het_han' => '',
            'noi_cap' => '',
            'thong_tin' => [],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraCsrf();
            $du_lieu_cu = [
                'ma_loai' => (int) ($_POST['ma_loai'] ?? 0),
                'so_giay_to' => trim($_POST['so_giay_to'] ?? ''),
                'ten_chu_so_huu' => trim($_POST['ten_chu_so_huu'] ?? ''),
                'ngay_cap' => trim($_POST['ngay_cap'] ?? ''),
                'ngay_het_han' => trim($_POST['ngay_het_han'] ?? ''),
                'noi_cap' => trim($_POST['noi_cap'] ?? ''),
                'thong_tin' => is_array($_POST['thong_tin'] ?? null)
                    ? $_POST['thong_tin'] : [],
            ];
            [$ds_loi, $thong_tin] = $this->kiemTraDuLieu($du_lieu_cu);
            if (empty($ds_loi)) {
                try {
                    $ma_giay_to = $this->mo_hinh_giay_to_vi->themMoi([
                        'ma_nguoi_dung' => $ma_nguoi_dung,
                        'ma_loai' => $du_lieu_cu['ma_loai'],
                        'so_giay_to' => $du_lieu_cu['so_giay_to'],
                        'ten_chu_so_huu' => $du_lieu_cu['ten_chu_so_huu'],
                        'ngay_cap' => $du_lieu_cu['ngay_cap'],
                        'ngay_het_han' => $du_lieu_cu['ngay_het_han'],
                        'noi_cap' => $du_lieu_cu['noi_cap'],
                        'thong_tin' => $thong_tin,
                    ]);
                    (new ThongBao($this->ket_noi))->themMoi(
                        $ma_nguoi_dung,
                        ThongBao::LOAI_HE_THONG,
                        'Đã thêm giấy tờ vào ví',
                        'Giấy tờ số ' . $du_lieu_cu['so_giay_to'] . ' đang chờ xác minh.'
                    );
                    (new ThongBao($this->ket_noi))->guiChoCanBo(
                        ThongBao::LOAI_HE_THONG,
                        'Có giấy tờ mới cần xác minh',
                        ($_SESSION['ho_ten'] ?? 'Công dân') . ' vừa thêm giấy tờ số ' . $du_lieu_cu['so_giay_to'] . '.'
                    );
                    datPhanHoi('thanh_cong', 'Đã thêm giấy tờ, đang chờ xác minh.');
                    chuyenHuong('giay_to_vi/chi_tiet?ma_giay_to=' . $ma_giay_to);
                } catch (PDOException $loi) {
                    if ($loi->getCode() === '23000') {
                        $ds_loi[] = 'Giấy tờ này đã có trong ví của bạn.';
                    } else {
                        throw $loi;
                    }
                }
            }
        }

        hienThiTrang('giay_to_vi/them_moi', [
            'tieu_de_trang' => 'Thêm giấy tờ',
            'muc_dang_chon' => 'giay_to_vi',
            'ds_loai' => $ds_loai,
            'ds_loi' => $ds_loi,
            'du_lieu_cu' => $du_lieu_cu,
            'js_them' => ['/public/js/giayToVi.js'],
        ]);
    }

    // Xóa giấy tờ
    public function xoa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được hỗ trợ.');
        }
        kiemTraCsrf();
        $da_xoa = $this->mo_hinh_giay_to_vi->xoa(
            (int) ($_POST['ma_giay_to'] ?? 0),
            layMaNguoiDungHienTai()
        );
        datPhanHoi(
            $da_xoa ? 'thanh_cong' : 'loi',
            $da_xoa ? 'Đã xóa giấy tờ khỏi ví.' : 'Không xóa được giấy tờ.'
        );
        chuyenHuong('giay_to_vi/danh_sach');
    }

    // Trả JSON cho yêu cầu AJAX
    private function traJson(array $du_lieu, $ma_http = 200) {
        http_response_code($ma_http);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($du_lieu, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Kiểm tra mật khẩu đăng nhập của người dùng hiện tại
    private function kiemTraMatKhau($ma_nguoi_dung, $mat_khau_nhap) {
        if ($mat_khau_nhap === '') return false;
        $stmt = $this->ket_noi->prepare(
            "SELECT mat_khau FROM nguoi_dung WHERE ma_nguoi_dung = ? LIMIT 1"
        );
        $stmt->execute([$ma_nguoi_dung]);
        $mat_khau_bam = $stmt->fetchColumn();
        return $mat_khau_bam && password_verify($mat_khau_nhap, $mat_khau_bam);
    }

    // Xem thẻ căn cước (xác nhận bằng mật khẩu đăng nhập)
    public function xemTheCanCuoc() {
        $la_ajax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
        $ma_nguoi_dung = layMaNguoiDungHienTai();
        $the_can_cuoc = $this->mo_hinh_the_can_cuoc->layTheoNguoiDung($ma_nguoi_dung);

        if (!$the_can_cuoc) {
            if ($la_ajax) $this->traJson(['thanh_cong' => false, 'thong_bao' => 'Bạn chưa có thẻ căn cước.'], 404);
            datPhanHoi('loi', 'Bạn chưa có thẻ căn cước.');
            chuyenHuong('giay_to_vi/danh_sach');
        }

        $thong_bao_loi = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraCsrf();

            // Chống dò mật khẩu
            if (($_SESSION['khoa_xem_the_den'] ?? 0) > time()) {
                $thong_bao_loi = 'Bạn nhập sai quá nhiều lần, vui lòng thử lại sau ít phút.';
                if ($la_ajax) $this->traJson(['thanh_cong' => false, 'thong_bao' => $thong_bao_loi], 429);
            } else {
                $mat_khau_nhap = (string) ($_POST['mat_khau'] ?? '');
                if ($this->kiemTraMatKhau($ma_nguoi_dung, $mat_khau_nhap)) {
                    unset($_SESSION['so_lan_sai_xem_the'], $_SESSION['khoa_xem_the_den']);
                    // Chỉ xác nhận cho lần xem hiện tại
                    $_SESSION['da_xac_nhan_the'] = true;
                    if ($la_ajax) {
                        $this->traJson([
                            'thanh_cong' => true,
                            'chuyen_huong' => '/giay_to_vi/xem_the_can_cuoc?da_xac_nhan=1'
                        ]);
                    }
                    chuyenHuong('giay_to_vi/xem_the_can_cuoc?da_xac_nhan=1');
                }
                $_SESSION['so_lan_sai_xem_the'] = ($_SESSION['so_lan_sai_xem_the'] ?? 0) + 1;
                if ($_SESSION['so_lan_sai_xem_the'] >= self::SO_LAN_SAI_TOI_DA) {
                    $_SESSION['khoa_xem_the_den'] = time() + self::THOI_GIAN_KHOA_GIAY;
                    $_SESSION['so_lan_sai_xem_the'] = 0;
                }
                $thong_bao_loi = 'Mật khẩu không đúng.';
                if ($la_ajax) $this->traJson(['thanh_cong' => false, 'thong_bao' => $thong_bao_loi], 422);
            }
        }

        $da_xac_nhan = (
            $_SERVER['REQUEST_METHOD'] === 'GET'
            && ($_GET['da_xac_nhan'] ?? '') === '1'
            && !empty($_SESSION['da_xac_nhan_the'])
        );
        unset($_SESSION['da_xac_nhan_the']);
        hienThiTrang('giay_to_vi/xem_the_can_cuoc', [
            'tieu_de_trang' => 'Thông tin thẻ căn cước',
            'an_tieu_de' => true,
            'muc_dang_chon' => 'giay_to_vi',
            'the_can_cuoc' => $the_can_cuoc,
            'da_xac_nhan' => $da_xac_nhan,
            'thong_bao_loi' => $thong_bao_loi,
        ]);
    }
    // Kiểm tra dữ liệu giấy tờ
    private function kiemTraDuLieu(array $du_lieu) {
        $ds_loi = [];
        $thong_tin = [];
        $loai = $this->mo_hinh_giay_to_vi->layLoaiTheoMa($du_lieu['ma_loai']);

        if (!$loai) $ds_loi[] = 'Vui lòng chọn loại giấy tờ.';

        if ($du_lieu['so_giay_to'] === '' || mb_strlen($du_lieu['so_giay_to']) > 30) {
            $ds_loi[] = 'Số giấy tờ bắt buộc và tối đa 30 ký tự.';
        }

        if ($du_lieu['ten_chu_so_huu'] === '' || mb_strlen($du_lieu['ten_chu_so_huu']) > 100) {
            $ds_loi[] = 'Tên chủ sở hữu bắt buộc và tối đa 100 ký tự.';
        }

        if (mb_strlen($du_lieu['noi_cap']) > 150) {
            $ds_loi[] = 'Nơi cấp tối đa 150 ký tự.';
        }

        $ngay_cap = $this->phanTichNgay($du_lieu['ngay_cap']);
        $ngay_het_han = $this->phanTichNgay($du_lieu['ngay_het_han']);

        if ($du_lieu['ngay_cap'] !== '' && !$ngay_cap) {
            $ds_loi[] = 'Ngày cấp không hợp lệ.';
        }

        if ($du_lieu['ngay_het_han'] !== '' && !$ngay_het_han) {
            $ds_loi[] = 'Ngày hết hạn không hợp lệ.';
        }

        if ($ngay_cap && $ngay_het_han && $ngay_het_han < $ngay_cap) {
            $ds_loi[] = 'Ngày hết hạn phải sau ngày cấp.';
        }

        if ($loai) {
            foreach ($loai['cac_truong'] as $ten_truong) {
                $gia_tri = trim((string)($du_lieu['thong_tin'][$ten_truong] ?? ''));

                if ($gia_tri === '' || mb_strlen($gia_tri) > 100) {
                    $ds_loi[] = layNhanTruong($ten_truong) . ' bắt buộc và tối đa 100 ký tự.';
                }

                $thong_tin[$ten_truong] = $gia_tri;
            }
        }

        return [$ds_loi, $thong_tin];
    }
    private function phanTichNgay($chuoi_ngay) {
        $ngay = DateTime::createFromFormat('Y-m-d', $chuoi_ngay);
        return ($ngay && $ngay->format('Y-m-d') === $chuoi_ngay) ? $chuoi_ngay : null;
    }
}