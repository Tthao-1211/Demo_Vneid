<?php
// Model thông báo: làm việc với bảng thong_bao
class ThongBao {
    const LOAI_HE_THONG     = 'he_thong';
    const LOAI_DICH_VU_CONG = 'dich_vu_cong';
    const LOAI_BAO_MAT      = 'bao_mat';
    const SO_DONG_MOI_TRANG = 10;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Tạo phần WHERE dùng chung cho lấy danh sách và đếm
    private function taoDieuKien($ma_nguoi_dung, $loai, $chi_chua_doc) {
        $dieu_kien = 'ma_nguoi_nhan = :ma_nguoi_nhan';
        $tham_so   = [':ma_nguoi_nhan' => $ma_nguoi_dung];
        if ($loai !== null && $loai !== '') {
            $dieu_kien .= ' AND loai = :loai';
            $tham_so[':loai'] = $loai;
        }
        if ($chi_chua_doc) {
            $dieu_kien .= ' AND da_doc = 0';
        }
        return [$dieu_kien, $tham_so];
    }

    public function layDanhSachTheoNguoiDung($ma_nguoi_dung, $loai = null, $chi_chua_doc = false, $trang = 1) {
        [$dieu_kien, $tham_so] = $this->taoDieuKien($ma_nguoi_dung, $loai, $chi_chua_doc);
        $vi_tri = (max(1, (int) $trang) - 1) * self::SO_DONG_MOI_TRANG;

        $sql = "SELECT * FROM thong_bao WHERE $dieu_kien
                ORDER BY ngay_tao DESC, ma_thong_bao DESC
                LIMIT :gioi_han OFFSET :vi_tri";
        $cau_lenh = $this->pdo->prepare($sql);
        foreach ($tham_so as $khoa => $gia_tri) {
            $cau_lenh->bindValue($khoa, $gia_tri);
        }
        $cau_lenh->bindValue(':gioi_han', self::SO_DONG_MOI_TRANG, PDO::PARAM_INT);
        $cau_lenh->bindValue(':vi_tri', $vi_tri, PDO::PARAM_INT);
        $cau_lenh->execute();
        return $cau_lenh->fetchAll();
    }

    public function demTheoNguoiDung($ma_nguoi_dung, $loai = null, $chi_chua_doc = false) {
        [$dieu_kien, $tham_so] = $this->taoDieuKien($ma_nguoi_dung, $loai, $chi_chua_doc);
        $cau_lenh = $this->pdo->prepare("SELECT COUNT(*) FROM thong_bao WHERE $dieu_kien");
        $cau_lenh->execute($tham_so);
        return (int) $cau_lenh->fetchColumn();
    }

    public function demChuaDoc($ma_nguoi_dung) {
        return $this->demTheoNguoiDung($ma_nguoi_dung, null, true);
    }

    // Chỉ lấy được thông báo của chính người nhận
    public function layTheoMa($ma_thong_bao, $ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'SELECT * FROM thong_bao WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?'
        );
        $cau_lenh->execute([$ma_thong_bao, $ma_nguoi_dung]);
        return $cau_lenh->fetch() ?: null;
    }

    public function danhDauDaDoc($ma_thong_bao, $ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'UPDATE thong_bao SET da_doc = 1 WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?'
        );
        return $cau_lenh->execute([$ma_thong_bao, $ma_nguoi_dung]);
    }

    public function danhDauTatCaDaDoc($ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'UPDATE thong_bao SET da_doc = 1 WHERE ma_nguoi_nhan = ? AND da_doc = 0'
        );
        $cau_lenh->execute([$ma_nguoi_dung]);
        return $cau_lenh->rowCount();
    }

    public function xoa($ma_thong_bao, $ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'DELETE FROM thong_bao WHERE ma_thong_bao = ? AND ma_nguoi_nhan = ?'
        );
        $cau_lenh->execute([$ma_thong_bao, $ma_nguoi_dung]);
        return $cau_lenh->rowCount() > 0;
    }

    // Gửi cho một người. Các module khác (dich_vu, giay_to_vi...) gọi hàm này để báo tin
    public function themMoi($ma_nguoi_nhan, $loai, $tieu_de, $noi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung) VALUES (?, ?, ?, ?)'
        );
        $cau_lenh->execute([$ma_nguoi_nhan, $loai, $tieu_de, $noi_dung]);
        return (int) $this->pdo->lastInsertId();
    }

    // Quản trị gửi cho mọi tài khoản đang hoạt động (trừ chính người gửi). Trả về số người nhận
    public function guiHangLoat($loai, $tieu_de, $noi_dung, $ma_nguoi_gui) {
        $cau_lenh = $this->pdo->prepare(
            "INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung)
             SELECT ma_nguoi_dung, ?, ?, ? FROM nguoi_dung
             WHERE trang_thai = 'hoat_dong' AND ma_nguoi_dung <> ?"
        );
        $cau_lenh->execute([$loai, $tieu_de, $noi_dung, $ma_nguoi_gui]);
        return $cau_lenh->rowCount();
    }
}