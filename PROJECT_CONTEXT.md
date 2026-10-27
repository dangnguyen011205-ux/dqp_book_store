# TÀI LIỆU NGỮ CẢNH DỰ ÁN (PROJECT CONTEXT)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Kiến trúc:** PHP 8.x Thuần (OOP, MVC Pattern, PDO Prepared Statements)  
> **Design System:** Athenaeum Modern (Thư Viện Tinh Hoa Cổ Điển Hiện Đại v2.4)  
> **Phiên bản cập nhật:** 4.5.0 (Hoàn thành 5/7 Nhiệm vụ Giai đoạn 4: Admin Portal)  
> **Ngày cập nhật:** 27/10/2026  

---

## 1. MỤC TIÊU DỰ ÁN
Xây dựng trọn vẹn hệ thống thương mại điện tử chuyên ngành sách **"DQP Book Store"** đáp ứng đầy đủ:
1. **Phân hệ Khách hàng (Client Portal):** 
   - Khám phá sách theo phong cách thư viện tinh hoa: Hero Mosaic Banner, Flash Deals có progress bar tồn kho, Bestseller Tabs lọc theo thể loại, Tác giả tiêu biểu, Bộ sưu tập tuyển chọn.
   - Tìm kiếm & Lọc đa tiêu chí: Tương thích đồng thời từ khóa `q`, thể loại `the_loai`, tác giả `tac_gia`, NXB `nxb`, khoảng giá Min-Max / Presets, số sao đánh giá `so_sao`, tình trạng còn hàng `con_hang`, sắp xếp và phân trang URL động.
   - Chi tiết tác phẩm: Thông số kỹ thuật, đồng tác giả, sách liên quan cùng thể loại, phân bổ rating 1-5 sao và danh sách nhận xét kiểm duyệt.
   - Giỏ hàng & Thanh toán: Quản lý giỏ hàng linh hoạt (Session cho khách vãng lai và CSDL cho thành viên, tự động gộp khi đăng nhập), kiểm tra tồn kho tức thì, áp dụng voucher với các điều kiện ràng buộc khắt khe, hỗ trợ chọn địa chỉ nhận hàng từ sổ địa chỉ hoặc thêm mới trực tiếp.
   - Quản lý đơn hàng & Hành trình vận đơn: Bộ lọc Tabs trạng thái kèm số lượng đếm, Timeline Stepper 4 bước hiển thị lộ trình trực quan (`lich_su_don_hang`), hủy đơn an toàn với Database Transaction và hoàn trả tồn kho.
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
  - Vanilla JavaScript cho các tương tác DOM, Toast notifications, Modal popups, Countdown timer, Stepper animations.

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
  - `cho_xac_nhan` / `cho_duyet` / `cho_thanh_toan`: Nền `#FEF3C7` (Amber-100), Chữ `#B45309`, Icon `pending_actions`.
  - `da_xac_nhan`: Nền `#E2E8F0` (Slate-200), Chữ `#334155`, Icon `inventory`.
  - `dang_giao`: Nền `#E0F2FE` (Sky-100), Chữ `#0369A1`, Icon `local_shipping`.
  - `hoan_thanh` / `hien_thi` / `thanh_cong`: Nền `#D1FAE5` (Emerald-100), Chữ `#047857`, Icon `verified` / `check_circle`.
  - `da_huy` / `an` / `that_bai`: Nền `#FFDAD6` (Rose-100), Chữ `#BA1A1A`, Icon `cancel` / `warning`.

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
│   ├── AuthController.php        # Đăng nhập, đăng ký, đăng xuất, tự động gán & gộp giỏ hàng session
│   ├── AccountController.php     # Hồ sơ cá nhân, đổi mật khẩu, CRUD sổ địa chỉ, đơn hàng & hủy đơn
│   ├── HomeController.php        # Trang chủ: nạp Flash Deals, Bestsellers, Tác giả, Reviews
│   ├── BookController.php        # Danh mục, bộ lọc tìm kiếm đa tiêu chí, chi tiết sách, gửi đánh giá
│   ├── CartController.php        # Quản lý giỏ hàng, cập nhật số lượng, áp dụng / gỡ mã giảm giá
│   ├── CheckoutController.php    # Thanh toán, chọn/thêm địa chỉ, phí ship, xác nhận đơn hàng
│   ├── AdminDashboardController.php # Bảng điều khiển quản trị tổng quan (4 Bento KPI cards, biểu đồ SVG)
│   ├── AdminBookController.php   # Quản lý sách: tìm kiếm, bộ lọc, modal thêm/sửa, upload ảnh bìa, gán tác giả
│   ├── AdminInventoryController.php # Quản lý tồn kho: lập phiếu nhập/xuất/điều chỉnh với Transaction
│   ├── AdminOrderController.php  # Quản lý đơn hàng: bộ lọc nâng cao, drawer chi tiết, chuyển đổi trạng thái
│   ├── AdminReviewController.php # Kiểm duyệt đánh giá: bộ lọc sao, tabs trạng thái, floating bulk toolbar
│   ├── AdminCategoryController.php # Quản lý thể loại sách: modal thêm/sửa, xóa an toàn kiểm tra sách
│   ├── AdminAuthorController.php # Quản lý tác giả: modal thêm/sửa, xóa an toàn kiểm tra sách
│   └── AdminPublisherController.php # Quản lý nhà xuất bản: modal thêm/sửa, xóa an toàn kiểm tra sách
│
├── Models/
│   ├── User.php                  # Tương tác bảng nguoi_dung
│   ├── Address.php               # Tương tác bảng dia_chi (quản lý địa chỉ mặc định bằng Transaction)
│   ├── Category.php              # Tương tác bảng the_loai (danh mục sách, đếm đầu sách, safe delete)
│   ├── Book.php                  # Tương tác bảng sach, sach_tac_gia, chi_tiet_don_hang
│   ├── Author.php                # Tương tác bảng tac_gia (safe delete, đếm tác phẩm)
│   ├── Publisher.php             # Tương tác bảng nha_xuat_ban (safe delete, đếm tác phẩm)
│   ├── Review.php                # Tương tác bảng danh_gia (rating summary, kiểm duyệt, duyệt hàng loạt)
│   ├── Cart.php                  # Tương tác bảng gio_hang & chi_tiet_gio_hang, session cart, merge cart
│   ├── Coupon.php                # Tương tác bảng ma_giam_gia (xác thực thời hạn, đơn tối thiểu, số lượt)
│   ├── Order.php                 # Tương tác bảng don_hang, chi_tiet_don_hang, thanh_toan, lich_su_don_hang
│   └── InventorySlip.php         # Tương tác bảng phieu_kho & chi_tiet_phieu_kho (nhập, xuất, điều chỉnh)
│
├── Views/
│   ├── layout/
│   │   ├── main.php              # Layout Khách hàng: Header 3 tầng, Mega Menu, Cart badge, Toast, Footer
│   │   └── admin.php             # Layout Quản trị: Sidebar cố định, Topbar tìm kiếm, profile chip
│   ├── client/
│   │   ├── 404.php               # Giao diện trang 404 không tìm thấy
│   │   ├── home.php              # Giao diện trang chủ (Hero Mosaic, Countdown, Flash deals, Bestsellers)
│   │   ├── catalog.php           # Giao diện tìm kiếm, bộ lọc đa tiêu chí, phân trang
│   │   ├── detail.php            # Giao diện chi tiết sách, 3 tabs, thống kê rating & form nhận xét
│   │   ├── cart.php              # Giao diện giỏ hàng (bộ đếm số lượng, nhập voucher, tóm tắt chi phí)
│   │   ├── checkout.php          # Giao diện thanh toán (sổ địa chỉ, chọn vận chuyển tiêu chuẩn / hỏa tốc)
│   │   ├── checkout_success.php  # Màn hình xác nhận đặt hàng thành công (mã đơn #BK-..., bento grid)
│   │   ├── auth/
│   │   │   ├── login.php         # Giao diện đăng nhập (có nút 1 chạm điền demo admin / khách)
│   │   │   └── register.php      # Giao diện đăng ký thành viên
│   │   └── account/
│   │       ├── _sidebar.php      # Component Sidebar độc giả (cấp hội viên, menu tài khoản)
│   │       ├── profile.php       # Quản lý hồ sơ độc giả & đổi mật khẩu
│   │       ├── addresses.php     # Sổ địa chỉ giao nhận kèm modal thêm/sửa
│   │       ├── orders.php        # Quản lý đơn hàng (Tabs trạng thái, đếm số lượng, danh sách thẻ đơn)
│   │       └── order_detail.php  # Chi tiết đơn hàng (Timeline Stepper 4 bước, hành trình vận đơn, hủy đơn)
│   └── admin/
│       ├── dashboard.php         # Bảng điều khiển quản trị tổng quan (4 Bento KPI cards, SVG charts)
│       ├── books/
│       │   └── index.php         # Quản lý sách & kho: modal thêm/sửa sách, modal lập phiếu kho, 2 tabs
│       ├── orders/
│       │   └── index.php         # Quản lý đơn hàng: tabs trạng thái, bộ lọc, slide-over drawer, đổi trạng thái
│       ├── reviews/
│       │   └── index.php         # Quản lý & duyệt đánh giá: tabs, lọc sao, floating bulk toolbar
│       ├── categories/
│       │   └── index.php         # Quản lý thể loại sách: sub-nav, bento KPI, modal thêm/sửa, safe delete
│       ├── authors/
│       │   └── index.php         # Quản lý tác giả: sub-nav, bento KPI, modal thêm/sửa, avatar, safe delete
│       └── publishers/
│           └── index.php         # Quản lý nhà xuất bản: sub-nav, bento KPI, modal thêm/sửa, safe delete
│
├── Public/
│   ├── css/
│   │   └── main.css              # Custom keyframes animation, tỉ lệ bìa 2:3, scrollbar
│   ├── js/
│   │   └── main.js               # Quản lý Toast timeout, đóng mở modal
│   └── uploads/
│       └── books/                # Thư mục lưu ảnh bìa sách tải lên từ Admin
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
5. **Quy tắc Giỏ Hàng & Tự Động Gộp Khi Đăng Nhập:**
   - Khách vãng lai: Lưu giỏ hàng tạm thời trong `$_SESSION['cart']`.
   - Thành viên đã đăng nhập: Lưu trực tiếp trong CSDL tại 2 bảng `gio_hang` và `chi_tiet_gio_hang`.
   - Khi người dùng đăng nhập thành công (`AuthController::login()`), phương thức `Cart::mergeSessionCart()` sẽ tự động chuyển toàn bộ sách từ Session vào CSDL của tài khoản (cộng dồn số lượng nếu đã có, kiểm tra không vượt quá tồn kho), sau đó dọn dẹp sạch `$_SESSION['cart']`.
