<!-- app/views/the_can_cuoc/them_moi.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm mới Thẻ căn cước - VNeID</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body {
            background-color: #E9EAE8 !important;
            margin: 0; padding: 0;
            display: flex; flex-direction: column; align-items: center; min-height: 100vh;
        }

        .boca-brand { text-align: center; color: #ffffff; margin-top: 40px; margin-bottom: 25px; }
        .boca-title { font-size: 20px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin: 5px 0; }
        .boca-sub { font-size: 13px; color: #ffca2c; margin: 0; }

        .vneid-form-container {
            width: 100%; max-width: 720px;
            background-color: #ffffff !important;
            padding: 45px; border-radius: 16px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); box-sizing: border-box; margin-bottom: 50px; position: relative;
        }

        .btn-back-arrow {
            position: absolute; left: 40px; top: 45px; font-size: 18px; color: #555;
            cursor: pointer; background: #f5f5f7; padding: 8px 14px; border-radius: 6px; border: none; text-decoration: none;
        }

        .vneid-section-title {
            text-align: center; color: #222222 !important; font-size: 24px !important;
            font-weight: 700 !important; margin-top: 15px !important; margin-bottom: 45px !important;
        }

        .form-grid-custom { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .span-2 { grid-column: span 2; }
        .form-group-custom { margin-bottom: 5px; }
        .form-group-custom label { display: block; font-size: 13px; font-weight: 600; color: #4a5568; margin-bottom: 8px; }

        input[type="text"], input[type="date"], select {
            width: 100%; padding: 14px 16px !important; border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important; background-color: #ffffff !important;
            font-size: 15px !important; box-sizing: border-box; color: #222222 !important;
        }

        input:focus, select:focus {
            border-color: #e3261f !important; box-shadow: 0 0 0 3px rgba(227, 38, 31, 0.15) !important; outline: none;
        }

        .btn-vneid-submit-custom {
            display: block; width: 100%; padding: 14px !important; background-color: #e3261f !important;
            color: #ffffff !important; border: none !important; border-radius: 8px !important; font-size: 16px !important;
            font-weight: 700 !important; cursor: pointer; box-shadow: 0 4px 12px rgba(227, 38, 31, 0.2);
            transition: background-color 0.2s; margin-top: 25px;
        }
        .btn-vneid-submit-custom:hover { background-color: #c91e17 !important; }
    </style>
</head>

<body>
    <div class="boca-brand">
        <div class="boca-title">Bộ Công An</div>
        <div class="boca-sub">Trung tâm dữ liệu Quốc gia về dân cư</div>
    </div>

    <div class="vneid-form-container">
        <a href="/" class="btn-back-arrow">⌂</a>
        <h2 class="vneid-section-title">Thêm mới thông tin Thẻ căn cước</h2>

        <form action="/the_can_cuoc/them_moi" method="POST">
            <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
            <div class="form-grid-custom">

                <div class="form-group-custom span-2">
                    <label>Số định danh cá nhân (12 chữ số)</label>
                    <input type="text" name="so_cccd" maxlength="12" pattern="\d{12}" required placeholder="Số định danh cá nhân">
                </div>

                <div class="form-group-custom span-2">
                    <label>Họ, chữ đệm và tên khai sinh</label>
                    <input type="text" name="ho_ten" required placeholder="Họ và tên viết hoa">
                </div>

                <div class="form-group-custom">
                    <label>Ngày, tháng, năm sinh</label>
                    <input type="date" name="ngay_sinh" required>
                </div>

                <div class="form-group-custom">
                    <label>Giới tính</label>
                    <select name="gioi_tinh" required>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>

                <div class="form-group-custom span-2">
                    <label>Quê quán (34 Tỉnh/Thành phố sau sáp nhập)</label>
                    <select name="que_quan" id="queQuanSelect" required>
                        <option value="">-- Chọn Tỉnh/Thành phố --</option>
                        <?php foreach (DS_TINH_THANH as $ten_tinh): ?>
                            <option value="<?php echo htmlspecialchars($ten_tinh); ?>"><?php echo htmlspecialchars($ten_tinh); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-custom span-2">
                    <label>Nơi cư trú hiện tại</label>
                    <input type="text" name="noi_thuong_tru" required placeholder="Địa chỉ thường trú">
                </div>

                <div class="form-group-custom">
                    <label>Ngày cấp thẻ</label>
                    <input type="date" name="ngay_cap" required>
                </div>

                <div class="form-group-custom">
                    <label>Nơi cấp</label>
                    <input type="text" name="noi_cap" required value="Cục Cảnh sát QLHC về TTXH">
                </div>

                <div class="form-group-custom span-2">
                    <label>Thời hạn sử dụng (Ngày hết hạn)</label>
                    <input type="date" name="ngay_het_han" required>
                </div>

                <div class="form-group-custom span-2">
                    <button type="submit" class="btn-vneid-submit-custom">Gửi yêu cầu lưu trữ hồ sơ</button>
                </div>

            </div>
        </form>
    </div>
</body>
</html>
