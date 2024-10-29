<?php
session_start();
include('../inc/server.php');

// สมมติว่ามีการเก็บ shop_id ในเซสชันเมื่อผู้ใช้ล็อกอิน
$shop_id = $_SESSION['shop_id']; // ควรตั้งค่านี้เมื่อผู้ใช้ทำการล็อกอิน

// ดึงข้อมูลจากตาราง tb_shop รวมถึงข้อมูลธนาคาร
$query = "SELECT shop_name, shop_lastname, nameshop, shop_phone, shop_details, shop_address, shop_email, shop_pass, shop_img,
                 monday, tuesday, wednesday, thursday, friday, saturday, sunday, shop_time_open, shop_time_out,
                 shop_bank, shop_numberbank 
          FROM tb_shop WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shop = $result->fetch_assoc();
    $nameshop = htmlspecialchars($shop["nameshop"]);
    $name = htmlspecialchars($shop["shop_name"]);
    $lastname = htmlspecialchars($shop["shop_lastname"]);
    $phone = htmlspecialchars($shop["shop_phone"]);
    $details = htmlspecialchars($shop["shop_details"]);
    $address = htmlspecialchars($shop["shop_address"]);
    $email = htmlspecialchars($shop["shop_email"]);
    $password = htmlspecialchars($shop["shop_pass"]);

    // วันทำการ
    $monday = htmlspecialchars($shop["monday"]);
    $tuesday = htmlspecialchars($shop["tuesday"]);
    $wednesday = htmlspecialchars($shop["wednesday"]);
    $thursday = htmlspecialchars($shop["thursday"]);
    $friday = htmlspecialchars($shop["friday"]);
    $saturday = htmlspecialchars($shop["saturday"]);
    $sunday = htmlspecialchars($shop["sunday"]);

    // เวลาทำการ
    $shop_time_open = htmlspecialchars($shop["shop_time_open"]);
    $shop_time_out = htmlspecialchars($shop["shop_time_out"]);

    // ตรวจสอบว่ามีค่า NULL หรือไม่ และตั้งค่าตามต้องการ
    if ($shop_time_open === "00:00:00" || $shop_time_open === NULL) {
        $shop_time_open = ""; // ตั้งค่าให้ว่างถ้าค่าเป็น 00:00:00
    }

    if ($shop_time_out === "00:00:00" || $shop_time_out === NULL) {
        $shop_time_out = ""; // ตั้งค่าให้ว่างถ้าค่าเป็น 00:00:00
    }

    // ข้อมูลธนาคาร
    $shop_bank = htmlspecialchars($shop["shop_bank"]);
    $shop_numberbank = htmlspecialchars($shop["shop_numberbank"]);

    // ดึงชื่อเต็มของร้าน
    $fullName = htmlspecialchars($shop["shop_name"]) . " " . htmlspecialchars($shop["shop_lastname"]);
} else {
    $nameshop = "";
    $name = "";
    $lastname = "";
    $phone = "";
    $details = "";
    $address = "";
    $email = "";
    $password = "";
    // วันทำการ
    $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = 0;

    // เวลาทำการ
    $shop_time_open = "";
    $shop_time_out = "";

    // ข้อมูลธนาคาร
    $shop_bank = "";
    $shop_numberbank = "";

    // ตั้งค่าชื่อร้านเมื่อไม่พบ
    $fullName = "Shop not found";
}

$stmt->close();
$conn->close();
?>