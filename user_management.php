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
    header("Location: thongbaodangnhap.php?message=Người dùng không tồn tại!");
    exit();
}
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'no_selection') {
        echo "<div class='alert alert-warning'>Chưa chọn sinh viên</div>";
    } elseif ($_GET['message'] === 'multiple_selection') {
        echo "<div class='alert alert-warning'>Chỉ chọn 1 sinh viên để chỉnh sửa</div>";
    }
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
				<a href='course_management.php'><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thông tin học phần	</a>
				<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thời khóa biểu</a>
				<a href=''><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Báo cáo thống kê</a>
			</div>
		</div>
		<div class="main-content" style="margin-top: 142px;">
		<div class="info-container">
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý thông tin người dùng</h2> </div>
		<div class="info-card tabs">
				<button class="tab-button" onclick="openTab(event, 'sinhvien')">Sinh viên</button>
				<button class="tab-button" onclick="openTab(event, 'giangvien')">Giảng viên</button>
				<button class="tab-button" onclick="openTab(event, 'nhanvien')">Nhân viên</button>
		</div>
		<div class="info-card" style="display: block;">
			<div id="sinhvien" class="tab-content" >	
				<h2>Danh sách Sinh viên</h2>
				<div class="info-card search-filter-container" style="margin-bottom: 20px;">
					<form method="GET" action="">
					<input 
						type="text" 
						id="searchInput" 
						placeholder="Tìm kiếm theo tên, mã sinh viên..." 
						name="search"
						value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
						style="padding: 8px; width: 300px; margin-right: 10px;"
					>
					<select name="nganh">
						<option value="">-- Ngành học --</option>
						<?php
							$nganhResult = $conn->query("SELECT DISTINCT TenKhoa FROM khoa");
							while ($row = $nganhResult->fetch_assoc()) {
								echo "<option value='" . htmlspecialchars($row['TenKhoa']) . "'" . 
									 ((isset($_GET['nganh']) && $_GET['nganh'] === $row['TenKhoa']) ? ' selected' : '') . 
									 ">" . htmlspecialchars($row['TenKhoa']) . "</option>";
							}
						?>
					</select>
					<select name="gender">
						<option value="">-- Giới tính --</option>
						<option value="Nam" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
						<option value="Nữ" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
					</select>
					 <select name="lopHC">
								<option value="">-- Lớp hành chính --</option>
								<?php
									$lopHCResult = $conn->query("SELECT DISTINCT LopHC FROM sinhvien");
									while ($row = $lopHCResult->fetch_assoc()) {
										echo "<option value='" . htmlspecialchars($row['LopHC']) . "'" . 
											 ((isset($_GET['lopHC']) && $_GET['lopHC'] === $row['LopHC']) ? ' selected' : '') . 
											 ">" . htmlspecialchars($row['LopHC']) . "</option>";
									}
								?>
					</select>
					<select name="khoaHoc">
						<option value="">-- Khóa học --</option>
						<?php
							$khoaHocResult = $conn->query("SELECT DISTINCT KhoaHoc FROM sinhvien");
							while ($row = $khoaHocResult->fetch_assoc()) {
								echo "<option value='" . htmlspecialchars($row['KhoaHoc']) . "'" . 
									 ((isset($_GET['khoaHoc']) && $_GET['khoaHoc'] === $row['KhoaHoc']) ? ' selected' : '') . 
									 ">" . htmlspecialchars($row['KhoaHoc']) . "</option>";
							}
						?>
					</select>		
						<button type="submit">Tìm kiếm</button>
					</form>
				</div>
				
				<?php
				$whereClauses = [];
				if (!empty($_GET['search'])) {
					$search = $conn->real_escape_string($_GET['search']);
					$whereClauses[] = "(sinhvien.TenSV LIKE '%$search%' OR sinhvien.MaID LIKE '%$search%')";
				}
				if (!empty($_GET['gender'])) {
					$gender = $conn->real_escape_string($_GET['gender']);
					$whereClauses[] = "sinhvien.GioiTinh = '$gender'";
				}
				if (!empty($_GET['lopHC'])) {
					$lopHC = $conn->real_escape_string($_GET['lopHC']);
					$whereClauses[] = "sinhvien.LopHC = '$lopHC'";
				}
				if (!empty($_GET['khoaHoc'])) {
					$khoaHoc = $conn->real_escape_string($_GET['khoaHoc']);
					$whereClauses[] = "sinhvien.KhoaHoc = '$khoaHoc'";
				}
				if (!empty($_GET['nganh'])) {
					$nganh = $conn->real_escape_string($_GET['nganh']);
					$whereClauses[] = "khoa.TenKhoa = '$nganh'";
				}

				// Nối các điều kiện lọc lại với nhau
				$whereSql = '';
				if (count($whereClauses) > 0) 
				{
					$whereSql = "WHERE " . implode(' AND ', $whereClauses);
				}
					$sql = "SELECT * 
							FROM sinhvien 
							INNER JOIN khoa ON sinhvien.MaKhoa = khoa.MaKhoa 
							$whereSql";
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
										<th>Khóa học</th>
										<th>Ngành</th>
										<th>Chi tiết</th>
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
									<td>" . htmlspecialchars($row['DiaChi']) . "</td>
									<td>" . htmlspecialchars($row['GioiTinh']) . "</td>
									<td>" . htmlspecialchars($row['LopHC']) . "</td>
									<td>" . htmlspecialchars($row['KhoaHoc']) . "</td>
									<td>" . htmlspecialchars($row['TenKhoa']) . "</td>
									<td>
										<a href='student_details.php?MaID=" . $row['MaID'] . "'><img src='img/see.png'></a>
									</td>
								  </tr>";
						}
						echo "</tbody></table>";
						echo "</form>";
					} else {
						echo "<p>Không có dữ liệu sinh viên.</p>";
					}
					
					?>

			</div>
			<div id="giangvien" class="tab-content" style="display: none;">
				<h2>Giảng viên</h2>
				<div class="info-card search-filter-container" style="margin-bottom: 20px;">
					<form method="GET" action="">
						<input 
						type="text" 
						id="searchInput" 
						placeholder="Tìm kiếm theo tên, mã nhân viên..." 
						name="search"
						value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
						style="padding: 8px; width: 300px; margin-right: 10px;"
						>
						<select name="gender">
							<option value="">-- Giới tính --</option>
							<option value="Nam" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
							<option value="Nữ" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
						</select>
						<select name="khoa">
							<option value="">-- Khoa --</option>
							<?php
								$khoaResult = $conn->query("SELECT DISTINCT TenKhoa FROM khoa");
								while ($row = $khoaResult->fetch_assoc()) {
									echo "<option value='" . htmlspecialchars($row['TenKhoa']) . "'" . 
										 ((isset($_GET['khoa']) && $_GET['khoa'] === $row['TenKhoa']) ? ' selected' : '') . 
										 ">" . htmlspecialchars($row['TenKhoa']) . "</option>";
								}
							?>
						</select>
							<button type="submit">Tìm kiếm</button>
					</form>
				</div>
				<?php
				$whereClauses = [];
				if (!empty($_GET['search'])) {
					$search = $conn->real_escape_string($_GET['search']);
					$whereClauses[] = "(nhanvien.TenNV LIKE '%$search%' OR nhanvien.MaID LIKE '%$search%')";
				}
				if (!empty($_GET['gender'])) {
					$gender = $conn->real_escape_string($_GET['gender']);
					$whereClauses[] = "nhanvien.GioiTinh = '$gender'";
				}
				if (!empty($_GET['khoa'])) {
					$khoa = $conn->real_escape_string($_GET['khoa']);
					$whereClauses[] = "khoa.TenKhoa = '$khoa'";
				}
				
				$whereSql = '';
				if (count($whereClauses) > 0) 
				{
					$whereSql = "WHERE " . implode(' AND ', $whereClauses);
				}
					$sql = "SELECT * 
							FROM giangvien 
							INNER JOIN khoa ON giangvien.MaKhoa = khoa.MaKhoa 
							INNER JOIN nhanvien ON giangvien.MaID = nhanvien.MaID
							$whereSql";
					$result = $conn->query($sql);
				if ($result->num_rows > 0) {
						echo "<form method='POST' action='handle_teachers.php'>";
						echo "<div class='infor1'>";
						echo "<button type='button' onclick=\"window.location.href='add_teacher.php';\">+ Thêm mới</button>";
						echo "<button type='button' id='openPopupDelete'>Xóa</button>";
						echo "<button type='submit' name='action' value='edit'>Chỉnh sửa</button>";
						echo "</div>";
						echo "<table border='1'>
								<thead>
									<tr>
										<th>Chọn</th>
										<th>Mã NV</th>
										<th>Họ Tên</th>
										<th>Địa Chỉ</th>
										<th>Giới Tính </th>
										<th>Học Vị</th>
										<th>Khoa</th>
										<th>Chi tiết</th>
									</tr>
								</thead>
								<tbody>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr>
									<td>
										<input type='checkbox' name='selected_teachers[]' value='" . htmlspecialchars($row['MaID']) . "'>
									</td>
									<td>" . htmlspecialchars($row['MaID']) . "</td>
									<td>" . htmlspecialchars($row['TenNV']) . "</td>
									<td>" . htmlspecialchars($row['DiaChi']) . "</td>
									<td>" . htmlspecialchars($row['GioiTinh']) . "</td>
									<td>" . htmlspecialchars($row['HocVi']) . "</td>
									<td>" . htmlspecialchars($row['TenKhoa']) . "</td>
									<td>
										<a href='teacher_details.php?MaID=" . $row['MaID'] . "'><img src='img/see.png'></a>
									</td>
								  </tr>";
						}
						echo "</tbody></table>";
						echo "</form>";
					} else {
						echo "<p>Không có dữ liệu Giảng viên.</p>";
					}
					?>
			</div>
			<div id="nhanvien" class="tab-content" style="display: none;">
				<h2>Nhân viên</h2>
				<div class="info-card search-filter-container" style="margin-bottom: 20px;">
					<form method="GET" action="">
						<input 
						type="text" 
						id="searchInput" 
						placeholder="Tìm kiếm theo tên, mã nhân viên..." 
						name="search"
						value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
						style="padding: 8px; width: 300px; margin-right: 10px;"
						>
						<select name="gender">
							<option value="">-- Giới tính --</option>
							<option value="Nam" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
							<option value="Nữ" <?php echo (isset($_GET['gender']) && $_GET['gender'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
						</select>
						<select name="phong">
							<option value="">--Phòng ban--</option>
							<?php
								$phongResult = $conn->query("SELECT DISTINCT PhongBan FROM nhanvienquanly");
								while ($row = $phongResult->fetch_assoc()) {
									echo "<option value='" . htmlspecialchars($row['PhongBan']) . "'" . 
										 ((isset($_GET['phong']) && $_GET['phong'] === $row['PhongBan']) ? ' selected' : '') . 
										 ">" . htmlspecialchars($row['PhongBan']) . "</option>";
								}
							?>
						</select>
						<select name="chucvu">
							<option value="">--Chức vụ--</option>
							<?php
								$chucvuResult = $conn->query("SELECT DISTINCT ChucVu FROM nhanvienquanly");
								while ($row = $chucvuResult->fetch_assoc()) {
									echo "<option value='" . htmlspecialchars($row['ChucVu']) . "'" . 
										 ((isset($_GET['chucvu']) && $_GET['chucvu'] === $row['ChucVu']) ? ' selected' : '') . 
										 ">" . htmlspecialchars($row['ChucVu']) . "</option>";
								}
							?>
						</select>
							<button type="submit">Tìm kiếm</button>
					</form>
				</div>
				<?php
				$whereClauses = [];
				if (!empty($_GET['search'])) {
					$search = $conn->real_escape_string($_GET['search']);
					$whereClauses[] = "(nhanvien.TenNV LIKE '%$search%' OR nhanvien.MaID LIKE '%$search%')";
				}
				if (!empty($_GET['gender'])) {
					$gender = $conn->real_escape_string($_GET['gender']);
					$whereClauses[] = "nhanvien.GioiTinh = '$gender'";
				}
				if (!empty($_GET['phong'])) {
					$phong = $conn->real_escape_string($_GET['phong']);
					$whereClauses[] = "nhanvienquanly.PhongBan = '$phong'";
				}
				if (!empty($_GET['chucvu'])) {
					$chucvu = $conn->real_escape_string($_GET['chucvu']);
					$whereClauses[] = "nhanvienquanly.ChucVu = '$chucvu'";
				}				
				$whereSql = '';
				if (count($whereClauses) > 0) 
				{
					$whereSql = "WHERE " . implode(' AND ', $whereClauses);
				}
					$sql = "SELECT * 
							FROM nhanvienquanly
							INNER JOIN nhanvien ON nhanvienquanly.MaID = nhanvien.MaID
							$whereSql";
					$result = $conn->query($sql);
				if ($result->num_rows > 0) {
						echo "<form method='POST' action='handle_admins.php'>";
						echo "<div class='infor1'>";
						echo "<button type='button' onclick=\"window.location.href='add_admin.php';\">+ Thêm mới</button>";
						echo "<button type='button' id='openPopupDelete'>Xóa</button>";
						echo "<button type='submit' name='action' value='edit'>Chỉnh sửa</button>";
						echo "</div>";
						echo "<table border='1'>
								<thead>
									<tr>
										<th>Chọn</th>
										<th>Mã NV</th>
										<th>Họ Tên</th>
										<th>Địa Chỉ</th>
										<th>Giới Tính </th>
										<th>Phòng ban</th>
										<th>Chức vụ</th>
										<th>Chi tiết</th>
									</tr>
								</thead>
								<tbody>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr>
									<td>
										<input type='checkbox' name='selected_admins[]' value='" . htmlspecialchars($row['MaID']) . "'>
									</td>
									<td>" . htmlspecialchars($row['MaID']) . "</td>
									<td>" . htmlspecialchars($row['TenNV']) . "</td>
									<td>" . htmlspecialchars($row['DiaChi']) . "</td>
									<td>" . htmlspecialchars($row['GioiTinh']) . "</td>
									<td>" . htmlspecialchars($row['PhongBan']) . "</td>
									<td>" . htmlspecialchars($row['ChucVu']) . "</td>
									<td>
										<a href='admin_details.php?MaID=" . $row['MaID'] . "'><img src='img/see.png'></a>
									</td>
								  </tr>";
						}
						echo "</tbody></table>";
						echo "</form>";
					} else {
						echo "<p>Không có dữ liệu Giảng viên.</p>";
					}
					?>
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
        <form id="deleteForm" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="type" id="deleteType">
            <input type="hidden" name="selected_ids[]" id="selectedIDs">
			<div id="selectedIDsDisplay" style="margin-top: 10px; font-weight: bold;"></div>
            <button type="button" onclick="submitDeleteForm('info')">Xóa thông tin</button>
            <button type="button" onclick="submitDeleteForm('all')">Xóa toàn bộ</button>
            <button type="button" id="closePopupDelete">Hủy</button>
        </form>
    </div>
	</div>
	<script src="script.js"></script>	
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			// Hàm mở tab
			window.openTab = function(evt, tabId) {
				// Ẩn tất cả nội dung tab
				const contents = document.querySelectorAll('.tab-content');
				contents.forEach(content => content.style.display = 'none');

				// Xóa class 'active' trên tất cả nút
				const buttons = document.querySelectorAll('.tab-button');
				buttons.forEach(button => button.classList.remove('active'));

				// Hiển thị tab được chọn
				const activeTab = document.getElementById(tabId);
				if (activeTab) {
					activeTab.style.display = 'block';
				}

				// Thêm class 'active' vào nút được chọn
				evt.currentTarget.classList.add('active');

				// Lưu trạng thái tab vào localStorage
				localStorage.setItem('activeTab', tabId);
			};

			// Khôi phục tab được lưu khi tải lại trang
			function restoreTabState() {
				const activeTab = localStorage.getItem('activeTab') || 'sinhvien'; // Mặc định là 'sinhvien'
				const activeButton = document.querySelector(`.tab-button[onclick="openTab(event, '${activeTab}')"]`);
				if (activeButton) {
					activeButton.click();
				}
			}
			restoreTabState();

			// Mở popup xóa
				document.querySelectorAll("button[type='button'][id='openPopupDelete']").forEach(button => {
			button.addEventListener("click", function (e)  {
				
				// Kiểm tra các ô đã chọn
				const selectedStudents = document.querySelectorAll("input[name='selected_students[]']:checked");
				const selectedTeachers = document.querySelectorAll("input[name='selected_teachers[]']:checked");
				const selectedAdmins = document.querySelectorAll("input[name='selected_admins[]']:checked");

				// Tạo mảng để lưu ID đã chọn
				let selectedIDs = [];

				if (selectedStudents.length === 0 && selectedTeachers.length === 0 && selectedAdmins.length === 0) {
					alert("Chưa chọn đối tượng để xóa");
					return;
				}

				// Lấy ID từ các checkbox đã chọn
				selectedStudents.forEach(student => selectedIDs.push(student.value));
				selectedTeachers.forEach(teacher => selectedIDs.push(teacher.value));
				selectedAdmins.forEach(admin => selectedIDs.push(admin.value));

				// Hiển thị các ID đã chọn trong popup
				document.getElementById('selectedIDsDisplay').innerText = "ID đã chọn: " + selectedIDs.join(', ');

				// Gán ID đã chọn vào input hidden
				const selectedIDsInput = document.getElementById('selectedIDs');
				selectedIDsInput.value = ''; // Đặt giá trị ban đầu là rỗng

				// Tạo các input ẩn cho từng ID đã chọn
				selectedIDs.forEach(id => {
					const input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'selected_ids[]'; // Tên mảng
					input.value = id; // Giá trị ID
					selectedIDsInput.parentNode.appendChild(input); // Thêm vào form
				});

				// Hiển thị popup xóa
				document.getElementById('popupOverlayDelete').style.display = 'block';
			});
		});

			// Hàm gửi form xóa
			window.submitDeleteForm = function(type) {
				const selectedIDs = document.querySelectorAll("input[name='selected_ids[]']"); // Lấy tất cả input ẩn

				if (selectedIDs.length === 0) {
					alert("Vui lòng chọn ít nhất một đối tượng để xóa.");
					return;
				}

				// Gán giá trị cho trường type
				document.getElementById('deleteType').value = type;

				// Thay đổi action của form dựa trên loại đối tượng
				const form = document.getElementById('deleteForm');
				if (document.querySelectorAll("input[name='selected_students[]']:checked").length > 0) {
					form.action = 'handle_students.php';
				} else if (document.querySelectorAll("input[name='selected_teachers[]']:checked"). length > 0) {
					form.action = 'handle_teachers.php';
				} else if (document.querySelectorAll("input[name='selected_admins[]']:checked").length > 0) {
					form.action = 'handle_admins.php';
				} else {
					alert("Vui lòng chọn ít nhất một đối tượng để xóa.");
					return; // Ngăn không cho gửi form nếu không có đối tượng nào được chọn
				}

				// Gửi form
				form.submit();
			};
		});
	</script>
	</body>
</html>

