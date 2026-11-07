<?php
class NguoiDung {
    const TRANG_THAI_HOAT_DONG     = 'hoat_dong';
    const TRANG_THAI_BI_KHOA       = 'bi_khoa';
    const TRANG_THAI_CHO_KICH_HOAT = 'cho_kich_hoat';

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function layTheoMa($ma_nguoi_dung) {
        $lenh = $this->pdo->prepare('SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = ?');
        $lenh->execute([$ma_nguoi_dung]);
        return $lenh->fetch();
    }

    public function layTheoSoDinhDanh($so_dinh_danh) {
        $lenh = $this->pdo->prepare('SELECT * FROM nguoi_dung WHERE so_dinh_danh = ?');
        $lenh->execute([$so_dinh_danh]);
        return $lenh->fetch();
    }

    public function layTheoSoDienThoai($so_dien_thoai) {
        $lenh = $this->pdo->prepare('SELECT * FROM nguoi_dung WHERE so_dien_thoai = ?');
        $lenh->execute([$so_dien_thoai]);
        return $lenh->fetch();
    }

    public function layTheoEmail($email) {
        $lenh = $this->pdo->prepare('SELECT * FROM nguoi_dung WHERE email = ?');
        $lenh->execute([$email]);
        return $lenh->fetch();
    }

    public function kiemTraTonTai($so_dinh_danh, $so_dien_thoai, $email) {
        $lenh = $this->pdo->prepare(
            'SELECT COUNT(*) FROM nguoi_dung
             WHERE so_dinh_danh = ? OR so_dien_thoai = ? OR (email IS NOT NULL AND email = ?)'
        );
        $lenh->execute([$so_dinh_danh, $so_dien_thoai, $email]);
        return $lenh->fetchColumn() > 0;
    }

