# TÀI LIỆU NGỮ CẢNH DỰ ÁN (PROJECT CONTEXT)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Kiến trúc:** PHP 8.x Thuần (OOP, MVC Pattern, PDO Prepared Statements)  
> **Design System:** Athenaeum Modern (Thư Viện Tinh Hoa Cổ Điển Hiện Đại v2.4)  
> **Phiên bản cập nhật:** 2.0.0 (Hoàn thành Giai đoạn 2 - Khám Phá Sách, Tìm Kiếm & Đánh Giá)  
> **Ngày cập nhật:** 24/10/2026  

---

## 1. MỤC TIÊU DỰ ÁN
Xây dựng trọn vẹn hệ thống thương mại điện tử chuyên ngành sách **"DQP Book Store"** đáp ứng đầy đủ:
1. **Phân hệ Khách hàng (Client Portal):** 
   - Khám phá sách theo phong cách thư viện tinh hoa: Hero Mosaic Banner, Flash Deals có progress bar tồn kho, Bestseller Tabs lọc theo thể loại, Tác giả tiêu biểu, Bộ sưu tập tuyển chọn.
   - Tìm kiếm & Lọc đa tiêu chí: Tương thích đồng thời từ khóa `q`, thể loại `the_loai`, tác giả `tac_gia`, NXB `nxb`, khoảng giá Min-Max / Presets, số sao đánh giá `so_sao`, tình trạng còn hàng `con_hang`, sắp xếp và phân trang URL động.
   - Chi tiết tác phẩm: Thông số kỹ thuật, đồng tác giả, sách liên quan cùng thể loại, phân bổ rating 1-5 sao và danh sách nhận xét kiểm duyệt.
   - Giỏ hàng tương tác, áp dụng mã voucher, checkout transaction và tra cứu tiến độ đơn hàng qua Timeline Stepper 4 bước.
2. **Phân hệ Quản trị (Admin Portal):** Dashboard Bento KPI cards phân tích doanh thu và tồn kho, CRUD danh mục/sách/tác giả/NXB, quản lý nhập-xuất-điều chỉnh kho bằng phiếu kho, xử lý vòng đời đơn hàng, duyệt đánh giá độc giả, quản lý voucher và phân quyền/khóa người dùng.
3. **Tiêu chuẩn Kỹ thuật:** Không sử dụng framework nặng (Laravel/Symfony), viết bằng PHP thuần hướng đối tượng (OOP MVC), bảo mật chặt chẽ bằng PDO Prepared Statements, Session an toàn, Bcrypt password hashing, kiểm soát trạng thái tài khoản thời gian thực.

---

## 2. STACK CÔNG NGHỆ & MÔI TRƯỜNG CHẠY
- **Ngôn ngữ Backend:** PHP 8.2+ (hướng đối tượng, strict types, PSR-4 Autoloading tự tạo).
- **Hệ quản trị CSDL:** MySQL 8.x / MariaDB, bảng mã `utf8mb4_unicode_ci`, gồm 16 bảng chuẩn hóa.
- **Web Server:** Apache (XAMPP for Windows), cấu hình URL Rewriting thông qua `.htaccess`.
- **Thư viện Frontend & UI:**
  - Tailwind CSS (mở rộng Design Tokens của phong cách Athenaeum Modern).
  - Phông chữ chính: Google Fonts `Plus Jakarta Sans` (weights 400, 500, 600, 700).
  - Bộ Icon hệ thống: Google `Material Symbols Outlined`.
  - Vanilla JavaScript cho các tương tác DOM, Toast notifications, Modal popups, Countdown timer.

---

## 3. THIẾT KẾ GIAO DIỆN "ATHENAEUM MODERN" (DESIGN SYSTEM)
Thiết kế mang vẻ đẹp tri thức, học thuật, tôn vinh văn hóa đọc nhưng hiện đại và mượt mà:
- **Brand Core Colors:**
  - `primary` (`#001428`): Xanh hải quân thẫm (tiêu đề, nút chính).
  - `primary-container` (`#0F2942`): Xanh navy đại dương (top bar, hero banner).
  - `secondary` (`#006A61`): Xanh mòng két Deep Teal (nút Mua ngay, điểm nhấn).
  - `secondary-container` (`#86F2E4`): Xanh ngọc biển nhạt (highlight).
  - `tertiary-fixed` (`#FFDDB8`): Cam hổ phách nhạt (sao đánh giá, deal hot).
- **Surface & Backgrounds:**
  - `background` (`#F7F9FB`): Nền toàn trang ngả tông giấy sách ấm.
  - `surface-container-lowest` (`#FFFFFF`): Nền thẻ sách, giỏ hàng, modal popup, card.
  - `surface-container-low` (`#F2F4F6`): Nền sidebar, input search.
