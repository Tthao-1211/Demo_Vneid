<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ công - VNeID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <style>
        :root { --vneid-red: #A31D1D; --vneid-dark: #7A0000; }
        body { background-color: #F4F6F8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .vneid-header { background: linear-gradient(180deg, var(--vneid-dark), var(--vneid-red)); color: white; }
        .service-card { border: none; border-radius: 10px; transition: transform 0.2s; }
        .service-card:hover { transform: translateY(-2px); }
        .badge-muc { background-color: #FFF3CD; color: #856404; font-size: 0.75rem; border: 1px solid #FFEBAA; }
    </style>
</head>
<body class="pb-4">
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <div class="container-fluid px-4 py-4">
        <!-- Danh mục Dịch vụ công -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-danger mb-0"><i class="bi bi-grid-fill me-2"></i>Danh mục Dịch vụ công khả dụng</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($ds_dich_vu as $dv): ?>
                        <div class="col-12 col-md-6">
                            <div class="p-3 border rounded-3 bg-white d-flex justify-content-between align-items-center service-card shadow-sm">
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($dv['ten_dich_vu']) ?></div>
                                    <span class="badge badge-muc rounded-pill px-2 mt-1">
                                        <i class="bi bi-shield-check me-1"></i>Định danh Mức <?= $dv['muc_toi_thieu'] ?>
                                    </span>
                                </div>
                                <a href="/dich_vu/nop_ho_so?ma_dich_vu=<?= $dv['ma_dich_vu'] ?>" 
                                   class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold" style="background-color: #A31D1D;">
                                    Nộp hồ sơ
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Lịch sử hồ sơ đã nộp -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử hồ sơ đã nộp</h6>
                <span class="badge bg-secondary rounded-pill"><?= count($ds_ho_so) ?> hồ sơ</span>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Mã HS</th>
                                <th>Tên dịch vụ</th>
                                <th>Ngày nộp</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú cán bộ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ds_ho_so)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Bạn chưa nộp hồ sơ dịch vụ công nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ds_ho_so as $hs): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-danger">#<?= $hs['ma_ho_so'] ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($hs['ten_dich_vu']) ?></td>
                                        <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($hs['ngay_nop'])) ?></td>
                                        <td>
                                            <?php
                                                $st = trim($hs['trang_thai'] ?? '');
                                                if ($st === 'Đã duyệt' || $st === 'da_duyet') {
                                                    echo '<span class="badge bg-success-subtle text-success border border-success px-2 py-1">Đã duyệt</span>';
                                                } elseif ($st === 'Cần bổ sung' || $st === 'can_bo_sung') {
                                                    echo '<span class="badge bg-info-subtle text-info border border-info px-2 py-1">Cần bổ sung</span>';
                                                } elseif ($st === 'Từ chối' || $st === 'tu_choi') {
                                                    echo '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Từ chối</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">Đang xử lý</span>';
                                                }
                                            ?>
                                        </td>
                                        <td class="small text-secondary"><?= htmlspecialchars($hs['ghi_chu'] ?? 'Không có') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>