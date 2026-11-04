<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nộp hồ sơ Dịch vụ công</title>
</head>
<body>
    <h2>Đơn nộp hồ sơ Dịch vụ công</h2>
    <form action="/Mo_phong_app_vneid/dich_vu/nop_ho_so" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">

        <label for="ma_dich_vu">Chọn dịch vụ:</label><br>
        <select name="ma_dich_vu" id="ma_dich_vu" required>
            <?php foreach ($ds_dich_vu as $dv): ?>
                <option value="<?= $dv['ma_dich_vu'] ?>" 
                    <?= (isset($_GET['ma_dich_vu']) && $_GET['ma_dich_vu'] == $dv['ma_dich_vu']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dv['ten_dich_vu']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="noi_dung">Nội dung khai báo:</label><br>
        <textarea name="noi_dung" id="noi_dung" rows="4" cols="50" required></textarea><br><br>

        <label for="tep_dinh_kem">Tệp đính kèm (nếu có):</label><br>
        <input type="file" name="tep_dinh_kem" id="tep_dinh_kem"><br><br>

        <button type="submit">Gửi hồ sơ</button>
    </form>
</body>
</html>