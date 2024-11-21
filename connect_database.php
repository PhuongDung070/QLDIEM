<?php

function connect_to_database($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }
    return $conn;
}
function login_user($conn, $input_username, $input_password) {
    $error_msg = "";

    // Chuẩn bị câu lệnh truy vấn để lấy mật khẩu, quyền và ID của người dùng
    $stmt = $conn->prepare("SELECT MatKhau, Quyen, MaID FROM TaiKhoan WHERE BINARY TenDangNhap = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    // Kiểm tra nếu có tài khoản tồn tại
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $role, $user_id); // Lấy thông tin từ truy vấn
        $stmt->fetch();

        // Kiểm tra mật khẩu
        if ($input_password === $hashed_password) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['TenDangNhap'] = $input_username;
            $_SESSION['Quyen'] = $role;
            $_SESSION['MaID'] = $user_id;

            // Chuyển hướng người dùng đến dashboard phù hợp theo quyền
            header("Location: " . 
                ($role === 'SinhVien' ? "student_dashboard.php" : 
                ($role === 'GiangVien' ? "teacher_dashboard.php" : "admin_dashboard.php")));
            exit();
        } else {
            $error_msg = "Mật khẩu không chính xác.";
        }
    } else {
        $error_msg = "Tài khoản không tồn tại.";
    }

    return $error_msg; // Trả về thông báo lỗi nếu có
}

function get_nhanvien_info($conn, $user_id) {
    $sql = "SELECT * 
            FROM nhanvienquanly 
            INNER JOIN nhanvien ON nhanvienquanly.MaID = nhanvien.MaID 		
            WHERE nhanvienquanly.MaID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false; 
    }
}
function get_account_info($conn, $user_id) {
    $sql = "SELECT * 
            FROM taikhoan		
            WHERE MaID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false; 
    }
}
function get_teacher_info($conn, $user_id) {
$sql = "SELECT * 
        FROM giangvien 
        INNER JOIN khoa ON giangvien.MaKhoa = khoa.MaKhoa
		INNER JOIN nhanvien ON giangvien.MaID = nhanvien.MaID 		
        WHERE giangvien.MaID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false; 
    }
}

function get_khoa_list($conn) {
    $sql_khoa = "SELECT * FROM khoa";
    $stmt_khoa = $conn->prepare($sql_khoa);
    $stmt_khoa->execute();
    return $stmt_khoa->get_result();
}
function get_tkb_list($conn) {
    $sql_tkb = "SELECT * FROM thoikhoabieu";
    $stmt_tkb = $conn->prepare($sql_tkb);
    $stmt_tkb->execute();
    return $stmt_tkb->get_result();
}
function get_student_info($conn, $ma_id_sinh_vien) {
	
    $sql = "SELECT * 
            FROM sinhvien 
            INNER JOIN khoa ON sinhvien.MaKhoa = khoa.MaKhoa
            WHERE sinhvien.MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_id_sinh_vien);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function update_student($conn, $ma_id, $ten_sv, $dia_chi, $ngay_sinh, $gioi_tinh, $sdt, $email, $lop_hc, $khoa_hoc, $ma_khoa, $hinh_thuc_dt) {
    // Sử dụng mysqli_real_escape_string để tránh SQL Injection
    $ma_id = mysqli_real_escape_string($conn, $ma_id);
    $ten_sv = mysqli_real_escape_string($conn, $ten_sv);
    $dia_chi = mysqli_real_escape_string($conn, $dia_chi);
    $ngay_sinh = mysqli_real_escape_string($conn, $ngay_sinh);
    $gioi_tinh = mysqli_real_escape_string($conn, $gioi_tinh);
    $sdt = mysqli_real_escape_string($conn, $sdt);
    $email = mysqli_real_escape_string($conn, $email);
    $lop_hc = mysqli_real_escape_string($conn, $lop_hc);
    $khoa_hoc = mysqli_real_escape_string($conn, $khoa_hoc);
    $ma_khoa = mysqli_real_escape_string($conn, $ma_khoa);
    $hinh_thuc_dt = mysqli_real_escape_string($conn, $hinh_thuc_dt);

    // Câu lệnh SQL để cập nhật thông tin sinh viên
    $query = "UPDATE sinhvien 
              SET 
                  TenSV = '$ten_sv', 
                  DiaChi = '$dia_chi', 
                  NgaySinh = '$ngay_sinh', 
                  GioiTinh = '$gioi_tinh', 
                  SDT = '$sdt', 
                  Email = '$email', 
                  LopHC = '$lop_hc', 
                  KhoaHoc = '$khoa_hoc', 
                  MaKhoa = '$ma_khoa', 
                  HinhThucDT = '$hinh_thuc_dt', 
              WHERE 
                  MaID = '$ma_id'";

    // Thực thi truy vấn và kiểm tra kết quả
    if (mysqli_query($conn, $query)) {
        return true; // Successful update
    } else {
        // Return the error if query fails
        return "Error: " . mysqli_error($conn);
    }
}
function update_account_info($conn, $maID, $tenDangNhap, $matKhau) {
    $sql = "UPDATE taikhoan SET 
                TenDangNhap = ?, 
                MatKhau = ? 
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $tenDangNhap, $matKhau, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}

function update_student_info($conn, $maID, $tenSV, $diaChi, $ngaySinh, $gioiTinh, $sdt, $email, $lopHC, $khoaHoc, $hinhThucDT) {
    $sql = "UPDATE sinhvien SET 
                TenSV = ?, 
                DiaChi = ?, 
                NgaySinh = ?, 
                GioiTinh = ?, 
                SDT = ?, 
                Email = ?, 
                LopHC = ?, 
                KhoaHoc = ?, 
                HinhThucDT = ?
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $tenSV, $diaChi, $ngaySinh, $gioiTinh, $sdt, $email, $lopHC, $khoaHoc, $hinhThucDT, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}
function getCourseResults($conn, $MaID) {
    $sql = "
    SELECT 
        t.MaLHP, 
        h.TenHP, 
        h.SoTinChi,
		k.MaLop,
        ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) AS DiemTB,
        CASE 
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 8.5 THEN 4.0
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 7.0 THEN 3.0
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 5.5 THEN 2.0
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 4.0 THEN 1.0
            ELSE 0.0
        END AS DiemH4,
        CASE 
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 8.5 THEN 'A'
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 7.0 THEN 'B'
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 5.5 THEN 'C'
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 4.0 THEN 'D'
            ELSE 'F'
        END AS DiemChu,
        CASE 
            WHEN ROUND((k.DiemCC * 0.1) + ((k.DiemKT1 + k.DiemKT2) * 0.075) + (k.DiemTL * 0.15) + (k.DiemKTHP * 0.6), 2) >= 5.5 THEN 'Đạt'
            ELSE 'Không đạt'
        END AS KetQua
    FROM ketquasv k
    JOIN thoikhoabieu t ON k.MaLop = t.MaLop
    JOIN hocphan h ON t.MaLHP = h.MaLHP
    WHERE k.MaID = ?;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaID); // Truyền tham số vào
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
function getCourseDetails($MaID, $MaLop) {
    include 'connect_database.php';
    $conn = connect_to_database("localhost", "root", "", "qldiem");

    $sql = "SELECT DiemCC, DiemKT1, DiemKT2, DiemTL, DiemKTHP 
            FROM ketquasv 
            WHERE MaID = ? AND MaLop = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $MaID, $MaLop);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $details = $result->fetch_assoc();
        return $details;
    } else {
        return ['error' => 'Không tìm thấy dữ liệu.'];
    }
}



?>