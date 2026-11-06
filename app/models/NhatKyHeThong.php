<?php
class NhatKyHeThong {
        const NHAN_HANH_DONG = [
        'thanh_cong'         => 'Đăng nhập thành công',
        'that_bai'           => 'Đăng nhập thất bại',
        'dang_xuat'          => 'Đăng xuất',
        'dang_ky'            => 'Đăng ký tài khoản',
        'kich_hoat'          => 'Kích hoạt tài khoản',
        'doi_mat_khau'       => 'Đổi mật khẩu',
        'dat_lai_mat_khau'   => 'Đặt lại mật khẩu',
        'khoa_tu_dong'       => 'Khóa tự động (sai mật khẩu)',
        'khoa_tai_khoan'     => 'Khóa tài khoản',
        'mo_khoa_tai_khoan'  => 'Mở khóa tài khoản',
        'doi_vai_tro'        => 'Đổi vai trò',
        'nop_ho_so_nang_muc' => 'Nộp hồ sơ nâng mức',
        'duyet_ho_so'        => 'Duyệt hồ sơ',
        'tu_choi_ho_so'      => 'Từ chối hồ sơ',
        'yeu_cau_bo_sung'    => 'Yêu cầu bổ sung hồ sơ',
    ];

    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function ghi($ma_nguoi_dung, $hanh_dong, $dia_chi_ip, $noi_dung = null) {
        $this->pdo->prepare(
            'INSERT INTO nhat_ky_he_thong (ma_nguoi_dung, hanh_dong, noi_dung, dia_chi_ip)
                VALUES (?, ?, ?, ?)'
        )->execute([$ma_nguoi_dung, $hanh_dong, $noi_dung, $dia_chi_ip]);
    }

    public function timKiem($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay, $vi_tri, $so_luong) {
        [$dieu_kien, $ds_tham_so] = $this->taoDieuKien($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay);
        $lenh = $this->pdo->prepare(
            "SELECT nk.*, nd.ho_ten, nd.so_dinh_danh
             FROM nhat_ky_he_thong nk
             LEFT JOIN nguoi_dung nd ON nd.ma_nguoi_dung = nk.ma_nguoi_dung
             $dieu_kien ORDER BY nk.ma_nhat_ky_he_thong DESC LIMIT ? OFFSET ?"
        );
        $i = 1;
        foreach ($ds_tham_so as $gia_tri) {
            $lenh->bindValue($i++, $gia_tri);
        }
        $lenh->bindValue($i++, (int)$so_luong, PDO::PARAM_INT);
        $lenh->bindValue($i,   (int)$vi_tri,   PDO::PARAM_INT);
        $lenh->execute();
        return $lenh->fetchAll();
    }

    public function dem($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay) {
        [$dieu_kien, $ds_tham_so] = $this->taoDieuKien($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay);
        $lenh = $this->pdo->prepare(
            "SELECT COUNT(*) FROM nhat_ky_he_thong nk
             LEFT JOIN nguoi_dung nd ON nd.ma_nguoi_dung = nk.ma_nguoi_dung $dieu_kien"
        );
        $lenh->execute($ds_tham_so);
        return (int)$lenh->fetchColumn();
    }

    private function taoDieuKien($hanh_dong, $tu_khoa, $tu_ngay, $den_ngay) {
        $ds_dieu_kien = [];
        $ds_tham_so   = [];
        if ($hanh_dong !== '') {
            $ds_dieu_kien[] = 'nk.hanh_dong = ?';
            $ds_tham_so[]   = $hanh_dong;
        }
        if ($tu_khoa !== '') {
            $ds_dieu_kien[] = '(nk.noi_dung LIKE ? OR nd.ho_ten LIKE ? OR nd.so_dinh_danh LIKE ?)';
            array_push($ds_tham_so, "%$tu_khoa%", "%$tu_khoa%", "%$tu_khoa%");
        }
        if ($tu_ngay !== '') {
            $ds_dieu_kien[] = 'nk.thoi_gian >= ?';
            $ds_tham_so[]   = $tu_ngay;
        }
        if ($den_ngay !== '') {
            $ds_dieu_kien[] = 'nk.thoi_gian < DATE_ADD(?, INTERVAL 1 DAY)';
            $ds_tham_so[]   = $den_ngay;
        }
        return [$ds_dieu_kien ? 'WHERE ' . implode(' AND ', $ds_dieu_kien) : '', $ds_tham_so];
    }

    public function thongKeTheoHanhDong() {
        return $this->pdo->query(
            "SELECT hanh_dong, COUNT(*) AS so_luong FROM nhat_ky_he_thong
             WHERE hanh_dong IN ('thanh_cong','that_bai','dang_xuat') GROUP BY hanh_dong"
        )->fetchAll();
    }
}