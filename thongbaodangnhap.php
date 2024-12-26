<?php
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "Có lỗi xảy ra.";
?>
<!DOCTYPE html>
<html lang="vi">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="icon" href="img/logo-dhtm.png" type="image/png"/> 
			<title>Thông báo</title>
			<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600&display=swap">
			    <style>
				   body {
				font-family: 'IBM Plex Sans', sans-serif;
				background-color: #f0f0f0;
				margin: 0;
				padding: 0;
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
			}

			h2 {
				font-size: 24px;
				color: #333;
				margin-bottom: 20px;
				text-align: center;
			}

			p {
				font-size: 18px;
				color: #666;
				margin-bottom: 20px;
				text-align: center;
				padding: 0 10px;
			}

			a {
				display: inline-block;
				padding: 10px 20px;
				background-color: #007BFF;
				color: white;
				text-decoration: none;
				border-radius: 20px;
				text-align: center;
			}

			a:hover {
				background-color: #0056b3;
			}

    </style>
		</head>
<body>
	<div>
    <h2>Thông báo</h2>
    <p><?php echo $message; ?></p>
    <a href="login.php">Quay lại</a>
	</div>
</body>
</html>
