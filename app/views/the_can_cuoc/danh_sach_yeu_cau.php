<?php
$ds_yeu_cau = $ds_yeu_cau ?? [];
$nhanTrangThai = [
    'da_nop' => ['Đã nộp', 'badge-pending'],
    'dang_xu_ly' => ['Đang xử lý', 'badge-processing'],
    'can_bo_sung' => ['Cần bổ sung', 'badge-supplement'],
    'da_duyet' => ['Đã duyệt', 'badge-approved'],
    'tu_choi' => ['Từ chối', 'badge-rejected']
];

// ----- Tìm kiếm -----
$q = trim($_GET['q'] ?? '');
$ds_loc = $ds_yeu_cau;
if ($q !== '') {
    $ds_loc = array_values(array_filter($ds_yeu_cau, function ($yc) use ($q) {
        $chuoi = implode(' ', [
            $yc['ma_ho_so'] ?? '',
            $yc['so_dinh_danh'] ?? '',
            $yc['ho_ten'] ?? '',
            $yc['ten_dich_vu'] ?? '',
        ]);
        return mb_stripos($chuoi, $q, 0, 'UTF-8') !== false;
    }));
}

// ----- Phân trang -----
$moi_trang   = 10;
$tong        = count($ds_loc);
$tong_trang  = max(1, (int)ceil($tong / $moi_trang));
$trang       = min(max(1, (int)($_GET['trang'] ?? 1)), $tong_trang);
$ds_hien_thi = array_slice($ds_loc, ($trang - 1) * $moi_trang, $moi_trang);
$tu_dong     = $tong === 0 ? 0 : ($trang - 1) * $moi_trang + 1;
$den_dong    = min($trang * $moi_trang, $tong);

