-- Mật khẩu mẫu của mọi tài khoản: Admin@123

CREATE DATABASE vneid_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vneid_db;

-- 1. Người dùng & xác thực -------------------------------------------------
CREATE TABLE vai_tro (
    ma_vai_tro   INT PRIMARY KEY AUTO_INCREMENT,          -- 1 = Công dân, 2 = Cán bộ xử lý, 3 = Quản trị viên
    ten_vai_tro  VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE nguoi_dung (
    ma_nguoi_dung  INT PRIMARY KEY AUTO_INCREMENT,
    ma_vai_tro     INT NOT NULL DEFAULT 1,
    so_dinh_danh   CHAR(12) NOT NULL UNIQUE,
    ho_ten         VARCHAR(100) NOT NULL,
    so_dien_thoai  VARCHAR(15) NOT NULL UNIQUE,
    email          VARCHAR(100) UNIQUE,
    mat_khau       VARCHAR(255) NOT NULL,                -- đã băm bằng password_hash()
    muc_dinh_danh  TINYINT NOT NULL DEFAULT 1,           -- 1 hoặc 2
    trang_thai     ENUM('hoat_dong','bi_khoa','cho_kich_hoat') NOT NULL DEFAULT 'cho_kich_hoat',
    so_lan_sai     TINYINT NOT NULL DEFAULT 0,           -- đăng nhập sai liên tiếp
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_vai_tro) REFERENCES vai_tro(ma_vai_tro)
);

CREATE TABLE ma_otp (
    ma_otp_id      INT PRIMARY KEY AUTO_INCREMENT,
    so_dien_thoai  VARCHAR(15) NOT NULL,
    ma_otp         VARCHAR(6) NOT NULL,
    het_han        DATETIME NOT NULL,
    da_dung        TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE nhat_ky_dang_nhap (
    ma_nhat_ky     INT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung  INT,
    hanh_dong      ENUM('thanh_cong','that_bai','dang_xuat') NOT NULL,
    dia_chi_ip     VARCHAR(45),
    thoi_gian      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE SET NULL
);

-- 2. Căn cước công dân (đủ các trường in trên thẻ) -------------------------
-- Họ tên lấy từ nguoi_dung.ho_ten. Giấy tờ CCCD hiển thị trong ví lấy từ bảng này.
CREATE TABLE the_can_cuoc (
    ma_the              INT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung       INT NOT NULL,
    so_the              CHAR(12) NOT NULL UNIQUE,
    ngay_sinh           DATE NOT NULL,
    gioi_tinh           ENUM('Nam','Nữ','Khác') NOT NULL,
    quoc_tich           VARCHAR(50) NOT NULL DEFAULT 'Việt Nam',
    dan_toc             VARCHAR(50),
    ton_giao            VARCHAR(50),
    que_quan            VARCHAR(255),
    noi_thuong_tru      VARCHAR(255),
    tinh_thanh          VARCHAR(100),                     -- dùng cho thống kê theo khu vực
    dac_diem_nhan_dang  VARCHAR(255),                     -- vd: sẹo chấm cách 2cm dưới đuôi mắt phải
    anh_chan_dung       VARCHAR(255),                     -- đường dẫn ảnh
    ngay_cap            DATE NOT NULL,
    ngay_het_han        DATE NOT NULL,
    noi_cap             VARCHAR(150),
    trang_thai          ENUM('con_hieu_luc','het_han','bao_mat','thu_hoi') NOT NULL DEFAULT 'con_hieu_luc',
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE
);

-- 3. Ví giấy tờ điện tử (các giấy tờ khác ngoài CCCD) ----------------------
-- Mỗi loại giấy tờ có các trường riêng; tên trường liệt kê ở cac_truong (JSON)
CREATE TABLE loai_giay_to (
    ma_loai     INT PRIMARY KEY AUTO_INCREMENT,
    ten_loai    VARCHAR(100) NOT NULL UNIQUE,
    cac_truong  JSON                                      -- vd: ["hang"] cho GPLX
);

-- Thông tin chung là cột riêng, thông tin riêng từng loại lưu trong thong_tin (JSON)
CREATE TABLE giay_to_vi (
    ma_giay_to     INT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung  INT NOT NULL,
    ma_loai        INT NOT NULL,
    so_giay_to     VARCHAR(30) NOT NULL,
    ten_chu_so_huu VARCHAR(100) NOT NULL,
    ngay_cap       DATE,
    ngay_het_han   DATE,
    noi_cap        VARCHAR(150),
    thong_tin      JSON,
    trang_thai     ENUM('cho_xac_minh','da_xac_minh','bi_tu_choi') NOT NULL DEFAULT 'cho_xac_minh',
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE,
    FOREIGN KEY (ma_loai) REFERENCES loai_giay_to(ma_loai),
    UNIQUE (ma_nguoi_dung, ma_loai, so_giay_to)
);

-- 4. Thông báo -------------------------------------------------------------
CREATE TABLE thong_bao (
    ma_thong_bao   INT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_nhan  INT NOT NULL,
    loai           ENUM('he_thong','dich_vu_cong','bao_mat') NOT NULL DEFAULT 'he_thong',
    tieu_de        VARCHAR(150) NOT NULL,
    noi_dung       TEXT,
    da_doc         TINYINT(1) NOT NULL DEFAULT 0,
    ngay_tao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_nhan) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE
);

