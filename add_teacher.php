	<?php
session_start();
include 'connect_database.php';

// Kết nối tới cơ sở dữ liệu
$conn = connect_to_database("localhost", "root", "", "qldiem");

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['MaID'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['MaID'];
	
	$admin_info = get_nhanvien_info($conn, $user_id);

	if ($admin_info) {
    $ma_nhan_vien = $admin_info['MaID'];
    $ten_nhan_vien = $admin_info['TenNV'];
	} else {
    $error_msg = "Người dùng không tồn tại!";
    exit();
}

// Lấy danh sách các khoa
$result_khoa = get_khoa_list($conn);

$error_msg = "";
$success_msg = "";
function generateUsername($prefix) {
    return $prefix . rand(1000, 9999);
}

// Hàm tạo mật khẩu ngẫu nhiên
function generatePassword() {
    $length = 8; // Độ dài mật khẩu
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Xử lý thêm mới nhân viên khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Lấy dữ liệu từ form
    $maID = $_POST['MaID'];
    $tenNV = $_POST['TenNV'];
    $diaChi = $_POST['DiaChi'];
    $ngaySinh = $_POST['NgaySinh'];
    $gioiTinh = $_POST['GioiTinh'];
    $sdt = $_POST['SDT'];
    $email = $_POST['Email'];
	$maKhoa = $_POST['MaKhoa'];
    $hocVi = $_POST['HocVi'];

    // Tạo MaID cho sinh viên (SV....)
    $role = "GV";
    $maID = generate_unique_maID($conn, $role);
    // Tạo tên đăng nhập
    $tenDangNhap = generateUsername($maID);
    // Tạo mật khẩu ngẫu nhiên
    $matKhau = generatePassword();
    // Quyền mặc định là sinh viên
    $quyen = 'GiangVien';

     // Câu lệnh SQL để thêm tài khoản vào bảng taikhoan
    $sql_taikhoan = "INSERT INTO taikhoan (MaID, TenDangNhap, MatKhau, Quyen) 
                     VALUES (?, ?, ?, ?)";
    $stmt_taikhoan = $conn->prepare($sql_taikhoan);
    $stmt_taikhoan->bind_param("ssss", $maID, $tenDangNhap, $matKhau, $quyen);

    // Thực thi câu lệnh SQL thêm tài khoản
    if ($stmt_taikhoan->execute()) {
        // Thêm thông tin sinh viên vào bảng sinhvien
        $sql_nhanvien = "INSERT INTO nhanvien (MaID, TenNV, NgaySinh, DiaChi, GioiTinh, SDT, Email) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_nhanvien = $conn->prepare($sql_nhanvien);
        $stmt_nhanvien->bind_param("sssssss", $maID, $tenNV, $ngaySinh, $diaChi, $gioiTinh, $sdt, $email);

        // Thực thi câu lệnh SQL thêm sinh viên
        if ($stmt_nhanvien->execute()) {
			$sql_giangvien = "INSERT INTO giangvien (MaID, MaKhoa, HocVi) 
                         VALUES (?, ?, ?)";
			$stmt_giangvien = $conn->prepare($sql_giangvien);
			$stmt_giangvien->bind_param("sss", $maID, $maKhoa, $hocVi);
            
			if ($stmt_giangvien->execute()) {
            $success_msg = "Thêm sinh viên, tài khoản và giảng viên thành công!";
        } else {
            $error_msg = "Lỗi khi thêm giảng viên: " . $stmt_giangvien->error;
        }

        // Đóng câu lệnh thêm giảng viên
        $stmt_giangvien->close();
        } else {
            $error_msg = "Lỗi khi thêm sinh viên: " . $stmt_sinhvien->error;
        }

        // Đóng câu lệnh thêm sinh viên
        $stmt_nhanvien->close();
    } else {
        $error_msg = "Lỗi khi thêm tài khoản: " . $stmt_taikhoan->error;
    }

    // Đóng câu lệnh thêm tài khoản
    $stmt_taikhoan->close();
}

// Đóng kết nối
$conn->close();
?>


	<!DOCTYPE html>
	<html lang="vn">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" href="img/logo-dhtm.png" type="image/png"/> 
			<title>Cổng đào tạo</title>
			<link rel="stylesheet" href="style.css">
			<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600&display=swap">
		</head>
		<body>
			<header class="header">
				<div class="menubar">
					<button id="menu-toggle"> <img src="img/burger-bar.png"> </button>
				</div>
				
				<div class="title"> 
					<img src="img/logo-dhtm.png" alt="Logo" class="logo"> 
					<h2 class="school-name">TRƯỜNG ĐẠI HỌC THƯƠNG MẠI</h2> 
				</div>
				<div class="icons">
					<a href="#"><img class="flag" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/1200px-Flag_of_Vietnam.svg.png" alt="Flag"></a>
					<div class="profile-container" style="margin-right: 50px;">
						<div class="profile">
							<img src="img/user.png" alt="Profile" class="logo" style="width: 100%; cursor: pointer;">
						</div>
						<div class="dropdown-menu" id="dropdownMenu">
							<a href="admin_dashboard.php"><img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Hồ sơ của tôi</a>
							<a href="#" id="openPopup"><img src="img/password.png" alt="Logo" class="logo" style="width: 15px;"> Đổi mật khẩu</a>
							<a href="logout.php"><img src="img/logout.png" alt="Logo" class="logo" style="width: 15px;"> Đăng xuất</a>
						</div>
					</div>

				</div>
				
			</header>	
			<main>
			 <div class="slidebar active" id="slidebar">
				<div class="infor">
					<img src="img/user.png" alt="Logo" class="logo">
					<div class="infor-text">
						<h3><?php echo htmlspecialchars($ten_nhan_vien); ?></h3>
						<h3><?php echo htmlspecialchars($ma_nhan_vien); ?></h3>
						<h3>Nhân viên <?php echo htmlspecialchars($admin_info['PhongBan'] ?? " "); ?></h3>
					</div>
				</div>
				<div>
					<h4 class="title"> TRANG CÁ NHÂN </h4> 
					<a href='admin_dashboard.php'> <img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Thông tin cá nhân</a>
					<a href=''><img src="img/bell.png" alt="Logo" class="logo" style="width: 15px;"> Thông báo</a>
				</div>
				<div>
					<h4 class="title"> TRA CỨU THÔNG TIN</h4>
					<a href='user_management.php'><img src="img/list.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thông tin người dùng</a>
					<a href='course_management.php'><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thông tin học phần	</a>
					<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thời khóa biểu</a>
					<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Báo cáo thống kê</a>
				</div>
			</div>
			<div class="main-content" style="margin-top: 142px;">
			<div class="info-container">
			<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý thông tin người dùng</h2> </div>
			<div class="info-card" style="display: block;">
				<div class="student-details">
					<form method="POST">
							<button type="button" id="backButton">BACK</button>
							<button type="submit" name="add">Thêm giảng viên</button>	
							<table border="1">
								<tr>
									<td><strong>Mã nhân viên</strong></td>
									<td>
										<input type="text" name="MaID" readonly value="<?php echo isset($maID) ? $maID : ''; ?>" placeholder="Tự động tạo mã"/>
									</td>
								</tr>
								<tr>
									<td><strong>Họ tên</strong></td>
									<td>
										<input type="text" name="TenNV" required />
									</td>
								</tr>
								<tr>
									<td><strong>Ngày sinh</strong></td>
									<td>
										<input type="date" name="NgaySinh" required />
									</td>
								</tr>
								<tr>
									<td><strong>Địa chỉ</strong></td>
									<td>
										<input type="text" name="DiaChi" required />
									</td>
								</tr>
								<tr>
									<td><strong>Giới tính</strong></td>
									<td>
										<select name="GioiTinh" required>
											<option value=""> Chọn giới tính</option>
											<option value="Nam" >Nam</option>
											<option value="Nữ" >Nữ</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><strong>Số điện thoại</strong></td>
									<td>
										<input type="text" name="SDT" required />
									</td>
								</tr>
								<tr>
									<td><strong>Email</strong></td>
									<td>
										<input type="email" name="Email" required />
									</td>
								</tr>
								<tr>
									<td><strong>Khoa</strong></td>
									<td> 
									<select name="MaKhoa" required>
										<option value=""> Chọn Khoa </option>
									<?php
										while ($khoa = $result_khoa->fetch_assoc()) {
											echo "<option value='" . $khoa['MaKhoa'] . "'>" . htmlspecialchars($khoa['TenKhoa']) . "</option>";
										}
                                    ?>
									</select>
									</td>
								</tr>
								<tr>
									<td><strong>Học vị</strong></td>
									<td>
										<input type="text" name="HocVi"  required />
									</td>
								</tr>
							</table>			
						</form>
						 <?php if ($error_msg): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_msg); ?></p>
                <?php endif; ?>
                <?php if ($success_msg): ?>
                    <p style="color: green;"><?php echo htmlspecialchars($success_msg); ?></p>
                <?php endif; ?>
					</div>
			</div>
		</main>
		<div class="popup-overlay" id="popupOverlay">
			<div class="popup-content">
				<h2>ĐỔI MẬT KHẨU</h2>
				<form id="changePasswordForm" method="POST">
					<div class="form-group">
						<label for="oldPassword">Mật khẩu cũ:</label>
						<input type="password" id="oldPassword" name="oldPassword" placeholder="Nhập mật khẩu cũ" required>
					</div>
					<div class="form-group">
						<label for="newPassword">Mật khẩu mới</label>
						<input type="password" id="newPassword" name="newPassword" placeholder="Nhập mật khẩu mới" required>
					</div>
					<div class="form-group">
						<label for="confirmPassword">Nhập lại mật khẩu mới</label>
						<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu mới" required>
					</div>
					<button type="submit" class="submit-btn">Đổi mật khẩu</button>
				</form>
				<button id="closePopup" class="close-popup-btn">Đóng</button>
			</div>
		</div>
		 <script src="script.js"></script>
		 <script> 
			 const backButton = document.getElementById("backButton");
			 if (backButton) {
				backButton.addEventListener("click", function () {
					window.location.href = "user_management.php"; // Redirect
				});
			}
		 </script>
		</body>
	</html>


