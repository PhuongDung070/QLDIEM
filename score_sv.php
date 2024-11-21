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
} else {
    $error_msg ="Người dùng không tồn tại!";
    exit();
}
$courses = getCourseResults($conn,$user_id);
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
				<a href='timetable_sv.php'><img src="img/calendar.png" alt="Logo" class="logo" style="width: 15px;"> Thời khóa biểu</a>
				<a href='score_sv.php'><img src="img/aplus.png" alt="Logo" class="logo" style="width: 15px;"> Kết quả học tập</a>
			</div>
		</div>
		<div class="main-content" style="margin-top: 142px;">
		<div class="info-container">
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Kết quả học tập</h2> </div>

		<div class="info-card">
			<table>
				<thead>
					<tr>
						<th>Mã LHP</th>
						<th>Tên HP</th>
						<th>Số tín chỉ</th>
						<th>Điểm TB</th>
						<th>Điểm Hệ 4</th>
						<th>Điểm Chữ</th>
						<th>Kết Quả</th>
						<th>Chi tiết</th>
					</tr>
				</thead>
				<tbody>
				  <?php foreach ($courses as $course): ?>
					<tr>
					  <td style='display: none;'><?= htmlspecialchars($course['MaLop']); ?></td>
					  <td><?= htmlspecialchars($course['MaLHP']); ?></td>
					  <td><?= htmlspecialchars($course['TenHP']); ?></td>
					  <td><?= htmlspecialchars($course['SoTinChi']); ?></td>
					  <td><?= htmlspecialchars($course['DiemTB']); ?></td>
					  <td><?= htmlspecialchars($course['DiemH4']); ?></td>
					  <td><?= htmlspecialchars($course['DiemChu']); ?></td>
					  <td><?= htmlspecialchars($course['KetQua']); ?></td>
					  <td>
						<button onclick="showDetails('<?= $course['MaLop']; ?>')" style="border-radius: 50%; width: 30px; height: 30px; ">
							<img src="img/arrow-down.png" alt="Mũi tên xuống" style="width: 12px; height: 12px;">
							<img src="img/arrow-up.png" alt="Mũi tên lên" style="width: 12px; height: 12px; display: none;">
						</button>
					  </td>
					</tr>
					<tr id="detailContainer-<?= $course['MaLop']; ?>" class="detail-container" style="display: none;">
					  <td colspan="8">
						<table>
						  <thead>
							<tr>
							  <th>STT</th>
							  <th>Thành phần</th>
							  <th>Trọng số</th>
							  <th>Điểm </th>
							</tr>
						  </thead>
						  <tbody id="detailTableBody-<?= $course['MaLop']; ?>">
							<!-- Các thông tin chi tiết sẽ được thêm vào đây -->
						  </tbody>
						</table>
					  </td>
					</tr>
				  <?php endforeach; ?>
				</tbody>
			</table>

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
function showDetails(maLop) {
    const detailContainer = document.getElementById('detailContainer-' + maLop);
    const arrowDown = detailContainer.previousElementSibling.querySelector('img[src="img/arrow-down.png"]');
    const arrowUp = detailContainer.previousElementSibling.querySelector('img[src="img/arrow-up.png"]');

    // Nếu bảng chi tiết đang ẩn, mở nó ra và thay đổi mũi tên
    if (detailContainer.style.display === 'none' || detailContainer.style.display === '') {
        // Hiển thị bảng chi tiết
        detailContainer.style.display = 'table-row';
        
        // Chuyển mũi tên thành mũi tên lên
        arrowDown.style.display = 'none';
        arrowUp.style.display = 'inline';
        
        // Gọi hàm fetch để lấy dữ liệu chi tiết điểm
        const maID = <?= json_encode($ma_sinh_vien); ?>; // Lấy MaID từ PHP

        fetch(`get_details.php?MaID=${maID}&MaLop=${maLop}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const detailTableBody = document.getElementById('detailTableBody-' + maLop);
                detailTableBody.innerHTML = `
                     <tr>
                        <td>1</td>
                        <td>Điểm chuyên cần</td>
                        <td>10%</td>
                        <td>${data.DiemCC || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Điểm KT1</td>
                        <td>7.5%</td>
                        <td>${data.DiemKT1 || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Điểm KT2</td>
                        <td>7.5%</td>
                        <td>${data.DiemKT2 || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Điểm Thảo Luận</td>
                        <td>15%</td>
                        <td>${data.DiemTL || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Điểm Thi</td>
                        <td>60%</td>
                        <td>${data.DiemKTHP || 'N/A'}</td>
                    </tr>
                `;
            })
            .catch(error => {
                console.error('Error fetching course details:', error);
                alert('Không thể tải dữ liệu chi tiết. Vui lòng thử lại sau.');
            });
    } else {
        // Nếu bảng chi tiết đang hiển thị, đóng nó lại và thay đổi mũi tên
        detailContainer.style.display = 'none';
        
        // Chuyển mũi tên trở lại mũi tên xuống
        arrowDown.style.display = 'inline';
        arrowUp.style.display = 'none';
    }
}

		</script>
	</body>
</html>