    public function themMoi($so_dinh_danh, $ho_ten, $so_dien_thoai, $email, $mat_khau) {
        $lenh = $this->pdo->prepare(
            'INSERT INTO nguoi_dung (ma_vai_tro, so_dinh_danh, ho_ten, so_dien_thoai, email, mat_khau, trang_thai)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $lenh->execute([
            VAI_TRO_CONG_DAN, $so_dinh_danh, $ho_ten, $so_dien_thoai,
            $email ?: null, password_hash($mat_khau, PASSWORD_DEFAULT),
            self::TRANG_THAI_CHO_KICH_HOAT,
        ]);
        return $this->pdo->lastInsertId();
    }

    public function capNhatTrangThai($ma_nguoi_dung, $trang_thai) {
        $lenh = $this->pdo->prepare(
            'UPDATE nguoi_dung SET trang_thai = ?, so_lan_sai = 0 WHERE ma_nguoi_dung = ?'
        );
        return $lenh->execute([$trang_thai, $ma_nguoi_dung]);
    }

    public function tangSoLanSai($ma_nguoi_dung) {
        $this->pdo->prepare(
            'UPDATE nguoi_dung SET so_lan_sai = so_lan_sai + 1 WHERE ma_nguoi_dung = ?'
        )->execute([$ma_nguoi_dung]);

        $nguoi_dung = $this->layTheoMa($ma_nguoi_dung);
        if ($nguoi_dung['so_lan_sai'] >= SO_LAN_SAI_TOI_DA) {
            $this->pdo->prepare(
                'UPDATE nguoi_dung SET trang_thai = ? WHERE ma_nguoi_dung = ?'
            )->execute([self::TRANG_THAI_BI_KHOA, $ma_nguoi_dung]);
        }
        return (int)$nguoi_dung['so_lan_sai'];
    }

    public function datLaiSoLanSai($ma_nguoi_dung) {
        $this->pdo->prepare('UPDATE nguoi_dung SET so_lan_sai = 0 WHERE ma_nguoi_dung = ?')
                  ->execute([$ma_nguoi_dung]);
    }

    public function capNhatVaiTro($ma_nguoi_dung, $ma_vai_tro) {
        $lenh = $this->pdo->prepare('UPDATE nguoi_dung SET ma_vai_tro = ? WHERE ma_nguoi_dung = ?');
        return $lenh->execute([$ma_vai_tro, $ma_nguoi_dung]);
    }

    public function timKiem($tu_khoa, $trang_thai, $ma_vai_tro, $vi_tri, $so_luong) {
        [$dieu_kien, $ds_tham_so] = $this->taoDieuKien($tu_khoa, $trang_thai, $ma_vai_tro);
        $sql = "SELECT nd.*, vt.ten_vai_tro
                FROM nguoi_dung nd JOIN vai_tro vt ON vt.ma_vai_tro = nd.ma_vai_tro
                $dieu_kien ORDER BY nd.ma_nguoi_dung ASC LIMIT ? OFFSET ?";
        $lenh = $this->pdo->prepare($sql);
        $i = 1;
        foreach ($ds_tham_so as $gia_tri) {
            $lenh->bindValue($i++, $gia_tri);
        }
        $lenh->bindValue($i++, (int)$so_luong, PDO::PARAM_INT);
        $lenh->bindValue($i,   (int)$vi_tri,   PDO::PARAM_INT);
        $lenh->execute();
        return $lenh->fetchAll();
    }

    public function demTimKiem($tu_khoa, $trang_thai, $ma_vai_tro) {
        [$dieu_kien, $ds_tham_so] = $this->taoDieuKien($tu_khoa, $trang_thai, $ma_vai_tro);
        $lenh = $this->pdo->prepare("SELECT COUNT(*) FROM nguoi_dung nd $dieu_kien");
        $lenh->execute($ds_tham_so);
        return (int)$lenh->fetchColumn();
    }

    private function taoDieuKien($tu_khoa, $trang_thai, $ma_vai_tro) {
        $ds_dieu_kien = [];
        $ds_tham_so   = [];
        if ($tu_khoa !== '') {
            $ds_dieu_kien[] = '(nd.ho_ten LIKE ? OR nd.so_dinh_danh LIKE ? OR nd.so_dien_thoai LIKE ? OR nd.email LIKE ?)';
            array_push($ds_tham_so, "%$tu_khoa%", "%$tu_khoa%", "%$tu_khoa%", "%$tu_khoa%");
        }
        if ($trang_thai !== '') {
            $ds_dieu_kien[] = 'nd.trang_thai = ?';
            $ds_tham_so[] = $trang_thai;
        }
        if ($ma_vai_tro !== '') {
            $ds_dieu_kien[] = 'nd.ma_vai_tro = ?';
            $ds_tham_so[] = $ma_vai_tro;
        }
        $dieu_kien = $ds_dieu_kien ? 'WHERE ' . implode(' AND ', $ds_dieu_kien) : '';
        return [$dieu_kien, $ds_tham_so];
    }

    public function thongKeTheoTrangThai() {
        return $this->pdo->query(
            'SELECT trang_thai, COUNT(*) AS so_luong FROM nguoi_dung GROUP BY trang_thai'
        )->fetchAll();
    }

    public function thongKeTheoVaiTro() {
        return $this->pdo->query(
            'SELECT vt.ten_vai_tro, COUNT(*) AS so_luong
             FROM nguoi_dung nd JOIN vai_tro vt ON vt.ma_vai_tro = nd.ma_vai_tro
             GROUP BY vt.ten_vai_tro'
        )->fetchAll();
    }

    public function demQuanTri() {
        $lenh = $this->pdo->prepare('SELECT COUNT(*) FROM nguoi_dung WHERE ma_vai_tro = ? AND trang_thai = ?');
        $lenh->execute([VAI_TRO_QUAN_TRI, self::TRANG_THAI_HOAT_DONG]);
        return (int)$lenh->fetchColumn();
    }

    public function capNhatMatKhau($ma_nguoi_dung, $mat_khau_da_bam) {
        $this->pdo->prepare('UPDATE nguoi_dung SET mat_khau = ?, so_lan_sai = 0 WHERE ma_nguoi_dung = ?')
                  ->execute([$mat_khau_da_bam, $ma_nguoi_dung]);
    }
}