- **Semantic Status Badges (Khớp 100% CSDL):**
  - `cho_xac_nhan` / `cho_duyet`: Nền `#FEF3C7` (Amber-100), Chữ `#B45309`, Icon `pending_actions`.
  - `da_xac_nhan`: Nền `#E2E8F0` (Slate-200), Chữ `#334155`, Icon `inventory`.
  - `dang_giao`: Nền `#E0F2FE` (Sky-100), Chữ `#0369A1`, Icon `local_shipping`.
  - `hoan_thanh` / `hien_thi`: Nền `#D1FAE5` (Emerald-100), Chữ `#047857`, Icon `verified` / `check_circle`.
  - `da_huy` / `an`: Nền `#FFDAD6` (Rose-100), Chữ `#BA1A1A`, Icon `cancel` / `warning`.

---

## 4. CẤU TRÚC THƯ MỤC HỆ THỐNG
```
c:\xampp\htdocs\PHP_nang_cao\book_store_online\
│
├── .htaccess                     # Cấu hình Apache rewrite url sang index.php?url=$1 [B,QSA,L]
├── index.php                     # Front Controller duy nhất khởi động toàn bộ ứng dụng
├── database.sql                  # Toàn bộ schema 16 bảng và dữ liệu mẫu UTF-8
├── DESIGN.md                     # Tài liệu đặc tả hệ thống giao diện Athenaeum Modern
├── PROJECT_CONTEXT.md            # [Tài liệu này] Ngữ cảnh, kiến trúc và quyết định kỹ thuật
├── PROGRESS.md                   # Tiến độ công việc chi tiết qua các giai đoạn
├── TASKS.md                      # Trạng thái từng nhiệm vụ
│
├── Config/
│   └── config.php                # Cấu hình BASE_URL động, hằng số kết nối DB, session, app info
│
├── Core/
│   ├── App.php                   # Core Router phân giải Friendly Routes & Controller/Action/Params
│   ├── Database.php              # Singleton PDO kết nối MySQL an toàn, ném Exception
│   ├── Model.php                 # Base Model (query, fetchOne, fetchAll, execute, transaction)
│   ├── Controller.php            # Base Controller (view layout engine, model loader, json, redirect)
│   ├── Auth.php                  # Quản lý phiên session, xác thực bcrypt, phân quyền, kiểm tra khóa
│   └── Helper.php                # Các hàm trợ giúp toàn cục: base_url, asset, flash, status_badge...
│
├── Controllers/
│   ├── AuthController.php        # Đăng nhập, đăng ký, đăng xuất, tự động gán giỏ hàng
│   ├── AccountController.php     # Hồ sơ cá nhân, đổi mật khẩu, CRUD sổ địa chỉ
│   ├── HomeController.php        # Trang chủ: nạp Flash Deals, Bestsellers, Tác giả, Reviews
│   ├── BookController.php        # Danh mục, bộ lọc tìm kiếm đa tiêu chí, chi tiết sách, gửi đánh giá
│   └── AdminDashboardController.php # Bảng điều khiển quản trị tổng quan
│
├── Models/
│   ├── User.php                  # Tương tác bảng nguoi_dung
│   ├── Address.php               # Tương tác bảng dia_chi (quản lý địa chỉ mặc định bằng Transaction)
│   ├── Category.php              # Tương tác bảng the_loai (danh mục sách, đếm đầu sách)
│   ├── Book.php                  # Tương tác bảng sach, sach_tac_gia, chi_tiet_don_hang (bộ lọc, flash deals, chi tiết)
│   ├── Author.php                # Tương tác bảng tac_gia
│   ├── Publisher.php             # Tương tác bảng nha_xuat_ban
│   ├── Review.php                # Tương tác bảng danh_gia (rating summary, kiểm duyệt, kiểm tra quyền mua)
│   └── Cart.php                  # Tương tác bảng gio_hang & chi_tiet_gio_hang
│
├── Views/
│   ├── layout/
│   │   ├── main.php              # Layout Khách hàng: Header 3 tầng, Mega Menu, Toast, Footer 5 cột
│   │   └── admin.php             # Layout Quản trị: Sidebar cố định, Topbar tìm kiếm, profile chip
│   ├── client/
│   │   ├── 404.php               # Giao diện trang 404 không tìm thấy
│   │   ├── home.php              # Giao diện trang chủ (Hero Mosaic, Countdown, Flash deals, Bestsellers)
│   │   ├── catalog.php           # Giao diện tìm kiếm, bộ lọc đa tiêu chí, phân trang
│   │   ├── detail.php            # Giao diện chi tiết sách, 3 tabs, thống kê rating & form nhận xét
│   │   ├── auth/
│   │   │   ├── login.php         # Giao diện đăng nhập (có nút 1 chạm điền demo admin / khách)
│   │   │   └── register.php      # Giao diện đăng ký thành viên
│   │   └── account/
│   │       ├── _sidebar.php      # Component Sidebar độc giả (cấp hội viên, tiến độ đọc)
│   │       ├── profile.php       # Quản lý hồ sơ độc giả & đổi mật khẩu
│   │       └── addresses.php     # Sổ địa chỉ giao nhận kèm modal thêm/sửa
│   └── admin/
│       └── dashboard.php         # Trang tổng quan quản trị (4 Bento KPI cards)
│
├── Public/
│   ├── css/
│   │   └── main.css              # Custom keyframes animation, tỉ lệ bìa 2:3, scrollbar
│   ├── js/
│   │   └── main.js               # Quản lý Toast timeout, đóng mở modal
│   └── uploads/
│       └── books/                # Thư mục lưu ảnh bìa sách
│
├── stitch_modern_bookstore_platform_ui_ux/  # [READ-ONLY SINGLE SOURCE OF TRUTH] 13 folder giao diện mẫu
└── vnpay_php/                    # Thư mục chứa mã nguồn mẫu tích hợp cổng thanh toán VNPAY
```

