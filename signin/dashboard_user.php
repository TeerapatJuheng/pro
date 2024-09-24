<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');
// include('../signin/upload.php');


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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <style>
        :root {
        --orange: #ff7800;
        --black: #130f40;
        --white: #fff;
        --light-color: #666;
        --box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
        --border:.2rem solid rgba(0,0,0,.1);
        --outline:.1rem solid rgba(0,0,0,.1);
        --outline-hover:.2rem solid var(--black);
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
            background: var(--white) ;
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

            section {
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

        @media (max-width: 450px) {
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
            .popup2.active{
                width: 100%;
                height: 120%;
            }
        }
        .stars {
            padding-top:12rem ;
            /*padding-bottom:2rem ;*/
        }

        .stars3 {
            font-size:2rem;
            color:#FFBF00;
        }

        .stars i {
            font-size:2rem;
            color:#FFBF00;
        }

        .products .product-slider {
            padding: 1rem;
        }

        .products .product-slider:first-child {
            margin-bottom:2rem;
        }

        .products .product-slider .box {
            background:#fff;
            border-radius:.5rem;
            text-align:center;
            padding: 3rem 2rem;
            outline-offset:-1rem;
            outline:var(--outline);
            box-shadow:var(--box-shadow);
            transition: .2s linear;
        }

        .products .product-slider .box:hover {
            outline-offset:0rem;
            outline:var(--outline-hover);
        }

        .products .product-slider .box img {
            height: 20rem;
        }

        .products .product-slider .box h3 {
            font-size:2.5rem;
            color:var(--black);
        }

        .products .product-slider .box .gps {
            font-size:2rem;
            color:var(--light-color);
            padding: .5rem 0;
        }

        /*.products .product-slider .box .starss i {
            font-size:1.7rem;
            color:var(--orange);
            padding:0.5rem 0 ;
        }*/


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
            background: rgba(0, 0, 0, 0.4);
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

        
        
    </style>
    <title>Dashboard</title>
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
            <div class="box">
                <i class='bx bx-trash'></i>
                <img src="../photo/ตู้ซักผ้า.jpg" alt="">
                <div class="content">
                    <h3>ซัก อบ </h3>
                    <span class="price">50฿</span>
                </div>
            </div>
            <div class="box">
                <i class='bx bx-trash'></i>
                <img src="../photo/ตู้ซักผ้า.jpg" alt="">
                <div class="content">
                    <h3>ซัก อบ </h3>
                    <span class="price">50฿</span>
                </div>
            </div>
            <div class="box">
                <i class='bx bx-trash'></i>
                <img src="../photo/ตู้ซักผ้า.jpg" alt="">
                <div class="content">
                    <h3>ซัก อบ </h3>
                    <span class="price">50฿</span>
                </div>
            </div>
            <div class="total"> Total : 150฿</div>
            <button class="ck" onclick="togglePopup()">Checkout</button>
        </div>


        <div class="profile">
            <img src="../photo/1x/รีด.png" alt="">
            <h3>kkkkkkk oooooooo</h3>
            <span>User</span>
            <a href="profile_user.php" class="btnp">Profile</a>
            <div class="flex-btnp">
                <a href="history.php" class="option-btnp">ประวัติ</a>
                <a href="login.php" class="option-btnp">Logout</a>
            </div>
        </div>
        </header> 

   
    <!-- ดาว -->

    <section class="stars" id="stars">
        <i class='bx bxs-star'></i>
        <i class='bx bxs-star'></i>
        <i class='bx bxs-star'></i>
        <i class='bx bxs-star'></i>
        <i class='bx bxs-star'></i>

    </section>
    
    <!-- ดาว end-->

    <!-- section ของตัวเลือกที่ต้องการดู-->

    <section class="products" id="products">

        <div class="swiper product-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                    <img src="../photo/1x/ผ้า2.png" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="shop2_user.php" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="shop2_user.php" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="shop2_user.php" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="shop2_user.php" class="btn">เลือก</a>
                </div>
            
            </div>

        </div>

        

    </section>
        

    <!-- card ของตัวเลือกที่ต้องการดู end-->

    <!-- ดาว -->

    <section class="stars3" id="stars">
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
    </section>

    <!-- ดาว end-->
    
    <!-- section ของตัวเลือกที่ต้องการดู-->

    <section class="products" id="products">

        <div class="swiper product-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>
            
            </div>

        </div>

    </section>
        

    <!-- section ของตัวเลือกที่ต้องการดู end-->

    <!-- ดาว -->

    <section class="stars3" id="stars">
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
    </section>

    <!-- ดาว end-->

    <!-- section ของตัวเลือกที่ต้องการดู-->

    <section class="products" id="products">

        <div class="swiper product-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>
            
            </div>

        </div>

    </section>
        

    <!-- section ของตัวเลือกที่ต้องการดู end-->

    <!-- ดาว -->

    <section class="stars3" id="stars">
            <i class='bx bxs-star'></i>
            <i class='bx bxs-star'></i>
    </section>

    <!-- ดาว end-->

    <!-- section ของตัวเลือกที่ต้องการดู-->

    <section class="products" id="products">

        <div class="swiper product-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>
            
            </div>

        </div>

    </section>
        

    <!-- section ของตัวเลือกที่ต้องการดู end-->

    <!-- ดาว -->

    <section class="stars3" id="stars">
            <i class='bx bxs-star'></i>
    </section>

    <!-- ดาว end-->

    <!-- section ของตัวเลือกที่ต้องการดู-->

    <section class="products" id="products">

        <div class="swiper product-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>

                <div class="swiper-slide box">
                    <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    <h3>ชื่อร้าน</h3>
                    <div class="gps">พิกัด</div>
                    <a href="#" class="btn">เลือก</a>
                </div>
            
            </div>

        </div>

    </section>
        

    <!-- section ของตัวเลือกที่ต้องการดู end-->


        
    <!-- popup-->

    <div class="popup2" id="popup-2"> 
        <div class="overlay1"></div>
        <div class="content5">
            <div class="close-btn" onclick="togglePopup()">&times;</div>
            <h1>รายการ</h1>
            <div class="name-report1">
                <label for="text">ชื่อร้าน : </label>
                <span><p>ซักรีด1</p></span>
            </div>
            <div class="name-report1">
                <label for="text">หมายเหตุ : </label>
                <span><p></p></span>
            </div>
            <div class="name-report1">
                <label for="text">ประเภท : </label>
                <span><p>เสื้อผ้า</p></span>
            </div>
            <div class="name-report1">
                <label for="text">ขนาด : </label>
                <span><p> M </p></span>
            </div>
            <div class="name-report1">
                <label for="text">บริการ : </label>
                <span><p>ซัก อบแห้ง</p></span>
            </div>
            <div class="name-report1">
                <label for="text">ราคา : </label>
                <span><p>80 บาท</p></span>
            </div>
            <div class="name-report1">
                <label for="text">ระยะทาง : </label>
                <span><p>5 Km.</p></span>
            </div>
            <div class="name-report1">
                <label for="text">ค่าขนส่ง : </label>
                <span><p>50 บาท</p></span>
            </div>
            
            <div class="name-report1">
                <label for="text">ราคารวม : </label>
                <span><p>130 บาท</p></span>
            </div>
            <div class="name-report1">
                <label for="text" class="qr">แสกน QR ชำระเงิน</label>
            </div>
            <div class="name-report1">
                <img src="../photo/ตู้ซักผ้า2.jpg" alt="" for="qr">
            </div>
        </div>
    </div>

    <!-- popup end-->









    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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

            document.querySelector('#cart-btn').onclick = () => {
                shoppingCart.classList.toggle('active');
                searchForm.classList.remove('active');
                profile.classList.remove('active');
                navbar.classList.remove('active');
            }

            window.onscroll = () => {
                searchForm.classList.remove('active');
                shoppingCart.classList.remove('active');
                profile.classList.remove('active');
                navbar.classList.remove('active');
            }


        var swiper = new Swiper(".product-slider", {
            loop:true,
            spaceBetween: 20,
            autoplay: {
                delay:7500,
                disableOnInteraction:false,
            },
            centeredSlides:true,
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
            },
        });

        

        /* popup */
        function togglePopup() {
            document.getElementById("popup-2").classList.toggle("active");
            document.querySelector('.shopping-cart').classList.remove('active');
        }


        </script>
    </body>
</html>
