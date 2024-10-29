<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your server connection file
include('../inc/server.php');

// Check if the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $cartData = isset($_POST['cartData']) ? json_decode($_POST['cartData'], true) : [];
    $cartTotal = isset($_POST['cartTotal']) ? $_POST['cartTotal'] : 0;

    // ตรวจสอบว่ามีข้อมูลตะกร้าหรือไม่
    if (empty($cartData)) {
        echo json_encode(array("success" => false, "message" => "ไม่มีสินค้าที่จะชำระเงิน"));
        exit;
    }

    // เตรียมข้อมูลสำหรับการ INSERT
    $sellOrder = uniqid("ORD-"); // หรือใช้วิธีอื่นเพื่อสร้างหมายเลขคำสั่งซื้อ
    $sellDate = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน
    $sellDistance = ''; // ระยะทาง (ถ้ามี)
    $sellQR = null; // QR Code (ถ้ามี)
    $ct_id = $_SESSION['user_id']; // เปลี่ยนให้เหมาะสม
    $shop_id = $_SESSION['shop_id']; // เปลี่ยนให้เหมาะสม

    // วนลูปเพื่อ INSERT ข้อมูลสินค้าแต่ละรายการ
    foreach ($cartData as $item) {
        $product_id = $item['product_id']; // สมมติว่ามี product_id ในข้อมูล
        $sellSize = $item['size'] ?? ''; // ขนาดของสินค้า
        $sellNote = $item['note'] ?? ''; // หมายเหตุของสินค้า

        // สร้างคำสั่ง SQL
        $sql = "INSERT INTO tb_sell (sell_order, sell_date, sell_note, sell_distance, sell_total, sell_qr, ct_id, product_id, shop_id, sell_size) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // เตรียมคำสั่ง
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdsisss", $sellOrder, $sellDate, $sellNote, $sellDistance, $cartTotal, $sellQR, $ct_id, $product_id, $shop_id, $sellSize);

        // ประมวลผลคำสั่ง
        if (!$stmt->execute()) {
            echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error));
            exit;
        }
    }

    // เคลียร์ cartItems ทั้งหมดในเซสชัน (ถ้ามี)
    unset($_SESSION['cartItems']);

    // ส่งผลลัพธ์กลับไปยัง client
    echo json_encode(array("success" => true, "message" => "บันทึกข้อมูลสำเร็จ"));

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("success" => false, "message" => "ไม่สามารถเข้าถึงหน้านี้ได้"));
}
?>