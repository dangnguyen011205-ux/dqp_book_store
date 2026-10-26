# BÁO CÁO TIẾN ĐỘ DỰ ÁN (PROJECT PROGRESS)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Cập nhật:** Hoàn thành 100% Giai đoạn 3 / Sẵn sàng bước vào Giai đoạn 4 (Admin Portal)  
> **Thời điểm cập nhật:** 26/10/2026  

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

### Giai đoạn 3: Giỏ Hàng, Mã Giảm Giá, Đặt Hàng & Quản Lý Đơn Hàng Độc Giả (Hoàn thành 100%)

| Hạng mục | Tệp tin thực tế | Trạng thái | Bằng chứng kiểm thử |
|:---|:---|:---:|:---|
| **Model Mã Giảm Giá (`Coupon`)** | `Models/Coupon.php` | Hoàn thành | - `validateCoupon()`: Kiểm tra tồn tại, thời hạn, đơn tối thiểu, số lượt tối đa, tính số tiền giảm theo % hoặc tiền mặt.<br>- Đã test tự động với mã hợp lệ `WELCOME2026`, đơn dưới tối thiểu, mã không tồn tại. |
| **Model Giỏ Hàng Nâng Cao (`Cart`)** | `Models/Cart.php` | Hoàn thành | - `getItemCount()`, `getItems()`: Tương thích cả khách vãng lai (`$_SESSION['cart']`) và thành viên (`gio_hang`, `chi_tiet_gio_hang`).<br>- `addItem()`: Kiểm tra tồn kho thời gian thực, chặn thêm quá số lượng tồn.<br>- `mergeSessionCart()`: Tự động gộp giỏ hàng session vào tài khoản CSDL khi đăng nhập (`AuthController::login`). |
| **Model Đơn Hàng Giao Tác (`Order`)** | `Models/Order.php` | Hoàn thành | - `createOrder()`: Khóa dòng kiểm tra tồn kho (`SELECT ... FOR UPDATE`), trừ tồn kho, tạo `don_hang`, tạo `chi_tiet_don_hang`, tăng `da_dung` của voucher, tạo `thanh_toan` (COD/VNPAY), ghi log `lich_su_don_hang`, xóa giỏ hàng bằng Database Transaction.<br>- `getUserOrders()`: Danh sách đơn hàng theo tab trạng thái.<br>- `getOrderDetail()`: Chi tiết đơn hàng, sản phẩm, và toàn bộ lịch sử vận chuyển.<br>- `cancelOrder()`: Hủy đơn an toàn khi đơn ở `cho_xac_nhan`, hoàn trả tồn kho, giảm số lượt dùng voucher, ghi log hủy kèm lý do. |
| **Bộ Điều Khiển Giỏ Hàng (`CartController`)** | `Controllers/CartController.php` | Hoàn thành | Các actions: `index`, `add` (hỗ trợ Mua Ngay và Thêm Giỏ), `update` (+ / -), `remove`, `clear`, `applyCoupon`, `removeCoupon`. |
| **Bộ Điều Khiển Thanh Toán (`CheckoutController`)** | `Controllers/CheckoutController.php` | Hoàn thành | Các actions: `index`, `process` (chọn địa chỉ có sẵn từ sổ địa chỉ hoặc nhập mới, chọn vận chuyển tiêu chuẩn / hỏa tốc, thanh toán COD / VNPAY), `success` (hiển thị trang xác nhận). |
| **Quản Lý Đơn Hàng Độc Giả (`AccountController`)** | `Controllers/AccountController.php` | Hoàn thành | Các actions: `orders()` (lọc theo tabs trạng thái, đếm số lượng), `orderDetail($id)`, `cancelOrder($id)`. |
| **Giao Diện Giỏ Hàng** | `Views/client/cart.php` | Hoàn thành | Dựa trên `gi_h_ng_thanh_to_n_bookstore/code.html`: Stepper tiến độ, danh sách sách, bộ chọn số lượng, xóa món, xóa tất cả, form nhập voucher, danh sách mã có sẵn gợi ý, tóm tắt chi phí, nút Tiếp tục mua hàng và Tiến hành đặt hàng. |
| **Giao Diện Thanh Toán** | `Views/client/checkout.php` | Hoàn thành | Dựa trên `thanh_to_n_x_c_nh_n_n_h_ng_bookstore/code.html`: Chọn địa chỉ có sẵn kèm badge mặc định, form nhập địa chỉ mới, chọn phương thức giao hàng, chọn phương thức thanh toán COD/VNPAY, sticky tóm tắt đơn hàng. |
| **Giao Diện Đặt Hàng Thành Công** | `Views/client/checkout_success.php` | Hoàn thành | Hiển thị mã đơn hàng `#BK-...`, bento grid chi tiết ngày đặt, trạng thái, thanh toán, địa chỉ nhận hàng, danh sách sách, nút theo dõi hành trình đơn hàng. |
| **Giao Diện Quản Lý Đơn Hàng** | `Views/client/account/orders.php` | Hoàn thành | Dựa trên `qu_n_l_n_h_ng_bookstore/code.html`: Tích hợp sidebar độc giả, thanh Tabs trạng thái kèm số lượng đếm, thẻ đơn hàng tóm tắt, nút Theo dõi vận đơn, modal popup hủy đơn hàng. |
| **Giao Diện Chi Tiết Đơn Hàng & Timeline Stepper** | `Views/client/account/order_detail.php` | Hoàn thành | Dựa trên `qu_n_l_theo_d_i_n_h_ng_bookstore/code.html`: **Timeline Stepper 4 bước** trực quan theo Design System Athenaeum Modern, hành trình vận đơn từ `lich_su_don_hang`, thông tin giao nhận, thanh toán, danh sách sản phẩm, modal hủy đơn kèm lý do. |
| **Kiểm Thử Tích Hợp Tự Động (Integration Test)** | `scratch/test_stage3.php` | Hoàn thành | 14 test cases chạy qua PHP CLI đạt kết quả 100% Passed (Coupon, Cart, Transaction tạo đơn, trừ tồn kho, Timeline, Hủy đơn, hoàn trả tồn kho). |
| **Kiểm Thử Thực Tế Trình Duyệt (Browser E2E)** | Browser Subagent | Hoàn thành | Đã quay video và chụp ảnh màn hình luồng thực tế: Đăng nhập -> Thêm giỏ -> Áp dụng mã `WELCOME2026` (-23.700đ) -> Đặt hàng COD -> Trang thành công `#BK-7` -> Danh sách đơn hàng -> Chi tiết đơn hàng với Timeline Stepper 4 bước -> Hủy đơn hàng và cập nhật huy hiệu `ĐÃ HỦY`. |
| **Bộ Kiểm Thử Toàn Diện Hệ Thống (Master Regression)** | `scratch/test_all_features.php` & `lint_all.php` | Hoàn thành | **89/89 Ca kiểm thử đạt 100%**: Quét cú pháp 42/42 file PHP, kiểm tra CSDL 16 bảng, Auth bcrypt, cập nhật profile, giao tác địa chỉ mặc định, đếm sách thể loại, bộ lọc đa tiêu chí, rating summary, transaction đặt hàng & hủy đơn hoàn kho, kiểm tra 21 HTTP routes (khách, member, admin). |

