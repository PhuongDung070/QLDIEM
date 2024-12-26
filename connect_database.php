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
            if ($role === 'SinhVien') {
				header('Location: student_dashboard.php');
			} elseif ($role === 'GiangVien') {
				header('Location: teacher_dashboard.php');
			} elseif ($role === 'QuanLy') {
				header('Location: admin_dashboard.php');
			} else {
				header("Location: thongbaodangnhap.php?message=Tài khoản của bạn bị khóa!");
			}
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

function get_admin_info($conn, $user_id) {
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

function get_khoa_list($conn) {
    $sql_khoa = "SELECT * FROM khoa";
    $stmt_khoa = $conn->prepare($sql_khoa);
    $stmt_khoa->execute();
    return $stmt_khoa->get_result();
}

function get_hocphan_list($conn) {
    $sql_hocphan = "SELECT * FROM hocphan";
    $stmt_hocphan = $conn->prepare($sql_hocphan);
    $stmt_hocphan->execute();
    return $stmt_hocphan->get_result();
}
function get_tkb_list($conn) {
    $sql_tkb = "SELECT * FROM thoikhoabieu";
    $stmt_tkb = $conn->prepare($sql_tkb);
    $stmt_tkb->execute();
    return $stmt_tkb->get_result();
}
function get_student_info($conn, $ma_id) {
	
    $sql = "SELECT * 
            FROM sinhvien 
            INNER JOIN khoa ON sinhvien.MaKhoa = khoa.MaKhoa
            WHERE sinhvien.MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_course_info($conn, $ma_lhp) {
	
    $sql = "SELECT * 
            FROM hocphan 
            INNER JOIN khoa ON hocphan.Khoaphutrach = khoa.MaKhoa
            WHERE hocphan.MaLHP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_lhp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_listcourse_info($conn, $ma_lhp) {
		$sql = "SELECT MaKhoa FROM danhsachhocphan WHERE MaLHP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ma_lhp);
        $stmt->execute();
        $result = $stmt->get_result();
        $result_khoa = [];
        while ($row = $result->fetch_assoc()) {
            $result_khoa[] = $row["MaKhoa"];
        }
        return $result_khoa;
}
 function generate_unique_maDS($conn) {
        do {
            $ma_ds = "DS" . rand(1000, 9999);
            $sql_check = "SELECT COUNT(*) AS count FROM danhsachhocphan WHERE MaDS = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $ma_ds);
            $stmt_check->execute();
            $result = $stmt_check->get_result()->fetch_assoc();
        } while ($result['count'] > 0);
        return $ma_ds;
    }
function generate_unique_maID($conn, $role) {
    do {
        // Tạo mã ngẫu nhiên
        $ma_id = $role . rand(1000, 9999);

        // Chuẩn bị câu truy vấn
        $sql_check = "SELECT COUNT(*) AS count FROM taikhoan WHERE MaID = ?";
        $stmt_check = $conn->prepare($sql_check);

        if ($stmt_check) {
            // Gắn giá trị tham số và thực thi
            $stmt_check->bind_param("s", $ma_id);
            $stmt_check->execute();

            // Lấy kết quả và kiểm tra
            $result = $stmt_check->get_result()->fetch_assoc();
            $stmt_check->close(); // Đóng statement sau khi dùng
        } else {
            // Xử lý lỗi nếu không chuẩn bị được câu truy vấn
            throw new Exception("Không thể chuẩn bị truy vấn: " . $conn->error);
        }
    } while ($result['count'] > 0);

    return $ma_id;
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
function update_account_info($conn, $maID, $tenDangNhap, $matKhau, $quyen) {
    $sql = "UPDATE taikhoan SET 
                TenDangNhap = ?, 
                MatKhau = ?, 
				Quyen = ?
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $tenDangNhap, $matKhau, $quyen, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}

function update_student_info($conn, $maID, $tenSV, $diaChi, $ngaySinh, $gioiTinh, $sdt, $email, $lopHC, $khoaHoc, $maKhoa, $hinhThucDT) {
    $sql = "UPDATE sinhvien SET 
                TenSV = ?, 
                DiaChi = ?, 
                NgaySinh = ?, 
                GioiTinh = ?, 
                SDT = ?, 
                Email = ?, 
                LopHC = ?, 
                KhoaHoc = ?,
				MaKhoa = ?, 
                HinhThucDT = ?
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $tenSV, $diaChi, $ngaySinh, $gioiTinh, $sdt, $email, $lopHC, $khoaHoc, $maKhoa, $hinhThucDT, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}
function update_course_info($conn, $maLHP, $tenHP, $soTC, $khoaPT, $hpTruoc, $soTiet) {
    $sql = "UPDATE hocphan SET 
                TenHP = ?, 
                SoTinChi = ?, 
                KhoaPhuTrach = ?, 
                Hocphantruoc = ?, 
                SoTiet = ?
            WHERE MaLHP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $tenHP, $soTC, $khoaPT, $hpTruoc, $soTiet, $maLHP);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}
 function update_list_course_info($conn, $ma_lhp, $selected_khoa) {
        // Lấy danh sách các khoa hiện tại
        $sql_current = "SELECT MaKhoa FROM danhsachhocphan WHERE MaLHP = ?";
        $stmt_current = $conn->prepare($sql_current);
        $stmt_current->bind_param("s", $ma_lhp);
        $stmt_current->execute();
        $result_current = $stmt_current->get_result();

        $current_khoa = [];
        while ($row = $result_current->fetch_assoc()) {
            $current_khoa[] = $row['MaKhoa'];
        }

        // Xác định các khoa cần thêm và xóa
        $khoa_to_add = array_diff($selected_khoa, $current_khoa);
        $khoa_to_remove = array_diff($current_khoa, $selected_khoa);

        // Xóa các khoa không còn liên kết
        if (!empty($khoa_to_remove)) {
            $sql_delete = "DELETE FROM danhsachhocphan WHERE MaLHP = ? AND MaKhoa = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            foreach ($khoa_to_remove as $ma_khoa) {
                $stmt_delete->bind_param("ss", $ma_lhp, $ma_khoa);
                $stmt_delete->execute();
            }
        }

        // Thêm các khoa mới
        if (!empty($khoa_to_add)) {
            $sql_insert = "INSERT INTO danhsachhocphan (MaDS, MaLHP, MaKhoa) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            foreach ($khoa_to_add as $ma_khoa) {
                $ma_ds = generate_unique_maDS($conn);
                $stmt_insert->bind_param("sss", $ma_ds, $ma_lhp, $ma_khoa);
                $stmt_insert->execute();
            }
        }

        return true;
    }
	
function update_staff_info($conn, $maID, $tenNV,  $ngaySinh, $diaChi, $gioiTinh, $sdt, $email) {
    $sql = "UPDATE nhanvien SET 
                TenNV = ?, 
                NgaySinh = ?, 
				DiaChi = ?, 
                GioiTinh = ?, 
                SDT = ?, 
                Email = ? 
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $tenNV,  $ngaySinh, $diaChi, $gioiTinh, $sdt, $email, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}
function update_teacher_info($conn, $maID, $maKhoa, $hocVi) {
    $sql = "UPDATE giangvien SET 
                MaKhoa = ?,
				HocVi = ?
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $maKhoa, $hocVi, $maID);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return $stmt->error;
    }
}

function update_admin_info($conn, $maID, $phongBan, $chucVu) {
    $sql = "UPDATE nhanvienquanly SET 
                PhongBan = ?,
				ChucVu = ?
            WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $phongBan, $chucVu, $maID);
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