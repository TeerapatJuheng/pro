<?php
session_start();
include('../inc/server.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['save_customer'])) {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
    $user_id = $_SESSION['user_id']; // รับ customer_id จากเซสชัน

    // ตรวจสอบและอัพโหลดไฟล์ภาพ
    $imagePath = null;
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../photo/";
        $imageName = basename($_FILES["shop_image"]["name"]);
        $imagePath = $target_dir . $imageName;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $imagePath)) {
            // หากอัปโหลดสำเร็จ
        } else {
            // กรณีย้ายไฟล์ไม่สำเร็จ
            echo "ไม่สามารถอัปโหลดไฟล์ภาพได้!";
            exit();
        }
    } else {
        // หากไม่มีการอัปโหลดไฟล์ใหม่ ให้ใช้ภาพเดิมจากฐานข้อมูล
        $query = "SELECT img FROM tb_customer WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();

        $imagePath = $current_image; // ใช้ภาพเดิม
    }

    // อัปเดตข้อมูลผู้ใช้ลงในฐานข้อมูล
    $updateQuery = "UPDATE tb_customer SET name = ?, lastname = ?, phone = ?, address = ?, email = ?, password = ?, img = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssssi", $name, $lastname, $phone, $address, $email, $password, $imagePath, $user_id);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล!";
    }
    
    $stmt->close();
}


?>