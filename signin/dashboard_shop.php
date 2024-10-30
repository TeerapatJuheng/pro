<?php
session_start();
include('../inc/server.php');

$Query1 = "SELECT COUNT(*) as total 
FROM tb_sell 
WHERE shop_id = '$_SESSION[shop_id]' 
AND DATE(sell_date) = CURDATE()";
$result1 = mysqli_query($conn, $Query1) or die("database error:" . mysqli_error($conn));
$total_date = mysqli_fetch_assoc($result1);

$Query2 = "SELECT COUNT(*) as total FROM `tb_sell` WHERE shop_id = $_SESSION[shop_id]";
$result2 = mysqli_query($conn, $Query2) or die("database error:" . mysqli_error($conn));
$total = mysqli_fetch_assoc($result2);

$query3 = "SELECT SUM(sell_total) as sell_total
FROM tb_sell 
WHERE DATE(sell_date) = CURDATE() AND shop_id = ?";
$stmt3 = $conn->prepare($query3);
$stmt3->bind_param("i", $_SESSION['shop_id']);
$stmt3->execute();
$result3 = $stmt3->get_result();
$sale_date = $result3->fetch_assoc();

$Query4 = "SELECT SUM(sell_total) as sell_total FROM `tb_sell` WHERE shop_id = $_SESSION[shop_id]";
$result4 = mysqli_query($conn, $Query4) or die("database error:" . mysqli_error($conn));
$sale_total = mysqli_fetch_assoc($result4);

$Query5 = "SELECT COUNT(date_service) as date_service
FROM tb_service 
WHERE DATE(date_service) = CURDATE()";
$result5 = mysqli_query($conn, $Query5) or die("database error:" . mysqli_error($conn));
$date_service = mysqli_fetch_assoc($result5);

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
    $profileImage = htmlspecialchars($shop['shop_img']);
} else {
    $fullName = "User not found";
    $profileImage = "default-image.png"; // กรณีที่ไม่พบผู้ใช้
}

$stmt->close();
$conn->close();

// แสดงผลราคาที่จัดรูปแบบ
$sellTotalToday = isset($sale_date['sell_total']) ? number_format($sale_date['sell_total'], 2) . ' บาท' : '0 บาท';
$totalSell = isset($sale_total['sell_total']) ? number_format($sale_total['sell_total'], 2) . ' บาท' : '0 บาท';

