<?php
session_start();
include('../inc/server.php');

// สมมติว่ามีการเก็บ shop_id ในเซสชันเมื่อผู้ใช้ล็อกอิน
$shop_id = $_SESSION['shop_id']; // ควรตั้งค่านี้เมื่อผู้ใช้ทำการล็อกอิน

// ดึงข้อมูลชื่อและนามสกุลจากตาราง tb_shop
$query = "SELECT * FROM tb_shop WHERE id = $shop_id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shop = $result->fetch_assoc();
    $fullName = htmlspecialchars($shop["shop_name"]) . " " . htmlspecialchars($shop["shop_lastname"]);
} else {
    $fullName = "Shop not found";
}

// ดึงข้อมูลชื่อและนามสกุลจากตาราง tb_shop
$query = "SELECT shop_name, shop_lastname , nameshop , shop_phone , shop_sex , shop_age, shop_job, shop_details , shop_address , shop_email, shop_pass, shop_img FROM tb_shop WHERE id = ?";
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
    $sex = htmlspecialchars($shop["shop_sex"]);
    $age = htmlspecialchars($shop["shop_age"]);
    $job = htmlspecialchars($shop["shop_job"]);
    $details = htmlspecialchars($shop["shop_details"]);
    $address = htmlspecialchars($shop["shop_address"]);
    $email = htmlspecialchars($shop["shop_email"]);
    $password = htmlspecialchars($shop["shop_pass"]);
} else {
    $nameshop = "";
    $name = "";
    $lastname = "";
    $phone = "";
    $sex = "";
    $age = "";
    $job = "";
    $details = "";
    $address = "";
    $email = "";
    $password = "";
}

$stmt->close();
$conn->close();

?>

