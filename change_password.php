<?php
session_start();

include 'connect_database.php';
$conn = connect_to_database("localhost", "root","",	"qldiem");
$error_msg = "";

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['MaID'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['MaID']; // Lấy ID người dùng từ session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Kiểm tra mật khẩu mới và mật khẩu xác nhận có trùng nhau không
    if ($newPassword !== $confirmPassword) {
        echo "Mật khẩu xác nhận không trùng khớp.";
        exit;
    }

    // Lấy mật khẩu hiện tại từ cơ sở dữ liệu
    $sql = "SELECT MatKhau FROM taikhoan WHERE BINARY MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

echo "ID: " . $user_id . "<br>";

    if ( $oldPassword !== $user['MatKhau']) {
        echo "Mật khẩu cũ không chính xác.";
        exit;
    }

    // Cập nhật mật khẩu mới
    $hashedPassword = $newPassword;
    $sql = "UPDATE taikhoan SET MatKhau = ? WHERE MaID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $user_id);

    if ($stmt->execute()) {
        echo "Đổi mật khẩu thành công.";
    } else {
        echo "Lỗi khi đổi mật khẩu: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
