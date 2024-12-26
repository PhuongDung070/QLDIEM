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
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý thông tin học phần</h2> </div>

		<div class="info-card" style="display: block;">
			<div>	
				<h2>Danh sách Học phần</h2>
				<div class="info-card search-filter-container" style="margin-bottom: 20px;">
					<form method="GET" action="">
					<input 
						type="text" 
						id="searchInput" 
						placeholder="Tìm kiếm theo tên, mã học phần..." 
						name="search"
						value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
						style="padding: 8px; width: 300px; margin-right: 10px;"
					>
					<select name="nganh">
						<option value="">-- Khoa --</option>
						<?php
							$nganhResult = $conn->query("SELECT DISTINCT TenKhoa FROM khoa");
							while ($row = $nganhResult->fetch_assoc()) {
								echo "<option value='" . htmlspecialchars($row['TenKhoa']) . "'" . 
									 ((isset($_GET['nganh']) && $_GET['nganh'] === $row['TenKhoa']) ? ' selected' : '') . 
									 ">" . htmlspecialchars($row['TenKhoa']) . "</option>";
							}
						?>
					</select>
					 <select name="lopHP">
								<option value="">-- Lớp học phần --</option>
								<?php
									$lopHPResult = $conn->query("SELECT DISTINCT TenHP FROM hocphan");
									while ($row = $lopHPResult->fetch_assoc()) {
										echo "<option value='" . htmlspecialchars($row['TenHP']) . "'" . 
											 ((isset($_GET['lopHP']) && $_GET['lopHP'] === $row['TenHP']) ? ' selected' : '') . 
											 ">" . htmlspecialchars($row['TenHP']) . "</option>";
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
					$whereClauses[] = "(hocphan.TenHP LIKE '%$search%' OR hocphan.MaLHP LIKE '%$search%')";
				}
				if (!empty($_GET['lopHP'])) {
					$lopHP = $conn->real_escape_string($_GET['lopHP']);
					$whereClauses[] = "hocphan.TenHP LIKE '$lopHP'";
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
							FROM hocphan 
							INNER JOIN khoa ON hocphan.KhoaPhuTrach = khoa.MaKhoa 
							$whereSql";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						echo "<form method='POST' action='handle_courses.php'>";
						echo "<div class='infor1'>";
						echo "<button type='button' onclick=\"window.location.href='add_course.php';\">+ Thêm mới</button>";
						echo "<button type='button' id='openPopupDelete'>Xóa</button>";
						echo "<button type='submit' name='action' value='edit' id='editButton'>Chỉnh sửa</button>";
						echo "</div>";
						echo " <div id='hiddenInputsContainer' style='display: none;'></div>";
						echo "<table border='1'>
								<thead>
									<tr>
										<th>Chọn</th>
										<th>Mã HP</th>
										<th>Lớp học phần</th>
										<th>Số tín chỉ</th>
										<th>Khoa phụ trách</th>
										<th>Học phần trước</th>
										<th>Số tiết</th>
										<th>Chi tiết</th>
									</tr>
								</thead>
								<tbody>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr>
									<td>
										<input type='checkbox' name='selected_courses[]' value='" . htmlspecialchars($row['MaLHP']) . "'>
									</td>
									<td>" . htmlspecialchars($row['MaLHP']) . "</td>
									<td>" . htmlspecialchars($row['TenHP']) . "</td>
									<td>" . htmlspecialchars($row['SoTinChi']) . "</td>
									<td>" . htmlspecialchars($row['TenKhoa']) . "</td>
									<td>" . htmlspecialchars($row['Hocphantruoc']) . "</td>
									<td>" . htmlspecialchars($row['SoTiet']) . "</td>
									<td>
										<a href='course_details.php?MaLHP=" . $row['MaLHP'] . "'><img src='img/see.png'></a>
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
            <button type="button" onclick="submitDeleteForm('all')">Xóa toàn bộ</button>
            <button type="button" id="closePopupDelete">Hủy</button>
        </form>
    </div>
	</div>
	<script src="script.js"></script>	
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		
			document.getElementById("editButton").addEventListener("click", function(event) {
				var selectedCourses = document.querySelectorAll("input[name='selected_courses[]']:checked");
				
				if (selectedCourses.length !== 1) {
					event.preventDefault(); // Ngừng việc gửi form nếu không có đúng 1 học phần được chọn
					alert("Vui lòng chọn chỉ một học phần để chỉnh sửa.");
				}
			});

			// Mở popup xóa
				document.querySelectorAll("button[type='button'][id='openPopupDelete']").forEach(button => {
			button.addEventListener("click", function (e)  {
				
				// Kiểm tra các ô đã chọn
				const selectedCourses = document.querySelectorAll("input[name='selected_courses[]']:checked");
				

				// Tạo mảng để lưu ID đã chọn
				let selectedIDs = [];

				if (selectedCourses.length === 0) {
					alert("Chưa chọn đối tượng để xóa");
					return;
				}

				// Lấy ID từ các checkbox đã chọn
				selectedCourses.forEach(course => selectedIDs.push(course.value));
				

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
				if (document.querySelectorAll("input[name='selected_courses[]']:checked").length > 0) {
					form.action = 'handle_courses.php';
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

