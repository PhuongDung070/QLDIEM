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
$ma_id = isset($_GET['MaID']) ? $_GET['MaID'] : null;
if ($ma_id) {
		$taikhoan_info = get_account_info($conn, $ma_id);
			if ($taikhoan_info) {
			$ten_dang_nhap = $taikhoan_info['TenDangNhap'];
			$mat_khau = $taikhoan_info['MatKhau'];
			} else {
			$error_msg = "Người dùng không tồn tại!";
			exit();
		}
	
		$result_khoa = get_khoa_list($conn);
	
	// Truy vấn chi tiết 
	
		$user_info = get_admin_info($conn, $ma_id);
		if ($user_info) {
		} else {
			echo "<p>Không tìm thấy nhân viên.</p>";
			exit();
		}
	} else {
		echo "<p>Không có mã nhân viên được cung cấp.</p>";
		exit();
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Lấy dữ liệu từ form
    $maID = $_POST['MaID']; 
    $tenNV = $_POST['TenNV'];
	$ngaySinh = $_POST['NgaySinh'];
    $diaChi = $_POST['DiaChi'];
    $gioiTinh = $_POST['GioiTinh'];
    $sdt = $_POST['SDT'];
    $email = $_POST['Email'];
	$phongBan = $_POST['PhongBan'];
    $chucVu = $_POST['ChucVu'];
	$tenDangNhap = $_POST['TenDangNhap'];
    $matKhau = $_POST['MatKhau'];
	$quyen = $_POST['Quyen'];

    // Gọi hàm cập nhật thông tin giảng viên
	$update_staff_result = update_staff_info($conn, $maID, $tenNV,  $ngaySinh, $diaChi, $gioiTinh, $sdt, $email);
    $update_admin_result = update_admin_info($conn,$maID, $phongBan, $chucVu);

    // Gọi hàm cập nhật thông tin tài khoản
    $update_account_result = update_account_info($conn, $maID, $tenDangNhap, $matKhau, $quyen);

    // Kiểm tra kết quả
    if ($update_staff_result === true && $update_admin_result === true && $update_account_result === true) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?MaID=" . urlencode($maID));
        exit(); 
    } else {
        echo "Lỗi cập nhật: ";
		if ($update_staff_result !== true) {
            echo "Nhân viên - " . $update_staff_result . "<br>";
        }
        if ($update_admin_result !== true) {
            echo "Giảng viên - " . $update_admin_result . "<br>";
        }
        if ($update_account_result !== true) {
            echo "Tài khoản - " . $update_account_result . "<br>";
        }
    }
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
							<div class="infor1">
							<button type="button" id="backButton">BACK</button>
							<button type="button" id="editButton">Chỉnh sửa</button>
							<button type="submit" style="display:none;" id="saveButton" name="update">Lưu thông tin</button>
							<button type="button" id="openPopupDelete">Xóa</button>
							</div>
							<table border="1">
								<tr>
									<td><strong>Mã nhân viên</strong></td>
									<td>
										<input type="text" name="MaID" value="<?php echo htmlspecialchars($user_info['MaID']); ?>" readonly />
									</td>
								</tr>
								<tr>
									<td><strong>Họ tên</strong></td>
									<td>
										<input type="text" name="TenNV" value="<?php echo htmlspecialchars($user_info['TenNV']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Địa chỉ</strong></td>
									<td>
										<input type="text" name="DiaChi"  value="<?php echo htmlspecialchars($user_info['DiaChi']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Ngày sinh</strong></td>
									<td>
										<input type="date" name="NgaySinh" value="<?php echo htmlspecialchars($user_info['NgaySinh']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Giới tính</strong></td>
									<td>
										<select name="GioiTinh" disabled>
											<option value="Nam" <?php if ($user_info['GioiTinh'] == 'Nam') echo 'selected'; ?> >Nam</option>
											<option value="Nữ" <?php if ($user_info['GioiTinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><strong>Số điện thoại</strong></td>
									<td>
										<input type="text" name="SDT" value="<?php echo htmlspecialchars($user_info['SDT']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Email</strong></td>
									<td>
										<input type="email" name="Email" value="<?php echo htmlspecialchars($user_info['Email']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Phòng ban</strong></td>
									<td>
										<input type="text" name="PhongBan" value="<?php echo htmlspecialchars($user_info['PhongBan']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Chức Vụ</strong></td>
									<td>
										<input type="text" name="ChucVu" value="<?php echo htmlspecialchars($user_info['ChucVu']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Tên Đăng Nhập</strong></td>
									<td>
										<input type="text" name="TenDangNhap" value="<?php echo htmlspecialchars($taikhoan_info['TenDangNhap']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Mật Khẩu</strong></td>
									<td>
										<input type="text" name="MatKhau" value="<?php echo htmlspecialchars($taikhoan_info['MatKhau']); ?>" disabled />
									</td>
								</tr>
								<tr>
									<td><strong>Quyền</strong></td>
									<td>
										<input type="text" name="Quyen" value="<?php echo htmlspecialchars($taikhoan_info['Quyen']); ?>" disabled />
									</td>
								</tr>
								<tr style="display:none;">
									<td> <input type="checkbox" name="selected_admins[]" value="<?php echo htmlspecialchars($user_info['MaID']); ?> " checked> </td>
								</tr>
							</table>				
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
		<div class="popup-overlay" id="popupOverlayDelete" style="z-index: 1000; " >
			<div  class="popup-content" style=" margin-left: 500px;  margin-top: 250px;">
				<h2>Bạn muốn xóa gì?</h2>
				<p>Hãy chọn loại dữ liệu cần xóa:</p>
				<div id="selectedIDsDisplay" style="margin-top: 10px; font-weight: bold;"></div>
				<form id="deleteForm" method="POST">
					<input type="hidden" name="action" value="delete">
					<input type="hidden" name="type" id="deleteType">
					<input type="hidden" name="selected_ids[]" id="selectedIDs">
					<button type="button" onclick="submitDeleteForm('info')">Xóa thông tin</button>
					<button type="button" onclick="submitDeleteForm('all')">Xóa toàn bộ</button>
					<button type="button" id="closePopupDelete">Hủy</button>
				</form>
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
        if (input.name !== "MaID") {
            input.disabled = true;
        }
    });

    // Event listener for the Edit button
