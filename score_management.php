<?php
session_start();
include 'connect_database.php';
require 'autoload.php'; // Đảm bảo rằng thư viện PHPSpreadsheet được load đúng

// Đưa câu lệnh use lên đầu để tránh lỗi
use PhpOffice\PhpSpreadsheet\IOFactory;// Đảm bảo bạn đã cài đặt PHPSpreadsheet

$conn = connect_to_database("localhost", "root", "", "qldiem");
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
    $error_msg = "Người dùng không tồn tại!";
    exit();
}

// Xử lý file Excel sau khi tải lên
if (isset($_POST['upload'])) {
    $file = $_FILES['excelFile']['tmp_name'];
    
    if (move_uploaded_file($file, 'uploads/' . $_FILES['excelFile']['name'])) {
        $excelFilePath = 'uploads/' . $_FILES['excelFile']['name'];

        // Đọc file Excel và cập nhật điểm
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelFilePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // Cập nhật điểm cho từng sinh viên trong file Excel
        for ($row = 2; $row <= $highestRow; $row++) {
            $ma_id = $sheet->getCell('A' . $row)->getValue();
            $diem_cc = $sheet->getCell('C' . $row)->getValue();
            $diem_kt1 = $sheet->getCell('D' . $row)->getValue();
            $diem_kt2 = $sheet->getCell('E' . $row)->getValue();
            $diem_tl = $sheet->getCell('F' . $row)->getValue();
            $diem_kthp = $sheet->getCell('G' . $row)->getValue();

            // Cập nhật điểm vào cơ sở dữ liệu sử dụng câu lệnh chuẩn bị
            $stmt = $conn->prepare("UPDATE ketquasv SET DiemCC = ?, DiemKT1 = ?, DiemKT2 = ?, DiemTL = ?, DiemKTHP = ? WHERE MaID = ? AND MaLop = ?");
            $stmt->bind_param("dddddss", $diem_cc, $diem_kt1, $diem_kt2, $diem_tl, $diem_kthp, $ma_id, $ma_lop);
            if ($stmt->execute()) {
                echo "Cập nhật điểm cho sinh viên $ma_id thành công.";
            } else {
                echo "Lỗi khi cập nhật điểm cho sinh viên $ma_id: " . $stmt->error;
            }
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Lấy dữ liệu từ form
    $ma_ids = $_POST['MaID']; // Mã sinh viên
    $diem_cc = $_POST['diem_cc'] ?? null;
    $diem_kt1 = $_POST['diemKT1'] ?? null;
    $diem_kt2 = $_POST['diemKT2'] ?? null;
    $diem_tl = $_POST['diemTL'] ?? null;
    $diem_kthp = $_POST['diemKTHP'] ?? null;
    $ma_lop = $_POST['MaLop'] ?? null; // Mã lớp

    if (isset($ma_ids) && is_array($ma_ids)) {
        for ($i = 0; $i < count($ma_ids); $i++) {
            // Kiểm tra xem các giá trị có hợp lệ không
            $diem_cc_value = !empty($diem_cc[$i]) ? $diem_cc[$i] : null;
            $diem_kt1_value = !empty($diem_kt1[$i]) ? $diem_kt1[$i] : null;
            $diem_kt2_value = !empty($diem_kt2[$i]) ? $diem_kt2[$i] : null;
            $diem_tl_value = !empty($diem_tl[$i]) ? $diem_tl[$i] : null;
            $diem_kthp_value = !empty($diem_kthp[$i]) ? $diem_kthp[$i] : null;

            $stmt = $conn->prepare("UPDATE ketquasv SET DiemCC = ?, DiemKT1 = ?, DiemKT2 = ?, DiemTL = ?, DiemKTHP = ? WHERE MaID = ? AND MaLop = ?");
            $stmt->bind_param("dddddss", $diem_cc_value, $diem_kt1_value, $diem_kt2_value, $diem_tl_value, $diem_kthp_value, $ma_ids[$i], $ma_lop);
            
            if ($stmt->execute()) {
                echo "Cập nhật điểm cho sinh viên " . htmlspecialchars($ma_ids[$i]) . " thành công.<br>";
            } else {
                echo "Lỗi khi cập nhật điểm cho sinh viên " . htmlspecialchars($ma_ids[$i]) . ": " . $stmt->error . "<br>";
            }
        }
		header("Location: score_management.php?ma_lop=" . urlencode($ma_lop));
        exit();
    } else {
        echo "Không có dữ liệu MaID!";
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
		<div class="title" style="margin-left: 120px;"> <img src="img/next.png" alt="Profile  Image" style="width: 40px;"> <h2>Quản lý điểm và kết quả học tập</h2> </div>

		<div class="info-card" style="display: block;">
			 <div>
			 <form method="POST" action="">
				<label for="ma_lop">Chọn lớp:</label>
				<select name="ma_lop" id="ma_lop">
					<?php
					$sql_tkb = "SELECT DISTINCT MaLop FROM thoikhoabieu WHERE GiangVien = '$user_id'"; 
					$result_tkb = $conn->query($sql_tkb);
							echo "<option value=''>Chọn Lớp</option>";
					if ($result_tkb->num_rows > 0) {
						while ($row = $result_tkb->fetch_assoc()) {
							echo "<option value='" . $row['MaLop'] . "'>" . $row['MaLop'] . "</option>";
						}
					} else {
						echo "<option value=''>Không có lớp nào</option>";
					}
					?>
				</select>
				 <button type="submit" name="submit_class">Chọn</button>
			</form>
			</div>
		</div>
		<div class="info-card" id="uploadForm" style="display: none;">
			<form method="POST" enctype="multipart/form-data">
				<label for="excelFile">Chọn file Excel:</label>
				<input type="file" name="excelFile" id="excelFile" accept=".xls, .xlsx" required>
				<button type="submit" name="upload">Tải</button>
			</form>
		</div>
		<div class="info-card" style="display: block;">
			<div>
			<form method="POST">
			<?php
					$show_infor1 = false;
					if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_class'])) {
					$ma_lop = $_POST['ma_lop']; // Lấy mã lớp đã chọn
						if (!empty($ma_lop)) {
							$show_infor1 = true; // Hiển thị div khi có mã lớp
						}
					

                if (!empty($ma_lop)) {
                    // Truy vấn bảng ketquasv theo mã lớp đã chọn
                    $sql_ktq = "SELECT k.MaID, k.MaLop, k.DiemCC, k.DiemKT1, k.DiemKT2, k.DiemTL, k.DiemKTHP, s.TenSV
								FROM ketquasv k
								JOIN sinhvien s ON k.MaID = s.MaID
								WHERE k.MaLop = '$ma_lop'";
                    $result_ktq = $conn->query($sql_ktq);
                    echo '
						<div class="infor1" style="display: ' . ($show_infor1 ? 'flex' : 'none') . ';">
							<button type="button" id="uploadButton">Tải lên</button>
							<button type="button" id="editButton">Chỉnh sửa</button>
							<button type="submit" style="display:none;" id="saveButton" name="update">Lưu thông tin</button>
						</div>
					';
                    if ($result_ktq->num_rows > 0) {
                        // Hiển thị kết quả trong bảng
                        echo "<table border='1' style='width: 100%; margin-top: 20px;'>";
						echo " <h1> $ma_lop </h1>";
                        echo "<tr><th>Mã Sinh Viên</th><th>Họ Tên</th><th>DiemCC</th><th>DiemKT1</th><th>DiemKT2</th><th>DiemTL</th><th>DiemKTHP</th></tr>";
                        while ($row = $result_ktq->fetch_assoc()) {
                            echo "<tr>";
							echo '<input type="hidden" name="MaLop" value="' . htmlspecialchars($row['MaLop']) . '" />';
							echo '<input type="hidden" name="MaID[]" value="' . htmlspecialchars($row['MaID']) . '" />';
							echo "<td style='width: 100px;'>" . htmlspecialchars($row['MaID']) . "</td>";
                            echo "<td style='width: 200px;'>" . htmlspecialchars($row['TenSV']) . "</td>";
							echo '<td><input type="text" name="diem_cc[]" value="' . htmlspecialchars($row['DiemCC']) . '"></td>';
							echo '<td><input type="text" name="diemKT1[]" value="' . htmlspecialchars($row['DiemKT1']) . '"></td>';
							echo '<td><input type="text" name="diemKT2[]" value="' . htmlspecialchars($row['DiemKT2']) . '"></td>';
							echo '<td><input type="text" name="diemTL[]" value="' . htmlspecialchars($row['DiemTL']) . '"></td>';
							echo '<td><input type="text" name="diemKTHP[]" value="' . htmlspecialchars($row['DiemKTHP']) . '"></td>';
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Không có kết quả cho lớp này.</p>";
                    }
                } else {
                    echo "<p>Vui lòng chọn lớp.</p>";
                }
            }
            ?>
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
		<script src="script.js"></script>	
		<script>
				const editButton = document.getElementById("editButton");
				const saveButton = document.getElementById("saveButton");
				const formInputs = document.querySelectorAll("form input");
				const resetButton = document.querySelector("#resetButton");
				const uploadButton = document.getElementById("uploadButton");
				const uploadForm = document.getElementById("uploadForm");

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
					editButton.style.display = "inline-block";
				});
			}
			if (uploadButton) {
				uploadButton.addEventListener("click", function () {
					// Ẩn nút "Tải lên"
					uploadButton.style.display = "inline-block";

					// Hiển thị form tải lên
					uploadForm.style.display = "block";

					// Hiển thị nút "Lưu thông tin"
					saveButton.style.display = "inline-block";
					editButton.style.display = "inline-block";
				});
			}


			resetButton.addEventListener("click", function () {
					// Reset tất cả các input trong form, ngoại trừ MaID
				formInputs.forEach(input => {
					if (input.name !== "MaID") { // Đảm bảo không reset MaID
						input.value = ''; // Đặt lại giá trị của input về rỗng
					}
				});
				saveButton.style.display = "inline-block";
				editButton.style.display = "inline-block";
			});
				if (saveButton) {
				saveButton.addEventListener("click", function () {
					alert("Xác nhận lưu!");
				});
			}
		 </script>
	</body>
</html>


