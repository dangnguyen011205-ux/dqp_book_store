# TÀI LIỆU THIẾT KẾ GIAO DIỆN HỆ THỐNG - DQP BOOK STORE
> **Design System, UI/UX Specifications & Reference Mapping Guide**  
> **Dự án:** Website Bán Sách Trực Tuyến & Cổng Quản Trị BookStore (PHP MVC Nâng Cao)  
> **Phiên bản:** 2.4 - Modern Classic Edition  
> **Ngày lập:** 10/10/2026  

---

## 1. TỔNG QUAN & NGUỒN THAM CHIẾU GIAO DIỆN

Thư mục giao diện tham chiếu chính thức đã được tích hợp trong dự án tại:
```
c:\xampp\htdocs\PHP_nang_cao\book_store\stitch_modern_bookstore_platform_ui_ux\
```

### 1.1. Nguyên Tắc Quản Lý Thư Mục Tham Chiếu
- **Quy tắc Read-Only:** Thư mục `stitch_modern_bookstore_platform_ui_ux` là **tài nguyên mẫu tham chiếu nguyên bản** (Single Source of Truth). Nghiêm cấm mọi hành vi chỉnh sửa, di chuyển, đổi tên hoặc xóa các file bên trong thư mục này.
- **Mục đích:** Sử dụng làm tài liệu đối chiếu trực tiếp về mặt thị giác (Visual UI), cấu trúc DOM (HTML semantics), bảng định kiểu (Tailwind/CSS tokens), hành vi tương tác (JavaScript logic) và ánh xạ dữ liệu (Data Schema).
- **Cấu trúc lưu trữ:** Gồm 1 folder tài liệu Design Token (`athenaeum_modern`) và 13 folder trang chuyên biệt, mỗi folder chứa 1 file `code.html` và 1 file ảnh chụp màn hình `screen.png`.

---

## 2. BẢNG TỔNG HỢP CÁC FILE NGUỒN THAM CHIẾU

Dưới đây là danh sách toàn bộ các file nguồn tham chiếu cùng đường dẫn tuyệt đối phục vụ việc đối chiếu khi triển khai:

| STT | Tên Phân Hệ & Màn Hình | Folder Nguồn Tham Chiếu | File Mã Nguồn (HTML) | File Ảnh Giao Diện (PNG) |
|:---:|:---|:---|:---|:---|
| 0 | **Design Tokens & System Spec** | `athenaeum_modern` | `athenaeum_modern/DESIGN.md` | *(Tài liệu đặc tả tokens)* |
| 1 | **Trang Chủ BookStore** | `trang_ch_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/trang_ch_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/trang_ch_bookstore/screen.png) |
| 2 | **Tìm Kiếm & Bộ Lọc Sách** | `t_m_ki_m_l_c_s_ch_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_m_ki_m_l_c_s_ch_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_m_ki_m_l_c_s_ch_bookstore/screen.png) |
| 3 | **Chi Tiết Sách (Bản Tiêu Chuẩn)** | `chi_ti_t_s_ch_t_m_l_h_c_v_ti_n` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/chi_ti_t_s_ch_t_m_l_h_c_v_ti_n/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/chi_ti_t_s_ch_t_m_l_h_c_v_ti_n/screen.png) |
| 4 | **Chi Tiết Sách (Đồng Bộ CSDL)** | `chi_ti_t_s_ch_t_m_l_h_c_v_ti_n_csdl_sach_tac_gia_nha_xuat_ban_danh_gia` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/chi_ti_t_s_ch_t_m_l_h_c_v_ti_n_csdl_sach_tac_gia_nha_xuat_ban_danh_gia/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/chi_ti_t_s_ch_t_m_l_h_c_v_ti_n_csdl_sach_tac_gia_nha_xuat_ban_danh_gia/screen.png) |
| 5 | **Giỏ Hàng & Tóm Tắt Đơn** | `gi_h_ng_thanh_to_n_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/gi_h_ng_thanh_to_n_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/gi_h_ng_thanh_to_n_bookstore/screen.png) |
| 6 | **Thanh Toán & Xác Nhận Đơn** | `thanh_to_n_x_c_nh_n_n_h_ng_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/thanh_to_n_x_c_nh_n_n_h_ng_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/thanh_to_n_x_c_nh_n_n_h_ng_bookstore/screen.png) |
| 7 | **Quản Lý Đơn Hàng (Độc Giả)** | `qu_n_l_n_h_ng_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_n_h_ng_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_n_h_ng_bookstore/screen.png) |
| 8 | **Theo Dõi Vận Đơn & Lịch Sử** | `qu_n_l_theo_d_i_n_h_ng_bookstore` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_theo_d_i_n_h_ng_bookstore/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_theo_d_i_n_h_ng_bookstore/screen.png) |
| 9 | **Admin Dashboard (Phương Án 1)** | `t_ng_quan_qu_n_tr_bookstore_admin_1` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_ng_quan_qu_n_tr_bookstore_admin_1/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_ng_quan_qu_n_tr_bookstore_admin_1/screen.png) |
| 10 | **Admin Dashboard (Phương Án 2)** | `t_ng_quan_qu_n_tr_bookstore_admin_2` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_ng_quan_qu_n_tr_bookstore_admin_2/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/t_ng_quan_qu_n_tr_bookstore_admin_2/screen.png) |
| 11 | **Admin Quản Lý Sách & Kho** | `qu_n_l_s_ch_qu_n_l_kho_bookstore_admin` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_s_ch_qu_n_l_kho_bookstore_admin/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_s_ch_qu_n_l_kho_bookstore_admin/screen.png) |
| 12 | **Admin Quản Lý & Xử Lý Đơn** | `qu_n_l_n_h_ng_bookstore_admin` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_n_h_ng_bookstore_admin/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/qu_n_l_n_h_ng_bookstore_admin/screen.png) |
| 13 | **Admin Duyệt Đánh Giá Khách** | `duy_t_nh_gi_kh_ch_h_ng_bookstore_admin` | [`code.html`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/duy_t_nh_gi_kh_ch_h_ng_bookstore_admin/code.html) | [`screen.png`](file:///c:/xampp/htdocs/PHP_nang_cao/book_store/stitch_modern_bookstore_platform_ui_ux/duy_t_nh_gi_kh_ch_h_ng_bookstore_admin/screen.png) |

---

## 3. DESIGN SYSTEM & VISUAL IDENTITY ("ATHENAEUM MODERN")

Phong cách thiết kế **Athenaeum Modern (Thư Viện Tinh Hoa Cổ Điển Hiện Đại)** kết hợp giữa vẻ đẹp trang trọng, học thuật của một thư viện viện hàn lâm với sự mượt mà, tiện lợi của thương mại điện tử đương đại.

### 3.1. Tính Cách Thương Hiệu (Brand Personality)
- **Học thuật & Tuyển chọn (Curated & Intellectual):** Ưu tiên tính dễ đọc của văn bản (editorial typography), không gian thở thoáng đãng, loại bỏ các chi tiết thừa thãi quảng cáo gây rối mắt.
- **Đáng tin cậy & Bền vững (Trustworthy & Enduring):** Sử dụng gam màu Xanh Hải Quân Đậm (Primary Navy) kết hợp đường nét kẻ viền 1px tinh xảo, tạo cảm giác học thức và uy tín.
- **Thân thiện với thị giác (Tactile & Warmth):** Nền giấy sách hơi ngả ấm (`#FAF9F6` / `#F7F9FB`) giúp độc giả đọc sách và duyệt danh mục nhiều giờ không bị mỏi mắt.