// echo "<div class='number'>ยอดขายวันนี้: $sellTotalToday</div>";
// echo "<div class='number'>ยอดขายทั้งหมด: $totalSell</div>";
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
            --primary: #eeeeee;
            --secondary: #2192ff;
            --grey: #808080;
            --white: #ffffff;
            --black: #222222;
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

        section {
            padding: 2rem 9%;
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
            font-size: 12px;
            color: var(--black);
            cursor: pointer;
            text-align: center;
            background-color: var(--white);
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
            background: #fff;
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

        @media (min-width: 1050px) and (max-width: 1199px) {
            html {
                font-size: 58%;
                /* Slightly smaller base font size */
            }

            .cardBox {
                grid-template-columns: repeat(3, 1fr);
                /* Reduce to 3 columns for better balance */
                grid-gap: 25px;
                /* Increase spacing between cards */
                padding: 15px;
                /* Adjust padding for better fit */
            }

            .cardBox .card {
                padding: 18px;
                /* Slightly reduce padding inside the card */
                border-radius: 8px;
                /* Adjust border-radius for smaller screens */
            }

            .cardBox .card .iconBx {
                font-size: 3rem;
                /* Slightly smaller icon size */
                top: 15px;
                /* Adjust top position for icon */
                right: 15px;
                /* Adjust right position for icon */
            }

            .cardBox .card .number {
                font-size: 2.2rem;
                /* Decrease the size of the number text */
            }

            .cardBox .card .cardname {
                font-size: 1.4rem;
                /* Decrease the card name text size */
            }

            .popup2 .content5 {
                padding: 18px;
                /* Slight adjustment for padding */
                border-radius: 5px;
                max-width: 85%;
                margin-top: 60px;
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 15px;
                /* Font size slightly smaller for text */
            }
        }

        @media (min-width: 1024px) and (max-width: 1049px) {
            html {
                font-size: 56%;
                /* ปรับขนาดฟอนต์พื้นฐานให้เล็กลง */
            }

            .cardBox {
                grid-template-columns: repeat(2, 1fr);
                /* ใช้ 2 คอลัมน์แทน 3 เพื่อไม่ให้คอนเทนต์แน่นเกินไป */
                grid-gap: 20px;
                /* ระยะห่างระหว่างกล่อง */
                padding: 15px;
                /* ปรับขนาด padding */
            }

            .cardBox .card {
                padding: 15px;
                /* ลดขนาด padding ภายในการ์ด */
                border-radius: 6px;
                /* ลดความโค้งของการ์ด */
            }

            .cardBox .card .iconBx {
                font-size: 2.8rem;
                /* ลดขนาดไอคอนให้เล็กลง */
                top: 10px;
                /* ลดระยะห่างจากด้านบน */
                right: 10px;
                /* ลดระยะห่างจากด้านขวา */
            }

            .cardBox .card .number {
                font-size: 2rem;
                /* ปรับขนาดตัวเลขให้เล็กลง */
            }

            .cardBox .card .cardname {
                font-size: 1.3rem;
                /* ขนาดของชื่อการ์ด */
            }

            .popup2 .content5 {
                padding: 15px;
                /* ปรับ padding ของ popup */
                border-radius: 5px;
                max-width: 80%;
                /* ปรับความกว้างของ popup */
                margin-top: 50px;
                /* ระยะห่างจากด้านบน */
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 14px;
                /* ขนาดฟอนต์สำหรับ label และข้อความ */
            }
        }



        @media (max-width:991px) {
            html {
                font-size: 55%;
            }

            .header {
                padding: 2rem;
            }

            .cardBox {
                grid-template-columns: repeat(2, 1fr);
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

            .cardBox {
                grid-template-columns: 1fr;
            }

            .cardBox .card {
                grid-template-columns: 1fr;
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

        @media (max-width: 450px) {
            html {
                font-size: 50%;
                /* ปรับขนาดฟอนต์พื้นฐานให้เล็กลง */
            }

            .cardBox {
                grid-template-columns: 1fr !important;
                /* ทำให้ cardBox มีการจัดเรียงในแนวตั้ง */
                padding: 10px;
                text-align: left;
                /* จัดให้ข้อความอยู่ชิดซ้าย */
            }

            .cardBox .card {
                display: flex;
                flex-direction: row;
                /* จัดเรียงเนื้อหาในแนวนอน */
                justify-content: flex-start;
                /* จัดให้เริ่มต้นที่ด้านซ้าย */
                align-items: center;
                /* จัดให้กลางในแนวตั้ง */
                padding: 15px;
                /* ปรับขนาด padding ให้เหมาะสม */
                border-radius: 5px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                margin: 5px 0;
                /* เพิ่มระยะห่างระหว่างการ์ด */
                width: 100%;
                /* ให้การ์ดกว้างเต็มพื้นที่ */
            }

            .cardBox .card .text {
                display: flex;
                flex-direction: column;
                /* จัดเรียงตัวเลขและข้อความในแนวตั้ง */
                margin-right: 10px;
                /* เพิ่มระยะห่างระหว่างข้อความกับไอคอน */
                flex-grow: 1;
                /* ให้ข้อความขยายเต็มพื้นที่ */
                align-items: flex-start;
                /* จัดให้ชิดซ้าย */
                width: auto;
                /* ปรับให้กว้างตามเนื้อหา */
            }

            .cardBox .card .number {
                font-size: 2rem;
                /* ขนาดตัวเลข */
                color: var(--blue);
                font-weight: 600;
                margin: 0;
                /* ลบ margin เพื่อชิดขอบซ้าย */
                text-align: left;
                /* จัดให้ชิดซ้าย */
            }

            .cardBox .card .cardname {
                font-size: 1.1rem;
                /* ขนาดชื่อการ์ด */
                color: gray;
                margin: 0;
                /* ลบ margin เพื่อชิดขอบซ้าย */
                text-align: left;
                /* จัดให้ชิดซ้าย */
            }

            .cardBox .card .iconBx {
                font-size: 2.5rem;
                /* ขนาดไอคอน */
                color: var(--black2);
                margin-left: auto;
                /* จัดให้ไอคอนอยู่ที่ด้านขวา */
                text-align: left;
                /* จัดให้ชิดซ้าย */
            }

            /* popup2 */
            .popup2 .content5 {
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 45px;
            }

            .popup2 .name-report1 label,
            .popup2 .name-report1 span p {
                font-size: 14px;
            }

            .popup2.active {
                width: 100%;
                height: 100%;
            }

            /* popup messenger */
            .popup3 .content3 {
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 45px;
                max-width: 350px;
            }

            .popup3 .name-report3 label,
            .popup3 .name-report3 span p {
                font-size: 14px;
            }

            .popup3.active {
                width: 100%;
                height: 100%;
            }

            .content-table {
                display: inline;
            }

            .popup4 .content4 {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0);
                background: #fff;
                max-width: 300px;
                z-index: 2;
                padding: 20px;
                box-sizing: border-box;
                border-radius: 10px;
                margin-top: 30px;
                overflow-y: auto;
                height: 550px;
            }
        }

        /* card box*/

        .font {
            margin-top: 100px;
            font-size: 12px;
            margin-left: 20px;
        }

        .cardBox {
            position: relative;
            width: 100%;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* ใช้ 4 คอลัมน์ */
            margin-top: 10px;
            grid-gap: 20px;
            /* ระยะห่างระหว่างกล่อง */
        }

        .cardBox .card {
            position: relative;
            background: var(--white);
            padding: 20px;
            /* ลด padding เพื่อให้ดูเรียบง่าย */
            border-radius: 10px;
            display: flex;
            flex-direction: row;
            /* จัดเรียงเนื้อหาในแนวนอน */
            justify-content: space-between;
            /* จัดให้เนื้อหาแบ่งอยู่ระหว่างด้านซ้ายและขวา */
            align-items: center;
            /* จัดให้กลางในแนวตั้ง */
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* ลดเงาให้ดูเบาลง */
            transition: background 0.3s, transform 0.2s;
            /* เพิ่มการเคลื่อนไหว */
        }

        .cardBox .card:hover {
            background: #507F99;
            /* สีพื้นหลังเมื่อ hover */
            transform: translateY(-5px);
            /* ยกขึ้นเมื่อ hover */
        }

        .cardBox .card .text {
            display: flex;
            flex-direction: column;
            /* จัดเรียงตัวเลขและข้อความในแนวตั้ง */
            align-items: flex-start;
            /* จัดให้ชิดซ้าย */
            flex-grow: 1;
            /* ให้ข้อความขยายเต็มพื้นที่ */
        }

        .cardBox .card .number {
            font-size: 2rem;
            /* ขนาดตัวเลข */
            color: var(--blue);
            font-weight: 600;
            margin: 0;
            /* ลบ margin เพื่อชิดขอบซ้าย */
            text-align: left;
            /* จัดให้ชิดซ้าย */
        }

        .cardBox .card .cardname {
            font-size: 1.3rem;
            /* ขนาดชื่อการ์ด */
            color: gray;
            margin: 0;
            /* ลบ margin เพื่อชิดขอบซ้าย */
            text-align: left;
            /* จัดให้ชิดซ้าย */
        }

        .cardBox .card .iconBx {
            font-size: 3.5rem;
            /* ขนาดไอคอน */
            color: var(--black2);
            margin-left: auto;
            /* จัดให้ไอคอนอยู่ที่ด้านขวา */
        }

        .cardBox .card:hover .number,
        .cardBox .card:hover .cardname,
        .cardBox .card:hover .iconBx {
            color: var(--white);
            /* เปลี่ยนสีข้อความเมื่อ hover */
        }

        /*รายการออเดอร์*/

        .container {
            max-width: 100%;
            /*margin-top:100px;*/
        }

        .container h1 {
            text-align: left;
            font-size: 22px;
            color: #000;
            margin-left: 20px;
        }

        table {
            width: 99%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /*th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }*/

        th {
            background-color: #507F99;
        }

        .content-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 11px;
            min-width: 400px;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            margin-left: 6px;
        }

        .content-table thead tr {
            background-color: #507F99;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .content-table th,
        .content-table td {
            padding: 12px 15px;
        }

        .container .content-table th {
            font-size: 15px;
        }

        .container .content-table td {
            font-size: 12px;
        }

        .content-table td {
            background-color: #ffffff;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #507F99;
        }

        /*.content-table tbody tr.active-row {
            font-weight:bold;
            color:#507F99;
        }*/

        select {
            width: 100%;
            height: 3rem;
            font-size: 1.5rem;
            color: #666;
            border: .1rem solid #507F99;
            border-radius: 5px;
            text-align: center;
        }

        .active p {
            background: #9FE2BF;
            padding: 2px 10px;
            display: inline-block;
            border-radius: 40px;
            color: #2b2b2b;
        }

        tr:hover td {
            color: #507F99;
            cursor: pointer;
            background-color: #EBF5FB;
        }

        /* review */

        .review {
            margin-bottom: 30px;
            width: 100vw;
            padding: 0;
            /* เอา padding ออก */
        }

        .review .review-slider {
            padding: 1rem;
            box-sizing: border-box;
            width: 100%;
            display: flex;
            /* ใช้ flexbox เพื่อจัดเรียง slides */
            justify-content: center;
            /* จัดกลาง */
        }

        .review .review-slider .box3 {
            background: #fff;
            border-radius: .5rem;
            text-align: center;
            padding: 1rem;
            /* ลด padding เพื่อให้กล่องเล็กลง */
            outline-offset: -1rem;
            outline: var(--outline);
            box-shadow: var(--box-shadow);
            transition: .2s linear;
            width: 200px;
            /* กำหนดความกว้างให้เหมาะสม */
            margin: 0 10px;
            /* เพิ่มระยะห่างระหว่างกล่อง */
            box-sizing: border-box;
        }

        .review .review-slider .box3:hover {
            outline-offset: 0rem;
            outline: var(--outline-hover);
        }

        .review .review-slider .box3 img {
            height: 80px;
            /* ลดความสูงของรูปภาพ */
            width: 80px;
            /* ปรับความกว้างให้เหมาะสม */
            border: 1px solid #000;
        }

        .review .review-slider .box3 h3 {
            font-size: 14px;
            /* ปรับขนาดให้เล็กลง */
            color: var(--black);
            margin: 0.5rem 0;
            /* เพิ่มระยะห่าง */
        }

        .review .review-slider .box3 p {
            font-size: 11px;
            /* ปรับขนาดข้อความ */
            color: var(--light-color);
            padding: .2rem 0;
        }

        .review .review-slider .box3 .stars i {
            font-size: 1.5rem;
            /* ปรับขนาดดาวให้เล็กลง */
            color: #FFBF00;
            padding: 0.2rem 0;
            /* ลด padding */
        }

        .review h1 {
            text-align: left;
            font-size: 22px;
            color: #000;
            margin: 0;
            padding: 0 1rem;
            box-sizing: border-box;
            width: calc(100vw - 2rem);
            /* ปรับขนาดเพื่อไม่ให้เกินขอบ */
        }

        /* popup2 */

        #popup-2 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: none;
        }

        #popup-2.active {
            display: block;
        }

        .overlay1 {
            width: 1000vw;
            height: 1000vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup2 .content5 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            height: 585px;
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
            transform: translate(-50%, -50%) scale(1);
        }

        .popup2 .content5 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup2 .content5 .name-report1 {
            font-size: 15px;
            display: flex;
            justify-content: left;
            padding-bottom: 5px;
        }

        .popup2 .content5 .name-report1 p {
            font-size: 14px;
            color: #507F99;
            background-color: #fff;
        }

        .popup2 .content5 .name-report1 span {
            color: #666;
        }

        .popup2 .content5 a {
            font-size: 14px;
        }

        /* step */
        .wrapper {
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .progress-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .progress-container::after {
            content: "";
            position: absolute;
            height: 3px;
            width: 90%;
            top: 31%;
            left: 1rem;
            background-color: var(--grey);
            z-index: 0;
        }

        .progress {
            position: absolute;
            left: 0;
            height: 3px;
            top: 33%;
            width: 0;
            transform: translateY(-50%);
            background-color: var(--secondary);
            z-index: 1;
        }

        .progress-step {
            display: flex;
            align-items: center;
            flex-direction: column;
            font-weight: 500;
            color: var(--black);
            z-index: 2;
        }

        .progress-step .bx {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--grey);
            border: 1px solid var(--grey);
            height: 50px;
            width: 50px;
            border-radius: 50%;
            font-size: 18px;
            background-color: var(--white);
            margin-bottom: 10px;
        }

        .progress-step .bx.active {
            border: 3px solid var(--secondary);
            color: var(--secondary);
        }

        .btn-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-container .btn-next {
            padding: 0.5rem 1.5rem;
            margin: 0 1rem;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            background-color: #507F99;
            color: var(--white);
            cursor: pointer;
        }

        .btn.disabled {
            border: 1px solid var(--grey);
            background-color: var(--grey);
            color: var(--white);
            cursor: not-allowed;
        }

        /* popup3 */

        #popup3 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: none;
        }

        #popup3.active {
            display: block;
        }

        .overlay3 {
            width: 1000vw;
            height: 1000vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup3 .content3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            height: 585px;
            z-index: 2;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        .popup3 .close-btn {
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

        .popup3.active .overlay3 {
            display: block;
        }

        .popup3.active .content3 {
            transition: all 300ms ease-in-out;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup3 .content3 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup3 .content3 .name-report3 {
            font-size: 15px;
            display: flex;
            justify-content: left;
            padding-bottom: 5px;
        }

        .popup3 .content3 .name-report3 p {
            font-size: 14px;
            color: #507F99;
            background-color: #fff;
        }

        .popup3 .content3 .name-report3 span {
            color: #666;
        }

        .popup3 .content3 a {
            font-size: 14px;
        }

        /* popup4 */

        #popup4 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: none;
        }

        #popup4.active {
            display: block;
        }

        .overlay4 {
            width: 1000vw;
            height: 1000vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup4 .content4 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            z-index: 2;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
            margin-top: 30px;
            overflow-y: auto;
            max-height: 600px;
        }

        .popup4 .close-btn {
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

        .popup4.active .overlay4 {
            display: block;
        }

        .popup4.active .content4 {
            transition: all 300ms ease-in-out;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup4 .content4 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .popup4 .content4 a {
            font-size: 14px;
        }

        /* form */
        .popup4 {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .overlay4 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .content4 {
            position: relative;
            background: #fff;
            padding: 10px;
            border-radius: 10px;
            width: 300px;
            margin: auto;
            top: 10%;
        }

        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
        }

        .container2 {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 12px;
        }

        .form-group img {
            width: 200px;
            height: 200px;
        }

        .form-group input,
        .form-group textarea {
            padding: 5px;
            font-size: 12px;
        }


        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
            resize: none;
        }

        .form-group p {
            margin-bottom: 5px;
        }

        .checkbox-group {
            margin-bottom: 10px;
        }

        .checkbox-group label {
            display: inline-block;
            margin-right: 10px;
        }

        /* Additional CSS for centering the button */
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Ensure button is centered vertically */
        }
    </style>
    <title>Dashboard shop</title>
</head>

<body>

    <!-- header section starts -->
    <header class="header">
        <a href="dashboard_shop.php" class="logo">Laundry</a>

        <nav class="navbar">
            <a href="dashboard_shop.php">Dashboard Service</a>
            <a href="store_shop.php">Service</a>
            <a href="faq_shop.php">FAQ</a>
            <a href="sevice_shop.php">Terms & Condition</a>
        </nav>

        <div class="icons">
            <div class="bx bx-menu" id="menu-btn"></div>
            <div class="bx bx-search" id="search-btn"></div>
            <div class="bx bx-user" id="user-btn"></div>
        </div>

        <form action="" class="search-form">
            <input type="search" id="search-box" placeholder="Search here...">
            <label for="search-box" class="bx bx-search"></label>
        </form>


        <div class="profile">
            <?php
            // ตรวจสอบว่ามีรูปภาพหรือไม่ ถ้าไม่มีให้แสดงรูปภาพเริ่มต้น
            $img = !empty($shop['shop_img']) ? htmlspecialchars($shop['shop_img']) : 'default.jpg';
            ?>
            <img src="../photo/<?php echo $img; ?>" alt="Profile Image">
            <h3><?php echo $fullName; ?></h3>
            <span>shop</span>
            <a href="profile_shop.php" class="btnp">Profile</a>
            <div class="flex-btnp">
                <a href="report_shop.php" class="option-btnp">รายงาน</a>
                <a href="login.php" class="option-btnp">Logout</a>
            </div>
        </div>
    </header>

    <!-- cards  -->

    <div class="font">
        <h1>Dashboard</h1>
    </div>
    <div class="cardBox">
        <div class="card">
            <div>
                <div class="number"><?= ucfirst(number_format($sale_date['sell_total'] ?? 0, 2) . ' THB'); ?></div>
                <div class="cardname">sale</div>
            </div>

            <div class="iconBx">
                <i class='bx bx-money'></i>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="number"><?= ucfirst(number_format($sale_total['sell_total'] ?? 0, 2) . ' THB'); ?></div>
                <div class="cardname">total sale</div>
            </div>

            <div class="iconBx">
                <i class='bx bxs-wallet'></i>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="number"><?= ucfirst($total_date['total'] ?? 0) . ' รายการ'; ?></div>
                <div class="cardname">order</div>
            </div>

            <div class="iconBx">
                <i class='bx bx-cart'></i>
            </div>
        </div>

        <div class="card" onclick="messenger()">
            <div>
                <div class="number"><?= ucfirst($date_service['date_service']); ?></div>
                <div class="cardname">messenger</div>
            </div>

            <div class="iconBx">
                <i class='bx bx-message-alt-error'></i>
            </div>
        </div>
    </div>

    <!-- cards end -->



    <!-- order  -->

    <div class="container">
        <h1>Order</h1>

        <table class="content-table">
            <thead>
                <tr>
                    <th>ออเดอร์</th>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th>วันที่</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>00001</td>
                    <td>สาวสวย</td>
                    <td class="active">
                        <p>รอรับออเดอร์</p>
                    </td>
                    <td>10-07-24</td>
                    <td class="edit"><button class="ck" onclick="edit()">Edit</button></td>
                </tr>
                <tr>
                    <td>00002</td>
                    <td>สุดใจ</td>
                    <td class="active">
                        <p>กำลังดำเนินการ</p>
                    </td>
                    <td>10-07-24</td>
                    <td class="edit"><button class="ck" onclick="edit()">Edit</button></td>
                </tr>
                <tr>
                    <td>00003</td>
                    <td>โอ้โห</td>
                    <td class="active">
                        <p>เสร็จ</p>
                    </td>
                    <td>10-07-24</td>
                    <td class="edit"><button class="ck" onclick="edit()">view</button></td>
                </tr>
                <!-- สามารถเพิ่มแถวข้อมูลต่อไปตามต้องการ -->
            </tbody>
        </table>
    </div>

    <!-- order end-->


    <!-- review-->
    <section class="review" id="review">

        <h1 class="review-name">Review</h1>

        <div class="swiper review-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="swiper-slide box3">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อลูกค้า</h3>
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>Lorem ipsum dolor sit amet.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- popup-->

    <div class="popup2" id="popup-2">
        <div class="overlay1"></div>
        <div class="content5">
            <div class="close-btn" onclick="edit()">&times;</div>
            <h1>รายการ</h1>
            <div class="name-report1">
                <label for="text">ลำดับ : </label>
                <span>
                    <p>0001</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ชื่อ : </label>
                <span>
                    <p>สาวสวย</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">บริการ : </label>
                <span>
                    <p>ซัก อบ แห้ง</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">เบอร์โทร : </label>
                <span>
                    <p> 099-999-9999 </p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ที่อยู่ : </label>
                <span>
                    <p>หอพักสวัสดี 48/2 ม.7 ว.ลำผักชี ข.หนองจอก กทม</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">Map : </label>
                <span>
                    <p><a href="https://maps.app.goo.gl/sxaEgafBW4vicRHJA">ที่อยู่</a></p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ราคา : </label>
                <span>
                    <p>80 บาท</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ระยะทาง : </label>
                <span>
                    <p>5 Km.</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ค่าขนส่ง : </label>
                <span>
                    <p>50 บาท</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ราคารวม : </label>
                <span>
                    <p>130 บาท</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ประเภท : </label>
                <span>
                    <p>เสื้อผ้า</p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">ขนาด : </label>
                <span>
                    <p> S </p>
                </span>
            </div>
            <div class="name-report1">
                <label for="text">หมายเหตุ : </label>
                <span>
                    <p> </p>
                </span>
            </div>

            <!-- step by step-->
            <div class="wrapper">
                <div class="progress-container">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step">
                        <i class='bx bxs-cart-alt active'></i>รับออเดอร์
                    </div>
                    <div class="progress-step">
                        <i class='bx bx-loader-circle'></i>ดำเนินการ
                    </div>
                    <div class="progress-step">
                        <i class='bx bx-check-circle'></i>สำเร็จ
                    </div>
                </div>

                <div class="btn-container">
                    <button class="btn-next" onclick="next()">Next</button>
                </div>
            </div>
            <!-- step by step end-->

        </div>
    </div>

    <!-- popup end-->

    <!-- popup messenger-->

    <div class="popup3" id="popup3">
        <div class="overlay3"></div>
        <div class="content3">
            <div class="close-btn" onclick="messenger()">&times;</div>
            <h1>Messenger</h1>

            <!-- table messenger  -->

            <div class="container">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>เรื่อง</th>
                            <th>วันที่</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>สาวสวย</td>
                            <td>เสื้อขาด</td>
                            <td>10-07-24</td>
                            <td class="edit"><button class="ck" onclick="viewmessenger()">View</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>สาวสวย</td>
                            <td>เสื้อขาด</td>
                            <td>10-07-24</td>
                            <td class="edit"><button class="ck" onclick="viewmessenger()">View</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>สาวสวย</td>
                            <td>เสื้อขาด</td>
                            <td>10-07-24</td>
                            <td class="edit"><button class="ck" onclick="viewmessenger()">View</button></td>
                        </tr>
                        <!-- สามารถเพิ่มแถวข้อมูลต่อไปตามต้องการ -->
                    </tbody>
                </table>
            </div>

            <!-- table messenger end-->

        </div>
    </div>

    <!-- popup messenger end-->


    <!-- popup3 messenger view-->

    <div class="popup4" id="popup4">
        <div class="overlay4"></div>
        <div class="content4">
            <div class="close-btn" onclick="viewmessenger()">&times;</div>
            <h1>ปัญหา</h1>
            <div class="container2">
                <div class="form-group">
                    <label for="name">ชื่อ:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์:</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="order">เลขออเดอร์:</label>
                    <input type="text" id="order" name="order" required>
                </div>
                <div class="form-group">
                    <label for="issue">หัวข้อปัญหา:</label>
                    <input type="text" id="issue" name="issue" required>
                </div>
                <div class="form-group">
                    <label for="description">ลักษณะของปัญหาที่พบ:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image1">แนบรูปภาพ 1:</label>
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                </div>
                <div class="form-group">
                    <label for="image2">แนบรูปภาพ 2:</label>
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                </div>
                <div class="form-group">
                    <label for="image3">แนบ QR Payment :</label>
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- popup3 messenger view end-->

    <!-- product ดูยอดขาย -->

    <!-- <div class="container"> 
            <h1>Service</h1>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>ลำดับบริการ</th>
                            <th>ชื่อบริการ</th>
                            <th>จำนวนยอดขาย</th>
                            <th><select name="" id="">
                            <option value="1">วันนี้</option>
                            <option value="2">เดือนนี้</option>
                            </select></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ซัก+อบแห้ง</td>
                            <td>10</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>ซัก+อบแห้ง+รีด</td>
                            <td>7</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>ซัก+อบแห้ง</td>
                            <td>2</td>
                            <td></td>
                        </tr>
    
    </tbody>
    </table>
    </div>  -->

    <!-- product ดูยอดขาย end -->





    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--custom js file link -->
    <script src="js/script.js"></script>

    <script>
        let searchForm = document.querySelector('.search-form');
        let profile = document.querySelector('.header .profile');
        let navbar = document.querySelector('.header .navbar');

        document.querySelector('#search-btn').onclick = () => {
            searchForm.classList.toggle('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        }

        document.querySelector('#user-btn').onclick = () => {
            profile.classList.toggle('active');
            searchForm.classList.remove('active');
            navbar.classList.remove('active');
        }

        document.querySelector('#menu-btn').onclick = () => {
            navbar.classList.toggle('active');
            searchForm.classList.remove('active');
            profile.classList.remove('active');
        }

        window.onscroll = () => {
            searchForm.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        }

        var swiper = new Swiper(".review-slider", {
            loop: true,
            spaceBetween: 20,
            autoplay: {
                delay: 7500,
                disableOnInteraction: false,
            },
            centeredSlides: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1020: {
                    slidesPerView: 3,
                },
                1080: {
                    slidesPerView: 4,
                },

            },
        });

        /* popup */
        function edit() {
            document.getElementById("popup-2").classList.toggle("active");
        }

        const progress = document.getElementById("progress");
        const nextBtn = document.querySelector(".btn-next");
        const progressSteps = document.querySelectorAll(".progress-step .bx");

        // Default step value
        let currentStep = 1;

        const next = () => {
            // Increase step value by 1
            if (currentStep < progressSteps.length) {
                currentStep++;
                refresh();
            }
        };

        const refresh = () => {
            // Add or remove active class according to current step value
            progressSteps.forEach((step, index) => {
                if (index < currentStep) step.classList.add("active");
                else step.classList.remove("active");
            });

            // If current step value is greater than all progress steps
            // then set value to total length of progress steps
            if (currentStep > progressSteps.length) {
                currentStep = progressSteps.length;
                nextBtn.classList.add("disabled");
            } else {
                nextBtn.classList.remove("disabled");
            }

            // Calculate width for the progress bar
            const allActiveSteps = document.querySelectorAll(".progress-step .bx.active");
            const width = (allActiveSteps.length / progressSteps.length) * 100 - 5;

            progress.style.width = width + "%";
        };

        /* popup */
        function messenger() {
            document.getElementById("popup3").classList.toggle("active");
        }

        /* popup3 */
        function viewmessenger() {
            document.getElementById("popup4").classList.toggle("active");
        }
    </script>
</body>