---

## 2. NHỮNG VIỆC ĐÃ TRIỂN KHAI NHƯNG CẦN KIỂM THỬ MỞ RỘNG
- **Admin Dashboard Layout (`Views/admin/dashboard.php`):** Khung tổng quan và 4 Bento KPI cards đã nạp số liệu thực từ CSDL. Sẽ hoàn thiện biểu đồ doanh thu SVG, biểu đồ phân bổ thể loại, và danh sách đơn hàng cần xử lý trong Giai đoạn 4.
- **Thanh toán VNPAY Online Sandbox:** Đã sẵn sàng bản ghi và mã giao dịch giả lập, sẵn sàng cấu hình URL Sandbox VNPAY khi đưa vào production.

---

## 3. CÁC LỖI & VẤN ĐỀ ĐÃ KHẮC PHỤC
1. **Lỗi gọi tên hàm `Address::findById()` trong CheckoutController:**
   - *Nguyên nhân:* Model `Address` ban đầu chỉ khai báo `getById($id, $userId)`.
   - *Khắc phục:* Đã bổ sung alias `findById()` trong `Models/Address.php` và đồng bộ lời gọi hàm trong `CheckoutController.php`.
2. **Lệnh `curl` trong PowerShell:**
   - *Nguyên nhân:* PowerShell gán alias `curl` sang `Invoke-WebRequest`.
   - *Khắc phục:* Sử dụng trực tiếp `curl.exe` cho mọi request kiểm thử HTTP.
3. **Phân trang khi lọc số sao trong `Book::filterBooks()`:**
   - *Khắc phục:* Tách count query thành subquery khi có mệnh đề `HAVING`, đảm bảo phân trang đồng bộ 100%.
4. **Xử lý đường dẫn không tồn tại (Fallback 404 Router):**
   - *Nguyên nhân:* Trong `Core/App.php`, các URL lạ không khớp route và không có Controller tương ứng trước đây bị giữ nguyên controller mặc định là `HomeController`, âm thầm trả về HTTP 200 trang chủ.
   - *Khắc phục:* Bổ sung nhánh `else { $this->render404(); }` đảm bảo mọi URL sai đều trả về đúng mã HTTP 404 và giao diện `Views/client/404.php`.
5. **Đồng bộ Route Hồ sơ cá nhân (`tai-khoan/ho-so`):**
   - *Khắc phục:* Bổ sung `'tai-khoan/ho-so' => ['AccountController', 'profile']` vào mảng `$routes` trong `Core/App.php`.
6. **Cảnh báo tĩnh từ Extension IDE (DevSense / Intelephense):**
   - *Khắc phục:* Thêm cấu hình `.vscode/settings.json` để ẩn các cảnh báo giả về biến template được trích xuất qua `extract($data)` trong mô hình PHP MVC thuần.
   - *Cam kết:* Tuân thủ nghiêm ngặt chỉ dẫn của người dùng: Giữ nguyên tệp `order_detail.php` không sửa đổi.

