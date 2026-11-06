<?php
class CanBoThongBao {
    public static function tao(int $maNguoiNhan, string $tieuDe, string $noiDung): void {
        db()->prepare("INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung, da_doc, ngay_tao)
                       VALUES (?, 'he_thong', ?, ?, 0, NOW())")->execute([$maNguoiNhan, $tieuDe, $noiDung]);
    }
    /** Thông báo hệ thống liên quan xác minh giấy tờ (dùng cho cả Lịch sử xử lý) */
    public static function daGui(int $gioiHan = 100): array {
        return db()->query("SELECT t.*, nd.ho_ten, nd.so_dinh_danh FROM thong_bao t
            JOIN nguoi_dung nd ON nd.ma_nguoi_dung = t.ma_nguoi_nhan
            WHERE t.loai = 'he_thong' AND (t.tieu_de LIKE 'Giấy tờ%' OR t.tieu_de LIKE 'Yêu cầu bổ sung%')
            ORDER BY t.ngay_tao DESC LIMIT " . (int)$gioiHan)->fetchAll();
    }
}
