<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your server connection file
include('../inc/server.php');

// Check if the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $shop_id = $_SESSION['shop_id'];
    $service_id = intval($_GET['id']);
    $query = "SELECT 
                s.id AS service_id,
                s.head_sevice,
                s.details_sevice,
                s.img_sevice,
                s.img2_sevice,
                s.pay_sevice,
                s.date_service,
                s.sell_id,
                s.ct_id,
                s.shop_id,
                c.id AS customer_id,
                c.name AS customer_name,
                c.lastname,
                c.phone,
                c.address,
                c.email
            FROM 
                tb_service s
            INNER JOIN 
                tb_customer c ON s.ct_id = c.id
            WHERE s.shop_id = $shop_id AND s.id = $service_id";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // แปลง BLOB เป็น Base64
        $image1 = base64_encode($row['img_sevice']);
        $image2 = base64_encode($row['img2_sevice']);
        $image3 = base64_encode($row['pay_sevice']);

        $data = [
            'name' => htmlspecialchars($row['customer_name']." ".$row['lastname']),
            'email' => htmlspecialchars($row['email']), 
            'phone' => htmlspecialchars($row['phone']), 
            'order' => htmlspecialchars($row['service_id']),
            'issue' => htmlspecialchars($row['head_sevice']),
            'description' => htmlspecialchars($row['details_sevice']),
            'image1' => 'data:image/jpeg;base64,' . $image1, 
            'image2' => 'data:image/jpeg;base64,' . $image2,
            'image3' => 'data:image/jpeg;base64,' . $image3,
        ];
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
}
?>