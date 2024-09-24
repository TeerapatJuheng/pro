<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['save_customer'])) { // เปลี่ยนชื่อตามปุ่มในฟอร์ม

    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password']; // ควรเข้ารหัสรหัสผ่านก่อนบันทึก
    $customer_id = $_SESSION['user_id'];  // สมมติว่าเรามี customer_id ในเซสชัน

    // ตรวจสอบและอัพโหลดไฟล์ภาพ
    $imagePath = null;
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../photo/";
        $imagePath = $target_dir . basename($_FILES["shop_image"]["name"]);

        // ลบภาพเก่าหากมี
        $query = "SELECT img FROM tb_customer WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();

        if ($current_image && file_exists($current_image)) {
            unlink($current_image); // ลบภาพเก่า
        }

        if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $imagePath)) {
            // อัปโหลดไฟล์สำเร็จ
        } else {
            echo "Error uploading file.";
            $imagePath = null; // ถ้าไม่สามารถอัปโหลดได้ ให้ไม่เปลี่ยนเส้นทางรูปภาพ
        }
    } else {
        // หากไม่อัปโหลดไฟล์ใหม่ ให้ใช้ค่าเดิมที่มีอยู่ในฐานข้อมูล
        $query = "SELECT img FROM tb_customer WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();
        $imagePath = $current_image;
    }

    // เตรียมคำสั่ง UPDATE เพื่อลงข้อมูลในฐานข้อมูล
    if ($imagePath) {
        $query = "UPDATE tb_customer SET name = ?, lastname = ?, phone = ?, address = ?, email = ?, password = ?, img = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
        $stmt->bind_param("sssssssi", $name, $lastname, $phone, $address, $email, $hashedPassword, $imagePath, $customer_id);
    } else {
        // ถ้าไม่มีภาพใหม่อัพโหลด ก็ไม่อัพเดตฟิลด์ img
        $query = "UPDATE tb_customer SET name = ?, lastname = ?, phone = ?, address = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
        $stmt->bind_param("ssssssi", $name, $lastname, $phone, $address, $email, $hashedPassword, $customer_id);
    }

    if ($stmt->execute()) {
        // หากบันทึกข้อมูลสำเร็จ ให้เปลี่ยนเส้นทางกลับไปยังหน้า dashboard_user.php
        header("Location: dashboard_user.php");
        exit(); // ใช้ exit() เพื่อหยุดการทำงานของสคริปต์หลังจากการเปลี่ยนเส้นทาง
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
    }

    $stmt->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
