<?php
session_start();
include('../inc/server.php'); // เชื่อมต่อกับฐานข้อมูล
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $head_service = $_POST['head_service'];
    $details_service = $_POST['description'];
    $sell_order = trim($_POST['sell_order']);

    // ตรวจสอบการอัปโหลดไฟล์
    $img_service = null;
    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        $img_service = file_get_contents($_FILES['image1']['tmp_name']);
    }

    $img2_service = null;
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        $img2_service = file_get_contents($_FILES['image2']['tmp_name']);
    }

    $pay_service = null;
    if (isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {
        $pay_service = file_get_contents($_FILES['image3']['tmp_name']);
    }

    // ดึงข้อมูล sell_id และ ct_id
    $query_sell = "SELECT sell_id, ct_id FROM tb_sell WHERE sell_order = ?";
    $stmt = $conn->prepare($query_sell);
    $stmt->bind_param("s", $sell_order);
    $stmt->execute();
    $result_sell = $stmt->get_result();

    if ($result_sell->num_rows > 0) {
        $row = $result_sell->fetch_assoc();
        $sell_id = $row['sell_id'];
        $ct_id = $row['ct_id'];

        // กำหนดค่าที่จะส่งไปยัง bind_param()
        $img_service_param = $img_service ?: null;
        $img2_service_param = $img2_service ?: null;
        $pay_service_param = $pay_service ?: null;

        // บันทึกข้อมูลลงใน tb_service
        $query_service = "INSERT INTO tb_service (head_sevice, details_sevice, img_sevice, img2_sevice, pay_sevice, date_service, sell_id, ct_id)
                          VALUES (?, ?, ?, ?, ?, CURDATE(), ?, ?)";
        $stmt_service = $conn->prepare($query_service);

        // ใช้ประเภทข้อมูลที่ถูกต้อง
        $stmt_service->bind_param("ssbbiii", 
            $head_service, 
            $details_service, 
            $img_service_param, 
            $img2_service_param, 
            $pay_service_param, 
            $sell_id, 
            $ct_id
        );

        // ตรวจสอบการบันทึก
        if ($stmt_service->execute()) {
            echo "บันทึกข้อมูลสำเร็จ";
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt_service->error;
        }
    } else {
        echo "ไม่พบเลขออเดอร์ในระบบ: " . htmlspecialchars($sell_order);
    }

    // ปิด statement
    $stmt->close();
    $stmt_service->close();
}
?>