---

## 5. CÁC QUY TẮC NGHIỆP VỤ & QUYẾT ĐỊNH KỸ THUẬT QUAN TRỌNG

1. **Nguyên tắc Bất khả xâm phạm (Read-Only):**
   - Thư mục `stitch_modern_bookstore_platform_ui_ux/` là tài nguyên mẫu tham chiếu nguyên bản (Single Source of Truth). Nghiêm cấm xóa, đổi tên, ghi đè hoặc di chuyển bất kỳ tệp tin nào trong thư mục này. Mọi mã nguồn thực thi phải nằm ở `Controllers/`, `Models/`, `Views/`, `Core/`, `Config/`, `Public/`.
2. **Quy tắc Base URL Động:**
   - Trong `Config/config.php`, `BASE_URL` được tính toán tự động dựa trên `dirname($_SERVER['SCRIPT_NAME'])`. Nhờ đó, ứng dụng chạy trơn tru dù đặt ở root web server (`http://localhost/`) hay thư mục con (`http://localhost/PHP_nang_cao/book_store_online/`).
3. **Quy tắc Địa chỉ Giao Hàng Mặc Định:**
   - Mỗi người dùng chỉ được có tối đa 1 địa chỉ mặc định (`mac_dinh = 1`). Khi thêm mới hoặc sửa một địa chỉ thành mặc định, `Address::create()` và `Address::update()` luôn sử dụng `beginTransaction()` để reset các địa chỉ khác về `0`. Nếu xóa đúng địa chỉ mặc định, hệ thống tự động gán địa chỉ còn lại gần nhất thành mặc định.
4. **Quy tắc Kiểm Soát Trạng Thái Tài Khoản:**
   - Mọi request của người dùng đã đăng nhập đều được `Auth::check()` xác thực lại trực tiếp với CSDL qua `Auth::verifyAccountStatus()`. Nếu người quản trị khóa tài khoản (`trang_thai = 'khoa'`), người dùng bị lập tức đăng xuất và chuyển hướng về màn hình đăng nhập.
5. **Quy tắc Tìm Kiếm & Lọc Đa Tiêu Chí:**
   - Cho phép kết hợp không giới hạn các tiêu chí: Từ khóa `q` (tìm trên tiêu đề sách, tên tác giả, tóm tắt nội dung), Thể loại `the_loai`, Tác giả `tac_gia`, Nhà xuất bản `nxb`, Khoảng giá `gia_min`/`gia_max`, Đánh giá sao `so_sao`, và Tình trạng còn hàng `con_hang` (`so_luong_ton > 0`).
   - Câu truy vấn đếm tổng số bản ghi phân trang trong `Book::filterBooks()` được tối ưu bằng subquery `HAVING` khi có lọc số sao, đảm bảo số trang và thanh phân trang đồng bộ chính xác.
6. **Quy tắc Kiểm Duyệt Đánh Giá:**
   - Khách hàng chỉ được gửi đánh giá cho cuốn sách khi thỏa mãn:
     1. Đã đăng nhập tài khoản.
     2. Đã mua cuốn sách đó trong một đơn hàng có trạng thái `hoan_thanh` (`Review::hasUserPurchased`).
     3. Chưa từng đánh giá cuốn sách này trước đó (`Review::hasUserReviewed`, ràng buộc duy nhất `uk_danh_gia_user_sach`).
   - Đánh giá mới tạo luôn mang trạng thái `cho_duyet`, chỉ được hiển thị công khai ngoài trang chi tiết khi quản trị viên duyệt sang `hien_thi`.
7. **Quy tắc Fallback Ảnh Bìa Chuẩn Tỷ Lệ 2:3:**
   - Hàm tiện ích `getBookCover()` kiểm tra tệp tin thực tế trong `Public/uploads/books/`. Nếu chưa có tệp ảnh vật lý, hệ thống tự động fallback sang ảnh chuẩn của các tác phẩm mẫu trong CSDL để đảm bảo giao diện luôn hiển thị trực quan và tinh tế theo chuẩn Athenaeum Modern.
8. **Quy tắc Giao Tác Đơn Hàng & Kho:**
   - Bắt buộc dùng Database Transaction khi tạo đơn hàng (trừ tồn kho, tăng số lượt dùng voucher, ghi log lịch sử đơn hàng) và khi hủy đơn (hoàn lại tồn kho, hoàn lượt voucher, ghi log lịch sử).
