<?php
$yeu_cau   = $yeu_cau ?? [];
$noi_dung  = $noi_dung ?? [];
$thong_bao = $thong_bao ?? '';
$loi       = $loi ?? '';

$nhanTrangThai = [
    'da_nop'      => ['Đã nộp', 'badge-pending'],
    'dang_xu_ly'  => ['Đang xử lý', 'badge-processing'],
    'can_bo_sung' => ['Cần bổ sung', 'badge-supplement'],
    'da_duyet'    => ['Đã duyệt', 'badge-approved'],
    'tu_choi'     => ['Từ chối', 'badge-rejected'],
];
$tt = $yeu_cau['trang_thai'] ?? '';
[$nhan_tt, $lop_tt] = $nhanTrangThai[$tt] ?? [$tt, 'badge-pending'];
$da_xu_ly = in_array($tt, ['da_duyet', 'tu_choi'], true);
// Loại yêu cầu: điều chỉnh -> nút "Cập nhật", cấp đổi -> nút "Duyệt"
$la_dieu_chinh = mb_stripos($yeu_cau['ten_dich_vu'] ?? '', 'điều chỉnh', 0, 'UTF-8') !== false;

$nhanTruong = [
    'ho_ten' => 'Họ và tên',
    'ngay_sinh' => 'Ngày sinh',
    'gioi_tinh' => 'Giới tính',
    'quoc_tich' => 'Quốc tịch',
    'dan_toc' => 'Dân tộc',
    'ton_giao' => 'Tôn giáo',
    'que_quan' => 'Quê quán',
    'noi_thuong_tru' => 'Nơi thường trú',
    'tinh_thanh' => 'Tỉnh/Thành phố',
    'dac_diem_nhan_dang' => 'Đặc điểm nhận dạng',
    'noi_cap' => 'Nơi cấp',
    'ly_do' => 'Lý do',
];

$chuanHoa = function ($v) {
    if (is_array($v)) return json_encode($v, JSON_UNESCAPED_UNICODE);
    return trim((string)$v);
};
$dinhDangNgay = function ($v) {
    if ($v !== '' && preg_match('/^\d{4}-\d{2}-\d{2}/', $v) && ($ts = strtotime($v)) !== false) {
        return date('d/m/Y', $ts);
    }
    return $v;
};

// ----- Bản gốc để so sánh (chỉ dùng cho yêu cầu điều chỉnh) -----
// Nếu yêu cầu đã được cập nhật thì controller đã lưu ảnh chụp bản gốc trong __ban_goc
$the_hien_tai = $the_hien_tai ?? [];
$co_anh_chup  = isset($noi_dung['__ban_goc']) && is_array($noi_dung['__ban_goc']);
$ban_goc      = $co_anh_chup ? $noi_dung['__ban_goc'] : $the_hien_tai;
unset($noi_dung['__ban_goc']);
// Đã duyệt nhưng không có ảnh chụp: thẻ đã mang giá trị mới nên không còn gì để so sánh
$mat_ban_goc  = ($tt === 'da_duyet' && !$co_anh_chup);
if ($mat_ban_goc) { $ban_goc = []; }
$co_ban_goc = !empty($ban_goc); // false nếu không lấy được thẻ gốc của công dân
// Họ tên gốc lấy từ tài khoản nên luôn so sánh được, kể cả khi chưa lấy được thẻ
if (!$mat_ban_goc && !isset($ban_goc['ho_ten'])) { $ban_goc['ho_ten'] = $yeu_cau['ho_ten'] ?? ''; }

$thay_doi = []; $khong_doi = []; $khac = [];
if ($la_dieu_chinh) {
    $truongSoSanh = ['ho_ten', 'ngay_sinh', 'gioi_tinh', 'quoc_tich', 'dan_toc', 'ton_giao',
                     'dac_diem_nhan_dang', 'que_quan', 'tinh_thanh', 'noi_thuong_tru'];
    $chuanSS = function ($k, $v) {
        $v = trim((string)$v);
        if ($k === 'ngay_sinh' && $v !== '' && ($ts = strtotime($v)) !== false) return date('Y-m-d', $ts);
        return $v;
    };
    foreach ($truongSoSanh as $k) {
        $moi = $chuanHoa($noi_dung[$k . '_moi'] ?? $noi_dung[$k] ?? '');
        if ($moi === '') continue; // công dân không gửi trường này
        $cu  = $chuanHoa($ban_goc[$k] ?? '');
        $hang = [$nhanTruong[$k] ?? $k, $dinhDangNgay($cu), $dinhDangNgay($moi)];
        if ($chuanSS($k, $moi) !== $chuanSS($k, $cu)) { $thay_doi[] = $hang; } else { $khong_doi[] = $hang; }
    }
    foreach ($noi_dung as $k => $gt) {
        if (in_array($k, $truongSoSanh, true) || in_array($k, ['ly_do', 'csrf_token', 'ma_csrf'], true)) continue;
        if (substr($k, -4) === '_cu' || substr($k, -4) === '_moi') continue;
        $khac[] = [$nhanTruong[$k] ?? ucfirst(str_replace('_', ' ', $k)), $chuanHoa($gt)];
    }
}

