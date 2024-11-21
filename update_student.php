<?php
session_start();
include 'connect_database.php';
$conn = connect_to_database("localhost", "root","", "qldiem");

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['MaID'])) {
    header("Location: login.php");
    exit();
}

// Xử lý form khi gửi yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $ma_id_sinh_vien = $_POST['MaID'];  // Mã sinh viên
    $ten_sv = $_POST['TenSV'];  // Họ tên
    $dia_chi = $_POST['DiaChi'];  // Địa chỉ
    $ngay_sinh = $_POST['NgaySinh'];  // Ngày sinh
    $gioi_tinh = $_POST['GioiTinh'];  // Giới tính
    $sdt = $_POST['SDT'];  // Số điện thoại
    $email = $_POST['Email'];  // Email
    $lop_hc = $_POST['LopHC'];  // Lớp hành chính
    $khoa_hoc = $_POST['KhoaHoc'];  // Khóa học
    $ma_khoa = $_POST['MaKhoa'];  // Mã ngành học (MaKhoa)
    $hinh_thuc_dt = $_POST['HinhThucDT'];  // Hình thức đào tạo
    $gvhd = $_POST['Gvhd'];  // GVHD

    // Kiểm tra xem các trường quan trọng có được điền không
    if (empty($ten_sv) || empty($dia_chi) || empty($ngay_sinh) || empty($gioi_tinh) || empty($sdt) || empty($email) || empty($ma_khoa)) {
        echo "Vui lòng điền đầy đủ thông tin yêu cầu!";
        exit();
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Định dạng email không hợp lệ!";
        exit();
    }

    // Gọi hàm update_student và xử lý kết quả
    $update_result = update_student($conn, $ma_id_sinh_vien, $ten_sv, $dia_chi, $ngay_sinh, $gioi_tinh, $sdt, $email, $lop_hc, $khoa_hoc, $ma_khoa, $hinh_thuc_dt, $gvhd);

    // Kiểm tra kết quả
    if ($update_result === true) {
        header("Location: user_management.php?message=success");
        exit();
    } else {
        echo $update_result; // If there was an error, show it
    }
} else {
    echo "Phương thức không được hỗ trợ!";
}

$conn->close();

?>	
