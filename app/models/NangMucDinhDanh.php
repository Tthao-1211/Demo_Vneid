<?php
class NangMucDinhDanh {
    const TEN_DICH_VU = 'Nâng mức định danh lên mức 2';

    const NHAN_TRANG_THAI = [
        'da_nop'      => 'Đã nộp',
        'dang_xu_ly'  => 'Đang xử lý',
        'can_bo_sung' => 'Cần bổ sung',
        'da_duyet'    => 'Đã duyệt',
        'tu_choi'     => 'Từ chối',
    ];

    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function layMaDichVu() {
        $lenh = $this->pdo->prepare('SELECT ma_dich_vu FROM dich_vu WHERE ten_dich_vu = ?');
        $lenh->execute([self::TEN_DICH_VU]);
        return (int)$lenh->fetchColumn();
    }

    public function coHoSoDangCho($ma_nguoi_dung) {
        $lenh = $this->pdo->prepare(
            "SELECT COUNT(*) FROM ho_so_dich_vu
             WHERE ma_nguoi_dung = ? AND ma_dich_vu = ? AND trang_thai IN ('da_nop','dang_xu_ly')"
        );
        $lenh->execute([$ma_nguoi_dung, $this->layMaDichVu()]);
        return $lenh->fetchColumn() > 0;
    }

    public function themMoi($ma_nguoi_dung, $noi_dung, $tep_dinh_kem) {
        $this->pdo->prepare(
            'INSERT INTO ho_so_dich_vu (ma_nguoi_dung, ma_dich_vu, noi_dung, tep_dinh_kem) VALUES (?, ?, ?, ?)'
        )->execute([$ma_nguoi_dung, $this->layMaDichVu(), $noi_dung, $tep_dinh_kem]);
    }

    public function layDanhSachTheoNguoiDung($ma_nguoi_dung) {
        $lenh = $this->pdo->prepare(
            'SELECT * FROM ho_so_dich_vu WHERE ma_nguoi_dung = ? AND ma_dich_vu = ? ORDER BY ma_ho_so DESC'
        );
        $lenh->execute([$ma_nguoi_dung, $this->layMaDichVu()]);
        return $lenh->fetchAll();
    }

    public function layDanhSachTheoTrangThai($trang_thai) {
        $sql = 'SELECT hs.*, nd.ho_ten, nd.so_dinh_danh FROM ho_so_dich_vu hs
                JOIN nguoi_dung nd ON nd.ma_nguoi_dung = hs.ma_nguoi_dung
                WHERE hs.ma_dich_vu = ?' . ($trang_thai !== '' ? ' AND hs.trang_thai = ?' : '') .
               ' ORDER BY hs.ma_ho_so DESC';
        $lenh = $this->pdo->prepare($sql);
        $lenh->execute($trang_thai !== '' ? [$this->layMaDichVu(), $trang_thai] : [$this->layMaDichVu()]);
        return $lenh->fetchAll();
    }

    public function layTheoMa($ma_ho_so) {
        $lenh = $this->pdo->prepare(
            'SELECT hs.*, nd.ho_ten, nd.so_dinh_danh, nd.muc_dinh_danh FROM ho_so_dich_vu hs
             JOIN nguoi_dung nd ON nd.ma_nguoi_dung = hs.ma_nguoi_dung
             WHERE hs.ma_ho_so = ? AND hs.ma_dich_vu = ?'
        );
        $lenh->execute([$ma_ho_so, $this->layMaDichVu()]);
        return $lenh->fetch();
    }

    public function capNhatTrangThai($ma_ho_so, $trang_thai, $ghi_chu, $ma_can_bo) {
        $this->pdo->prepare(
            'UPDATE ho_so_dich_vu SET trang_thai = ?, ghi_chu = ?, ma_can_bo = ? WHERE ma_ho_so = ?'
        )->execute([$trang_thai, $ghi_chu, $ma_can_bo, $ma_ho_so]);
    }

