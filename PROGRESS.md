# BÁO CÁO TIẾN ĐỘ DỰ ÁN (PROJECT PROGRESS)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Cập nhật:** Hoàn thành 100% Giai đoạn 2 / Chuẩn bị bước vào Giai đoạn 3  
> **Thời điểm cập nhật:** 24/10/2026  

---

## 1. NHỮNG VIỆC ĐÃ HOÀN THÀNH

### Giai đoạn 1: Nền tảng MVC, CSDL, Bảo Mật, Hồ Sơ & Sổ Địa Chỉ (Hoàn thành 100%)

| Hạng mục | Tệp tin thực tế | Trạng thái | Bằng chứng kiểm thử |
|:---|:---|:---:|:---|
| **Cấu hình & Base URL** | `Config/config.php` | Hoàn thành | Đã kiểm tra qua `curl.exe http://localhost/PHP_nang_cao/book_store_online/`, nhận diện đúng domain và subfolder |
| **Kết nối CSDL PDO Singleton** | `Core/Database.php` | Hoàn thành | Đã kết nối thành công tới database `book_store`, lấy đúng 16 bảng qua Prepared Statements |
| **Hệ thống Trợ giúp (Helper)** | `Core/Helper.php` | Hoàn thành | Đã test các hàm `format_currency()`, `status_badge()`, `payment_status_badge()`, `flash()`, `csrf_field()` |
| **Bộ Định Tuyến (Router)** | `Core/App.php` | Hoàn thành | Ánh xạ toàn bộ friendly routes (`dang-nhap`, `dang-ky`, `tai-khoan`, `so-dia-chi`, `admin`), test HTTP 200/302 chuẩn |
| **Bảo Mật & Phân Quyền (Auth)** | `Core/Auth.php` | Hoàn thành | Xác thực mật khẩu bcrypt, phát hiện khóa tài khoản theo thời gian thực và tự động thu hồi session |
| **Base Model & Base Controller** | `Core/Model.php`, `Core/Controller.php` | Hoàn thành | Hỗ trợ Transaction (`beginTransaction`, `commit`, `rollback`), render view kèm layout |
| **Model Người dùng & Địa chỉ** | `Models/User.php`, `Models/Address.php` | Hoàn thành | CRUD địa chỉ, tự động duy trì duy nhất 1 địa chỉ mặc định bằng Transaction |
| **Model Thể loại & Giỏ hàng** | `Models/Category.php`, `Models/Cart.php` | Hoàn thành | Nạp danh mục thể loại lên Mega Menu, đếm giỏ hàng trên Header |
| **Client Layout chuẩn Athenaeum** | `Views/layout/main.php` | Hoàn thành | Header 3 tầng (Top bar, Main header, Mega menu), Toast notification, Footer 5 cột |
| **Admin Layout chuẩn Athenaeum** | `Views/layout/admin.php` | Hoàn thành | Sidebar cố định Navy, Topbar, Profile chip, bảo vệ bởi `Auth::requireAdmin()` |
| **Đăng Nhập & Đăng Ký** | `Controllers/AuthController.php`, `Views/client/auth/*` | Hoàn thành | Đăng nhập/đăng ký hoạt động mượt mà, có hộp tài khoản demo 1 chạm cho admin / khách |
| **Hồ Sơ Độc Giả & Sổ Địa Chỉ** | `Controllers/AccountController.php`, `Views/client/account/*` | Hoàn thành | Cập nhật profile, đổi mật khẩu, thêm/sửa/xóa địa chỉ qua modal popup |
| **Trang 404 & Trang Chủ Mở Đầu** | `Views/client/404.php`, `Views/client/home.php` | Hoàn thành | Giao diện hiển thị trang nhã, không gãy vỡ layout |

---

### Giai đoạn 2: Phân Hệ Khám Phá Sách, Tìm Kiếm, Bộ Lọc & Đánh Giá (Hoàn thành 100%)

