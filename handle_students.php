<?php
include 'connect_database.php';
$conn = connect_to_database("localhost", "root", "", "qldiem");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['selected_students'])) {
        $action = $_POST['action'];
        $selected_students = $_POST['selected_students'];

        // Action: Delete students
        if ($action === 'delete') {
            if (!empty($selected_students)) {
                // Prepare placeholders for the query
                $placeholders = implode(',', array_fill(0, count($selected_students), '?'));
                
                // Prepare DELETE query
                $stmt = $conn->prepare("DELETE FROM sinhvien WHERE MaID IN ($placeholders)");

                // Dynamically bind the parameters (ensure all are strings)
                $stmt->bind_param(str_repeat('s', count($selected_students)), ...$selected_students);
                
                // Execute the query
                if ($stmt->execute()) {
                    $deleted_count = $stmt->affected_rows;
                    if ($deleted_count > 0) {
                        $_SESSION['message'] = "$deleted_count sinh viên đã được xóa thành công.";
                    } else {
                        $_SESSION['message'] = "Không có sinh viên nào được xóa.";
                    }
                } else {
                    $_SESSION['message'] = "Lỗi khi xóa sinh viên. Vui lòng thử lại.";
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Vui lòng chọn sinh viên để xóa.";
            }
            header("Location: user_management.php");
            exit;
        }
        // Action: Edit student
        elseif ($action === 'edit') {
            if (count($selected_students) === 1) {
                $maID = $selected_students[0];
                header("Location: student_details.php?MaID=$maID");
                exit;
            } else {
                $_SESSION['message'] = "Vui lòng chỉ chọn một sinh viên để chỉnh sửa.";
                header("Location: user_management.php");
                exit;
            }
        }
    } else {
        $_SESSION['message'] = "Vui lòng chọn sinh viên và hành động cần thực hiện.";
        header("Location: user_management.php");
        exit;
    }
}
?>
