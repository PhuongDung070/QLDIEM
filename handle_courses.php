<?php
session_start();
include 'connect_database.php';
$conn = connect_to_database("localhost", "root", "", "qldiem");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra hành động và dữ liệu học phần
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $selected_ids = isset($_POST['selected_ids']) ? $_POST['selected_ids'] : null;
	$selected_courses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : null;

    if (empty($action)) {
        $_SESSION['message'] = "Vui lòng chọn hành động cần thực hiện.";
        
        exit;
    }

    if (empty($selected_ids)) {
        $_SESSION['message'] = "Vui lòng chọn ít nhất một học phần.";
        exit;
    }

    // Kiểm tra có phải là mảng không
    if (!is_array($selected_ids) || !is_array($selected_courses) ) {
        $_SESSION['message'] = "Dữ liệu không hợp lệ.";
   
        exit;
    }
	if ($action === 'edit') {
    // Kiểm tra có học phần nào được chọn không và chỉ có một học phần được chọn
    if (count($selected_courses) === 1) {
        $maLHP = $selected_courses[0]; // Lấy mã học phần của học phần được chọn
        header("Location: course_details.php?MaLHP=$maLHP"); // Chuyển hướng đến trang chi tiết học phần
        exit;
    } else {
        $_SESSION['message'] = "Vui lòng chọn chỉ một học phần để chỉnh sửa.";
        header("Location: course_management.php"); // Quay lại trang quản lý học phần
        exit;
    }
}

    // Thực hiện hành động delete
    if ($action === 'delete') {
        if (!empty($selected_ids)) {
            $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
            $conn->begin_transaction();

            try {
                // Cập nhật MaLHP trong bảng thoikhoabieu
                $stmt_update = $conn->prepare("UPDATE thoikhoabieu SET MaLHP = NULL WHERE MaLHP IN ($placeholders)");
                $stmt_update->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                $stmt_update->execute();
                $stmt_update->close();

                // Xóa liên kết trong bảng giangvien_hocphan
                $stmt_scores = $conn->prepare("DELETE FROM giangvien_hocphan WHERE MaLHP IN ($placeholders)");
                $stmt_scores->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                $stmt_scores->execute();
                $stmt_scores->close();

                // Xóa học phần trong bảng danhsachhocphan
                $stmt_list = $conn->prepare("DELETE FROM danhsachhocphan WHERE MaLHP IN ($placeholders)");
                $stmt_list->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                $stmt_list->execute();
                $stmt_list->close();

                // Xóa học phần trong bảng hocphan
                $stmt_courses = $conn->prepare("DELETE FROM hocphan WHERE MaLHP IN ($placeholders)");
                $stmt_courses->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                $stmt_courses->execute();
                $stmt_courses->close();

                $conn->commit();
                $_SESSION['message'] = count($selected_ids) . " học phần đã được xóa toàn bộ thông tin thành công.";
            } catch (Exception $e) {
                $conn->rollback();
                $_SESSION['message'] = "Xóa thất bại: " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Vui lòng chọn học phần để xóa.";
        }
    } 
  
    exit;
} else {
    $_SESSION['message'] = "Yêu cầu không hợp lệ.";
	header("Location: course_management.php");
    exit;
}
?>