---

### 3.2. Bảng Màu Chi Tiết (Color Palette Tokens)

Hệ thống màu tuân theo chuẩn Material Design Token kết hợp cấu hình Tailwind mở rộng:

#### A. Màu Thương Hiệu Chính (Brand Core Colors)
| Tên Biến Token | Mã HEX | Mô Tả & Vị Trí Áp Dụng |
|:---|:---:|:---|
| `primary` | `#001428` | Xanh hải quân thẫm nhất, chữ tiêu đề chính, nút nhấn chủ đạo |
| `primary-container` | `#0F2942` | Xanh navy đại dương, nền header phụ, hero banner, thanh điều hướng |
| `on-primary` | `#FFFFFF` | Màu chữ trên nền primary |
| `on-primary-container` | `#7991AF` | Màu chữ phụ, icon mờ trên nền xanh navy |
| `primary-fixed` | `#D1E4FF` | Xanh phấn nhạt, dùng cho tag chip và badge nổi bật |
| `on-primary-fixed` | `#011D35` | Chữ tương phản cao trên nền primary-fixed |
| `secondary` | `#006A61` | Xanh mòng két (Deep Teal), màu điểm nhấn thương hiệu, action nút "Mua ngay" |
| `secondary-container` | `#86F2E4` | Xanh ngọc biển nhạt, dùng làm container điểm nhấn, highlight |
| `on-secondary-container`| `#006F66` | Màu chữ trên nền secondary container |
| `secondary-fixed` | `#89F5E7` | Nền nhãn nổi bật, thanh tiến trình |
| `tertiary` | `#1F1000` | Nâu đất hổ phách, dùng cho yếu tố cổ điển |
| `tertiary-fixed` | `#FFDDB8` | Nền cam hổ phách nhạt (Amber), dùng cho sao đánh giá, deal hot |
| `on-tertiary-fixed` | `#2A1700` | Chữ tương phản trên nền tertiary-fixed |

#### B. Hệ Thống Mặt Nền (Surface & Background)
| Tên Biến Token | Mã HEX | Vị Trí Áp Dụng Thực Tế |
|:---|:---:|:---|
| `background` | `#F7F9FB` | Nền tổng thể toàn bộ trang web (Canvas ngả tông giấy sách) |
| `surface-container-lowest` | `#FFFFFF` | Mặt thẻ sản phẩm (Book Card), bảng giỏ hàng, modal popup, dropdown |
| `surface-container-low` | `#F2F4F6` | Nền thanh phụ lục, sidebar lọc sách, ô nhập dữ liệu input |
| `surface-container` | `#ECEEF0` | Nền phân cách khối, ô chứa icon |
| `surface-container-high` | `#E6E8EA` | Trạng thái hover của nút bấm phụ, viền ngăn cách |
| `surface-container-highest` | `#E0E3E5` | Nền tag chọn |
| `outline` | `#74777E` | Đường viền thành phần phụ |
| `outline-variant` | `#C3C6CE` | Đường kẻ hairline 1px bảng biểu, divider ngăn cách mục |