    public function duyet($ho_so, $ma_can_bo, $ghi_chu = '') {
        $this->pdo->beginTransaction();
        try {
            // 1. Cập nhật trạng thái hồ sơ nâng mức
            $stmt1 = $this->pdo->prepare("
                UPDATE ho_so_dich_vu 
                SET trang_thai = 'da_duyet', ghi_chu = ?, ma_can_bo = ? 
                WHERE ma_ho_so = ?
            ");
            $stmt1->execute([$ghi_chu, $ma_can_bo, $ho_so['ma_ho_so']]);

            // 2. Nâng mức định danh công dân lên mức 2
            $stmt2 = $this->pdo->prepare("
                UPDATE nguoi_dung 
                SET muc_dinh_danh = 2 
                WHERE ma_nguoi_dung = ?
            ");
            $stmt2->execute([$ho_so['ma_nguoi_dung']]);

            // 3. Lấy file ảnh chân dung và cập nhật hoặc tạo mới thẻ căn cước
            $ds_tep = explode(';', (string)$ho_so['tep_dinh_kem']);
            if (isset($ds_tep[1]) && trim($ds_tep[1]) !== '') {
                $duong_dan_anh = '/muc_dinh_danh/xem_tep?ma_ho_so=' . (int)$ho_so['ma_ho_so'] . '&vi_tri=1';

                // Kiểm tra xem người dùng đã có bản ghi trong the_can_cuoc chưa
                $stmt_check = $this->pdo->prepare("SELECT ma_the FROM the_can_cuoc WHERE ma_nguoi_dung = ? LIMIT 1");
                $stmt_check->execute([$ho_so['ma_nguoi_dung']]);
                $da_co_cccd = $stmt_check->fetchColumn();

                if ($da_co_cccd) {
                    // Nếu đã có -> Cập nhật đường dẫn ảnh chân dung
                    $stmt3 = $this->pdo->prepare("
                        UPDATE the_can_cuoc 
                        SET anh_chan_dung = ? 
                        WHERE ma_nguoi_dung = ?
                    ");
                    $stmt3->execute([$duong_dan_anh, $ho_so['ma_nguoi_dung']]);
                } else {
                    // Nếu chưa có (tài khoản mới) -> Lấy dữ liệu từ nội dung hồ sơ để INSERT tạo thẻ căn cước mới
                    $thong_tin = json_decode($ho_so['noi_dung'] ?? '[]', true) ?: [];
                    
                    // Lấy số định danh của người dùng từ bảng nguoi_dung làm số thẻ mặc định
                    $stmt_nd = $this->pdo->prepare("SELECT so_dinh_danh FROM nguoi_dung WHERE ma_nguoi_dung = ? LIMIT 1");
                    $stmt_nd->execute([$ho_so['ma_nguoi_dung']]);
                    $so_dinh_danh = $stmt_nd->fetchColumn() ?: ('079' . str_pad((string)$ho_so['ma_nguoi_dung'], 9, '0', STR_PAD_LEFT));

                    $so_the         = $thong_tin['so_the'] ?? $so_dinh_danh;
                    $ngay_sinh      = $thong_tin['ngay_sinh'] ?? '2000-01-01';
                    $gioi_tinh      = $thong_tin['gioi_tinh'] ?? 'Nam';
                    $noi_thuong_tru = $thong_tin['noi_thuong_tru'] ?? 'Chưa cập nhật';
                    $noi_cap        = $thong_tin['noi_cap'] ?? 'Cục Cảnh sát QLHC về TTXH';

                    $stmt3 = $this->pdo->prepare("
                        INSERT INTO the_can_cuoc 
                        (ma_nguoi_dung, so_the, ngay_sinh, gioi_tinh, que_quan, noi_thuong_tru, tinh_thanh, anh_chan_dung, ngay_cap, ngay_het_han, noi_cap, trang_thai)
                        VALUES (?, ?, ?, ?, 'Chưa cập nhật', ?, 'Khánh Hòa', ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 10 YEAR), ?, 'con_hieu_luc')
                    ");
                    $stmt3->execute([
                        $ho_so['ma_nguoi_dung'],
                        $so_the,
                        $ngay_sinh,
                        $gioi_tinh,
                        $noi_thuong_tru,
                        $duong_dan_anh,
                        $noi_cap
                    ]);
                }
            }

            // 4. Gửi thông báo cho công dân
            $stmt4 = $this->pdo->prepare("
                INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung) 
                VALUES (?, 'he_thong', 'Hồ sơ nâng mức định danh đã được duyệt', 'Hồ sơ nâng mức định danh lên mức 2 của bạn đã được phê duyệt thành công.')
            ");
            $stmt4->execute([$ho_so['ma_nguoi_dung']]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function guiThongBao($ma_nguoi_nhan, $tieu_de, $noi_dung) {
        $this->pdo->prepare(
            "INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung) VALUES (?, 'dich_vu_cong', ?, ?)"
        )->execute([$ma_nguoi_nhan, $tieu_de, $noi_dung]);
    }

    public function thongKeTheoTrangThai() {
        $ket_qua = array_fill_keys(array_keys(self::NHAN_TRANG_THAI), 0);
        $lenh = $this->pdo->query('SELECT trang_thai, COUNT(*) AS so_luong FROM ho_so_dich_vu GROUP BY trang_thai');
        foreach ($lenh->fetchAll() as $dong) {
            $ket_qua[$dong['trang_thai']] = (int)$dong['so_luong'];
        }
        return $ket_qua;
    }

    public function layDanhSachDichVu() {
        return $this->pdo->query('SELECT ma_dich_vu, ten_dich_vu FROM dich_vu ORDER BY ma_dich_vu')->fetchAll();
    }

    public function timKiem($ma_dich_vu, $trang_thai, $tu_khoa, $tu_ngay, $den_ngay) {
        $ds_dieu_kien = ['1 = 1'];
        $ds_tham_so   = [];
        if ($ma_dich_vu > 0) {
            $ds_dieu_kien[] = 'hs.ma_dich_vu = ?';
            $ds_tham_so[]   = $ma_dich_vu;
        }
        if ($trang_thai !== '') {
            $ds_dieu_kien[] = 'hs.trang_thai = ?';
            $ds_tham_so[]   = $trang_thai;
        }
        if ($tu_khoa !== '') {
            $ds_dieu_kien[] = '(nd.ho_ten LIKE ? OR nd.so_dinh_danh LIKE ?)';
            array_push($ds_tham_so, "%$tu_khoa%", "%$tu_khoa%");
        }
        if ($tu_ngay !== '') {
            $ds_dieu_kien[] = 'hs.ngay_nop >= ?';
            $ds_tham_so[]   = $tu_ngay;
        }
        if ($den_ngay !== '') {
            $ds_dieu_kien[] = 'hs.ngay_nop < DATE_ADD(?, INTERVAL 1 DAY)';
            $ds_tham_so[]   = $den_ngay;
        }
        $lenh = $this->pdo->prepare(
            'SELECT hs.*, nd.ho_ten, nd.so_dinh_danh, dv.ten_dich_vu
             FROM ho_so_dich_vu hs
             JOIN nguoi_dung nd ON nd.ma_nguoi_dung = hs.ma_nguoi_dung
             JOIN dich_vu dv ON dv.ma_dich_vu = hs.ma_dich_vu
             WHERE ' . implode(' AND ', $ds_dieu_kien) . ' ORDER BY hs.ma_ho_so DESC LIMIT 100');
        $lenh->execute($ds_tham_so);
        return $lenh->fetchAll();
    }
}