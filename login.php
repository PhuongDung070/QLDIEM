<?php 
session_start();
include 'connect_database.php';
$conn = connect_to_database("localhost", "root","",	"qldiem");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);

    // Gọi hàm đăng nhập và nhận thông báo lỗi nếu có
    $error_msg = login_user($conn, $input_username, $input_password);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vn">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="img/logo-dhtm.png" type="image/png"/> 
		<title>Đăng nhập</title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600&display=swap">
	</head>
	<body style="display:flex;">
	
	<div class="banner">
		<img src="img/banner-tmu.jpg">
	</div>
		<div class="main">
		<img src="img/logo-dhtm.png" alt="Logo" class="logo">
		<h1>ĐĂNG NHẬP</h1>
		<h5>Cổng thông tin đào tạo</h5>
		<form id="loginForm" class="login" method="POST" autocomplete="off">
		<table>
			<tr>
				<td> <label for="username">Tên đăng nhập:</label>
				<input type="text" id="username" name="username" placeholder="Enter your Username" required>
				</td>
            </tr>
			<tr>
				<td>
				<label for="password">Mật khẩu:</label>
				<input type="password" id="password" name="password" placeholder="Enter your Password" required>
				</td>
            </tr>
			<tr>
				<td>
				<button class="login" type="submit">Đăng nhập</button>
				</td>
			</tr>
		</table>
		 <?php if (!empty($error_msg)) { echo "<p style='color:red;'>$error_msg</p>"; } ?>
        </form>
        </form>
		</div>
	</body>
</html>