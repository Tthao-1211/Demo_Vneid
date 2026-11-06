<?php
// app/models/HoSoDichVu.php

class HoSoDichVu {
    // Lấy danh sách hồ sơ của công dân đang đăng nhập
    public function layTheoNguoiDung($ma_nguoi_dung) {
        global $pdo;
        $sql = "SELECT hs.*, dv.ten_dich_vu 
                FROM ho_so_dich_vu hs
                JOIN dich_vu dv ON hs.ma_dich_vu = dv.ma_dich_vu
                WHERE hs.ma_nguoi_dung = ?
                ORDER BY hs.ngay_nop DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_nguoi_dung]);
        return $stmt->fetchAll();
    }

    // Công dân nộp hồ sơ mới
    public function themMoi($ma_nguoi_dung, $ma_dich_vu, $noi_dung, $tep_dinh_kem = null) {
        global $pdo;
        $sql = "INSERT INTO ho_so_dich_vu (ma_nguoi_dung, ma_dich_vu, noi_dung, tep_dinh_kem) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$ma_nguoi_dung, $ma_dich_vu, $noi_dung, $tep_dinh_kem]);
    }

    // Cán bộ lấy toàn bộ danh sách hồ sơ cần xử lý
    public function layDanhSachCanBo() {
        global $pdo;
        $sql = "SELECT hs.*, dv.ten_dich_vu, nd.ho_ten, nd.so_dinh_danh 
                FROM ho_so_dich_vu hs
                JOIN dich_vu dv ON hs.ma_dich_vu = dv.ma_dich_vu
                JOIN nguoi_dung nd ON hs.ma_nguoi_dung = nd.ma_nguoi_dung
                ORDER BY hs.ngay_nop DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Cán bộ cập nhật trạng thái hồ sơ
    public function capNhatTrangThai($ma_ho_so, $trang_thai, $ghi_chu, $ma_can_bo) {
        global $pdo;
        $sql = "UPDATE ho_so_dich_vu 
                SET trang_thai = ?, ghi_chu = ?, ma_can_bo = ? 
                WHERE ma_ho_so = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$trang_thai, $ghi_chu, $ma_can_bo, $ma_ho_so]);
    }
}