| Hạng mục | Tệp tin thực tế | Trạng thái | Bằng chứng kiểm thử |
|:---|:---|:---:|:---|
| **Model Sách (`Book`)** | `Models/Book.php` | Hoàn thành | - `getBooks()`: Lấy sách đang bán kèm tác giả, thể loại, NXB, điểm sao trung bình.<br>- `getBestsellers()`: Sách bán chạy theo số lượng đã bán.<br>- `getFlashDeals()`: Sách giá tốt giờ vàng.<br>- `getDetail()`: Chi tiết sách kèm mảng `tac_gia_list` và thông số NXB.<br>- `getRelatedBooks()`: Sách liên quan cùng thể loại.<br>- `filterBooks()`: Lọc đa tiêu chí kết hợp subquery `HAVING` đếm chính xác phân trang. |
| **Model Tác Giả (`Author`)** | `Models/Author.php` | Hoàn thành | Lấy toàn bộ tác giả, tác giả nổi bật (`getFeatured()`) phục vụ trang chủ & bộ lọc. |
| **Model Nhà Xuất Bản (`Publisher`)** | `Models/Publisher.php` | Hoàn thành | Lấy danh sách NXB phục vụ bộ lọc tìm kiếm và chi tiết sách. |
| **Model Đánh Giá (`Review`)** | `Models/Review.php` | Hoàn thành | - `getByBookId()`: Lấy nhận xét đã duyệt `hien_thi`.<br>- `getRatingSummary()`: Tính điểm trung bình và tỷ lệ % từng mức 1-5 sao.<br>- `hasUserReviewed()` & `hasUserPurchased()`: Ràng buộc độc giả chỉ được đánh giá sách sau khi mua hàng thành công.<br>- `getFeaturedReviews()`: Đánh giá tiêu biểu 4-5 sao đưa ra trang chủ. |
| **Trang Chủ Hoàn Chỉnh (Home View & Controller)** | `Controllers/HomeController.php`, `Views/client/home.php` | Hoàn thành | - Hero Mosaic Banner với đồng hồ đếm ngược JavaScript.<br>- Khối 4 cam kết chất lượng.<br>- Danh mục sách nổi bật đếm số đầu sách.<br>- Sách Giờ Vàng (Flash Deals) có thanh tiến trình số lượng tồn kho.<br>- Sách Bán Chạy Trong Tuần phân chia theo Tabs thể loại (`#bestseller-tabs`).<br>- Khối Tuyển tập Văn minh Đông Á & Tác giả tiêu biểu trong tháng.<br>- Cảm nhận độc giả tiêu biểu từ bảng `danh_gia`. |
| **Trang Tìm Kiếm & Lọc Đa Tiêu Chí** | `Controllers/BookController.php@index`, `Views/client/catalog.php` | Hoàn thành | - Sidebar lọc: Thể loại, Tác giả, NXB, Khoảng giá (Presets + Min/Max), Số sao (3+, 4+, 5 sao), Còn hàng.<br>- Toolbar sắp xếp: Mới nhất, Bán chạy, Giá tăng/giảm, Đánh giá cao.<br>- Grid sách tỷ lệ bìa 2:3, huy hiệu Chuẩn NXB, giá tiết kiệm.<br>- Thanh phân trang động lưu giữ query params trên URL.<br>- Empty state hiển thị khi không tìm thấy kết quả. |
| **Trang Chi Tiết Sách & Nhận Xét** | `Controllers/BookController.php@detail`, `Views/client/detail.php` | Hoàn thành | - Khung sách 2:3, thông số NXB, năm xuất bản, tồn kho, bộ chọn số lượng (+ / -), nút Thêm giỏ hàng / Mua ngay.<br>- 3 Tabs chuyển đổi mượt: Mô tả nội dung, Thông tin tác giả, Thông số kỹ thuật.<br>- Điểm đánh giá trung bình & Biểu đồ thanh tỷ lệ 1-5 sao.<br>- Form gửi đánh giá: Tự động phát hiện trạng thái đăng nhập & đã mua hàng, lưu `cho_duyet`.<br>- Danh sách đánh giá độc giả đã kiểm duyệt. |
| **Xử Lý Gửi Đánh Giá** | `Controllers/BookController.php@addReview` | Hoàn thành | Kiểm tra đăng nhập, kiểm tra đơn hàng hoàn thành, chặn đánh giá trùng lặp 1 khách/sách. |

