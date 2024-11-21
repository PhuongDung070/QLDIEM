HHƯỚNG DẪN:
1. Giải nén và lưu vào "C:\xampp\htdocs\QLDIEM"
2. Khởi động XAMPP
3. Import file qldiem.sql lên phpMyAdmin 
4. Mở đường dẫn http://localhost:8080/QLDIEM/login.php để bắt đầu  [port:8080 thay đổi theo xampp]

CHỨC NĂNG ĐANG CÓ
1. Đăng nhập:
   Tài khoản test:
    SINH VIÊN: testsv - 123456
    GIẢNG VIÊN: testgv - 123456
    NHÂN VIÊN: testql - 123456
2. Đăng xuất
3. Đổi mật khẩu
4. Hiển thị thông tin cá nhân sinh viên ở tài khoản sinh viên
5. Hiển thị thông tin cá nhân giảng viên ở tài khoản giảng viên
6. Hiển thị thông tin cá nhân nhân viên ở tài khoản nhân viên quản lý
7. Thêm thông tin sinh viên
- Chưa có lọc thông tin
8. Sinh viên xem chương trình đào tạo
9. Sinh viên xem điểm và kết quả học tập:
- Chưa có lọc theo kì và năm học vì trong database không có trường để lọc
10. Giảng viên quản lý điểm
- Tải lên bằng excel lỗi
- Chưa có tìm kiếm mã lớp

*NOTE VỀ DATABASE:
1. Bảng sinhvien bỏ trường GVHD
2. Bảng thoikhoabieu thêm trường MaLop và là khóa chính, 
    Đổi trường TenGiangVien thành GiangVien và là khóa ngoại đến MaID (giangvien)
  [Chưa bổ sung thêm các trường như trên giao diện đã vẽ, lúc làm đến thì sửa sau :V ]
3. Bảng ketquasv đổi MaLHP thành MaLop, là khóa ngoại đến MaLop (thoikhoabieu)
4. Xem lại chi tiết trong phpMyAdmin sau khi tải file SQL lên
