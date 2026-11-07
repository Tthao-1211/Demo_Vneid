<?php
require_once __DIR__ . '/../models/TheCanCuoc.php';
require_once __DIR__ . '/../models/ThongBao.php';
require_once __DIR__ . '/../models/NguoiDung.php';
class TheCanCuocController
{
    private PDO $pdo;
    private TheCanCuoc $model;
    public function __construct(PDO $pdo)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = $pdo;
        $this->model = new TheCanCuoc($pdo);
    }
    private function layMaNguoiDung(): int
    {
        return (int)($_SESSION['ma_nguoi_dung'] ?? 0);
    }
    private function layMaVaiTro(): int
    {
        return (int)($_SESSION['ma_vai_tro'] ?? 0);
    }
    private function kiemTraDangNhap(): void
    {
        if ($this->layMaNguoiDung() <= 0) {
            http_response_code(401);
            exit('Vui lòng đăng nhập để tiếp tục.');
        }
    }
    private function kiemTraVaiTro(array $vaiTroDuocPhep): void
    {
        $this->kiemTraDangNhap();

        if (!in_array($this->layMaVaiTro(), $vaiTroDuocPhep, true)) {
            http_response_code(403);
            exit('Bạn không có quyền thực hiện chức năng này.');
        }
    }
    private function kiemTraCanBo(): void
    {
        $this->kiemTraVaiTro([2]);
    }
    private function kiemTraCSRF(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        $tokenSession = $_SESSION['csrf_token'] ?? '';
        if (
            $token === '' ||
            $tokenSession === '' ||
            !hash_equals($tokenSession, $token)
        ) {
            http_response_code(403);
            exit('Phiên làm việc không hợp lệ. Vui lòng tải lại trang.');
        }
    }
    private function taoCSRF(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function hienThi(string $view, array $duLieu = []): void
    {
        extract($duLieu, EXTR_SKIP);

        $csrf_token = $this->taoCSRF();

        require __DIR__ . '/../views/the_can_cuoc/' . $view . '.php';
    }
    private function chuyenHuong(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
    private function baoLoi(string $noiDung, int $maLoi = 400): void
    {
        http_response_code($maLoi);
        exit($noiDung);
    }
    private function layDuLieuForm(): array
    {
        return [
            'so_the' => trim($_POST['so_the'] ?? ''),
            'ngay_sinh' => $_POST['ngay_sinh'] ?? '',
            'gioi_tinh' => trim($_POST['gioi_tinh'] ?? ''),
            'quoc_tich' => trim($_POST['quoc_tich'] ?? ''),
            'dan_toc' => trim($_POST['dan_toc'] ?? ''),
            'ton_giao' => trim($_POST['ton_giao'] ?? ''),
            'que_quan' => trim($_POST['que_quan'] ?? ''),
            'noi_thuong_tru' => trim($_POST['noi_thuong_tru'] ?? ''),
            'tinh_thanh' => trim($_POST['tinh_thanh'] ?? ''),
            'dac_diem_nhan_dang' => trim($_POST['dac_diem_nhan_dang'] ?? ''),
            'anh_chan_dung' => trim($_POST['anh_chan_dung'] ?? ''),
            'ngay_cap' => $_POST['ngay_cap'] ?? '',
            'ngay_het_han' => $_POST['ngay_het_han'] ?? '',
            'noi_cap' => trim($_POST['noi_cap'] ?? ''),
            'trang_thai' => trim($_POST['trang_thai'] ?? '')
        ];
    }
    private function kiemTraDuLieu(array $duLieu): void
    {
        $batBuoc = [
            'so_the',
            'ngay_sinh',
            'gioi_tinh',
            'quoc_tich',
            'que_quan',
            'noi_thuong_tru',
            'ngay_cap',
            'ngay_het_han',
            'noi_cap',
            'trang_thai'
        ];
        foreach ($batBuoc as $truong) {
            if (
                !isset($duLieu[$truong]) ||
                trim((string)$duLieu[$truong]) === ''
            ) {
                $this->baoLoi('Vui lòng nhập đầy đủ thông tin bắt buộc.');
            }
        }
        if (!preg_match('/^\d{12}$/', $duLieu['so_the'])) {
            $this->baoLoi('Số CCCD phải gồm đúng 12 chữ số.');
        }
        if (
            strtotime($duLieu['ngay_sinh']) === false ||
            strtotime($duLieu['ngay_cap']) === false ||
            strtotime($duLieu['ngay_het_han']) === false
        ) {
            $this->baoLoi('Ngày tháng không hợp lệ.');
        }
        if ($duLieu['ngay_het_han'] < $duLieu['ngay_cap']) {
            $this->baoLoi('Ngày hết hạn phải sau ngày cấp.');
        }
    }
    private function layTheCuaToi(): array
    {
        $maNguoiDung = $this->layMaNguoiDung();

        if ($maNguoiDung <= 0) {
            $this->baoLoi('Không xác định được tài khoản đăng nhập.', 401);
        }
        $the = $this->model->layTheoNguoiDung($maNguoiDung);
        if (!$the) {
            $this->baoLoi('Tài khoản chưa có thông tin thẻ căn cước.', 404);
        }
        if (
            (int)$the['ma_nguoi_dung'] !== $maNguoiDung
        ) {
            $this->baoLoi('Bạn không có quyền xem thẻ này.', 403);
        }
        return $the;
    }
    public function chiTiet(): void
    {
        $this->kiemTraVaiTro([1, 2, 3]);
        try {
            $the_can_cuoc = $this->layTheCuaToi();

            $this->hienThi('chi_tiet', [
                'the_can_cuoc' => $the_can_cuoc,
                'che_so_the' => $this->model->cheSoThe(
                    $the_can_cuoc['so_the']
                )
            ]);
        } catch (PDOException $e) {
            $this->baoLoi('Không thể tải thông tin căn cước.', 500);
        }
    }
    public function xemThongTin(): void
    {
        $this->chiTiet();
    }
    public function xemCuaToi(): void
    {
        $this->chiTiet();
    }
    
    public function danhSach(): void
    {
        $this->kiemTraCanBo();

        $soDong    = 10;   // số hồ sơ mỗi trang
        $tuKhoa    = trim($_GET['search'] ?? '');
        $trangThai = trim($_GET['trang_thai'] ?? '');
        $sapXep    = $_GET['sap_xep'] ?? 'moi_nhat';
        $trang     = max(1, (int)($_GET['trang'] ?? 1));
        $cacTrangThai = ['con_hieu_luc', 'het_han', 'bao_mat', 'thu_hoi'];

        if (!in_array($trangThai, $cacTrangThai, true)) {
            $trangThai = '';
        }

        $ds_the_can_cuoc = [];
        $search_error = '';
        $search_empty = false;
        $tong = 0;
        $tongTrang = 1;

        try {
            $from = " FROM the_can_cuoc t
                    INNER JOIN nguoi_dung u ON t.ma_nguoi_dung = u.ma_nguoi_dung";
            $dieuKien = [];
            $params = [];

            if ($tuKhoa !== '') {
                if (ctype_digit($tuKhoa) && strlen($tuKhoa) !== 12) {
                    $search_error = 'Số định danh phải gồm đúng 12 chữ số.';
                } else {
                    $dieuKien[] = "(t.so_the LIKE :tk1 OR u.ho_ten LIKE :tk2 OR u.so_dinh_danh LIKE :tk3)";
                    $like = '%' . $tuKhoa . '%';
                    $params[':tk1'] = $like;
                    $params[':tk2'] = $like;
                    $params[':tk3'] = $like;
                }
            }

            if ($trangThai !== '') {
                $dieuKien[] = "t.trang_thai = :trang_thai";
                $params[':trang_thai'] = $trangThai;
            }

            $where = $dieuKien ? ' WHERE ' . implode(' AND ', $dieuKien) : '';

            $orderBy = match ($sapXep) {
                'cu_nhat' => " ORDER BY t.ma_the ASC",
                'ten_az'  => " ORDER BY u.ho_ten ASC",
                default   => " ORDER BY t.ma_the DESC",
            };

            if ($search_error === '') {
                // Đếm tổng số bản ghi
                $stmt = $this->pdo->prepare("SELECT COUNT(*)" . $from . $where);
                $stmt->execute($params);
                $tong = (int)$stmt->fetchColumn();

                $tongTrang = max(1, (int)ceil($tong / $soDong));
                if ($trang > $tongTrang) {
                    $trang = $tongTrang;
                }
                $offset = ($trang - 1) * $soDong;

                // Lấy dữ liệu của trang hiện tại
                $sql = "SELECT t.ma_the, t.so_the, u.ho_ten, t.ngay_sinh, t.gioi_tinh, t.que_quan, t.trang_thai"
                    . $from . $where . $orderBy . " LIMIT :lim OFFSET :off";
                $stmt = $this->pdo->prepare($sql);
                foreach ($params as $k => $v) {
                    $stmt->bindValue($k, $v);
                }
                $stmt->bindValue(':lim', $soDong, PDO::PARAM_INT);
                $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
                $stmt->execute();

                $ds_the_can_cuoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $search_empty = ($tuKhoa !== '' || $trangThai !== '') && $tong === 0;
            }

            $this->hienThi('danh_sach', [
                'ds_the_can_cuoc' => $ds_the_can_cuoc,
                'search'          => $tuKhoa,
                'trang_thai_loc'  => $trangThai,
                'sap_xep'         => $sapXep,
                'search_error'    => $search_error,
                'search_empty'    => $search_empty,
                'trang'           => $trang,
                'tong_trang'      => $tongTrang,
                'tong'            => $tong,
                'so_dong'         => $soDong,
            ]);
        } catch (PDOException $e) {
            error_log('danhSach: ' . $e->getMessage());
            $this->baoLoi('Không thể tải danh sách hồ sơ căn cước.', 500);
        }
    }

    public function xemHoSo(): void
    {
        $this->kiemTraCanBo();

        $maThe = (int)($_GET['id'] ?? 0);

        if ($maThe <= 0) {
            $this->baoLoi('Mã hồ sơ không hợp lệ.');
        }

        try {
            $the_can_cuoc = $this->model->layTheoMa($maThe);

            if (!$the_can_cuoc) {
                $this->baoLoi('Không tìm thấy hồ sơ căn cước.', 404);
            }

            $this->hienThi('chi_tiet', [
                'the_can_cuoc' => $the_can_cuoc,
                'che_so_the' => $this->model->cheSoThe(
                    $the_can_cuoc['so_the']
                )
            ]);
        } catch (PDOException $e) {
            $this->baoLoi('Không thể tải hồ sơ căn cước.', 500);
        }
    }
    public function themMoi(): void
    {
        $this->kiemTraCanBo();

        $loi = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->kiemTraCSRF();

            $maNguoiDung = (int)($_POST['ma_nguoi_dung'] ?? 0);

            if ($maNguoiDung <= 0) {
                $this->baoLoi('Vui lòng chọn tài khoản công dân.');
            }

            $duLieu = $this->layDuLieuForm();
            $duLieu['ma_nguoi_dung'] = $maNguoiDung;

            try {
                $this->kiemTraDuLieu($duLieu);

                $ketQua = $this->model->themMoi($duLieu);

                if ($ketQua) {
                    $this->chuyenHuong('/the_can_cuoc/danh_sach');
                }

                $loi = 'Không thể thêm hồ sơ căn cước.';
            } catch (PDOException $e) {
                $loi = 'Không thể lưu hồ sơ. Vui lòng kiểm tra dữ liệu.';
            }
        }

        $ds_nguoi_dung = [];
    try {
        $stmt = $this->pdo->query(
            "SELECT n.ma_nguoi_dung, n.ho_ten
             FROM nguoi_dung n
             WHERE n.ma_nguoi_dung NOT IN (SELECT ma_nguoi_dung FROM the_can_cuoc)
             ORDER BY n.ho_ten ASC"
        );
        $ds_nguoi_dung = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('themMoi ds_nguoi_dung: ' . $e->getMessage());
    }

    $this->hienThi('them_moi', [
        'loi'           => $loi,
        'ds_nguoi_dung' => $ds_nguoi_dung
    ]);
    }
    public function capNhat(): void
    {
        $this->kiemTraCanBo();

        $maThe = (int)($_GET['id'] ?? 0);

    // Mở từ yêu cầu điều chỉnh mà thiếu id thẻ: tự tìm thẻ theo công dân của yêu cầu
    if ($maThe <= 0 && (int)($_GET['yeu_cau'] ?? 0) > 0) {
        $stmt = $this->pdo->prepare(
            "SELECT t.ma_the
            FROM ho_so_dich_vu hs
            INNER JOIN the_can_cuoc t ON t.ma_nguoi_dung = hs.ma_nguoi_dung
            WHERE hs.ma_ho_so = :id
            LIMIT 1"
        );
        $stmt->execute([':id' => (int)$_GET['yeu_cau']]);
        $maThe = (int)$stmt->fetchColumn();

        if ($maThe <= 0) {
            $this->baoLoi('Công dân này chưa có thẻ căn cước để cập nhật.', 404);
        }
    }

    if ($maThe <= 0) {
        $this->baoLoi('Mã thẻ không hợp lệ.');
    }

        try {
            $the_can_cuoc = $this->model->layTheoMa($maThe);
            if (!$the_can_cuoc) {
                $this->baoLoi('Không tìm thấy hồ sơ căn cước.', 404);
            }

            // ----- Nếu mở từ yêu cầu điều chỉnh: lấy giá trị công dân đề nghị -----
            $maYeuCau = (int)($_GET['yeu_cau'] ?? 0);
            $deXuat = [];
            $noiDungYc = [];
            $truongSoSanh = ['ho_ten', 'ngay_sinh', 'gioi_tinh', 'quoc_tich', 'dan_toc', 'ton_giao',
                    'dac_diem_nhan_dang', 'que_quan', 'tinh_thanh', 'noi_thuong_tru'];
            if ($maYeuCau > 0) {
                $stmt = $this->pdo->prepare(
                    "SELECT ma_nguoi_dung, noi_dung, trang_thai FROM ho_so_dich_vu WHERE ma_ho_so = :id"
                );
                $stmt->execute([':id' => $maYeuCau]);
                $hs = $stmt->fetch(PDO::FETCH_ASSOC);

                $hopLe = $hs
                    && (int)$hs['ma_nguoi_dung'] === (int)$the_can_cuoc['ma_nguoi_dung']
                    && in_array($hs['trang_thai'], ['da_nop', 'dang_xu_ly'], true);

                if ($hopLe) {
                    $noiDungYc = json_decode($hs['noi_dung'] ?? '', true) ?: [];
                    foreach ($truongSoSanh as $k) {
                        $m = trim((string)($noiDungYc[$k . '_moi'] ?? $noiDungYc[$k] ?? ''));
                        if ($m !== '') {
                            $deXuat[$k] = $m;
                        }
                    }
                } else {
                    $maYeuCau = 0; // yêu cầu không hợp lệ hoặc đã xử lý: mở như cập nhật thường
                }
            }

            $loi = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->kiemTraCSRF();
                $duLieu = $this->layDuLieuForm();

                try {
                    $this->kiemTraDuLieu($duLieu);

                    if ($this->model->capNhat($maThe, $duLieu)) {
                        $hoTenMoi = trim($_POST['ho_ten'] ?? '');
        if ($hoTenMoi !== '' && mb_strlen($hoTenMoi, 'UTF-8') <= 100) {
            $maNguoiCuaThe = (int)$the_can_cuoc['ma_nguoi_dung'];

            $this->pdo->prepare(
                "UPDATE nguoi_dung SET ho_ten = :ht WHERE ma_nguoi_dung = :nd"
            )->execute([':ht' => $hoTenMoi, ':nd' => $maNguoiCuaThe]);

            // Nếu người được sửa chính là tài khoản đang đăng nhập thì làm mới tên trên menu
            if ($maNguoiCuaThe === $this->layMaNguoiDung()) {
                $_SESSION['ho_ten'] = $hoTenMoi;
            }
        }
                        if ($maYeuCau > 0) {
                            // Lưu bản gốc (thẻ trước khi sửa) để xem lại được phần thay đổi
                            $noiDungYc['__ban_goc'] = array_intersect_key(
                                $the_can_cuoc,
                                array_flip(array_merge(['ho_ten'], $truongSoSanh))
                            );
                            $this->pdo->prepare(
                                "UPDATE ho_so_dich_vu
                                SET trang_thai = 'da_duyet',
                                    ghi_chu = 'Đã cập nhật thông tin căn cước theo yêu cầu',
                                    noi_dung = :nd
                                WHERE ma_ho_so = :id AND trang_thai IN ('da_nop','dang_xu_ly')"
                            )->execute([
                                ':nd' => json_encode($noiDungYc, JSON_UNESCAPED_UNICODE),
                                ':id' => $maYeuCau,
                            ]);
                            $this->chuyenHuong('/the_can_cuoc/chi_tiet_yeu_cau_dieu_chinh?id=' . $maYeuCau);
                        }
                        $this->chuyenHuong('/the_can_cuoc/danh_sach');
                    }
                    $loi = 'Không thể cập nhật hồ sơ.';
                } catch (PDOException $e) {
                    $loi = 'Lỗi khi cập nhật dữ liệu căn cước.';
                }
            }

            $this->hienThi('cap_nhat', [
                'the_can_cuoc' => $the_can_cuoc,
                'loi'          => $loi,
                'de_xuat'      => $deXuat,
                'yeu_cau_id'   => $maYeuCau,
            ]);
        } catch (PDOException $e) {
            $this->baoLoi('Không thể tải hồ sơ cần cập nhật.', 500);
        }
    }
    public function quanLyTrangThai(): void
    {
        $this->kiemTraCanBo();

        $maThe = (int)($_GET['id'] ?? 0);

        try {
            if ($maThe <= 0) {
                $ds_the_can_cuoc = $this->model->danhSach();

                $this->hienThi('danh_sach_trang_thai', [
                    'ds_the_can_cuoc' => $ds_the_can_cuoc
                ]);

                return;
            }

            $the_can_cuoc = $this->model->layTheoMa($maThe);

            if (!$the_can_cuoc) {
                $this->baoLoi('Không tìm thấy thẻ căn cước.', 404);
            }

            $thong_bao = '';
            $loi = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->kiemTraCSRF();

                $trangThai = trim($_POST['trang_thai'] ?? '');

                $cacTrangThai = [
                    'con_hieu_luc',
                    'het_han',
                    'bao_mat',
                    'thu_hoi'
                ];

                if (!in_array($trangThai, $cacTrangThai, true)) {
                    $this->baoLoi('Trạng thái thẻ không hợp lệ.');
                }

                try {
                    if ($this->model->capNhatTrangThai($maThe, $trangThai)) {
                        $the_can_cuoc['trang_thai'] = $trangThai;
                        $thong_bao = 'Cập nhật trạng thái thẻ thành công.';
                    } else {
                        $loi = 'Không thể cập nhật trạng thái thẻ.';
                    }
                } catch (PDOException $e) {
                    $loi = 'Đã xảy ra lỗi khi cập nhật trạng thái.';
                }
            }

            $this->hienThi('quan_ly_trang_thai', [
                'the_can_cuoc' => $the_can_cuoc,
                'thong_bao' => $thong_bao,
                'loi' => $loi
            ]);
        } catch (PDOException $e) {
            $this->baoLoi('Không thể tải dữ liệu trạng thái thẻ.', 500);
        }
    }

    public function kichHoat(): void
    {
        $this->themMoi();
    }

    public function dieuChinh(): void
    {
        $this->capNhat();
    }
    public function yeuCauDieuChinh(): void
    {
        $this->kiemTraVaiTro([1,2,3]);
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $this->luuYeuCau('Điều chỉnh thông tin căn cước');
            return;
        }
        try{
            $the=$this->layTheCuaToi();
            $this->hienThi('de_nghi_dieu_chinh',['the'=>$the,'yeuCauDaGui'=>null]);
        }catch(PDOException $e){
            $this->baoLoi('Không thể tải trang đề nghị điều chỉnh.',500);
        }
    }
    public function yeu_cau_dieu_chinh(): void
    {
        $this->yeuCauDieuChinh();
    }

    public function yeuCauCapThe(): void
    {
        $this->kiemTraVaiTro([1,2,3]);
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $this->luuYeuCau('Cấp đổi thẻ căn cước');
            return;
        }
        try{
            $the=$this->layTheCuaToi();
            $this->hienThi('yeu_cau_cap_the',['the'=>$the,'yeuCauDaGui'=>null]);
        }catch(PDOException $e){
            $this->baoLoi('Không thể tải trang yêu cầu cấp thẻ.',500);
        }
    }
    public function yeu_cau_cap_the(): void
    {
        $this->yeuCauCapThe();
    }

    public function yeuCauCuaToi(): void
    {
        $this->kiemTraVaiTro([1, 2, 3]);

        $maNguoiDung = $this->layMaNguoiDung();

        try {
            $sql = "
                SELECT
                    hs.ma_ho_so,
                    hs.ma_nguoi_dung,
                    hs.ma_dich_vu,
                    dv.ten_dich_vu,
                    hs.noi_dung,
                    hs.trang_thai,
                    hs.ghi_chu,
                    hs.ngay_nop
                FROM ho_so_dich_vu hs
                INNER JOIN dich_vu dv
                    ON hs.ma_dich_vu = dv.ma_dich_vu
                WHERE hs.ma_nguoi_dung = :ma_nguoi_dung
                ORDER BY hs.ngay_nop DESC, hs.ma_ho_so DESC
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':ma_nguoi_dung' => $maNguoiDung
            ]);

            $dsYeuCau = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->hienThi('yeu_cau_cua_toi', [
                'dsYeuCau' => $dsYeuCau
            ]);

        } catch (PDOException $e) {
            $this->baoLoi(
                'Không thể tải danh sách yêu cầu của bạn.',
                500
            );
        }
    }

    public function yeu_cau_cua_toi(): void
    {
        $this->yeuCauCuaToi();
    }

    private function luuYeuCau(string $tenDichVu): void
    {
        $this->kiemTraVaiTro([1, 2, 3]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->baoLoi('Phương thức gửi yêu cầu không hợp lệ.', 405);
        }

        $this->kiemTraCSRF();

        $maNguoiDung = $this->layMaNguoiDung();

        // Lấy nội dung biểu mẫu, loại bỏ CSRF token
        $noiDung = $_POST;
        unset($noiDung['csrf_token']);

        if (empty($noiDung)) {
            $this->baoLoi('Vui lòng nhập thông tin yêu cầu.');
        }

        $noiDungJson = json_encode(
            $noiDung,
            JSON_UNESCAPED_UNICODE
        );

        if ($noiDungJson === false) {
            $this->baoLoi('Không thể xử lý nội dung yêu cầu.');
        }

        try {
            // Tìm dịch vụ tương ứng
            $sql = "
                SELECT ma_dich_vu
                FROM dich_vu
                WHERE ten_dich_vu = :ten_dich_vu
                LIMIT 1
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':ten_dich_vu' => $tenDichVu
            ]);

            $dichVu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dichVu) {
                $this->baoLoi(
                    'Dịch vụ "' . $tenDichVu . '" chưa có trong danh mục dịch vụ.',
                    400
                );
            }

            // Lưu yêu cầu vào hồ sơ dịch vụ
            $sql = "
                INSERT INTO ho_so_dich_vu
                (
                    ma_nguoi_dung,
                    ma_dich_vu,
                    noi_dung,
                    tep_dinh_kem,
                    trang_thai,
                    ngay_nop
                )
                VALUES
                (
                    :ma_nguoi_dung,
                    :ma_dich_vu,
                    :noi_dung,
                    NULL,
                    'da_nop',
                    NOW()
                )
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':ma_nguoi_dung' => $maNguoiDung,
                ':ma_dich_vu' => $dichVu['ma_dich_vu'],
                ':noi_dung' => $noiDungJson
            ]);

            $maHoSoMoi = (int)$this->pdo->lastInsertId();
            $this->ghiNhatKy('nop_ho_so', 'Nộp yêu cầu "' . $tenDichVu . '" #' . $maHoSoMoi, $maHoSoMoi);

            // Báo cho cán bộ có yêu cầu mới
            try {
                (new ThongBao($this->pdo))->guiChoCanBo(
                    ThongBao::LOAI_DICH_VU_CONG,
                    'Có yêu cầu căn cước mới',
                    ($_SESSION['ho_ten'] ?? 'Công dân') . ' vừa gửi yêu cầu "' . $tenDichVu . '" (hồ sơ #' . $maHoSoMoi . ').'
                );
            } catch (PDOException $e) {}

            // Gửi thành công thì chuyển đến danh sách yêu cầu
            $this->chuyenHuong('/the_can_cuoc/yeu_cau_cua_toi');

        } catch (PDOException $e) {
            $this->baoLoi(
                'Không thể lưu yêu cầu. Vui lòng kiểm tra dữ liệu và thử lại.',
                500
            );
        }
    }

    // Ghi nhật ký hệ thống; lỗi ghi log không được làm hỏng việc nộp / xử lý yêu cầu
    private function ghiNhatKy(string $hanhDong, string $noiDung, int $maHoSo): void
    {
        try {
            ghiNhatKyHeThong($hanhDong, $noiDung, 'ho_so_dich_vu', $maHoSo);
        } catch (Throwable $e) {
            error_log('Ghi nhật ký thất bại (' . $hanhDong . '): ' . $e->getMessage());
        }
    }

    // Gửi thông báo kết quả xử lý yêu cầu (cấp thẻ / điều chỉnh) cho công dân đã nộp
    private function guiThongBaoYeuCau(int $maHoSo, string $ketQua, string $ghiChu = ''): void
    {
        $nhan = [
            'dang_xu_ly' => 'đang được xử lý',
            'duyet'      => 'đã được duyệt',
            'bo_sung'    => 'cần bổ sung thông tin',
            'tu_choi'    => 'đã bị từ chối',
        ];
        if (!isset($nhan[$ketQua])) {
            return;
        }
        try {
            $stmt = $this->pdo->prepare(
                "SELECT hs.ma_nguoi_dung, dv.ten_dich_vu
                 FROM ho_so_dich_vu hs
                 INNER JOIN dich_vu dv ON hs.ma_dich_vu = dv.ma_dich_vu
                 WHERE hs.ma_ho_so = :id
                 LIMIT 1"
            );
            $stmt->execute([':id' => $maHoSo]);
            $hs = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$hs) {
                return;
            }
            $noiDung = 'Yêu cầu "' . $hs['ten_dich_vu'] . '" (#' . $maHoSo . ') của bạn ' . $nhan[$ketQua] . '.';
            if ($ghiChu !== '') {
                $noiDung .= ' Ghi chú của cán bộ: ' . $ghiChu;
            }
            (new ThongBao($this->pdo))->themMoi(
                (int)$hs['ma_nguoi_dung'],
                ThongBao::LOAI_DICH_VU_CONG,
                'Yêu cầu căn cước ' . $nhan[$ketQua],
                $noiDung
            );
        } catch (PDOException $e) {
            error_log('guiThongBaoYeuCau: ' . $e->getMessage());
        }
    }

    public function danhSachYeuCau(): void
    {
        $this->kiemTraCanBo();
        try {
            $sql = "SELECT hs.ma_ho_so,hs.ma_nguoi_dung,hs.noi_dung,hs.trang_thai,hs.ghi_chu,hs.ngay_nop,
                        nd.so_dinh_danh,nd.ho_ten,dv.ten_dich_vu
                    FROM ho_so_dich_vu hs
                    INNER JOIN nguoi_dung nd ON hs.ma_nguoi_dung=nd.ma_nguoi_dung
                    INNER JOIN dich_vu dv ON hs.ma_dich_vu=dv.ma_dich_vu
                    WHERE dv.ten_dich_vu IN ('Cấp đổi thẻ căn cước','Điều chỉnh thông tin căn cước')
                    ORDER BY hs.ngay_nop DESC";
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute();
            $ds_yeu_cau=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->hienThi('danh_sach_yeu_cau',$ds_yeu_cau ? ['ds_yeu_cau'=>$ds_yeu_cau] : ['ds_yeu_cau'=>[]]);
        } catch(PDOException $e) {
            $this->baoLoi('Không thể tải danh sách yêu cầu.',500);
        }
    }

    public function chiTietYeuCauDieuChinh(): void
    {
        $this->kiemTraCanBo();

        $maHoSo = (int)($_GET['id'] ?? 0);
        if ($maHoSo <= 0) {
            $this->baoLoi('Mã hồ sơ không hợp lệ.');
        }

        $thongBao = '';
        $loi = '';

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->kiemTraCSRF();

                $ketQua = $_POST['ket_qua'] ?? '';
                $ghiChu = trim($_POST['ghi_chu'] ?? '');
                $anhXa = [
                    'duyet'   => 'da_duyet',
                    'bo_sung' => 'can_bo_sung',
                    'tu_choi' => 'tu_choi',
                ];

                if (!isset($anhXa[$ketQua])) {
                    $this->baoLoi('Thao tác không hợp lệ.');
                }

                if ($ketQua !== 'duyet' && $ghiChu === '') {
                    $loi = 'Vui lòng nhập lý do từ chối hoặc nội dung cần bổ sung.';
                } else {
                    $stmt = $this->pdo->prepare(
                        "UPDATE ho_so_dich_vu
                        SET trang_thai = :tt, ghi_chu = :gc
                        WHERE ma_ho_so = :id AND trang_thai IN ('da_nop','dang_xu_ly')"
                    );
                    $stmt->execute([':tt' => $anhXa[$ketQua], ':gc' => $ghiChu, ':id' => $maHoSo]);
                    $daCapNhat = $stmt->rowCount() > 0;
                    $thongBao = $daCapNhat
                        ? 'Đã cập nhật kết quả xử lý yêu cầu.'
                        : 'Yêu cầu này đã được xử lý trước đó.';
                    if ($daCapNhat) {
                        $this->guiThongBaoYeuCau($maHoSo, $ketQua, $ghiChu);

                        $khoaNhatKy = [
                            'duyet'   => 'duyet_ho_so',
                            'bo_sung' => 'yeu_cau_bo_sung',
                            'tu_choi' => 'tu_choi_ho_so',
                        ][$ketQua];
                        $moTaNhatKy = [
                            'duyet'   => 'Duyệt yêu cầu căn cước',
                            'bo_sung' => 'Yêu cầu bổ sung yêu cầu căn cước',
                            'tu_choi' => 'Từ chối yêu cầu căn cước',
                        ][$ketQua];
                        $this->ghiNhatKy(
                            $khoaNhatKy,
                            $moTaNhatKy . ' #' . $maHoSo . ($ghiChu !== '' ? '. Ghi chú: ' . $ghiChu : ''),
                            $maHoSo
                        );
                    }
                }
            } else {
                // Mở xem chi tiết: tự chuyển "Đã nộp" -> "Đang xử lý"
                $stmt = $this->pdo->prepare(
                    "UPDATE ho_so_dich_vu SET trang_thai = 'dang_xu_ly'
                    WHERE ma_ho_so = :id AND trang_thai = 'da_nop'"
                );
                $stmt->execute([':id' => $maHoSo]);
                if ($stmt->rowCount() > 0) {
                    $this->guiThongBaoYeuCau($maHoSo, 'dang_xu_ly');
                }
            }

            $stmt = $this->pdo->prepare(
                "SELECT hs.ma_ho_so, hs.ma_nguoi_dung, hs.noi_dung, hs.trang_thai, hs.ghi_chu, hs.ngay_nop,
                        nd.so_dinh_danh, nd.ho_ten, dv.ten_dich_vu
                FROM ho_so_dich_vu hs
                INNER JOIN nguoi_dung nd ON hs.ma_nguoi_dung = nd.ma_nguoi_dung
                INNER JOIN dich_vu dv ON hs.ma_dich_vu = dv.ma_dich_vu
                WHERE hs.ma_ho_so = :id
                LIMIT 1"
            );
            $stmt->execute([':id' => $maHoSo]);
            $yeuCau = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$yeuCau) {
                $this->baoLoi('Không tìm thấy yêu cầu.', 404);
            }

            $stmt = $this->pdo->prepare(
        "SELECT t.*, nd.ho_ten
        FROM the_can_cuoc t
        INNER JOIN nguoi_dung nd ON t.ma_nguoi_dung = nd.ma_nguoi_dung
        WHERE t.ma_nguoi_dung = :nd LIMIT 1"
    );
    $stmt->execute([':nd' => $yeuCau['ma_nguoi_dung']]);
    $theHienTai = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            $this->hienThi('chi_tiet_yeu_cau_dieu_chinh', [
                'yeu_cau'   => $yeuCau,
                'noi_dung'  => json_decode($yeuCau['noi_dung'] ?? '', true) ?: [],
                'the_hien_tai' => $theHienTai,
                'thong_bao' => $thongBao,
                'loi'       => $loi,
            ]);
        } catch (PDOException $e) {
            error_log('chiTietYeuCauDieuChinh: ' . $e->getMessage());
            $this->baoLoi('Không thể tải chi tiết yêu cầu.', 500);
        }
    }
}