6. **Quy tắc Mã Giảm Giá (Coupon Validation):**
   - Phương thức `Coupon::validateCoupon($code, $subtotal)` kiểm tra đầy đủ 4 điều kiện:
     1. Mã có tồn tại trong bảng `ma_giam_gia` không.
     2. Thời gian hiện tại nằm trong khoảng `bat_dau` <= NOW() <= `ket_thuc`.
     3. Số lượt sử dụng đã đạt giới hạn `da_dung >= so_luot_toi_da` hay chưa.
     4. Giá trị đơn hàng tối thiểu `don_toi_thieu` có được thỏa mãn không.
   - Hỗ trợ 2 hình thức: `phan_tram` (tính theo % giá trị giỏ hàng) và `tien_mat` (trừ trực tiếp số tiền cố định).
7. **Quy tắc Phí Vận Chuyển:**
   - Đơn hàng tiêu chuẩn có giá trị từ 250.000đ trở lên được miễn phí vận chuyển (0đ). Đơn dưới 250.000đ có phí 22.000đ - 25.000đ.
   - Tùy chọn giao hỏa tốc NowShip 2h áp dụng mức phí cố định 40.000đ.
8. **Quy tắc Giao Tác Đơn Hàng An Toàn Tuyệt Đối (Database Transaction):**
   - Khi tạo đơn hàng (`Order::createOrder`):
     1. Bắt đầu giao tác `beginTransaction()`.
     2. Khóa dòng kiểm tra tồn kho bằng `SELECT ... FOR UPDATE` cho từng đầu sách trong giỏ. Nếu tồn kho không đủ hoặc sách đã ngừng bán, lập tức `rollBack()` và báo lỗi.
     3. Trừ số lượng tồn kho `UPDATE sach SET so_luong_ton = so_luong_ton - ?`.
     4. Tạo đơn hàng trong bảng `don_hang` với trạng thái `cho_xac_nhan`.
     5. Lưu từng mặt hàng vào `chi_tiet_don_hang`.
     6. Nếu có mã giảm giá, tăng số lượt dùng `UPDATE ma_giam_gia SET da_dung = da_dung + 1`.
     7. Tạo bản ghi giao dịch trong bảng `thanh_toan` (COD: `cho_thanh_toan`, VNPAY: `thanh_cong` kèm mã giao dịch).
     8. Ghi log khởi tạo vào `lich_su_don_hang` ("Đã đặt đơn hàng thành công qua hệ thống trực tuyến").
     9. Xóa giỏ hàng của người dùng qua `Cart::clearCart()`.
     10. `commit()` giao tác an toàn.
