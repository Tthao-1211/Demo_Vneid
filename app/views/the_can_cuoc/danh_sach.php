<?php
$search         = $search ?? '';
$trang_thai_loc = $trang_thai_loc ?? '';
$sap_xep        = $sap_xep ?? 'moi_nhat';
$coLoc          = ($search !== '' || $trang_thai_loc !== '');

$nhanTrangThai = [
    'con_hieu_luc' => ['Còn hiệu lực', 'badge-active'],
    'het_han'      => ['Hết hạn',      'badge-expired'],
    'bao_mat'      => ['Báo mất',      'badge-lost'],
    'thu_hoi'      => ['Thu hồi',      'badge-revoked'],
];

$trang      = $trang ?? 1;
$tong_trang = $tong_trang ?? 1;
$tong       = $tong ?? 0;
$stt_dau    = ($trang - 1) * ($so_dong ?? 10);

$duongDanTrang = function (int $p) use ($search, $trang_thai_loc, $sap_xep) {
    return '/the_can_cuoc/danh_sach?' . http_build_query(array_filter([
        'search'     => $search,
        'trang_thai' => $trang_thai_loc,
        'sap_xep'    => $sap_xep !== 'moi_nhat' ? $sap_xep : '',
        'trang'      => $p > 1 ? $p : '',
    ], fn($v) => $v !== '' && $v !== null));
};
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VNeID - Tra cứu &amp; Quản lý Căn cước</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-danh-sach.css">
</head>
<body>
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <div class="app-shell">
        <main class="main">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Quản lý căn cước</h1>
                    <p class="page-sub">
                        <?= $tong > 0
                            ? 'Tìm thấy ' . $tong . ' hồ sơ'
                            : 'Trung tâm dữ liệu Quốc gia về dân cư' ?>
                    </p>
                </div>
            </div>

            <section class="panel">
                <form method="GET" action="/the_can_cuoc/danh_sach" class="filter-bar">
                    <div class="search-box">
                        <input type="text" id="searchInput" name="search"
                               placeholder="Tìm theo họ tên hoặc số định danh (12 số)"
                               value="<?= htmlspecialchars($search) ?>">
                        <div id="searchHelper" class="search-helper-text"></div>
                    </div>

                    <select name="trang_thai">
                        <option value="">Tất cả trạng thái</option>
                        <?php foreach ($nhanTrangThai as $giaTri => [$nhan]): ?>
                            <option value="<?= $giaTri ?>" <?= $trang_thai_loc === $giaTri ? 'selected' : '' ?>><?= $nhan ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="sap_xep">
                        <option value="moi_nhat" <?= $sap_xep === 'moi_nhat' ? 'selected' : '' ?>>Mới nhất trước</option>
                        <option value="cu_nhat"  <?= $sap_xep === 'cu_nhat'  ? 'selected' : '' ?>>Cũ nhất trước</option>
                        <option value="ten_az"   <?= $sap_xep === 'ten_az'   ? 'selected' : '' ?>>Tên A → Z</option>
                    </select>

                    <button type="submit" class="btn-filter">Lọc</button>
                </form>

                <?php if (!empty($search_error)): ?>
                    <div class="notice error">
                        <div class="notice-icon">⚠️</div>
                        <div class="notice-title">Lỗi định dạng Số định danh cá nhân</div>
                        <div class="notice-desc"><?= htmlspecialchars($search_error) ?></div>
                        <div class="notice-actions">
                            <a href="/the_can_cuoc/danh_sach" class="btn-box btn-box-cancel">Hủy bỏ</a>
                            <button type="button" class="btn-box btn-box-retry" onclick="focusSearchInput()">Thử lại</button>
                        </div>
                    </div>
                <?php elseif (!empty($search_empty)): ?>
                    <div class="notice">
                        <div class="notice-icon">🔍</div>
                        <div class="notice-title">Không tìm thấy thông tin hồ sơ</div>
                        <div class="notice-desc">Không có hồ sơ nào phù hợp với bộ lọc hiện tại.</div>
                        <div class="notice-actions">
                            <a href="/the_can_cuoc/danh_sach" class="btn-box btn-box-cancel">Xóa bộ lọc</a>
                            <button type="button" class="btn-box btn-box-retry" onclick="focusSearchInput()">Thử lại</button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($ds_the_can_cuoc)): ?>
                    <?php if ($coLoc): ?>
                        <div class="result-bar">
                            <div>✅ Tìm thấy <strong><?= $tong ?></strong> hồ sơ phù hợp</div>
                            <a href="/the_can_cuoc/danh_sach" class="btn-close-search">✕ Đóng kết quả</a>
                        </div>
                    <?php endif; ?>

                    <div class="table-wrap">
                        <table class="cccd-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Số định danh</th>
                                    <th>Họ tên công dân</th>
                                    <th>Ngày sinh</th>
                                    <th class="col-gt">Giới tính</th>
                                    <th class="col-que">Quê quán</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($ds_the_can_cuoc as $i => $the): ?>
                                <?php
                                $tt = $the['trang_thai'] ?? '';
                                [$nhan, $lop] = $nhanTrangThai[$tt] ?? [$tt, 'badge-lost'];
                                ?>
                                <tr>
                                    <td><?= $stt_dau + $i + 1 ?></td>
                                    <td class="td-num"><?= htmlspecialchars($the['so_the']) ?></td>
                                    <td class="td-name"><?= htmlspecialchars($the['ho_ten']) ?></td>
                                    <td><?= !empty($the['ngay_sinh']) ? date('d/m/Y', strtotime($the['ngay_sinh'])) : '--' ?></td>
                                    <td class="col-gt"><?= htmlspecialchars($the['gioi_tinh'] ?? '--') ?></td>
                                    <td class="col-que"><?= htmlspecialchars($the['que_quan'] ?? '--') ?></td>
                                    <td><span class="badge <?= $lop ?>"><?= htmlspecialchars($nhan) ?></span></td>
                                    <td>
                                        <div class="action-group">
                                            <a class="btn-action status" title="Cập nhật trạng thái thẻ"
                                               href="/the_can_cuoc/quan_ly_trang_thai?id=<?= (int)$the['ma_the'] ?>">
                                                <i class="fa-solid fa-toggle-on"></i> <span>Trạng thái</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav class="pagination" aria-label="Phân trang">
                        <div class="pagination-info">
                            Hiển thị <?= $stt_dau + 1 ?>–<?= $stt_dau + count($ds_the_can_cuoc) ?> / <?= $tong ?> hồ sơ
                            · Trang <?= $trang ?>/<?= $tong_trang ?>
                        </div>
                        <div class="pagination-list">
                            <?php if ($trang > 1): ?>
                                <a class="pg-item" href="<?= $duongDanTrang($trang - 1) ?>">‹</a>
                            <?php else: ?>
                                <span class="pg-item disabled">‹</span>
                            <?php endif; ?>

                            <?php
                            $hienThi = array_unique(array_filter([
                                1, $tong_trang, $trang - 1, $trang, $trang + 1,
                            ], fn($p) => $p >= 1 && $p <= $tong_trang));
                            sort($hienThi);
                            $truoc = 0;
                            foreach ($hienThi as $p):
                                if ($p - $truoc > 1): ?><span class="pg-dots">…</span><?php endif;
                                if ($p === $trang): ?>
                                    <span class="pg-item active"><?= $p ?></span>
                                <?php else: ?>
                                    <a class="pg-item" href="<?= $duongDanTrang($p) ?>"><?= $p ?></a>
                                <?php endif;
                                $truoc = $p;
                            endforeach; ?>

                            <?php if ($trang < $tong_trang): ?>
                                <a class="pg-item" href="<?= $duongDanTrang($trang + 1) ?>">›</a>
                            <?php else: ?>
                                <span class="pg-item disabled">›</span>
                            <?php endif; ?>
                        </div>
                    </nav>

                <?php elseif (empty($search_error) && empty($search_empty)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📂</div>
                        <div class="empty-title">Chưa có hồ sơ căn cước</div>
                        <div class="empty-desc">Danh sách căn cước hiện chưa có dữ liệu.</div>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
    function focusSearchInput() {
        var i = document.getElementById('searchInput');
        if (i) { i.focus(); i.select(); }
    }
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('searchInput');
        var helper = document.getElementById('searchHelper');
        if (!input || !helper) return;
        function capNhat() {
            var v = input.value.trim();
            if (v === '') { helper.textContent = ''; return; }
            if (/^\d+$/.test(v)) {
                helper.style.color = v.length === 12 ? '#166534' : '#b91c1c';
                helper.textContent = v.length === 12 ? '✓ Đã đủ 12 số' : 'Số định danh: ' + v.length + '/12 số';
            } else {
                helper.style.color = '#1d4ed8';
                helper.textContent = 'Đang tìm theo họ tên';
            }
        }
        input.addEventListener('input', capNhat);
        capNhat();
    });
    </script>
</body>
</html>