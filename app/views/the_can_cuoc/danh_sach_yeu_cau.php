<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách yêu cầu cấp thẻ - VNeID</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;padding:20px;background:#E9EAE8;font-family:'Times New Roman',Times,serif;box-sizing:border-box}
        .brand{text-align:center;color:white;margin:20px 0}
        .brand strong{font-size:20px;text-transform:uppercase;display:block}
        .brand span{font-size:13px;color:#ffca2c}
        .container{max-width:1100px;margin:auto;background:white;border-radius:16px;padding:35px;box-sizing:border-box}
        h1{text-align:center;font-size:24px;margin-top:0}
        table{width:100%;border-collapse:collapse;font-size:14px}
        th,td{padding:12px 10px;border-bottom:1px solid #e2e8f0;text-align:left}
        th{background:#f8fafc;color:#64748b}
        .badge{padding:4px 9px;border-radius:20px;font-size:12px;font-weight:bold}
        .pending{background:#fef3c7;color:#92400e}.processing{background:#dbeafe;color:#1e40af}
        .done{background:#dcfce7;color:#166534}.rejected{background:#fee2e2;color:#991b1b}
        .btn{display:inline-block;padding:9px 12px;border-radius:7px;text-decoration:none;background:#e3261f;color:white;font-weight:bold}
        .home{display:inline-block;margin-bottom:20px;color:#475569;text-decoration:none}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="brand"><strong>Bộ Công An</strong><span>Trung tâm dữ liệu Quốc gia về dân cư</span></div>
    <div class="container">
        <a class="home" href="/">← Trang chủ</a>
        <h1>Danh sách yêu cầu cấp thẻ</h1>
        <table>
            <thead><tr><th>Mã hồ sơ</th><th>Số định danh</th><th>Loại yêu cầu</th><th>Ngày gửi</th><th>Trạng thái xử lý</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php foreach ($ds_yeu_cau as $yc): ?>
                <tr>
                    <td><?php echo htmlspecialchars($yc['ma_ho_so']); ?></td>
                    <td><?php echo htmlspecialchars($yc['so_cccd']); ?></td>
                    <td><?php echo $yc['loai_yeu_cau'] === 'cap_moi' ? 'Cấp mới' : ($yc['loai_yeu_cau'] === 'cap_doi' ? 'Cấp đổi' : 'Cấp lại'); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($yc['ngay_yeu_cau'])); ?></td>
                    <td>
                        <?php
                        $map = [
                            'cho_tiep_nhan' => ['Chờ tiếp nhận', 'pending'],
                            'dang_xu_ly' => ['Đang xử lý', 'processing'],
                            'da_in_the' => ['Đã in thẻ', 'processing'],
                            'hoan_thanh' => ['Hoàn thành', 'done'],
                            'tu_choi' => ['Từ chối', 'rejected']
                        ];
                        $tt = $map[$yc['trang_thai']] ?? [$yc['trang_thai'], 'pending'];
                        echo '<span class="badge ' . $tt[1] . '">' . $tt[0] . '</span>';
                        ?>
                    </td>
                    <td><a class="btn" href="/the_can_cuoc/xu_ly_yeu_cau?id=<?php echo $yc['ma_yeu_cau']; ?>">Xem và xử lý</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
