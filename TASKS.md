# DANH SÁCH NHIỆM VỤ DỰ ÁN (PROJECT TASKS)
> **Dự án:** Website Bán Sách Trực Tuyến - DQP Book Store  
> **Cập nhật:** Hoàn thành 100% Giai đoạn 1, 2, 3, 4 (Client & Admin Portal)  
> **Thời điểm cập nhật:** 31/10/2026  

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

## GIAI ĐOẠN 3: GIỎ HÀNG, ĐẶT HÀNG & THEO DÕI VẬN ĐƠN ĐỘC GIẢ (HOÀN THÀNH 100%)
- [x] Xây dựng Model bổ trợ: `Models/Coupon.php`, `Models/Order.php`, mở rộng `Models/Cart.php`.
- [x] Chuyển đổi Giỏ hàng từ `gi_h_ng_thanh_to_n_bookstore/code.html` sang `Views/client/cart.php`:
  - [x] Thêm sách vào giỏ (Form POST / AJAX), kiểm tra không vượt quá `so_luong_ton`.
  - [x] Cập nhật số lượng (+ / -) và xóa từng sản phẩm hoặc xóa sạch giỏ hàng.
  - [x] Tự động gộp giỏ hàng session vào tài khoản CSDL khi đăng nhập (`Cart::mergeSessionCart()`).
  - [x] Áp dụng mã giảm giá (`ma_giam_gia`): Kiểm tra thời hạn, đơn tối thiểu, số lượt tối đa, tính số tiền giảm theo % hoặc tiền mặt.
  - [x] Danh sách mã voucher có sẵn kèm nút "Dùng ngay" và nút "Gỡ bỏ".
- [x] Chuyển đổi Màn hình Checkout từ `thanh_to_n_x_c_nh_n_n_h_ng_bookstore/code.html` sang `Views/client/checkout.php`:
  - [x] Chọn địa chỉ giao từ sổ địa chỉ `dia_chi` (kèm badge Mặc định) hoặc nhập địa chỉ mới (tùy chọn lưu vào sổ).
  - [x] Chọn phương thức vận chuyển: Giao tiêu chuẩn (miễn phí từ 250k) hoặc Hỏa tốc NowShip 2h (40k).
  - [x] Chọn phương thức thanh toán: COD hoặc VNPAY.
  - [x] Xử lý Đặt hàng bằng Database Transaction an toàn (`Order::createOrder`):
    - Khóa dòng kiểm tra tồn kho `SELECT ... FOR UPDATE`.
    - Trừ số lượng tồn kho `sach.so_luong_ton`.
    - Tạo `don_hang` trạng thái `cho_xac_nhan`.
    - Lưu từng món vào `chi_tiet_don_hang`.
    - Tăng `da_dung` của mã giảm giá.
    - Tạo bản ghi thanh toán trong `thanh_toan`.
    - Ghi log khởi tạo vào `lich_su_don_hang`.
    - Xóa sạch giỏ hàng qua `Cart::clearCart()`.
  - [x] Màn hình xác nhận đặt hàng thành công (`Views/client/checkout_success.php`): Mã đơn hàng `#BK-...`, bento grid chi tiết, nút theo dõi đơn hàng.
- [x] Chuyển đổi Quản lý đơn hàng độc giả từ `qu_n_l_n_h_ng_bookstore/code.html` sang `Views/client/account/orders.php`:
  - [x] Danh sách đơn hàng phân theo Tabs trạng thái (`tat_ca`, `cho_xac_nhan`, `da_xac_nhan`, `dang_giao`, `hoan_thanh`, `da_huy`) kèm số lượng đếm trên từng tab.
  - [x] Thẻ tóm tắt đơn hàng: Mã đơn, ngày đặt, trạng thái badge, ảnh đại diện các cuốn sách, tổng thanh toán, nút Xem chi tiết / Theo dõi vận đơn.
- [x] Chuyển đổi Theo dõi chi tiết đơn hàng từ `qu_n_l_theo_d_i_n_h_ng_bookstore/code.html` sang `Views/client/account/order_detail.php`:
  - [x] **Timeline Stepper 4 bước** trực quan theo Design System Athenaeum Modern (*Đã Đặt Đơn* -> *Xác Nhận & Đóng Gói* -> *Đang Vận Chuyển* -> *Giao Thành Công*).
  - [x] Lịch sử hành trình vận đơn chi tiết lấy từ bảng `lich_su_don_hang` kèm mốc thời gian thực tế.
  - [x] Bảng tổng kết chi phí: Tạm tính, Phí vận chuyển, Giảm giá voucher, Tổng thanh toán.
  - [x] Hủy đơn hàng an toàn khi đơn ở trạng thái `cho_xac_nhan` bằng Database Transaction:
    - Khóa bản ghi kiểm tra.
    - Hoàn trả lại số lượng tồn kho `sach.so_luong_ton`.
    - Giảm số lượt dùng voucher `ma_giam_gia.da_dung`.
    - Đổi trạng thái sang `da_huy`.
    - Ghi log hủy kèm lý do vào `lich_su_don_hang`.
