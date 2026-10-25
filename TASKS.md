# DANH SÁCH NHIỆM VỤ DỰ ÁN (PROJECT TASKS)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Cập nhật:** Hoàn thành Giai đoạn 1 & Giai đoạn 2 / Sẵn sàng Giai đoạn 3  
> **Thời điểm cập nhật:** 24/10/2026  

---

## GIAI ĐOẠN 1: THIẾT LẬP KIẾN TRÚC MVC, BẢO MẬT & SỔ ĐỊA CHỈ (HOÀN THÀNH 100%)
- [x] Khởi tạo cấu trúc thư mục chuẩn MVC (`Config/`, `Core/`, `Controllers/`, `Models/`, `Views/`, `Public/`).
- [x] Cấu hình hệ thống `Config/config.php` với cơ chế tự động nhận diện `BASE_URL` động hỗ trợ XAMPP.
- [x] Xây dựng kết nối CSDL Singleton PDO `Core/Database.php` hỗ trợ Prepared Statements, UTF-8 (`utf8mb4`).
- [x] Xây dựng Base Model `Core/Model.php` và Base Controller `Core/Controller.php`.
- [x] Xây dựng Bộ định tuyến Router `Core/App.php` ánh xạ Friendly Routes và fallback MVC `Controller/Action/Params`.
- [x] Xây dựng Thư viện trợ giúp `Core/Helper.php` (tiền tệ, status badges, flash message, CSRF).
- [x] Xây dựng Quản lý phiên làm việc & phân quyền bảo mật `Core/Auth.php` (xác thực bcrypt, kiểm tra tài khoản khóa theo thời gian thực).
- [x] Xây dựng Layout giao diện Khách hàng `Views/layout/main.php` chuẩn "Athenaeum Modern" (Header 3 tầng, Mega Menu danh mục, Toast notifications, Footer 5 cột).
- [x] Xây dựng Layout giao diện Quản trị `Views/layout/admin.php` (Sidebar Navy cố định, Topbar, Profile chip).
- [x] Xây dựng Phân hệ Đăng nhập / Đăng ký (`Controllers/AuthController.php`, `Views/client/auth/login.php`, `Views/client/auth/register.php`) kèm hộp tài khoản mẫu 1 chạm.
- [x] Xây dựng Quản lý Hồ sơ độc giả & Đổi mật khẩu (`Controllers/AccountController.php@profile`, `Views/client/account/profile.php`).
- [x] Xây dựng Quản lý Sổ địa chỉ nhận hàng (`Controllers/AccountController.php@addresses`, `Views/client/account/addresses.php`) với Transaction duy trì duy nhất 1 địa chỉ mặc định.
- [x] Xây dựng Component Sidebar Độc giả dùng chung (`Views/client/account/_sidebar.php`).
- [x] Xây dựng Trang chủ mở đầu `Views/client/home.php` và trang lỗi 404 `Views/client/404.php`.
- [x] Viết unit test tự động xác thực toàn bộ luồng nghiệp vụ Giai đoạn 1 (`100% Passed`).

---

## GIAI ĐOẠN 2: PHÂN HỆ KHÁM PHÁ SÁCH, TÌM KIẾM, BỘ LỌC & ĐÁNH GIÁ (HOÀN THÀNH 100%)
- [x] Xây dựng Model sách và liên kết: `Models/Book.php`, `Models/Author.php`, `Models/Publisher.php`, `Models/Review.php`.
- [x] Chuyển đổi giao diện Trang chủ hoàn chỉnh từ `trang_ch_bookstore/code.html` sang `Views/client/home.php`:
  - [x] Hero Mosaic Banner với countdown timer JavaScript giờ vàng.
  - [x] Sách Bán Chạy Trong Tuần theo tabs thể loại tương tác (`#bestseller-tabs`).
  - [x] Sách Giờ Vàng (Flash Deals) với thanh tiến trình số lượng tồn kho/đã bán.
  - [x] Danh mục nổi bật & Tác giả tiêu biểu trong tháng.
  - [x] Tuyển tập sách biên tập tinh hoa Đông Á.
  - [x] Khối Đánh giá độc giả tiêu biểu từ CSDL (`trang_thai = 'hien_thi'`).
- [x] Chuyển đổi giao diện Tìm kiếm & Bộ lọc từ `t_m_ki_m_l_c_s_ch_bookstore/code.html` sang `Views/client/catalog.php`:
  - [x] Bộ lọc Sidebar: Thể loại (`the_loai`), Tác giả (`tac_gia`), NXB (`nxb`).
  - [x] Bộ lọc Khoảng giá (Presets: <100k, 100k-200k, 200k-500k, >500k và nhập Min-Max).
  - [x] Lọc theo số sao đánh giá (3+, 4+, 5 sao) và tình trạng còn hàng (`so_luong_ton > 0`).
  - [x] Toolbar sắp xếp (bán chạy, giá tăng/giảm, mới nhất, đánh giá cao) và Phân trang (Pagination) lưu query params.
  - [x] Tối ưu truy vấn đếm số lượng phân trang bằng Subquery `HAVING` khi kết hợp lọc số sao.
- [x] Chuyển đổi giao diện Chi tiết sách từ `chi_ti_t_s_ch_t_m_l_h_c_v_ti_n_csdl_sach_tac_gia_nha_xuat_ban_danh_gia/code.html` sang `Views/client/detail.php`:
  - [x] Khối giới thiệu sách: Ảnh bìa tỷ lệ 2:3, tên sách, đồng tác giả, NXB, năm xuất bản, giá bán, số lượng tồn.
  - [x] Bộ chọn số lượng (+ / -) và nút Thêm Giỏ Hàng / Mua Ngay.
  - [x] 3 Tabs chuyển đổi mượt: Mô tả nội dung, Thông tin tác giả, Thông số kỹ thuật.
  - [x] Thống kê điểm sao trung bình và biểu đồ tỷ lệ 1–5 sao kèm thanh phần trăm trực quan.
  - [x] Hiển thị danh sách nhận xét độc giả đã được duyệt (`trang_thai = 'hien_thi'`).
  - [x] Form gửi đánh giá (`so_sao` 1-5 sao, `noi_dung`), kiểm tra điều kiện đã mua hàng và lưu trạng thái `cho_duyet`.
  - [x] Danh sách tác phẩm liên quan cùng thể loại.