---

## 2. NHỮNG VIỆC ĐÃ TRIỂN KHAI NHƯNG CẦN KIỂM THỬ MỞ RỘNG
- **Admin Dashboard Layout (`Views/admin/dashboard.php`):** Hiện đã dựng khung và 4 Bento KPI cards nạp số liệu từ bảng `nguoi_dung`, `sach`, `don_hang`. Sẽ hoàn thiện biểu đồ doanh thu SVG và bảng đơn hàng mới cần duyệt ở Giai đoạn 4.
- **Nút Thêm Giỏ Hàng & Mua Ngay ở Client:** Các form POST trỏ đến `/gio-hang/them` đã có sẵn các trường `sach_id`, `so_luong`. Sẽ được kết nối xử lý trọn vẹn trong Giai đoạn 3 khi xây dựng `CartController`.

---

## 3. CÁC LỖI & VẤN ĐỀ ĐANG TỒN TẠI
- **Lệnh Git trong môi trường PowerShell:** Biến môi trường PATH của hệ điều hành hiện tại chưa bao gồm đường dẫn `git.exe`. Mọi thay đổi được kiểm soát trực tiếp trên hệ thống tệp tin local và ghi nhận đầy đủ qua các tài liệu bàn giao `PROJECT_CONTEXT.md`, `PROGRESS.md`, `TASKS.md`.
- **Tệp tin rác/tạm thời:** Đã dọn dẹp sạch sẽ, không có tệp tin thừa trong codebase.

---

## 4. NHỮNG VIỆC CHƯA HOÀN THÀNH (THEO LỘ TRÌNH KẾ TIẾP)

### Giai đoạn 3: Giỏ Hàng, Đặt Hàng & Quản Lý Đơn Hàng Độc Giả (Bắt đầu ngay)
- **Model Mã Giảm Giá & Đơn Hàng:** `Models/Coupon.php`, `Models/Order.php`.
- **Giỏ hàng (`CartController`, `Views/client/cart.php`):** Thêm sách vào giỏ (kiểm tra tồn kho), cập nhật số lượng (+ / -), xóa món, tính tổng tiền.
- **Áp dụng voucher (`ma_giam_gia`):** Kiểm tra thời hạn, giá trị đơn tối thiểu, giới hạn số lượt sử dụng.
- **Thanh toán & Đặt hàng (`CheckoutController`, `Views/client/checkout.php`):**
  - Chọn địa chỉ từ bảng `dia_chi` hoặc nhập địa chỉ mới.
  - Chọn phương thức vận chuyển và phương thức thanh toán (COD hoặc VNPAY/Chuyển khoản).
  - Sử dụng **Database Transaction** thực hiện chuỗi giao tác: tạo `don_hang`, tạo `chi_tiet_don_hang`, trừ `so_luong_ton` trong `sach`, tăng `da_dung` của `ma_giam_gia`, tạo bản ghi `thanh_toan`, ghi log vào `lich_su_don_hang`, xóa sạch giỏ hàng.
- **Quản lý đơn hàng độc giả (`AccountController@orders`, `Views/client/orders.php`):**
  - Danh sách đơn hàng phân theo Tabs trạng thái (`cho_xac_nhan`, `da_xac_nhan`, `dang_giao`, `hoan_thanh`, `da_huy`).
  - Lộ trình vận đơn với **Timeline Stepper 4 bước** trực quan dựa trên bảng `lich_su_don_hang`.
  - Hủy đơn hàng khi còn ở trạng thái `cho_xac_nhan` (hoàn tồn kho, hoàn lượt voucher, ghi log hủy).

