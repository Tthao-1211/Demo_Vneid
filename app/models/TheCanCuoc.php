
<?php

class TheCanCuoc
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /*
     * FR3.1 - Lấy thông tin CCCD theo tài khoản
     * Mỗi tài khoản chỉ lấy thẻ thuộc chính mình.
     */
    public function layTheoNguoiDung($maNguoiDung)
    {
        $sql = "
            SELECT
                t.*,
                n.so_dinh_danh,
                n.ho_ten,
                n.muc_dinh_danh
            FROM the_can_cuoc t
            INNER JOIN nguoi_dung n
                ON n.ma_nguoi_dung = t.ma_nguoi_dung
            WHERE t.ma_nguoi_dung = ?
            ORDER BY t.ngay_cap DESC
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maNguoiDung]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /*
     * FR3.2 - Lấy hồ sơ CCCD theo mã thẻ
     */
    public function layTheoMa($maThe)
    {
        $sql = "
            SELECT
                t.*,
                n.so_dinh_danh,
                n.ho_ten,
                n.muc_dinh_danh
            FROM the_can_cuoc t
            INNER JOIN nguoi_dung n
                ON n.ma_nguoi_dung = t.ma_nguoi_dung
            WHERE t.ma_the = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maThe]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /*
     * FR3.2 - Danh sách hồ sơ CCCD
     */
    public function danhSach()
    {
        $sql = "
            SELECT
                t.*,
                n.so_dinh_danh,
                n.ho_ten,
                n.muc_dinh_danh
            FROM the_can_cuoc t
            INNER JOIN nguoi_dung n
                ON n.ma_nguoi_dung = t.ma_nguoi_dung
            ORDER BY t.ma_the DESC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * FR3.2 - Tra cứu hồ sơ CCCD
     */
    public function timKiem($tuKhoa)
    {
        $tuKhoa = trim($tuKhoa);
        $tuKhoa = "%{$tuKhoa}%";

        $sql = "
            SELECT
                t.*,
                n.so_dinh_danh,
                n.ho_ten,
                n.muc_dinh_danh
            FROM the_can_cuoc t
            INNER JOIN nguoi_dung n
                ON n.ma_nguoi_dung = t.ma_nguoi_dung
            WHERE
                n.so_dinh_danh LIKE ?
                OR n.ho_ten LIKE ?
                OR t.so_the LIKE ?
            ORDER BY t.ma_the DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tuKhoa, $tuKhoa, $tuKhoa]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * FR3.2 - Thêm hồ sơ CCCD
     */
    public function themMoi($duLieu)
    {
        $sql = "
            INSERT INTO the_can_cuoc (
                ma_nguoi_dung,
                so_the,
                ngay_sinh,
                gioi_tinh,
                quoc_tich,
                dan_toc,
                ton_giao,
                que_quan,
                noi_thuong_tru,
                tinh_thanh,
                dac_diem_nhan_dang,
                anh_chan_dung,
                ngay_cap,
                ngay_het_han,
                noi_cap,
                trang_thai
            ) VALUES (
                :ma_nguoi_dung,
                :so_the,
                :ngay_sinh,
                :gioi_tinh,
                :quoc_tich,
                :dan_toc,
                :ton_giao,
                :que_quan,
                :noi_thuong_tru,
                :tinh_thanh,
                :dac_diem_nhan_dang,
                :anh_chan_dung,
                :ngay_cap,
                :ngay_het_han,
                :noi_cap,
                :trang_thai
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':ma_nguoi_dung' => $duLieu['ma_nguoi_dung'],
            ':so_the' => $duLieu['so_the'],
            ':ngay_sinh' => $duLieu['ngay_sinh'],
            ':gioi_tinh' => $duLieu['gioi_tinh'],
            ':quoc_tich' => $duLieu['quoc_tich'],
            ':dan_toc' => $duLieu['dan_toc'],
            ':ton_giao' => $duLieu['ton_giao'],
            ':que_quan' => $duLieu['que_quan'],
            ':noi_thuong_tru' => $duLieu['noi_thuong_tru'],
            ':tinh_thanh' => $duLieu['tinh_thanh'],
            ':dac_diem_nhan_dang' => $duLieu['dac_diem_nhan_dang'] ?? null,
            ':anh_chan_dung' => $duLieu['anh_chan_dung'] ?? null,
            ':ngay_cap' => $duLieu['ngay_cap'],
            ':ngay_het_han' => $duLieu['ngay_het_han'],
            ':noi_cap' => $duLieu['noi_cap'],
            ':trang_thai' => $duLieu['trang_thai']
        ]);
    }

    /*
     * FR3.2 - Cập nhật hồ sơ CCCD
     */
    public function capNhat($maThe, $duLieu)
    {
        $sql = "
            UPDATE the_can_cuoc SET
                so_the = :so_the,
                ngay_sinh = :ngay_sinh,
                gioi_tinh = :gioi_tinh,
                quoc_tich = :quoc_tich,
                dan_toc = :dan_toc,
                ton_giao = :ton_giao,
                que_quan = :que_quan,
                noi_thuong_tru = :noi_thuong_tru,
                tinh_thanh = :tinh_thanh,
                dac_diem_nhan_dang = :dac_diem_nhan_dang,
                anh_chan_dung = :anh_chan_dung,
                ngay_cap = :ngay_cap,
                ngay_het_han = :ngay_het_han,
                noi_cap = :noi_cap,
                trang_thai = :trang_thai
            WHERE ma_the = :ma_the
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':so_the' => $duLieu['so_the'],
            ':ngay_sinh' => $duLieu['ngay_sinh'],
            ':gioi_tinh' => $duLieu['gioi_tinh'],
            ':quoc_tich' => $duLieu['quoc_tich'],
            ':dan_toc' => $duLieu['dan_toc'],
            ':ton_giao' => $duLieu['ton_giao'],
            ':que_quan' => $duLieu['que_quan'],
            ':noi_thuong_tru' => $duLieu['noi_thuong_tru'],
            ':tinh_thanh' => $duLieu['tinh_thanh'],
            ':dac_diem_nhan_dang' => $duLieu['dac_diem_nhan_dang'] ?? null,
            ':anh_chan_dung' => $duLieu['anh_chan_dung'] ?? null,
            ':ngay_cap' => $duLieu['ngay_cap'],
            ':ngay_het_han' => $duLieu['ngay_het_han'],
            ':noi_cap' => $duLieu['noi_cap'],
            ':trang_thai' => $duLieu['trang_thai'],
            ':ma_the' => $maThe
        ]);
    }

    /*
     * FR3.4 - Cập nhật trạng thái thẻ
     */
    public function capNhatTrangThai($maThe, $trangThai)
    {
        $cacTrangThai = [
            'con_hieu_luc',
            'het_han',
            'bao_mat',
            'thu_hoi'
        ];

        if (!in_array($trangThai, $cacTrangThai, true)) {
            return false;
        }

        $sql = "
            UPDATE the_can_cuoc
            SET trang_thai = ?
            WHERE ma_the = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$trangThai, $maThe]);
    }

    /*
     * Che số thẻ CCCD
     * Ví dụ: 056200000001 -> 0********001
     */
    public function cheSoThe($soThe)
    {
        $soThe = (string)$soThe;

        if (strlen($soThe) <= 4) {
            return $soThe;
        }

        return substr($soThe, 0, 1)
            . str_repeat('*', strlen($soThe) - 4)
            . substr($soThe, -3);
    }

    /*
     * Xác nhận mật khẩu trước khi xem thông tin đầy đủ
     */
    public function xacNhanMatKhau($maNguoiDung, $matKhau)
    {
        $sql = "
            SELECT mat_khau
            FROM nguoi_dung
            WHERE ma_nguoi_dung = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$maNguoiDung]);

        $matKhauBam = $stmt->fetchColumn();

        return $matKhauBam !== false
            && password_verify($matKhau, $matKhauBam);
    }
}