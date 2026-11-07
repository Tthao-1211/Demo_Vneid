<?php
$dsYeuCau = is_array($dsYeuCau ?? null) ? $dsYeuCau : [];
$e = static function($value){ return htmlspecialchars((string)($value ?? ''),ENT_QUOTES,'UTF-8'); };
$tongYeuCau = count($dsYeuCau);
$dangXuLy = 0;
$daDuyet = 0;
$tuChoi = 0;
foreach($dsYeuCau as $yc){
    $tt = $yc['trang_thai'] ?? '';
    if(in_array($tt,['da_nop','dang_xu_ly','can_bo_sung'],true)) $dangXuLy++;
    elseif($tt === 'da_duyet') $daDuyet++;
    elseif($tt === 'tu_choi') $tuChoi++;
}
$nhanTruong = [
    'loai_yeu_cau'=>'Loại yêu cầu',
    'so_dinh_danh'=>'Số định danh',
    'ho_ten'=>'Họ và tên',
    'so_dien_thoai'=>'Số điện thoại',
    'hinh_thuc_nhan'=>'Hình thức nhận',
    'dia_chi_nhan'=>'Địa chỉ nhận',
    'ly_do'=>'Lý do yêu cầu',
    'ghi_chu'=>'Ghi chú',
    'ho_ten_moi'=>'Họ tên mới',
    'ngay_sinh_moi'=>'Ngày sinh mới',
    'gioi_tinh_moi'=>'Giới tính mới',
    'quoc_tich_moi'=>'Quốc tịch mới',
    'dan_toc_moi'=>'Dân tộc mới',
    'ton_giao_moi'=>'Tôn giáo mới',
    'que_quan_moi'=>'Quê quán mới',
    'noi_thuong_tru_moi'=>'Nơi thường trú mới'
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Yêu cầu của tôi</title>
<link rel="stylesheet" href="/public/css/trang-chu.css">
<link rel="stylesheet" href="/public/css/chi-tiet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box}
body{background:#f5f6f8;color:#263238}
.vneid-form-container{max-width:1100px;margin:40px auto;padding:32px;background:#fff;border-radius:18px;box-shadow:0 5px 25px rgba(0,0,0,.055)}
.page-heading{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap}
.vneid-section-title{margin:0;color:#952D25;font-size:27px;font-weight:750}
.section-description{color:#78818d;font-size:14px;line-height:1.7;margin:10px 0 25px}
.request-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin:25px 0 30px}
.summary-item{position:relative;padding:22px;border:1px solid #eee;border-radius:14px;background:#fff;display:flex;align-items:center;gap:16px;overflow:hidden}
.summary-icon{width:48px;height:48px;border-radius:13px;background:#f9e9e7;color:#952D25;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.summary-item strong{display:block;font-size:25px;color:#292929;margin-bottom:4px}
.summary-item span{font-size:13px;color:#78818d}
.request-list{display:grid;gap:20px}
.request-card{background:#fff;border:1px solid #e9e9ed;border-radius:15px;overflow:hidden;transition:.2s}
.request-card:hover{border-color:#d9aaa6;box-shadow:0 7px 22px rgba(149,45,37,.07)}
.request-head{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;padding:18px 22px;background:#fffafa;border-bottom:1px solid #f0e7e6}
.request-code{font-size:14px;font-weight:750;color:#333}
.request-code i{color:#952D25;margin-right:7px}
.request-type{font-size:15px;font-weight:700;color:#952D25;margin-top:7px}
.request-badge{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border-radius:30px;background:#fff3df;color:#a65c00;font-size:12px;font-weight:700;white-space:nowrap}
.request-badge.processing{background:#e7f3ff;color:#1766a5}
.request-badge.approved{background:#e6f7ed;color:#16834a}
.request-badge.rejected{background:#ffebeb;color:#c0392b}
.request-body{padding:22px}
.content-title{font-size:14px;font-weight:750;color:#333;margin-bottom:14px}
.content-title i{color:#952D25;margin-right:7px}
.request-content{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.content-field{padding:13px 15px;background:#f8f9fb;border:1px solid #edf0f3;border-radius:10px;min-width:0}
.content-field.full{grid-column:1/-1}
.content-field span{display:block;color:#818995;font-size:12px;margin-bottom:7px}
.content-field strong{display:block;color:#30343b;font-size:13px;font-weight:650;line-height:1.6;overflow-wrap:anywhere;white-space:pre-wrap}
.request-meta{display:flex;gap:35px;flex-wrap:wrap;margin-top:20px;padding-top:17px;border-top:1px solid #eee}
.meta-row span{display:block;color:#818995;font-size:12px;margin-bottom:6px}
.meta-row strong{font-size:13px;color:#343a40;font-weight:650}
.request-note{margin-top:18px;padding:14px 16px;background:#fff8f0;border-left:3px solid #d69b54;border-radius:7px}
.request-note span{display:block;color:#986b36;font-size:12px;margin-bottom:6px}
.request-note strong{font-size:13px;color:#66513a;line-height:1.6;font-weight:550}
.request-empty{padding:55px 20px;text-align:center;background:#fafafa;border:1px dashed #d9dce2;border-radius:14px}
.empty-icon{width:65px;height:65px;margin:0 auto 16px;border-radius:50%;background:#f8e9e7;color:#952D25;display:flex;align-items:center;justify-content:center;font-size:26px}
.request-empty strong{display:block;color:#343a40;font-size:17px;margin-bottom:8px}
.request-empty p{color:#858d98;font-size:13px;margin:0}
.request-back{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:9px;background:#952D25;color:#fff;text-decoration:none;font-size:13px;font-weight:650;transition:.2s}
.request-back:hover{background:#76231d;color:#fff;transform:translateY(-1px)}
@media(max-width:700px){
.vneid-form-container{margin:15px;padding:20px 15px}
.vneid-section-title{font-size:22px}
.request-summary{gap:8px}
.summary-item{padding:13px 9px;display:block;text-align:center}
.summary-icon{width:38px;height:38px;margin:0 auto 9px;font-size:16px}
.summary-item strong{font-size:21px}
.summary-item span{font-size:11px}
.request-head{padding:15px}
.request-body{padding:15px}
.request-content{grid-template-columns:1fr}
.content-field.full{grid-column:auto}
.request-meta{gap:20px}
}
</style>
</head>
<body>
<?php require __DIR__.'/../nguoi_dung/thanh_menu.php'; ?>
<main class="vneid-form-container">
    <div class="page-heading">
        <h1 class="vneid-section-title"><i class="fa-solid fa-folder-open"></i> Yêu cầu của tôi</h1>
    </div>
    <p class="section-description">Theo dõi tiến độ xử lý các yêu cầu điều chỉnh thông tin và cấp đổi thẻ căn cước của bạn.</p>
    <div class="request-summary">
        <div class="summary-item">
            <div class="summary-icon"><i class="fa-solid fa-folder"></i></div>
            <div><strong><?= $tongYeuCau ?></strong><span>Tổng yêu cầu</span></div>
        </div>
        <div class="summary-item">
            <div class="summary-icon"><i class="fa-solid fa-clock"></i></div>
            <div><strong><?= $dangXuLy ?></strong><span>Đang xử lý</span></div>
        </div>
        <div class="summary-item">
            <div class="summary-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div><strong><?= $daDuyet ?></strong><span>Đã duyệt</span></div>
        </div>
    </div>
    <?php if(empty($dsYeuCau)): ?>
        <div class="request-empty">
            <div class="empty-icon"><i class="fa-regular fa-folder-open"></i></div>
            <strong>Bạn chưa có yêu cầu nào</strong>
            <p>Các yêu cầu bạn gửi sẽ được hiển thị tại đây.</p>
        </div>
    <?php else: ?>
        <div class="request-list">
        <?php foreach($dsYeuCau as $yc):
            $maHoSo = $yc['ma_ho_so'] ?? '';
            $tenDichVu = $yc['ten_dich_vu'] ?? 'Dịch vụ không xác định';
            $noiDung = $yc['noi_dung'] ?? '';
            $ghiChu = $yc['ghi_chu'] ?? '';
            $trangThai = $yc['trang_thai'] ?? '';
            $duLieu = json_decode($noiDung,true);
            $ngayNop = !empty($yc['ngay_nop']) ? strtotime($yc['ngay_nop']) : false;
            $ngayHienThi = $ngayNop ? date('d/m/Y H:i',$ngayNop) : 'Không có dữ liệu';
            $nhanTrangThai = 'Chưa xác định';
            $lopTrangThai = '';
            $iconTrangThai = 'fa-clock';
            switch($trangThai){
                case 'da_nop': $nhanTrangThai='Đã nộp'; $iconTrangThai='fa-paper-plane'; break;
                case 'dang_xu_ly': $nhanTrangThai='Đang xử lý'; $lopTrangThai='processing'; $iconTrangThai='fa-spinner'; break;
                case 'can_bo_sung': $nhanTrangThai='Cần bổ sung'; $lopTrangThai='processing'; $iconTrangThai='fa-circle-exclamation'; break;
                case 'da_duyet': $nhanTrangThai='Đã duyệt'; $lopTrangThai='approved'; $iconTrangThai='fa-circle-check'; break;
                case 'tu_choi': $nhanTrangThai='Từ chối'; $lopTrangThai='rejected'; $iconTrangThai='fa-circle-xmark'; break;
            }
        ?>
            <article class="request-card">
                <div class="request-head">
                    <div>
                        <div class="request-code"><i class="fa-solid fa-file-lines"></i> Mã hồ sơ: HS-<?= $e($maHoSo) ?></div>
                        <div class="request-type"><?= $e($tenDichVu) ?></div>
                    </div>
                    <span class="request-badge <?= $e($lopTrangThai) ?>"><i class="fa-solid <?= $e($iconTrangThai) ?>"></i> <?= $e($nhanTrangThai) ?></span>
                </div>
                <div class="request-body">
                    <div class="content-title"><i class="fa-solid fa-circle-info"></i> Nội dung yêu cầu</div>
                    <div class="request-content">
                    <?php if(is_array($duLieu) && !empty($duLieu)): ?>
                        <?php foreach($duLieu as $key=>$value):
                            if($key==='csrf_token' || $value==='' || $value===null) continue;
                            $nhan = $nhanTruong[$key] ?? ucfirst(str_replace('_',' ',$key));
                            if($key==='loai_yeu_cau') $value = ['cap_moi'=>'Cấp mới','cap_doi'=>'Cấp đổi','cap_lai'=>'Cấp lại','bao_mat'=>'Báo mất'][$value] ?? $value;
                            if($key==='hinh_thuc_nhan') $value = ['truc_tiep'=>'Nhận trực tiếp tại cơ quan','buu_dien'=>'Nhận qua bưu điện'][$value] ?? $value;
                            $full = in_array($key,['ly_do','ghi_chu','dia_chi_nhan','noi_thuong_tru_moi','que_quan_moi'],true);
                        ?>
                            <div class="content-field <?= $full?'full':'' ?>">
                                <span><?= $e($nhan) ?></span>
                                <strong><?= $e($value) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="content-field full"><strong><?= nl2br($e($noiDung ?: 'Chưa có nội dung yêu cầu.')) ?></strong></div>
                    <?php endif; ?>
                    </div>
                    <div class="request-meta">
                        <div class="meta-row"><span><i class="fa-regular fa-calendar"></i> Ngày gửi</span><strong><?= $e($ngayHienThi) ?></strong></div>
                        <div class="meta-row"><span><i class="fa-solid fa-list-check"></i> Trạng thái</span><strong><?= $e($nhanTrangThai) ?></strong></div>
                    </div>
                    <div class="request-note">
                        <span><i class="fa-solid fa-comment-dots"></i> Ghi chú của cán bộ xử lý</span>
                        <strong><?= $e($ghiChu !== '' ? $ghiChu : 'Chưa có ghi chú xử lý.') ?></strong>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
```