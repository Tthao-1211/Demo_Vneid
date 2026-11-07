<?php
/**
 * Hiển thị cột "Nội dung khai báo" (JSON) thành danh sách nhãn: giá trị.
 * - Bỏ qua trường rỗng
 * - Tách 2 nhóm: "Đề nghị thay đổi" (khóa kết thúc _moi) và "Thông tin hiện tại"
 */
if (!function_exists('hienThiNoiDung')) {
    function hienThiNoiDung($raw)
    {
        $nhan = [
            'ho_ten'             => 'Họ tên',
            'ngay_sinh'          => 'Ngày sinh',
            'gioi_tinh'          => 'Giới tính',
            'quoc_tich'          => 'Quốc tịch',
            'dan_toc'            => 'Dân tộc',
            'ton_giao'           => 'Tôn giáo',
            'que_quan'           => 'Quê quán',
            'noi_thuong_tru'     => 'Nơi thường trú',
            'tinh_thanh'         => 'Tỉnh/Thành',
            'dac_diem_nhan_dang' => 'Đặc điểm nhận dạng',
        ];

        $data = json_decode((string)($raw ?? ''), true);
        if (!is_array($data)) {
            return '<span class="small text-secondary">' . htmlspecialchars((string)$raw) . '</span>';
        }

        $nhomMoi = [];
        $nhomCu  = [];

        foreach ($data as $k => $v) {
            if ($v === '' || $v === null) continue;
            if (is_array($v)) $v = implode(', ', $v);

            $laMoi = substr($k, -4) === '_moi';
            $khoaGoc = $laMoi ? substr($k, 0, -4) : $k;
            $label = $nhan[$khoaGoc] ?? ucfirst(str_replace('_', ' ', $khoaGoc));

            $dong = '<div class="dong"><span class="nhan">' . htmlspecialchars($label) . '</span>'
                  . '<span class="gia-tri">' . htmlspecialchars((string)$v) . '</span></div>';

            if ($laMoi) $nhomMoi[] = $dong; else $nhomCu[] = $dong;
        }

        if (!$nhomMoi && !$nhomCu) {
            return '<span class="small text-muted fst-italic">Không có nội dung</span>';
        }

        $html = '<div class="khai-bao">';
        if ($nhomMoi) {
            $html .= '<div class="khoi khoi-moi"><div class="khoi-tieu-de"><i class="bi bi-pencil-square me-1"></i>Đề nghị thay đổi</div>'
                   . implode('', $nhomMoi) . '</div>';
        }
        if ($nhomCu) {
            $html .= '<div class="khoi khoi-cu"><div class="khoi-tieu-de"><i class="bi bi-person-vcard me-1"></i>Thông tin hiện tại</div>'
                   . implode('', $nhomCu) . '</div>';
        }
        return $html . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xử lý Dịch vụ công - Cán bộ VNeID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
    <link rel="stylesheet" href="/public/css/quan-tri.css">
    <style>
        :root { --vneid-red: #A31D1D; --vneid-dark: #7A0000; }
        body { background-color: #F4F6F8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .vneid-header { background: linear-gradient(180deg, var(--vneid-dark), var(--vneid-red)); color: white; }

        /* Bảng không cuộn ngang trên màn hình thường */
        .bang-ho-so { table-layout: auto; }
        .bang-ho-so td { vertical-align: top; }
        .bang-ho-so td:nth-child(1),
        .bang-ho-so td:nth-child(2),
        .bang-ho-so td:nth-child(3) { white-space: nowrap; }
        .bang-ho-so td:nth-child(4) { word-break: break-word; }

        /* Nội dung khai báo */
        .khai-bao { display: flex; flex-direction: column; gap: 8px; min-width: 260px; max-width: 460px; }
        .khai-bao .khoi { border-radius: 8px; padding: 8px 10px; border: 1px solid #e9ecef; background: #fff; }
        .khai-bao .khoi-moi { background: #fff5f5; border-color: #f3c9c9; }
        .khai-bao .khoi-tieu-de { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: #6c757d; margin-bottom: 4px; }
        .khai-bao .khoi-moi .khoi-tieu-de { color: var(--vneid-red); }
        .khai-bao .dong { display: flex; gap: 8px; font-size: .8rem; line-height: 1.4; padding: 1px 0; }
        .khai-bao .nhan { flex: 0 0 110px; color: #6c757d; }
        .khai-bao .gia-tri { flex: 1; color: #212529; font-weight: 500; overflow-wrap: anywhere; word-break: break-word; }
        .khai-bao .khoi-moi .gia-tri { color: var(--vneid-red); }

        /* Phân trang */
        .phan-trang-chua { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 16px; border-top: 1px solid #eef0f2; }
        .phan-trang-thongtin { font-size: .85rem; color: #6c757d; }
        .pagination { --bs-pagination-active-bg: var(--vneid-red); --bs-pagination-active-border-color: var(--vneid-red); --bs-pagination-color: var(--vneid-red); --bs-pagination-hover-color: var(--vneid-dark); --bs-pagination-focus-color: var(--vneid-dark); }

        /* Form xử lý */
        .form-xu-ly { display: flex; flex-wrap: wrap; gap: 8px; min-width: 240px; }
        .form-xu-ly select { width: 130px; }
        .form-xu-ly input[name="ghi_chu"] { flex: 1 1 140px; min-width: 140px; }
    </style>
</head>
<body class="pb-4">
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <main class="chua than-trang">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark"><i class="bi bi-inbox-fill me-2 text-danger"></i>Danh sách hồ sơ dịch vụ công gửi đến</h6>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 bang-ho-so">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-nowrap">Mã HS</th>
                                <th class="text-nowrap">Công dân nộp</th>
                                <th class="text-nowrap">Dịch vụ đề nghị</th>
                                <th style="min-width: 260px;">Nội dung khai báo</th>
                                <th class="text-nowrap" style="width: 440px;">Trạng thái & Thao tác xử lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Chỉ hiển thị hồ sơ ở trạng thái "đã nộp" và "đang xử lý"
                            $trang_thai_hien_thi = ['da_nop', 'dang_xu_ly'];
                            $ds_hien_thi = array_filter($ds_ho_so, function ($h) use ($trang_thai_hien_thi) {
                                return in_array($h['trang_thai'], $trang_thai_hien_thi, true);
                            });
                            ?>
                            <?php
                            // Phân trang (tự cắt mảng tại view; nếu controller đã phân trang thì truyền sẵn các biến này)
                            $so_dong = isset($so_dong) ? max(1, (int)$so_dong) : 10;
                            // Luôn tính lại từ danh sách ĐÃ LỌC (không tin $tong_trang từ controller,
                            // vì controller đếm cả hồ sơ ở trạng thái đã bị ẩn)
                            $ds_hien_thi    = array_values($ds_hien_thi);
                            $tong_ban_ghi   = count($ds_hien_thi);
                            $tong_trang     = max(1, (int)ceil($tong_ban_ghi / $so_dong));
                            $trang_hien_tai = min(max(1, (int)($_GET['trang'] ?? 1)), $tong_trang);
                            $ds_hien_thi    = array_slice($ds_hien_thi, ($trang_hien_tai - 1) * $so_dong, $so_dong);
                            $tu_dong  = $tong_ban_ghi ? ($trang_hien_tai - 1) * $so_dong + 1 : 0;
                            $den_dong = $tong_ban_ghi ? $tu_dong + count($ds_hien_thi) - 1 : 0;

                            // URL giữ nguyên các tham số GET khác khi chuyển trang
                            $url_trang = function (int $p) {
                                $tham_so = $_GET;
                                unset($tham_so['trang']);
                                if ($p > 1) $tham_so['trang'] = $p;
                                $qs = http_build_query($tham_so);
                                return '/dich_vu/ds_ho_so_can_xu_ly' . ($qs ? '?' . $qs : '');
                            };

                            // Danh sách số trang hiển thị (có dấu ...)
                            $cac_trang = [];
                            for ($i = 1; $i <= $tong_trang; $i++) {
                                if ($i === 1 || $i === $tong_trang || abs($i - $trang_hien_tai) <= 2) {
                                    $cac_trang[] = $i;
                                } elseif (end($cac_trang) !== '...') {
                                    $cac_trang[] = '...';
                                }
                            }
                            ?>
                            <?php if (!$ds_hien_thi): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Không có hồ sơ nào cần xử lý.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($ds_hien_thi as $hs): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-danger">#<?= htmlspecialchars((string)$hs['ma_ho_so']) ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($hs['ho_ten']) ?></div>
                                        <small class="text-muted">CCCD: <?= htmlspecialchars((string)$hs['so_dinh_danh']) ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($hs['ten_dich_vu']) ?></span></td>
                                    <td><?= hienThiNoiDung($hs['noi_dung']) ?></td>
                                    <td>
                                        <form action="/dich_vu/ds_ho_so_can_xu_ly" method="POST" class="form-xu-ly">
                                            <input type="hidden" name="ma_csrf" value="<?= taoMaCsrf() ?>">
                                            <input type="hidden" name="ma_ho_so" value="<?= htmlspecialchars((string)$hs['ma_ho_so']) ?>">

                                            <select name="trang_thai" class="form-select form-select-sm">
                                                <option value="da_nop" <?= $hs['trang_thai'] === 'da_nop' ? 'selected' : '' ?>>Đã nộp</option>
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

                <?php if ($tong_ban_ghi > 0): ?>
                    <div class="phan-trang-chua">
                        <div class="phan-trang-thongtin">
                            Hiển thị <?= $tu_dong ?>–<?= $den_dong ?> / <?= $tong_ban_ghi ?> hồ sơ
                        </div>

                        <?php if (true): ?>
                            <nav aria-label="Phân trang">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item <?= $trang_hien_tai <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= htmlspecialchars($url_trang($trang_hien_tai - 1)) ?>" aria-label="Trang trước">&laquo;</a>
                                    </li>

                                    <?php foreach ($cac_trang as $p): ?>
                                        <?php if ($p === '...'): ?>
                                            <li class="page-item disabled"><span class="page-link">…</span></li>
                                        <?php else: ?>
                                            <li class="page-item <?= $p === $trang_hien_tai ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= htmlspecialchars($url_trang($p)) ?>"><?= $p ?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <li class="page-item <?= $trang_hien_tai >= $tong_trang ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= htmlspecialchars($url_trang($trang_hien_tai + 1)) ?>" aria-label="Trang sau">&raquo;</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>