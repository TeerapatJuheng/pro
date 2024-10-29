<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}
// get_session.php
session_start();

$response = [];

if (isset($_SESSION['ct_id'])) {
    echo json_encode(['ct_id' => $_SESSION['ct_id']]);
} else {
    echo json_encode([]); // ถ้าไม่มี ct_id
}

echo json_encode($response);
?>