<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}

$user_id = $_SESSION['user_id']; // รับค่า user_id จากเซสชัน
$ct_id = $_SESSION['ct_id']; // รับค่า ct_id จากเซสชัน

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT * FROM tb_customer WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $fullName = htmlspecialchars($customer["name"]) . " " . htmlspecialchars($customer["lastname"]);
    $profileImage = htmlspecialchars($customer['img']);
} else {
    $fullName = "User not found";
    $profileImage = "default-image.png"; // กรณีที่ไม่พบผู้ใช้
}

// ดึง shop_id จาก URL
$shop_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($shop_id > 0) {
    // ดึงข้อมูลร้านจากฐานข้อมูล
    $query = "SELECT shop_img, nameshop, shop_details, shop_phone, shop_address, 
              monday, tuesday, wednesday, thursday, friday, saturday, sunday, 
              shop_time_open, shop_time_out 
              FROM tb_shop WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $stmt->bind_result(
        $shop_img,
        $nameshop,
        $shop_details,
        $shop_phone,
        $shop_address,
        $monday,
        $tuesday,
        $wednesday,
        $thursday,
        $friday,
        $saturday,
        $sunday,
        $shop_time_open,
        $shop_time_out
    );
    $stmt->fetch();
    $stmt->close();

    // ดึงข้อมูลสินค้าจาก tb_product
    $query = "SELECT product_id, product_img, product_name, product_type, product_details, product_price FROM tb_product WHERE shop_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result_products = $stmt->get_result();
    $stmt->close();

    // ดึงข้อมูลรีวิวพร้อมคะแนนเฉลี่ย
    $query = "
SELECT 
    r.review_stars, 
    r.review_commant, 
    c.name AS customer_name, 
    c.lastname AS customer_lastname, 
    c.img AS customer_img,
    (SELECT AVG(r2.review_stars) 
     FROM tb_review r2 
     JOIN tb_sell s2 ON r2.review_order = s2.sell_order 
     WHERE s2.shop_id = ?) AS average_rating
FROM 
    tb_review r 
JOIN 
    tb_sell s ON r.review_order = s.sell_order 
JOIN 
    tb_customer c ON s.ct_id = c.id 
WHERE 
    s.shop_id = ? 
GROUP BY 
    r.review_order"; // จัดกลุ่มโดย order เพื่อป้องกันการซ้ำซ้อน

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $shop_id, $shop_id); // ใช้ $shop_id สองครั้ง
    $stmt->execute();
    $result_reviews = $stmt->get_result();

    $reviews = [];
    $average_rating = 0; // ตัวแปรสำหรับเก็บคะแนนเฉลี่ย

    if ($result_reviews->num_rows > 0) {
        while ($row = $result_reviews->fetch_assoc()) {
            // เช็คว่ามีรูปภาพหรือไม่
            if (!empty($row['customer_img'])) {
                // ใช้ path ของภาพแทนการแปลงเป็น Base64
                $row['customer_img'] = '../photo/' . htmlspecialchars($row['customer_img']);
            } else {
                // ใช้รูปภาพดีฟอลต์ถ้าไม่มีรูปภาพ
                $row['customer_img'] = '../photo/default-avatar.jpg';
            }

            // เก็บคะแนนเฉลี่ยจากรีวิว
            if (isset($row['average_rating'])) {
                $average_rating = $row['average_rating'];
            }

            $reviews[] = $row;
        }
    } else {
        // echo "ไม่พบรีวิวสำหรับ shop_id: " . htmlspecialchars($shop_id);
    }

    $stmt->close();
} else {
    echo "ไม่พบข้อมูลร้าน";
    exit;
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่าค่ามีอยู่จริง
    if (!isset($_POST['size']) || !isset($_POST['service'])) {
        echo "ข้อมูลไม่ครบถ้วน";
        exit;
    }
    // รับค่าจากฟอร์ม
    $size = $_POST['size'];
    $service = $_POST['service'];
    $note = $_POST['textarea'];

    // คำนวณ total และ distance (เพิ่มตามความต้องการ)
    $total = 0; // คุณจะต้องคำนวณยอดรวมตามสินค้า
    $distance = 0; // ระยะทาง สามารถนำเข้าจากการคำนวณตามที่ต้องการ

    // SQL สำหรับการบันทึกข้อมูลลงฐานข้อมูล
    $query = "INSERT INTO tb_sell (sell_order, sell_date, sell_note, sell_distance, sell_total, ct_id, product_id, shop_id, sell_size) 
              VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    // สร้างรหัสการสั่งซื้อ (คุณสามารถปรับให้เหมาะสม)
    $order_id = uniqid("order_"); // หรือใช้วิธีอื่นในการสร้างรหัสการสั่งซื้อ
    $stmt->bind_param("ssiiissss", $order_id, $note, $distance, $total, $ct_id, $service, $shop_id, $size);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




    <style>
        :root {
            --orange: #ff7800;
            --black: #130f40;
            --white: #fff;
            --light-color: #666;
            --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
            --border: .2rem solid rgba(0, 0, 0, .1);
            --outline: .1rem solid rgba(0, 0, 0, .1);
            --outline-hover: .2rem solid var(--black);
            box-sizing: border-box;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-decoration: none;
            text-transform: capitalize;
            transition: all .2s linear;
            font-family: 'Poppins', sans-serif;
        }

        html {
            font-size: 62.5%;
            overflow-x: hidden;
            scroll-behavior: smooth;
            scroll-padding-top: 7rem;
        }

        body {
            background: #eee;
        }

        .heading {
            padding-top: 8rem;
            padding-bottom: 2rem;
            font-size: 3rem;
            color: var(--black);
        }

        .btn {
            display: inline-block;
            padding: .8rem 3rem;
            font-size: 1.7rem;
            border-radius: .5rem;
            border: .2rem solid var(--black);
            color: var(--black);
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background: #507F99;
            color: #fff;
        }

        .ck {
            display: inline-block;
            padding: .8rem 3rem;
            font-size: 1.7rem;
            border-radius: .5rem;
            border: .2rem solid var(--black);
            color: var(--black);
            cursor: pointer;
            text-align: center;
            min-width: 100%;
            background-color: var(--white);
        }

        .ck:hover {
            background: #507F99;
            color: #fff;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2rem 9%;
            background: var(--white);
            box-shadow: var(--box-shadow);
        }

        #popup-2 {
            z-index: 9999;
            /* Ensure popup is above all other elements */
        }

        /* สไตล์ส่วนหัว */
        .header-title {
            font-size: 28px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            padding-top: 10px;
            letter-spacing: 1px;
            border-bottom: 2px solid #e0e0e0;
        }


        .header .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--black);
        }

        .header .navbar.active {
            right: 2rem;
            transition: .4s linear;
        }

        .header .navbar a {
            font-size: 1.7rem;
            margin: 0 1rem;
            color: var(--black);
        }

        .header .navbar a:hover {
            color: #507F99;
        }

        .header .icons div {
            height: 4.5rem;
            width: 4.5rem;
            line-height: 4.5rem;
            border-radius: .5rem;
            background: #eee;
            color: var(--black);
            font-size: 2rem;
            margin-right: .3rem;
            cursor: pointer;
            text-align: center;
        }

        .header .icons div:hover {
            background: #507F99;
            color: #fff;
        }

        #menu-btn {
            display: none;
        }

        .header .search-form {
            position: absolute;
            top: 110%;
            right: -110%;
            width: 50rem;
            height: 5rem;
            background: var(--white);
            border-radius: .5rem;
            overflow: hidden;
            display: flex;
            align-items: center;
            box-shadow: var(--box-shadow);
        }

        .header .search-form.active {
            right: 2rem;
            transition: .4s linear;
        }

        .header .search-form input {
            height: 100%;
            width: 100%;
            background: none;
            font-size: 1.6rem;
            color: var(--black);
            padding: 0 1.5rem;
        }

        .header .search-form label {
            font-size: 2.2rem;
            padding-right: 1.5rem;
            color: var(--black);
            cursor: pointer;
        }

        .header .search-form label:hover {
            color: #507F99;
        }

        .header .shopping-cart {
            position: absolute;
            top: 110%;
            right: -110%;
            padding: 1rem;
            border-radius: .5rem;
            box-shadow: var(--box-shadow);
            width: 35rem;
            background: var(--white);
        }

        .header .shopping-cart.active {
            right: 2rem;
            transition: .4s linear;
        }

        .header .shopping-cart .box {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            margin: 1rem 0;
        }

        .header .shopping-cart .box img {
            width: 80px;
            height: 80px;
        }

        .header .shopping-cart .box .bx-trash {
            font-size: 2rem;
            position: absolute;
            top: 50%;
            right: 2rem;
            cursor: pointer;
            color: var(--light-color);
            transform: translateY(-50%);
        }

        .header .shopping-cart .box .content h3 {
            font-size: 1.8rem;
            color: var(--black);
            margin-bottom: .5rem;
        }

        .header .shopping-cart .box .content .price {
            color: #507F99;
            font-size: 1.6rem;
        }

        .header .shopping-cart .box .content .quantity {
            color: var(--light-color);
            font-size: 1.4rem;
        }

        .header .shopping-cart .total {
            font-size: 1.8rem;
            color: var(--black);
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .header .shopping-cart .btn {
            display: block;
        }

        .header .profile {
            position: absolute;
            top: 110%;
            right: -110%;
            padding: 1rem;
            border-radius: .5rem;
            box-shadow: var(--box-shadow);
            width: 25rem;
            background: var(--white);
            text-align: center;
        }

        .header .profile.active {
            right: 2rem;
            transition: .4s linear;
        }

        .header .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: .5rem;
        }

        .header .profile h3 {
            font-size: 1.8rem;
            color: var(--black);
            margin-bottom: .5rem;
        }

        .header .profile span {
            font-size: 1.4rem;
            color: var(--light-color);
        }

        .header .profile .btnp {
            display: block;
            width: 100%;
            text-align: center;
            background: #507F99;
            color: #fff;
            padding: .8rem 0;
            margin-top: 1rem;
            border-radius: .5rem;
            font-size: 1.7rem;
        }

        .header .profile .btnp:hover {
            background: #728FCE;
        }

        .header .profile .flex-btnp {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .header .profile .option-btnp {
            flex: 1;
            text-align: center;
            padding: .8rem 0;
            background: #507F99;
            color: var(--white);
            font-size: 1.4rem;
            border-radius: .5rem;
            margin: 0 .5rem;
            transition: all .3s ease;
        }

        .header .profile .option-btnp:hover {
            background: #728FCE;
            color: #fff;
        }

        @media (max-width:991px) {
            html {
                font-size: 55%;
            }

            .header {
                padding: 2rem;
            }

            .popup2 .content5 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 70px;
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 14px;
            }

        }

        @media (max-width: 768px) {
            .header .search-form {
                width: 90%;
            }

            #menu-btn {
                display: inline-block;
            }

            .header .navbar {
                position: absolute;
                top: 100%;
                right: -110%;
                width: 30rem;
                box-shadow: var(--box-shadow);
                border-radius: .5rem;
                background: #fff;
            }

            .header .navbar.active {
                right: 2rem;
                transition: .4s linear;
            }

            .header .navbar a {
                font-size: 2rem;
                margin: 2rem 2.5rem;
                display: block;
            }

            html {
                font-size: 55%;
            }

            .box-container .box {
                flex-flow: column;
            }

            .card-container {
                flex-flow: column;
            }

            .popup2 .content5 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 50px;
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 14px;
            }
        }

        @media (max-while:450px) {
            html {
                font-size: 50%;
            }

            .box-container .box .image-container .big-image img {
                height: auto;
                width: 100%;
            }

            .popup2 .content5 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 40px;
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 14px;
            }
        }

        .big-image {
            width: 300px;
            /* กำหนดความกว้างของ div */
            height: 200px;
            /* กำหนดความสูงของ div */
            overflow: hidden;
            /* ป้องกันไม่ให้ภาพเกินขอบเขต */
            display: flex;
            /* จัดแนวในแนวนอน */
            justify-content: center;
            /* จัดกลางภาพในแนวนอน */
            align-items: center;
            /* จัดกลางภาพในแนวตั้ง */
        }

        .big-image img {
            width: 100%;
            /* ให้ความกว้างของรูปภาพเป็น 100% ของ div */
            height: 100%;
            /* ให้ความสูงของรูปภาพเป็น 100% ของ div */
            object-fit: contain;
            /* ทำให้รูปภาพปรับขนาดเต็มพื้นที่โดยไม่ตัด */
        }



        /* shop2 */

        .box-container {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            /* ใช้ flex-start เพื่อให้เนื้อหาเริ่มที่ด้านบน */
            justify-content: center;
            padding-top: 50px;
            /* เพิ่มพื้นที่ว่างที่ด้านบน */
            margin-top: 0;
            /* ลบ margin-top ที่ไม่จำเป็น */
        }

        .box {
            display: flex;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin: 2rem;
            flex-wrap: wrap;
            /* เพื่อให้กล่องยืดหยุ่น */
        }

        .image-container {
            flex: 1 1 40%;
            /* กำหนดพื้นที่ให้ภาพ */
            padding: 1rem;
        }

        .big-image {
            border: 1px solid #507F99;
            border-radius: 8px;
            padding: 1rem;
        }

        .big-image img {
            height: auto;
            max-height: 25rem;
            max-width: 100%;
        }

        .content {
            flex: 1 1 60%;
            /* กำหนดพื้นที่ให้เนื้อหา */
            padding: 2rem;
            /* เพิ่ม padding เพื่อความห่างหาย */
        }

        .title {
            font-size: 26px;
            /* ขนาดตัวอักษรสำหรับชื่อร้าน */
            color: #007BFF;
            margin-bottom: 15px;
            /* เพิ่มระยะห่างด้านล่าง */
        }

        .address,
        .time,
        .cont {
            font-size: 20px;
            /* ขนาดตัวอักษรสำหรับหัวข้อ */
            color: #333;
            margin: 15px 0;
            /* เพิ่มระยะห่าง */
        }

        .p1 {
            padding: 5px 0;
            font-size: 16px;
            /* ปรับขนาดตัวอักษร */
            color: #666;
        }

        .btn2 {
            height: 50px;
            width: 100%;
            background: #507F99;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            border-radius: 5px;
            margin-top: 20px;
            /* เพิ่มระยะห่างด้านบน */
            transition: background 0.3s;
        }

        .btn2:hover {
            background: #728FCE;
        }

        .card-service {
            margin: 15px 0;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            /* ทำให้การ์ดเรียงกันแบบยืดหยุ่น */
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin: 10px;
            flex: 1 1 calc(33% - 20px);
            /* กำหนดขนาดการ์ด */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
            /* เพิ่มเอฟเฟกต์เมื่อวางเมาส์ */
        }

        .price2 {
            font-size: 16px;
            color: #007BFF;
            font-weight: bold;
            margin: 10px 0;
        }

        .tt {
            margin-top: 15px;
        }

        .tt textarea {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            resize: none;
            /* ปิดการปรับขนาด */
        }

        .dropDown {
            background-color: #f0f8ff;
            /* สีพื้นหลังที่สว่าง */
            border: 1px solid #007BFF;
            /* สีกรอบ */
            border-radius: 8px;
            /* มุมมน */
            padding: 15px;
            /* ระยะห่างภายใน */
            margin: 20px 0;
            /* ระยะห่างด้านบนและด้านล่าง */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* เงา */
        }

        .dropDown h5 {
            margin: 0;
            /* ลบระยะห่างเริ่มต้น */
            font-size: 20px;
            /* ขนาดตัวอักษรใหญ่ขึ้น */
            color: #333;
            /* สีตัวอักษร */
        }

        .dropDown select {
            width: 100%;
            /* ให้เลือกขนาดเต็มที่ */
            padding: 10px;
            /* ระยะห่างภายใน */
            border: 1px solid #007BFF;
            /* สีกรอบ */
            border-radius: 5px;
            /* มุมมน */
            font-size: 16px;
            /* ขนาดตัวอักษร */
            background-color: #ffffff;
            /* สีพื้นหลังของ select */
            transition: border-color 0.3s;
            /* เอฟเฟกต์เปลี่ยนสี */
        }

        .dropDown select:focus {
            border-color: #0056b3;
            /* เปลี่ยนสีกรอบเมื่อเลือก */
            outline: none;
            /* ลบเส้นขอบที่ไม่ต้องการ */
        }



        /* ช่องบริการ */
        .card-container {
            display: flex;
            /* ใช้ flexbox เพื่อจัดเรียงการ์ด */
            flex-wrap: wrap;
            /* อนุญาตให้การ์ดเลื่อนไปบรรทัดถัดไป */
            justify-content: space-evenly;
            /* จัดการ์ดให้มีระยะห่างเท่ากัน */
            padding: 20px;
            /* เพิ่ม padding รอบ ๆ container */
            margin: 0 auto;
            /* จัด container ให้อยู่ตรงกลาง */
            gap: 20px;
            /* เพิ่มระยะห่างระหว่างการ์ด */
        }

        h5 {
            font-size: 18px;
            /* ขนาดตัวอักษรเท่ากันสำหรับทุกหัวข้อ */
            color: #333;
            /* สีตัวอักษร */
            margin: 10px 0;
            /* ระยะห่างด้านบนและด้านล่าง */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
        }

        .card-service {
            margin: 20px;
            /* ระยะห่างรอบ ๆ */
        }

        .card-service .title2 {
            font-size: 16px;
            /* ขนาดตัวอักษรสำหรับชื่อบริการในการ์ด */
            font-weight: bold;
            /* ทำให้ตัวหนา */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่าง */
        }

        .card-service .p2 {
            font-size: 14px;
            /* ขนาดตัวอักษรสำหรับรายละเอียดบริการ */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่าง */
        }

        .img {
            width: 100%;
            /* ให้ภาพเต็มพื้นที่การ์ด */
            height: auto;
            /* ให้ความสูงอัตโนมัติ */
        }

        .img2 {
            width: 100%;
            height: 150px;
            /* ความสูงที่คงที่สำหรับภาพ */
            object-fit: cover;
            /* รักษาสัดส่วน */
        }

        .card {
            flex: 0 1 220px;
            /* ขนาดการ์ดเริ่มต้น */
            min-width: 220px;
            /* ขนาดขั้นต่ำ */
            max-width: 250px;
            /* ขนาดสูงสุด */
            height: auto;
            /* ให้ความสูงอัตโนมัติ */
            text-align: center;
            /* จัดข้อความให้อยู่ตรงกลาง */
            padding: 20px;
            /* เพิ่ม padding ภายในการ์ด */
            border: 2px solid transparent;
            /* ขอบโปร่งใส */
            border-radius: 8px;
            /* มุมมน */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงา */
            margin: 15px;
            /* เพิ่มระยะห่างระหว่างการ์ด */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background-color: #fff;
            /* เพิ่มสีพื้นหลังการ์ด */
        }

        .icon {
            font-size: 48px;
            /* ปรับขนาดไอคอน */
            color: #507F99;
            /* สีของไอคอน */
        }

        .p1 {
            font-size: 16px;
            /* ขนาดตัวอักษรสำหรับข้อความทั่วไป */
            line-height: 1.5;
            /* ระยะห่างระหว่างบรรทัด */
            margin-bottom: 10px;
            /* ระยะห่างด้านล่าง */
        }

        .address,
        .time,
        .cont {
            font-size: 18px;
            /* ขนาดตัวอักษรสำหรับหัวข้อย่อย */
            font-weight: bold;
            /* ทำให้ตัวหนา */
            margin-top: 15px;
            /* ระยะห่างด้านบน */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่าง */
        }

        .p2 {
            color: #1f1208;
            text-shadow: 0 0 .1rem rgba(0, 0, 0, .4);
            font-size: 10px;
            /* ปรับขนาดตัวอักษรเพื่อให้พอดี */
            overflow: hidden;
            /* ซ่อนข้อความที่ล้นออก */
            display: -webkit-box;
            /* Flexbox สำหรับข้อความหลายบรรทัด */
            -webkit-box-orient: vertical;
            /* ตั้งค่าให้กล่องจัดเรียงในแนวตั้ง */
            -webkit-line-clamp: 2;
            /* จำกัดให้แสดง 2 บรรทัด */
            max-height: 3em;
            /* ตั้งความสูงสูงสุด */
            line-height: 1.5em;
            /* ความสูงของบรรทัด */
            font-size: 12px;
            /* ปรับขนาดตัวอักษรสำหรับรายละเอียดเพิ่มเติม */
            margin-top: 5px;
            /* ระยะห่างด้านบน */
        }

        .title {
            font-size: 24px;
            /* ขนาดตัวอักษรสำหรับชื่อร้าน */
            font-weight: bold;
            /* ทำให้ตัวหนา */
            margin-bottom: 10px;
            /* ระยะห่างด้านล่าง */
        }

        .title2 {
            margin: 1rem 0;
            font-size: 12px;
            /* ปรับขนาดตัวอักษร */
        }

        .title2,
        .p2,
        .price2 {
            margin: 5px 0;
            /* ลดระยะห่างด้านบนและด้านล่าง */
        }

        .card:hover {
            transform: scale(1.05);
            /* เพิ่มขนาดเมื่อ hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            /* เพิ่มเงาเมื่อ hover */
            border-color: #507F99;
            /* ขอบเมื่อ hover */
        }

        .card:active {
            transform: scale(0.95);
            /* ลดขนาดเมื่อกด */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* เงาเมื่อกด */
        }

        input[type="radio"] {
            display: none;
            /* ซ่อน radio button */
        }

        /* เมื่อการ์ดถูกเลือก */
        input[type="radio"]:checked+label .card {
            border-color: #507F99;
            /* ขอบเมื่อเลือกการ์ด */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            /* เพิ่มเงาเมื่อเลือก */
            transform: scale(1.05);
            /* เพิ่มขนาดเล็กน้อยเมื่อเลือก */
        }

        /* กำหนดความกว้างของช่องหมายเหตุ */
        #textarea {
            width: 100%;
            /* หรือกำหนดเป็น px หรือ % ตามต้องการ */
            max-width: 400px;
            /* กำหนดขนาดสูงสุด */
            resize: vertical;
            /* ให้ปรับขนาดได้เฉพาะแนวตั้ง */
        }

        /* กำหนดขนาดของปุ่ม */
        .btn2 {
            width: auto;
            /* กำหนดขนาดตามเนื้อหา */
            padding: 10px 20px;
            /* เพิ่มระยะห่างภายใน */
            background-color: #507F99;
            /* ปรับสีพื้นหลัง */
            color: #fff;
            /* ปรับสีตัวอักษร */
            border: none;
            /* ไม่มีกรอบ */
            border-radius: 5px;
            /* มุมมน */
            cursor: pointer;
            /* เปลี่ยนเคอร์เซอร์เป็นมือเมื่อชี้ */
        }

        /* ทำให้ปุ่มขยายเต็มความกว้างของฟอร์ม */
        .btn2 {
            display: block;
            /* ทำให้ปุ่มเป็นบล็อก */
            margin: 20px 0;
            /* เพิ่มระยะห่างด้านบนและล่าง */
            width: 100%;
            /* กำหนดให้เต็มความกว้าง */
        }

        /* ขนาดการแสดงผลที่ต่างกัน */
        @media (max-width: 1200px) {
            .card-container {
                justify-content: center;
                /* จัดการ์ดให้ตรงกลางเมื่อหน้าจอแคบลง */
            }
        }

        @media (max-width: 991px) {
            html {
                font-size: 55%;
                /* ขนาดฟอนต์ที่เล็กลง */
            }

            .header {
                padding: 2rem;
            }

            .card-container {
                justify-content: center;
                /* จัดการ์ดให้ตรงกลางเมื่อหน้าจอแคบลง */
            }

            .card {
                padding: 10px;
                /* ลดระยะห่างภายในการ์ด */
            }

            .img {
                width: 100%;
                /* ให้ภาพเต็มพื้นที่การ์ด */
                height: auto;
                /* ให้ความสูงอัตโนมัติ */
            }

            .title2,
            .p2,
            .price2 {
                margin: 5px 0;
                /* ลดระยะห่างด้านบนและด้านล่าง */
            }
        }

        @media (max-width: 780px) {
            .card-container {
                flex-direction: column;
                /* เปลี่ยนการจัดเรียงการ์ดเป็นแนวตั้ง */
                align-items: center;
                /* จัดการ์ดให้ตรงกลาง */
            }

            .card {
                width: 90%;
                /* กำหนดให้การ์ดมีความกว้าง 90% ของพื้นที่ */
                max-width: 350px;
                /* ขนาดสูงสุดของการ์ด */
                height: 300px;
                /* ความสูงที่แน่นอน */
                margin: 10px 0;
                /* ระยะห่างระหว่างการ์ดในแนวตั้ง */
                padding: 15px;
                /* เพิ่มระยะห่างภายในการ์ด */
            }

            .title {
                font-size: 20px;
                /* ขนาดตัวอักษรสำหรับชื่อบริการ */
            }

            .title2,
            .p2,
            .price2 {
                font-size: 14px;
                /* ปรับขนาดตัวอักษรสำหรับรายละเอียดบริการ */
                margin: 5px 0;
                /* ระยะห่างด้านบนและด้านล่าง */
            }

            .img2 {
                height: 120px;
                /* ปรับความสูงของภาพให้พอดีกับพื้นที่ */
            }
        }

        @media (max-width: 768px) {
            html {
                font-size: 50%;
                /* ขนาดฟอนต์ที่เล็กลง */
            }

            .header .search-form {
                width: 90%;
                /* ขยายฟอร์มค้นหาให้เต็มพื้นที่ */
            }

            .card-container {
                flex-direction: column;
                /* จัดการ์ดเป็นแนวตั้ง */
                align-items: center;
                /* จัดให้การ์ดอยู่ตรงกลาง */
                gap: 20px;
                /* ระยะห่างระหว่างการ์ด */
            }

            .card {
                width: 90%;
                /* การ์ดจะมีความกว้าง 90% ของพื้นที่หน้าจอ */
                max-width: 350px;
                /* กำหนดขนาดสูงสุดของการ์ด */
            }
        }

        @media (max-width: 450px) {
            html {
                font-size: 45%;
                /* ขนาดฟอนต์ที่เล็กลง */
            }

            .card {
                padding: 15px;
                /* เพิ่มระยะห่างภายในการ์ดเล็กน้อย */
                height: auto;
                /* ตั้งค่าเป็น auto เพื่อให้ขนาดการ์ดปรับตามเนื้อหา */
                min-height: 350px;
                /* ตั้งค่าความสูงขั้นต่ำเพื่อให้เพียงพอสำหรับข้อมูล */
                margin-bottom: 20px;
                /* เพิ่มระยะห่างระหว่างการ์ด */
            }

            .title {
                font-size: 20px;
                /* ขนาดตัวอักษรสำหรับชื่อร้านเล็กลง */
            }

            .p1 {
                font-size: 14px;
                /* ขนาดตัวอักษรเล็กลง */
            }

            .address,
            .time,
            .cont {
                font-size: 16px;
                /* ขนาดตัวอักษรสำหรับหัวข้อย่อยเล็กลง */
            }

            .price2 {
                font-size: 14px;
                /* ขนาดตัวอักษรเล็กลง */
                margin: 5px 0;
                /* ระยะห่างด้านบนและล่าง */
                display: block;
                /* แสดงราคาชัดเจน */
                color: #000;
                /* สีของราคา */
            }
        }





        input[id="card_one"]:checked~label[for="card_one"] .card,
        input[id="card_two"]:checked~label[for="card_two"] .card,
        input[id="card_three"]:checked~label[for="card_three"] .card,
        input[id="card_four"]:checked~label[for="card_four"] .card,
        input[id="card_five"]:checked~label[for="card_five"] .card {
            border-color: #507F99;
        }

        /* you can see based on which card is checked the border color will be changed */
        /* ~ selector selects the sibling elements, in this case the label */

        input[id="card_one"]:checked~label[for="card_one"] .card .img,
        input[id="card_two"]:checked~label[for="card_two"] .card .img,
        input[id="card_three"]:checked~label[for="card_three"] .card .img,
        input[id="card_four"]:checked~label[for="card_four"] .card .img,
        input[id="card_five"]:checked~label[for="card_five"] .card .img {
            border-color: #507F99;
            box-shadow: 0 0 .5rem #507F99;
        }

        /* its all done and the last thing is we need to remove the input radio btns */
        .card-service .card-container input {
            display: none;
        }

        /*review */
        .review {
            margin-left: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            /* สีพื้นหลังเพื่อให้ดูสบายตา */
            border-radius: 1rem;
            /* ทำมุมมน */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* เงาเบา ๆ รอบกล่อง */
        }

        .review h1 {
            text-align: left;
            font-size: 26px;
            /* ขนาดฟอนต์ใหญ่ขึ้น */
            color: #333;
            /* สีเข้มขึ้น */
            margin-bottom: 20px;
            /* เว้นระยะด้านล่าง */
        }

        .review .review-slider {
            padding: 1rem;
        }

        .review .review-slider .box3 {
            background: #fff;
            border-radius: .5rem;
            text-align: center;
            padding: 2rem;
            outline-offset: -1rem;
            outline: var(--outline);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: .3s ease;
            margin: 1rem;
            /* เพิ่มระยะห่างระหว่างกล่อง */
            min-height: 150px;
            /* กำหนดความสูงขั้นต่ำ */
            max-height: 250px;
            /* กำหนดความสูงสูงสุด */
            display: flex;
            /* ใช้ Flexbox */
            flex-direction: column;
            /* แนวตั้ง */
            justify-content: center;
            /* จัดกลางแนวตั้ง */
            align-items: center;
            /* จัดกลางแนวนอน */
            position: relative;
            /* เพื่อความยืดหยุ่นในการจัดวาง */
        }

        /* ปรับให้ไม่ยืดเมื่อไม่มีข้อมูล */
        .review .review-slider .box3 p {
            margin: 0;
            /* เอา margin ออกเพื่อไม่ให้ยืด */
            padding: 0;
            /* เอา padding ออก */
            line-height: 1.5;
            /* เพิ่มระยะห่างระหว่างบรรทัด */
            font-size: 14px;
            /* ขนาดฟอนต์ */
            color: #666;
            /* สีเทาอ่อน */
        }

        /* เพิ่มการจัดการเมื่อไม่มีข้อมูล */
        .review .review-slider .box3.empty {
            min-height: 100px;
            /* ความสูงเมื่อไม่มีข้อมูล */
            max-height: 100px;
            /* ความสูงสูงสุดเมื่อไม่มีข้อมูล */
            display: flex;
            /* ใช้ Flexbox */
            justify-content: center;
            /* จัดกลางแนวตั้ง */
            align-items: center;
            /* จัดกลางแนวนอน */
        }

        .review .review-slider .box3:hover {
            outline-offset: 0rem;
            outline: var(--outline-hover);
            transform: translateY(-5px);
            /* ทำให้กล่องยกขึ้นเมื่อ hover */
        }

        .review .review-slider .box3 img {
            height: 100px;
            width: 100px;
            border-radius: 50%;
            /* ทำให้รูปภาพกลม */
            border: 2px solid #007bff;
            /* เปลี่ยนสีขอบให้สดใส */
            margin-bottom: 15px;
            /* เพิ่มระยะห่างด้านล่างรูปภาพ */
        }

        .review .review-slider .box3 h3 {
            font-size: 20px;
            /* ขนาดฟอนต์ใหญ่ขึ้น */
            color: #333;
            /* สีเข้มขึ้น */
            margin: 10px 0;
            /* เพิ่มระยะห่างด้านบนและล่าง */
        }

        .review .review-slider .box3 p {
            font-size: 14px;
            /* ขนาดฟอนต์ที่เหมาะสม */
            color: #666;
            /* สีเทาอ่อน */
            padding: .5rem 0;
            /* เพิ่มระยะห่าง */
            line-height: 1.5;
            /* เพิ่มระยะห่างระหว่างบรรทัด */
        }

        .review .review-slider .box3 .stars i {
            font-size: 1.7rem;
            /* ขนาดดาวที่ใหญ่ขึ้น */
            color: #FFBF00;
            /* สีดาว */
            padding: 0 0.2rem;
            /* เพิ่มระยะห่างระหว่างดาว */
        }

        /* popup2 */
        /* 
        .popup2 .overlay1 {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup2 .content5 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%) scale(0);
            background: #fff;
            width: 450px;
            height: 580px;
            z-index: 2;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        .popup2 .close-btn {
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 20px;
            width: 30px;
            height: 30px;
            background: #222;
            color: #fff;
            font-size: 25px;
            font-weight: 600;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
        }

        .popup2.active .overlay1 {
            display: block;
        }

        .popup2.active .content5 {
            transition: all 300ms ease-in-out;
            transform: translate(-50%,-50%) scale(1);
        }

        .popup2 .content5 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup2 .content5 .name-report1 {
            font-size: 15px;
            display: flex;
            justify-content: space-between;
            padding-bottom: 5px;
        }

        .popup2 .content5 .name-report1 p {
            font-size: 14px;
            color: #507F99;
        }

        .popup2 .content5 .name-report1 .qr {
            text-align: center;
            margin-top: 10px;
        }

        .popup2 .content5 .name-report1 img {
            height: 200px;
            width: 200px;
            text-align:center;
            margin: auto;
        }
        
        .qr {
            margin: auto;
        } */
        /* การรีวิว */

        .review-form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            /* ความกว้างสูงสุดของฟอร์ม */
            width: 90%;
            /* ความกว้างของฟอร์ม */
            margin: 20px;
            /* เว้นระยะให้กับฟอร์ม */
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .rating {
            margin-top: 15px;
        }

        label {
            margin-bottom: 5px;
            color: #333;
        }

        .star {
            font-size: 30px;
            color: #ccc;
            /* สีเทาสำหรับดาวที่ไม่ได้เลือก */
            cursor: pointer;
            transition: color 0.2s;
        }

        .star.selected {
            color: #f39c12;
            /* สีทองสำหรับดาวที่เลือก */
        }

        .selected-rating {
            font-size: 16px;
            margin-top: 5px;
            color: #333;
        }

        textarea {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #507F99;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            /* ทำให้ปุ่มมีความกว้างเต็ม */
        }

        button:hover {
            background-color: #3b5f72;
        }

        .we-customer-ratings__averages {
            font-size: 16px;
            /* ขนาดฟอนต์ทั่วไป */
        }

        .we-customer-ratings__averages__display {
            font-size: 24px;
            /* ขนาดฟอนต์ที่ใหญ่กว่า */
            font-weight: bold;
            /* เพิ่มความหนาให้กับตัวเลข */
        }

        .cart-item {
            display: flex;
            /* Use flexbox for layout */
            align-items: center;
            /* Center items vertically */
            border-bottom: 1px solid #e0e0e0;
            /* Light border for separation */
            padding: 10px 0;
            /* Add some padding */
        }

        .item-image {
            flex: 0 0 80px;
            /* Fixed width for the image container */
            margin-right: 15px;
            /* Space between image and text */
        }

        .item-image img {
            width: 100%;
            /* Make image responsive */
            height: auto;
            /* Maintain aspect ratio */
            border-radius: 5px;
            /* Rounded corners */
        }

        .item-info {
            flex: 1;
            /* Take remaining space */
        }

        .item-info h3 {
            margin: 0;
            /* Remove default margin */
            font-size: 18px;
            /* Set font size */
            color: #333;
            /* Dark color for title */
        }

        .item-info .price {
            font-size: 16px;
            /* Set font size for price */
            color: #507F99;
            /* Color for price */
            font-weight: bold;
            /* Make price bold */
        }

        /* ราคาค่าส่ง */
        .total-info {
            border-top: 2px solid #007bff;
            /* Blue border on top */
            padding-top: 15px;
            /* Space above the content */
            margin-top: 10px;
            /* Space from previous items */
            font-size: 16px;
            /* Font size for readability */
            color: #333;
            /* Dark text color */
        }

        .total-info p {
            margin: 5px 0;
            /* Space between paragraphs */
        }

        .total-info span {
            font-weight: bold;
            /* Make amounts bold */
            color: #507F99;
            /* Highlight the amounts with a different color */
        }

        .close-btn {
            cursor: pointer;
            font-size: 24px;
            /* Adjust size as needed */
            color: #333;
            /* Change color as needed */
            position: absolute;
            /* Position it at the top right */
            right: 20px;
            /* Adjust spacing from right */
            top: 20px;
            /* Adjust spacing from top */
            background: transparent;
            /* Transparent background */
            border: none;
            /* No border */
        }

        /*  Popup content5 */

        /* Style สำหรับ Popup เมื่อแสดงบิลหลังจากกด Checkout */
        .popup2 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Background หน้าต่าง Popup แบบโปร่งใส */
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            /* ซ่อน Popup โดย default */
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .popup2.active {
            visibility: visible;
            /* แสดง Popup เมื่อถูกเรียกใช้งาน */
            opacity: 1;
        }

        /* เนื้อหาภายใน Popup */
        .content5 {
            background-color: #fff;
            padding: 20px;
            padding-bottom: 40px;
            /* เพิ่ม Padding ด้านล่าง */
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            margin-bottom: 20px;
            /* เพิ่ม margin ด้านล่าง */
        }



        /* ปุ่มปิด Popup */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #ff4d4d;
            /* เพิ่มสีแดงเมื่อ hover */
        }

        /* Title */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        /* รายการในตะกร้า */
        #cart-items-container {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding-right: 10px;
            /* เพิ่ม Padding เพื่อหลีกเลี่ยงการชนของ Scrollbar */
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .item-info {
            flex-grow: 1;
            text-align: left;
        }

        .item-info h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .item-info .price {
            color: #666;
        }

        /* Total และค่าจัดส่ง */
        .total-info {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }

        /* สำหรับ QR Code */
        .qr-code-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .qr-code-container canvas {
            margin: 0 auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content5 {
                width: 90%;
                max-width: 400px;
            }

            .cart-item img {
                width: 40px;
                height: 40px;
            }

            h1 {
                font-size: 20px;
            }

            .total-info {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .content5 {
                width: 100%;
                margin: 0 10px;
                max-width: 350px;
            }

            .cart-item img {
                width: 35px;
                height: 35px;
            }

            .cart-item h3 {
                font-size: 14px;
            }

            .total-info {
                font-size: 14px;
            }
        }



        /* Review Swiper */
    </style>

    <title>Shop</title>

</head>

<body>

    <!-- header section starts -->
    <header class="header">
        <a href="dashboard_user.php" class="logo">Laundry</a>

        <nav class="navbar">
            <a href="dashboard_user.php">Dashboard</a>
            <a href="shop_user.php">Shop</a>
            <a href="faq_user.php">FAQ</a>
            <a href="sevice_user.php">Terms & Condition</a>
        </nav>

        <div class="icons">
            <div class="bx bx-menu" id="menu-btn"></div>
            <div class="bx bx-search" id="search-btn"></div>
            <div class="bx bx-basket" id="cart-btn"></div>
            <div class="bx bx-user" id="user-btn"></div>
        </div>

        <div class="shopping-cart">
            <div><input type="text" name="mch_order_no" value="<?php echo date("YmdHis", time()) . rand(100000, 999999); ?>" /></div>
            <form id="checkout-form" method="POST">
                <div id="cart-items"></div> <!-- ส่วนนี้จะถูกอัปเดตเมื่อมีสินค้าถูกเพิ่มเข้ามา -->
                <div class="total"> Total : <span id="cart-total">0฿</span></div>
                <input type="hidden" name="cartData" id="cartData" value="">
                <input type="hidden" name="cartTotal" id="cartTotal" value="0">
                <button type="button" class="ck" onclick="checkout()">Checkout</button> <!-- เปลี่ยนเป็น type="button" -->
            </form>
        </div>
        <div class="profile">
            <?php
            // ตรวจสอบว่ามีรูปภาพหรือไม่ ถ้าไม่มีให้แสดงรูปภาพเริ่มต้น
            $img = !empty($customer['img']) ? htmlspecialchars($customer['img']) : 'default.jpg';
            ?>
            <img src="../photo/<?php echo $img; ?>" alt="Profile Image">
            <h3><?php echo $fullName; ?></h3>
            <span>User</span>
            <a href="profile_user.php" class="btnp">Profile</a>
            <div class="flex-btnp">
                <a href="history.php" class="option-btnp">ประวัติการใช้งาน</a>
                <a href="login.php" class="option-btnp">Logout</a>
            </div>
        </div>
    </header>

    <!-- ร้านค้าที่กดเลือก  -->

    <div class="box-container">

        <div class="box">

            <div class="image-container">

                <div class="big-image">
                    <?php
                    // ตรวจสอบว่ามีภาพในฐานข้อมูลหรือไม่
                    if (!empty($shop_img)) {
                        // แสดงภาพจากฐานข้อมูล
                        echo '<img src="../photo/' . htmlspecialchars($shop_img) . '" alt="Shop Image" class="img2">';
                    } else {
                        // ใช้ภาพดีฟอลต์หากไม่มีภาพ
                        echo '<img src="../photo/default.jpg" alt="Default Image" class="img2">';
                    }
                    ?>
                </div>
            </div>

            <div class="content">

                <h3 class="title"><?php echo htmlspecialchars($nameshop); ?></h3>
                <p class="p1"><?php echo nl2br(htmlspecialchars($shop_details)); ?></p>
                </p>

                <h4 class="address">ที่อยู่ร้าน</h4>
                <p class="p1">ที่อยู่: <?php echo htmlspecialchars($shop_address); ?></p>
                </p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d684.840511221341!2d100.7878557596282!3d13.836459168288247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6fa0d3e43c17%3A0x6900adb7c859f5c7!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4o-C5jOC4oSDguITguK3guKPguYzguJvguK3guYDguKPguIrguLHguYjguJkg4LiI4LmN4Liy4LiB4Lix4LiU!5e0!3m2!1sth!2sth!4v1721792305749!5m2!1sth!2sth" width="200" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                <h4 class="time">เวลาทำการ</h4>
                <p class="p1">
                    <?php
                    // แปลงเวลาเปิดและปิด
                    $formattedOpenTime = date('H:i', strtotime($shop_time_open));
                    $formattedCloseTime = date('H:i', strtotime($shop_time_out));

                    $days = [
                        'จันทร์' => $monday,
                        'อังคาร' => $tuesday,
                        'พุธ' => $wednesday,
                        'พฤหัสบดี' => $thursday,
                        'ศุกร์' => $friday,
                        'เสาร์' => $saturday,
                        'อาทิตย์' => $sunday
                    ];

                    $openDays = [];

                    // ตรวจสอบวันทำการ
                    foreach ($days as $day => $isOpen) {
                        if ($isOpen) {
                            $openDays[] = $day; // เพิ่มวันที่เปิด
                        }
                    }

                    // แสดงผล
                    if (count($openDays) === 7) {
                        // หากเปิดทุกวัน
                        echo "เปิดทุกวัน: $formattedOpenTime - $formattedCloseTime";
                    } elseif (!empty($openDays)) {
                        $firstDay = $openDays[0]; // วันแรกที่เปิด
                        $lastDay = end($openDays); // วันสุดท้ายที่เปิด

                        // หากมีวันเดียวให้แสดงวันนั้น
                        if (count($openDays) === 1) {
                            $daysText = $firstDay;
                        } else {
                            $daysText = "$firstDay - $lastDay"; // แสดงช่วงวันที่เปิด
                        }

                        echo "$daysText: $formattedOpenTime - $formattedCloseTime";
                    } else {
                        echo "ปิด"; // หากไม่มีวันเปิด
                    }
                    ?>
                </p>


                <h4 class="cont">ช่องทางติดต่อ</h4>
                <p class="p1">หมายเลขโทรศัพท์: <?php echo htmlspecialchars($shop_phone); ?></p>
                </p>

                <form id="orderForm" method="POST" action="service_user.php">
                    <div class="dropDown">
                        <span>
                            <h5>ขนาดของตะกร้า :</h5>
                        </span>
                        <select name="size" id="size" required>
                            <option value="" selected disabled>เลือกขนาด</option>
                            <option value="small">S (ขนาดเล็ก)</option>
                            <option value="medium">M (ขนาดมาตรฐาน)</option>
                            <option value="big">L (ขนาดใหญ่)</option>
                        </select>
                    </div>
                    <span>
                        <h5>บริการ :</h5>
                    </span>

                    <div class="card-service">

                        <div class="card-container">
                            <?php while ($product = $result_products->fetch_assoc()): ?>
                                <input type="radio" name="service" id="card_<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>" required onchange="generateSellOrder()">
                                <label for="card_<?php echo $product['product_id']; ?>">
                                    <div class="card">
                                        <div class="img">
                                            <?php
                                            if (!empty($product['product_img'])) {
                                                $imageData = base64_encode($product['product_img']);
                                                echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Image" class="img2">';
                                            } else {
                                                echo '<img src="../photo/default.jpg" alt="Default Image" class="img2">';
                                            }
                                            ?>
                                        </div>
                                        <h5 class="title2"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                        <p class="p2"><?php echo htmlspecialchars($product['product_details']); ?></p>
                                        <h5 class="title2">ประเภท</h5>
                                        <p class="p2"><?php echo htmlspecialchars($product['product_type']); ?></p>
                                        <div class="price2"><?php echo htmlspecialchars($product['product_price']); ?> บาท</div>
                                    </div>
                                </label>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <div class="tt">
                        <span>
                            <h5>หมายเหตุ :</h5>
                        </span>
                        <textarea name="textarea" id="textarea" cols="50" rows="3"></textarea>
                    </div>
                    <!-- ฟิลด์แสดงหมายเลขการสั่งซื้อ
                    <div class="tt">
                        <span>
                            <h5>หมายเลขการสั่งซื้อ :</h5>
                        </span>
                        <input type="text" name="sell_order" id="sell_order" readonly required>
                    </div> -->
                    <button type="submit" class="btn2">ใส่ตะกร้า</button>
                </form>
            </div>
        </div>
    </div>
    <!-- ให้คะแนนรีวิว
    <div class="review-form-container">
        <h1>ให้คะแนนรีวิว</h1>
        <form id="review-form">
            <label for="quality">คุณภาพ:</label>
            <div class="rating" id="quality-rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <input type="hidden" id="quality" name="quality" value="0">
            <div id="quality-display" class="selected-rating">คะแนน: 0 ดาว</div>

            <label for="price">ราคา:</label>
            <div class="rating" id="price-rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <input type="hidden" id="price" name="price" value="0">
            <div id="price-display" class="selected-rating">คะแนน: 0 ดาว</div>

            <label for="service">บริการ:</label>
            <div class="rating" id="service-rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <input type="hidden" id="service" name="service" value="0">
            <div id="service-display" class="selected-rating">คะแนน: 0 ดาว</div>

            <textarea id="comment" name="comment" rows="4" placeholder="เขียนความคิดเห็นของคุณที่นี่..."></textarea>
            <button type="submit">ส่งรีวิว</button>
        </form>
    </div> -->



    <!-- ร้านค้าที่กดเลือก end -->

    <!-- review -->

    <section class="review" id="review">
        <h1 class="review-name">Rating & Review</h1>
        <div class="we-customer-ratings__averages">
            <span class="we-customer-ratings__averages__display"><?php echo number_format($average_rating, 1); ?></span> จาก 5
        </div>
        <div class="swiper review-slider">
            <div class="swiper-wrapper">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="swiper-slide box3 <?php echo empty($reviews) ? 'empty' : ''; ?>">
                            <?php if (empty($reviews)): ?>
                                <p>ยังไม่มีรีวิวสำหรับร้านนี้</p>
                            <?php else: ?>
                                <img src="<?php echo $review['customer_img']; ?>" alt="Profile Image">
                                <h3><?php echo htmlspecialchars($review['customer_name'] . ' ' . $review['customer_lastname']); ?></h3>
                                <div class="stars">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i class='bx bxs-star' style="color: <?php echo ($i < $review['review_stars']) ? 'gold' : 'gray'; ?>;"></i>
                                    <?php endfor; ?>
                                </div>
                                <p><?php echo htmlspecialchars($review['review_commant']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="swiper-slide box3">
                        <p>ยังไม่มีรีวิวสำหรับร้านนี้</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- review end -->

    <!-- popup-->
    <div class="popup2" id="popup-2">
        <div class="overlay1"></div>
        <div class="content5">
            <div class="close-btn" onclick="togglePopup()">&times;</div>

            <!-- Form starts here -->
            <form action="demo_pay.php" method="post">
                <h1 class="header-title">รายการ</h1>

                <!-- เพิ่มช่องสำหรับหมายเลขคำสั่งซื้อ -->
                <div class="group">
                    <label>หมายเลขคำสั่งซื้อ</label>
                    <div>
                        <input type="text" name="mch_order_no" id="mch_order_no" value="<?php echo date('YmdHis', time()) . rand(100000, 999999); ?>" readonly />
                    </div>
                </div>

                <!-- เพิ่มช่องสำหรับ fee_type -->
                <div class="group">
                    <label>ประเภทค่าธรรมเนียม</label>
                    <div>
                        <select name="fee_type">
                            <option value="THB">THB</option>
                        </select>
                    </div>
                </div>

                <!-- เพิ่มช่องสำหรับ channel -->
                <div class="group">
                    <label>ช่องทางการชำระเงิน</label>
                    <div>
                        <select name="channel">
                            <option value="promptpay">Promptpay</option>
                            <option value="alipay">Alipay</option>
                            <option value="wechat">Wechat</option>
                            <option value="airpay">Airpay</option>
                            <option value="truemoney">Truemoney</option>
                        </select>
                    </div>
                </div>

                <!-- รายการสินค้า -->
                <div id="cart-items-container">
                    <!-- รายการสินค้าจะถูกเพิ่มที่นี่ -->
                </div>

                <!-- Total และ QR Code -->
                <div class="total-info">
                    <!-- Total info will be injected here -->
                </div>
                <div class="qr-code-container" id="qr-code-container">
                    <!-- QR code will be generated here -->
                </div>

                <!-- ปรับปุ่มที่นี่ -->
                <div class="group">
                    <label>&nbsp;</label>
                    <input type='hidden' name='action' value='native_pay' />
                    <div>
                        <input type="submit" value="ไปชำระเงิน" />
                    </div>
                </div>
            </form>
            <!-- Form ends here -->

        </div>
    </div>




    <!-- popup end-->


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!--custom js file link -->
    <script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        let profile = document.querySelector('.profile');
        let shoppingCart = document.querySelector('.shopping-cart');
        let searchForm = document.querySelector('.search-form');
        let navbar = document.querySelector('.header .navbar');
        let popup = document.getElementById("popup-2");

        // Function to toggle the shopping cart
        document.querySelector('#cart-btn').onclick = () => {
            shoppingCart.classList.toggle('active');
            searchForm.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        };

        // ฟังก์ชัน toggle สำหรับ search form เมื่อกดปุ่ม Search
        document.querySelector('#search-btn').onclick = () => {
            searchForm.classList.toggle('active');
            // ซ่อนส่วนอื่น ๆ เมื่อ search form เปิด
            shoppingCart.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        };

        // ฟังก์ชัน toggle สำหรับ profile เมื่อกดปุ่ม User
        document.querySelector('#user-btn').onclick = () => {
            profile.classList.toggle('active');
            // ซ่อนส่วนอื่น ๆ เมื่อ profile เปิด
            searchForm.classList.remove('active');
            shoppingCart.classList.remove('active');
            navbar.classList.remove('active');
        };

        // Checkout button click event
        // function togglePopup() {
        //     shoppingCart.classList.remove('active'); // ซ่อน shopping-cart เมื่อคลิกปุ่ม Checkout
        //     popup.classList.toggle('active'); // เปิด popup

        //     // ส่ง request ไปยังเซิร์ฟเวอร์เพื่อดึงรายการสินค้าในตะกร้า
        //     fetch('http://localhost/Solar_LaundryWEBTEST/signin/service_user.php') // แทนที่ด้วย URL ของ API จริง
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 const cartItems = data.cartItems; // รับรายการสินค้าในตะกร้าจากการตอบกลับ
        //                 let total = 0;

        //                 // อัพเดตเนื้อหาใน popup ด้วยรายละเอียดของสินค้า
        //                 const popupContent = popup.querySelector('.content5');
        //                 popupContent.querySelector('#shop-name').innerHTML = cartItems[0].shop_name || 'ไม่ระบุ'; // ชื่อร้าน
        //                 popupContent.querySelector('#note').innerHTML = cartItems[0].note || 'ไม่มี'; // หมายเหตุ
        //                 popupContent.querySelector('#size').innerHTML = cartItems[0].size || 'ไม่ระบุ'; // ขนาด
        //                 popupContent.querySelector('#service-name').innerHTML = cartItems[0].name || 'ไม่ระบุ'; // ชื่อบริการ

        //                 // คำนวณราคารวม
        //                 const shippingFee = 50; // กำหนดค่าจัดส่งคงที่
        //                 cartItems.forEach(item => {
        //                     total += parseFloat(item.price); // เพิ่มราคาสินค้าไปยังราคารวม
        //                 });
        //                 total += shippingFee; // บวกค่าจัดส่ง

        //                 // อัพเดตราคาต่างๆ
        //                 popupContent.querySelector('#item-price').innerHTML = `${total - shippingFee} บาท`; // ราคารวมไม่รวมค่าจัดส่ง
        //                 popupContent.querySelector('#shipping-fee').innerHTML = `${shippingFee} บาท`; // ค่าจัดส่ง
        //                 popupContent.querySelector('#total-price').innerHTML = `${total} บาท`; // ราคารวมพร้อมค่าจัดส่ง

        //                 // อัพเดตระยะทาง
        //                 popupContent.querySelector('#distance').innerHTML = `${cartItems[0].distance || 'ไม่ระบุ'} Km.`; // ระยะทาง

        //                 // แสดง QR Code
        //                 const qrElement = popupContent.querySelector('#qr-code');
        //                 qrElement.src = 'path_to_your_qr_code.png'; // แทนที่ด้วย path จริงของรูปภาพ QR Code
        //                 qrElement.alt = 'QR Code'; // ข้อความ alt สำหรับรูปภาพ QR Code

        //                 // แสดงรูปภาพของบริการ
        //                 const imgElement = popupContent.querySelector('#service-image');
        //                 imgElement.src = cartItems[0].image; // อัพเดตแหล่งที่มาของรูปภาพ
        //                 imgElement.alt = cartItems[0].name; // ข้อความ alt สำหรับรูปภาพของบริการ

        //             } else {
        //                 console.error(data.message); // แสดงข้อผิดพลาดจากการตอบกลับของ API
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error fetching data:', error); // จัดการข้อผิดพลาดในการดึงข้อมูลจาก API
        //         });
        // }

        // ซ่อนทุกอย่างเมื่อเลื่อนหน้าจอ
        window.onscroll = () => {
            searchForm.classList.remove('active');
            shoppingCart.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        };
    </script>


    <!-- Swiper Review -->

    <script>
        // Initialize Swiper
        const swiper = new Swiper('.review-slider', {
            // Optional parameters
            slidesPerView: 1, // จำนวน slides ที่จะแสดงพร้อมกัน
            spaceBetween: 20, // ช่องว่างระหว่าง slides
            loop: true, // วนกลับมาที่ slide แรกหลังจากสุดท้าย
            autoplay: {
                delay: 5000, // เวลาในการแสดงผลต่อ slide (5000ms = 5 วินาที)
                disableOnInteraction: false, // หยุด autoplay เมื่อผู้ใช้โต้ตอบ
            },

            // Pagination (จุดที่แสดงสถานะ slide)
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Navigation arrows (ลูกศรเพื่อเลื่อนไปข้างหน้า/ถอยหลัง)
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Responsive breakpoints
            breakpoints: {
                640: {
                    slidesPerView: 1, // บนหน้าจอขนาดเล็ก แสดง 1 slide
                },
                768: {
                    slidesPerView: 2, // บนหน้าจอขนาดกลาง แสดง 2 slide
                },
                1024: {
                    slidesPerView: 3, // บนหน้าจอขนาดใหญ่ แสดง 3 slide
                },
            },
        });
    </script>


    <script>
        // ฟังก์ชันสำหรับการเลือกดาว
        const ratingStars = (ratingElement, inputElement, displayElement) => {
            const stars = ratingElement.querySelectorAll('.star');

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    // ตรวจสอบคะแนนที่เลือก
                    const currentValue = parseInt(inputElement.value);
                    const newValue = parseInt(star.getAttribute('data-value'));

                    if (currentValue === newValue) {
                        // ถ้าคลิกที่ดาวที่เลือกอยู่แล้ว ให้อนุญาตให้รีเซ็ตคะแนน
                        inputElement.value = 0; // ตั้งค่าคะแนนเป็น 0
                        displayElement.innerText = `คะแนน: 0 ดาว`; // แสดงคะแนน 0 ดาว
                        stars.forEach(s => s.classList.remove('selected')); // ล้างการเลือกดาว
                    } else {
                        // ล้างดาวที่เลือกก่อนหน้า
                        stars.forEach(s => {
                            s.classList.remove('selected');
                        });

                        // เลือกดาวที่ถูกคลิก
                        star.classList.add('selected');
                        // ตั้งค่าคะแนนใน input hidden
                        inputElement.value = newValue;

                        // เปลี่ยนสีดาวตามคะแนนที่เลือก
                        stars.forEach((s, index) => {
                            if (index < newValue) {
                                s.classList.add('selected'); // ทำให้ดาวทางซ้ายเป็นสีทอง
                            } else {
                                s.classList.remove('selected'); // ทำให้ดาวทางขวาเป็นสีเทา
                            }
                        });

                        // แสดงคะแนน
                        displayElement.innerText = `คะแนน: ${newValue} ดาว`; // แสดงคะแนน
                    }
                });
            });
        };

        // เรียกใช้ฟังก์ชันสำหรับแต่ละคะแนน
        ratingStars(document.getElementById('quality-rating'), document.getElementById('quality'), document.getElementById('quality-display'));
        ratingStars(document.getElementById('price-rating'), document.getElementById('price'), document.getElementById('price-display'));
        ratingStars(document.getElementById('service-rating'), document.getElementById('service'), document.getElementById('service-display'));

        // ตรวจสอบการส่งฟอร์ม
        document.getElementById('review-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const quality = document.getElementById('quality').value;
            const price = document.getElementById('price').value;
            const service = document.getElementById('service').value;
            const comment = document.getElementById('comment').value;

            if (quality && price && service && comment) {
                alert('รีวิวของคุณได้ถูกส่งแล้ว! ขอบคุณสำหรับการรีวิว');
                // สามารถใช้ฟังก์ชันนี้ในการส่งข้อมูลไปยังเซิร์ฟเวอร์
            } else {
                alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
        });
    </script>

    <!-- <script>
        function generateSellOrder() {
            // สร้างหมายเลขการสั่งซื้ออัตโนมัติ โดยใช้วันที่และเวลาปัจจุบันร่วมกับตัวเลขสุ่ม
            let currentDateTime = new Date();
            let timestamp = currentDateTime.getTime(); // ได้เป็น timestamp
            let randomInt = Math.floor(Math.random() * 1000); // สร้างเลขสุ่ม 3 หลัก
            let sellOrder = 'ORD-' + timestamp + '-' + randomInt;

            // แสดงหมายเลขการสั่งซื้อใน input ที่ readonly
            document.getElementById('sell_order').value = sellOrder;
        }
    </script> -->


    <!--  -->
    <script>
        // เมื่อหน้าเว็บถูกโหลด ให้เรียกฟังก์ชันเพื่ออัปเดตตะกร้า
        document.addEventListener('DOMContentLoaded', checkUserLogin);

        function checkUserLogin() {
            // Fetch session data to retrieve ct_id
            fetch('get_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.ct_id) {
                        loadCart(data.ct_id); // Load cart from server for this ct_id
                    } else {
                        loadCartFromLocalStorage(); // If not logged in, use localStorage
                    }
                })
                .catch(error => console.error('Error fetching session:', error));
        }

        // Function to load the cart items from PHP session
        function loadCart(ct_id) {
            fetch('get_cart_items.php?ct_id=' + ct_id) // Pass ct_id to get cart items for the user
                .then(response => response.json())
                .then(data => {
                    userCart = data;
                    updateCart(userCart);
                })
                .catch(error => console.error('Error loading cart:', error));
        }

        // Function to load cart data from localStorage
        function loadCartFromLocalStorage() {
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            updateCart(cartItems);
        }

        // Handle form submission to add an item to the cart
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            const formData = new FormData(this);

            // Send data to the server to save in the database
            fetch('service_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartItem = {
                            name: data.cartItems[0].name,
                            image: data.cartItems[0].image,
                            price: data.cartItems[0].price,
                            note: data.cartItems[0].note,
                            size: data.cartItems[0].size,
                            product_id: data.cartItems[0].product_id,
                        };

                        // Add new item to userCart without clearing previous items
                        userCart.push(cartItem); // Add the new item to the existing cart
                        updateCart(userCart); // Update cart display with all items
                        alert('Item added to cart successfully!'); // Notify the user

                        // Clear the form
                        this.reset(); // Clear the form fields
                    } else {
                        alert('Error saving data: ' + data.message); // Show error message
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An unexpected error occurred. Please try again.'); // Notify user of an error
                });
        });

        // Retrieve cart data from localStorage, linked with user_id
        let cartData = JSON.parse(localStorage.getItem('cartItems')) || {};
        let user_id = <?php echo json_encode($user_id); ?>; // Get user_id from PHP to use in JS
        let userCart = cartData[user_id] || []; // If user has no cart data, initialize to empty array
        // Function to handle checkout
        function checkout() {
            const cartItems = userCart; // สมมติว่า userCart เก็บรายการสินค้าปัจจุบัน
            const totalAmount = cartItems.reduce((total, item) => total + parseFloat(item.price), 0);

            // ดึงค่า mch_order_no จากฟอร์ม
            const mch_order_no = document.querySelector('input[name="mch_order_no"]').value;

            // ดึงค่าจาก fee_type และ channel
            const fee_type = document.querySelector('select[name="fee_type"]').value;
            const channel = document.querySelector('select[name="channel"]').value;

            // Log ข้อมูลที่ส่งไปยังเซิร์ฟเวอร์
            console.log('Sending data:', {
                cartData: cartItems,
                total_fee: totalAmount,
                mch_order_no: mch_order_no,
                fee_type: fee_type,
                channel: channel
            });

            fetch('payment_load.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        cartData: JSON.stringify(cartItems),
                        total_fee: totalAmount,
                        mch_order_no: mch_order_no,
                        fee_type: fee_type,
                        channel: channel
                    })
                })
                .then(response => {
                    // ตรวจสอบว่าการตอบสนองเป็น 200 OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // แปลงข้อมูล JSON
                })
                .then(data => {
                    if (data.success) {
                        console.log(data.message); // ข้อความจากเซิร์ฟเวอร์

                        const ct_id = data.ct_id;
                        const orderNumber = data.order_number;

                        // Update UI or refresh cart items
                        updateCart(cartItems); // อัปเดตการแสดงผลตะกร้า

                        // Show payment popup
                        togglePopup(); // เรียกใช้ togglePopup เพื่อแสดง popup

                        // Populate popup with cart items and total amount
                        const popupContent = document.querySelector('.content5');
                        const popupItemsContainer = popupContent.querySelector('#cart-items-container');
                        popupItemsContainer.innerHTML = ''; // Clear previous content

                        cartItems.forEach(item => {
                            popupItemsContainer.innerHTML += `
                <div class="cart-item">
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="item-info">
                        <h3>${item.name}</h3>
                        <span class="price">${item.price}฿</span>
                    </div>
                </div>
                `;
                        });

                        // สร้างหรืออัปเดต total-info
                        let totalInfoElement = popupContent.querySelector('.total-info');

                        if (!totalInfoElement) {
                            // ถ้ายังไม่มี .total-info ให้สร้างใหม่
                            totalInfoElement = document.createElement('div');
                            totalInfoElement.className = 'total-info';
                            popupContent.appendChild(totalInfoElement); // เพิ่มไปยัง popupContent
                        }

                        // กำหนดค่า innerHTML
                        totalInfoElement.innerHTML = `
            <p>รวม: <span id="total-price">${totalAmount}฿</span></p>
            `;

                        // Add edit button to the popup โดยส่งค่าที่จำเป็น
                        popupContent.innerHTML += `
            <button class="submit-btn" onclick="edit('${orderNumber}', '${ct_id}', ${totalAmount}, '${mch_order_no}', '${fee_type}', '${channel}')">ชำระเงิน</button>
            `;

                    } else {
                        console.error(data.message); // ข้อความผิดพลาดจากเซิร์ฟเวอร์
                        alert('Error: ' + data.message); // แจ้งผู้ใช้เกี่ยวกับข้อผิดพลาด
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // แสดงข้อผิดพลาดใน console
                    alert('เกิดข้อผิดพลาด: ' + error.message); // แจ้งผู้ใช้เกี่ยวกับข้อผิดพลาด
                });
        }

        // Function to redirect to demo_pay.php
        function edit(orderNumber, ct_id, totalAmount, mch_order_no, fee_type, channel) {
            // เปลี่ยนเส้นทางไปยังไฟล์ demo_pay.php และส่งข้อมูลที่จำเป็น
            window.location.href = `../test_qrcode/demo_pay.php?orderNumber=${orderNumber}&ct_id=${ct_id}&total_fee=${totalAmount}&mch_order_no=${mch_order_no}&fee_type=${fee_type}&channel=${channel}`;
        }

        // Function to redirect to demo_pay.php
        function edit(orderNumber, ct_id, totalAmount, mch_order_no, fee_type, channel) {
            // เปลี่ยนเส้นทางไปยังไฟล์ demo_pay.php และส่งข้อมูลที่จำเป็น
            window.location.href = `../test_qrcode/demo_pay.php?orderNumber=${orderNumber}&ct_id=${ct_id}&total_fee=${totalAmount}&mch_order_no=${mch_order_no}&fee_type=${fee_type}&channel=${channel}`;
        }
        // Function to add item to the cart
        function addToCart(item) {
            userCart.push(item); // Add new item to user's cart
            updateCart(userCart); // Update cart display
        }

        // Function to update cart display
        function updateCart(cartItems) {
            const cartItemsContainer = document.getElementById('cart-items');
            let cartHTML = '';
            let total = 0;

            // Loop to generate HTML for cart items
            cartItems.forEach((item, index) => {
                cartHTML += `
        <div class="box">
            <i class='bx bx-trash' onclick="removeFromCart(${index})"></i>
            <img src="${item.image}" alt="${item.name}">
            <div class="content">
                <h3>${item.name}</h3>
                <span class="price">${item.price}฿</span>
                <p class="note">หมายเหตุ: ${item.note || "ไม่มี"}</p>
                <p class="size">ขนาด: ${item.size || "ไม่ระบุ"}</p>
            </div>
        </div>
    `;
                total += parseFloat(item.price);
            });

            // Update cart HTML
            cartItemsContainer.innerHTML = cartHTML;

            // Update total price
            document.getElementById('cart-total').textContent = total + '฿';

            // Update hidden input values
            document.getElementById('cartData').value = JSON.stringify(cartItems); // Convert cart items to JSON string
            document.getElementById('cartTotal').value = total; // Update total price

            // Store cart data in localStorage
            cartData[user_id] = cartItems; // Update user's cart data
            localStorage.setItem('cartItems', JSON.stringify(cartData));
        }

        // Function to toggle popup for checkout
        function togglePopup() {
            const popup = document.getElementById("popup-2");
            const shoppingCart = document.querySelector('.shopping-cart'); // Select shopping-cart
            const isActive = popup.classList.contains("active");

            // If the popup is currently active, close it
            if (isActive) {
                popup.classList.remove("active");
                return; // Exit the function
            }

            // Clear previous content
            const popupContent = document.querySelector('.content5');
            popupContent.innerHTML = ''; // Clear previous content in popup

            // Add close button at the top of the popup
            popupContent.innerHTML += `
        <div class="close-btn" onclick="togglePopup()" style="cursor: pointer; font-size: 24px; text-align: right;">&times;</div>
    `;

            // If there are no items in the cart, notify the user
            if (userCart.length === 0) {
                popupContent.innerHTML += '<p>ไม่มีสินค้าในตะกร้า</p>'; // "No items in the cart"
            } else {
                // Generate checkout content if there are items
                const popupItemsContainer = document.createElement('div');
                popupItemsContainer.id = 'cart-items-container';
                popupContent.appendChild(popupItemsContainer);

                userCart.forEach(item => {
                    popupItemsContainer.innerHTML += `
                <div class="cart-item">
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="item-info">
                        <h3>${item.name}</h3>
                        <span class="price">${item.price}฿</span>
                    </div>
                </div>
            `;
                });
            }

            // Show popup
            popup.classList.add("active");

            // Hide the shopping cart when the popup is shown
            shoppingCart.classList.remove('active'); // Hide shopping-cart
        }

        // Function to remove item from cart
        function removeFromCart(index) {
            userCart.splice(index, 1); // Remove item at index
            updateCart(userCart); // Update cart display
        }


        // Show cart data when the page loads
        updateCart(userCart);
    </script>





</body>

</html>