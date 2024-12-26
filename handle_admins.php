<?php
session_start();
include 'connect_database.php';
$conn = connect_to_database("localhost", "root", "", "qldiem");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['selected_ids'])) {
        $action = $_POST['action'];
        $selected_ids = $_POST['selected_ids'];

        // Nếu action là delete
        if ($action === 'delete') {
            if (isset($_POST['type']) && $_POST['type'] === 'all') {
               
                if (!empty($selected_ids)) {
                    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

                    // Bắt đầu transaction
                    $conn->begin_transaction();
                    try {
						$stmt_update = $conn->prepare("
                             UPDATE thongkebaocao
								SET MaID = NULL
								WHERE MaID IN ($placeholders)
                        ");
                        $stmt_update->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_update->execute()) {
                            throw new Exception("Lỗi khi xóa thông tin báo cáo: " . $stmt_update->error);
                        }
                        $stmt_update->close();
                        

                        // Xóa sinh viên
                        $stmt_admins = $conn->prepare("DELETE FROM nhanvienquanly WHERE MaID IN ($placeholders)");
                        $stmt_admins->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_admins->execute()) {
                            throw new Exception("Lỗi khi xóa nhân viên: " . $stmt_admins->error);
                        }
                        $stmt_admins->close();
						
					    // Xóa sinh viên
                        $stmt_staffs = $conn->prepare("DELETE FROM nhanvien WHERE MaID IN ($placeholders)");
                        $stmt_staffs->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_staffs->execute()) {
                            throw new Exception("Lỗi khi xóa giảng viên: " . $stmt_staffs->error);
                        }
                        $stmt_staffs->close();
                        // Xóa tài khoản 
                        $stmt_taikhoan = $conn->prepare("DELETE FROM taikhoan WHERE MaID IN ($placeholders)");
                        $stmt_taikhoan->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_taikhoan->execute()) {
                            throw new Exception("Lỗi khi xóa tài khoản: " . $stmt_taikhoan->error);
                        }
                        $stmt_taikhoan->close();

                        $conn->commit(); // Commit transaction
                        $_SESSION['message'] = count($selected_ids) . " nhân viên đã được xóa toàn bộ thông tin thành công.";
                    } catch (Exception $e) {
                        $conn->rollback(); // Rollback transaction nếu xảy ra lỗi
                        $_SESSION['message'] = "Xóa thất bại: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['message'] = "Vui lòng chọn nhân viên để xóa.";
                }
            } elseif (isset($_POST['type']) && $_POST['type'] === 'info') {
                // Ẩn thông tin cá nhân, giữ lại tài khoản và điểm số
                if (!empty($selected_ids)) {
                    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

                    $conn->begin_transaction();
                    try {
                        // Cập nhật thông tin cá nhân để ẩn
                        $stmt_update = $conn->prepare("
                             UPDATE nhanvien 
								SET TenNV = CONCAT('Ẩn danh_', MaID), 
									DiaChi = NULL
								WHERE MaID IN ($placeholders)
                        ");
                        $stmt_update->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_update->execute()) {
                            throw new Exception("Lỗi khi ẩn thông tin cá nhân: " . $stmt_update->error);
                        }
                        $stmt_update->close();
						
                        $stmt_update_taikhoan = $conn->prepare("
                            UPDATE taikhoan 
                            SET 
                                Quyen = CONCAT(Quyen, ', AnDanh')
                                WHERE MaID IN ($placeholders)
                        ");
                        $stmt_update_taikhoan->bind_param(str_repeat('s', count($selected_ids)), ...$selected_ids);
                        if (!$stmt_update_taikhoan->execute()) {
                            throw new Exception("Lỗi khi cập nhật tài khoản: " . $stmt_update_taikhoan->error);
                        }
                        $stmt_update_taikhoan->close();
                        $conn->commit(); // Commit transaction
                        $_SESSION['message'] = count($selected_ids) . " nhân viên đã được ẩn thông tin cá nhân thành công.";
                    } catch (Exception $e) {
                        $conn->rollback(); // Rollback transaction nếu xảy ra lỗi
                        $_SESSION['message'] = "Ẩn thông tin thất bại: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['message'] = "Vui lòng chọn nhân viên để ẩn thông tin.";
                }
            } else {
                $_SESSION['message'] = "Loại hành động không hợp lệ.";
            }
            header("Location: user_management.php");
            exit;
        }

        // Nếu action là edit
        elseif ($action === 'edit') {
            if (empty($selected_ids)) {
                $_SESSION['message'] = "Chưa chọn nhân viên.";
                header("Location: user_management.php");
                exit;
            } elseif (count($selected_ids) > 1) {
                $_SESSION['message'] = "Chỉ chọn 1 nhân viên để chỉnh sửa.";
                header("Location: user_management.php");
                exit;
            } else {
                $maID = $selected_ids[0];
                header("Location: admin_details.php?MaID=$maID");
                exit;
            }
        }

        // Nếu action không hợp lệ
        else {
            $_SESSION['message'] = "Hành động không hợp lệ.";
            header("Location: user_management.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "Vui lòng chọn nhân viên và hành động cần thực hiện.";
        header("Location: user_management.php");
        exit;
    }
} else {
    $_SESSION['message'] = "Yêu cầu không hợp lệ.";
    header("Location: user_management.php");
    exit;
}
?>
