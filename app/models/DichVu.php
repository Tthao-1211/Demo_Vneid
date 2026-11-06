<?php
// app/models/DichVu.php

class DichVu {
    // Lấy danh mục tất cả dịch vụ công
    public function layDanhSach() {
        global $pdo;
        $sql = "SELECT * FROM dich_vu ORDER BY ma_dich_vu ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy thông tin chi tiết một dịch vụ
    public function layTheoMa($ma_dich_vu) {
        global $pdo;
        $sql = "SELECT * FROM dich_vu WHERE ma_dich_vu = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ma_dich_vu]);
        return $stmt->fetch();
    }
}