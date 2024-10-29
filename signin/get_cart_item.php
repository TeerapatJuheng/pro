<?php
session_start();

include('../inc/server.php'); // เชื่อมต่อฐานข้อมูล
header('Content-Type: application/json');

// ตรวจสอบว่า ct_id ถูกส่งมาหรือไม่
if (isset($_GET['ct_id'])) {
    $ct_id = intval($_GET['ct_id']); // แปลงให้เป็นตัวเลขเพื่อความปลอดภัย

    // คิวรีข้อมูลตะกร้าสินค้าจากฐานข้อมูล
    $query = "SELECT * FROM tb_sell WHERE ct_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ct_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];

    // ดึงข้อมูลตะกร้าสินค้า
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = [
            'name' => $row['product_name'],
            'image' => $row['product_image'],
            'price' => $row['product_price']
        ];
    }

    // ส่งข้อมูลตะกร้ากลับไปยัง JavaScript
    echo json_encode($cartItems);
} else {
    echo json_encode([]); // ถ้าไม่มี ct_id
}
?>