#### C. Bảng Màu Trạng Thái Nghiệp Vụ (Semantic Status Badges)
Được chuẩn hóa khớp chính xác với CSDL (`don_hang.trang_thai`, `danh_gia.trang_thai`, `phieu_kho.loai`):
| Trạng Thái Nghiệp Vụ | Màu Nền Badge | Màu Chữ Badge | Icon Kèm Theo | Mã Trạng Thái CSDL |
|:---|:---:|:---:|:---:|:---|
| **Chờ xác nhận / Chờ duyệt** | `#FEF3C7` (Amber-100) | `#B45309` (Amber-700) | `pending_actions` | `cho_xac_nhan` / `cho_duyet` |
| **Đã xác nhận / Đang chuẩn bị**| `#E2E8F0` (Slate-200) | `#334155` (Slate-700) | `inventory` | `da_xac_nhan` |
| **Đang vận chuyển** | `#E0F2FE` (Sky-100) | `#0369A1` (Sky-700) | `local_shipping` | `dang_giao` |
| **Hoàn thành / Hiển thị** | `#D1FAE5` (Emerald-100) | `#047857` (Emerald-700)| `verified` / `check_circle` | `hoan_thanh` / `hien_thi` |
| **Đã hủy / Bị ẩn / Cảnh báo lỗi**| `#FFDAD6` (Rose-100) | `#BA1A1A` (Red-700) | `cancel` / `warning` | `da_huy` / `an` |
| **Đánh giá sao / Flash Sale** | `#FFFBEB` | `#F59E0B` (Amber Gold)| `star` (FILL 1) | Điểm số `so_sao` |

---

### 3.3. Typography & Hệ Thống Phông Chữ

#### Phông chữ chủ đạo
- **Tên phông:** `Plus Jakarta Sans` (Google Fonts)
- **Đường dẫn nhúng:**
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  ```
- **Hệ thống Icon:** Google `Material Symbols Outlined`
  ```html
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  ```

#### Bảng Phân Cấp Cỡ Chữ (Type Scale)
| Token | Cỡ Chữ / Line-Height | Độ Dày (Weight) | Khoảng Cách Chữ | Ứng Dụng |
|:---|:---:|:---:|:---:|:---|
| `display` | 44px / 52px | 700 (Bold) | -0.02em | Tiêu đề lớn tại Hero Banner |
| `display-mobile` | 32px / 40px | 700 (Bold) | -0.01em | Hero Title trên điện thoại |
| `headline-lg` | 32px / 40px | 700 (Bold) | -0.015em | Tiêu đề H1 trang con, H1 Admin Dashboard |
| `headline-md` | 24px / 32px | 600 (SemiBold) | -0.01em | Tiêu đề khối lớn, nhóm sách |
| `headline-sm` | 20px / 28px | 600 (SemiBold) | Bình thường | Tiêu đề card, nhóm thống kê |
| `title-md` | 18px / 26px | 600 (SemiBold) | Bình thường | Tiêu đề danh mục, modal header |
| `title-sm` | 16px / 24px | 600 (SemiBold) | Bình thường | Tên sách trên thẻ sản phẩm (kèm line-clamp-2) |
| `body-lg` | 16px / 26px | 400 (Regular) | Bình thường | Đoạn văn giới thiệu sách, bài viết |
| `body-md` | 14px / 22px | 400 (Regular) | Bình thường | Văn bản mặc định, mô tả, nội dung bảng |
| `body-sm` | 13px / 20px | 400 (Regular) | Bình thường | Thông tin phụ, tên tác giả, NXB, ngày giờ |
| `label-md` | 13px / 18px | 600 (SemiBold) | +0.01em | Nút bấm, tab điều hướng, nhãn input |
| `label-sm` | 11px / 16px | 600 (SemiBold) | +0.02em | Badge trạng thái, tag thể loại, hotline |
| `price-lg` | 22px / 28px | 700 (Bold) | Bình thường | Giá nổi bật trên trang chi tiết, KPI doanh thu |
| `price-md` | 16px / 22px | 700 (Bold) | Bình thường | Giá bán trên thẻ sách, giá trong giỏ hàng |

---

### 3.4. Hệ Thống Khoảng Cách & Bo Góc (Spacing & Border Radius)

#### Khoảng Cách (Spacing Tokens)
- `space-xs`: `0.25rem` (4px) - Khoảng cách icon và chữ, giữa các ngôi sao đánh giá.
- `space-sm`: `0.5rem` (8px) - Padding trong badge, khoảng cách giữa các trường nhỏ.
- `space-md`: `1rem` (16px) - Padding trong thẻ sách, input padding, khoảng cách cột.
- `space-lg`: `1.5rem` (24px) - Padding khối bảng dữ liệu, khoảng cách giữa các phần tử form lớn.
- `space-xl`: `2.5rem` (40px) - Khoảng cách phân cách giữa các section lớn trên trang chủ.
- `gutter`: `1.5rem` (Desktop 24px) / `1rem` (Mobile 16px).
- `margin`: `2rem` (Desktop 32px) / `1rem` (Mobile 16px).
- Chiều rộng vùng chứa tối đa: `max-w-7xl` (`1280px` - có padding `px-margin`).

#### Bo Góc (Border Radius Discipline)
- `DEFAULT`: `0.125rem` (2px - 4px) - Checkbox, nút bấm nhỏ, viền ảnh bìa sách.
- `rounded-lg`: `0.5rem` (8px) - Thẻ sản phẩm, button form, ô input, dropdown menu.
- `rounded-xl`: `0.75rem` (12px) - Khối panel tóm tắt đơn hàng, hộp tìm kiếm, KPI card.
- `rounded-2xl`: `1rem` (16px) - Modal dialog popup (`modal-add-book`, `modal-quick-slip`).
- `rounded-full`: `9999px` - Thanh tìm kiếm chính, chip tag lọc, badge số lượng giỏ hàng, stepper tròn.

---

### 3.5. Hiệu Ứng Chiều Sâu & Bóng Đổ (Elevation & Shadows)
- **Level 0 (Flat):** Nền canvas trang web, viền mảnh hairline `border border-slate-200` (`#E2E8F0`).
- **Level 1 (Card Resting):** Thẻ sách tĩnh, card KPI:
  `box-shadow: 0 1px 3px 0 rgba(15, 41, 66, 0.04), 0 1px 2px -1px rgba(15, 41, 66, 0.04)`.
