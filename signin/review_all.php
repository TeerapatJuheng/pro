<?php
session_start();
include('../inc/server.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_SESSION['shop_id'])) {
    $shop_id = $_SESSION['shop_id'];
} else {
    echo "ไม่พบข้อมูล shop_id ในเซสชัน";
    exit;
}

$query = "
    SELECT 
        r.review_stars, 
        r.review_commant, 
        c.name AS customer_name, 
        c.lastname AS customer_lastname, 
        c.img AS customer_img 
    FROM 
        tb_review r 
    JOIN 
        tb_sell s ON r.review_order = s.sell_order 
    JOIN 
        tb_customer c ON s.ct_id = c.id 
    WHERE 
        s.shop_id = ?";
    
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("เตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
}

$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result_reviews = $stmt->get_result();

$reviews = [];
if ($result_reviews->num_rows > 0) {
    while ($row = $result_reviews->fetch_assoc()) {
        $row['customer_img'] = !empty($row['customer_img']) ? 'data:image/jpeg;base64,' . base64_encode($row['customer_img']) : '../photo/default-avatar.jpg';
        $reviews[] = $row;
    }
} else {
    echo "ไม่พบรีวิวสำหรับ shop_id: " . htmlspecialchars($shop_id);
}

$stmt->close();
$conn->close();
?>