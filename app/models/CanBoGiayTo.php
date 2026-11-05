<?php
class CanBoGiayTo {
    /** Thống kê: SELECT trang_thai, COUNT(*) ... GROUP BY trang_thai */
    public static function demTheoTrangThai(): array {
        $kq = array_fill_keys(array_keys(TRANG_THAI), 0);
        foreach (db()->query('SELECT trang_thai, COUNT(*) so_luong FROM giay_to_vi GROUP BY trang_thai') as $r) {
            $kq[$r['trang_thai']] = (int)$r['so_luong'];
        }
        return $kq;
    }

    /** Danh sách có tìm kiếm, lọc, sắp xếp, phân trang */
    public static function danhSach(array $loc, int $trang = 1, int $moiTrang = 10): array {
        $where = []; $p = [];
        if (!empty($loc['tu_khoa'])) {
            $where[] = '(nd.ho_ten LIKE ? OR nd.so_dinh_danh LIKE ? OR g.so_giay_to LIKE ?)';
            $k = '%' . $loc['tu_khoa'] . '%'; array_push($p, $k, $k, $k);
        }
        if (!empty($loc['ma_loai'])) { $where[] = 'g.ma_loai = ?'; $p[] = (int)$loc['ma_loai']; }
        if (!empty($loc['trang_thai'])) { $where[] = 'g.trang_thai = ?'; $p[] = $loc['trang_thai']; }
        $w = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $sx = ($loc['sap_xep'] ?? 'moi') === 'cu' ? 'ASC' : 'DESC';
        $from = "FROM giay_to_vi g JOIN nguoi_dung nd ON nd.ma_nguoi_dung = g.ma_nguoi_dung
                 JOIN loai_giay_to l ON l.ma_loai = g.ma_loai $w";
        $st = db()->prepare("SELECT COUNT(*) $from"); $st->execute($p); $tong = (int)$st->fetchColumn();
        $offset = max(0, ($trang - 1) * $moiTrang);
        $st = db()->prepare("SELECT g.*, nd.ho_ten, nd.so_dinh_danh, l.ten_loai $from
                             ORDER BY g.ma_giay_to $sx LIMIT $moiTrang OFFSET $offset");
        $st->execute($p);
        return ['dong' => $st->fetchAll(), 'tong' => $tong, 'so_trang' => max(1, (int)ceil($tong / $moiTrang))];
    }

    public static function ganDay(int $n = 5): array {
        return db()->query("SELECT g.*, nd.ho_ten, l.ten_loai FROM giay_to_vi g
            JOIN nguoi_dung nd ON nd.ma_nguoi_dung = g.ma_nguoi_dung JOIN loai_giay_to l ON l.ma_loai = g.ma_loai
            ORDER BY g.ma_giay_to DESC LIMIT " . (int)$n)->fetchAll();
    }

    public static function chiTiet(int $ma): ?array {
        $st = db()->prepare('SELECT g.*, l.ten_loai, l.cac_truong,
                nd.ho_ten, nd.so_dinh_danh, nd.so_dien_thoai, nd.email, nd.muc_dinh_danh,
                c.ngay_sinh, c.gioi_tinh, c.noi_thuong_tru, c.anh_chan_dung
            FROM giay_to_vi g
            JOIN loai_giay_to l ON l.ma_loai = g.ma_loai
            JOIN nguoi_dung nd ON nd.ma_nguoi_dung = g.ma_nguoi_dung
            LEFT JOIN the_can_cuoc c ON c.ma_nguoi_dung = g.ma_nguoi_dung
            WHERE g.ma_giay_to = ?');
        $st->execute([$ma]);
        return $st->fetch() ?: null;
    }

    /** Cập nhật trạng thái + tạo thông báo trong cùng một transaction */
    public static function xuLy(int $ma, string $hanhDong, string $ghiChu): bool {
        $g = self::chiTiet($ma);
        if (!$g) return false;
        $mau = [
            'da_xac_minh' => ['Giấy tờ đã được xác minh',
                "Giấy tờ {$g['ten_loai']} số {$g['so_giay_to']} của bạn đã được xác minh thành công và được cập nhật vào ví giấy tờ điện tử VNeID."],
            'bi_tu_choi' => ['Giấy tờ bị từ chối xác minh',
                "Giấy tờ {$g['ten_loai']} số {$g['so_giay_to']} của bạn bị từ chối xác minh. Lý do: $ghiChu"],
            'yeu_cau_bo_sung' => ['Yêu cầu bổ sung thông tin giấy tờ',
                "Giấy tờ {$g['ten_loai']} số {$g['so_giay_to']} của bạn cần bổ sung thông tin. Nội dung: $ghiChu"],
        ];
        if (!isset($mau[$hanhDong])) return false;
        $pdo = db();
        $pdo->beginTransaction();
        try {
            // "Yêu cầu bổ sung" chỉ gửi thông báo, giữ nguyên trạng thái chờ xác minh
            if ($hanhDong !== 'yeu_cau_bo_sung') {
                $pdo->prepare('UPDATE giay_to_vi SET trang_thai = ? WHERE ma_giay_to = ?')->execute([$hanhDong, $ma]);
            }
            CanBoThongBao::tao((int)$g['ma_nguoi_dung'], $mau[$hanhDong][0], $mau[$hanhDong][1]);
            $pdo->commit();
            return true;
        } catch (Throwable $ex) { $pdo->rollBack(); throw $ex; }
    }

    public static function theoLoai(): array {
        return db()->query('SELECT l.ten_loai, g.trang_thai, COUNT(g.ma_giay_to) so_luong
            FROM loai_giay_to l LEFT JOIN giay_to_vi g ON g.ma_loai = l.ma_loai
            GROUP BY l.ma_loai, l.ten_loai, g.trang_thai ORDER BY l.ma_loai')->fetchAll();
    }
}