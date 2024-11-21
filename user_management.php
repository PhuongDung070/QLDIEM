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

$admin_info = get_nhanvien_info($conn, $user_id);

if ($admin_info) {
    $ma_nhan_vien = $admin_info['MaID'];
    $ten_nhan_vien = $admin_info['TenNV'];
} else {
    $error_msg ="Người dùng không tồn tại!";
    exit();
}

// Kiểm tra nếu có thông điệp trong URL
if (isset($_GET['message'])) {
    $message = $_GET['message'];

    // Hiển thị thông báo tương ứng
    if ($message == 'success') {
        echo "<div class='alert alert-success'>Cập nhật thông tin thành công!</div>";
    } elseif ($message == 'error') {
        echo "<div class='alert alert-danger'>Cập nhật thất bại. Vui lòng thử lại!</div>";
    }
}


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
				<a href=''><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thông tin học phần	</a>
				<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thời khóa biểu</a>
				<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Báo cáo thống kê</a>
			</div>
		</div>
		<div class="main-content" style="margin-top: 142px;">
		<div class="info-container">
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý thông tin người dùng</h2> </div>
		<div class="info-card">
			<div class="tabs" >	
				<button class="tab-button" onclick="openTab(event, 'sinhvien')">Sinh viên</button>
				<button class="tab-button" onclick="openTab(event, 'giangvien')">Giảng viên</button>
				<button class="tab-button" onclick="openTab(event, 'nhanvien')">Nhân viên</button>
				</div>
			</div>
		<div class="info-card" style="display: block;">

			<div id="sinhvien" class="tab-content" >
				<h2>Danh sách Sinh viên</h2>
				<?php
					$sql = "SELECT * 
							FROM sinhvien 
							INNER JOIN khoa ON sinhvien.MaKhoa = khoa.MaKhoa";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						
						echo "<form method='POST' action='handle_students.php'>";
						echo "<div class='infor1'>";
						echo "<button type='button' onclick=\"window.location.href='add_student.php';\">+ Thêm mới</button>";
						echo "<button type='button' id='openPopupDelete'>Xóa</button>";
						echo "<button type='submit' name='action' value='edit'>Chỉnh sửa</button>";
						echo "</div>";
						echo "<table border='1'>
								<thead>
									<tr>
										<th>Chọn</th>
										<th>Mã SV</th>
										<th>Họ Tên</th>
										<th>Địa Chỉ</th>
										<th>Giới Tính </th>
										<th>Lớp Hành Chính</th>
										<th>Khóa Học</th>
										<th>Ngành</th>
									</tr>
								</thead>
								<tbody>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr>
									<td>
										<input type='checkbox' name='selected_students[]' value='" . htmlspecialchars($row['MaID']) . "'>
									</td>
									<td>" . htmlspecialchars($row['MaID']) . "</td>
									<td>" . htmlspecialchars($row['TenSV']) . "</td>
									<td>" . htmlspecialchars($row['GioiTinh']) . "</td>
									<td>" . htmlspecialchars($row['LopHC']) . "</td>
									<td>" . htmlspecialchars($row['KhoaHoc']) . "</td>
									<td>" . htmlspecialchars($row['TenKhoa']) . "</td>
									<td>
										<a href='student_details.php?MaID=" . $row['MaID'] . "'>Xem chi tiết</a>
									</td>
								  </tr>";
						}
						echo "</tbody></table>";
						echo "</form>";
					} else {
						echo "<p>Không có dữ liệu sinh viên.</p>";
					}
					$conn->close();
					?>

			</div>
			<div id="giangvien" class="tab-content" style="display: none;">
				<h2>Giảng viên</h2>
				
			</div>
			<div id="nhanvien" class="tab-content" style="display: none;">
				<h2>Nhân viên</h2>
				
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
	
	<div class="popup-overlay" id="popupOverlayDelete" style="z-index: 1000; " >
    <div  class="popup-content" style=" margin-left: 500px;  margin-top: 250px;">
        <h2>Bạn muốn xóa gì?</h2>
        <p>Hãy chọn loại dữ liệu cần xóa:</p>
        <form id="deleteForm" method="POST" action="handle_students.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="type" id="deleteType">
            <input type="hidden" name="selected_students[]" id="selectedStudents">
            <button type="button" onclick="submitDeleteForm('info')">Xóa thông tin</button>
            <button type="button" onclick="submitDeleteForm('all')">Xóa toàn bộ</button>
            <button type="button" id="closePopupDelete">Hủy</button>
        </form>
    </div>
</div>
			<script src="script.js"></script>		
	</body>
</html>