- [x] Viết và thực thi kiểm thử tích hợp tự động `scratch/test_stage3.php` đạt `100% Passed` (14/14 test cases).
- [x] Kiểm thử toàn diện trình duyệt bằng Browser Subagent: Đăng nhập -> Thêm giỏ -> Áp voucher `WELCOME2026` -> Checkout COD -> Đặt hàng #7 -> Xem danh sách đơn -> Chi tiết đơn hàng với Timeline Stepper -> Hủy đơn và xác nhận badge `ĐÃ HỦY`.
- [x] Quét cú pháp toàn diện 42/42 tệp PHP dự án đạt 100% hợp lệ (`lint_all.php`).
- [x] Thực thi Master Test Suite kiểm thử 26 chức năng trọng yếu (Database 16 bảng, Auth bcrypt, Profile, Address transaction, Category count, Multi-filter book, Review summary, Coupon engine, Cart session merge, Order transaction với khóa dòng FOR UPDATE, Timeline Stepper, Cancel order hoàn kho) đạt 26/26 Passed (`test_all_features.php`).
- [x] Kiểm thử toàn bộ 21 HTTP endpoints (khách vãng lai, thành viên đăng nhập, admin portal) đạt 100% mã phản hồi chuẩn (`test_endpoints.php`, `test_auth_endpoints.php`, `test_admin.php`).
- [x] Chuẩn hóa bộ điều hướng `Core/App.php`: Thêm route `'tai-khoan/ho-so'` và nhánh bắt lỗi 404 trả về HTTP code 404 cùng giao diện `Views/client/404.php`.
- [x] Cấu hình `.vscode/settings.json` để loại trừ các cảnh báo tĩnh giả lập của IDE DevSense/Intelephense trên các tệp View PHP MVC thuần.
- [x] Tuân thủ cam kết: Giữ nguyên vẹn tệp `Views/client/account/order_detail.php` và thư mục `stitch_modern_bookstore_platform_ui_ux/`.

---

## GIAI ĐOẠN 4: TOÀN BỘ PHÂN HỆ QUẢN TRỊ (ADMIN PORTAL)
- [x] Hoàn thiện Dashboard Quản trị từ `t_ng_quan_qu_n_tr_bookstore_admin_1` & `t_ng_quan_qu_n_tr_bookstore_admin_2`:
  - [x] 4 Bento KPI Cards phân tích doanh thu thực tế từ các đơn `hoan_thanh`, tổng đơn hàng mới, sách đã bán, tồn kho hệ thống.
  - [x] Biểu đồ Doanh thu thời gian (SVG interactive chart) & Biểu đồ phân bổ cơ cấu thể loại (Donut SVG chart).
  - [x] Bảng đơn hàng mới cần duyệt gấp (`cho_xac_nhan`) & Top 5 sách bán chạy nhất.
  - [x] Widget cảnh báo tồn kho thấp (sách tồn kho <= 30 cuốn).
  - [x] Widget duyệt đánh giá khách hàng mới nhất.
  - [x] Bổ sung helper toàn cục `get_book_cover()` trong `Core/Helper.php`.
- [x] Quản lý Sách & Quản lý Kho từ `qu_n_l_s_ch_qu_n_l_kho_bookstore_admin/code.html`:
  - [x] Danh sách sách với thanh tìm kiếm, bộ lọc danh mục/trạng thái, phân trang, hiển thị thẻ cảnh báo tồn kho thấp.
  - [x] Modal thêm sách `#modal-add-book` (upload ảnh bìa `uploads/books/`, gán nhiều tác giả qua `sach_tac_gia`, tự động lập phiếu nhập kho đầu kỳ).
  - [x] Modal sửa thông tin sách `#modal-edit-book` nạp dữ liệu động qua AJAX JSON, cập nhật giá, tác giả, mô tả và trạng thái kinh doanh.
  - [x] Quản lý Tồn kho & Lập phiếu kho qua Modal `#modal-quick-slip` (Phiếu Nhập, Xuất, Điều chỉnh kiểm kê) bằng Database Transaction an toàn với khóa dòng `SELECT ... FOR UPDATE`.
  - [x] Chuyển đổi mượt 2 tabs: Danh sách Sách & Tồn kho vs Lịch sử Phiếu kho Luân chuyển.
