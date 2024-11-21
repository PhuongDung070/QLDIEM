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
		$student_info = get_student_info($conn, $user_id);


		if ($student_info) {
			$ma_sinh_vien = $student_info['MaID'];
			$ten_sinh_vien = $student_info['TenSV'];
			$ma_khoa = $student_info['MaKhoa'];
		} else {
			$error_msg ="Người dùng không tồn tại!";
			exit();
		}

	
		$sql_hocphan = "SELECT *
					FROM hocphan
					INNER JOIN danhsachhocphan ON hocphan.MaLHP = danhsachhocphan.MaLHP
					INNER JOIN khoa ON hocphan.KhoaPhuTrach = khoa.MaKhoa
					WHERE danhsachhocphan.MaKhoa = ?";
	$stmt_hocphan = $conn->prepare($sql_hocphan);
	$stmt_hocphan->bind_param("s", $ma_khoa);
	$stmt_hocphan->execute();
	$result_hocphan = $stmt_hocphan->get_result();

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
							<a href="student_dashboard.php"><img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Hồ sơ của tôi</a>
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
						<h3><?php echo htmlspecialchars($ten_sinh_vien); ?></h3>
						<h3><?php echo htmlspecialchars($ma_sinh_vien); ?></h3>
						<h3>Sinh viên</h3>
					</div>
				</div>
				<div>
					<h4 class="title"> TRANG CÁ NHÂN </h4> 
					<a href='student_dashboard.php'> <img src="img/user1.png" alt="Logo" class="logo" style="width: 15px;"> Thông tin cá nhân</a>
					<a href=''><img src="img/bell.png" alt="Logo" class="logo" style="width: 15px;"> Thông báo</a>
				</div>
				<div>
					<h4 class="title"> TRA CỨU THÔNG TIN</h4>
					<a href='course.php'><img src="img/list.png" alt="Logo" class="logo" style="width: 15px;"> Chương trình đào tạo</a>
					<a href=''><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Thời khóa biểu</a>
					<a href='score_sv.php'><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Kết quả học tập</a>
				</div>
			</div>
			<div class="main-content" style="margin-top: 142px;">
			<div class="info-container">
			<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Chương trình đào tạo</h2> </div>

			<div class="info-card">
				<div class="infor-right">
				<h2><strong><?php echo htmlspecialchars($student_info['TenKhoa'] ?? "Không có"); ?></strong></h2>
				 <table class="infor-table">
					<thead>
									<tr>
										<th>Mã Học Phần</th>
										<th>Tên Học Phần</th>
										<th>Số Tín Chỉ</th>
										<th>Số Tiết</th>
										<th>Khoa Phụ Trách</th>
										<th>Học Phần Trước</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									if ($result_hocphan->num_rows > 0) {
										while ($row = $result_hocphan->fetch_assoc()) {
											echo "<tr>
													<td>" . $row['MaLHP'] . "</td>
													<td>" . $row['TenHP'] . "</td>
													<td>" . $row['SoTinChi'] . "</td>
													<td>" . $row['SoTiet'] . "</td>
													<td>" . $row['TenKhoa'] . "</td>
													<td>" . $row['Hocphantruoc'] . "</td>
												</tr>";
										}
									} else {
										echo "<tr><td colspan='6'>Không có học phần nào.</td></tr>";
									}
									?>
								</tbody>
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
			
		<script>
	document.addEventListener('DOMContentLoaded', function () {
		// Mở/đóng slidebar
		const slidebar = document.getElementById('slidebar');
		const menuToggle = document.getElementById('menu-toggle');

		menuToggle.addEventListener('click', function () {
			slidebar.classList.toggle('active');
		});

		// Dropdown menu của profile
		const profile = document.querySelector('.profile');
		const dropdownMenu = document.getElementById('dropdownMenu');

		profile.addEventListener('click', function (event) {
		// Toggle hiển thị dropdown menu
			dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
			event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
		});

		// Đóng dropdown menu khi nhấn ra ngoài
		document.addEventListener('click', function (event) {
			if (!profile.contains(event.target)) {
				dropdownMenu.style.display = 'none';
			}
		});
	});
	document.addEventListener("DOMContentLoaded", function () {
		const sidebar = document.getElementById("slidebar");
		if (sidebar.scrollHeight > sidebar.clientHeight) {
			sidebar.style.overflowY = "auto"; // Thêm thanh trượt nếu nội dung quá dài
		} else {
			sidebar.style.overflowY = "hidden"; // Ẩn thanh trượt nếu không cần thiết
		}
	});
		window.addEventListener('resize', function () {
			if (window.innerWidth <= 900) {
				slidebar.classList.remove('active'); // Tự động đóng slidebar
			}
		});
		document.addEventListener("DOMContentLoaded", () => {
		const openPopup = document.getElementById("openPopup");
		const closePopup = document.getElementById("closePopup");
		const popupOverlay = document.getElementById("popupOverlay");

		// Hiển thị popup
		openPopup.addEventListener("click", (e) => {
			e.preventDefault(); 
			popupOverlay.style.display = "flex"; 
		});

		// Đóng popup
		closePopup.addEventListener("click", () => {
			popupOverlay.style.display = "none";
		});
	});
	  document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
		event.preventDefault(); 
		
		const oldPassword = document.getElementById("oldPassword").value;
		const newPassword = document.getElementById("newPassword").value;
		const confirmPassword = document.getElementById("confirmPassword").value;
	   
		if (newPassword !== confirmPassword) {
		  alert("Mật khẩu xác nhận không trùng khớp.");
		  return;
		}

		// Tạo đối tượng FormData để gửi dữ liệu qua AJAX
		const formData = new FormData();
		formData.append("oldPassword", oldPassword);
		formData.append("newPassword", newPassword);
		formData.append("confirmPassword", confirmPassword);
		

		const xhr = new XMLHttpRequest();
		xhr.open("POST", "change_password.php", true);

		xhr.onload = function() {
		  if (xhr.status === 200) {
			alert(xhr.responseText); 
			if (xhr.responseText === "Đổi mật khẩu thành công.") {
			  document.getElementById("popupOverlay").style.display = "none";
			}
		  } else {
			alert("Có lỗi xảy ra. Vui lòng thử lại.");
		  }
		};
		xhr.send(formData);
	  });

	  // Đóng popup khi nhấn nút "Đóng"
	  document.getElementById("closePopup").addEventListener("click", function() {
		document.getElementById("popupOverlay").style.display = "none";
	  });
		</script>		
		</body>
	</html>


