<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý trạng thái thẻ căn cước</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body{margin:0;min-height:100vh;background:#E9EAE8;font-family:'Times New Roman',Times,serif;color:#222;padding:20px}
        .status-page{max-width:980px;margin:20px auto;background:#fff;border:1px solid #d2d4d2;border-top:5px solid #b91c1c;border-radius:16px;padding:30px;box-shadow:0 14px 34px rgba(36,40,38,.14)}
        .topbar-status{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
        .back-home{color:#b91c1c;text-decoration:none;font-weight:800;background:#fff;border:1px solid #cbd5e1;border-radius:8px;padding:10px 14px}
        .status-page h1{text-align:center;color:#b91c1c;font-size:28px;margin:0 0 24px}
        .current-card{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px}
        .current-box{border:1px solid #d8dad8;border-radius:12px;padding:18px;background:#f8f9f8}
        .status-label{color:#64748b;font-size:13px;margin-bottom:6px}
        .status-value{font-size:18px;font-weight:800}
        .badge{display:inline-block;border-radius:999px;padding:8px 13px;font-weight:800}
        .badge-active{background:#dcfce7;color:#166534}.badge-expired{background:#fee2e2;color:#991b1b}.badge-lost{background:#fff7ed;color:#c2410c}.badge-revoked{background:#e2e8f0;color:#334155}
        .status-section{border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-top:16px}
        .status-section h2{margin:0 0 16px;font-size:20px;color:#b91c1c}
        .status-change-options{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
        .status-change-option{border:1.5px solid #cbd5e1;border-radius:10px;padding:16px;text-align:center;cursor:pointer;font-weight:800;background:#fff;position:relative}
        .status-change-option input{position:absolute;opacity:0;pointer-events:none}
        .status-change-option.active{border-color:#e3261f;background:#fff7f7;color:#b91c1c;box-shadow:0 0 0 3px rgba(227,38,31,.08)}
        .status-change-option span{display:block;font-size:16px}
        .actions-row{display:flex;justify-content:space-between;gap:12px;margin-top:16px}
        .action-btn{display:inline-flex;align-items:center;justify-content:center;text-decoration:none;border-radius:8px;padding:12px 18px;font-weight:800;border:1px solid #cbd5e1;background:#fff;color:#334155;min-width:180px}
        .action-btn.primary{border-color:#e3261f;background:#e3261f;color:#fff}
        .history-item,.request-item{display:grid;grid-template-columns:150px 1fr 150px;gap:12px;padding:12px;border-bottom:1px solid #e2e8f0;font-size:14px}
        .history-item:last-child,.request-item:last-child{border-bottom:0}
        .history-empty{color:#64748b;padding:10px 0}
        @media(max-width:700px){body{padding:0}.status-page{margin:0;min-height:100vh;border-radius:0;padding:20px 16px}.current-card{grid-template-columns:1fr}.topbar-status{align-items:flex-start}.status-change-options{grid-template-columns:1fr}.actions-row{flex-direction:column}.action-btn{width:100%;box-sizing:border-box}.history-item,.request-item{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <main class="status-page">
        <div class="topbar-status">
            <a class="back-home" href="/">← Trang chủ</a>
        </div>
        <h1>Quản lý trạng thái thẻ</h1>

        <?php
            $labels = ['con_hieu_luc'=>'Còn hiệu lực','het_han'=>'Hết hạn','bao_mat'=>'Báo mất','thu_hoi'=>'Thu hồi'];
            $classes = ['con_hieu_luc'=>'badge-active','het_han'=>'badge-expired','bao_mat'=>'badge-lost','thu_hoi'=>'badge-revoked'];
            $trangThai = $the['trang_thai'] ?? 'con_hieu_luc';
        ?>
        <section class="current-card">
            <div class="current-box">
                <div class="status-label">Trạng thái hiện tại</div>
                <span class="badge <?php echo $classes[$trangThai] ?? 'badge-active'; ?>"><?php echo e($labels[$trangThai] ?? $trangThai); ?></span>
            </div>
            <div class="current-box">
                <div class="status-label">Ngày cấp / Ngày hết hạn</div>
                <div class="status-value">
                    <?php echo e(date('d/m/Y', strtotime($the['ngay_cap']))); ?> — <?php echo e(date('d/m/Y', strtotime($the['ngay_het_han']))); ?>
                </div>
            </div>
        </section>

        <section class="status-section">
            <h2>Yêu cầu thay đổi trạng thái thẻ</h2>
            <div class="status-change-options" id="statusChangeOptions">
                <label class="status-change-option" data-request="cap_doi" data-reason="Thẻ căn cước cần được cấp đổi do thẻ bị thu hồi.">
                    <input type="radio" name="trang_thai_yc" value="thu_hoi">
                    <span>Thu hồi</span>
                </label>
                <label class="status-change-option active" data-request="cap_doi" data-reason="Thẻ căn cước đã hết hạn, đề nghị cấp đổi thẻ.">
                    <input type="radio" name="trang_thai_yc" value="het_han" checked>
                    <span>Hết hạn</span>
                </label>
                <label class="status-change-option" data-request="bao_mat" data-reason="Báo mất thẻ căn cước công dân, đề nghị khóa thẻ.">
                    <input type="radio" name="trang_thai_yc" value="bao_mat">
                    <span>Báo mất</span>
                </label>
            </div>
            <div class="actions-row">
                <a class="action-btn" href="/the_can_cuoc/yeu_cau_cap_the?id=<?php echo e($the['id']); ?>&loai=cap_lai&ly_do=<?php echo rawurlencode('Thẻ căn cước cần được cấp lại theo yêu cầu của công dân.'); ?>">Cấp lại</a>
                <form method="POST" action="/the_can_cuoc/gui_yeu_cau_thay_doi_trang_thai" style="margin:0">
                    <input type="hidden" name="ma_csrf" value="<?php echo e(taoMaCsrf()); ?>">
                    <input type="hidden" name="id" value="<?php echo e($the['id']); ?>">
                    <input type="hidden" name="trang_thai_moi" id="trangThaiMoi" value="het_han">
                    <input type="hidden" name="ly_do" id="lyDoThayDoi" value="Thẻ căn cước đã hết hạn, đề nghị cấp đổi thẻ.">
                    <button class="action-btn primary" id="sendStatusRequest" type="submit">Gửi yêu cầu</button>
                </form>
            </div>
        </section>
    </main>
    <script>
        document.querySelectorAll('.status-change-option').forEach(function(option) {
            option.addEventListener('click', function() {
                document.querySelectorAll('.status-change-option').forEach(function(item) {
                    item.classList.remove('active');
                });
                option.classList.add('active');
                option.querySelector('input').checked = true;

                var link = document.getElementById('sendStatusRequest');
                var reason = option.dataset.reason;
                document.getElementById('trangThaiMoi').value = option.querySelector('input').value;
                document.getElementById('lyDoThayDoi').value = reason;
            });
        });
    </script>
</body>
</html>