- **Level 2 (Card Hover):** Khi rê chuột lên thẻ sách:
  `transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(15, 41, 66, 0.08), 0 4px 6px -4px rgba(15, 41, 66, 0.03)`.
- **Level 3 (Dropdown / Mega Menu):** Menu danh mục sách thả xuống:
  `box-shadow: 0 20px 25px -5px rgba(15, 41, 66, 0.12)`.
- **Level 4 (Modal Overlay):** Hộp thoại thêm sách, phiếu nhập kho:
  `box-shadow: 0 25px 50px -12px rgba(15, 41, 66, 0.22); backdrop-filter: blur(4px); background-color: rgba(0, 20, 40, 0.5)`.

---

## 4. PHÂN TÍCH CẤU TRÚC 13 TRANG GIAO DIỆN THỰC TẾ

### 4.1. Nhóm Giao Diện Khách Hàng (Client Portal)

#### 1. Trang Chủ (`trang_ch_bookstore/code.html`)
- **Header Toàn Cục (3 Tầng):**
  - *Tầng 1 (Top Bar - Xanh Navy):* Hotline `1900 6868`, thông báo Freeship từ 250k, nút chuyển vai trò nhanh `[Khách hàng | Quản trị]`.
  - *Tầng 2 (Main Header):* Logo DQP Book, thanh tìm kiếm bo tròn (`rounded-full`), liên kết Tra cứu đơn, Giỏ hàng có badge số lượng `(3)`, menu tài khoản Độc giả dropdown.
  - *Tầng 3 (Navigation Bar & Mega Menu):* Nút bấm mở Mega Menu "Danh Mục Sách" (6 cột: Văn học, Kinh tế, Tâm lý, Thiếu nhi, Ngoại văn, Lịch sử), hệ thống menu chính (Trang Chủ, Sách Mới, Bán Chạy, Khuyến Mãi Hot, Tủ Sách Tinh Hoa, Giới Thiệu), nhãn cam kết "100% Sách Thật Chuẩn NXB".
- **Hero Mosaic Section:**
  - Banner chính tỷ lệ 8/12: Gradient nền sâu (`from-primary-container via-[#133352] to-primary`), countdown timer giờ vàng `36:14:08`, nút CTA "Khám phá ngay", hình ảnh 3D sách bìa nghiêng 6 độ nghệ thuật.
  - 3 Banner phụ tỷ lệ 4/12: "Tác Giả Đoạt Giải Nobel", "Sách Thiếu Nhi Song Ngữ", "Combo Xoa Dịu Tâm Hồn".
- **Khối Cam Kết Giá Trị (Value Propositions):** 4 khối: 100% Sách Thật, Đóng Gói Cẩn Thận, Giao Hàng Siêu Tốc 2H, Đổi Trả 7 Ngày Dễ Dàng.
- **Danh Mục Nổi Bật (Featured Categories):** Grid icon phân loại sách kèm số lượng đầu sách.
- **Sách Giờ Vàng (Flash Deals):** Bộ đếm thời gian thực JavaScript (`initFlashSaleTimer`), thanh tiến trình đã bán (`progress bar`), thẻ giảm giá -35%, -40%.
- **Sách Bán Chạy Trong Tuần:** Hệ thống Tab chuyển nhanh theo thể loại (`#bestseller-tabs` tương tác qua JS), lưới sách 5 cột chuẩn tỷ lệ bìa 2:3.
- **Showcase Tuyển Tập:** "Văn Minh Đông Á & Hành Trình Chữ Viết".
- **Tác Giả Tiêu Biểu:** Danh thiếp tác giả với avatar tròn, tiểu sử và tác phẩm nổi tiếng.
- **Đánh Giá Độc Giả & Newsletter:** Nhận xét kèm sao và form đăng ký email nhận voucher 50.000đ.
- **Footer Toàn Cục (5 Cột):** Giới thiệu nhà sách, dịch vụ khách hàng, chính sách pháp lý, các cổng thanh toán hỗ trợ (VNPAY-QR, MoMo, Visa/Master, COD), chứng nhận SSL và bản quyền.

