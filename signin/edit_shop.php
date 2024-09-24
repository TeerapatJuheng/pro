<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['save_shop'])) {

    // รับข้อมูลจากฟอร์ม
    $nameshop = $_POST['nameshop'];
    $name = $_POST['shop_name'];
    $lastname = $_POST['shop_lastname'];
    $phone = $_POST['shop_phone'];
    $details = $_POST['shop_details'];
    $address = $_POST['shop_address'];
    $email = $_POST['shop_email'];
    $password = $_POST['shop_pass']; // รหัสผ่านจากฟอร์ม
    $shop_id = $_SESSION['shop_id']; // รับ shop_id จากเซสชัน

    // เข้ารหัสรหัสผ่านใหม่ก่อนบันทึก
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ตรวจสอบและอัปโหลดไฟล์ภาพ
    $imagePath = null;
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../photo/";
        $imagePath = $target_dir . basename($_FILES["shop_image"]["name"]);

        // ตรวจสอบว่ามีไฟล์เก่าอยู่ในระบบหรือไม่
        $query = "SELECT shop_img FROM tb_shop WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();

        // ลบไฟล์ภาพเก่าหากมี
        if ($current_image && file_exists($current_image)) {
            unlink($current_image);
        }

        // ย้ายไฟล์ภาพใหม่ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $imagePath)) {
            // อัปโหลดไฟล์สำเร็จ
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ภาพ";
            $imagePath = null; // ถ้าอัปโหลดไม่สำเร็จ ให้ไม่เปลี่ยนแปลงรูปภาพ
        }
    } else {
        // หากไม่มีการอัปโหลดรูปภาพใหม่ ใช้ภาพเก่าจากฐานข้อมูล
        $query = "SELECT shop_img FROM tb_shop WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();
        $imagePath = $current_image;
    }

    // เตรียมคำสั่ง UPDATE เพื่อลงข้อมูลในฐานข้อมูล
    if ($imagePath) {
        $query = "UPDATE tb_shop SET nameshop = ?, shop_name = ?, shop_lastname = ?, shop_phone = ?, shop_details = ?, shop_address = ?, shop_email = ?, shop_pass = ?, shop_img = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssi", $nameshop, $name, $lastname, $phone, $details, $address, $email, $hashedPassword, $imagePath, $shop_id);
    } else {
        // หากไม่มีรูปภาพใหม่ ใช้คำสั่ง UPDATE โดยไม่แก้ไขรูปภาพ
        $query = "UPDATE tb_shop SET nameshop = ?, shop_name = ?, shop_lastname = ?, shop_phone = ?, shop_details = ?, shop_address = ?, shop_email = ?, shop_pass = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssi", $nameshop, $name, $lastname, $phone, $details, $address, $email, $hashedPassword, $shop_id);
    }

    // ทำการอัปเดตข้อมูล
    if ($stmt->execute()) {
        // หากบันทึกข้อมูลสำเร็จ ให้เปลี่ยนเส้นทางกลับไปยังหน้า dashboard_shop.php
        header("Location: dashboard_shop.php");
        exit(); // หยุดการทำงานของสคริปต์หลังจากการเปลี่ยนเส้นทาง
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูลร้านค้า: " . $stmt->error;
    }

    $stmt->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