- [x] Quản lý Đơn hàng từ `qu_n_l_n_h_ng_bookstore_admin/code.html` (Hoàn thành 100%):
  - [x] 4 Thẻ chỉ số Bento KPI: Tổng đơn & Doanh thu hôm nay, Đơn Chờ xác nhận & cảnh báo tồn đọng > 2 giờ, Đơn đang vận chuyển, Tỷ lệ giao thành công & hoàn tất trong tháng.
  - [x] Thanh Tabs trạng thái (`tat_ca`, `cho_xac_nhan`, `da_xac_nhan`, `dang_giao`, `hoan_thanh`, `da_huy`) kèm huy hiệu số lượng đếm trên từng tab.
  - [x] Bộ lọc nâng cao: Tìm kiếm theo mã đơn (`#BK-...`), tên khách hàng, số điện thoại, địa chỉ; lọc khoảng ngày đặt (`tu_ngay`, `den_ngay`); lọc phương thức thanh toán (`COD`, `VNPAY`).
  - [x] Bảng dữ liệu đơn hàng: Tóm tắt kiện sách kèm ảnh bìa, tổng tiền, phương thức & trạng thái thanh toán, huy hiệu trạng thái đơn, dropdown đổi trạng thái nhanh.
  - [x] Slide-over Drawer `#orderDrawer`: Xem chi tiết kiện hàng, thông tin người nhận, danh sách sách, tổng chi phí (tiền hàng, ship, giảm giá), lịch sử hành trình vận đơn từ `lich_su_don_hang`, nút hành trình tiếp theo (Xác nhận, Giao bưu cục, Hoàn tất, Hủy đơn).
  - [x] Xử lý trạng thái an toàn bằng Database Transaction với khóa dòng `SELECT ... FOR UPDATE`: Tự động hoàn trả tồn kho `sach.so_luong_ton`, giảm số lượt dùng voucher `ma_giam_gia.da_dung`, hủy thanh toán khi Hủy đơn; tự động đánh dấu thanh toán COD `thanh_cong` khi hoàn tất đơn; ghi log chi tiết vào `lich_su_don_hang`.
  - [x] Tích hợp Toast notification `#statusToast` thông báo tức thì khi thao tác AJAX thành công/thất bại.
  - [x] Kiểm thử tích hợp tự động 47/47 Passed (`scratch/test_admin_orders.php`) và kiểm thử trực quan trình duyệt qua Browser Subagent.
- [x] Quản lý & Duyệt Đánh giá từ `duy_t_nh_gi_kh_ch_h_ng_bookstore_admin/code.html` (Hoàn thành 100%):
  - [x] 4 Thẻ chỉ số Bento: Đánh giá Chờ Duyệt (cảnh báo cần xử lý gấp), Đang Hiển Thị (tỷ lệ công khai), Điểm Đánh Giá Toàn Sàn (sao vàng và tỷ lệ hài lòng 4-5 sao), Đã Ẩn / Vi Phạm.
  - [x] Thanh Tabs trạng thái (`tat_ca`, `cho_duyet`, `hien_thi`, `an`) kèm huy hiệu số lượng đếm trên từng tab.
  - [x] Bộ lọc xếp hạng sao (Tất cả sao, 5★, 4★, 3★, 2★, 1★), tìm kiếm tức thì theo độc giả, email, tên sách, nội dung nhận xét; sắp xếp mới nhất, cũ nhất, sao cao/thấp.
  - [x] Thanh thao tác hàng loạt Floating Bulk Toolbar `#bulkToolbar`: Duyệt hiển thị, Ẩn đánh giá, Xóa vĩnh viễn nhiều đánh giá cùng lúc.
  - [x] Bảng dữ liệu chi tiết kèm avatar người đánh giá, huy hiệu "Đã mua hàng", bìa sách, sao vàng, nội dung nhận xét, thời gian, trạng thái badge, nút duyệt/ẩn/xóa nhanh.
  - [x] Toast notification tức thì `#statusToast` phản hồi kết quả thao tác AJAX không cần tải lại trang.
  - [x] Kiểm thử tích hợp tự động 35/35 Passed (`scratch/test_admin_reviews.php`) và kiểm thử trực quan trình duyệt qua Browser Subagent.
