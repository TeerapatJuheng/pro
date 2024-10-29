<?php
session_start();
include('../inc/server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "message" => "คุณยังไม่ได้เข้าสู่ระบบ"));
    exit;
}

$user_id = $_SESSION['user_id']; // รับค่า user_id จากเซสชัน (ซึ่งจะใช้เป็น ct_id)

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $sell_order = $_POST['sell_order']; // หมายเลขการสั่งซื้อ
    $service = $_POST['service'];       // product_id

    // ตรวจสอบให้แน่ใจว่ามีค่าทั้งหมด
    if (empty($sell_order)) {
        echo json_encode(array("success" => false, "message" => "กรุณากรอกหมายเลขการสั่งซื้อ"));
        exit;
    }
    if (empty($service)) {
        echo json_encode(array("success" => false, "message" => "กรุณาเลือกบริการ"));
        exit;
    }

    // ตรวจสอบว่า sell_order และ service ตรงกับรายการในฐานข้อมูล
    $stmt = $conn->prepare("SELECT * FROM tb_sell WHERE sell_order = ? AND product_id = ? AND ct_id = ?");
    $stmt->bind_param("ssi", $sell_order, $service, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($result->num_rows === 0) {
        echo json_encode(array("success" => false, "message" => "ไม่พบรายการที่ตรงกับข้อมูลที่ระบุ"));
        exit;
    }

    // หากพบข้อมูล ทำการลบ
    $delete_stmt = $conn->prepare("DELETE FROM tb_sell WHERE sell_order = ? AND product_id = ? AND ct_id = ?");
    $delete_stmt->bind_param("ssi", $sell_order, $service, $user_id);

    if ($delete_stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "ลบข้อมูลเรียบร้อย"));
    } else {
        echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการลบ"));
    }

    $delete_stmt->close();
    $stmt->close();
}

$conn->close();


?>
