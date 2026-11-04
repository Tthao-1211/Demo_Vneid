<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dịch vụ công - VNeID</title>
</head>
<body>
    <h2>Danh mục Dịch vụ công</h2>
    <ul>
        <?php foreach ($ds_dich_vu as $dv): ?>
            <li>
                <strong><?= htmlspecialchars($dv['ten_dich_vu']) ?></strong> 
                (Yêu cầu định danh mức <?= $dv['muc_toi_thieu'] ?>)
                - <a href="/Mo_phong_app_vneid/dich_vu/nop_ho_so?ma_dich_vu=<?= $dv['ma_dich_vu'] ?>">Nộp hồ sơ</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Lịch sử nộp hồ sơ của bạn</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Mã HS</th>
                <th>Dịch vụ</th>
                <th>Ngày nộp</th>
                <th>Trạng thái</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ds_ho_so as $hs): ?>
                <tr>
                    <td>#<?= $hs['ma_ho_so'] ?></td>
                    <td><?= htmlspecialchars($hs['ten_dich_vu']) ?></td>
                    <td><?= $hs['ngay_nop'] ?></td>
                    <td><?= htmlspecialchars($hs['trang_thai']) ?></td>
                    <td><?= htmlspecialchars($hs['ghi_chu'] ?? 'Khong co') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>