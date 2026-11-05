<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý Dịch vụ công - Cán bộ VNeID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --vneid-red: #A31D1D; --vneid-dark: #7A0000; }
        body { background-color: #F4F6F8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .vneid-header { background: linear-gradient(180deg, var(--vneid-dark), var(--vneid-red)); color: white; }
    </style>
</head>
<body class="pb-4">

    <!-- Header Cán bộ - Hiện tên Cán bộ và Badge vai trò -->
    <div class="vneid-header p-3 mb-4 shadow-sm">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="bi bi-person-badge-fill me-2"></i>Cán bộ Cổng Dịch vụ công</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark fw-bold">Quyền xử lý</span>
                <span class="fw-semibold ms-2"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['ho_ten'] ?? 'Trần Văn Cán Bộ') ?></span>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark"><i class="bi bi-inbox-fill me-2 text-danger"></i>Danh sách hồ sơ dịch vụ công gửi đến</h6>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Mã HS</th>
                                <th>Công dân nộp</th>
                                <th>Dịch vụ đề nghị</th>
                                <th style="width: 28%;">Nội dung khai báo</th>
                                <th>Trạng thái & Thao tác xử lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ds_ho_so as $hs): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-danger">#<?= $hs['ma_ho_so'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($hs['ho_ten']) ?></div>
                                        <small class="text-muted">CCCD: <?= $hs['so_dinh_danh'] ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($hs['ten_dich_vu']) ?></span></td>
                                    <td class="small text-secondary"><?= htmlspecialchars($hs['noi_dung']) ?></td>
                                    <td>
                                        <form action="/Mo_phong_app_vneid/dich_vu/ds_ho_so_can_xu_ly" method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                                            <input type="hidden" name="ma_ho_so" value="<?= $hs['ma_ho_so'] ?>">
                                            
                                            <select name="trang_thai" class="form-select form-select-sm" style="width: 130px;">
                                                <option value="dang_xu_ly" <?= $hs['trang_thai'] === 'dang_xu_ly' ? 'selected' : '' ?>>Đang xử lý</option>
                                                <option value="can_bo_sung" <?= $hs['trang_thai'] === 'can_bo_sung' ? 'selected' : '' ?>>Cần bổ sung</option>
                                                <option value="da_duyet" <?= $hs['trang_thai'] === 'da_duyet' ? 'selected' : '' ?>>Đã duyệt</option>
                                                <option value="tu_choi" <?= $hs['trang_thai'] === 'tu_choi' ? 'selected' : '' ?>>Từ chối</option>
                                            </select>
                                            <input type="text" name="ghi_chu" class="form-control form-control-sm" placeholder="Ghi chú cán bộ" value="<?= htmlspecialchars($hs['ghi_chu'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold">Lưu</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>