-- 5. Dịch vụ công ----------------------------------------------------------
CREATE TABLE dich_vu (
    ma_dich_vu     INT PRIMARY KEY AUTO_INCREMENT,
    ten_dich_vu    VARCHAR(150) NOT NULL UNIQUE,
    muc_toi_thieu  TINYINT NOT NULL DEFAULT 1             -- mức định danh tối thiểu
);

CREATE TABLE ho_so_dich_vu (
    ma_ho_so       INT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung  INT NOT NULL,
    ma_dich_vu     INT NOT NULL,
    noi_dung       TEXT,                                  -- nội dung biểu mẫu
    tep_dinh_kem   VARCHAR(255),                          -- đường dẫn tệp
    trang_thai     ENUM('da_nop','dang_xu_ly','can_bo_sung','da_duyet','tu_choi') NOT NULL DEFAULT 'da_nop',
    ghi_chu        VARCHAR(500),                          -- ghi chú của cán bộ
    ma_can_bo      INT,                                   -- cán bộ xử lý
    ngay_nop       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE,
    FOREIGN KEY (ma_dich_vu) REFERENCES dich_vu(ma_dich_vu),
    FOREIGN KEY (ma_can_bo) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE SET NULL
);

-- 6. Báo cáo & thống kê: dùng truy vấn GROUP BY trực tiếp trong PHP, ví dụ:
--   SELECT trang_thai, COUNT(*) FROM ho_so_dich_vu GROUP BY trang_thai;

-- DỮ LIỆU MẪU ---------------------------------------------------------------
INSERT INTO vai_tro (ten_vai_tro) VALUES
('Công dân'),('Cán bộ xử lý'),('Quản trị viên');

INSERT INTO nguoi_dung (ma_vai_tro, so_dinh_danh, ho_ten, so_dien_thoai, email, mat_khau, muc_dinh_danh, trang_thai) VALUES
(3,'079000000001','Quản Trị Hệ Thống','0900000001','admin@vneid.local','$2y$10$1GJQGNQjNLVpeyzDV8xtf.QRECCJT6VHf9CBihV4u0q.kTw6hQFFO',2,'hoat_dong'),
(2,'079000000002','Trần Văn Cán Bộ','0900000002','canbo@vneid.local','$2y$10$1GJQGNQjNLVpeyzDV8xtf.QRECCJT6VHf9CBihV4u0q.kTw6hQFFO',2,'hoat_dong'),
(1,'056200000001','Nguyễn Văn An','0911000001','an@vneid.local','$2y$10$1GJQGNQjNLVpeyzDV8xtf.QRECCJT6VHf9CBihV4u0q.kTw6hQFFO',2,'hoat_dong'),
(1,'056200000002','Lê Thị Bình','0911000002','binh@vneid.local','$2y$10$1GJQGNQjNLVpeyzDV8xtf.QRECCJT6VHf9CBihV4u0q.kTw6hQFFO',1,'hoat_dong'),
(1,'056200000003','Phạm Minh Châu','0911000003','chau@vneid.local','$2y$10$1GJQGNQjNLVpeyzDV8xtf.QRECCJT6VHf9CBihV4u0q.kTw6hQFFO',1,'bi_khoa');

