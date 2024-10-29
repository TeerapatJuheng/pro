<?php
session_start();
include('../inc/server.php');

// สมมติว่ามีการเก็บ customer_id ในเซสชันเมื่อผู้ใช้ล็อกอิน
$user_id = $_SESSION['user_id']; // ควรตั้งค่านี้เมื่อผู้ใช้ทำการล็อกอิน

// ดึงข้อมูลชื่อและนามสกุลจากตาราง tb_customer
$query = "SELECT * FROM tb_customer WHERE id = $user_id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $fullName = htmlspecialchars($customer["name"]) . " " . htmlspecialchars($customer["lastname"]);
} else {
    $fullName = "Customer not found";
}

// ดึงข้อมูลชื่อและนามสกุลจากตาราง tb_user
$query = "SELECT name, lastname, phone, address, email, password , img , age , job , sex FROM tb_customer WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = htmlspecialchars($user["name"]);
    $lastname = htmlspecialchars($user["lastname"]);
    $phone = htmlspecialchars($user["phone"]);
    $address = htmlspecialchars($user["address"]);
    $email = htmlspecialchars($user["email"]);
    $password = htmlspecialchars($user["password"]);
    $age = htmlspecialchars($user["age"]);
    $job = htmlspecialchars($user["job"]);
    $sex = htmlspecialchars($user["sex"]);
} else {
    $name = "";
    $lastname = "";
    $phone = "";
    $address = "";
    $email = "";
    $password = "";
    $age = "";
    $job = "";
    $sex = "";
}

$stmt->close();
$conn->close();

?>
