<?php
session_start();
include('../inc/server.php');

// ตรวจสอบว่ามีค่า shop_id ใน session หรือไม่
if (!isset($_SESSION['shop_id'])) {
    die("Shop ID not found in session.");
}

// Queries to get various statistics
$Query1 = "SELECT COUNT(*) as total 
FROM tb_sell 
WHERE shop_id = ? 
AND DATE(sell_date) = CURDATE()";
$stmt1 = $conn->prepare($Query1);
$stmt1->bind_param("i", $_SESSION['shop_id']);
$stmt1->execute();
$result1 = $stmt1->get_result();
$total_date = $result1->fetch_assoc();

$Query2 = "SELECT COUNT(*) as total FROM `tb_sell` WHERE shop_id = ?";
$stmt2 = $conn->prepare($Query2);
$stmt2->bind_param("i", $_SESSION['shop_id']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$total = $result2->fetch_assoc();

$query3 = "SELECT SUM(sell_total) as sell_total
FROM tb_sell 
WHERE DATE(sell_date) = CURDATE() AND shop_id = ?";
$stmt3 = $conn->prepare($query3);
$stmt3->bind_param("i", $_SESSION['shop_id']);
$stmt3->execute();
$result3 = $stmt3->get_result();
$sale_date = $result3->fetch_assoc();

$Query4 = "SELECT SUM(sell_total) as sell_total FROM `tb_sell` WHERE shop_id = ?";
$stmt4 = $conn->prepare($Query4);
$stmt4->bind_param("i", $_SESSION['shop_id']);
$stmt4->execute();
$result4 = $stmt4->get_result();
$sale_total = $result4->fetch_assoc();

$Query5 = "SELECT COUNT(date_service) as date_service
FROM tb_service 
WHERE DATE(date_service) = CURDATE() AND ct_id = ?";
$stmt5 = $conn->prepare($Query5);
$stmt5->bind_param("i", $_SESSION['shop_id']);
$stmt5->execute();
$result5 = $stmt5->get_result();
$date_service = $result5->fetch_assoc();

// Fetch shop details
$shop_id = $_SESSION['shop_id'];
$query = "SELECT * FROM tb_shop WHERE id = ?";
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
    $profileImage = "default-image.png";
}

$stmt->close();

// Query to fetch services related to the shop
$serviceQuery = "
    SELECT 
        s.id,
        s.head_sevice,
        s.details_sevice,
        s.date_service,
        c.name AS customer_name,
        c.lastname AS customer_lastname
    FROM 
        tb_service s
    LEFT JOIN 
        tb_sell sel ON s.sell_id = sel.sell_id
    LEFT JOIN 
        tb_customer c ON s.ct_id = c.id
    WHERE 
        sel.shop_id = ?";

$serviceStmt = $conn->prepare($serviceQuery);
$serviceStmt->bind_param("i", $_SESSION['shop_id']);
$serviceStmt->execute();
$result_service = $serviceStmt->get_result();

// New Query to get order details
$orderQuery = "
    SELECT 
        s.sell_id,
        s.sell_order, 
        CONCAT(c.name, ' ', c.lastname) AS customer_name,
        s.sell_date 
    FROM 
        tb_sell s 
    JOIN 
        tb_customer c ON s.ct_id = c.id
    WHERE 
        s.shop_id = ?";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param("i", $shop_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

// New Query to get reviews
$reviewQuery = "
    SELECT 
        c.name,
        c.lastname,
        c.img,
        r.review_commant,
        r.review_stars
    FROM 
        tb_review r
    JOIN 
        tb_sell s ON r.review_order = s.sell_order
    JOIN 
        tb_customer c ON s.ct_id = c.id
    WHERE 
        s.shop_id = ?";