9. **Quy tắc Hủy Đơn Hàng & Hoàn Trả Tồn Kho (Order Cancellation):**
   - Độc giả chỉ được phép hủy đơn hàng khi đơn còn ở trạng thái `cho_xac_nhan`.
   - Toàn bộ quá trình hủy được bọc trong `beginTransaction()`:
     1. Khóa dòng đơn hàng bằng `SELECT ... FOR UPDATE`.
     2. Duyệt qua danh sách `chi_tiet_don_hang`, hoàn trả lại số lượng sách vào kho `UPDATE sach SET so_luong_ton = so_luong_ton + ?`.
     3. Nếu đơn có dùng voucher, giảm số lượt sử dụng `UPDATE ma_giam_gia SET da_dung = GREATEST(0, da_dung - 1)`.
     4. Cập nhật trạng thái đơn thành `da_huy`.
     5. Ghi log hủy kèm lý do của khách hàng vào bảng `lich_su_don_hang`.
     6. `commit()` giao tác an toàn.
10. **Quy tắc Timeline Stepper 4 Bước Theo Dõi Đơn Hàng:**
    - Giao diện chi tiết đơn hàng trực quan hóa trạng thái thành 4 bước:
      - Bước 1: *Đã Đặt Đơn*
      - Bước 2: *Xác Nhận & Đóng Gói*
      - Bước 3: *Đang Vận Chuyển*
      - Bước 4: *Giao Thành Công*
    - Hiển thị dòng thời gian thực tế ghi nhận từ bảng `lich_su_don_hang` kèm icon trực quan theo Design System Athenaeum Modern. Nếu đơn bị hủy, hiển thị banner cảnh báo và badge `ĐÃ HỦY` (Rose-100 / Red-600).
