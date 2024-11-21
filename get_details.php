<?php
include 'connect_database.php';
$conn = connect_to_database("localhost", "root", "", "qldiem");

if (isset($_GET['MaID']) && isset($_GET['MaLop'])) {
    $maID = $_GET['MaID'];
    $maLop = $_GET['MaLop'];
    
    // Truy vấn lấy thông tin điểm từ bảng ketquasv
    $query = "SELECT DiemCC, DiemKT1, DiemKT2, DiemTL, DiemKTHP 
              FROM ketquasv 
              WHERE MaID = ? AND MaLop = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $maID, $maLop);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();

    if ($details) {
        echo json_encode($details);
    } else {
        echo json_encode(["error" => "Không tìm thấy dữ liệu chi tiết."]);
    }
} else {
    echo json_encode(["error" => "Thông tin không hợp lệ."]);
}
$conn->close();
?>
