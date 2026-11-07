<?php
global $pdo;
$menu_tb_ds = [];
$menu_tb_chua_doc = 0;
if (!empty($_SESSION['ma_nguoi_dung']) && isset($pdo)) {
    try {
        $st = $pdo->prepare('SELECT ma_thong_bao, tieu_de, da_doc, ngay_tao FROM thong_bao
                             WHERE ma_nguoi_nhan = ? ORDER BY ngay_tao DESC, ma_thong_bao DESC LIMIT 6');
        $st->execute([$_SESSION['ma_nguoi_dung']]);
        $menu_tb_ds = $st->fetchAll(PDO::FETCH_ASSOC);

        $st = $pdo->prepare('SELECT COUNT(*) FROM thong_bao WHERE ma_nguoi_nhan = ? AND da_doc = 0');
        $st->execute([$_SESSION['ma_nguoi_dung']]);
        $menu_tb_chua_doc = (int)$st->fetchColumn();
    } catch (PDOException $e) {
        // bảng chưa sẵn sàng thì bỏ qua, không làm hỏng menu
    }
}

$ma_vai_tro  = (int)($_SESSION['ma_vai_tro'] ?? 1);
$la_quan_tri = $ma_vai_tro === VAI_TRO_QUAN_TRI;
$la_can_bo   = $ma_vai_tro === VAI_TRO_CAN_BO;

$trang_chu = '/nguoi_dung/trang_chu';
$duong_dan_hien_tai = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Trang dành cho công dân: cán bộ vào các trang này vẫn KHÔNG hiện cột chức năng duyệt hồ sơ
$trang_cong_dan = [
    '/dich_vu/danh_sach',
    '/dich_vu/nop_ho_so',
    '/giay_to_vi/danh_sach',
    '/giay_to_vi/chi_tiet',
    '/giay_to_vi/them_moi',
    '/giay_to_vi/xem_the_can_cuoc',
    '/muc_dinh_danh/nop_ho_so',
    '/the_can_cuoc/yeu_cau_cua_toi',
    '/the_can_cuoc/yeu_cau_dieu_chinh',
    '/the_can_cuoc/yeu_cau_cap_the',
    '/the_can_cuoc/chi_tiet',
];
$la_trang_cong_dan = in_array($duong_dan_hien_tai, $trang_cong_dan, true);

if ($la_quan_tri) {
    $ds_muc_menu = [
        [$trang_chu,             'Trang chủ'],
        ['/quan_tri/tong_quan',  'Quản trị'],
    ];
} elseif ($la_can_bo) {
    $ds_muc_menu = [
        [$trang_chu,                        'Trang chủ'],
        ['/nguoi_dung/trang_chu_can_bo',    'Duyệt hồ sơ'],
    ];
} else {
    $ds_muc_menu = [
        [$trang_chu,                        'Trang chủ'],
    ];
}

if (!function_exists('laMucDangChon')) {
    function laMucDangChon($duong_dan_muc, $duong_dan_hien_tai, $la_trang_cong_dan = false) {
        if ($duong_dan_muc === $duong_dan_hien_tai) {
            return true;
        }
        $module = explode('/', trim($duong_dan_hien_tai, '/'))[0];
        if ($duong_dan_muc === '/quan_tri/tong_quan') {
            return $module === 'quan_tri';
        }
        if ($duong_dan_muc === '/nguoi_dung/trang_chu_can_bo') {
            return !$la_trang_cong_dan
                && in_array($module, ['muc_dinh_danh', 'giay_to_vi', 'dich_vu', 'can_bo', 'the_can_cuoc'], true);
        }
        return false;
    }
}
?>
<header class="thanh-menu">
  <div class="chua">
    <a class="thuong-hieu" href="<?= $trang_chu ?>">
      <img src="/public/images/logo-vneid.png" alt="Logo VNeID">
      <span>VNeID</span>
    </a>

    <nav class="menu">
      <?php foreach ($ds_muc_menu as [$duong_dan_muc, $nhan]): ?>
        <a href="<?= $duong_dan_muc ?>"
           class="<?= laMucDangChon($duong_dan_muc, $duong_dan_hien_tai, $la_trang_cong_dan) ? 'dang-chon' : '' ?>"><?= e($nhan) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="nhom-phai">
      <details class="thong-bao-dropdown">
        <summary aria-label="Thông báo">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          <?php if ($menu_tb_chua_doc > 0): ?>
            <span class="so-tb"><?= $menu_tb_chua_doc > 9 ? '9+' : $menu_tb_chua_doc ?></span>
          <?php endif; ?>
        </summary>

        <div class="danh-sach-thong-bao">
          <div class="tieu-de-dropdown">
            <span>Thông báo</span>
            <a href="/thong_bao/danh_sach">Xem tất cả</a>
          </div>
          <div class="noi-dung-ds">
            <?php if (!$menu_tb_ds): ?>
              <p class="khong-co-tb">Không có thông báo.</p>
            <?php else: foreach ($menu_tb_ds as $tb): ?>
              <a href="/thong_bao/chi_tiet?ma_thong_bao=<?= (int)$tb['ma_thong_bao'] ?>"
                class="item-tb <?= $tb['da_doc'] ? '' : 'chua-doc' ?>">
                <div class="tieu-de-tb"><?= e($tb['tieu_de']) ?></div>
                <div class="thoi-gian-tb"><?= date('H:i d/m/Y', strtotime($tb['ngay_tao'])) ?></div>
              </a>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </details>

      <details class="tai-khoan">
        <summary>
          <?php if (!empty($_SESSION['anh_chan_dung'])): ?>
            <span class="chu-cai" style="padding:0; overflow:hidden; display:flex; align-items:center; justify-content:center;">
              <img src="<?= e($_SESSION['anh_chan_dung']) ?>" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
            </span>
          <?php else: ?>
            <span class="chu-cai"><?= e(mb_strtoupper(mb_substr(trim($_SESSION['ho_ten'] ?? 'U'), -1, 1, 'UTF-8'), 'UTF-8')) ?></span>
          <?php endif; ?>
          <span class="ten"><?= e($_SESSION['ho_ten'] ?? 'Tài khoản') ?></span>
          <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </summary>
        <div class="danh-sach">
          <a href="/nguoi_dung/cai_dat">Cài đặt tài khoản</a>
          <a href="/the_can_cuoc/yeu_cau_cua_toi">Yêu cầu của tôi</a>
          <a href="/nguoi_dung/dang_xuat" onclick="return confirm('Đăng xuất khỏi tài khoản?');">Đăng xuất</a>
        </div>
      </details>
    </div>
  </div>
</header>

<?php
$module_hien_tai = explode('/', trim($duong_dan_hien_tai, '/'))[0];

$ds_chuc_nang = [];
if ($la_quan_tri) {
    $tieu_de_cot  = 'Quản trị hệ thống';
    $ds_chuc_nang = [
        ['/quan_tri/tong_quan',            'Tổng quan', '',
            '<path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>'],
        ['/quan_tri/danh_sach_nguoi_dung', 'Quản lý người dùng', '',
            '<circle cx="9" cy="8" r="4"/><path d="M2 21c1-4 4-6 7-6s6 2 7 6M17 11a3 3 0 100-6M22 21c-.5-3-2-5-4-5.5"/>'],
        ['/quan_tri/bao_cao_thong_ke',     'Báo cáo thống kê', '',
            '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>'],
        ['/quan_tri/nhat_ky_he_thong',     'Nhật ký hoạt động', '',
            '<path d="M6 3h9l4 4v14H6zM14 3v5h5M9 13h7M9 17h7"/>'],
    ];
} elseif ($la_can_bo) {
    $tieu_de_cot  = 'Menu';
    $ds_chuc_nang = [
        ['/nguoi_dung/trang_chu_can_bo', 'Tổng quan', 'nguoi_dung',
            '<rect x="3" y="3" width="8" height="8" rx="1.5"/>
              <rect x="13" y="3" width="8" height="5" rx="1.5"/>
              <rect x="13" y="10" width="8" height="11" rx="1.5"/>
              <rect x="3" y="13" width="8" height="8" rx="1.5"/>'],

        // ---- Nhóm: Căn cước ----
        ['', 'Căn cước', 'the_can_cuoc',
            '<rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8.5" cy="11" r="2"/><path d="M5.5 16c.5-1.7 1.8-2.5 3-2.5s2.5.8 3 2.5M14 10h5M14 14h5"/>',
            [
                ['/the_can_cuoc/danh_sach',          'Danh sách thẻ',     'the_can_cuoc'],
                ['/the_can_cuoc/danh_sach_yeu_cau',  'Danh sách yêu cầu', 'the_can_cuoc'],
            ]],

        ['/muc_dinh_danh/danh_sach_cho_duyet', 'Nâng mức định danh', 'muc_dinh_danh',
            '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M8.5 12l2.5 2.5L16 9.5"/>'],

        // ---- Nhóm: Giấy tờ ----
        ['', 'Giấy tờ', 'can_bo',
            '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
            [
                ['/can_bo/duyet?trang_thai=&che_do=xem', 'Danh sách giấy tờ',    'can_bo'],
                ['/can_bo/duyet',                        'Duyệt giấy tờ',        'can_bo'],
                ['/can_bo/loai_giay_to',                 'Quản lý loại giấy tờ', 'can_bo'],
                ['/can_bo/lich_su',                      'Lịch sử xử lý',        'can_bo'],
            ]],

        ['/dich_vu/ds_ho_so_can_xu_ly', 'Duyệt hồ sơ dịch vụ công', 'dich_vu',
            '<path d="M3 10l9-6 9 6M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18"/>'],
    ];
}

$trang_co_cot = $la_quan_tri
    ? ($module_hien_tai === 'quan_tri')
    : ($la_can_bo
        && !$la_trang_cong_dan
        && (in_array($module_hien_tai, ['muc_dinh_danh', 'giay_to_vi', 'dich_vu', 'can_bo', 'the_can_cuoc'], true)
            || $duong_dan_hien_tai === '/nguoi_dung/trang_chu_can_bo'));

// Kiểm tra một liên kết có đang được chọn không
$kiem_tra_dang_chon = function (string $lk, string $mo_dun) use ($la_quan_tri, $duong_dan_hien_tai, $module_hien_tai): bool {
    if ($la_quan_tri) {
        return $lk === $duong_dan_hien_tai;
    }

    $lk_path = parse_url($lk, PHP_URL_PATH);

    if ($mo_dun === 'can_bo') {
        // So theo đường dẫn đầy đủ để mỗi mục sáng riêng
        $xem_tat_ca = isset($_GET['trang_thai']) && $_GET['trang_thai'] === '';
        if (strpos($lk, '?') !== false) {
            return ($lk_path === $duong_dan_hien_tai) && $xem_tat_ca;
        }
        return ($lk_path === $duong_dan_hien_tai)
            && !($lk_path === '/can_bo/duyet' && $xem_tat_ca);
    }

    if ($mo_dun === 'the_can_cuoc') {
        // Trang thuộc nhóm "yêu cầu": đường dẫn hiện tại có chứa 'yeu_cau'
        $la_trang_yeu_cau = strpos($duong_dan_hien_tai, 'yeu_cau') !== false;
        if ($lk_path === '/the_can_cuoc/danh_sach') {
            return ($module_hien_tai === 'the_can_cuoc') && !$la_trang_yeu_cau;
        }
        return $la_trang_yeu_cau;
    }

    return $mo_dun === $module_hien_tai;
};

if ($ds_chuc_nang && $trang_co_cot):
?>
<aside class="cot-chuc-nang cot-co-dinh">
  <h3><?= e($tieu_de_cot) ?></h3>

  <?php foreach ($ds_chuc_nang as $cn):
      $lk      = $cn[0];
      $ten_cn  = $cn[1];
      $mo_dun  = $cn[2];
      $icon_cn = $cn[3];
      $ds_con  = $cn[4] ?? [];
  ?>

    <?php if ($ds_con):
        // Mở sẵn nếu đang đứng ở một trang con
        $co_con_chon = false;
        foreach ($ds_con as $c) {
            if ($kiem_tra_dang_chon($c[0], $c[2])) { $co_con_chon = true; break; }
        }
    ?>
      <details class="nhom-con" <?= $co_con_chon ? 'open' : '' ?>>
        <summary class="muc-cha <?= $co_con_chon ? 'co-con-chon' : '' ?>">
          <svg viewBox="0 0 24 24"><?= $icon_cn ?></svg>
          <span><?= e($ten_cn) ?></span>
          <svg class="mui-ten" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
        </summary>
        <div class="ds-con">
          <?php foreach ($ds_con as $c): ?>
            <a href="<?= $c[0] ?>" class="muc-con <?= $kiem_tra_dang_chon($c[0], $c[2]) ? 'dang-chon' : '' ?>">
              <span><?= e($c[1]) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </details>

    <?php else: ?>
      <a href="<?= $lk ?>" class="<?= $kiem_tra_dang_chon($lk, $mo_dun) ? 'dang-chon' : '' ?>">
        <svg viewBox="0 0 24 24"><?= $icon_cn ?></svg>
        <span><?= e($ten_cn) ?><?= $lk === '#' ? '<small>Sắp có</small>' : '' ?></span>
      </a>
    <?php endif; ?>

  <?php endforeach; ?>
</aside>
<?php endif; ?>

<style>
.thanh-menu .nhom-phai {
  display: flex;
  align-items: center;
  margin-left: auto;
  gap: 12px; 
}

.thanh-menu .thong-bao-dropdown {
  position: relative;
}
.thanh-menu .thong-bao-dropdown summary {
  list-style: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  padding: 8px;
  border-radius: 50%;
  transition: background 0.2s;
}
.thanh-menu .thong-bao-dropdown summary::-webkit-details-marker {
  display: none;
}
.thanh-menu .thong-bao-dropdown summary:hover {
  background: rgba(0, 0, 0, 0.05);
}
.thanh-menu .cham-do-thong-bao {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 8px;
  height: 8px;
  background-color: #ff3b30;
  border-radius: 50%;
  border: 1px solid #fff;
}
.thanh-menu .thong-bao-dropdown .danh-sach-thong-bao {
  position: absolute;
  right: 0;
  top: 100%;
  width: 320px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  margin-top: 8px;
  z-index: 1000;
  overflow: hidden;
  border: 1px solid #eaeaea;
}
.thanh-menu .tieu-de-dropdown {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-weight: 600;
  font-size: 14px;
}
.thanh-menu .tieu-de-dropdown a {
  font-size: 12px;
  color: #0066cc;
  text-decoration: none;
}
.thanh-menu .noi-dung-ds {
  max-height: 300px;
  overflow-y: auto;
}
.thanh-menu .item-tb {
  display: block;
  padding: 10px 16px;
  border-bottom: 1px solid #f7f7f7;
  text-decoration: none;
  color: #333;
  transition: background 0.2s;
}
.thanh-menu .item-tb:hover {
  background: #f9f9f9;
}
.thanh-menu .item-tb.chua-doc {
  background: #f0f7ff;
}
.thanh-menu .tieu-de-tb {
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 4px;
}
.thanh-menu .thoi-gian-tb {
  font-size: 11px;
  color: #888;
}
.thanh-menu .khong-co-tb {
  padding: 20px;
  text-align: center;
  color: #888;
  font-size: 13px;
  margin: 0;
}

.thanh-menu .so-tb {
  position: absolute; top: 0; right: 0;
  min-width: 16px; height: 16px; padding: 0 4px; box-sizing: border-box;
  background: #ff3b30; color: #fff; border-radius: 8px;
  font-size: 10px; font-weight: 700; line-height: 16px; text-align: center;
}
.thanh-menu .item-tb .tieu-de-tb { line-height: 1.35; }

/* ===== Menu con trong cột chức năng ===== */
.cot-chuc-nang .nhom-con { margin: 0; }

.cot-chuc-nang .nhom-con > summary.muc-cha {
  list-style: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 8px;
  color: inherit;
  font: inherit;
  user-select: none;
  transition: background .2s, color .2s;
}
.cot-chuc-nang .nhom-con > summary.muc-cha::-webkit-details-marker { display: none; }
.cot-chuc-nang .nhom-con > summary.muc-cha:hover { background: rgba(0, 0, 0, .05); }
.cot-chuc-nang .nhom-con > summary.muc-cha > svg:first-child {
  width: 20px; height: 20px; flex: 0 0 20px;
  fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
}
.cot-chuc-nang .nhom-con > summary.muc-cha > span { flex: 1; }
.cot-chuc-nang .nhom-con > summary.muc-cha.co-con-chon { color: #d71f18; font-weight: 600; }

/* Mũi tên xoay khi mở */
.cot-chuc-nang .mui-ten {
  width: 16px; height: 16px; flex: 0 0 16px;
  fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
  transition: transform .2s;
}
.cot-chuc-nang .nhom-con[open] > summary .mui-ten { transform: rotate(180deg); }

/* Danh sách con thụt vào, có đường kẻ dọc */
.cot-chuc-nang .ds-con {
  margin: 2px 0 6px 22px;
  padding-left: 12px;
  border-left: 2px solid #eee;
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.cot-chuc-nang .ds-con .muc-con {
  display: block;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: .9em;
  text-decoration: none;
  color: inherit;
  transition: background .2s, color .2s;
}
.cot-chuc-nang .ds-con .muc-con:hover { background: rgba(0, 0, 0, .05); }
.cot-chuc-nang .ds-con .muc-con.dang-chon {
  background: #fdecec;
  color: #d71f18;
  font-weight: 600;
}
</style>