<?php
session_start();
include 'connect_database.php';
$conn = connect_to_database("localhost", "root","",	"qldiem");
$error_msg = ""; 
if (!isset($_SESSION['MaID'])) {
    header("Location: login.php"); 
    exit();
}
$user_id = $_SESSION['MaID'];

$teacher_info = get_teacher_info($conn, $user_id);
if ($teacher_info) {
    $ma_giang_vien = $teacher_info['MaID'];
    $ten_giang_vien = $teacher_info['TenNV'];
} else {
    $error_msg ="Người dùng không tồn tại!";
    exit();
}
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
						<a href="teacher_dashboard.php"><img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Hồ sơ của tôi</a>
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
					<h3><?php echo htmlspecialchars($ten_giang_vien); ?></h3>
					<h3><?php echo htmlspecialchars($ma_giang_vien); ?></h3>
					<h3>Giảng viên</h3>
				</div>
			</div>
			<div>
				<h4 class="title"> TRANG CÁ NHÂN </h4> 
				<a href='teacher_dashboard.php'> <img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Thông tin cá nhân</a>
				<a href=''><img src="img/bell.png" alt="Logo" class="logo" style="width: 15px;"> Thông báo</a>
			</div>
			<div>
				<h4 class="title"> TRA CỨU THÔNG TIN</h4>
				<a href='course.php'><img src="img/list.png" alt="Logo" class="logo" style="width: 15px;"> Chương trình đào tạo</a>
				<a href=''><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Thời khóa biểu</a>
				<a href='score_management.php'><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý điểm và kết quả học tập</a>
			</div>
		</div>
		<div class="main-content" style="margin-top: 142px;">
		<div class="info-container">
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý thông tin giảng viên</h2> </div>

		<div class="info-card">
			<div class="infor-left">
					<img src="img/user.png" alt="Profile  Image">
					<h3><?php echo htmlspecialchars($teacher_info['TenNV'] ?? "Không có"); ?></h3>
			</div>
			<div class="infor-right">
			<h2><strong>Thông tin giảng viên</strong></h2>
			<table class="info-table">
				<tr>
					<td><strong>Mã nhân viên</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['MaID'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Họ tên</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['TenNV'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Địa chỉ</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['DiaChi'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Ngày sinh</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['NgaySinh'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Giới tính</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['GioiTinh'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Số điện thoại</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['SDT'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Email</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['Email'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Học vị</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['HocVi'] ?? "Không có"); ?></td>
				</tr>
				<tr>
					<td><strong>Khoa</strong></td>
					<td><?php echo htmlspecialchars($teacher_info['TenKhoa'] ?? "Không có"); ?></td>
				</tr>
			</table>
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
	</body>
</html>