#### 2. Tìm Kiếm & Lọc Sách Nâng Cao (`t_m_ki_m_l_c_s_ch_bookstore/code.html`)
- **Cột Trái - Bộ Lọc Sidebar (col-span-3):**
  - Thẻ chip hiển thị các bộ lọc đang chọn kèm nút "Xóa tất cả".
  - Lọc theo Thể loại sách (`the_loai` trong CSDL).
  - Lọc theo Khoảng giá: Presets (< 100k, 100k-200k, 200k-500k, > 500k) và 2 ô nhập Min - Max.
  - Lọc theo Đánh giá sao (từ 5 sao đến 3 sao trở lên).
  - Lọc theo Hình thức xuất bản (Bìa mềm, Bìa cứng, Hộp Boxset).
  - Lọc theo Nhà xuất bản (`nha_xuat_ban`).
  - Lọc theo Tình trạng (Còn hàng, Hàng sắp phát hành).
- **Cột Phải - Danh Mục Sách & Toolbar (col-span-9):**
  - Toolbar: Số lượng kết quả tìm thấy, lựa chọn sắp xếp (Phổ biến nhất, Bán chạy nhất, Mới nhất, Giá tăng/giảm dần), toggle chuyển đổi Grid View / List View.
  - Grid Sách: 4 cột sản phẩm, nhãn giảm giá đỏ, rating sao, tên sách clamp 2 dòng, tác giả, giá bán & giá gạch, nút hover "Thêm vào giỏ".
  - Phân trang chuẩn (Pagination): Các nút trang số 1, 2, 3, Next, Last.

#### 3 & 4. Chi Tiết Sách & Đồng Bộ CSDL (`chi_ti_t_s_ch_t_m_l_h_c_v_ti_n/code.html` & `chi_ti_t_s_ch_t_m_l_h_c_v_ti_n_csdl_sach_tac_gia_nha_xuat_ban_danh_gia/code.html`)
- **Khối Giới Thiệu Sản Phẩm:**
  - Gallery Ảnh: Khung ảnh lớn tỷ lệ 2:3, các thumbnail góc nhìn khác nhau, nhãn chứng nhận NXB, nút "Đọc thử các trang đầu".
  - Thông Tin Sách: Tiêu đề H1, tác giả (`Morgan Housel`), dịch giả, NXB Trẻ, năm phát hành, số sao trung bình (4.9/5 ⭐ dựa trên `danh_gia`), khối giá bán niêm yết và giá khuyến mãi.
  - Bộ Tăng Giảm Số Lượng: Input số lượng với nút `-` và `+`.
  - Cụm Nút Chuyển Đổi: "Thêm Vào Giỏ Hàng" (Teal outline/solid), "Mua Ngay" (Navy), nút Lưu Tủ Sách Yêu Thích (Bookmark).
- **Khối Thuộc Tính & Thông Số Kỹ Thuật (Ánh xạ bảng `sach`):**
  - Bảng thuộc tính chi tiết: Mã hàng/ISBN-13, Tên Nhà Xuất Bản, Tác giả, Năm xuất bản, Trọng lượng (gram), Kích thước bao bì, Số trang, Hình thức bìa.
- **Khối Mô Tả Nội Dung Sách:** Bài viết giới thiệu chuyên sâu, trích dẫn hay, nút "Xem thêm / Thu gọn".
- **Khối Đánh Giá & Nhận Xét Độc Giả (Ánh xạ bảng `danh_gia`):**
  - Thống kê sao phân bổ: Điểm trung bình, thanh tỷ lệ 5 sao, 4 sao, 3 sao, 2 sao, 1 sao.
  - Danh sách bình luận: Avatar độc giả, tên, dấu tích xanh "Đã mua hàng", ngày gửi, số sao, nội dung cảm nhận thực tế.
  - Nút mở form "Gửi Đánh Giá Của Bạn".

#### 5 & 6. Giỏ Hàng & Thanh Toán Đặt Hàng (`gi_h_ng_thanh_to_n_bookstore/code.html` & `thanh_to_n_x_c_nh_n_n_h_ng_bookstore/code.html`)
- **Màn Hình Giỏ Hàng:**
  - Danh sách item trong giỏ: Checkbox chọn từng cuốn hoặc chọn tất cả (`selectAllBooks`), ảnh thumbnail, tên sách, phân loại bìa, đơn giá, bộ đếm số lượng, tổng tiền từng dòng, nút xóa dòng, nút "Lưu mua sau".
  - Khối Mã Giảm Giá: Ô nhập mã voucher (áp dụng bảng `ma_giam_gia`), danh sách mã gợi ý sẵn (Freeship 30k, Giảm 10%).
  - Hộp Tóm Tắt Chi Phí: Tiền tạm tính hàng, Tiền giảm giá voucher, Phí vận chuyển ước tính, Tổng thanh toán cuối cùng.
  - Nút chuyển tiếp: "Tiến Hành Đặt Hàng".
