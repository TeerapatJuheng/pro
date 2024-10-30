<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "message" => "คุณยังไม่ได้เข้าสู่ระบบ"));
    exit;
}

$user_id = $_SESSION['user_id']; // รับค่า user_id จากเซสชัน (ซึ่งจะใช้เป็น ct_id)

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $size = $_POST['size']; // ขนาด
    $service = $_POST['service']; // บริการ (product_id)
    $note = isset($_POST['textarea']) ? $_POST['textarea'] : ""; // หมายเหตุ (ไม่บังคับ)

    // ตรวจสอบให้แน่ใจว่ามีค่าทั้งหมด
    if (empty($size)) {
        echo json_encode(array("success" => false, "message" => "กรุณาเลือกขนาดสินค้า"));
        exit;
    }
    if (empty($service)) {
        echo json_encode(array("success" => false, "message" => "กรุณาเลือกบริการ"));
        exit;
    }
    // หมายเหตุไม่จำเป็นต้องกรอก

    // ดึง shop_id, product_price, product_name, และ product_img จากฐานข้อมูลตาม product_id
    $product_query = "SELECT shop_id, product_price, product_name, product_img, product_id FROM tb_product WHERE product_id = ?";
    $product_stmt = $conn->prepare($product_query);
    $product_stmt->bind_param("s", $service);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();

    // ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
    if ($product_result->num_rows > 0) {
        $row = $product_result->fetch_assoc();
        $shop_id = $row['shop_id']; // ดึงค่า shop_id
        $product_id = $row['product_id'];
        $product_price = $row['product_price']; // ดึงราคาของบริการ
        $product_name = $row['product_name']; // ดึงชื่อผลิตภัณฑ์
        $product_img = $row['product_img']; // ดึงข้อมูลรูปภาพจากฟิลด์ BLOB
        
        // แปลงรูปภาพเป็น base64
        $base64_img = base64_encode($product_img);
        
        // สร้างรูปแบบ base64 ที่ใช้สำหรับแสดงรูปใน HTML
        $base64_img_src = 'data:image/jpeg;base64,' . $base64_img; // เปลี่ยน 'image/jpeg' ตามชนิดของรูปภาพ

        // ดึงชื่อร้านจาก tb_shop โดยใช้ shop_id
        $shop_query = "SELECT shop_name FROM tb_shop WHERE id = ?";
        $shop_stmt = $conn->prepare($shop_query);
        $shop_stmt->bind_param("i", $shop_id);
        $shop_stmt->execute();
        $shop_result = $shop_stmt->get_result();

        if ($shop_result->num_rows > 0) {
            $shop_row = $shop_result->fetch_assoc();
            $shop_name = $shop_row['shop_name']; // ดึงชื่อร้าน
        } else {
            $shop_name = "ไม่พบชื่อร้าน"; // หากไม่พบชื่อร้าน
        }

        // ส่งผลลัพธ์กลับไปยัง client ในรูปแบบ JSON
        echo json_encode(array(
            "success" => true,
            "message" => "ข้อมูลได้รับการประมวลผลเรียบร้อยแล้ว",
            "cartItems" => array(
                array(
                    "image" => $base64_img_src, // ส่งรูปภาพในรูปแบบ base64
                    "name" => $product_name,
                    "price" => $product_price,
                    "size" => $size,
                    "note" => $note, // หมายเหตุสามารถเป็นค่าว่างได้
                    "shop_name" => $shop_name, // ส่งชื่อร้านกลับ
                    "product_id" => $product_id
                )
            )
        ));
        
    } else {
        echo json_encode(array("success" => false, "message" => "ไม่พบข้อมูลร้านค้าที่ตรงกับบริการนี้"));
        exit;
    }

    // ปิด statement
    $product_stmt->close();
    $shop_stmt->close(); // ปิด statement สำหรับร้าน
}

// ปิดการเชื่อมต่อ
$conn->close();
?>