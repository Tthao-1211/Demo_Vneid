<?php
require_once DUONG_DAN_APP . '/models/ThongBao.php';
class ThongBaoController {
    private $mo_hinh_thong_bao;

    public function __construct(PDO $pdo) {
        yeuCauDangNhap();
        $this->mo_hinh_thong_bao = new ThongBao($pdo);
    }

    // GET /thong_bao/danh_sach?loai=he_thong&chua_doc=1&trang=2
    public function danhSach() {
        $ma_nguoi_dung = layMaNguoiDungHienTai();
        $loai          = $_GET['loai'] ?? '';
        if (!in_array($loai, ['he_thong', 'dich_vu_cong', 'bao_mat'], true)) {
            $loai = '';
        }
        $chi_chua_doc = ($_GET['chua_doc'] ?? '') === '1';
        $trang        = max(1, (int) ($_GET['trang'] ?? 1));

        $tong_so    = $this->mo_hinh_thong_bao->demTheoNguoiDung($ma_nguoi_dung, $loai, $chi_chua_doc);
        $tong_trang = max(1, (int) ceil($tong_so / ThongBao::SO_DONG_MOI_TRANG));
        $trang      = min($trang, $tong_trang);

        hienThiTrang('thong_bao/danh_sach', [
            'tieu_de_trang' => 'Thông báo',
            'an_tieu_de'    => true,
            'muc_dang_chon' => 'thong_bao',
            'ds_thong_bao'  => $this->mo_hinh_thong_bao->layDanhSachTheoNguoiDung($ma_nguoi_dung, $loai, $chi_chua_doc, $trang),
            'loai'          => $loai,
            'chi_chua_doc'  => $chi_chua_doc,
            'trang'         => $trang,
            'tong_trang'    => $tong_trang,
        ]);
    }

    // GET /thong_bao/chi_tiet?ma_thong_bao=5  (xem là tự đánh dấu đã đọc)
    public function chiTiet() {
        $ma_nguoi_dung = layMaNguoiDungHienTai();
        $ma_thong_bao  = (int) ($_GET['ma_thong_bao'] ?? 0);
        $thong_bao     = $this->mo_hinh_thong_bao->layTheoMa($ma_thong_bao, $ma_nguoi_dung);

        if (!$thong_bao) {
            datPhanHoi('loi', 'Không tìm thấy thông báo.');
            chuyenHuong('thong_bao/danh_sach');
        }
        if (!$thong_bao['da_doc']) {
            $this->mo_hinh_thong_bao->danhDauDaDoc($ma_thong_bao, $ma_nguoi_dung);
        }

        hienThiTrang('thong_bao/chi_tiet', [
            'tieu_de_trang' => 'Chi tiết thông báo',
            'muc_dang_chon' => 'thong_bao',
            'thong_bao'     => $thong_bao,
        ]);
    }

    // POST /thong_bao/danh_dau_da_doc
    public function danhDauDaDoc() {
        $this->chiChapNhanPost();
        $this->mo_hinh_thong_bao->danhDauDaDoc((int) ($_POST['ma_thong_bao'] ?? 0), layMaNguoiDungHienTai());
        chuyenHuong('thong_bao/danh_sach');
    }

    // POST /thong_bao/danh_dau_tat_ca_da_doc
    public function danhDauTatCaDaDoc() {
        $this->chiChapNhanPost();
        $so_dong = $this->mo_hinh_thong_bao->danhDauTatCaDaDoc(layMaNguoiDungHienTai());
        datPhanHoi('thanh_cong', "Đã đánh dấu $so_dong thông báo là đã đọc.");
        chuyenHuong('thong_bao/danh_sach');
    }

    // POST /thong_bao/xoa
    public function xoa() {
        $this->chiChapNhanPost();
        $da_xoa = $this->mo_hinh_thong_bao->xoa((int) ($_POST['ma_thong_bao'] ?? 0), layMaNguoiDungHienTai());
        datPhanHoi($da_xoa ? 'thanh_cong' : 'loi', $da_xoa ? 'Đã xóa thông báo.' : 'Không xóa được thông báo.');
        chuyenHuong('thong_bao/danh_sach');
    }

    // GET/POST /thong_bao/gui 
    public function gui() {
        yeuCauVaiTro([VAI_TRO_QUAN_TRI]);
        $thong_bao_loi = '';
        $du_lieu_cu    = ['loai' => 'he_thong', 'tieu_de' => '', 'noi_dung' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            kiemTraCsrf();
            $du_lieu_cu = [
                'loai'     => $_POST['loai'] ?? 'he_thong',
                'tieu_de'  => trim($_POST['tieu_de'] ?? ''),
                'noi_dung' => trim($_POST['noi_dung'] ?? ''),
            ];

            if (!in_array($du_lieu_cu['loai'], ['he_thong', 'dich_vu_cong', 'bao_mat'], true)) {
                $thong_bao_loi = 'Loại thông báo không hợp lệ.';
            } elseif ($du_lieu_cu['tieu_de'] === '' || mb_strlen($du_lieu_cu['tieu_de']) > 150) {
                $thong_bao_loi = 'Tiêu đề bắt buộc và tối đa 150 ký tự.';
            } elseif ($du_lieu_cu['noi_dung'] === '') {
                $thong_bao_loi = 'Vui lòng nhập nội dung.';
            } else {
                $so_nguoi_nhan = $this->mo_hinh_thong_bao->guiHangLoat(
                    $du_lieu_cu['loai'], $du_lieu_cu['tieu_de'], $du_lieu_cu['noi_dung'], layMaNguoiDungHienTai()
                );
                datPhanHoi('thanh_cong', "Đã gửi thông báo tới $so_nguoi_nhan người dùng.");
                chuyenHuong('thong_bao/danh_sach');
            }
        }

        hienThiTrang('thong_bao/gui', [
            'tieu_de_trang'  => 'Gửi thông báo hệ thống',
            'muc_dang_chon'  => 'thong_bao',
            'thong_bao_loi'  => $thong_bao_loi,
            'du_lieu_cu'     => $du_lieu_cu,
        ]);
    }

    private function chiChapNhanPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được hỗ trợ.');
        }
        kiemTraCsrf();
    }
}