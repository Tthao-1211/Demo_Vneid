<?php
// Khu vực cán bộ xử lý: truy cập tại /can_bo/<hanh_dong>
class CanBoController {
    private function bang(string $menu, string $tieuDe, string $macDinh): void {
        yeuCauCanBo();
        $loc = [
            'tu_khoa' => trim($_GET['tu_khoa'] ?? ''),
            'ma_loai' => $_GET['ma_loai'] ?? '',
            'trang_thai' => $_GET['trang_thai'] ?? $macDinh,
            'sap_xep' => $_GET['sap_xep'] ?? 'moi',
        ];
        $trang = max(1, (int)($_GET['p'] ?? 1));
        view('danh_sach', [
            'kq' => CanBoGiayTo::danhSach($loc, $trang),
            'loc' => $loc,
            'p' => $trang,
            'loai' => CanBoLoaiGiayTo::tatCa(),
            'tieuDe' => $tieuDe,
            'menu' => $menu,
        ]);
    }
    public function duyet(): void { $this->bang('duyet', 'Duyệt giấy tờ', 'cho_xac_minh'); }

    public function chiTiet(): void {
        yeuCauCanBo();
        $g = CanBoGiayTo::chiTiet((int)($_GET['id'] ?? 0));
        if (!$g) {
            flash('Không tìm thấy giấy tờ.', 'loi');
            chuyenTrang('duyet');
        }
        $nhan = CanBoLoaiGiayTo::giaiMaTruong($g['cac_truong']);
        $giaTri = json_decode($g['thong_tin'] ?? '{}', true) ?: [];
        foreach ($giaTri as $k => $_) $nhan[$k] ??= nhanTruong($k); // khóa ngoài định nghĩa
        view('chi_tiet', ['g' => $g, 'truong' => $nhan, 'giaTri' => $giaTri, 'menu' => 'duyet']);
    }

    public function xuLy(): void {
        yeuCauCanBo(); kiemTraCsrf();
        $ma = (int)($_POST['ma_giay_to'] ?? 0);
        $hd = $_POST['hanh_dong'] ?? '';
        $ghi = trim($_POST['ghi_chu'] ?? '');
        if ($hd !== 'da_xac_minh' && $ghi === '') {
            flash('Vui lòng nhập lý do / nội dung.', 'loi');
            chuyenTrang('chi_tiet', ['id' => $ma]);
        }
        flash(
            CanBoGiayTo::xuLy($ma, $hd, $ghi)
                ? 'Đã xử lý và gửi thông báo VNeID đến công dân.'
                : 'Không thể xử lý giấy tờ.',
            'ok'
        );
        chuyenTrang('chi_tiet', ['id' => $ma]);
    }

    public function loaiGiayTo(): void {
        yeuCauCanBo();
        view('loai_giay_to', ['ds' => CanBoLoaiGiayTo::tatCa(), 'menu' => 'loai_giay_to']);
    }

    public function luuLoai(): void {
        yeuCauCanBo(); kiemTraCsrf();
        $truong = [];
        foreach (($_POST['nhan'] ?? []) as $i => $nhan) {
            $nhan = trim($nhan);
            if ($nhan === '') continue;
            $khoa = trim($_POST['khoa'][$i] ?? '')
                ?: strtolower(preg_replace('/[^a-z0-9]+/i', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $nhan)));
            $truong[] = ['khoa' => trim($khoa, '_'), 'nhan' => $nhan];
        }
        if (trim($_POST['ten_loai'] ?? '') === '') {
            flash('Nhập tên loại giấy tờ.', 'loi');
        } else {
            CanBoLoaiGiayTo::luu(((int)($_POST['ma_loai'] ?? 0)) ?: null, trim($_POST['ten_loai']), $truong);
            flash('Đã lưu loại giấy tờ.');
        }
        chuyenTrang('loai_giay_to');
    }

    public function xoaLoai(): void {
        yeuCauCanBo(); kiemTraCsrf();
        CanBoLoaiGiayTo::xoa((int)($_POST['ma_loai'] ?? 0))
            ? flash('Đã xóa.')
            : flash('Không thể xóa: đang có giấy tờ thuộc loại này.', 'loi');
        chuyenTrang('loai_giay_to');
    }

    public function lichSu(): void {
        yeuCauCanBo();
        view('lich_su', ['ds' => CanBoThongBao::daGui(), 'menu' => 'lich_su']);
    }
}