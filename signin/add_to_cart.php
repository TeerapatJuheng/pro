<?php
session_start();
include('../inc/server.php');

// รับข้อมูล JSON ที่ส่งมาจาก JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['item']['id']; // ดึง product_id

// ตรวจสอบว่า product_id เป็นตัวเลข
if (!is_numeric($product_id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
}

if (empty($_POST['item']) || !isset($_POST['item']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid item data']);
    exit;
}

// Query ดึงข้อมูลสินค้าจากฐานข้อมูล
$query = "SELECT product_name, product_price, product_image FROM tb_product WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id); // ใช้ "i" สำหรับตัวเลข
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // ตรวจสอบว่ามี session ตะกร้าแล้วหรือไม่
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // ถ้าไม่มี ให้สร้าง array ว่าง
    }

    // เพิ่มสินค้าที่ดึงมาได้ใน session (ตะกร้า)
    $_SESSION['cart'][] = [
        'sell_id' => $product_id, // แก้ไขเป็น sell_id
        'name' => $product['product_name'],
        'price' => $product['product_price'],
        'image' => $product['product_image'] // ไม่ต้องเข้ารหัสเป็น base64
    ];

    // ส่งข้อมูลสินค้าในตะกร้ากลับไปในรูปแบบ JSON
    echo json_encode(['success' => true, 'cartItems' => $_SESSION['cart']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
