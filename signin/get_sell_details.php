<?php
include('../inc/server.php'); // รวมการเชื่อมต่อฐานข้อมูล

if (isset($_GET['sell_id'])) {
    $sell_id = $_GET['sell_id'];

    // ดึงข้อมูลจาก tb_sell และ tb_product ตาม sell_id
    $query = "
        SELECT 
            s.sell_id, 
            s.sell_order, 
            s.sell_date, 
            s.sell_note, 
            s.sell_distance, 
            s.sell_total, 
            s.sell_qr, 
            s.ct_id, 
            s.product_id, 
            s.shop_id, 
            s.sell_size, 
            p.product_name, 
            p.product_type 
        FROM 
            tb_sell s 
        JOIN 
            tb_product p ON s.product_id = p.product_id 
        WHERE 
            s.sell_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sell_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data); // ส่งข้อมูลกลับในรูปแบบ JSON
    } else {
        echo json_encode(['error' => 'ไม่พบข้อมูล']);
    }
} else {
    echo json_encode(['error' => 'ไม่มี sell_id ที่ถูกต้อง']);
}
?>