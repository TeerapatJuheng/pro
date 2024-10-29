<?php
session_start();
include('../inc/server.php'); // เชื่อมต่อฐานข้อมูล

$ct_id = $_GET['ct_id'];

$sql = "SELECT * FROM tb_sell WHERE ct_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ct_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode(['success' => true, 'cartItems' => $cartItems]);
?>
