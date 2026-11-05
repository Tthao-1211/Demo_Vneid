<?php
class CanBoLoaiGiayTo {
    public static function tatCa(): array {
        return db()->query('SELECT l.*, (SELECT COUNT(*) FROM giay_to_vi g WHERE g.ma_loai = l.ma_loai) so_giay_to
                            FROM loai_giay_to l ORDER BY l.ma_loai')->fetchAll();
    }

    /** cac_truong: ["hang","bien_so"] (chuẩn của dự án) hoặc [{"khoa":"hang","nhan":"Hạng"}] hoặc {"hang":"Hạng"} */
    public static function giaiMaTruong(?string $json): array {
        $d = json_decode($json ?? '[]', true) ?: [];
        $kq = [];
        foreach ($d as $k => $v) {
            if (is_array($v)) $kq[$v['khoa'] ?? $v['ten'] ?? $k] = $v['nhan'] ?? $v['label'] ?? ($v['khoa'] ?? $k);
            elseif (is_int($k)) $kq[$v] = nhanTruong($v);
            else $kq[$k] = $v;
        }
        return $kq;
    }

    public static function luu(?int $ma, string $ten, array $truong): void {
        // Lưu dạng ["hang","bien_so"] để phía công dân (foreach cac_truong) đọc được
        $khoa = array_values(array_unique(array_filter(array_column($truong, 'khoa'))));
        $json = json_encode($khoa, JSON_UNESCAPED_UNICODE);
        if ($ma) db()->prepare('UPDATE loai_giay_to SET ten_loai = ?, cac_truong = ? WHERE ma_loai = ?')->execute([$ten, $json, $ma]);
        else db()->prepare('INSERT INTO loai_giay_to (ten_loai, cac_truong) VALUES (?, ?)')->execute([$ten, $json]);
    }

    public static function xoa(int $ma): bool {
        $st = db()->prepare('SELECT COUNT(*) FROM giay_to_vi WHERE ma_loai = ?'); $st->execute([$ma]);
        if ($st->fetchColumn() > 0) return false;
        db()->prepare('DELETE FROM loai_giay_to WHERE ma_loai = ?')->execute([$ma]);
        return true;
    }
}
