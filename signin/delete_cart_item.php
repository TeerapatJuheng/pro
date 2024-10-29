<?php
// เชื่อมต่อฐานข้อมูล
include('../inc/server.php');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // ดึง sell_id จาก query string
    parse_str(file_get_contents("php://input"), $_DELETE);
    $sell_id = $_DELETE['sell_id'];

    // ตรวจสอบว่า sell_id ถูกต้องหรือไม่
    if (empty($sell_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid sell_id']);
        exit();
    }

    // ลบข้อมูลจาก tb_sell ตาม sell_id
    $stmt = $conn->prepare("DELETE FROM tb_sell WHERE sell_id = ?");
    $stmt->bind_param("i", $sell_id); // ใช้ i สำหรับ integer
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting item']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
