<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');
include('../signin/upload.php');


// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo "คุณยังไม่ได้เข้าสู่ระบบ";
    exit;
}

$user_id = $_SESSION['user_id']; // รับค่า customer_id จากเซสชัน

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

$stmt->close();
$conn->close();
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
        --box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
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
            background:#507F99;
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
            background:#507F99;
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

        .header .shopping-cart .btn{
            display: block;
        }
        .ck {
            display: inline-block;
            margin: auto;
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
            background:#507F99;
            color: #fff;
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
            text-align:center;
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

        @media (max-width:991px){
            html{
                font-size: 55%;
            }

            .header{
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
            .header .search-form{
                width: 90%;
            }
            
            #menu-btn {
                display: inline-block;
            }

            .header .navbar {
                position: absolute;
                top: 100%; right: -110%;
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

        @media (max-while:450px){
            html{
                font-size:50%;
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
            margin:20px auto;
            max-width:700px;
        }

        .container .heading{
            text-align:center;
            font-size:30px;
            padding: 20px;
            margin-bottom:20px;
            margin-top:90px;
        }

        .container .accordion-container {
    padding: 0 20px;
}

.container .accordion-container .accordion {
    margin-top: 20px;
    cursor: pointer;
}

.container .accordion-container .accordion.active .accordion-heading {
    background: #507F99;
    transition: background 0.3s ease;
}

.container .accordion-container .accordion.active .accordion-heading h3 {
    color: #fff;
    transition: color 0.3s ease;
}

.container .accordion-container .accordion.active .accordion-heading i {
    color: #fff;
    transform: rotate(180deg);
    transition: transform 0.3s ease, color 0.3s ease;
}

.container .accordion-container .accordion.active .accordion-content {
    display: block;
    max-height: 500px; /* ปรับ max-height เพื่อให้แสดงผลแบบ smooth */
    overflow: hidden;
    transition: max-height 0.5s ease;
    animation: animate 0.4s linear backwards; /* ใส่ animation กลับไป */
}

.container .accordion-container .accordion .accordion-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #fff;
    border: 1px solid #ccc; /* ปรับสี border ให้อ่อนลง */
    padding: 15px 20px;
    border-radius: 1rem;
    transition: background 0.3s ease, border 0.3s ease;
}

.container .accordion-container .accordion .accordion-heading h3 {
    font-size: 16px; /* ปรับขนาดเพื่ออ่านง่าย */
}

.container .accordion-container .accordion .accordion-heading i {
    font-size: 20px;
    transition: transform 0.3s ease;
}

.container .accordion-container .accordion .accordion-content {
    padding: 15px 20px;
    border: 1px solid #ccc; /* ปรับสี border ให้อ่อนลง */
    font-size: 14px; /* เพิ่มขนาด font */
    background: #f9f9f9; /* สีพื้นหลังให้อ่อนลง */
    border-top: 0;
    max-height: 0; /* เริ่มต้นที่ max-height 0 เพื่อซ่อน */
    overflow: hidden;
    transition: max-height 0.5s ease; /* ใช้ transition */
    line-height: 1.8; /* ปรับระยะห่างระหว่างบรรทัด */
    transform-origin: top;
    border-radius: 0 0 1rem 1rem;
}

@keyframes animate {
    0% {
        transform: scale(0);
    }
    100% {
        transform: scale(1);
    }
}



        .container2 {
            max-width:700px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: block;
            align-items: center;
            justify-content:center ;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
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

        button {
            background-color: #507F99;
            color: white;
            padding: 5px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom:10px;
            margin-top: -17px;
            text-align: center;
            float: right;
        }

        button:hover {
            background-color: #507F90;
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
            height: 100vh; /* Ensure button is centered vertically */
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
        }

           
        /* รายการในตะกร้า */
#cart-items-container {
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 20px;
    padding-right: 10px; /* เพิ่ม Padding เพื่อหลีกเลี่ยงการชนของ Scrollbar */
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
    <title>FQAs</title>
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

    <!-- FAQ section-->

    <div class="container">
        <h1 class="heading">Frequently Asked Questions</h1>

        <div class="accordion-container">

            <div class="accordion active">
                <div class="accordion-heading">
                    <h3>Q: ต้องเตรียมผงซักผ้าหรือไม่?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    หากลูกค้ามีผงซักฟอก น้ำยาซักผ้า น้ำยาปรับผ้านุ่ม ที่ใช้กลิ่นและแบบที่ลูกค้าต้องการสามารถใส่ไว้ในตระกร้าผ้าได้เลย เพื่อให้ทางร้านผู้ให้บริการนำไปใช้ หากไม่มี จะใช้ผงซักฟอก น้ำยาซัก น้ำยาปรับผ้านุ่มของทางร้านผู้ให้บริการ
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: ใช้งานเว็บไซต์อย่างไร?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    หลังจากสมัครสมาชิก ผู้ใช้สามารถเลือกดูร้านค้าผู้ให้บริการที่ต้องการใช้ได้ในหน้า Dashboard และหน้า Shop หลังจากได้ร้านที่ต้องการลูกค้าทำการเลือกประเภท และขนาดของผ้า จากนั้นเลือกบริการที่ต้องการ แล้วกดยืนยันเพื่อใส่ตระกร้าร้านค้า จากนั้นกดชำระเงินแล้วทำการแสกนจ่าย QR เสร็จแล้วรอทางร้านมารับผ้า และรอแจ้งเตือนจากทางร้าน
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: อบผ้าดีอย่างไร?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    ผ้าแห้งสนิทไม่ต้องตาก ไม่ต้องรอเก็บผ้า ผ้าไม่เหม็นอับ กลิ่นของน้ำยาปรับผ้านุ่มคงอยู่กับผ้าได้ดีกว่าการตากผ้า เลี่ยงลดความเสี่ยงในการเจอมลพิษขณะตากผ้า เช่น ฝุ่นละออง แมลงกินผ้า ควันต่างๆ ช่วยกำจัดฝุ่นผ้า กำจัดไรฝุ่น ฆ่าเชื้อได้ ประหยัดเวลา ในการตากต้องใช้เวลาทั้งวันในการรอผ้าแห้ง ผ้าแห้งสนิท พับเก็บเข้าตู้ได้เลย
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: คิดราคาอย่างไร?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    ราคาจะขึ้นอยู่กับร้านค้าผู้ให้บริการ และบริการที่ผู้ใช้บริการเลือก ปกติเริ่มต้น 50 บาท
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: หากผ้าเกิดสีตก กระดุมหลุด ทางร้านรับผิดชอบไหม?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    ทางร้านไม่รับผิดชอบในกรณีดังกล่าว ผู้ใช้สามารถอ่านเงื่อนไขการใช้บริการได้ใน Condition บอกการใช้บริการ และเงื่อนไขการรับผิดชอบต่างๆ
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: หากหลังการซักเกิดผ้าเสียหายจนไม่สามารถใช้งานต่อได้ ต้องทำอย่างไร?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    หลังผู้ใช้บริการได้รับผ้าแล้วตรวจเจอความเสียหายตามเงื่อนไขที่รับผิดชอบ ให้ผู้ใช้บริการกรอกแบบฟอร์มแจ้งปัญหามาทางเว็บไซต์ และหากผ่านการตรวจสอบถูกต้อง ทางร้านค้าผู้ให้บริการจะรับผิดชอบค่าเสียหาย 10 เท่าจากค่าบริการเท่านั้น
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: หากมีปัญหาสามารถติดต่อช่างทางใดได้บ้าง?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    สามารถติดต่อได้ 2 ช่องทาง 1.ทางแบบฟอร์มแจ้งปัญหา 2.Line Offcial ID:@armcorp 
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: หากอยากซักตุ๊กตา หรือผ้าอื่นๆสามารถซักได้หรือไม่?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    สามารถซักได้หากทางร้านผู้ให้บริการใส่ไว้ในประเภทที่ต้องซัก 
                </p>
            </div>

            <div class="accordion">
                <div class="accordion-heading">
                    <h3>Q: ชำระค่าบริการช่องทางใดบ้าง?</h3>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <p class="accordion-content">
                    ชำระค่าบริการได้ทางเดียวคือ QR พร้อมเพย์ เท่านั้น
                </p>
            </div>

        </div>
    </div>

    <!-- FAQ section end-->

    <!--form แจ้งปัญหา-->
        <div class="container2">
            <!--<h2>แบบฟอร์มการแจ้งปัญหา</h2>-->
            <button onclick="toggleForm()">แบบฟอร์มแจ้งปัญหา</button>
            <form id="issueForm" action="#" style="display: none;" enctype="multipart/form-data">
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
                    <label for="issue">เลขออเดอร์:</label>
                    <input type="text" id="issue" name="issue" required>
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
                    <input type="file" id="image1" name="image1" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="image2">แนบรูปภาพ 2:</label>
                    <input type="file" id="image2" name="image2" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="image3">แนบ OR Payment :</label>
                    <input type="file" id="image3" name="image3" accept="image/*">
                </div>
                <div class="form-group">
                    <button type="submit">ส่งแบบฟอร์ม</button>
                </div>
            </form>
        </div>
     
    <!--form end-->

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
let accordions = document.querySelectorAll('.accordion-container .accordion');

// ซ่อนเนื้อหาทั้งหมดตอนเริ่มต้น
accordions.forEach(acco => {
    const content = acco.querySelector('.accordion-content');
    content.style.display = 'none';  // ซ่อนทุก accordion content ตั้งแต่ต้น
    
    acco.querySelector('.accordion-heading').onclick = () => {
        // Toggle active class for the clicked accordion
        acco.classList.toggle('active');
        
        // Toggle display of content
        if (acco.classList.contains('active')) {
            content.style.display = 'block';
        } else {
            content.style.display = 'none';
        }

        // Close other accordions if needed
        accordions.forEach(subAcco => {
            if (subAcco !== acco) {
                subAcco.classList.remove('active');
                subAcco.querySelector('.accordion-content').style.display = 'none';  // ซ่อน accordion อื่น ๆ
            }
        });
    };
});

document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    shoppingCart.classList.remove('active');
    profile.classList.remove('active');
    navbar.classList.remove('active');
};

document.querySelector('#cart-btn').onclick = () => {
    shoppingCart.classList.toggle('active');
    searchForm.classList.remove('active');
    profile.classList.remove('active');
    navbar.classList.remove('active');
};

document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    navbar.classList.remove('active');
};

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    profile.classList.remove('active');
};

window.onscroll = () => {
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    profile.classList.remove('active');
    navbar.classList.remove('active');
};

function toggleForm() {
    var form = document.getElementById('issueForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}



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
</body>
</html>