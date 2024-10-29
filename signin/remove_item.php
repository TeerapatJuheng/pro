<?php
session_start();
include('../inc/server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(array("success" => false, "message" => "กรุณาเข้าสู่ระบบก่อน"));
    exit;
}

// รับข้อมูลจากคำขอที่ส่งมา
$data = json_decode(file_get_contents('php://input'), true);
$productName = $data['productName'];

// Debug: แสดงผลข้อมูลที่รับมา
error_log("Received product name: " . $productName);

// ค้นหา product_id จากชื่อสินค้า
$query = "SELECT product_id FROM tb_product WHERE product_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_id = $row['product_id'];

    error_log("Found product_id: " . $product_id);  // ตรวจสอบว่าค้นหาได้หรือไม่

    // ลบข้อมูลจาก tb_sell
    $deleteQuery = "DELETE FROM tb_sell WHERE product_id = ? AND ct_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ss", $product_id, $_SESSION['user_id']);

    if ($deleteStmt->execute()) {
        http_response_code(200);
        echo json_encode(array("success" => true, "message" => "ลบสินค้าจากตะกร้าเรียบร้อยแล้ว"));
    } else {
        error_log("Error executing delete query: " . $conn->error);  // แสดงข้อผิดพลาด
        echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
    }
} else {
    error_log("Product not found: " . $productName);  // กรณีค้นหาสินค้าไม่เจอ
    echo json_encode(array("success" => false, "message" => "ไม่พบสินค้านี้ในฐานข้อมูล"));
}


$stmt->close();
$conn->close();
?>
