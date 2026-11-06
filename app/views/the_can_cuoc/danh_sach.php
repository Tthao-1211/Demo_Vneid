<!-- app/views/the_can_cuoc/danh_sach.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>VNeID - Hệ thống Tra cứu Căn cước</title>
    <link rel="stylesheet" href="/css/vneid-theme.css">
    <link rel="stylesheet" href="/css/professional-form.css">
    <style>
        body {
            background-color: #E9EAE8 !important; font-family: 'Times New Roman', Times, serif;
            margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; min-height: 100vh;
        }

        .boca-brand { text-align: center; color: #ffffff; margin-top: 20px; margin-bottom: 20px; }
        .boca-sub { font-size: 13px; color: #ffca2c; margin: 0; }
        .boca-title { font-size: 20px; font-weight: 700; text-transform: uppercase; margin: 5px 0; }

        .vneid-table-container {
            width: 100%; max-width: 1050px; background-color: #ffffff;
            padding: 35px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); box-sizing: border-box;
        }

        .vneid-section-title { text-align: center; color: #222222; font-size: 24px; font-weight: 700; margin-bottom: 30px; }
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }
        .page-header-bar .vneid-section-title { margin: 0; text-align: left; }
        .btn-home {
            background-color: #fff7ed !important;
            color: #9a3412 !important;
            border: 1px solid #fdba74 !important;
        }
        .btn-home:hover { background-color: #ffedd5 !important; }
        
        .search-box-wrapper {
            position: relative;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .search-input { 
            padding: 12px 16px; 
            border: 1px solid #cbd5e1; 
            border-radius: 8px; 
            font-size: 15px; 
            width: 100%;
            height: 46px;
            box-sizing: border-box;
            background-color: #ffffff;
            color: #222222;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus {
            border-color: #e3261f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(227, 38, 31, 0.15);
        }

        .search-helper-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
            padding-left: 2px;
        }

        .btn-search { 
            padding: 12px 24px; 
            background-color: #e3261f; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-weight: 700; 
            cursor: pointer; 
            height: 46px;
            box-sizing: border-box;
        }
        .btn-search:hover { background-color: #c91e17; }

        .btn-header-link {
            padding: 12px 18px; 
            border-radius: 8px; 
            font-weight: 700; 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            height: 46px; 
            box-sizing: border-box; 
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .btn-add-new { background-color: #fde8e8; color: #a31a14; border: 1px solid #fbcfe8; }
        .btn-add-new:hover { background-color: #fce7f3; color: #9d174d; }
        .btn-service-request { background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .btn-service-request:hover { background-color: #dbeafe; color: #1e3a8a; }

        /* Box thông báo khi không tìm thấy / lỗi tra cứu */
        .search-result-box {
            background-color: #ffffff;
            border: 1.5px solid #fed7aa;
            border-radius: 12px;
            padding: 25px 30px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.08);
            text-align: center;
        }
        .search-result-box.box-error {
            border-color: #fca5a5;
            background-color: #fffafb;
        }
        .box-icon { font-size: 32px; margin-bottom: 10px; }
        .box-title { font-size: 17px; font-weight: 700; color: #9a3412; margin-bottom: 8px; }
        .box-error .box-title { color: #b91c1c; }
        .box-desc { font-size: 14px; color: #4b5563; line-height: 1.6; margin-bottom: 20px; max-width: 600px; margin-left: auto; margin-right: auto; }
        
        .box-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn-box {
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.15s ease;
        }
        .btn-box-cancel {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-box-cancel:hover { background-color: #e2e8f0; }
        
        .btn-box-retry {
            background-color: #e3261f;
            color: #ffffff;
            border: 1px solid #e3261f;
        }
        .btn-box-retry:hover { background-color: #c91e17; }

        /* Khung hướng dẫn bảo mật khi chưa tra cứu */
        .security-empty-state {
            text-align: center;
            padding: 50px 20px;
            background: #fafaf9;
            border: 1px dashed #d6d3d1;
            border-radius: 12px;
            margin-top: 25px;
        }
        .security-icon { font-size: 45px; margin-bottom: 12px; }
        .security-title { font-size: 18px; font-weight: 700; color: #44403c; margin-bottom: 8px; }
        .security-desc { font-size: 14px; color: #78716c; max-width: 580px; margin: 0 auto; line-height: 1.6; }

        /* Bảng hiển thị kết quả */
        .cccd-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .cccd-table th { background-color: #f8fafc; color: #4a5568; font-weight: 600; padding: 14px; text-align: left; border-bottom: 2px solid #e2e8f0; font-size: 14px; }
        .cccd-table td { padding: 14px; border-bottom: 1px solid #f2f2f7; font-size: 14px; color: #222; }
        .cccd-table tr:hover { background-color: #f8fafc; }

        .badge { padding: 5px 10px; border-radius: 50px; font-size: 12px; font-weight: bold; display: inline-block; white-space: nowrap; }
        .badge-active { background-color: #e6f4ea; color: #137333; }
        .badge-expired { background-color: #fce8e6; color: #c5221f; }
        .badge-lost { background-color: #fef7e0; color: #b06000; }
        .badge-revoked { background-color: #f1f5f9; color: #475569; }

        .btn-action-link { text-decoration: none; font-weight: 600; font-size: 13px; margin-right: 8px; white-space: nowrap; display: inline-block; }
        .btn-action-view { color: #0284c7; }
        .btn-action-view:hover { text-decoration: underline; }
        .btn-action-status { color: #d97706; }
        .btn-action-status:hover { text-decoration: underline; }
        .btn-action-request { color: #7c3aed; }
        .btn-action-request:hover { text-decoration: underline; }
        .btn-action-edit { color: #16a34a; }
        .btn-action-edit:hover { text-decoration: underline; }

        .search-success-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
        }
        .btn-close-search {
            color: #166534;
            text-decoration: none;
            font-weight: 700;
            padding: 4px 10px;
            border: 1px solid #166534;
            border-radius: 4px;
            font-size: 12px;
        }
        .btn-close-search:hover { background-color: #dcfce7; }

        @media (max-width: 760px) {
            .page-header-bar {
                align-items: stretch;
                flex-direction: column;
            }
            .page-header-bar .vneid-section-title {
                text-align: center;
            }
            .btn-home {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php xuatThanhChuyenVaiTroDemo(); ?>
    <div class="boca-brand">
        <div class="boca-title">Bộ Công An</div>
        <div class="boca-sub">Trung tâm dữ liệu Quốc gia về dân cư</div>
    </div>

    <div class="vneid-table-container">
        <div class="page-header-bar">
            <h2 class="vneid-section-title">Hệ thống Tra cứu &amp; Quản lý Căn cước</h2>
            <a href="/" class="btn-header-link btn-home" aria-label="Quay về trang chủ">← Quay về trang chủ</a>
        </div>

        <div style="margin-bottom: 20px;">
            <form method="GET" action="/the_can_cuoc/danh_sach" style="display: flex; gap: 12px; align-items: flex-start; width: 100%;">
                <div class="search-box-wrapper">
                    <input type="text" 
                           id="searchInput" 
                           name="search" 
                           class="search-input" 
                           placeholder="Nhập Số định danh cá nhân (12 số) hoặc Họ và tên để tra cứu..." 
                           value="<?php echo htmlspecialchars($search); ?>"
                           autofocus>
                    <div id="searchHelper" class="search-helper-text">
                        Gợi ý: Nhập đủ 12 chữ số cho CCCD, hoặc ký tự chữ cho Họ tên.
                    </div>
                </div>
                
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-search" style="white-space: nowrap;">Tra cứu</button>
                    <?php if (laCanBoDemo() || laQuanTriDemo()): ?>
                    <a href="/the_can_cuoc/them_moi" class="btn-header-link btn-add-new" style="white-space: nowrap;">+ Thêm mới</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- 1. Khi nhập sai định dạng số CCCD -->
        <?php if (!empty($search_error)): ?>
            <div class="search-result-box box-error">
                <div class="box-icon">⚠️</div>
                <div class="box-title">Lỗi định dạng Số định danh cá nhân</div>
                <div class="box-desc">
                    <?php echo htmlspecialchars($search_error); ?>
                </div>
                <div class="box-actions">
                    <a href="/the_can_cuoc/danh_sach" class="btn-box btn-box-cancel">Hủy bỏ</a>
                    <button type="button" class="btn-box btn-box-retry" onclick="focusSearchInput()">Thử lại</button>
                </div>
            </div>

        <!-- 2. Khi tra cứu không tìm thấy hồ sơ -->
        <?php elseif (!empty($search) && $search_empty): ?>
            <div class="search-result-box">
                <div class="box-icon">🔍</div>
                <div class="box-title">Không tìm thấy thông tin hồ sơ</div>
                <div class="box-desc">
                    Hệ thống không tìm thấy hồ sơ căn cước công dân nào khớp với từ khóa: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>.<br>
                    Vui lòng kiểm tra lại độ chính xác của Số định danh hoặc Họ và tên đã nhập.
                </div>
                <div class="box-actions">
                    <a href="/the_can_cuoc/danh_sach" class="btn-box btn-box-cancel">Hủy bỏ</a>
                    <button type="button" class="btn-box btn-box-retry" onclick="focusSearchInput()">Thử lại</button>
                </div>
            </div>

        <!-- 3. Khi tra cứu có kết quả -->
        <?php elseif (!empty($search) && count($ds_the_can_cuoc) > 0): ?>
            <div class="search-success-bar">
                <div>
                    ✅ Tìm thấy <strong><?php echo count($ds_the_can_cuoc); ?></strong> hồ sơ phù hợp với từ khóa: <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                </div>
                <a href="/the_can_cuoc/danh_sach" class="btn-close-search">✕ Đóng kết quả</a>
            </div>

            <table class="cccd-table">
                <thead>
                    <tr>
                        <th>Số định danh</th>
                        <th>Họ và Tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Quê quán</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ds_the_can_cuoc as $the_can_cuoc): ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($the_can_cuoc['so_cccd']); ?></td>
                            <td style="text-transform: uppercase; font-weight: 600;"><?php echo htmlspecialchars($the_can_cuoc['ho_ten']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($the_can_cuoc['ngay_sinh'])); ?></td>
                            <td><?php echo htmlspecialchars($the_can_cuoc['gioi_tinh']); ?></td>
                            <td><?php echo htmlspecialchars($the_can_cuoc['que_quan']); ?></td>
                            <td>
                                <?php if ($the_can_cuoc['trang_thai'] === 'con_hieu_luc'): ?>
                                    <span class="badge badge-active">Còn hiệu lực</span>
                                <?php elseif ($the_can_cuoc['trang_thai'] === 'het_han'): ?>
                                    <span class="badge badge-expired">Hết hạn</span>
                                <?php elseif ($the_can_cuoc['trang_thai'] === 'bao_mat'): ?>
                                    <span class="badge badge-lost">Báo mất</span>
                                <?php elseif ($the_can_cuoc['trang_thai'] === 'thu_hoi'): ?>
                                    <span class="badge badge-revoked">Thu hồi</span>
                                <?php else: ?>
                                    <span class="badge badge-lost"><?php echo htmlspecialchars($the_can_cuoc['trang_thai']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/the_can_cuoc/chi_tiet?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action-link btn-action-view" title="Xem thông tin chi tiết">Chi tiết</a>
                                <?php if (laCanBoDemo() || laQuanTriDemo()): ?>
                                <a href="/the_can_cuoc/quan_ly_trang_thai?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action-link btn-action-status" title="Đổi trạng thái thẻ">Trạng thái</a>
                                <?php endif; ?>
                                <?php if (laQuanTriDemo()): ?>
                                <a href="/the_can_cuoc/cap_nhat?id=<?php echo $the_can_cuoc['id']; ?>" class="btn-action-link btn-action-edit" title="Chỉnh sửa hồ sơ">Sửa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <!-- 4. Trạng thái mặc định bảo mật: Chưa thực hiện tra cứu -->
        <?php else: ?>
            <div class="security-empty-state">
                <div class="security-icon">🛡️</div>
                <div class="security-title">Dữ liệu Định danh & Dân cư được bảo mật</div>
                <div class="security-desc">
                    Để đảm bảo quyền riêng tư và an toàn thông tin cá nhân của công dân theo quy định, hệ thống không tự động hiển thị danh sách hồ sơ căn cước hàng loạt.<br><br>
                    Vui lòng nhập <strong>Số định danh cá nhân (12 chữ số)</strong> hoặc <strong>Họ và tên</strong> vào ô tìm kiếm ở trên để tiến hành tra cứu hồ sơ.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function focusSearchInput() {
            const input = document.getElementById('searchInput');
            if (input) {
                input.focus();
                input.select();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchHelper = document.getElementById('searchHelper');

            if (!searchInput || !searchHelper) return;

            function capNhatGoiY() {
                const giaTri = searchInput.value.trim();
                if (giaTri === '') {
                    searchHelper.innerHTML = 'Gợi ý: Nhập đủ 12 chữ số cho CCCD, hoặc ký tự chữ cho Họ tên.';
                    searchHelper.style.color = '#64748b';
                    return;
                }

                if (/^\d+$/.test(giaTri)) {
                    const doDai = giaTri.length;
                    if (doDai === 12) {
                        searchHelper.innerHTML = `<span style="color: #166534; font-weight: 600;">✓ Đã đủ 12 số CCCD hợp lệ</span>`;
                    } else {
                        searchHelper.innerHTML = `<span style="color: #b91c1c; font-weight: 600;">Đang nhập số CCCD: ${doDai}/12 số (còn thiếu ${12 - doDai > 0 ? 12 - doDai : 0} số)</span>`;
                    }
                } else {
                    searchHelper.innerHTML = `<span style="color: #1d4ed8;">🔍 Đang tra cứu theo Họ và tên: "${giaTri}"</span>`;
                }
            }

            searchInput.addEventListener('input', capNhatGoiY);
            capNhatGoiY();
        });
    </script>
</body>
</html>