// Gom dữ liệu: cặp *_cu / *_moi thì so sánh, trường lẻ thì hiện ở cột "Đề nghị"
$ly_do = '';
$dong  = [];
foreach ($noi_dung as $khoa => $gia_tri) {
    if (in_array($khoa, ['csrf_token', 'ma_csrf'], true)) continue;
    $v = $chuanHoa($gia_tri);
    if ($khoa === 'ly_do') { $ly_do = $v; continue; }

    if (substr($khoa, -4) === '_cu') {
        $goc = substr($khoa, 0, -4);
        if (array_key_exists($goc . '_moi', $noi_dung)) continue; // sẽ xử lý ở dòng *_moi
    }
    if (substr($khoa, -4) === '_moi') {
        $goc = substr($khoa, 0, -4);
        $cu  = $chuanHoa($noi_dung[$goc . '_cu'] ?? '');
        $dong[] = [$nhanTruong[$goc] ?? ucfirst(str_replace('_', ' ', $goc)), $dinhDangNgay($cu), $dinhDangNgay($v)];
        continue;
    }
    $dong[] = [$nhanTruong[$khoa] ?? ucfirst(str_replace('_', ' ', $khoa)), '', $dinhDangNgay($v)];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết yêu cầu - VNeID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/css/trang-chu.css">
    <link rel="stylesheet" href="/public/css/chi-tiet.css">
    <link rel="stylesheet" href="/public/css/can-cuoc-danh-sach.css">
    <style>
    .yc-back{display:inline-flex;align-items:center;gap:8px;padding:9px 14px;border:1.5px solid #e4e0e0;border-radius:10px;background:#fff;color:#3a2f2f;text-decoration:none;font-size:14px;font-weight:600;transition:all .15s}
    .yc-back:hover{border-color:#8e2a30;color:#8e2a30}
    .yc-alert{display:flex;gap:10px;align-items:center;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:600}
    .yc-alert.ok{background:#eefaf1;border:1px solid #bfe8ca;color:#1d6b35}
    .yc-alert.err{background:#fdeeee;border:1px solid #f3c6c6;color:#9b1c1c}
    .yc-meta{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
    .yc-meta .card{border:1px solid #eee6e6;border-radius:12px;background:#fcfafa;padding:14px 16px}
    .yc-meta .card span{display:block;font-size:12px;color:#8a7f7f;text-transform:uppercase;letter-spacing:.3px}
    .yc-meta .card strong{display:block;margin-top:6px;font-size:15px;color:#2b2222;word-break:break-word}
    .yc-table{border:1px solid #eee6e6;border-radius:12px;overflow:hidden}
    .yc-row{display:grid;grid-template-columns:200px 1fr 1fr;border-bottom:1px solid #f1eaea}
    .yc-row:last-child{border-bottom:0}
    .yc-row>div{padding:13px 16px;font-size:14px;word-break:break-word}
    .yc-row.head{background:#8e2a30;color:#fff;font-weight:700;font-size:13px;text-transform:uppercase}
    .yc-row .lbl{font-weight:600;color:#3a2f2f}
    .yc-row .old{color:#8a7f7f}
    .yc-row .new{font-weight:700;color:#8e2a30}
    .yc-box{margin-top:18px;border:1px solid #eee6e6;border-radius:12px;padding:16px;background:#fffafa}
    .yc-box span{display:block;font-size:12px;color:#8a7f7f;text-transform:uppercase}
    .yc-box strong{display:block;margin-top:6px;line-height:1.5;font-weight:600}
    .yc-form{margin-top:22px;padding-top:20px;border-top:1px solid #eee6e6}
    .yc-form label{display:block;font-weight:700;margin-bottom:8px;font-size:14px}
    .yc-form textarea{width:100%;min-height:96px;box-sizing:border-box;padding:12px 14px;border:1.5px solid #e4e0e0;border-radius:12px;font:inherit;font-size:14px;resize:vertical;outline:none}
    .yc-form textarea:focus{border-color:#8e2a30;box-shadow:0 0 0 4px rgba(142,42,48,.12)}
    .yc-actions{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:14px}
    .yc-actions button{height:46px;border-radius:12px;border:1.5px solid transparent;font:inherit;font-size:14px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;transition:all .15s}
    .yc-actions .approve{background:#8e2a30;color:#fff}.yc-actions .approve:hover{background:#74202a}
    .yc-actions a.approve{height:46px;border-radius:12px;font:inherit;font-size:14px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#8e2a30;color:#fff;text-decoration:none;transition:background .15s}
    .yc-actions a.approve:hover{background:#74202a}
    .yc-actions .supp{background:#fff7e8;color:#8a5a00;border-color:#f0d9a8}.yc-actions .supp:hover{background:#ffefd0}
    .yc-actions .reject{background:#fff;color:#3a2f2f;border-color:#d9d2d2}.yc-actions .reject:hover{border-color:#8e2a30;color:#8e2a30}
    .yc-diff-head{display:flex;align-items:center;justify-content:space-between;margin:4px 0 12px}
    .yc-diff-head h3{margin:0;font-size:16px;color:#2b2222;display:flex;align-items:center;gap:8px}
    .yc-diff-head h3 i{color:#8e2a30}
    .yc-pill{padding:5px 12px;border-radius:999px;background:#f1eeee;color:#756a6a;font-size:13px;font-weight:700}
    .yc-pill.has{background:#fdeeee;color:#8e2a30}
    .yc-row.changed{background:#fffaf0}
    .yc-row.changed .old s{color:#9a8f8f;text-decoration-thickness:1.5px}
    .yc-row.changed .new i{font-size:11px;margin-right:4px}
    .yc-same{margin-top:14px}
    .yc-same summary{cursor:pointer;font-size:14px;font-weight:600;color:#756a6a;padding:8px 2px;user-select:none}
    .yc-same summary:hover{color:#8e2a30}
    .yc-same .yc-table{margin-top:8px}
    @media(max-width:760px){.yc-meta{grid-template-columns:1fr 1fr}
        .yc-actions{grid-template-columns:1fr}
        .yc-table{overflow-x:auto}.yc-row{min-width:640px}
    }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

    <div class="app-shell">
        <main class="main">
            <div class="page-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                <div>
                    <h1 class="page-title">Chi tiết yêu cầu</h1>
                    <p class="page-sub"><?= e($yeu_cau['ten_dich_vu'] ?? '') ?></p>
                </div>
                <a class="yc-back" href="/the_can_cuoc/danh_sach_yeu_cau"><i class="fa-solid fa-arrow-left"></i> Danh sách</a>
            </div>

            <section class="panel" style="padding:22px">
                <?php if ($thong_bao !== ''): ?>
                    <div class="yc-alert ok"><i class="fa-solid fa-circle-check"></i><?= e($thong_bao) ?></div>
                <?php endif; ?>
                <?php if ($loi !== ''): ?>
                    <div class="yc-alert err"><i class="fa-solid fa-circle-exclamation"></i><?= e($loi) ?></div>
                <?php endif; ?>

                <div class="yc-meta">
                    <div class="card"><span>Mã hồ sơ</span><strong>#<?= (int)($yeu_cau['ma_ho_so'] ?? 0) ?></strong></div>
                    <div class="card"><span>Công dân</span><strong><?= e($yeu_cau['ho_ten'] ?? '--') ?></strong></div>
                    <div class="card"><span>Số định danh</span><strong><?= e($yeu_cau['so_dinh_danh'] ?? '--') ?></strong></div>
                    <div class="card"><span>Trạng thái</span>
                        <strong><span class="badge <?= e($lop_tt) ?>"><?= e($nhan_tt) ?></span></strong>
                    </div>
                </div>

                <div class="yc-meta" style="grid-template-columns:1fr;margin-top:-6px">
                    <div class="card"><span>Ngày gửi</span>
                        <strong><?= !empty($yeu_cau['ngay_nop']) ? date('d/m/Y H:i', strtotime($yeu_cau['ngay_nop'])) : '--' ?></strong>
                    </div>
                </div>

                <?php if ($la_dieu_chinh): ?>
                    <?php if ($mat_ban_goc): ?>
                        <div class="yc-alert err"><i class="fa-solid fa-circle-info"></i>Yêu cầu này đã được cập nhật trước đó nên không còn bản gốc để đối chiếu. Bảng dưới chỉ hiển thị nội dung đề nghị.</div>
                    <?php elseif (!$co_ban_goc): ?>
                        <div class="yc-alert err"><i class="fa-solid fa-circle-exclamation"></i>Chưa lấy được thẻ căn cước của công dân nên chỉ đối chiếu được họ tên theo tài khoản.</div>
                    <?php endif; ?>

                    <div class="yc-diff-head">
                        <h3><i class="fa-solid fa-code-compare"></i> Thông tin thay đổi so với bản gốc</h3>
                        <span class="yc-pill <?= $thay_doi ? 'has' : '' ?>"><?= count($thay_doi) ?> thay đổi</span>
                    </div>

                    <div class="yc-table">
                        <div class="yc-row head"><div>Thông tin</div><div>Bản gốc</div><div>Đề nghị thay đổi</div></div>
                        <?php if ($thay_doi): foreach ($thay_doi as [$ten, $cu, $moi]): ?>
                            <div class="yc-row changed">
                                <div class="lbl"><?= e($ten) ?></div>
                                <div class="old"><?= $cu !== '' ? '<s>' . e($cu) . '</s>' : '—' ?></div>
                                <div class="new"><i class="fa-solid fa-arrow-right"></i> <?= e($moi) ?></div>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="yc-row"><div class="lbl" style="grid-column:1/-1;text-align:center;color:#8a7f7f">Không có thông tin nào khác so với bản gốc.</div></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($khong_doi): ?>
                        <details class="yc-same">
                            <summary>Xem <?= count($khong_doi) ?> thông tin không thay đổi</summary>
                            <div class="yc-table">
                                <?php foreach ($khong_doi as [$ten, $cu, $moi]): ?>
                                    <div class="yc-row">
                                        <div class="lbl"><?= e($ten) ?></div>
                                        <div class="old" style="grid-column:2/4"><?= e($moi) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php endif; ?>

                    <?php if ($khac): ?>
                        <div class="yc-table" style="margin-top:14px">
                            <?php foreach ($khac as [$ten, $gt]): ?>
                                <div class="yc-row"><div class="lbl"><?= e($ten) ?></div><div style="grid-column:2/4"><?= e($gt) ?></div></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                <div class="yc-table">
                    <div class="yc-row head"><div>Thông tin</div><div>Hiện tại</div><div>Đề nghị</div></div>
                    <?php if ($dong): foreach ($dong as [$ten, $cu, $moi]): ?>
                        <div class="yc-row">
                            <div class="lbl"><?= e($ten) ?></div>
                            <div class="old"><?= $cu !== '' ? e($cu) : '—' ?></div>
                            <div class="new"><?= $moi !== '' ? e($moi) : '—' ?></div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="yc-row"><div class="lbl" style="grid-column:1/-1">Yêu cầu không có nội dung chi tiết.</div></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($ly_do !== ''): ?>
                    <div class="yc-box"><span>Lý do của công dân</span><strong><?= e($ly_do) ?></strong></div>
                <?php endif; ?>

                <?php if (!$da_xu_ly): ?>
                    <form method="post" class="yc-form">
                        <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? '') ?>">
                        <label for="ghi_chu">Ghi chú xử lý</label>
                        <textarea id="ghi_chu" name="ghi_chu" placeholder="Nhập lý do duyệt, từ chối hoặc nội dung cần bổ sung..."></textarea>
                        <div class="yc-actions">
                            <?php if ($la_dieu_chinh): ?>
                                <?php $ma_the_cap_nhat = (int)($the_hien_tai['ma_the'] ?? 0); ?>
                                <?php if ($ma_the_cap_nhat > 0): ?>
                                    <a class="approve"
                                       href="/the_can_cuoc/cap_nhat?id=<?= $ma_the_cap_nhat ?>&yeu_cau=<?= (int)($yeu_cau['ma_ho_so'] ?? 0) ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="approve" disabled
                                            style="opacity:.5;cursor:not-allowed"
                                            title="Không tìm thấy thẻ căn cước của công dân này">
                                        <i class="fa-solid fa-pen-to-square"></i> Cập nhật thông tin
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="approve" name="ket_qua" value="duyet"
                                        onclick="return confirm('Duyệt yêu cầu cấp đổi thẻ căn cước này?');">
                                    <i class="fa-solid fa-check"></i> Duyệt yêu cầu
                                </button>
                            <?php endif; ?>
                            <button class="supp" name="ket_qua" value="bo_sung"><i class="fa-solid fa-file-circle-plus"></i> Yêu cầu bổ sung</button>
                            <button class="reject" name="ket_qua" value="tu_choi"><i class="fa-solid fa-xmark"></i> Từ chối</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="yc-box">
                        <span>Kết quả xử lý</span>
                        <strong><?= e($nhan_tt) ?></strong>
                        <strong style="color:#756a6a;font-weight:500">Ghi chú: <?= e(trim((string)($yeu_cau['ghi_chu'] ?? '')) ?: 'Không có ghi chú') ?></strong>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>