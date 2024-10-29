<?php
session_start();
include('../inc/server.php');
include('../signin/upload.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}

$user_id = $_SESSION['user_id']; // รับค่า user_id จากเซสชัน

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

// ดึงข้อมูลสินค้าจาก tb_sell ที่มี ct_id ตรงกับ user_id
$query = "SELECT * FROM tb_sell WHERE ct_id = ?"; // ใช้ ct_id แทน user_id
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row; // เก็บข้อมูลสินค้าลงใน array
}

$stmt->close();
$conn->close();

// แสดงข้อความสำเร็จถ้ามี
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // เคลียร์ข้อความหลังจากแสดงแล้ว
}

// ตัวอย่างการแสดงผลข้อมูลผู้ใช้และสินค้าที่ดึงมา
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --orange: #ff7800;
            --black: #130f40;
            --white: #fff;
            --light-color: #666;
            --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
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

            .popup .content4 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 70px;
            }

            .popup .name-report label,
            .popup .name-report span p {
                font-size: 14px;
            }

            .popup .wrapper h3 {
                font-size: 15px;
            }

            .popup .btn-submit {
                width: 100%;
                padding: 8px 0;
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

            .popup .content4 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 50px;
            }

            .popup .name-report label,
            .popup .name-report span p {
                font-size: 14px;
            }

            .popup .wrapper h3 {
                font-size: 15px;
            }

            .popup .btn-submit {
                width: 100%;
                padding: 8px 0;
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
            .popup .content4 {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 40px;
            }

            .popup .name-report label,
            .popup .name-report span p {
                font-size: 14px;
            }

            .popup .wrapper h3 {
                font-size: 15px;
            }

            .popup .btn-submit {
                width: 100%;
                padding: 8px 0;
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

        .container {
            max-width: 100%;
            margin-top: 100px;
        }

        .container h1 {
            text-align: center;
            font-size: 25px;
            color: #507F99;
        }

        table {
            width: 99%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /*th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
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

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #507F99;
        }

        /*.content-table tbody tr.active-row {
            font-weight:bold;
            color:#507F99;
        }*/

        .active .at {
            background: #9FE2BF;
            padding: 2px 10px;
            display: inline-block;
            border-radius: 40px;
            color: #2b2b2b;
        }

        .bt4 {
            background: #9FE2BF;
            padding: 2px 10px;
            display: inline-block;
            border-radius: 40px;
            color: #2b2b2b;
        }

        .bt4:hover {
            background: #728FCE;
            padding: 2px 10px;
            display: inline-block;
            border-radius: 40px;
            color: #2b2b2b;
        }

        /* popup */

        .popup .overlay {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup .content4 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            height: 580px;
            z-index: 2;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        .popup .close-btn {
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

        .popup.active .overlay {
            display: block;
        }

        .popup.active .content4 {
            transition: all 300ms ease-in-out;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup .content4 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup .content4 .name-report {
            font-size: 15px;
            display: flex;
            justify-content: space-between;
            padding-bottom: 5px;
        }

        .popup .content4 .name-report p {
            font-size: 14px;
            color: #507F99;
        }

        /* comment */
        .wrapper {
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-items: center;*/
            text-align: center;
        }

        .wrapper h3 {
            font-size: 12px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .rating {
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: .5rem;
            font-size: 2rem;
            color: orange;
            margin-bottom: 2rem;
        }

        .rating .star {
            cursor: pointer;
        }

        .rating .star.active {
            opacity: 0;
            animation: animate .5s calc(var(--i) * .1s) ease-in-out forwards;
        }

        @keyframes animate {
            0% {
                opacity: 0;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .rating .star:hover {
            transform: scale(1.1);
        }


        textarea {
            width: 100%;
            background: #dddddd;
            padding: 1rem;
            border-radius: .5rem;
            border: none;
            outline: none;
            resize: none;
            margin-bottom: .5rem;
        }

        .btn-group {
            display: flex;
            grid-gap: .5rem;
            align-items: center;
            font-size: .875rem;
            float: right;
        }

        .btn-group .btn-submit {
            padding: .75rem 1rem;
            border-radius: .5rem;
            border: none;
            outline: none;
            cursor: pointer;
            background: #507F99;
            color: #ffff;

        }

        .btn-group .btn-submit:hover {
            background: #728FCE;
        }

        /* popup2 */

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
            transform: translate(-50%, -50%) scale(0);
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
            text-align: center;
            margin: auto;
        }

        .qr {
            margin: auto;
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
    </style>
    <title>history</title>
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

        <form action="" class="search-form">
            <input type="search" id="search-box" placeholder="Search here...">
            <label for="search-box" class="bx bx-search"></label>
        </form>


        <div class="shopping-cart">
            <div id="cart-items"></div> <!-- ส่วนนี้จะถูกอัปเดตเมื่อมีสินค้าถูกเพิ่มเข้ามา -->
            <div class="total"> Total : <span id="cart-total">0฿</span></div>
            <button class="ck" onclick="togglePopup()">Checkout</button>
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



    <!-- รายการ  -->

    <div class="container">
        <h1>ประวัติการทำรายการ</h1>

        <table class="content-table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ออเดอร์</th>
                    <th>ชื่อร้าน</th>
                    <th>รายการ</th>
                    <th>วันที่</th>
                    <th>ดูรายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($cartItems) > 0) {
                    $i = 1;
                    foreach ($cartItems as $item) {
                        echo "<tr>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . htmlspecialchars($item['sell_order']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['nameshop']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['sell_note']) . "</td>";
                        echo "<td>" . date('d-m-Y', strtotime($item['sell_date'])) . "</td>";
                        echo '<td><button class="bt4" onclick="Popup_Detail(' . $item['sell_id'] . ')">View</button></td>';
                        echo "</tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูลการสั่งซื้อ</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- รายการ  end-->


    <!-- popup-->

    <div class="popup" id="popup-1">
        <div class="overlay"></div>
        <div class="content4">
            <div class="close-btn" onclick="Popup()">&times;</div>
            <h1>รายการ</h1>

            <form action="review_update.php" method="post" name="form1" enctype="multipart/form-data">
                <input type="hidden" name="orderNumber" id="inputOrderNumber"> <!-- ฟิลด์ที่ซ่อน -->
                <input type="hidden" name="shop_id" id="inputShopId"> <!-- แสดง shop_id -->
                <input type="hidden" name="sell_id" id="inputSellId"> <!-- แสดง sell_id -->

                <div class="name-report">
                    <label for="text">เลขออเดอร์: </label><span id="orderNumber"></span>
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

                <div class="wrapper">
                    <h3>Review & Comment</h3>
                    <div class="rating">
                        <input type="number" name="review_stars" id="inputRating" hidden>
                        <i class='bx bx-star star' style="--i: 0;" onclick="setRating(1)"></i>
                        <i class='bx bx-star star' style="--i: 1;" onclick="setRating(2)"></i>
                        <i class='bx bx-star star' style="--i: 2;" onclick="setRating(3)"></i>
                        <i class='bx bx-star star' style="--i: 3;" onclick="setRating(4)"></i>
                        <i class='bx bx-star star' style="--i: 4;" onclick="setRating(5)"></i>
                    </div>
                    <textarea name="review_comment" cols="30" rows="3" placeholder="ความคิดเห็นของคุณ..."></textarea>
                    <div class="btn-group">
                        <button type="submit" class="btn-submit">บันทึก</button>
                    </div>
                </div>
            </form>
        </div>


    </div>
    </div>

    <!-- popup end-->

    <!-- popup-->

    <div class="popup2" id="popup-2">
        <div class="overlay1"></div>
        <div class="content5">
            <div class="close-btn" onclick="togglePopup()">&times;</div>

            <!-- หัวข้อแสดงคำว่า "รายการ" ที่ด้านบนสุด -->
            <h1 class="header-title">รายการ</h1>

            <!-- รายการสินค้า -->
            <div id="cart-items-container">
                <div class="cart-item">
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="item-info">
                        <h3>${item.name}</h3>
                        <span class="price">${item.price}฿</span>
                    </div>
                </div>
            </div>

            <!-- Total และ QR Code -->
            <div class="total-info">
                <!-- Total info will be injected here -->
            </div>
            <div class="qr-code-container" id="qr-code-container">
                <!-- QR code will be generated here -->
            </div>
        </div>
    </div>

    <!-- popup end-->

    <!--custom js file link -->
    <script src="js/script.js"></script>

    <script>
        let searchForm = document.querySelector('.search-form');
        let shoppingCart = document.querySelector('.shopping-cart');
        let profile = document.querySelector('.header .profile');
        let navbar = document.querySelector('.header .navbar');

        document.querySelector('#search-btn').onclick = () => {
            searchForm.classList.toggle('active');
            shoppingCart.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        }

        document.querySelector('#cart-btn').onclick = () => {
            shoppingCart.classList.toggle('active');
            searchForm.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        }

        document.querySelector('#user-btn').onclick = () => {
            profile.classList.toggle('active');
            searchForm.classList.remove('active');
            shoppingCart.classList.remove('active');
            navbar.classList.remove('active');
        }

        document.querySelector('#menu-btn').onclick = () => {
            navbar.classList.toggle('active');
            searchForm.classList.remove('active');
            shoppingCart.classList.remove('active');
            profile.classList.remove('active');
        }

        window.onscroll = () => {
            searchForm.classList.remove('active');
            shoppingCart.classList.remove('active');
            profile.classList.remove('active');
            navbar.classList.remove('active');
        }

        function Popup_Detail(sellId) {
            // เรียก Ajax เพื่อดึงข้อมูลตาม sell_id
            fetch('get_sell_details.php?sell_id=' + sellId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // แสดงข้อมูลที่ดึงมา
                        document.getElementById('orderNumber').innerText = data.sell_order || 'N/A';
                        // ตั้งค่าฟิลด์ที่ซ่อน
                        document.getElementById('inputOrderNumber').value = data.sell_order || ''; // ตั้งค่า orderNumber

                        document.getElementById('orderDate').innerText = data.sell_date || 'N/A';
                        document.getElementById('orderType').innerText = data.product_type || 'N/A';
                        document.getElementById('orderSize').innerText = data.sell_size || 'N/A';
                        document.getElementById('orderService').innerText = data.product_name || 'N/A';
                        document.getElementById('orderPrice').innerText = data.sell_total + ' บาท' || 'N/A';
                        document.getElementById('orderDistance').innerText = data.sell_distance + ' กม.' || 'N/A';
                        document.getElementById('shippingCost').innerText = '50 บาท';
                        document.getElementById('orderNotes').innerText = data.sell_note || 'ไม่มี';
                        document.getElementById('totalPrice').innerText = (parseFloat(data.sell_total) + 50).toFixed(2) + ' บาท';

                        // จัดการค่าของร้าน
                        document.getElementById('inputShopId').value = data.shop_id || 'N/A'; // ตั้งค่า shop_id
                        document.getElementById('inputSellId').value = sellId; // ตั้งค่า sell_id

                        // แสดง popup
                        document.getElementById('popup-1').classList.add('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function Popup() {
            document.getElementById("popup-1").classList.toggle("active");
        }

        const allStar = document.querySelectorAll('.rating .star')
        const ratingValue = document.querySelector('.rating input')

        allStar.forEach((item, idx) => {
            item.addEventListener('click', function() {
                let click = 0
                ratingValue.value = idx + 1
                console.log(ratingValue.value)

                allStar.forEach(i => {
                    i.classList.replace('bxs-star', 'bx-star')
                    i.classList.remove('active')
                })
                for (let i = 0; i < allStar.length; i++) {
                    if (i <= idx) {
                        allStar[i].classList.replace('bx-star', 'bxs-star')
                        allStar[i].classList.add('active')
                    } else {
                        allStar[i].style.setProperty('--i', click)
                        click++
                    }
                }
            })
        })

        // Function to toggle popup for checkout
        function togglePopup() {
            const popup = document.getElementById("popup-2");
            const shoppingCart = document.querySelector('.shopping-cart'); // เลือก shopping-cart
            const isActive = popup.classList.contains("active");

            // If the popup is currently active, close it
            if (isActive) {
                popup.classList.remove("active");
                return; // Exit the function
            }

            // Clear previous content
            const popupContent = document.querySelector('.content5');
            popupContent.innerHTML = ''; // Clear previous content in popup

            // If there are no items in the cart, notify the user
            if (userCart.length === 0) {
                popupContent.innerHTML = '<p>ไม่มีสินค้าในตะกร้า</p>'; // "No items in the cart"
                popup.classList.add("active"); // Show popup if cart is empty
                return;
            }

            let total = 0;
            const shippingFee = 50; // Assuming a static shipping fee

            // Add header title (แสดงคำว่า "รายการ" แค่ครั้งเดียว)
            popupContent.innerHTML += `<h1 class="header-title">รายการ</h1>`;

            // Generate checkout content
            userCart.forEach(item => {
                total += parseFloat(item.price);

                // Append item details to popup content
                popupContent.innerHTML += `
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

            // Add shipping fee and total to popup
            total += shippingFee; // Include shipping fee in total

            popupContent.innerHTML += `
    <div class="total-info">
        <p>ค่าจัดส่ง: <span id="shipping-fee">${shippingFee}฿</span></p>
        <p>รวม: <span id="total-price">${total}฿</span></p>
    </div>
    `;

            // Add QR code container below total
            popupContent.innerHTML += `
    <div class="qr-code-container" id="qr-code-container"></div>
    `;

            // Add close button at the top of the popup
            popupContent.innerHTML = `
        <div class="close-btn" onclick="togglePopup()">&times;</div>
        ${popupContent.innerHTML}  <!-- Insert previous content below the close button -->
    `;

            // Show popup
            popup.classList.add("active");

            // Hide the shopping cart when the popup is shown
            shoppingCart.classList.remove('active'); // ซ่อน shopping-cart

            // Generate QR Code for the total price
            const qrData = "test qr code"; // ข้อมูลใน QR code (เช่น จำนวนเงินที่ต้องชำระ)
            console.log("QR Data: ", qrData); // Log QR Data

            try {
                // Generate QR Code
                const qrCodeContainer = document.getElementById("qr-code-container");
                qrCodeContainer.innerHTML = ''; // Clear previous QR Code
                new QRCode(qrCodeContainer, {
                    text: qrData,
                    width: 128,
                    height: 128
                });
            } catch (error) {
                console.error("Error generating QR Code:", error);
                alert("เกิดข้อผิดพลาดในการสร้าง QR Code กรุณาลองใหม่อีกครั้ง");
            }
        }

        // Function to remove item from cart
        function removeFromCart(index) {
            userCart.splice(index, 1); // Remove item at index
            updateCart(userCart); // Update cart display
        }

        // Retrieve data from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || {};
            let user_id = <?php echo json_encode($user_id); ?>; // Retrieve user_id from PHP
            userCart = cartItems[user_id] || []; // If there's no cart data, make it an empty array

            // Update the display
            updateCart(userCart);
        });

        function updateCart(cartItems) {
            const cartItemsContainer = document.getElementById('cart-items');
            let cartHTML = '';
            let total = 0;

            cartItems.forEach((item, index) => {
                cartHTML += `
                <div class="box">
                    <i class='bx bx-trash' onclick="removeFromCart(${index})"></i>
                    <img src="${item.image}" alt="${item.name}">
                    <div class="content">
                        <h3>${item.name}</h3>
                        <span class="price">${item.price}฿</span>
                    </div>
                </div>
            `;
                total += parseFloat(item.price);
            });

            cartItemsContainer.innerHTML = cartHTML;
            document.getElementById('cart-total').textContent = total + '฿';
        }

        // Show cart data when the page loads
        updateCart(userCart);
    </script>

    <script>
        function setRating(rating) {
            document.getElementById('inputRating').value = rating;
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                star.classList.toggle('active', index < rating);
            });
        }

        function clearForm() {
            document.getElementById('inputRating').value = ''; // เคลียร์ดาว
            document.querySelector('textarea[name="review_comment"]').value = ''; // เคลียร์คอมเมนต์
        }

        // เรียกใช้ clearForm() เมื่อต้องการเคลียร์ฟอร์ม
    </script>




</body>

</html>