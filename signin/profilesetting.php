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



