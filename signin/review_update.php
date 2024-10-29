<?php
session_start();
include('../inc/server.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}

// เตรียมและผูก
$stmt = $conn->prepare("INSERT INTO tb_review (review_stars, review_order, review_commant, shop_id, sell_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $review_stars, $orderNumber, $review_comment, $shop_id, $sell_id);

// ตั้งค่าพารามิเตอร์และดำเนินการ
$orderNumber = $_POST['orderNumber'];
$review_stars = $_POST['review_stars']; // รับค่าจากฟอร์ม
$review_comment = $_POST['review_comment']; // รับค่าจากฟอร์ม
$shop_id = $_POST['shop_id']; // รับค่าจากฟอร์ม
$sell_id = $_POST['sell_id']; // รับค่าจากฟอร์ม

if ($stmt->execute()) {
    $_SESSION['success_message'] = "รีวิวใหม่ถูกสร้างเรียบร้อยแล้ว";
    $stmt->close();
    $conn->close();
    header("Location: history.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>