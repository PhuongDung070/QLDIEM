	<?php
	session_start();
	include 'connect_database.php';
	$conn = connect_to_database("localhost", "root","",	"qldiem");
	// Kiểm tra nếu người dùng đã đăng nhập
	$error_msg = ""; 
	if (!isset($_SESSION['MaID'])) {
		header("Location: login.php"); 
		exit();
	}

	// Lấy thông tin người quản lý từ session
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

	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Lấy dữ liệu từ form
    $maLHP = $_POST['MaLHP']; 
    $tenHP = $_POST['TenHP'];
	$soTC = $_POST['SoTinChi'];
    $khoaPT = $_POST['KhoaPhuTrach'];
    $hpTruoc = $_POST['Hocphantruoc'];
    $soTiet = $_POST['SoTiet'];
    $selected_khoa = $_POST["selected_khoa"];
	$maDS = generate_unique_maDS($conn);
   
	$sql_hocphan = "INSERT INTO hocphan (MaLHP, TenHP, SoTinChi, KhoaPhuTrach, SoTiet) 
                     VALUES (?, ?, ?, ?,?)";
    $stmt_hocphan = $conn->prepare($sql_hocphan);
    $stmt_hocphan->bind_param("ssssss", $maLHP, $tenHP, $soTC, $khoaPT, $soTiet);

    // Thực thi câu lệnh SQL thêm học phần
    if ($stmt_hocphan->execute()) {
        // Thêm thông tin học phần vào bảng danhsachhocphan
        $sql_dshocphan = "INSERT INTO danhsachhocphan (MaDS, MaLHP, MaKhoa) 
                         VALUES (?, ?, ?)";
        $stmt_dshocphan = $conn->prepare($sql_dshocphan);
        $stmt_dshocphan->bind_param("sss", $maDS, $maLHP, $maKhoa);

        // Thực thi câu lệnh SQL thêm sinh viên
        if ($stmt_dshocphan->execute()) {
            $success_msg = "Thêm học phần thành công!";
        } else {
            $error_msg = "Lỗi khi thêm học phần: " . $stmt_dshocphan->error;
        }

        // Đóng câu lệnh thêm sinh viên
        $stmt_dshocphan>close();
    } else {
        $error_msg = "Lỗi khi thêm tài khoản: " . $stmt_hocphan->error;
    }

    // Đóng câu lệnh thêm tài khoản
    $stmt_hocphan->close();
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
					<a href='course_management.php'><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Quản lý thông tin học phần</a>
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
							<div class="infor1">
							<button type="button" id="backButton">BACK</button>
							<button type="submit" name="add">Thêm học phần</button>
							</div>
							<div>
								<h4> Thông tin học phần </h4>
							<table border="1">
								<tr>
									<td><strong>Mã học phần</strong></td>
									<td>
										<input type="text" name="MaLHP" required />
									</td>
								</tr>
								<tr>
									<td><strong>Tên học phần</strong></td>
									<td>
										<input type="text" name="TenHP" required />
									</td>
								</tr>
								<tr>
									<td><strong>Số tín chỉ</strong></td>
									<td>
										<select name="SoTinChi" id="SoTinChi" onchange="updateSoTiet()" required>
											<option value="">Chọn Số tín chỉ</option>
											<option value="2">2</option>
											<option value="3">3</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><strong>Số tiết</strong></td>
									<td>
										<input type="text" name="SoTiet" id="SoTiet" value=" " required >
									</td>
								</tr>
								<tr>
									<td><strong>Khoa phụ trách</strong></td>
									<td>
										<select name="KhoaPhuTrach" required>
										<option value="">Chọn Khoa phụ trách</option>
										<?php
										while ($khoa = $result_khoa->fetch_assoc()) {
											echo "<option value='" . $khoa['MaKhoa'] . "'>" . htmlspecialchars($khoa['TenKhoa']) . "</option>";
										}
										?>
									</select>
									</td>
								</tr>
								<tr>
									<td><strong>Học phần trước</strong></td>
									<td>
									<select name="Hocphantruoc" required>
										<option value=""> Chọn Học phần trước</option>
										<?php
										while ($hocphan = $result_hocphan->fetch_assoc()) {
											echo "<option value='" . $hocphan['MaLHP'] . "'>" . htmlspecialchars($hocphan['TenHP']) . "</option>";
										}
										?>
									</select>
									</td>
								</tr>								
							</table>
							</div>
							<div>
								<h4> Thông tin ngành học </h4>
								<h4>(Lựa chọn khoa có học môn học phần <?php echo htmlspecialchars($course_info['TenHP']); ?>) </h4>
								<table>
									<tr>
										<td>
											<?php
											$conn = connect_to_database("localhost", "root","",	"qldiem");
											$khoa_list = get_khoa_list($conn);
											if ($khoa_list->num_rows > 0) {
												echo "<div class='khoa-list' style ='display: flex; flex-wrap: wrap; justify-content:space-between;'>";
												while ($khoa = $khoa_list->fetch_assoc()) {
													echo "<div class='khoa-item' style='  width: 48%; margin-bottom: 10px;  box-sizing: border-box;'>";
													echo "<input type='checkbox' name='selected_khoa[]' value='" . htmlspecialchars($khoa["MaKhoa"]) . "'>";
													echo htmlspecialchars($khoa["TenKhoa"]);
													echo "</div>";
												}
												echo "</div>";
											}
											?>
										</td>
									</tr>
								</table>
							</div>
						</form>
						
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
	
		 <script src="script.js"> </script>
		 <script>
			const editButton = document.getElementById("editButton");
			const saveButton = document.getElementById("saveButton");
			const backButton = document.getElementById("backButton");
			const formInputs = document.querySelectorAll("form input, form select");

			// Disable all inputs by default except MaID
			formInputs.forEach(input => {
				if (input.name !== "MaLHP") {
					input.disabled = true;
				}
			});

			// Event listener for the Edit button
		if (editButton) {
			editButton.addEventListener("click", function () {
				formInputs.forEach(input => {
					if (input.name !== "MaLHP") {
						input.disabled = false;
					}
				});
				saveButton.style.display = "inline-block";
				editButton.style.display = "none";
			});
		}

		if (saveButton) {
			saveButton.addEventListener("click", function () {
				alert("Lưu thông tin thành công!");
			});
		}

		if (backButton) {
			backButton.addEventListener("click", function () {
				window.location.href = "course_management.php"; // Redirect
			});
		}

			function updateSoTiet() {
    const soTinChiSelect = document.getElementById('SoTinChi'); // Lấy dropdown số tín chỉ
    const soTietInput = document.getElementById('SoTiet');     // Lấy ô nhập số tiết

    // Kiểm tra nếu các phần tử tồn tại
    if (!soTinChiSelect || !soTietInput) {
        console.error('Không tìm thấy phần tử SoTinChi hoặc SoTiet');
        return;
    }

    // Lấy giá trị đã chọn trong dropdown
    const selectedSoTinChi = soTinChiSelect.value;

    // Cập nhật giá trị số tiết dựa trên số tín chỉ
    if (selectedSoTinChi === '2') {
        soTietInput.value = '36'; // 2 tín chỉ = 36 tiết
    } else if (selectedSoTinChi === '3') {
        soTietInput.value = '45'; // 3 tín chỉ = 45 tiết
    } else {
        soTietInput.value = ''; // Xóa giá trị nếu không có gì được chọn
    }
}
	
				
		</script>
		</body>
	</html>


	