- **Màn Hình Thanh Toán (Checkout):**
  - Bước 1: Địa chỉ nhận hàng (Chọn địa chỉ từ `dia_chi` hoặc nhập địa chỉ mới).
  - Bước 2: Phương thức giao hàng (Tiêu chuẩn 2-3 ngày, Hỏa tốc 2-4h).
  - Bước 3: Hình thức thanh toán (COD Tiền mặt, VNPAY QR, Ví MoMo, Thẻ ATM/Visa).
  - Bước 4: Dịch vụ bọc sách màng co / Thiệp ghi lời chúc.
  - Nút "Xác Nhận & Đặt Hàng" kèm Modal Popup thông báo "Đặt hàng thành công", hiển thị Mã đơn hàng (`#DH-XXXXX`) và link về theo dõi đơn.

#### 7 & 8. Quản Lý Đơn Hàng & Lịch Sử Độc Giả (`qu_n_l_n_h_ng_bookstore/code.html` & `qu_n_l_theo_d_i_n_h_ng_bookstore/code.html`)
- **Bố Cục Trang Tài Khoản:**
  - Sidebar Độc Giả: Avatar người dùng, họ tên, cấp hội viên (DQP Book Hội Viên - Cấp Thân Thiết), danh mục điều hướng (Hồ sơ cá nhân, Sổ địa chỉ, Đơn hàng của tôi, Tủ sách yêu thích).
  - Tabs Trạng Thái Đơn Hàng: Tất cả, Chờ xác nhận, Đang chuẩn bị, Đang vận chuyển, Hoàn thành, Đã hủy.
  - Thẻ Đơn Hàng Chi Tiết: Mã đơn hàng, ngày đặt, trạng thái badge, danh sách các đầu sách mua, tổng tiền thanh toán.
  - Quy Trình Vận Chuyển (Timeline Stepper): 4 nốt trực quan (Đã đặt hàng -> Đã duyệt -> Đang giao -> Đã nhận).
  - Các Nút Hành Động Theo Ngữ Cảnh: Hủy đơn hàng (khi đơn còn ở trạng thái `cho_xac_nhan`), Theo dõi hành trình bưu tá, Xác nhận đã nhận hàng, Viết đánh giá sản phẩm, Mua lại đơn này.

---

### 4.2. Nhóm Giao Diện Quản Trị Hệ Thống (Admin Portal)

#### 9 & 10. Bảng Điều Khiển Tổng Quan (`t_ng_quan_qu_n_tr_bookstore_admin_1` & `t_ng_quan_qu_n_tr_bookstore_admin_2`)
- **Sidebar Quản Trị Cố Định (Fixed 72px/288px):**
  - Logo "DQP Book Quản Trị", chọn chi nhánh (Kho Trung Tâm).
  - Menu điều hướng: Dashboard, Quản lý Sách, Quản lý Đơn hàng (kèm badge số đơn cần duyệt `12`), Tồn kho & Phiếu kho, Mã giảm giá, Duyệt Đánh giá (kèm chấm đỏ alert), Người dùng & Hội viên, Báo cáo & Thống kê.
  - Nút "Về Shop Khách Hàng", hồ sơ Admin và nút Đăng xuất.
- **Topbar Quản Trị:** Thanh tìm kiếm nhanh ISBN/Đơn hàng, nút tạo nhanh "Thêm Sách Mới", chuông thông báo có badge, avatar tài khoản admin.
- **4 Bento KPI Cards:**
  1. Doanh thu bán sách (số tiền + % tăng trưởng so với tháng trước).
  2. Tổng đơn hàng mới (phân tách rõ số đơn chờ duyệt và số đơn đang giao).
  3. Lượng sách bán ra (cuốn).
  4. Sức khỏe tồn kho (tổng số sách trong kho + cảnh báo số đầu sách sắp hết < 10 cuốn).
- **Khu Vực Phân Tích Dữ Liệu:**
  - Biểu đồ Doanh thu & Khối lượng đơn hàng (Custom SVG Line/Bar chart kết hợp filter Theo Ngày / Theo Tuần / Theo Tháng).
  - Biểu đồ Phân bổ thể loại sách (Donut chart).
  - Bảng Đơn Hàng Mới Cần Duyệt Gấp (Mã đơn, Khách hàng, Sản phẩm, Tổng tiền, Thao tác duyệt nhanh).
  - Bảng Top 5 Tựa Sách Bán Chạy Nhất Tháng.
  - Widget Cảnh Báo Kho: Danh sách các sách có tồn kho dưới hạn mức an toàn.

