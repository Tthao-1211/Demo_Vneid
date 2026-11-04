<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xử lý hồ sơ dịch vụ công - Dành cho Cán bộ</title>
</head>
<body>
    <h2>Danh sách hồ sơ cần xử lý</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Mã HS</th>
                <th>Công dân</th>
                <th>Dịch vụ</th>
                <th>Nội dung</th>
                <th>Trạng thái hiện tại</th>
                <th>Cập nhật trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ds_ho_so as $hs): ?>
                <tr>
                    <td>#<?= $hs['ma_ho_so'] ?></td>
                    <td><?= htmlspecialchars($hs['ho_ten']) ?> (<?= $hs['so_dinh_danh'] ?>)</td>
                    <td><?= htmlspecialchars($hs['ten_dich_vu']) ?></td>
                    <td><?= htmlspecialchars($hs['noi_dung']) ?></td>
                    <td><strong><?= $hs['trang_thai'] ?></strong></td>
                    <td>
                        <form action="/Mo_phong_app_vneid/dich_vu/ds_ho_so_can_xu_ly" method="POST">
                            <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                            <input type="hidden" name="ma_ho_so" value="<?= $hs['ma_ho_so'] ?>">
                            
                            <select name="trang_thai">
                                <option value="dang_xu_ly" <?= $hs['trang_thai'] === 'dang_xu_ly' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="can_bo_sung" <?= $hs['trang_thai'] === 'can_bo_sung' ? 'selected' : '' ?>>Cần bổ sung</option>
                                <option value="da_duyet" <?= $hs['trang_thai'] === 'da_duyet' ? 'selected' : '' ?>>Đã duyệt</option>
                                <option value="tu_choi" <?= $hs['trang_thai'] === 'tu_choi' ? 'selected' : '' ?>>Từ chối</option>
                            </select>
                            <input type="text" name="ghi_chu" placeholder="Ghi chú cán bộ" value="<?= htmlspecialchars($hs['ghi_chu'] ?? '') ?>">
                            <button type="submit">Cập nhật</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>