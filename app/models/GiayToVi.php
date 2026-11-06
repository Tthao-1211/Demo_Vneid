<?php
// Model ví giấy tờ: bảng giay_to_vi và loai_giay_to
class GiayToVi {
    const TRANG_THAI_CHO_XAC_MINH = 'cho_xac_minh';
    const TRANG_THAI_DA_XAC_MINH  = 'da_xac_minh';
    const TRANG_THAI_BI_TU_CHOI   = 'bi_tu_choi';

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Giải mã cột JSON thành mảng PHP
    private function giaiMaJson($ban_ghi) {
        $ban_ghi['thong_tin'] = $ban_ghi['thong_tin'] ? json_decode($ban_ghi['thong_tin'], true) : [];
        return $ban_ghi;
    }

    public function layDanhSachTheoNguoiDung($ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'SELECT g.*, l.ten_loai
             FROM giay_to_vi g
             JOIN loai_giay_to l ON l.ma_loai = g.ma_loai
             WHERE g.ma_nguoi_dung = ?
             ORDER BY g.ma_giay_to DESC'
        );
        $cau_lenh->execute([$ma_nguoi_dung]);
        return array_map([$this, 'giaiMaJson'], $cau_lenh->fetchAll());
    }

    // Kèm điều kiện ma_nguoi_dung để không xem được giấy tờ của người khác
    public function layTheoMa($ma_giay_to, $ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare(
            'SELECT g.*, l.ten_loai, l.cac_truong
             FROM giay_to_vi g
             JOIN loai_giay_to l ON l.ma_loai = g.ma_loai
             WHERE g.ma_giay_to = ? AND g.ma_nguoi_dung = ?'
        );
        $cau_lenh->execute([$ma_giay_to, $ma_nguoi_dung]);
        $giay_to = $cau_lenh->fetch();
        if (!$giay_to) return null;
        $giay_to = $this->giaiMaJson($giay_to);
        $giay_to['cac_truong'] = $giay_to['cac_truong'] ? json_decode($giay_to['cac_truong'], true) : [];
        return $giay_to;
    }

    public function layDanhSachLoai() {
        $ds_loai = $this->pdo->query('SELECT * FROM loai_giay_to ORDER BY ma_loai')->fetchAll();
        foreach ($ds_loai as &$loai) {
            $loai['cac_truong'] = $loai['cac_truong'] ? json_decode($loai['cac_truong'], true) : [];
        }
        return $ds_loai;
    }

    public function layLoaiTheoMa($ma_loai) {
        foreach ($this->layDanhSachLoai() as $loai) {
            if ((int) $loai['ma_loai'] === (int) $ma_loai) return $loai;
        }
        return null;
    }

    // $du_lieu: ma_nguoi_dung, ma_loai, so_giay_to, ten_chu_so_huu, ngay_cap, ngay_het_han, noi_cap, thong_tin(mảng)
    public function themMoi(array $du_lieu) {
        $cau_lenh = $this->pdo->prepare(
            'INSERT INTO giay_to_vi
                (ma_nguoi_dung, ma_loai, so_giay_to, ten_chu_so_huu, ngay_cap, ngay_het_han, noi_cap, thong_tin)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $cau_lenh->execute([
            $du_lieu['ma_nguoi_dung'],
            $du_lieu['ma_loai'],
            $du_lieu['so_giay_to'],
            $du_lieu['ten_chu_so_huu'],
            $du_lieu['ngay_cap'] ?: null,
            $du_lieu['ngay_het_han'] ?: null,
            $du_lieu['noi_cap'] ?: null,
            json_encode($du_lieu['thong_tin'], JSON_UNESCAPED_UNICODE),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function xoa($ma_giay_to, $ma_nguoi_dung) {
        $cau_lenh = $this->pdo->prepare('DELETE FROM giay_to_vi WHERE ma_giay_to = ? AND ma_nguoi_dung = ?');
        $cau_lenh->execute([$ma_giay_to, $ma_nguoi_dung]);
        return $cau_lenh->rowCount() > 0;
    }
}