#### 11. Quản Lý Sách & Quản Lý Kho (`qu_n_l_s_ch_qu_n_l_kho_bookstore_admin/code.html`)
- **Header & Action Bar:** Nút Xuất Excel báo cáo, Quét Barcode, Nút nổi bật `+ Thêm Sách Mới`.
- **4 Thẻ Chỉ Số Kho Vận:** Tổng đầu sách, Tổng tồn kho, Cảnh báo sắp hết hàng (< 10 cuốn), Giá trị tồn kho ước tính.
- **Thanh Công Cụ Tìm Kiếm & Lọc Dữ Liệu:**
  - Input tìm theo tựa sách, mã ISBN, tác giả.
  - Dropdown lọc Thể loại, Dropdown lọc Nhà xuất bản, Dropdown lọc Tồn kho (Còn hàng, Sắp hết, Hết hàng).
- **Bảng Dữ Liệu Sách Toàn Diện:**
  - Cột: Checkbox chọn hàng loạt, Ảnh bìa sách, Tên sách & Mã ISBN, Tác giả / NXB, Thể loại, Giá nhập & Giá niêm yết, Số lượng tồn kho (hiển thị màu đỏ nếu < 10 cuốn), Switch trạng thái đang bán / ngừng bán, Cột thao tác (Sửa, Xóa, Lập phiếu kho nhanh).
- **Hệ Thống 2 Modal Dialogs Nghiệp Vụ Tích Hợp:**
  - **Modal 1 (`#modal-add-book`):** Form thêm sách mới vào hệ thống CSDL:
    - Cột trái: Drag & drop upload ảnh bìa (`anh_bia`), gợi ý tỷ lệ chuẩn 2:3.
    - Cột phải: Nhập Tiêu đề sách (`tieu_de`), Mã ISBN-13, Thể loại (`the_loai`), Tác giả (`tac_gia`), NXB (`nha_xuat_ban`), Giá niêm yết (`gia`), Số lượng tồn ban đầu (`so_luong_ton`), Số trang, Năm xuất bản, Trọng lượng, Mô tả nội dung.
  - **Modal 2 (`#modal-quick-slip`):** Form tạo phiếu kho nhanh:
    - Loại phiếu: Nhập kho (`nhap`), Xuất kho (`xuat`), Điều chỉnh kho (`dieu_chinh`) (khớp 100% enum bảng `phieu_kho`).
    - Đầu sách áp dụng, Số lượng luân chuyển (+ / -), Lý do / ghi chú chứng từ.

#### 12. Quản Lý & Xử Lý Đơn Hàng Admin (`qu_n_l_n_h_ng_bookstore_admin/code.html`)
- **Thanh Lọc Đơn Hàng:**
  - Input tìm mã đơn, tên khách hàng, số điện thoại.
  - Lọc khoảng ngày đặt, lọc phương thức thanh toán.
- **Tabs Trạng Thái Đơn Hàng Đồng Bộ CSDL:**
  - Tất cả (1.240), Chờ xác nhận (12), Đang xử lý (38), Đang giao hàng (142), Hoàn thành (1.020), Đã hủy (28).
- **Bảng Đơn Hàng Chi Tiết:**
  - Mã đơn hàng (link click xem chi tiết), Tên người nhận & SĐT, Ngày đặt, Số lượng món, Tổng tiền thanh toán (`tong_thanh_toan`), Hình thức thanh toán (COD, VNPAY, MoMo), Trạng thái thanh toán (Đã thanh toán / Chờ thanh toán), Badge trạng thái đơn hàng.
  - Bộ Thao Tác: Duyệt đơn hàng nhanh, Cập nhật trạng thái sang Đang giao, In hóa đơn giao hàng, Hủy đơn có ghi lý do.

#### 13. Quản Lý & Duyệt Đánh Giá Khách Hàng (`duy_t_nh_gi_kh_ch_h_ng_bookstore_admin/code.html`)
- **4 Thẻ Chỉ Số Đánh Giá:**
  - Chờ duyệt (cần xử lý gấp), Đang hiển thị, Điểm đánh giá toàn sàn (4.8/5.0), Đã ẩn/Vi phạm.
- **Bộ Lọc Đánh Giá:** Lọc theo số sao (1 ⭐ đến 5 ⭐), lọc trạng thái (Chờ duyệt, Đã duyệt, Đã ẩn), lọc theo tựa sách.
- **Bảng / Danh Sách Đánh Giá:**
  - Tên độc giả, email, huy hiệu "Đã mua tại BookStore".
  - Tựa sách được đánh giá kèm ảnh thumbnail.
  - Số sao xếp hạng (`so_sao` 1-5 sao) và thời gian đánh giá.
  - Nội dung nhận xét chi tiết của độc giả.
  - Trạng thái hiện tại: `cho_duyet`, `hien_thi`, `an`.
  - Bộ Nút Xử Lý Nghiệp Vụ: Nút "Duyệt hiển thị" (Xanh lá), Nút "Ẩn bình luận" (Đỏ), Nút "Phản hồi từ Nhà Sách" (Mở modal trả lời độc giả).

---