$reviewStmt = $conn->prepare($reviewQuery);
$reviewStmt->bind_param("i", $shop_id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

$conn->close();

// Format sales totals
$sellTotalToday = isset($sale_date['sell_total']) ? number_format($sale_date['sell_total'], 2) . ' บาท' : '0 บาท';
$totalSell = isset($sale_total['sell_total']) ? number_format($sale_total['sell_total'], 2) . ' บาท' : '0 บาท';
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
            z-index: 1000;
            /* เพิ่ม z-index เพื่อให้แน่ใจว่ามันอยู่บนสุด */
            display: none;
        }

        #popup-2.active {
            display: block;
        }

        .overlay1 {
            width: 100vw;
            /* ปรับให้เข้ากับขนาดหน้าจอ */
            height: 100vh;
            /* ปรับให้เข้ากับขนาดหน้าจอ */
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            /* ให้แน่ใจว่ามันอยู่ใต้ popup */
            display: none;
        }

        .popup2 .content5 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            width: 500px;
            /* ความกว้างของ container */
            max-width: 90%;
            /* ให้แน่ใจว่ามันไม่เกินขนาดหน้าจอ */
            height: auto;
            /* ทำให้ความสูงเป็นอัตโนมัติ */
            max-height: 90vh;
            /* ปรับความสูงสูงสุดให้มากขึ้น */
            z-index: 1000;
            /* ให้แน่ใจว่ามันอยู่บนสุด */
            padding: 20px;
            /* เพิ่ม padding ให้น้อยลง */
            box-sizing: border-box;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            /* ซ่อน scrollbar */
        }

        .popup2 .close-btn {
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 20px;
            width: 30px;
            height: 30px;
            background: #007bff;
            /* สีน้ำเงิน */
            color: #fff;
            font-size: 20px;
            /* ขนาดตัวอักษร */
            font-weight: bold;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            transition: background-color 0.3s;
            /* เพิ่ม transition เมื่อชี้ */
        }

        .popup2 .close-btn:hover {
            background: #0056b3;
            /* สีเมื่อชี้ */
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
            margin: 5px 0;
            font-size: 20px;
            /* ขนาดตัวอักษร */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
        }

        .popup2 .content5 .name-report {
            font-size: 12px;
            /* ขนาดตัวอักษรใน name-report */
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-weight: bold;
            /* ทำให้ตัวอักษรหนาใน name-report */
        }

        .popup2 .content5 .name-report label {
            color: #333;
            font-size: 12px;
            /* ขนาดตัวอักษรของ label */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
        }

        .popup2 .content5 .name-report span {
            color: #666;
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
            font-size: 12px;
            /* ขนาดตัวอักษรของข้อมูล */
        }

        .popup2 .content5 a {
            font-size: 10px;
            /* ขนาดตัวอักษรสำหรับลิงก์ */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
        }

        .popup2 .content5 a:hover {
            text-decoration: underline;
            /* ขีดเส้นใต้เมื่อชี้ */
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            /* จัดปุ่มให้ห่างกัน */
            margin-top: 15px;
            /* ลดระยะห่างด้านบน */
        }

        .btn-next {
            font-size: 12px;
            /* ขนาดตัวอักษรในปุ่ม */
            padding: 6px 10px;
            /* ปรับระยะห่างภายในให้เล็กลง */
            flex: 1;
            /* ให้ปุ่มใช้พื้นที่ให้เต็ม */
            margin: 5px;
            /* เพิ่มระยะห่างระหว่างปุ่ม */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
        }

        /* เพิ่มการจัดการเมื่อมีข้อมูลมาก */
        .name-report {
            flex-wrap: wrap;
            /* ให้มีการห่อเมื่อมีข้อมูลยาว */
        }

        .wrapper {
            margin-top: 10px;
            /* เพิ่มระยะห่างด้านบนสำหรับ progress */
        }

        .progress-container {
            display: flex;
            align-items: center;
            /* จัดให้อยู่ตรงกลาง */
            margin-bottom: 10px;
            /* เพิ่มระยะห่างด้านล่าง */
        }

        .progress {
            height: 5px;
            /* ความสูงของ progress bar */
            background: #007bff;
            /* สีของ progress bar */
            border-radius: 5px;
            flex: 1;
            /* ให้ progress bar ใช้พื้นที่ให้เต็ม */
            margin-right: 10px;
            /* ระยะห่างระหว่าง progress bar และ steps */
        }

        .progress-step {
            font-size: 12px;
            /* ขนาดตัวอักษรของ progress steps */
            color: #666;
            /* สีของตัวอักษร */
            font-weight: bold;
            /* ทำให้ตัวอักษรหนา */
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
            width: 600px;
            /* เพิ่มความกว้าง */
            max-width: 90%;
            /* เพื่อให้เหมาะกับหน้าจอเล็ก */
            height: auto;
            /* เปลี่ยนเป็น auto เพื่อให้ความสูงปรับตามเนื้อหา */
            max-height: 80vh;
            /* จำกัดความสูง */
            overflow-y: auto;
            /* เพิ่ม scroll เมื่อเนื้อหามากเกินไป */
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
                    <th>ลำดับ</th>
                    <th>ออเดอร์</th>
                    <th>ชื่อ</th>
                    <th>สถานะ</th>
                    <th>วันที่</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="order-status-body">
                <?php
                // Initialize the counter for order numbers
                $orderNumber = 1;

                // Display order details
                if ($orderResult->num_rows > 0) {
                    while ($row = $orderResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $orderNumber . "</td>"; // Display the order number
                        echo "<td>" . htmlspecialchars($row['sell_order']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                        echo "<td class='status' data-status-index='0'><p>รับออเดอร์</p></td>"; // Set initial status
                        echo "<td>" . date('d-m-y', strtotime($row['sell_date'])) . "</td>"; // Display date
                        echo "<td class='edit'><button class='ck' onclick='Popup_Detail(" . $row['sell_id'] . ", this.closest(\"tr\"))'>Edit</button></td>";
                        echo "</tr>";
                        $orderNumber++; // Increment the order number
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูลออเดอร์</td></tr>"; // Adjust colspan to match the new column
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- order end-->


    <!-- review-->
    <section class="review" id="review">
        <h1 class="review-name">Review</h1>

        <div class="swiper review-slider">
            <div class="swiper-wrapper">
                <?php
                // สมมติว่าคุณได้ดึงข้อมูลรีวิวไว้ในตัวแปร $reviewResult
                while ($review = $reviewResult->fetch_assoc()): ?>
                    <div class="swiper-slide box3">
                        <?php if (!empty($review['img'])): ?>
                            <img src="../photo/<?= htmlspecialchars($review['img']) ?>" alt="Review Image">
                        <?php else: ?>
                            <img src="default-image.png" alt="Default Image">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($review['name'] . ' ' . $review['lastname']) ?></h3>
                        <div class="stars">
                            <?php for ($i = 0; $i < intval($review['review_stars']); $i++): ?>
                                <i class='bx bxs-star'></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= htmlspecialchars($review['review_commant']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>


    <!-- popup-->

    <div class="popup2" id="popup-2">
        <div class="overlay1"></div>
        <div class="content5">
            <div class="close-btn" onclick="document.getElementById('popup-2').classList.remove('active')">&times;</div>
            <h1>รายการ</h1>
            <input type="hidden" name="orderNumber" id="inputOrderNumber"> <!-- ฟิลด์ที่ซ่อน -->
            <input type="hidden" name="shop_id" id="inputShopId"> <!-- แสดง shop_id -->
            <input type="hidden" name="sell_id" id="inputSellId"> <!-- แสดง sell_id -->

            <div class="name-report">
                <label for="text">เลขออเดอร์: </label><span id="orderNumber"></span>
            </div>
            <div class="name-report">
                <label for="text">ชื่อ: </label><span id="customerName"></span>
            </div>
            <div class="name-report">
                <label for="text">เบอร์โทร: </label><span id="customerPhone"></span>
            </div>
            <div class="name-report">
                <label for="text">ที่อยู่: </label><span id="customerAddress"></span>
            </div>
            <div class="name-report">
                <label for="text">วันที่: </label><span id="orderDate"></span>
            </div>
            <div class="name-report">
                <label for="text">ประเภท: </label><span id="orderType"></span>
            </div>
            <div class="name-report">
                <label for="text">ขนาด: </label><span id="orderSize"></span>
            </div>
            <div class="name-report">
                <label for="text">บริการ: </label><span id="orderService"></span>
            </div>
            <div class="name-report">
                <label for="text">ราคา: </label><span id="orderPrice"></span>
            </div>
            <div class="name-report">
                <label for="text">ระยะทาง: </label><span id="orderDistance"></span>
            </div>
            <div class="name-report">
                <label for="text">ค่าขนส่ง: </label><span id="shippingCost"></span>
            </div>
            <div class="name-report">
                <label for="text">หมายเหตุ: </label><span id="orderNotes"></span>
            </div>
            <div class="name-report">
                <label for="text">ราคารวม: </label><span id="totalPrice"></span>
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
                    <button class="btn-next" onclick="confirmStatus()">ยืนยัน</button>
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

            <div class="container">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>เรื่อง</th>
                            <th>วันที่</th>
                            <th>ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are any results
                        if ($result_service->num_rows > 0) {
                            $index = 1; // Initialize index for numbering
                            while ($row = $result_service->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $index++ . "</td>"; // Display index
                                echo "<td>" . htmlspecialchars($row['customer_name'] . ' ' . $row['customer_lastname']) . "</td>"; // Customer name
                                echo "<td>" . htmlspecialchars($row['head_sevice']) . "</td>"; // Service name
                                echo "<td>" . htmlspecialchars($row['date_service']) . "</td>"; // Service date
                                echo "<td class='edit'><button class='ck' onclick='viewmessenger(" . htmlspecialchars($row['id']) . ")'>View</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            // Display a message if no data is found
                            echo "<tr><td colspan='5'>ไม่พบข้อมูล</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- popup messenger end-->


    <!-- popup3 messenger view-->

    <div class="popup4" id="popup4">
        <div class="overlay4"></div>
        <div class="content4">
            <div class="close-btn" onclick="document.getElementById('popup4').classList.toggle('active')">&times;</div>
            <h1>ปัญหา</h1>
            <div class="container2">
                <div class="form-group">
                    <label for="name">ชื่อ:</label>
                    <input type="text" id="name" name="name" disabled>
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" disabled>
                </div>
                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์:</label>
                    <input type="tel" id="phone" name="phone" disabled>
                </div>
                <div class="form-group">
                    <label for="order">เลขออเดอร์:</label>
                    <input type="text" id="order" name="order" disabled>
                </div>
                <div class="form-group">
                    <label for="issue">หัวข้อปัญหา:</label>
                    <input type="text" id="issue" name="issue" disabled>
                </div>
                <div class="form-group">
                    <label for="description">ลักษณะของปัญหาที่พบ:</label>
                    <textarea id="description" name="description" rows="4" disabled></textarea>
                </div>
                <div class="form-group">
                    <label for="image1">แนบรูปภาพ 1:</label>
                    <img id="image1" src="" alt="">
                </div>
                <div class="form-group">
                    <label for="image2">แนบรูปภาพ 2:</label>
                    <img id="image2" src="" alt="">
                </div>
                <div class="form-group">
                    <label for="image3">แนบ QR Payment :</label>
                    <img id="image3" src="" alt="">
                </div>
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
        const statuses = ["รับออเดอร์", "ดำเนินการ", "สำเร็จ"];
        let currentStatusIndex = 0; // เริ่มต้นที่สถานะแรก
        let currentOrderRow; // Variable to keep track of the current order row

        // ฟังก์ชันสำหรับเปิด popup
        function Popup_Detail(sellId, rowElement) {
            currentOrderRow = rowElement; // เก็บแถวปัจจุบัน

            // เรียก Ajax เพื่อดึงข้อมูลตาม sell_id
            fetch('get_sell_details.php?sell_id=' + sellId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // แสดงข้อมูลที่ดึงมา
                        document.getElementById('orderNumber').innerText = data.sell_order || 'N/A';
                        document.getElementById('orderDate').innerText = data.sell_date || 'N/A';
                        document.getElementById('orderType').innerText = data.product_type || 'N/A';
                        document.getElementById('orderSize').innerText = data.sell_size || 'N/A';
                        document.getElementById('orderService').innerText = data.product_name || 'N/A';
                        document.getElementById('orderPrice').innerText = data.sell_total + ' บาท' || 'N/A';
                        document.getElementById('orderDistance').innerText = data.sell_distance + ' กม.' || 'N/A';
                        document.getElementById('shippingCost').innerText = '50 บาท';
                        document.getElementById('orderNotes').innerText = data.sell_note || 'ไม่มี';

                        // แสดงข้อมูลลูกค้า
                        document.getElementById('customerName').innerText = data.name + ' ' + data.lastname || 'N/A';
                        document.getElementById('customerPhone').innerText = data.phone || 'N/A';
                        document.getElementById('customerAddress').innerText = data.address || 'ไม่มีที่อยู่';
                        document.getElementById('totalPrice').innerText = (parseFloat(data.sell_total) + 50).toFixed(2) + ' บาท';

                        // จัดการค่าของร้าน
                        document.getElementById('inputShopId').value = data.shop_id || 'N/A'; // ตั้งค่า shop_id
                        document.getElementById('inputSellId').value = sellId; // ตั้งค่า sell_id

                        // แสดง popup
                        document.getElementById('popup-2').classList.add('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // ฟังก์ชันสำหรับยืนยันสถานะ
        function confirmStatus() {
            const statusCell = currentOrderRow.querySelector('.status'); // Get the status cell

            // Update the status in the order table
            statusCell.innerHTML = `<p>${statuses[currentStatusIndex]}</p>`; // Update displayed status
            statusCell.setAttribute('data-status-index', currentStatusIndex); // Update index

            // ปิด popup
            document.getElementById("popup-2").classList.remove("active");

            // Move to the next status if not at the last one
            if (currentStatusIndex < statuses.length - 1) {
                currentStatusIndex++;
            } else {
                alert("สถานะเสร็จสิ้นแล้ว."); // Alert if at the last status
            }

            // บันทึกข้อมูลสถานะลงฐานข้อมูล
            const sellId = document.getElementById('inputSellId').value;
            const shopId = document.getElementById('inputShopId').value;
            const ctId = 1; // ตั้งค่า ct_id ตามที่คุณต้องการ
            const productId = 1; // ตั้งค่า product_id ตามที่คุณต้องการ

            fetch('save_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        status: statuses[currentStatusIndex],
                        sell_id: sellId,
                        ct_id: ctId,
                        shop_id: shopId,
                        product_id: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("บันทึกสถานะเรียบร้อยแล้ว");
                    } else {
                        console.error("เกิดข้อผิดพลาดในการบันทึกสถานะ:", data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            updateProgress();
        }

        // ฟังก์ชันเพื่ออัปเดตการแสดงผล
        function updateProgress() {
            const progressSteps = document.querySelectorAll(".progress-step .bx");
            progressSteps.forEach((step, index) => {
                step.classList.toggle('active', index <= currentStatusIndex);
            });

            // คำนวณความกว้างสำหรับแถบโปรเกรส
            const width = ((currentStatusIndex + 1) / statuses.length) * 100;
            document.getElementById("progress").style.width = width + "%";
        }

        // ฟังก์ชันถัดไป
        const next = () => {
            // เพิ่มค่าสถานะ
            if (currentStatusIndex < statuses.length - 1) {
                currentStatusIndex++;
                updateProgress();
            }
        };

        /* popup */
        function messenger() {
            document.getElementById("popup3").classList.toggle("active");
        }

        /* popup3 */
        function viewmessenger(serviceId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_service_details.php?id=' + serviceId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const data = JSON.parse(this.responseText);
                    if (data.name) { // ตรวจสอบว่ามีข้อมูล
                        document.getElementById("name").value = data.name;
                        document.getElementById("email").value = data.email;
                        document.getElementById("phone").value = data.phone;
                        document.getElementById("order").value = data.order;
                        document.getElementById("issue").value = data.issue;
                        document.getElementById("description").value = data.description;

                        // แสดงภาพ
                        document.getElementById("image1").src = data.image1;
                        document.getElementById("image2").src = data.image2;
                        document.getElementById("image3").src = data.image3;

                        // แสดง popup
                        document.getElementById("popup4").classList.add("active");
                    } else {
                        alert('ไม่พบข้อมูล');
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>