<?php
  $tong_nguoi_dung = array_sum(array_column($ds_theo_muc ?? [], 'so_luong'));
  $lon_nhat_tinh   = max(array_column($ds_theo_tinh ?? [], 'so_luong') ?: [1]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Báo Báo Thống Kê</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/muc-dinh-danh.css">
  <link rel="stylesheet" href="/public/css/quan-tri.css">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-light">

  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

  <main class="than-trang-quan-tri">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h2 class="mb-1 text-danger fw-bold fs-4">
          <i class="fa-solid fa-chart-pie me-2"></i> Báo Cáo Thống Kê Hệ Thống
        </h2>
        <p class="text-muted mb-0 font-size-14">
          Tổng hợp chi tiết người dùng, ví giấy tờ, dịch vụ công và nhật ký bảo mật.
        </p>
      </div>
      <div class="thanh-chuc-nang-in d-flex gap-2">
        <button onclick="window.print()" class="nut-vien nut-nho d-inline-flex align-items-center gap-2">
          <i class="fa-solid fa-file-pdf text-danger"></i> Xuất / In PDF
        </button>
        <button onclick="xuatBaoCaoExcel()" class="nut-do nut-nho d-inline-flex align-items-center gap-2">
          <i class="fa-solid fa-file-excel"></i> Xuất Báo Cáo Excel
        </button>
      </div>
    </div>
    <form method="GET" action="" class="khoi" style="padding: 16px 20px; display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 15px; align-items: center; width: 100%; box-sizing: border-box;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <label class="fw-bold fs-7 mb-0 text-nowrap">Từ ngày:</label>
        <input type="date" name="tu_ngay" value="<?= e($_GET['tu_ngay'] ?? date('Y-m-01')) ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; background: #fafafa; font-family: inherit;">
      </div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <label class="fw-bold fs-7 mb-0 text-nowrap">Đến ngày:</label>
        <input type="date" name="den_ngay" value="<?= e($_GET['den_ngay'] ?? date('Y-m-d')) ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; background: #fafafa; font-family: inherit;">
      </div>

      <div style="display: flex; align-items: center; gap: 10px; grid-column: span 2; justify-content: flex-end;">
        <button type="submit" class="nut-do nut-nho" style="padding: 10px 20px;">Tìm kiếm</button>
        <a href="?" class="nut-vien nut-nho" style="padding: 10px 18px; text-decoration: none; display: inline-flex; align-items: center;">Xóa</a>
      </div>
    </form>

    <section class="khoi">
      <div class="tieu-de-hang mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-users text-danger me-2"></i>Người Dùng</h2>
        <small class="text-muted">Tổng số: <b><?= (int)$tong_nguoi_dung ?></b> tài khoản</small>
      </div>

      <div class="hai-cot">
        <div>
          <h3 class="fs-6 fw-bold mb-3">Theo mức định danh</h3>
          <div class="bieu-do-khung">
            <canvas id="chartMucDinhDanh"></canvas>
          </div>
        </div>

        <div>
          <h3 class="fs-6 fw-bold mb-3">Theo Tỉnh / Thành Phố</h3>
          <div class="bang-cuon" style="max-height: 260px;">
            <table class="bang">
              <thead>
                <tr>
                  <th>Tỉnh / Thành phố</th>
                  <th>Tỷ lệ</th>
                  <th class="text-end">Số lượng</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ds_theo_tinh ?? [] as $dong): ?>
                  <tr>
                    <td><?= e($dong['tinh_thanh'] ?? '(Chưa cập nhật)') ?></td>
                    <td style="width: 45%;">
                      <div class="thanh-do">
                        <i style="width: <?= round(((int)$dong['so_luong'] / $lon_nhat_tinh) * 100) ?>%"></i>
                      </div>
                    </td>
                    <td class="text-end"><b><?= (int)$dong['so_luong'] ?></b></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($ds_theo_tinh)): ?>
                  <tr><td colspan="3" class="trong">Chưa có dữ liệu khu vực.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="khoi">
      <div class="tieu-de-hang mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-id-card text-danger me-2"></i> Giấy Tờ</h2>
      </div>

      <div class="luoi luoi-4">
        <div class="the-dem-so border-start border-4 border-success">
          <span class="text-muted fs-7 d-block">Thẻ Căn Cước Công Dân</span>
          <strong class="fs-3 text-success"><?= (int)($tk_giay_to['cccd'] ?? $tong_nguoi_dung) ?></strong>
          <small class="text-success d-block"><i class="fa-solid fa-circle-check"></i> Đã xác thực</small>
        </div>

        <div class="the-dem-so border-start border-4 border-primary">
          <span class="text-muted fs-7 d-block">Giấy Phép Lái Xe (GPLX)</span>
          <strong class="fs-3 text-primary"><?= (int)($tk_giay_to['gplx'] ?? 0) ?></strong>
          <small class="text-muted d-block">Giấy tờ trong ví</small>
        </div>

        <div class="the-dem-so border-start border-4 border-warning">
          <span class="text-muted fs-7 d-block">Bảo Hiểm Y Tế (BHYT)</span>
          <strong class="fs-3 text-warning"><?= (int)($tk_giay_to['bhyt'] ?? 0) ?></strong>
          <small class="text-muted d-block">Giấy tờ trong ví</small>
        </div>

        <div class="the-dem-so border-start border-4 border-danger">
          <span class="text-muted fs-7 d-block">Đăng Ký Xe</span>
          <strong class="fs-3 text-danger"><?= (int)($tk_giay_to['dang_ky_xe'] ?? 0) ?></strong>
          <small class="text-muted d-block">Giấy tờ trong ví</small>
        </div>

        <div class="the-dem-so border-start border-4 border-danger">
          <span class="text-muted fs-7 d-block">Hộ Chiếu</span>
          <strong class="fs-3 text-danger"><?= (int)($tk_giay_to['ho_chieu'] ?? 0) ?></strong>
          <small class="text-muted d-block">Giấy tờ trong ví</small>
        </div>
      </div>
    </section>

    <section class="khoi">
      <div class="tieu-de-hang mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-file-signature text-danger me-2"></i> Dịch Vụ Công</h2>
        <small class="text-muted">Hồ sơ xử lý theo loại dịch vụ và trạng thái</small>
      </div>

      <div class="bang-cuon">
        <table class="bang" id="bangThongKeDichVu">
          <thead>
            <tr>
              <th>Loại dịch vụ công</th>
              <th class="text-center">Tổng số hồ sơ</th>
              <th class="text-center">Đang chờ xử lý</th>
              <th class="text-center">Đã phê duyệt</th>
              <th class="text-center">Từ chối hồ sơ</th>
              <th>Tỷ lệ hoàn thành</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ds_theo_dich_vu ?? [] as $dong): ?>
              <?php 
                $tong_hs = (int)$dong['tong'];
                $da_duyet = (int)$dong['da_duyet'];
                $ty_le = $tong_hs > 0 ? round(($da_duyet / $tong_hs) * 100) : 0;
              ?>
              <tr>
                <td><b><?= e($dong['ten_dich_vu']) ?></b></td>
                <td class="text-center"><b><?= $tong_hs ?></b></td>
                <td class="text-center"><span class="nhan nhan-dang_xu_ly"><?= (int)$dong['dang_cho'] ?></span></td>
                <td class="text-center"><span class="nhan nhan-da_duyet"><?= $da_duyet ?></span></td>
                <td class="text-center"><span class="nhan nhan-tu_choi"><?= (int)$dong['tu_choi'] ?></span></td>
                <td style="width: 20%;">
                  <div class="d-flex align-items-center gap-2">
                    <div class="thanh-do flex-grow-1"><i style="width: <?= $ty_le ?>%"></i></div>
                    <small class="fw-bold"><?= $ty_le ?>%</small>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($ds_theo_dich_vu)): ?>
              <tr><td colspan="6" class="trong">Chưa có dữ liệu hồ sơ dịch vụ công.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="khoi">
      <div class="tieu-de-hang mb-3">
        <h2 class="mb-0"><i class="fa-solid fa-shield-halved text-danger me-2"></i> Bảo Mật & Đăng Nhập</h2>
        <small class="text-muted">Theo dõi lượt đăng nhập và cảnh báo bảo mật (7 ngày qua)</small>
      </div>

      <div class="hai-cot">
        <div>
          <h3 class="fs-6 fw-bold mb-3">Biểu đồ Lượt Đăng Nhập (7 ngày)</h3>
          <div class="bieu-do-khung">
            <canvas id="chartDangNhap"></canvas>
          </div>
        </div>
        <div>
          <h3 class="fs-6 fw-bold mb-3">Chi tiết sự kiện đăng nhập</h3>
          <div class="bang-cuon">
            <table class="bang">
              <thead>
                <tr>
                  <th>Ngày</th>
                  <th class="text-success">Thành công</th>
                  <th class="text-danger">Thất bại (Cảnh báo)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ds_dang_nhap_7_ngay ?? [] as $dong): ?>
                  <tr>
                    <td><b><?= e($dong['ngay']) ?></b></td>
                    <td class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i><?= (int)$dong['thanh_cong'] ?> lượt</td>
                    <td class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i><?= (int)$dong['that_bai'] ?> lượt</td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($ds_dang_nhap_7_ngay)): ?>
                  <tr><td colspan="3" class="trong">Chưa có nhật ký đăng nhập.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

  </main>
  <script>
    const dataMuc = <?= json_encode($ds_theo_muc ?? []) ?>;
    const labelsMuc = dataMuc.length ? dataMuc.map(item => 'Mức ' + item.muc_dinh_danh) : ['Mức 1', 'Mức 2'];
    const valuesMuc = dataMuc.length ? dataMuc.map(item => parseInt(item.so_luong)) : [0, 0];

    new Chart(document.getElementById('chartMucDinhDanh'), {
      type: 'doughnut',
      data: {
        labels: labelsMuc,
        datasets: [{
          data: valuesMuc,
          backgroundColor: ['#fbbf24', '#34d399', '#f87171']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
      }
    });

    const dataDangNhap = <?= json_encode($ds_dang_nhap_7_ngay ?? []) ?>;
    const labelsNgay = dataDangNhap.map(item => item.ngay);
    const valThanhCong = dataDangNhap.map(item => item.thanh_cong);
    const valThatBai = dataDangNhap.map(item => item.that_bai);

    new Chart(document.getElementById('chartDangNhap'), {
      type: 'bar',
      data: {
        labels: labelsNgay,
        datasets: [
          { label: 'Thành công', data: valThanhCong, backgroundColor: '#22c55e' },
          { label: 'Thất bại', data: valThatBai, backgroundColor: '#ef4444' }
        ]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { position: 'bottom' } }
      }
    });

    function xuatBaoCaoExcel() {
      const tuNgayInput = document.querySelector('input[name="tu_ngay"]').value;
      const denNgayInput = document.querySelector('input[name="den_ngay"]').value;
      
      const formatVnDate = (dateStr) => {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
      };

      const thoiGianStr = (tuNgayInput && denNgayInput) 
        ? `Từ ngày ${formatVnDate(tuNgayInput)} Đến ngày ${formatVnDate(denNgayInput)}` 
        : `Thời gian xuất: ${new Date().toLocaleDateString('vi-VN')}`;

      let html = "";
      html += `<h3>BÁO CÁO THỐNG KÊ TỔNG HỢP HỆ THỐNG MINI-VNEID</h3>`;
      html += `<p><b>${thoiGianStr}</b></p><br>`;

      html += `<b>1. Thống kê Mức định danh người dùng</b>`;
      html += `<table border="1"><thead><tr><th>Mức định danh</th><th>Số lượng tài khoản</th></tr></thead><tbody>`;
      const dataMuc = <?= json_encode($ds_theo_muc ?? []) ?>;
      if (dataMuc.length > 0) {
        dataMuc.forEach(item => {
          html += `<tr><td>Mức ${item.muc_dinh_danh}</td><td align="center">${item.so_luong}</td></tr>`;
        });
      } else {
        html += `<tr><td colspan="2" align="center">Chưa có dữ liệu</td></tr>`;
      }
      html += `</tbody></table><br>`;

      html += `<b>2. Thống kê Giấy tờ trong ví</b>`;
      html += `<table border="1"><thead><tr><th>Loại giấy tờ</th><th>Số lượng</th><th>Trạng thái</th></tr></thead><tbody>
        <tr><td>Thẻ Căn Cước Công Dân (CCCD)</td><td align="center"><?= (int)($tk_giay_to['cccd'] ?? $tong_nguoi_dung) ?></td><td>Đã xác thực</td></tr>
        <tr><td>Giấy Phép Lái Xe (GPLX)</td><td align="center"><?= (int)($tk_giay_to['gplx'] ?? 0) ?></td><td>Trong ví</td></tr>
        <tr><td>Bảo Hiểm Y Tế (BHYT)</td><td align="center"><?= (int)($tk_giay_to['bhyt'] ?? 0) ?></td><td>Trong ví</td></tr>
        <tr><td>Đăng Ký Xe</td><td align="center"><?= (int)($tk_giay_to['dang_ky_xe'] ?? 0) ?></td><td>Trong ví</td></tr>
        <tr><td>Hộ Chiếu</td><td align="center"><?= (int)($tk_giay_to['ho_chieu'] ?? 0) ?></td><td>Trong ví</td></tr>
      </tbody></table><br>`;

      html += `<b>3. Thống kê Dịch vụ công</b>`;
      html += `<table border="1"><thead><tr><th>Loại dịch vụ công</th><th>Tổng số hồ sơ</th><th>Đang chờ xử lý</th><th>Đã phê duyệt</th><th>Từ chối</th><th>Tỷ lệ hoàn thành</th></tr></thead><tbody>`;
      const bangDV = document.getElementById("bangThongKeDichVu");
      if (bangDV) {
        bangDV.querySelectorAll("tbody tr").forEach(tr => {
          const tds = tr.querySelectorAll("td");
          if (tds.length >= 5) {
            const ten = tds[0].innerText.trim();
            const tong = tds[1].innerText.trim();
            const cho = tds[2].innerText.trim();
            const duyet = tds[3].innerText.trim();
            const tuchoi = tds[4].innerText.trim();
            const tongNum = parseInt(tong) || 0;
            const duyetNum = parseInt(duyet) || 0;
            const tyle = tongNum > 0 ? Math.round((duyetNum / tongNum) * 100) + "%" : "0%";

            html += `<tr><td>${ten}</td><td align="center">${tong}</td><td align="center">${cho}</td><td align="center">${duyet}</td><td align="center">${tuchoi}</td><td align="center">${tyle}</td></tr>`;
          }
        });
      }
      html += `</tbody></table><br>`;

      html += `<b>4. Thống kê Bảo mật & Đăng nhập</b>`;
      html += `<table border="1"><thead><tr><th>Ngày</th><th>Thành công</th><th>Thất bại</th></tr></thead><tbody>`;
      const dataDN = <?= json_encode($ds_dang_nhap_7_ngay ?? []) ?>;
      if (dataDN.length > 0) {
        dataDN.forEach(item => {
          html += `<tr><td>${item.ngay}</td><td align="center">${item.thanh_cong} lượt</td><td align="center">${item.that_bai} lượt</td></tr>`;
        });
      } else {
        html += `<tr><td colspan="3" align="center">Chưa có dữ liệu</td></tr>`;
      }
      html += `</tbody></table>`;

      const blob = new Blob(["\uFEFF" + html], { type: 'application/vnd.ms-excel;charset=utf-8' });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `Bao_Cao_Tong_Hop_${tuNgayInput}_den_${denNgayInput}.xls`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  </script>
</body>
</html>