11. **Quy tắc Định Tuyến & Bắt Lỗi 404 Chặt Chẽ:**
    - `Core/App.php` phân giải route theo thứ tự: Friendly Routes -> Class Controller tương ứng -> Nếu không tồn tại bất kỳ controller nào hợp lệ, lập tức trả về `render404()` với `http_response_code(404)` và render `Views/client/404.php`. Nghiêm cấm fallback âm thầm về Trang Chủ nhằm bảo đảm tính toàn vẹn SEO.
12. **Bảo Tồn Mã Nguồn Đã Được Yêu Cầu Giữ Nguyên:**
    - Tệp `Views/client/account/order_detail.php` và thư mục `stitch_modern_bookstore_platform_ui_ux/` tuân thủ nghiêm ngặt chỉ dẫn của người dùng: Giữ nguyên vẹn, không tự ý chỉnh sửa nếu không có yêu cầu cụ thể.
13. **Quy tắc Quản Lý Phiếu Kho & Kiểm Kê Hàng Tồn (Inventory Slips):**
    - Mọi thao tác nhập hàng, xuất hàng, hoặc điều chỉnh kiểm kê đều phải thông qua Database Transaction (`beginTransaction()`) với khóa dòng `SELECT ... FOR UPDATE` trên bảng `sach`.
    - Khi xuất kho hoặc điều chỉnh giảm, hệ thống bắt buộc kiểm tra `so_luong_ton - so_luong >= 0`, nghiêm cấm để xảy ra tình trạng tồn kho âm.
    - Lưu vết chi tiết vào `phieu_kho` và `chi_tiet_phieu_kho` kèm thông tin người lập, ghi chú và lý do điều chỉnh.