INSERT INTO the_can_cuoc (ma_nguoi_dung, so_the, ngay_sinh, gioi_tinh, quoc_tich, dan_toc, ton_giao, que_quan, noi_thuong_tru, tinh_thanh, dac_diem_nhan_dang, anh_chan_dung, ngay_cap, ngay_het_han, noi_cap, trang_thai) VALUES
(3,'056200000001','2003-05-12','Nam','Việt Nam','Kinh','Không','Vạn Ninh, Khánh Hòa','12 Trần Phú, Nha Trang, Khánh Hòa','Khánh Hòa','Nốt ruồi cách 2cm dưới đuôi mắt trái','uploads/anh/an.jpg','2021-06-01','2029-05-12','Cục Cảnh sát QLHC về TTXH','con_hieu_luc'),
(4,'056200000002','2002-11-03','Nữ','Việt Nam','Kinh','Phật giáo','Ninh Hòa, Khánh Hòa','45 Lê Hồng Phong, Nha Trang, Khánh Hòa','Khánh Hòa','Sẹo chấm cách 1cm trên đuôi lông mày phải','uploads/anh/binh.jpg','2021-07-15','2027-11-03','Cục Cảnh sát QLHC về TTXH','con_hieu_luc'),
(5,'056200000003','2001-02-20','Nữ','Việt Nam','Kinh','Không','Tuy Hòa, Phú Yên','8 Nguyễn Huệ, Tuy Hòa, Phú Yên','Phú Yên','Nốt ruồi cách 3cm dưới mép phải','uploads/anh/chau.jpg','2020-03-10','2026-02-20','Cục Cảnh sát QLHC về TTXH','het_han');

INSERT INTO loai_giay_to (ten_loai, cac_truong) VALUES
('Giấy phép lái xe','["hang"]'),
('Thẻ bảo hiểm y tế','["noi_dang_ky_kcb","muc_huong"]'),
('Giấy đăng ký xe','["bien_so","nhan_hieu","so_khung"]'),
('Hộ chiếu','["loai_ho_chieu","quoc_tich"]');

INSERT INTO giay_to_vi (ma_nguoi_dung, ma_loai, so_giay_to, ten_chu_so_huu, ngay_cap, ngay_het_han, noi_cap, thong_tin, trang_thai) VALUES
(3,1,'790012345678','Nguyễn Văn An','2022-01-10','2032-01-10','Sở GTVT Khánh Hòa',
    JSON_OBJECT('hang','B2'),'da_xac_minh'),
(3,2,'HS4790012345','Nguyễn Văn An','2025-01-01','2026-12-31','BHXH Khánh Hòa',
    JSON_OBJECT('noi_dang_ky_kcb','BV Đa khoa Khánh Hòa','muc_huong','80%'),'cho_xac_minh'),
(4,3,'ĐK0012345','Lê Thị Bình','2023-04-02',NULL,'Công an TP Nha Trang',
    JSON_OBJECT('bien_so','79A-123.45','nhan_hieu','Honda Vision','so_khung','RLHJF1234PY000001'),'cho_xac_minh');

INSERT INTO dich_vu (ten_dich_vu, muc_toi_thieu) VALUES
('Khai báo tạm trú',2),('Đăng ký phương tiện',2),('Xác nhận cư trú',2),('Cấp lại giấy tờ',1);

INSERT INTO ho_so_dich_vu (ma_nguoi_dung, ma_dich_vu, noi_dung, trang_thai, ma_can_bo) VALUES
(3,1,'Tạm trú tại 12 Trần Phú, Nha Trang','da_duyet',2),
(3,3,'Xác nhận cư trú để làm thủ tục học tập','dang_xu_ly',2),
(4,4,'Cấp lại thẻ BHYT bị mất','can_bo_sung',2);

INSERT INTO thong_bao (ma_nguoi_nhan, loai, tieu_de, noi_dung, da_doc) VALUES
(3,'he_thong','Chào mừng','Tài khoản của bạn đã được kích hoạt.',1),
(4,'he_thong','Chào mừng','Tài khoản của bạn đã được kích hoạt.',0),
(4,'dich_vu_cong','Hồ sơ cần bổ sung','Vui lòng bổ sung đơn trình báo mất.',0);

INSERT INTO nhat_ky_dang_nhap (ma_nguoi_dung, hanh_dong, dia_chi_ip) VALUES
(3,'thanh_cong','127.0.0.1'),(4,'that_bai','127.0.0.1'),(4,'thanh_cong','127.0.0.1');