- [x] CRUD Danh mục Thể loại (`the_loai`), Tác giả (`tac_gia`), Nhà xuất bản (`nha_xuat_ban`) (Hoàn thành 100%):
  - [x] Thanh Sub-navigation tabs chuyển đổi mượt mà 3 phân hệ Metadata (`admin/the-loai`, `admin/tac-gia`, `admin/nha-xuat-ban`) kèm counter badges.
  - [x] Phân hệ Thể Loại Sách (`admin/the-loai`): 3 Bento KPI cards, tìm kiếm, Modal thêm/sửa `#categoryModal`, xóa an toàn chặn vi phạm ràng buộc toàn vẹn khi đang có sách liên kết.
  - [x] Phân hệ Tác Giả (`admin/tac-gia`): 3 Bento KPI cards, tìm kiếm, avatar chữ cái, Modal thêm/sửa `#authorModal`, xóa an toàn chặn vi phạm khi đang có sách.
  - [x] Phân hệ Nhà Xuất Bản (`admin/nha-xuat-ban`): 3 Bento KPI cards, tìm kiếm, thông tin địa chỉ & liên hệ, Modal thêm/sửa `#publisherModal`, xóa an toàn chặn vi phạm khi đang có sách.
  - [x] Kiểm thử tích hợp tự động 36/36 Passed (`scratch/test_admin_metadata.php`) và kiểm thử trực quan trình duyệt qua Browser Subagent.
- [x] CRUD Quản Lý Mã Giảm Giá (`ma_giam_gia`) (Hoàn thành 100%):
  - [x] 4 Bento KPI Cards: Tổng số voucher, Đang hiệu lực, Hết hạn / Hết lượt, Tổng lượt đã dùng & Tổng tiền đã tiết kiệm cho khách.
  - [x] Bộ lọc linh hoạt: Tìm kiếm mã voucher tức thì, lọc theo loại giảm giá (`phan_tram`, `tien_mat`), lọc trạng thái hiệu lực (`con_han`, `chua_bat_dau`, `het_han`, `het_luot`).
  - [x] Bảng dữ liệu voucher: Thẻ mã phong cách vé voucher, loại & mức giảm badge, đơn hàng tối thiểu, khoảng thời gian hiệu lực, thanh tiến trình lượt dùng (progress bar), badge trạng thái hoạt động/hết hạn.
  - [x] Modal Thêm / Chỉnh Sửa `#couponModal`: Hỗ trợ tự động chuyển đổi đơn vị (%) và (VNĐ), validation chặt chẽ (mã in hoa, hạn dùng, giá trị > 0), nạp dữ liệu AJAX JSON qua `admin/ma-giam-gia/chi-tiet/{id}`.
  - [x] Xóa voucher an toàn kèm cảnh báo số đơn hàng cũ đã từng sử dụng.
  - [x] Kiểm thử tích hợp tự động 44/44 Passed (`scratch/test_admin_coupons.php`) và kiểm thử HTTP Endpoints.
- [x] Quản lý Người dùng & Phân quyền (`nguoi_dung`) (Hoàn thành 100%):
  - [x] 4 Bento KPI Cards: Tổng số người dùng, Khách hàng độc giả, Ban quản trị, Tài khoản đang bị khóa.
  - [x] Bộ lọc linh hoạt: Tìm kiếm họ tên, email, số điện thoại; lọc theo vai trò (`tat_ca`, `khach_hang`, `quan_tri`), lọc theo trạng thái (`tat_ca`, `hoat_dong`, `khoa`).
  - [x] Bảng dữ liệu người dùng: Avatar chữ cái, thông tin liên hệ, thống kê số đơn hàng & tổng chi tiêu, badge vai trò, badge trạng thái.
  - [x] Phân quyền Quản Trị Viên / Khách Hàng thời gian thực qua AJAX.
  - [x] Khóa / Mở khóa tài khoản tức thì, đồng bộ thu hồi session ngay lập tức khi bị khóa.
  - [x] Ràng buộc bảo mật tối cao: Chặn quản trị viên tự khóa tài khoản của chính mình và chặn tự hạ quyền quản trị.
  - [x] Modal Thêm mới tài khoản `#addUserModal` (kiểm tra email duy nhất, băm mật khẩu Bcrypt, gán quyền Admin/Khách hàng), Modal xem chi tiết `#userDetailModal` (hồ sơ, địa chỉ, đơn hàng gần nhất) và Modal sửa thông tin `#userEditModal`.
  - [x] Kiểm thử tích hợp tự động 74/74 Passed (`scratch/test_admin_users.php`) và kiểm thử HTTP Endpoints.
