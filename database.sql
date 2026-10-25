SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_connection=utf8mb4;

DROP DATABASE IF EXISTS `book_store`;
CREATE DATABASE `book_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `book_store`;

CREATE TABLE `nguoi_dung` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ho_ten` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `mat_khau_hash` VARCHAR(255) NOT NULL,
    `so_dien_thoai` VARCHAR(20) DEFAULT NULL,
    `vai_tro` ENUM('khach_hang', 'quan_tri') NOT NULL DEFAULT 'khach_hang',
    `trang_thai` ENUM('hoat_dong', 'khoa') NOT NULL DEFAULT 'hoat_dong',
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `dia_chi` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nguoi_dung_id` INT NOT NULL,
    `nguoi_nhan` VARCHAR(100) NOT NULL,
    `so_dien_thoai` VARCHAR(20) NOT NULL,
    `dia_chi` VARCHAR(255) NOT NULL,
    `mac_dinh` TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT `fk_dia_chi_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) 
        REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `the_loai` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten` VARCHAR(100) NOT NULL,
    `mo_ta` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nha_xuat_ban` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten` VARCHAR(150) NOT NULL,
    `dia_chi` VARCHAR(255) DEFAULT NULL,
    `lien_he` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tac_gia` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten` VARCHAR(150) NOT NULL,
    `tieu_su` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sach` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tieu_de` VARCHAR(255) NOT NULL,
    `gia` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `mo_ta` TEXT DEFAULT NULL,
    `anh_bia` VARCHAR(255) DEFAULT NULL,
    `so_trang` INT DEFAULT NULL,
    `nam_xuat_ban` INT DEFAULT NULL,
    `so_luong_ton` INT NOT NULL DEFAULT 0,
    `the_loai_id` INT DEFAULT NULL,
    `nxb_id` INT DEFAULT NULL,
    `dang_ban` TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT `fk_sach_the_loai` FOREIGN KEY (`the_loai_id`) 
        REFERENCES `the_loai` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_sach_nxb` FOREIGN KEY (`nxb_id`) 
        REFERENCES `nha_xuat_ban` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sach_tac_gia` (
    `sach_id` INT NOT NULL,
    `tac_gia_id` INT NOT NULL,
    PRIMARY KEY (`sach_id`, `tac_gia_id`),
    CONSTRAINT `fk_stg_sach` FOREIGN KEY (`sach_id`) 
        REFERENCES `sach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_stg_tac_gia` FOREIGN KEY (`tac_gia_id`) 
        REFERENCES `tac_gia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phieu_kho` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sach_id` INT NOT NULL,
    `nguoi_dung_id` INT NOT NULL,
    `loai` ENUM('nhap', 'xuat', 'dieu_chinh') NOT NULL,
    `so_luong` INT NOT NULL,
    `ghi_chu` VARCHAR(255) DEFAULT NULL,
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_phieu_kho_sach` FOREIGN KEY (`sach_id`) 
        REFERENCES `sach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_phieu_kho_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) 
        REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gio_hang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nguoi_dung_id` INT NOT NULL UNIQUE,
    `cap_nhat` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_gio_hang_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) 
        REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chi_tiet_gio_hang` (
    `gio_hang_id` INT NOT NULL,
    `sach_id` INT NOT NULL,
    `so_luong` INT NOT NULL DEFAULT 1,
    PRIMARY KEY (`gio_hang_id`, `sach_id`),
    CONSTRAINT `fk_ctgh_gio_hang` FOREIGN KEY (`gio_hang_id`) 
        REFERENCES `gio_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ctgh_sach` FOREIGN KEY (`sach_id`) 
        REFERENCES `sach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ma_giam_gia` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma` VARCHAR(50) NOT NULL UNIQUE,
    `loai` ENUM('phan_tram', 'tien_mat') NOT NULL,
    `gia_tri` DECIMAL(12, 2) NOT NULL,
    `don_toi_thieu` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `bat_dau` DATETIME NOT NULL,
    `ket_thuc` DATETIME NOT NULL,
    `so_luot_toi_da` INT NOT NULL DEFAULT 0,
    `da_dung` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `don_hang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nguoi_dung_id` INT NOT NULL,
    `ma_giam_gia_id` INT DEFAULT NULL,
    `nguoi_nhan` VARCHAR(100) NOT NULL,
    `dia_chi_giao` VARCHAR(255) NOT NULL,
    `tong_tien_hang` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `tien_giam` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `phi_van_chuyen` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `tong_thanh_toan` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `trang_thai` ENUM('cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'hoan_thanh', 'da_huy') 
        NOT NULL DEFAULT 'cho_xac_nhan',
    `ngay_dat` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) 
        REFERENCES `nguoi_dung` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_don_hang_ma_giam_gia` FOREIGN KEY (`ma_giam_gia_id`) 
        REFERENCES `ma_giam_gia` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chi_tiet_don_hang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `don_hang_id` INT NOT NULL,
    `sach_id` INT NOT NULL,
    `so_luong` INT NOT NULL,
    `don_gia` DECIMAL(12, 2) NOT NULL,
    CONSTRAINT `fk_ctdh_don_hang` FOREIGN KEY (`don_hang_id`) 
        REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_ctdh_sach` FOREIGN KEY (`sach_id`) 
        REFERENCES `sach` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `thanh_toan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `don_hang_id` INT NOT NULL,
    `phuong_thuc` VARCHAR(50) NOT NULL,
    `so_tien` DECIMAL(12, 2) NOT NULL,
    `trang_thai` ENUM('cho_thanh_toan', 'thanh_cong', 'that_bai') 
        NOT NULL DEFAULT 'cho_thanh_toan',
    `ma_giao_dich` VARCHAR(100) DEFAULT NULL,
    `ngay_thanh_toan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_thanh_toan_don_hang` FOREIGN KEY (`don_hang_id`) 
        REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lich_su_don_hang` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `don_hang_id` INT NOT NULL,
    `trang_thai` VARCHAR(50) NOT NULL,
    `ghi_chu` VARCHAR(255) DEFAULT NULL,
    `thoi_gian` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_lsdh_don_hang` FOREIGN KEY (`don_hang_id`) 
        REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `danh_gia` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nguoi_dung_id` INT NOT NULL,
    `sach_id` INT NOT NULL,
    `so_sao` TINYINT NOT NULL,
    `noi_dung` TEXT DEFAULT NULL,
    `trang_thai` ENUM('cho_duyet', 'hien_thi', 'an') 
        NOT NULL DEFAULT 'cho_duyet',
    `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `chk_danh_gia_so_sao` CHECK (`so_sao` >= 1 AND `so_sao` <= 5),
    CONSTRAINT `uk_danh_gia_user_sach` UNIQUE (`nguoi_dung_id`, `sach_id`),
    CONSTRAINT `fk_danh_gia_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) 
        REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_danh_gia_sach` FOREIGN KEY (`sach_id`) 
        REFERENCES `sach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- DỮ LIỆU MẪU (SEED DATA ĐÃ ĐỒNG BỘ UTF-8 ĐẦY ĐỦ)
-- =====================================================================

-- 1. Người dùng
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau_hash`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Quản Trị Viên', 'admin@bookstore.vn', '$2y$10$e8w8G18Q6Q47jJ8lB9y6re7tU27kUuTqYf4z9jZ39lHhN/m6sO1yW', '0901234567', 'quan_tri', 'hoat_dong', NOW()),
(2, 'Nguyễn Văn An', 'nguyenvanan@gmail.com', '$2y$10$e8w8G18Q6Q47jJ8lB9y6re7tU27kUuTqYf4z9jZ39lHhN/m6sO1yW', '0912345678', 'khach_hang', 'hoat_dong', NOW()),
(3, 'Trần Thị Bình', 'tranthibinh@gmail.com', '$2y$10$e8w8G18Q6Q47jJ8lB9y6re7tU27kUuTqYf4z9jZ39lHhN/m6sO1yW', '0987654321', 'khach_hang', 'hoat_dong', NOW());

-- 2. Địa chỉ nhận hàng
INSERT INTO `dia_chi` (`id`, `nguoi_dung_id`, `nguoi_nhan`, `so_dien_thoai`, `dia_chi`, `mac_dinh`) VALUES
(1, 2, 'Nguyễn Văn An', '0912345678', '123 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', 1),
(2, 2, 'Nguyễn Văn An (Công ty)', '0912345678', '456 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh', 0),
(3, 3, 'Trần Thị Bình', '0987654321', '789 Đường Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội', 1);

-- 3. Thể loại sách
INSERT INTO `the_loai` (`id`, `ten`, `mo_ta`) VALUES
(1, 'Văn học & Tiểu thuyết', 'Các tác phẩm văn học kinh điển, tiểu thuyết hiện đại trong và ngoài nước'),
(2, 'Kinh tế & Quản trị', 'Sách về kinh doanh, khởi nghiệp, tài chính, đầu tư và nghệ thuật quản trị'),
(3, 'Kỹ năng sống', 'Phát triển bản thân, tư duy tích cực, kỹ năng giao tiếp và lãnh đạo'),
(4, 'Công nghệ thông tin', 'Sách lập trình, kiến trúc phần mềm, an toàn thông tin, trí tuệ nhân tạo'),
(5, 'Thiếu nhi', 'Truyện tranh, sách khám phá thế giới, truyện cổ tích cho lứa tuổi thiếu nhi');

-- 4. Nhà xuất bản
INSERT INTO `nha_xuat_ban` (`id`, `ten`, `dia_chi`, `lien_he`) VALUES
(1, 'NXB Trẻ', '161B Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, TP.HCM', 'hopthu@nxbtre.com.vn'),
(2, 'NXB Kim Đồng', '55 Quang Trung, Nguyễn Du, Hai Bà Trưng, Hà Nội', 'cskh_online@nxbkimdong.com.vn'),
(3, 'NXB Tổng Hợp TP.HCM', '62 Nguyễn Thị Minh Khai, Đa Kao, Quận 1, TP.HCM', 'tonghop@nxbhcm.com.vn'),
(4, 'NXB Thông Tin và Truyền Thông', '115 Trần Duy Hưng, Cầu Giấy, Hà Nội', 'kinhdoanh@nxbthongtin.vn');

-- 5. Tác giả
INSERT INTO `tac_gia` (`id`, `ten`, `tieu_su`) VALUES
(1, 'Nguyễn Nhật Ánh', 'Nhà văn Việt Nam nổi tiếng với các tác phẩm viết về tuổi thơ, học trò được bạn đọc mọi lứa tuổi yêu thích.'),
(2, 'Haruki Murakami', 'Nhà văn, dịch giả nổi tiếng người Nhật Bản với phong cách siêu thực và hiện sinh.'),
(3, 'Dale Carnegie', 'Tác giả và diễn giả người Mỹ, người sáng lập các khóa học nổi tiếng về đối nhân xử thế.'),
(4, 'Robert C. Martin (Uncle Bob)', 'Chuyên gia công nghệ phần mềm hàng đầu, tác giả cuốn Clean Code nổi tiếng thế giới.'),
(5, 'Paulo Coelho', 'Tiểu thuyết gia người Brasil, tác giả của kiệt tác Nhà Giả Kim bán chạy hàng chục triệu bản.');

-- 6. Sách
INSERT INTO `sach` (`id`, `tieu_de`, `gia`, `mo_ta`, `anh_bia`, `so_trang`, `nam_xuat_ban`, `so_luong_ton`, `the_loai_id`, `nxb_id`, `dang_ban`) VALUES
(1, 'Cho Tôi Xin Một Vé Đi Tuổi Thơ', 85000.00, 'Tác phẩm đưa độc giả quay về những ký ức tuổi thơ trong sáng, hồn nhiên nhưng giàu triết lý nhân sinh.', 've_di_tuoi_tho.jpg', 212, 2021, 50, 1, 1, 1),
(2, 'Mắt Biếc', 110000.00, 'Câu chuyện tình đẹp nhưng đượm buồn của Ngạn và Hà Lan dưới bóng ngôi làng Đo Đo mộng mơ.', 'mat_biec.jpg', 300, 2022, 45, 1, 1, 1),
(3, 'Rừng Na Uy', 145000.00, 'Bức tranh nội tâm sâu sắc của những thanh niên Nhật Bản thập niên 60 giữa cô đơn và khát khao kết nối.', 'rung_na_uy.jpg', 560, 2020, 30, 1, 3, 1),
(4, 'Đắc Nhân Tâm', 98000.00, 'Cuốn sách nghệ thuật thu phục lòng người kinh điển nhất mọi thời đại.', 'dac_nhan_tam.jpg', 320, 2023, 120, 3, 1, 1),
(5, 'Clean Code: A Handbook of Agile Software Craftsmanship', 350000.00, 'Cẩm nang toàn diện về viết mã sạch, tối ưu hóa kiến trúc mã nguồn cho lập trình viên chuyên nghiệp.', 'clean_code.jpg', 464, 2019, 25, 4, 4, 1),
(6, 'Nhà Giả Kim', 79000.00, 'Hành trình vượt qua sa mạc đi tìm kho báu và lắng nghe tiếng gọi trái tim của chàng chăn cừu Santiago.', 'nha_gia_kim.jpg', 228, 2022, 80, 1, 1, 1);

-- 7. Sách - Tác giả
INSERT INTO `sach_tac_gia` (`sach_id`, `tac_gia_id`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5);

-- 8. Phiếu kho
INSERT INTO `phieu_kho` (`id`, `sach_id`, `nguoi_dung_id`, `loai`, `so_luong`, `ghi_chu`, `ngay_tao`) VALUES
(1, 1, 1, 'nhap', 50, 'Nhập kho lô đầu kỳ', NOW()),
(2, 2, 1, 'nhap', 50, 'Nhập kho lô đầu kỳ', NOW()),
(3, 3, 1, 'nhap', 30, 'Nhập kho định kỳ từ NXB Tổng Hợp', NOW()),
(4, 4, 1, 'nhap', 120, 'Nhập tái bản Đắc Nhân Tâm', NOW()),
(5, 5, 1, 'nhap', 25, 'Nhập sách nhập khẩu công nghệ Clean Code', NOW()),
(6, 6, 1, 'nhap', 80, 'Nhập kho Nhà Giả Kim', NOW());

-- 9. Giỏ hàng
INSERT INTO `gio_hang` (`id`, `nguoi_dung_id`, `cap_nhat`) VALUES
(1, 2, NOW()),
(2, 3, NOW());

-- 10. Chi tiết giỏ hàng
INSERT INTO `chi_tiet_gio_hang` (`gio_hang_id`, `sach_id`, `so_luong`) VALUES
(1, 4, 1),
(1, 6, 2),
(2, 1, 1);

-- 11. Mã giảm giá
INSERT INTO `ma_giam_gia` (`id`, `ma`, `loai`, `gia_tri`, `don_toi_thieu`, `bat_dau`, `ket_thuc`, `so_luot_toi_da`, `da_dung`) VALUES
(1, 'WELCOME2026', 'phan_tram', 10.00, 150000.00, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 100, 1),
(2, 'GIAM30K', 'tien_mat', 30000.00, 200000.00, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 50, 0),
(3, 'FREESHIP', 'tien_mat', 25000.00, 100000.00, '2026-01-01 00:00:00', '2026-12-31 23:59:59', 200, 0);

-- 12. Đơn hàng
INSERT INTO `don_hang` (`id`, `nguoi_dung_id`, `ma_giam_gia_id`, `nguoi_nhan`, `dia_chi_giao`, `tong_tien_hang`, `tien_giam`, `phi_van_chuyen`, `tong_thanh_toan`, `trang_thai`, `ngay_dat`) VALUES
(1, 2, 1, 'Nguyễn Văn An', '123 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh', 195000.00, 19500.00, 25000.00, 200500.00, 'hoan_thanh', '2026-10-01 10:30:00'),
(2, 3, NULL, 'Trần Thị Bình', '789 Đường Cầu Giấy, Phường Dịch Vọng, Quận Cầu Giấy, Hà Nội', 350000.00, 0.00, 30000.00, 380000.00, 'dang_giao', '2026-10-06 14:15:00');

-- 13. Chi tiết đơn hàng
INSERT INTO `chi_tiet_don_hang` (`id`, `don_hang_id`, `sach_id`, `so_luong`, `don_gia`) VALUES
(1, 1, 1, 1, 85000.00),
(2, 1, 2, 1, 110000.00),
(3, 2, 5, 1, 350000.00);

-- 14. Thanh toán
INSERT INTO `thanh_toan` (`id`, `don_hang_id`, `phuong_thuc`, `so_tien`, `trang_thai`, `ma_giao_dich`, `ngay_thanh_toan`) VALUES
(1, 1, 'VNPAY', 200500.00, 'thanh_cong', 'VNPAY20261001123456', '2026-10-01 10:32:00'),
(2, 2, 'COD', 380000.00, 'cho_thanh_toan', NULL, '2026-10-06 14:15:00');

-- 15. Lịch sử đơn hàng
INSERT INTO `lich_su_don_hang` (`id`, `don_hang_id`, `trang_thai`, `ghi_chu`, `thoi_gian`) VALUES
(1, 1, 'cho_xac_nhan', 'Khách hàng hoàn tất tạo đơn qua website', '2026-10-01 10:30:00'),
(2, 1, 'da_xac_nhan', 'Đơn hàng được hệ thống xác nhận thanh toán thành công', '2026-10-01 10:35:00'),
(3, 1, 'dang_giao', 'Đơn hàng đã bàn giao cho đơn vị vận chuyển', '2026-10-01 15:00:00'),
(4, 1, 'hoan_thanh', 'Giao hàng thành công đến khách hàng', '2026-10-03 09:20:00'),
(5, 2, 'cho_xac_nhan', 'Khách hàng chọn hình thức COD', '2026-10-06 14:15:00'),
(6, 2, 'da_xac_nhan', 'Nhân viên kho xác nhận sẵn sàng đóng gói', '2026-10-06 16:00:00'),
(7, 2, 'dang_giao', 'Đang vận chuyển từ kho Hà Nội', '2026-10-07 08:30:00');

-- 16. Đánh giá
INSERT INTO `danh_gia` (`id`, `nguoi_dung_id`, `sach_id`, `so_sao`, `noi_dung`, `trang_thai`, `ngay_tao`) VALUES
(1, 2, 1, 5, 'Sách bìa đẹp, đóng gói cẩn thận, nội dung rất ý nghĩa và xúc động!', 'hien_thi', '2026-10-04 14:00:00'),
(2, 2, 2, 4, 'Truyện rất buồn nhưng đậm tính nhân văn, giao hàng nhanh.', 'hien_thi', '2026-10-04 14:05:00');