14. **Quy tắc Xử Lý Vòng Đời Đơn Hàng Phía Quản Trị (Admin Order Cycle):**
    - Chuyển đổi trạng thái đơn hàng (`cho_xac_nhan` -> `da_xac_nhan` -> `dang_giao` -> `hoan_thanh` -> `da_huy`) được bảo vệ bằng Transaction và khóa dòng `SELECT ... FOR UPDATE`.
    - Khi Admin Hủy đơn: Tự động hoàn trả tồn kho vào bảng `sach`, giảm số lượt dùng voucher `ma_giam_gia.da_dung`, hủy bản ghi thanh toán và ghi nhật ký vận chuyển vào `lich_su_don_hang`.
    - Khi Admin Hoàn tất đơn: Tự động cập nhật thanh toán COD thành `thanh_cong` nếu khách thanh toán khi nhận hàng.
15. **Quy tắc Kiểm Duyệt Đánh Giá Khách Hàng (Review Moderation):**
    - Hỗ trợ 3 trạng thái kiểm duyệt: `cho_duyet` (mới gửi), `hien_thi` (công khai ra trang chi tiết sách và trang chủ), `an` (vi phạm/ẩn).
    - Hỗ trợ thanh công cụ hàng loạt Floating Bulk Toolbar (`#bulkToolbar`) cho phép Admin chọn nhiều đánh giá cùng lúc để Duyệt hiển thị, Ẩn hoặc Xóa vĩnh viễn với phản hồi AJAX Toast tức thì.
16. **Quy tắc Bảo Vệ Ràng Buộc Khóa Ngoại Metadata (Safe Delete):**
    - Thể loại (`the_loai`), Tác giả (`tac_gia`), Nhà xuất bản (`nha_xuat_ban`) trước khi xóa bắt buộc kiểm tra xem có sách liên kết trong hệ thống hay không (`tong_sach > 0`).
    - Nếu đang có sách liên kết, hệ thống chủ động chặn xóa từ cấp Model và trả về cảnh báo thân thiện, ngăn chặn lỗi vi phạm khóa ngoại MySQL (`FOREIGN KEY constraint fails`).
17. **Quy tắc Trailing Slash `baseUrl` Phía Client JavaScript:**
    - Khi nhúng `base_url()` vào JavaScript làm tiền tố URL cho các lời gọi AJAX hoặc form action động, bắt buộc phải có dấu gạch chéo cuối: `const baseUrl = '<?= base_url() ?>/';` để ngăn chặn việc nối chuỗi thiếu slash dẫn tới lỗi 404 dính liền tên miền (ví dụ: `.../book_store_onlineadmin/...`).

---

## 6. LỆNH CÀI ĐẶT, VẬN HÀNH & KIỂM THỬ HỆ THỐNG

### Vận hành cục bộ (Local Runtime):
- Môi trường: XAMPP với Apache (Port 80) và MySQL (Port 3306).
- URL gốc: `http://localhost/PHP_nang_cao/book_store_online/`
- Phân hệ Admin: `http://localhost/PHP_nang_cao/book_store_online/admin`

### Kiểm thử hệ thống:
```bash
# 1. Quét cú pháp toàn bộ 73 tệp PHP toàn dự án
php scratch/test_lint_all.php

# 2. Chạy Master Suite kiểm thử toàn diện Giai đoạn 1, 2, 3 (26 nghiệp vụ)
php scratch/test_all_features.php

# 3. Kiểm thử tích hợp Quản lý Sách & Kho Admin (16 test cases)
php scratch/test_admin_books_inventory.php

# 4. Kiểm thử tích hợp Quản lý Đơn hàng Admin (47 test cases)
php scratch/test_admin_orders.php

# 5. Kiểm thử tích hợp Quản lý & Duyệt Đánh giá (35 test cases)
php scratch/test_admin_reviews.php

# 6. Kiểm thử tích hợp CRUD Metadata Thể loại, Tác giả, NXB (36 test cases)
php scratch/test_admin_metadata.php
```