---

## 4. NHỮNG VIỆC CHƯA HOÀN THÀNH (GIAI ĐOẠN 4 - ADMIN PORTAL)

### Giai đoạn 4: Toàn Bộ Phân Hệ Quản Trị (Admin Portal)
- **1. Admin Dashboard Tương Tác:**
  - Hoàn thiện 4 Bento KPI cards (Doanh thu thực, Đơn hàng mới, Tổng đầu sách, Độc giả đăng ký).
  - Biểu đồ doanh thu SVG (doanh thu theo tháng/tuần).
  - Bảng cảnh báo tồn kho (sách có `so_luong_ton <= 5`).
  - Danh sách đơn hàng mới cần xử lý (`cho_xac_nhan`).
- **2. Quản Lý Sách & Danh Mục (CRUD Sách, Thể loại, Tác giả, NXB):**
  - Danh sách sách với bộ lọc quản trị, tìm kiếm theo từ khóa/danh mục.
  - Modal thêm sách `#modal-add-book` (nhập thông tin, tải ảnh bìa lên `Public/uploads/books/`, gán nhiều tác giả qua bảng `sach_tac_gia`).
  - Sửa thông tin sách và chuyển đổi trạng thái đang bán (`dang_ban = 1/0`).
- **3. Quản Lý Kho & Phiếu Kho (`phieu_kho`):**
  - Bảng theo dõi tồn kho, cảnh báo sách sắp hết.
  - Modal lập phiếu kho nhanh `#modal-quick-slip` (Phiếu nhập `nhap`, Phiếu xuất `xuat`, Điều chỉnh `kiem_ke`).
  - Tự động cộng/trừ tồn kho trong `sach` bằng Database Transaction khi lưu phiếu kho.
- **4. Quản Lý Đơn Hàng Admin:**
  - Bảng danh sách đơn hàng có bộ lọc trạng thái, tìm kiếm theo mã đơn / tên khách.
  - Modal/Trang chi tiết đơn hàng: Cập nhật trạng thái (`cho_xac_nhan` -> `da_xac_nhan` -> `dang_giao` -> `hoan_thanh` / `da_huy`).
  - Tự động ghi log vào bảng `lich_su_don_hang` và hoàn trả kho nếu hủy đơn từ phía admin.
- **5. Quản Lý & Duyệt Đánh Giá (`danh_gia`):**
  - Danh sách đánh giá chờ duyệt (`cho_duyet`).
  - Nút duyệt hiển thị (`hien_thi`) hoặc ẩn vi phạm (`an`).
- **6. Quản Lý Mã Giảm Giá & Người Dùng:**
  - Thêm, sửa, xóa, vô hiệu hóa mã giảm giá (`ma_giam_gia`).
  - Quản lý danh sách người dùng (`nguoi_dung`): Phân quyền (`admin`/`khach_hang`), khóa hoặc mở khóa tài khoản (`hoat_dong`/`khoa`).

---

## 5. HƯỚNG DẪN CÀI ĐẶT, CHẠY & KIỂM THỬ DỰ ÁN

### 1. Môi trường yêu cầu
- XAMPP đang chạy dịch vụ **Apache** (cổng 80) và **MySQL** (cổng 3306).
- Thư mục dự án: `c:\xampp\htdocs\PHP_nang_cao\book_store_online\`.
- Tên CSDL: `book_store` (đã nạp sẵn `database.sql`).

### 2. URL kiểm thử các tính năng
- **Trang chủ:** `http://localhost/PHP_nang_cao/book_store_online/`
- **Danh mục & Tìm kiếm:** `http://localhost/PHP_nang_cao/book_store_online/tim-kiem`
- **Chi tiết sách:** `http://localhost/PHP_nang_cao/book_store_online/chi-tiet/1`
- **Giỏ hàng:** `http://localhost/PHP_nang_cao/book_store_online/gio-hang`
- **Thanh toán:** `http://localhost/PHP_nang_cao/book_store_online/thanh-toan`
- **Đơn hàng của tôi:** `http://localhost/PHP_nang_cao/book_store_online/tai-khoan/don-hang`
- **Chi tiết đơn hàng:** `http://localhost/PHP_nang_cao/book_store_online/tai-khoan/don-hang/chi-tiet/7`
- **Sổ địa chỉ:** `http://localhost/PHP_nang_cao/book_store_online/tai-khoan/so-dia-chi`
- **Admin Dashboard:** `http://localhost/PHP_nang_cao/book_store_online/admin`

### 3. Tài khoản mẫu
- **Quản Trị Viên (Admin):** Email: `admin@bookstore.vn` | Mật khẩu: `123456`
- **Độc Giả 1:** Email: `nguyenvanan@gmail.com` | Mật khẩu: `123456`
- **Độc Giả 2:** Email: `tranthibinh@gmail.com` | Mật khẩu: `123456`