## 5. ÁNH XẠ THIẾT KẾ VỚI CƠ SỞ DỮ LIỆU & KIẾN TRÚC PHP MVC

Hệ thống giao diện mẫu được thiết kế chuẩn mực, tương thích tuyệt đối với cấu trúc cơ sở dữ liệu `database.sql` hiện có trong dự án:

```
[UI Design Components]  <--->  [PHP MVC Controllers/Views]  <--->  [MySQL Database Schema]
```

### 5.1. Bảng Ánh Xạ Chi Tiết Giữa Giao Diện, View, Controller và CSDL

| Màn Hình UI Giao Diện | File View Dự Kiến Trong PHP MVC | Controller Phụ Trách | Các Bảng CSDL Tương Ứng (`database.sql`) |
|:---|:---|:---|:---|
| **Trang Chủ** | `Views/client/home.php` | `HomeController@index` | `sach`, `the_loai`, `tac_gia`, `danh_gia` |
| **Tìm Kiếm & Bộ Lọc** | `Views/client/catalog.php` | `BookController@index` | `sach`, `the_loai`, `nha_xuat_ban`, `tac_gia`, `danh_gia` |
| **Chi Tiết Sách** | `Views/client/detail.php` | `BookController@detail` | `sach`, `sach_tac_gia`, `tac_gia`, `nha_xuat_ban`, `the_loai`, `danh_gia`, `nguoi_dung` |
| **Giỏ Hàng** | `Views/client/cart.php` | `CartController@index` | `gio_hang`, `chi_tiet_gio_hang`, `sach`, `ma_giam_gia` |
| **Thanh Toán / Checkout** | `Views/client/checkout.php` | `CheckoutController@index` | `dia_chi`, `don_hang`, `chi_tiet_don_hang`, `thanh_toan`, `ma_giam_gia` |
| **Đơn Hàng Của Tôi** | `Views/client/orders.php` | `AccountController@orders` | `don_hang`, `chi_tiet_don_hang`, `lich_su_don_hang`, `sach` |
| **Admin Dashboard** | `Views/admin/dashboard.php` | `AdminDashboardController@index` | `don_hang`, `chi_tiet_don_hang`, `sach`, `nguoi_dung` |
| **Admin Quản Lý Sách** | `Views/admin/books/index.php` | `AdminBookController@index` | `sach`, `the_loai`, `nha_xuat_ban`, `tac_gia`, `sach_tac_gia` |
| **Admin Quản Lý Kho** | `Views/admin/inventory/index.php`| `AdminInventoryController@index` | `phieu_kho`, `sach`, `nguoi_dung` |
| **Admin Quản Lý Đơn Hàng** | `Views/admin/orders/index.php` | `AdminOrderController@index` | `don_hang`, `chi_tiet_don_hang`, `thanh_toan`, `lich_su_don_hang`, `nguoi_dung` |
| **Admin Duyệt Đánh Giá**| `Views/admin/reviews/index.php`| `AdminReviewController@index` | `danh_gia`, `sach`, `nguoi_dung` |

---

## 6. QUY TẮC TRIỂN KHAI VÀ BẢO TOÀN THIẾT KẾ (GUIDELINES)

1. **Bảo Tồn Folder Tham Chiếu:** Tuyệt đối không xóa, sửa hoặc ghi đè thư mục `stitch_modern_bookstore_platform_ui_ux`. Toàn bộ mã nguồn MVC mới phải được xây dựng trong các thư mục `Controllers/`, `Models/`, `Views/`, `Core/` và `Public/`.
2. **Quản Lý CSS & Assets:**
   - Cấu hình Tailwind CSS trong file giao diện tham chiếu có thể trích xuất thành CSS tùy chỉnh đặt tại `Public/css/main.css` hoặc sử dụng Tailwind CLI / CDN đồng nhất.
   - Thư viện Google Fonts `Plus Jakarta Sans` và Google `Material Symbols Outlined` cần được nhúng trong layout chung (`Views/layout/main.php` và `Views/layout/admin.php`).
3. **Responsive Breakpoints:**
   - **Mobile (< 768px):** Menu rút gọn drawer, lưới sách 2 cột, giỏ hàng xếp dạng card dọc, bảng quản trị có thanh cuộn ngang `overflow-x-auto`.
   - **Tablet (768px - 1023px):** Lưới sách 3 cột, sidebar ẩn hoặc thu gọn.
   - **Desktop (≥ 1024px):** Lưới sách 4-5 cột, Mega menu đầy đủ, Sidebar Admin 72px/288px cố định.
4. **Trải Nghiệm Tương Tác (Micro-interactions):**
   - Đảm bảo hiệu ứng hover nâng thẻ (`hover:-translate-y-1 hover:shadow-lg transition-all`).
   - Cập nhật số lượng giỏ hàng tức thời không tải lại trang (AJAX).
   - Đóng mở các Modal (`modal-add-book`, `modal-quick-slip`) nhẹ nhàng với animation `fade-in zoom-in-95`.

---
*Tài liệu này là căn cứ kỹ thuật chính thức cho toàn bộ giai đoạn lập trình giao diện và nghiệp vụ website DQP BOOK STORE.*
