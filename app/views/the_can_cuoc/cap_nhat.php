<!-- app/views/the_can_cuoc/cap_nhat.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật thông tin Thẻ căn cước - VNeID</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body {
            background-color: #E9EAE8 !important; margin: 0; padding: 0;
            display: flex; flex-direction: column; align-items: center; min-height: 100vh; font-family: 'Times New Roman', Times, serif;
        }
        .boca-brand { text-align: center; color: #ffffff; margin-top: 40px; margin-bottom: 25px; }
        .boca-title { font-size: 20px; font-weight: 700; text-transform: uppercase; }
        .vneid-form-container { width: 100%; max-width: 680px; background-color: #ffffff; padding: 45px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); box-sizing: border-box; margin-bottom: 50px; position: relative; }
        .btn-back-arrow { position: absolute; left: 40px; top: 45px; font-size: 18px; color: #555; cursor: pointer; background: #f5f5f7; padding: 8px 14px; border-radius: 6px; border: none; text-decoration: none; }
        .vneid-section-title { text-align: center; color: #222222; font-size: 24px; font-weight: 700; margin-top: 15px; margin-bottom: 45px; }
        .form-grid-custom { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .span-2 { grid-column: span 2; }
        .form-group-custom label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 8px; }
        input[type="text"], input[type="date"], select { width: 100%; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 8px; background-color: #ffffff; font-size: 15px; box-sizing: border-box; }
        .btn-vneid-submit-custom { display: block; width: 100%; padding: 14px; background-color: #e3261f; color: #ffffff; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 25px; }
    </style>
</head>

<body>
    <div class="boca-brand">
        <div class="boca-title">Bộ Công An</div>
    </div>

    <div class="vneid-form-container">
        <a class="btn-back-arrow" href="/the_can_cuoc/chi_tiet?id=<?php echo $the_can_cuoc['id']; ?>">← Chi tiết</a>
        <h2 class="vneid-section-title">Cập nhật thông tin Thẻ căn cước</h2>

        <form method="POST" action="/the_can_cuoc/cap_nhat?id=<?php echo $the_can_cuoc['id']; ?>">
            <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
            <div class="form-grid-custom">
                <div class="form-group-custom span-2">
                    <label>Số định danh cá nhân (Không được phép sửa)</label>
                    <input type="text" value="<?php echo htmlspecialchars($the_can_cuoc['so_cccd']); ?>" disabled style="background-color: #f1f5f9 !important; color: #64748b;">
                </div>

                <div class="form-group-custom span-2">
                    <label>Họ, chữ đệm và tên khai sinh</label>
                    <input type="text" name="ho_ten" value="<?php echo htmlspecialchars($the_can_cuoc['ho_ten']); ?>" required>
                </div>

                <div class="form-group-custom">
                    <label>Ngày, tháng, năm sinh</label>
                    <input type="date" name="ngay_sinh" value="<?php echo htmlspecialchars($the_can_cuoc['ngay_sinh']); ?>" required>
                </div>

                <div class="form-group-custom">
                    <label>Giới tính</label>
                    <select name="gioi_tinh" required>
                        <option value="Nam" <?php echo $the_can_cuoc['gioi_tinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Nữ" <?php echo $the_can_cuoc['gioi_tinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                    </select>
                </div>

                <div class="form-group-custom span-2">
                    <label>Nơi cư trú hiện tại</label>
                    <input type="text" name="noi_thuong_tru" value="<?php echo htmlspecialchars($the_can_cuoc['noi_thuong_tru']); ?>" required>
                </div>

                <div class="form-group-custom span-2">
                    <label>Trạng thái hiệu lực thẻ</label>
                    <select name="trang_thai" required style="border: 1.5px solid #da251d;">
                        <option value="con_hieu_luc" <?php echo $the_can_cuoc['trang_thai'] === 'con_hieu_luc' ? 'selected' : ''; ?>>Còn hiệu lực (Hoạt động)</option>
                        <option value="het_han" <?php echo $the_can_cuoc['trang_thai'] === 'het_han' ? 'selected' : ''; ?>>Hết hạn sử dụng</option>
                        <option value="bao_mat" <?php echo $the_can_cuoc['trang_thai'] === 'bao_mat' ? 'selected' : ''; ?>>Báo mất / Tạm khóa thẻ</option>
                        <option value="thu_hoi" <?php echo $the_can_cuoc['trang_thai'] === 'thu_hoi' ? 'selected' : ''; ?>>Thu hồi thẻ</option>
                    </select>
                </div>

                <div class="form-group-custom span-2">
                    <button type="submit" class="btn-vneid-submit-custom">Xác nhận cập nhật hồ sơ</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