### Giai đoạn 4: Toàn Bộ Phân Hệ Quản Trị (Admin Portal)
- Hoàn thiện Admin Dashboard: Biểu đồ doanh thu SVG, biểu đồ phân bổ thể loại, cảnh báo kho.
- CRUD Sách, Tác giả, Thể loại, Nhà xuất bản (hỗ trợ upload ảnh bìa `uploads/books/`, gán nhiều tác giả qua `sach_tac_gia`, Modal `#modal-add-book`).
- Quản lý Tồn kho & Phiếu kho (`phieu_kho`): Lập phiếu kho qua Modal `#modal-quick-slip` (nhập, xuất, điều chỉnh) bằng Transaction.
- Quản lý đơn hàng: Đổi trạng thái, tự động ghi log vào `lich_su_don_hang`, hoàn kho khi hủy đơn.
- Quản lý Mã giảm giá & Quản lý Người dùng (phân quyền, khóa/mở khóa tài khoản).
- Quản lý & Duyệt Đánh giá (`danh_gia`): Duyệt hiển thị (`hien_thi`) hoặc ẩn (`an`).

---

## 5. CÁC BƯỚC TIẾP THEO THEO THỨ TỰ ƯU TIÊN

1. **Bước 1 (Ưu tiên cao nhất - Mở đầu Giai đoạn 3):** Xây dựng `Models/Coupon.php` và `Controllers/CartController.php` (các hành động `index`, `add`, `update`, `remove`, `clear`, `applyCoupon`).
2. **Bước 2:** Chuyển đổi giao diện `gi_h_ng_thanh_to_n_bookstore/code.html` sang `Views/client/cart.php` chuẩn phong cách Athenaeum Modern.
3. **Bước 3:** Xây dựng `Controllers/CheckoutController.php` và chuyển đổi `thanh_toan_x_c_nh_n_n_h_ng_bookstore/code.html` sang `Views/client/checkout.php`, lập trình Database Transaction tạo đơn hàng an toàn.
4. **Bước 4:** Xây dựng giao diện tra cứu và theo dõi đơn hàng với Timeline Stepper 4 bước tại `Views/client/account/orders.php` tích hợp vào `AccountController@orders`.

---

## 6. HƯỚNG DẪN CÀI ĐẶT, CHẠY & KIỂM THỬ DỰ ÁN

### 1. Môi trường yêu cầu
- XAMPP đang chạy dịch vụ **Apache** (cổng 80) và **MySQL** (cổng 3306).
- Thư mục dự án: `c:\xampp\htdocs\PHP_nang_cao\book_store_online\`.

### 2. CSDL MySQL
- Tên CSDL: `book_store`.
- Đã import sẵn tệp `database.sql` gồm 16 bảng và dữ liệu mẫu chuẩn UTF-8.

### 3. URL truy cập kiểm thử
- Trang chủ: `http://localhost/PHP_nang_cao/book_store_online/`
- Danh mục & Tìm kiếm sách: `http://localhost/PHP_nang_cao/book_store_online/tim-kiem`
- Chi tiết tác phẩm mẫu: `http://localhost/PHP_nang_cao/book_store_online/chi-tiet/1`
- Đăng nhập: `http://localhost/PHP_nang_cao/book_store_online/dang-nhap`
- Đăng ký: `http://localhost/PHP_nang_cao/book_store_online/dang-ky`
- Sổ địa chỉ: `http://localhost/PHP_nang_cao/book_store_online/tai-khoan/so-dia-chi`
- Tổng quan quản trị: `http://localhost/PHP_nang_cao/book_store_online/admin`

### 4. Tài khoản kiểm thử mẫu
- **Quản Trị Viên (Admin):** Email: `admin@bookstore.vn` | Mật khẩu: `123456`
- **Khách Hàng (Độc Giả 1):** Email: `nguyenvanan@gmail.com` | Mật khẩu: `123456`
- **Khách Hàng (Độc Giả 2):** Email: `tranthibinh@gmail.com` | Mật khẩu: `123456`
*(Trang đăng nhập có nút 1 chạm để tự động điền sẵn tài khoản mẫu tiện lợi)*
