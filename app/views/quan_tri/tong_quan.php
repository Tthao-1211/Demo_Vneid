<?php
// app/views/quan_tri/tong_quan.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trang Tổng Quan Quản Trị - VNeID</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link rel="stylesheet" href="/public/css/trang-chu.css">
  <link rel="stylesheet" href="/public/css/quan-tri.css">
</head>
<body class="bg-light">
  <?php require __DIR__ . '/../nguoi_dung/thanh_menu.php'; ?>

  <div class="chuan-noi-dung-quan-tri">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0 text-danger fw-bold"><i class="fa-solid fa-chart-line me-2"></i> Tổng Quan Hệ Thống Quản Trị</h4>
    </div>

    <?php if (!empty($_SESSION['thong_bao'])): ?>
      <div class="alert alert-success py-2"><?= e($_SESSION['thong_bao']) ?></div>
      <?php unset($_SESSION['thong_bao']); ?>
    <?php endif; ?>

    <div class="luoi luoi-3 mb-2">
      <?php 
        $tk_trang_thai = [];
        foreach ($ds_thong_ke_trang_thai as $item) {
            $tk_trang_thai[$item['trang_thai']] = (int)$item['so_luong'];
        }
      ?>
      <div class="the-so" style="background: #e6f4ea;">
        <strong style="color: #137333;"><?= $tk_trang_thai['hoat_dong'] ?? 0 ?></strong>
        <span>Tài khoản đang hoạt động</span>
      </div>
      <div class="the-so" style="background: #fef7e0;">
        <strong style="color: #b06000;"><?= $tk_trang_thai['cho_kich_hoat'] ?? 0 ?></strong>
        <span>Tài khoản chờ kích hoạt</span>
      </div>
      <div class="the-so" style="background: #fce8e6;">
        <strong style="color: #c5221f;"><?= $tk_trang_thai['bi_khoa'] ?? 0 ?></strong>
        <span>Tài khoản bị khóa</span>
      </div>
    </div>

    <div class="row g-4 mt-1">
      <div class="col-md-6">
        <div class="khoi h-100">
          <h2><i class="fa-solid fa-users-gear text-danger me-2"></i> Phân Bổ Vai Trò</h2>
          <ul class="list-group list-group-flush">
            <?php foreach ($ds_thong_ke_vai_tro as $vt): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                <span class="fw-medium"><?= e($vt['ten_vai_tro']) ?></span>
                <span class="badge bg-danger rounded-pill px-3 py-2"><?= (int)$vt['so_luong'] ?> tài khoản</span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="khoi h-100">
          <h2><i class="fa-solid fa-shield-halved text-danger me-2"></i> Nhật Ký Đăng Nhập</h2>
          <?php 
            $tk_dang_nhap = [];
            foreach ($ds_thong_ke_dang_nhap as $dn) {
                $tk_dang_nhap[$dn['hanh_dong']] = (int)$dn['so_luong'];
            }
          ?>
          <div class="d-flex flex-column gap-3">
            <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
              <span><i class="fa-solid fa-circle-check text-success me-2"></i> Đăng nhập thành công</span>
              <span class="fw-bold text-success"><?= $tk_dang_nhap['thanh_cong'] ?? 0 ?> lượt</span>
            </div>
            <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
              <span><i class="fa-solid fa-circle-xmark text-danger me-2"></i> Đăng nhập thất bại</span>
              <span class="fw-bold text-danger"><?= $tk_dang_nhap['that_bai'] ?? 0 ?> lượt</span>
            </div>
            <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
              <span><i class="fa-solid fa-right-from-bracket text-secondary me-2"></i> Đã đăng xuất</span>
              <span class="fw-bold text-secondary"><?= $tk_dang_nhap['dang_xuat'] ?? 0 ?> lượt</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-end mt-3">
      <a href="/quan_tri/danh_sach_nguoi_dung" class="btn btn-danger px-4 py-2 fw-semibold" style="border-radius: 10px; background: linear-gradient(180deg, #e8261c, #cd1710);">
        <i class="fa-solid fa-users me-2"></i> Quản lý danh sách người dùng
      </a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>