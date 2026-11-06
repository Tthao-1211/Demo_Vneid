<?php
// app/models/TheCanCuoc.php

class TheCanCuoc {
    const TRANG_THAI_CON_HIEU_LUC = 'con_hieu_luc';
    const TRANG_THAI_HET_HAN     = 'het_han';
    const TRANG_THAI_BAO_MAT      = 'bao_mat';
    const TRANG_THAI_THU_HOI      = 'thu_hoi';

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->khoiTaoBangYeuCauCapThe();
        $this->khoiTaoBangLichSuTrangThai();
        $this->khoiTaoBangYeuCauDieuChinh();
    }

    private function khoiTaoBangYeuCauDieuChinh() {
        try {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS yeu_cau_dieu_chinh (
                ma_yeu_cau INT PRIMARY KEY AUTO_INCREMENT,
                ma_the INT NOT NULL,
                so_cccd CHAR(12) NOT NULL,
                ho_ten_cu VARCHAR(100) NOT NULL,
                ho_ten_moi VARCHAR(100),
                ngay_sinh_cu DATE,
                ngay_sinh_moi DATE,
                gioi_tinh_cu VARCHAR(10),
                gioi_tinh_moi VARCHAR(10),
                noi_thuong_tru_cu VARCHAR(255),
                noi_thuong_tru_moi VARCHAR(255),
                ly_do VARCHAR(255) NOT NULL,
                trang_thai ENUM('cho_tiep_nhan','da_duyet','tu_choi') NOT NULL DEFAULT 'cho_tiep_nhan',
                ghi_chu VARCHAR(255),
                ngay_gui DATETIME DEFAULT CURRENT_TIMESTAMP,
                ngay_xu_ly DATETIME NULL,
                INDEX idx_the (ma_the)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            // Không làm gián đoạn nếu cơ sở dữ liệu không cho phép tạo bảng bổ sung.
        }
    }

    // Tự động khởi tạo bảng yeu_cau_cap_the nếu chưa tồn tại
    private function khoiTaoBangYeuCauCapThe() {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS yeu_cau_cap_the (
                ma_yeu_cau       INT PRIMARY KEY AUTO_INCREMENT,
                ma_ho_so         VARCHAR(30) NOT NULL UNIQUE,
                so_cccd          CHAR(12) NOT NULL,
                ho_ten           VARCHAR(100) NOT NULL,
                loai_yeu_cau     ENUM('cap_moi','cap_doi','cap_lai','bao_mat') NOT NULL,
                ly_do            VARCHAR(255) NOT NULL,
                hinh_thuc_nhan   ENUM('truc_tiep','buu_dien') NOT NULL DEFAULT 'truc_tiep',
                dia_chi_nhan     VARCHAR(255),
                so_dien_thoai    VARCHAR(15) NOT NULL,
                trang_thai       ENUM('cho_tiep_nhan','dang_xu_ly','da_in_the','hoan_thanh','tu_choi') NOT NULL DEFAULT 'cho_tiep_nhan',
                ngay_yeu_cau     DATETIME DEFAULT CURRENT_TIMESTAMP,
                ghi_chu          VARCHAR(255)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Bỏ qua nếu bảng đã tồn tại
        }
    }

    private function khoiTaoBangLichSuTrangThai() {
        try {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS lich_su_trang_thai_the (
                ma_lich_su INT PRIMARY KEY AUTO_INCREMENT,
                ma_the INT NOT NULL,
                trang_thai_truoc ENUM('con_hieu_luc','het_han','bao_mat','thu_hoi'),
                trang_thai_sau ENUM('con_hieu_luc','het_han','bao_mat','thu_hoi') NOT NULL,
                nguon_thay_doi VARCHAR(30) NOT NULL,
                ma_ho_so VARCHAR(30),
                ghi_chu VARCHAR(255),
                ngay_thay_doi DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_ma_the (ma_the),
                CONSTRAINT fk_lich_su_the FOREIGN KEY (ma_the)
                    REFERENCES the_can_cuoc(ma_the) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            // Không làm gián đoạn trang nếu bảng lịch sử chưa thể tạo.
        }
    }

    private function layDoanSelect() {
        return "SELECT t.*, t.ma_the AS id, t.so_the AS so_cccd,
                       u.ho_ten, u.ma_nguoi_dung
                FROM the_can_cuoc t
                JOIN nguoi_dung u ON u.ma_nguoi_dung = t.ma_nguoi_dung";
    }

    // Lấy chi tiết một thẻ căn cước theo ID
    public function layTheoMa($id) {
        $sql = $this->layDoanSelect() . " WHERE t.ma_the = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thẻ căn cước theo số CCCD
    public function layTheoSoCccd($so_cccd) {
        $sql = $this->layDoanSelect() . " WHERE t.so_the = :so_cccd LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':so_cccd' => $so_cccd]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layTheoNguoiDung($maNguoiDung) {
        $sql = $this->layDoanSelect() . " WHERE t.ma_nguoi_dung = :ma_nguoi_dung LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ma_nguoi_dung' => $maNguoiDung]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thẻ căn cước mới nhất trong hệ thống
    public function layBanGhiMoiNhat() {
        $sql = $this->layDoanSelect() . " ORDER BY t.ma_the DESC LIMIT 1";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function layTatCa() {
        $sql = $this->layDoanSelect() . " ORDER BY t.ma_the DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tra cứu chính xác theo số căn cước (12 số)
    public function timTheoSoThe($so_the) {
        $sql = $this->layDoanSelect() . " WHERE t.so_the = :so_the ORDER BY t.ma_the DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':so_the' => $so_the]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm theo họ tên công dân
    public function timTheoHoTen($ho_ten) {
        $sql = $this->layDoanSelect() . " WHERE u.ho_ten LIKE :ho_ten ORDER BY t.ma_the DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ho_ten' => "%$ho_ten%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm mới một hồ sơ thẻ căn cước
    public function themMoi($du_lieu) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "SELECT ma_nguoi_dung FROM nguoi_dung WHERE so_dinh_danh = :so_cccd LIMIT 1"
            );
            $stmt->execute([':so_cccd' => $du_lieu['so_cccd']]);
            $nguoiDung = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nguoiDung) {
                $maNguoiDung = (int)$nguoiDung['ma_nguoi_dung'];
                $stmt = $this->pdo->prepare(
                    "UPDATE nguoi_dung SET ho_ten = :ho_ten WHERE ma_nguoi_dung = :ma_nguoi_dung"
                );
                $stmt->execute([
                    ':ho_ten' => $du_lieu['ho_ten'],
                    ':ma_nguoi_dung' => $maNguoiDung
                ]);
            } else {
                $soDienThoai = '09' . substr($du_lieu['so_cccd'], -9);
                $stmt = $this->pdo->prepare(
                    "INSERT INTO nguoi_dung
                        (ma_vai_tro, so_dinh_danh, ho_ten, so_dien_thoai, mat_khau, trang_thai)
                     VALUES (1, :so_cccd, :ho_ten, :so_dien_thoai, :mat_khau, 'hoat_dong')"
                );
                $stmt->execute([
                    ':so_cccd' => $du_lieu['so_cccd'],
                    ':ho_ten' => $du_lieu['ho_ten'],
                    ':so_dien_thoai' => $soDienThoai,
                    ':mat_khau' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT)
                ]);
                $maNguoiDung = (int)$this->pdo->lastInsertId();
            }

            $sql = "INSERT INTO the_can_cuoc
                    (ma_nguoi_dung, so_the, ngay_sinh, gioi_tinh, que_quan, noi_thuong_tru,
                     ngay_cap, noi_cap, ngay_het_han, trang_thai)
                    VALUES
                    (:ma_nguoi_dung, :so_the, :ngay_sinh, :gioi_tinh, :que_quan, :noi_thuong_tru,
                     :ngay_cap, :noi_cap, :ngay_het_han, :trang_thai)";
            $stmt = $this->pdo->prepare($sql);
            $ketQua = $stmt->execute([
                ':ma_nguoi_dung' => $maNguoiDung,
                ':so_the' => $du_lieu['so_cccd'],
                ':ngay_sinh' => $du_lieu['ngay_sinh'],
                ':gioi_tinh' => $du_lieu['gioi_tinh'],
                ':que_quan' => $du_lieu['que_quan'],
                ':noi_thuong_tru' => $du_lieu['noi_thuong_tru'],
                ':ngay_cap' => $du_lieu['ngay_cap'],
                ':noi_cap' => $du_lieu['noi_cap'],
                ':ngay_het_han' => $du_lieu['ngay_het_han'],
                ':trang_thai' => $du_lieu['trang_thai'] ?? self::TRANG_THAI_CON_HIEU_LUC
            ]);
            $this->pdo->commit();
            return $ketQua;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    // Cập nhật thông tin thẻ căn cước
    public function capNhat($id, $du_lieu) {
        $this->pdo->beginTransaction();
        try {
            $the = $this->layTheoMa($id);
            if (!$the) {
                $this->pdo->rollBack();
                return false;
            }

            $stmt = $this->pdo->prepare(
                "UPDATE nguoi_dung SET ho_ten = :ho_ten WHERE ma_nguoi_dung = :ma_nguoi_dung"
            );
            $stmt->execute([
                ':ho_ten' => $du_lieu['ho_ten'],
                ':ma_nguoi_dung' => $the['ma_nguoi_dung']
            ]);

            $stmt = $this->pdo->prepare(
                "UPDATE the_can_cuoc
                 SET ngay_sinh = :ngay_sinh, gioi_tinh = :gioi_tinh,
                     noi_thuong_tru = :noi_thuong_tru, trang_thai = :trang_thai
                 WHERE ma_the = :id"
            );
            $ketQua = $stmt->execute([
                ':ngay_sinh' => $du_lieu['ngay_sinh'],
                ':gioi_tinh' => $du_lieu['gioi_tinh'],
                ':noi_thuong_tru' => $du_lieu['noi_thuong_tru'],
                ':trang_thai' => $du_lieu['trang_thai'],
                ':id' => $id
            ]);
            $this->pdo->commit();
            return $ketQua;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    // Chức năng 1: Cập nhật trạng thái thẻ căn cước
    public function capNhatTrangThai($id, $trang_thai) {
        $the = $this->layTheoMa($id);
        if (!$the) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE the_can_cuoc SET trang_thai = :trang_thai WHERE ma_the = :id"
            );
            $stmt->execute([':trang_thai' => $trang_thai, ':id' => $id]);
            $this->ghiLichSuTrangThai(
                $id,
                $the['trang_thai'],
                $trang_thai,
                'quan_ly_trang_thai',
                null,
                null
            );
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    private function ghiLichSuTrangThai(
        $maThe,
        $trangThaiTruoc,
        $trangThaiSau,
        $nguonThayDoi,
        $maHoSo = null,
        $ghiChu = null
    ) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO lich_su_trang_thai_the
                    (ma_the, trang_thai_truoc, trang_thai_sau, nguon_thay_doi, ma_ho_so, ghi_chu)
                 VALUES (:ma_the, :truoc, :sau, :nguon, :ma_ho_so, :ghi_chu)"
            );
            $stmt->execute([
                ':ma_the' => $maThe,
                ':truoc' => $trangThaiTruoc,
                ':sau' => $trangThaiSau,
                ':nguon' => $nguonThayDoi,
                ':ma_ho_so' => $maHoSo,
                ':ghi_chu' => $ghiChu
            ]);
        } catch (PDOException $e) {
            // Không thay đổi quy trình chính nếu bảng lịch sử chưa được tạo.
        }
    }

    // Chức năng 2: Thêm yêu cầu cấp thẻ (Cấp mới, Cấp đổi, Cấp lại)
    public function themYeuCauCapThe($du_lieu) {
        $sql = "INSERT INTO yeu_cau_cap_the (ma_ho_so, so_cccd, ho_ten, loai_yeu_cau, ly_do, hinh_thuc_nhan, dia_chi_nhan, so_dien_thoai, trang_thai, ghi_chu) 
                VALUES (:ma_ho_so, :so_cccd, :ho_ten, :loai_yeu_cau, :ly_do, :hinh_thuc_nhan, :dia_chi_nhan, :so_dien_thoai, :trang_thai, :ghi_chu)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':ma_ho_so'       => $du_lieu['ma_ho_so'],
            ':so_cccd'        => $du_lieu['so_cccd'],
            ':ho_ten'         => $du_lieu['ho_ten'],
            ':loai_yeu_cau'   => $du_lieu['loai_yeu_cau'],
            ':ly_do'          => $du_lieu['ly_do'],
            ':hinh_thuc_nhan' => $du_lieu['hinh_thuc_nhan'],
            ':dia_chi_nhan'   => $du_lieu['dia_chi_nhan'] ?? null,
            ':so_dien_thoai'  => $du_lieu['so_dien_thoai'],
            ':trang_thai'     => $du_lieu['trang_thai'] ?? 'cho_tiep_nhan',
            ':ghi_chu'        => $du_lieu['ghi_chu'] ?? null
        ]);
    }

    // Lấy danh sách yêu cầu cấp thẻ
    public function layDanhSachYeuCauCapThe($so_cccd = '') {
        if (!empty($so_cccd)) {
            $sql = "SELECT * FROM yeu_cau_cap_the WHERE so_cccd = :so_cccd ORDER BY ngay_yeu_cau DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':so_cccd' => $so_cccd]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $sql = "SELECT * FROM yeu_cau_cap_the ORDER BY ngay_yeu_cau DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layYeuCauTheoMa($maYeuCau) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM yeu_cau_cap_the WHERE ma_yeu_cau = :ma_yeu_cau LIMIT 1"
        );
        $stmt->execute([':ma_yeu_cau' => $maYeuCau]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layDanhSachYeuCauChoCanBo() {
        return $this->pdo->query(
            "SELECT * FROM yeu_cau_cap_the ORDER BY
                FIELD(trang_thai, 'cho_tiep_nhan', 'dang_xu_ly', 'da_in_the', 'tu_choi', 'hoan_thanh'),
                ngay_yeu_cau DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachYeuCauTrangThaiThe() {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM yeu_cau_cap_the
             WHERE loai_yeu_cau IN ('bao_mat', 'cap_lai', 'cap_doi')
               AND (loai_yeu_cau IN ('bao_mat', 'cap_lai')
                    OR ghi_chu LIKE 'THAY_DOI_TRANG_THAI:%')
             ORDER BY ngay_yeu_cau DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function xuLyYeuCauCapThe($maYeuCau, $ketQua, $ghiChu) {
        $yeuCau = $this->layYeuCauTheoMa($maYeuCau);
        if (!$yeuCau) {
            return false;
        }
        if (in_array($yeuCau['trang_thai'], ['hoan_thanh', 'tu_choi'], true)) {
            throw new InvalidArgumentException('Yêu cầu đã được cán bộ xử lý trước đó.');
        }
        $trangThaiThayDoi = null;
        if (str_starts_with((string)($yeuCau['ghi_chu'] ?? ''), 'THAY_DOI_TRANG_THAI:')) {
            $trangThaiThayDoi = substr((string)$yeuCau['ghi_chu'], strlen('THAY_DOI_TRANG_THAI:'));
        }

        $this->pdo->beginTransaction();
        try {
            if ($ketQua === 'tu_choi') {
                $trangThaiYeuCau = 'tu_choi';
            } elseif ($ketQua === 'duyet') {
                $trangThaiYeuCau = 'hoan_thanh';
            } else {
                throw new InvalidArgumentException('Kết quả xử lý yêu cầu không hợp lệ.');
            }

            $stmt = $this->pdo->prepare(
                "UPDATE yeu_cau_cap_the
                 SET trang_thai = :trang_thai, ghi_chu = :ghi_chu
                 WHERE ma_yeu_cau = :ma_yeu_cau"
            );
            $stmt->execute([
                ':trang_thai' => $trangThaiYeuCau,
                ':ghi_chu' => $ghiChu,
                ':ma_yeu_cau' => $maYeuCau
            ]);

            if ($ketQua === 'duyet') {
                $the = $this->layTheoSoCccd($yeuCau['so_cccd']);
                if ($the) {
                    if (in_array($trangThaiThayDoi, ['het_han', 'bao_mat', 'thu_hoi'], true)) {
                        $trangThaiThe = $trangThaiThayDoi;
                    } else {
                        $trangThaiThe = $yeuCau['loai_yeu_cau'] === 'bao_mat'
                            ? 'bao_mat'
                            : 'con_hieu_luc';
                    }
                    $stmt = $this->pdo->prepare(
                        "UPDATE the_can_cuoc SET trang_thai = :trang_thai WHERE ma_the = :ma_the"
                    );
                    $stmt->execute([
                        ':trang_thai' => $trangThaiThe,
                        ':ma_the' => $the['id']
                    ]);
                    $this->ghiLichSuTrangThai(
                        $the['id'],
                        $the['trang_thai'],
                        $trangThaiThe,
                        'duyet_yeu_cau_cap_the',
                        $yeuCau['ma_ho_so'],
                        $ghiChu
                    );
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function layLichSuTrangThaiThe($maThe) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM lich_su_trang_thai_the
                 WHERE ma_the = :ma_the ORDER BY ngay_thay_doi DESC"
            );
            $stmt->execute([':ma_the' => $maThe]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function themYeuCauDieuChinh($duLieu) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO yeu_cau_dieu_chinh
             (ma_ho_so, ma_nguoi_dung, thay_doi_json, ly_do, trang_thai)
             VALUES
             (:ma_ho_so, :ma_nguoi_dung, :thay_doi_json, :ly_do, 'cho_tiep_nhan')"
        );
        return $stmt->execute([
            ':ma_ho_so' => 'DC-' . date('Ym') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5)),
            ':ma_nguoi_dung' => (int)($_SESSION['ma_nguoi_dung'] ?? 0),
            ':thay_doi_json' => json_encode($duLieu, JSON_UNESCAPED_UNICODE),
            ':ly_do' => $duLieu['ly_do']
        ]);
    }

    private function chuanHoaYeuCauDieuChinh($yeuCau) {
        $maNguoiDung = (int)($yeuCau['ma_nguoi_dung'] ?? 0);
        if ($maNguoiDung > 0) {
            $the = $this->layTheoNguoiDung($maNguoiDung);
            if ($the) {
                $yeuCau['so_cccd'] = $yeuCau['so_cccd'] ?? $the['so_cccd'];
                $yeuCau['ho_ten_cu'] = $yeuCau['ho_ten_cu'] ?? $the['ho_ten'];
                $yeuCau['ngay_sinh_cu'] = $yeuCau['ngay_sinh_cu'] ?? $the['ngay_sinh'];
                $yeuCau['gioi_tinh_cu'] = $yeuCau['gioi_tinh_cu'] ?? $the['gioi_tinh'];
                $yeuCau['que_quan_cu'] = $yeuCau['que_quan_cu'] ?? ($the['que_quan'] ?? null);
                $yeuCau['noi_thuong_tru_cu'] = $yeuCau['noi_thuong_tru_cu'] ?? ($the['noi_thuong_tru'] ?? null);
                $yeuCau['ma_the'] = $yeuCau['ma_the'] ?? $the['id'];
            }
        }
        $yeuCau['ma_ho_so'] = $yeuCau['ma_ho_so'] ?? ('YC-' . (int)($yeuCau['ma_yeu_cau'] ?? 0));
        return $yeuCau;
    }

    public function layDanhSachYeuCauDieuChinh($soCccd = '') {
        if ($soCccd !== '') {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM yeu_cau_dieu_chinh
                 WHERE thay_doi_json LIKE :so_cccd
                 ORDER BY ngay_yeu_cau DESC"
            );
            $stmt->execute([':so_cccd' => '%' . $soCccd . '%']);
            $ds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $ds = $this->pdo->query(
                "SELECT * FROM yeu_cau_dieu_chinh ORDER BY ngay_yeu_cau DESC"
            )->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach ($ds as &$yeuCau) {
            $duLieu = json_decode($yeuCau['thay_doi_json'] ?? '{}', true);
            if (is_array($duLieu)) {
                $yeuCau = array_merge($yeuCau, $duLieu);
            }
            $yeuCau = $this->chuanHoaYeuCauDieuChinh($yeuCau);
        }
        unset($yeuCau);
        return $ds;
    }

    public function layYeuCauDieuChinhTheoMa($maYeuCau) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM yeu_cau_dieu_chinh WHERE ma_yeu_cau = :ma_yeu_cau LIMIT 1"
        );
        $stmt->execute([':ma_yeu_cau' => $maYeuCau]);
        $yeuCau = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$yeuCau) {
            return false;
        }
        $duLieu = json_decode($yeuCau['thay_doi_json'] ?? '{}', true);
        $yeuCau = array_merge($yeuCau, is_array($duLieu) ? $duLieu : []);
        return $this->chuanHoaYeuCauDieuChinh($yeuCau);
    }

    public function xuLyYeuCauDieuChinh($maYeuCau, $ketQua, $ghiChu) {
        $yeuCau = $this->layYeuCauDieuChinhTheoMa($maYeuCau);
        if (!$yeuCau || in_array($yeuCau['trang_thai'], ['da_duyet', 'hoan_thanh', 'tu_choi'], true)) {
            return false;
        }
        $this->pdo->beginTransaction();
        try {
            if ($ketQua === 'duyet') {
                $stmt = $this->pdo->prepare(
                    "UPDATE nguoi_dung u JOIN the_can_cuoc t ON t.ma_nguoi_dung = u.ma_nguoi_dung
                     SET u.ho_ten = COALESCE(:ho_ten, u.ho_ten),
                         t.ngay_sinh = COALESCE(:ngay_sinh, t.ngay_sinh),
                         t.gioi_tinh = COALESCE(:gioi_tinh, t.gioi_tinh),
                         t.quoc_tich = COALESCE(:quoc_tich, t.quoc_tich),
                         t.dan_toc = COALESCE(:dan_toc, t.dan_toc),
                         t.ton_giao = COALESCE(:ton_giao, t.ton_giao),
                         t.que_quan = COALESCE(:que_quan, t.que_quan),
                         t.noi_thuong_tru = COALESCE(:noi_thuong_tru, t.noi_thuong_tru),
                         t.anh_chan_dung = COALESCE(:anh_chan_dung, t.anh_chan_dung)
                     WHERE t.ma_the = :ma_the"
                );
                $stmt->execute([
                    ':ho_ten' => $yeuCau['ho_ten_moi'] ?: null,
                    ':ngay_sinh' => $yeuCau['ngay_sinh_moi'] ?: null,
                    ':gioi_tinh' => $yeuCau['gioi_tinh_moi'] ?: null,
                    ':quoc_tich' => $yeuCau['quoc_tich_moi'] ?: null,
                    ':dan_toc' => $yeuCau['dan_toc_moi'] ?: null,
                    ':ton_giao' => $yeuCau['ton_giao_moi'] ?: null,
                    ':que_quan' => $yeuCau['que_quan_moi'] ?: null,
                    ':noi_thuong_tru' => $yeuCau['noi_thuong_tru_moi'] ?: null,
                    ':anh_chan_dung' => $yeuCau['anh_chan_dung_moi'] ?: null,
                    ':ma_the' => $yeuCau['ma_the']
                ]);
                $trangThai = 'hoan_thanh';
            } elseif ($ketQua === 'tu_choi') {
                $trangThai = 'tu_choi';
            } else {
                throw new InvalidArgumentException('Kết quả xử lý không hợp lệ.');
            }
            $stmt = $this->pdo->prepare(
                "UPDATE yeu_cau_dieu_chinh
                 SET trang_thai = :trang_thai, ghi_chu = :ghi_chu, ngay_xu_ly = CURRENT_TIMESTAMP
                 WHERE ma_yeu_cau = :ma_yeu_cau"
            );
            $stmt->execute([
                ':trang_thai' => $trangThai,
                ':ghi_chu' => $ghiChu,
                ':ma_yeu_cau' => $maYeuCau
            ]);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
