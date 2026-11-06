<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nộp hồ sơ Dịch vụ công - VNeID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --vneid-red: #A31D1D; --vneid-dark: #7A0000; --vneid-bg: #F8F5EE; }
        body { background-color: var(--vneid-bg); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #333; }
        .vneid-header { background: linear-gradient(180deg, var(--vneid-dark), var(--vneid-red)); color: white; }
        .btn-vneid-red { background-color: var(--vneid-red); color: white; border: none; border-radius: 25px; font-weight: 600; }
        .btn-vneid-red:hover { background-color: var(--vneid-dark); color: white; }
        .form-card { background: white; border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid #EFEAE1; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 12px; border: 1px solid #DCD6CD; background-color: #FAF8F5; }
        .form-check-input:checked { background-color: var(--vneid-red); border-color: var(--vneid-red); }
        .step-panel { display: none; }
        .step-panel.active { display: block; }
        .custom-sheet-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; }
        .custom-sheet { position: fixed; bottom: -100%; left: 0; right: 0; background: white; border-radius: 20px 20px 0 0; padding: 20px; z-index: 1051; transition: bottom 0.3s ease-in-out; max-height: 80vh; overflow-y: auto; }
        .custom-sheet.show { bottom: 0; }
        .cursor-pointer { cursor: pointer; }
        .payment-option { border: 1.5px solid #DCD6CD; border-radius: 10px; padding: 10px 12px; cursor: pointer; transition: all 0.2s ease; background-color: #FAF8F5; display: flex; align-items: center; justify-content: space-between; }
        .payment-option:hover { border-color: var(--vneid-red); }
        .payment-option.active { border: 2px solid var(--vneid-red); background-color: #FFF5F5; }
        .payment-option .check-icon { display: none; color: var(--vneid-red); }
        .payment-option.active .check-icon { display: inline-block; }
        .passport-photo-box { width: 160px; height: 200px; background-color: #FFFDF8; border: 2px dashed #DCD6CD; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; }
    </style>
</head>
<body class="pb-5">

    <div class="vneid-header p-3 mb-3 shadow-sm">
        <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/Mo_phong_app_vneid/dich_vu/danh_sach" class="text-white fs-4 me-3 text-decoration-none">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h5 class="fw-bold mb-0">Nộp hồ sơ Dịch vụ công</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="fw-semibold"><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Công dân') ?></span>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-3">
            <label for="select_dich_vu" class="form-label fw-bold text-secondary">Thủ tục Dịch vụ công lựa chọn</label>
            <select id="select_dich_vu" class="form-select form-select-lg rounded-3" onchange="switchServiceForm(this.value)">
                <?php foreach ($ds_dich_vu as $dv): ?>
                    <option value="<?= $dv['ma_dich_vu'] ?>" 
                        <?= (isset($_GET['ma_dich_vu']) && $_GET['ma_dich_vu'] == $dv['ma_dich_vu']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dv['ten_dich_vu']) ?> (Yêu cầu Mức <?= $dv['muc_toi_thieu'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Nhúng 4 sub-view tương ứng với 4 thủ tục -->
        <?php include __DIR__ . '/forms/form_cu_tru.php'; ?>
        <?php include __DIR__ . '/forms/form_phuong_tien.php'; ?>
        <?php include __DIR__ . '/forms/form_xac_nhan_cu_tru.php'; ?>
        <?php include __DIR__ . '/forms/form_cap_lai_giay_to.php'; ?>
    </div>

    <script>
        function switchServiceForm(maDichVu) {
            document.querySelectorAll('.service-flow').forEach(el => el.style.display = 'none');
            if (maDichVu == 1) document.getElementById('form-cu-tru').style.display = 'block';
            else if (maDichVu == 2) document.getElementById('form-phuong-tien').style.display = 'block';
            else if (maDichVu == 3) document.getElementById('form-xac-nhan-cu-tru').style.display = 'block';
            else if (maDichVu == 4) document.getElementById('form-cap-lai-giay-to').style.display = 'block';
        }

        const initialServiceId = document.getElementById('select_dich_vu').value;
        switchServiceForm(initialServiceId);

        function openBottomSheet(overlayId, contentId) {
            document.getElementById(overlayId).style.display = 'block';
            document.getElementById(contentId).classList.add('show');
        }

        function closeBottomSheet(overlayId, contentId) {
            document.getElementById(overlayId).style.display = 'none';
            document.getElementById(contentId).classList.remove('show');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>