# BÁO CÁO TIẾN ĐỘ DỰ ÁN (PROJECT PROGRESS)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Cập nhật:** Hoàn thành 100% Giai đoạn 1, 2, 3, 4 (Client & Admin Portal)  
> **Thời điểm cập nhật:** 31/10/2026  

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

### Giai đoạn 4: Toàn Bộ Phân Hệ Quản Trị (Admin Portal - Đang Triển Khai)

| Hạng mục | Tệp tin thực tế | Trạng thái | Bằng chứng kiểm thử |
|:---|:---|:---:|:---|
| **Bảng Điều Khiển Quản Trị (Interactive Dashboard)** | `Controllers/AdminDashboardController.php`, `Views/admin/dashboard.php` | Hoàn thành | - 4 Bento KPI Cards phân tích số liệu thực (Doanh thu thực đơn `hoan_thanh`, Tổng đơn hàng kèm sub-badge chờ duyệt, Sách đã bán, Tồn kho hệ thống).<br>- Biểu đồ Doanh Thu SVG Area & Column Chart với hiệu ứng Glow, các cột volume, mốc đỉnh cao hôm nay.<br>- Biểu đồ Phân Bổ Thể Loại SVG Donut Chart tính toán tỷ trọng chính xác từ CSDL.<br>- Bảng Đơn Hàng Mới Cần Xử Lý (5 đơn mới nhất kèm nút duyệt/xử lý nhanh).<br>- Top 5 Tựa Sách Bán Chạy Nhất (kèm ảnh bìa, tác giả, thể loại, doanh thu, tồn kho).<br>- Widget Duyệt Đánh Giá Khách Hàng (5 sao vàng, trích dẫn phản hồi) & Cảnh Báo Tồn Kho Thấp.<br>- Kiểm thử tự động `test_admin.php` và xác minh thực tế qua Browser Subagent. |
| **Quản Lý Sách & Quản Lý Kho (Books & Inventory)** | `Controllers/AdminBookController.php`, `Controllers/AdminInventoryController.php`, `Models/Book.php`, `Models/InventorySlip.php`, `Views/admin/books/index.php` | Hoàn thành | - 4 Bento KPI cards (Tổng đầu sách, Tổng tồn kho, Cảnh báo sắp hết hàng &le; 10 cuốn, Giá trị tồn kho ước tính).<br>- Danh sách sách với bộ lọc đa tiêu chí (keyword, category, status: all, `dang_ban`, `sap_het`, `ngung_ban`) và phân trang.<br>- Modal `#modal-add-book` hỗ trợ upload ảnh bìa `Public/uploads/books/`, gán nhiều tác giả, tạo phiếu nhập kho đầu kỳ tự động.<br>- Modal `#modal-edit-book` nạp dữ liệu động AJAX JSON, cập nhật giá, thông tin xuất bản và trạng thái.<br>- Modal `#modal-quick-slip` và phân hệ Phiếu kho luân chuyển xử lý bằng Database Transaction với khóa dòng `SELECT ... FOR UPDATE`, tự động tăng/giảm tồn kho `sach.so_luong_ton`, kiểm tra tồn kho không âm khi xuất/giảm.<br>- Kiểm thử tự động 16/16 Passed (`test_admin_books_inventory.php`) và xác minh giao diện thực tế qua Browser Subagent. |
| **Quản Lý Đơn Hàng Admin (Admin Order Management)** | `Controllers/AdminOrderController.php`, `Models/Order.php`, `Views/admin/orders/index.php` | Hoàn thành | - 4 Bento KPI cards (Tổng đơn & Doanh thu hôm nay, Chờ xác nhận & Tồn đọng > 2 giờ, Đang vận chuyển, Tỷ lệ giao thành công & Hoàn tất trong tháng).<br>- Tabs trạng thái 6 mục kèm counter badges (`tat_ca`, `cho_xac_nhan`, `da_xac_nhan`, `dang_giao`, `hoan_thanh`, `da_huy`).<br>- Bộ lọc đa năng (mã đơn `#BK-...`, tên khách, SĐT, địa chỉ, khoảng ngày đặt, phương thức COD/VNPAY).<br>- Bảng dữ liệu hiển thị ảnh bìa, tóm tắt sách, giá trị thanh toán, dropdown đổi trạng thái nhanh.<br>- Slide-over Drawer `#orderDrawer`: Chi tiết người nhận, danh sách sách, tổng chi phí, Timeline nhật ký xử lý từ `lich_su_don_hang`, nút hành động theo chu kỳ.<br>- Database Transaction an toàn với khóa dòng `SELECT ... FOR UPDATE`: Tự động hoàn tồn kho `sach.so_luong_ton`, giảm số lượt dùng voucher `ma_giam_gia.da_dung`, hủy thanh toán khi hủy đơn; tự động đánh dấu thanh toán COD `thanh_cong` khi hoàn tất; ghi log lịch sử đơn hàng.<br>- Toast notification tức thì `#statusToast`.<br>- Kiểm thử tự động 47/47 Passed (`scratch/test_admin_orders.php`) và kiểm thử trực quan Browser Subagent. |
| **Quản Lý & Duyệt Đánh Giá (Review Moderation)** | `Controllers/AdminReviewController.php`, `Models/Review.php`, `Views/admin/reviews/index.php` | Hoàn thành | - 4 Bento KPI cards (Đánh giá Chờ Duyệt & cảnh báo duyệt gấp, Đang Hiển Thị & tỷ lệ công khai, Điểm Toàn Sàn 5 sao & tỷ lệ hài lòng 4-5 sao, Đã Ẩn / Vi Phạm).<br>- Tabs trạng thái 4 mục (`tat_ca`, `cho_duyet`, `hien_thi`, `an`) kèm counter badges.<br>- Bộ lọc sao (Tất cả, 5★, 4★, 3★, 2★, 1★), tìm kiếm tức thì theo độc giả, email, sách, nội dung; sắp xếp linh hoạt.<br>- Floating Bulk Toolbar `#bulkToolbar`: Thao tác hàng loạt (Duyệt hiển thị, Ẩn đánh giá, Xóa vĩnh viễn) cho nhiều bản ghi cùng lúc.<br>- Bảng dữ liệu: Avatar initials, badge "Đã mua hàng", bìa sách, sao vàng, nội dung nhận xét, nút thao tác duyệt/ẩn/xóa.<br>- Phản hồi AJAX tức thì kèm Toast notification `#statusToast`.<br>- Kiểm thử tự động 35/35 Passed (`scratch/test_admin_reviews.php`) và kiểm thử trực quan Browser Subagent. |
| **Quản Lý Thể Loại, Tác Giả & Nhà Xuất Bản (Metadata Management)** | `Controllers/AdminCategoryController.php`, `Controllers/AdminAuthorController.php`, `Controllers/AdminPublisherController.php`, `Models/Category.php`, `Models/Author.php`, `Models/Publisher.php`, `Views/admin/categories/`, `Views/admin/authors/`, `Views/admin/publishers/` | Hoàn thành | - Thanh Sub-navigation tabs chuyển đổi mượt mà 3 phân hệ Metadata (`admin/the-loai`, `admin/tac-gia`, `admin/nha-xuat-ban`) kèm counter badges.<br>- **Phân hệ Thể Loại Sách (`admin/the-loai`)**: 3 Bento KPI cards, tìm kiếm, Modal thêm/sửa `#categoryModal`, xóa an toàn chặn vi phạm ràng buộc toàn vẹn khi đang có sách liên kết.<br>- **Phân hệ Tác Giả (`admin/tac-gia`)**: 3 Bento KPI cards, tìm kiếm, avatar chữ cái, Modal thêm/sửa `#authorModal`, xóa an toàn chặn vi phạm khi đang có sách.<br>- **Phân hệ Nhà Xuất Bản (`admin/nha-xuat-ban`)**: 3 Bento KPI cards, tìm kiếm, thông tin địa chỉ & liên hệ, Modal thêm/sửa `#publisherModal`, xóa an toàn chặn vi phạm khi đang có sách.<br>- Kiểm thử tự động 36/36 Passed (`scratch/test_admin_metadata.php`) và kiểm thử trực quan trình duyệt qua Browser Subagent. |
| **Quản Lý Mã Giảm Giá (Coupon Management)** | `Controllers/AdminCouponController.php`, `Models/Coupon.php`, `Views/admin/coupons/index.php` | Hoàn thành | - 4 Bento KPI Cards: Tổng voucher, Đang hiệu lực, Hết hạn / Hết lượt, Tổng lượt dùng & Tiền tiết kiệm cho khách.<br>- Bộ lọc đa tiêu chí: Từ khóa mã voucher, Loại giảm (`phan_tram`/`tien_mat`), Trạng thái (`con_han`/`chua_bat_dau`/`het_han`/`het_luot`).<br>- Modal thêm/sửa voucher `#couponModal`: Hỗ trợ tự động đổi đơn vị và giới hạn %, validation chặt chẽ, nạp dữ liệu qua AJAX JSON `admin/ma-giam-gia/chi-tiet/{id}`.<br>- Xóa voucher an toàn kèm cảnh báo số đơn hàng cũ đã sử dụng.<br>- Kiểm thử tự động 44/44 Passed (`scratch/test_admin_coupons.php`) và kiểm thử HTTP Endpoints. |
| **Quản Lý Người Dùng & Phân Quyền (User & Role Management)** | `Controllers/AdminUserController.php`, `Models/User.php`, `Views/admin/users/index.php` | Hoàn thành | - 4 Bento KPI Cards: Tổng người dùng, Khách hàng, Ban quản trị, Tài khoản đang khóa.<br>- Bộ lọc từ khóa, lọc theo vai trò (`khach_hang`/`quan_tri`) và trạng thái (`hoat_dong`/`khoa`).<br>- Bảng dữ liệu hiển thị avatar, email, SĐT, số đơn đã đặt, tổng chi tiêu tích lũy, badge vai trò và trạng thái.<br>- Modal `#addUserModal` thêm mới tài khoản (chặn trùng email, băm Bcrypt, gán quyền Admin/Khách).<br>- Chức năng phân quyền Admin/Khách hàng và Khóa/Mở khóa tài khoản thời gian thực qua AJAX.<br>- Ràng buộc bảo mật tuyệt đối: Chặn Admin tự khóa hoặc tự hạ quyền tài khoản của chính mình.<br>- Modal `#userDetailModal` xem hồ sơ, địa chỉ, đơn hàng gần nhất và Modal `#userEditModal` sửa thông tin/mật khẩu.<br>- Kiểm thử tự động 74/74 Passed (`scratch/test_admin_users.php`) và kiểm thử HTTP Endpoints. |