if (!function_exists('urlTrang')) {
    function urlTrang($p, $q) {
        $tham_so = ['trang' => $p];
        if ($q !== '') $tham_so['q'] = $q;
        return '?' . http_build_query($tham_so);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách yêu cầu cấp đổi, điều chỉnh - VNeID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-danh-sach.css">
    <style>
    /* ===== Thanh tìm kiếm ===== */
    .toolbar{
        display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;
        padding:0 0 18px;
    }
    .toolbar .search-form{display:flex;align-items:center;gap:10px;flex:1;min-width:280px;width:100%}
    .toolbar .search-box{
        position:relative;flex:1;display:flex;align-items:center;
        background:#fff;border:1.5px solid #e4e0e0;border-radius:12px;
        transition:border-color .15s, box-shadow .15s;
    }
    .toolbar .search-box:focus-within{
        border-color:#8e2a30;box-shadow:0 0 0 4px rgba(142,42,48,.12);
    }
    .toolbar .search-box > i{position:absolute;left:14px;color:#9a8f8f;font-size:14px;pointer-events:none}
    .toolbar .search-box input{
        width:100%;height:44px;border:0;background:transparent;outline:none;
        padding:0 40px 0 40px;font:inherit;font-size:14px;color:#2b2222;
    }
    .toolbar .search-box input::placeholder{color:#a79d9d}
    .toolbar .search-box .clear{
        position:absolute;right:8px;width:28px;height:28px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        color:#8a7f7f;text-decoration:none;font-size:13px;transition:background .15s;
    }
    .toolbar .search-box .clear:hover{background:#f3eded;color:#8e2a30}
    .toolbar .btn-search{
        height:44px;padding:0 22px;border:0;border-radius:12px;cursor:pointer;
        background:#8e2a30;color:#fff;font:inherit;font-size:14px;font-weight:600;
        display:inline-flex;align-items:center;gap:8px;transition:background .15s, transform .05s;
    }
    .toolbar .btn-search:hover{background:#74202a}
    .toolbar .btn-search:active{transform:translateY(1px)}
    .toolbar .count-chip{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 14px;border-radius:999px;background:#faf3f3;
        border:1px solid #f0dede;color:#5c4a4a;font-size:13px;white-space:nowrap;
    }
    .toolbar .count-chip i{color:#8e2a30}
    .toolbar .count-chip strong{color:#8e2a30;font-size:14px}

    /* ===== Phân trang ===== */
    .pager{
        display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
        padding:18px 4px 4px;
    }
    .pager .pager-info{font-size:13px;color:#756a6a}
    .pager .pager-info strong{color:#2b2222}
    .pager .pager-nav{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
    .pager .pager-nav a,.pager .pager-nav span{
        min-width:38px;height:38px;padding:0 12px;box-sizing:border-box;
        display:inline-flex;align-items:center;justify-content:center;
        border:1.5px solid #e8e1e1;border-radius:10px;background:#fff;
        font-size:14px;font-weight:500;color:#3a2f2f;text-decoration:none;
        transition:all .15s;
    }
    .pager .pager-nav a:hover{border-color:#8e2a30;color:#8e2a30;background:#fdf6f6}
    .pager .pager-nav .active{
        background:#8e2a30;border-color:#8e2a30;color:#fff;font-weight:700;
        box-shadow:0 4px 10px rgba(142,42,48,.25);
    }
    .pager .pager-nav a.active:hover{background:#8e2a30;color:#fff}
    .pager .pager-nav .disabled{color:#c4baba;background:#faf8f8;border-color:#efeaea;pointer-events:none}
    .pager .pager-nav .dots{border:0;background:transparent;min-width:20px;padding:0;color:#9a8f8f}
    @media (max-width:640px){
        .toolbar .count-chip{width:100%;justify-content:center}
        .pager{justify-content:center}
    }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <div class="app-shell">
        <main class="main">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Danh sách yêu cầu cấp đổi, điều chỉnh</h1>
                    <p class="page-sub">Quản lý và theo dõi các hồ sơ yêu cầu căn cước</p>
                </div>
            </div>

            <section class="panel">
                <div class="toolbar">
                    <form class="search-form" method="get" action="">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>"
                                   placeholder="Tìm theo mã hồ sơ, số định danh, họ tên..."
                                   autocomplete="off">
                            <?php if ($q !== ''): ?>
                                <a class="clear" href="?" title="Xóa tìm kiếm"><i class="fa-solid fa-xmark"></i></a>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn-search">
                            <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                        </button>
                    </form>
                </div>

                <?php if (!empty($ds_hien_thi)): ?>
                    <div class="table-wrap">
                        <table class="cccd-table">
                            <thead>
                                <tr>
                                    <th>Mã hồ sơ</th>
                                    <th>Số định danh</th>
                                    <th>Họ và tên</th>
                                    <th>Loại yêu cầu</th>
                                    <th>Ngày gửi</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ds_hien_thi as $yc): ?>
                                    <?php
                                    $tt = $yc['trang_thai'] ?? '';
                                    [$nhan, $lop] = $nhanTrangThai[$tt] ?? [$tt, 'badge-pending'];
                                    ?>
                                    <tr>
                                        <td class="td-num">#<?= (int)$yc['ma_ho_so'] ?></td>
                                        <td class="td-num"><?= htmlspecialchars($yc['so_dinh_danh'] ?? '--') ?></td>
                                        <td class="td-name"><?= htmlspecialchars($yc['ho_ten'] ?? '--') ?></td>
                                        <td><?= htmlspecialchars($yc['ten_dich_vu'] ?? '--') ?></td>
                                        <td><?= !empty($yc['ngay_nop']) ? date('d/m/Y H:i', strtotime($yc['ngay_nop'])) : '--' ?></td>
                                        <td>
                                            <span class="badge <?= htmlspecialchars($lop) ?>">
                                                <?= htmlspecialchars($nhan) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a class="btn-action status"
                                                   title="Xem chi tiết yêu cầu"
                                                   href="/the_can_cuoc/chi_tiet_yeu_cau_dieu_chinh?id=<?= (int)$yc['ma_ho_so'] ?>">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span>Xem chi tiết</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa-regular fa-folder-open"></i>
                        </div>
                        <div class="empty-title"><?= $q !== '' ? 'Không tìm thấy kết quả' : 'Chưa có yêu cầu nào' ?></div>
                        <div class="empty-desc">
                            <?= $q !== ''
                                ? 'Không có hồ sơ nào khớp với từ khóa "' . htmlspecialchars($q) . '".'
                                : 'Hiện chưa có hồ sơ cấp đổi hoặc điều chỉnh căn cước.' ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Phân trang: luôn hiển thị kể cả chỉ có 1 trang -->
                <div class="pager">
                    <div class="pager-info">
                        Hiển thị <strong><?= $tu_dong ?>–<?= $den_dong ?></strong>
                        trong <strong><?= $tong ?></strong> hồ sơ
                    </div>
                    <nav class="pager-nav" aria-label="Phân trang">
                        <a class="<?= $trang <= 1 ? 'disabled' : '' ?>" href="<?= urlTrang($trang - 1, $q) ?>" title="Trang trước">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                        <?php
                        $tu  = max(1, $trang - 2);
                        $den = min($tong_trang, $trang + 2);
                        if ($tu > 1): ?>
                            <a href="<?= urlTrang(1, $q) ?>">1</a>
                            <?php if ($tu > 2): ?><span class="dots">…</span><?php endif; ?>
                        <?php endif; ?>
                        <?php for ($p = $tu; $p <= $den; $p++): ?>
                            <a class="<?= $p === $trang ? 'active' : '' ?>" href="<?= urlTrang($p, $q) ?>"><?= $p ?></a>
                        <?php endfor; ?>
                        <?php if ($den < $tong_trang): ?>
                            <?php if ($den < $tong_trang - 1): ?><span class="dots">…</span><?php endif; ?>
                            <a href="<?= urlTrang($tong_trang, $q) ?>"><?= $tong_trang ?></a>
                        <?php endif; ?>
                        <a class="<?= $trang >= $tong_trang ? 'disabled' : '' ?>" href="<?= urlTrang($trang + 1, $q) ?>" title="Trang sau">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </section>
        </main>
    </div>
</body>
</html>