---

## GIAI ĐOẠN 3: GIỎ HÀNG, ĐẶT HÀNG & THEO DÕI VẬN ĐƠN ĐỘC GIẢ (SẴN SÀNG TRIỂN KHAI)
- [ ] Xây dựng Model bổ trợ: `Models/Coupon.php`, `Models/Order.php`.
- [ ] Chuyển đổi Giỏ hàng từ `gi_h_ng_thanh_to_n_bookstore/code.html` sang `Views/client/cart.php`:
  - [ ] Thêm sách vào giỏ (Form POST / AJAX), kiểm tra không vượt quá `so_luong_ton`.
  - [ ] Cập nhật số lượng (+ / -) và xóa sản phẩm khỏi giỏ hàng.
  - [ ] Áp dụng mã giảm giá (`ma_giam_gia`): Kiểm tra thời hạn, đơn tối thiểu, số lượt tối đa.
- [ ] Chuyển đổi Màn hình Checkout từ `thanh_toan_x_c_nh_n_n_h_ng_bookstore/code.html` sang `Views/client/checkout.php`:
  - [ ] Chọn địa chỉ giao từ bảng `dia_chi` hoặc nhập địa chỉ mới.
  - [ ] Chọn phương thức vận chuyển và tính phí giao hàng.
  - [ ] Chọn phương thức thanh toán (COD hoặc VNPAY/Chuyển khoản).
  - [ ] Xử lý Đặt hàng bằng Database Transaction (Tạo `don_hang`, `chi_tiet_don_hang`, trừ `so_luong_ton`, tăng `da_dung` của voucher, tạo `thanh_toan`, ghi log `lich_su_don_hang`, xóa giỏ hàng).
  - [ ] Màn hình xác nhận đặt hàng thành công (`Views/client/checkout_success.php`).
- [ ] Chuyển đổi Quản lý đơn hàng độc giả từ `qu_n_l_n_h_ng_bookstore/code.html` & `qu_n_l_theo_d_i_n_h_ng_bookstore/code.html` sang `Views/client/orders.php`:
  - [ ] Danh sách đơn hàng phân theo tabs trạng thái (`cho_xac_nhan`, `da_xac_nhan`, `dang_giao`, `hoan_thanh`, `da_huy`).
  - [ ] Timeline Stepper lộ trình vận chuyển 4 bước từ bảng `lich_su_don_hang`.
  - [ ] Hủy đơn hàng khi còn ở trạng thái `cho_xac_nhan` (hoàn tồn kho, hoàn lượt voucher, ghi log hủy).

---

## GIAI ĐOẠN 4: TOÀN BỘ PHÂN HỆ QUẢN TRỊ (ADMIN PORTAL)
- [ ] Hoàn thiện Dashboard Quản trị từ `t_ng_quan_qu_n_tr_bookstore_admin_1` & `t_ng_quan_qu_n_tr_bookstore_admin_2`:
  - [ ] 4 Bento KPI Cards phân tích doanh thu thực tế từ các đơn `hoan_thanh`.
  - [ ] Biểu đồ Doanh thu thời gian (SVG chart) & Biểu đồ phân bổ thể loại.
  - [ ] Bảng đơn hàng mới cần duyệt gấp & Top 5 sách bán chạy nhất.
  - [ ] Widget cảnh báo tồn kho thấp (< 10 cuốn).
- [ ] Quản lý Sách & Quản lý Kho từ `qu_n_l_s_ch_qu_n_l_kho_bookstore_admin/code.html`:
  - [ ] CRUD Sách (upload ảnh bìa `uploads/books/`, gán nhiều tác giả qua `sach_tac_gia`, Modal `#modal-add-book`).
  - [ ] Quản lý Tồn kho & Lập phiếu kho qua Modal `#modal-quick-slip` (Nhập, Xuất, Điều chỉnh) bằng Transaction.
- [ ] Quản lý Đơn hàng từ `qu_n_l_n_h_ng_bookstore_admin/code.html`:
  - [ ] Lọc đơn theo mã, khách hàng, ngày đặt, tabs trạng thái.
  - [ ] Cập nhật trạng thái đơn hàng & thanh toán, tự động ghi log vào `lich_su_don_hang` (hoàn tồn kho nếu hủy).
- [ ] CRUD Danh mục Thể loại (`the_loai`), Tác giả (`tac_gia`), Nhà xuất bản (`nha_xuat_ban`).
- [ ] CRUD Mã Giảm Giá (`ma_giam_gia`): Cấu hình phần trăm / tiền mặt, thời gian, giới hạn lượt dùng.
- [ ] Quản lý Người dùng (`nguoi_dung`): Danh sách, lọc theo vai trò/trạng thái, phân quyền, Khóa / Mở khóa tài khoản (chặn Admin tự khóa chính mình).
- [ ] Quản lý & Duyệt Đánh giá từ `duy_t_nh_gi_kh_ch_h_ng_bookstore_admin/code.html`:
  - [ ] 4 thẻ chỉ số đánh giá, lọc theo số sao / trạng thái / tựa sách.
  - [ ] Duyệt hiển thị (`hien_thi`) hoặc Ẩn đánh giá (`an`).