---

## 2. NHỮNG VIỆC ĐÃ TRIỂN KHAI NHƯNG CẦN KIỂM THỬ MỞ RỘNG
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
7. **Đồng bộ hiển thị ảnh bìa toàn hệ thống (`get_book_cover`):**
   - *Khắc phục:* Bổ sung helper toàn cục `get_book_cover(?string $anhBia)` trong `Core/Helper.php`, tự động ưu tiên ảnh upload cục bộ `Public/uploads/books/`, URL trực tiếp, hoặc fallback ảnh bìa chất lượng cao chuẩn Athenaeum.
8. **Lỗi 404 khi Submit Modal Thêm Thể Loại, Tác Giả, Nhà Xuất Bản (Thiếu dấu `/` ở `baseUrl`):**
   - *Nguyên nhân:* Biến JavaScript `const baseUrl = '<?= base_url() ?>';` trả về `http://localhost/PHP_nang_cao/book_store_online` (không có dấu gạch chéo cuối). Khi JavaScript gán lại action form bằng `baseUrl + 'admin/the-loai/them'`, URL bị ghép dính liền thành `.../book_store_onlineadmin/the-loai/them`, dẫn tới lỗi 404 Not Found từ Apache.
   - *Khắc phục:* Cập nhật thành `const baseUrl = '<?= base_url() ?>/';` trong cả 3 tệp giao diện [Views/admin/categories/index.php](file:///c:/xampp/htdocs/PHP_nang_cao/book_store_online/Views/admin/categories/index.php), [Views/admin/authors/index.php](file:///c:/xampp/htdocs/PHP_nang_cao/book_store_online/Views/admin/authors/index.php) và [Views/admin/publishers/index.php](file:///c:/xampp/htdocs/PHP_nang_cao/book_store_online/Views/admin/publishers/index.php). Đã kiểm thử trình duyệt thực tế xác nhận submit thành công 100%.

---

## 4. TIẾN ĐỘ TỔNG THỂ & HẠNG MỤC MỞ RỘNG
- **Toàn bộ 7/7 Nhiệm vụ của Giai đoạn 4 (Admin Portal)** đã hoàn thành 100% (Dashboard, Sách & Kho, Đơn hàng, Duyệt đánh giá, Metadata Thể loại/Tác giả/NXB, Mã giảm giá, Người dùng & Phân quyền).
- **Hạng mục mở rộng:** Kiểm thử sandbox tích hợp trực tiếp với cổng thanh toán VNPAY Online production khi đưa vào khai thác thương mại.







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