if (editButton) {
    editButton.addEventListener("click", function () {
        formInputs.forEach(input => {
            if (input.name !== "MaID") {
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
        window.location.href = "user_management.php"; // Redirect
    });
}


document.querySelectorAll("#openPopupDelete").forEach(button => {
    button.addEventListener("click", function () {
        // Lấy các checkbox đã chọn
        const selectedAdmins = document.querySelectorAll("input[name='selected_admins[]']:checked");
        const selectedIDs = [];

        // Thu thập giá trị từ các checkbox đã chọn
        selectedAdmins.forEach(admin => selectedIDs.push(admin.value));

        // Kiểm tra nếu không có ID nào được chọn
        if (selectedIDs.length === 0) {
            alert("Vui lòng chọn đối tượng cần xóa.");
            return;
        }

        // Hiển thị danh sách các ID đã chọn trong popup
        const selectedIDsDisplay = document.getElementById("selectedIDsDisplay");
        selectedIDsDisplay.innerText = `Các ID đã chọn: ${selectedIDs.join(", ")}`;

        // Gán giá trị vào input ẩn để gửi đến server
        document.getElementById("selectedIDs").value = selectedIDs.join(",");

        // Hiển thị popup
        document.getElementById("popupOverlayDelete").style.display = "block";
    });
});

// Đóng popup xóa
document.getElementById("closePopupDelete").addEventListener("click", function () {
    document.getElementById("popupOverlayDelete").style.display = "none";
});


			// Hàm gửi form xóa
			function submitDeleteForm(deleteType) {
				if (deleteType === "info") {
					document.getElementById("deleteType").value = "info";
				} else if (deleteType === "all") {
					document.getElementById("deleteType").value = "all";
				}
				document.getElementById("deleteForm").submit();
			}
			

		</script>
		</body>
	</html>

