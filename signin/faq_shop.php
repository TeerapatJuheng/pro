<?php
session_start();
include('../inc/server.php');

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
            
        }

        @media (max-while:450px){
            html{
                font-size:50%;
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
            margin-top:20px;
            cursor:pointer;
        }

        .container .accordion-container .accordion.active .accordion-heading{
            background:#507F99;
        }

        .container .accordion-container .accordion.active .accordion-heading h3{
            color:#fff;
        }

        .container .accordion-container .accordion.active .accordion-heading i{
            color:#fff;
            transform:rotate(180deg);
            transition:transform .2s .1s;
        }

        .container .accordion-container .accordion.active .accordion-content{
            display: block;
        }

        .container .accordion-container .accordion .accordion-heading {
            display: flex;
            align-items:center;
            justify-content: space-between;
            gap:10px;
            background:#fff;
            border:.5px solid #000;
            padding: 15px 20px;
            border-radius:1rem;

        }

        .container .accordion-container .accordion .accordion-heading h3 {
            font-size:15px;
        }

        .container .accordion-container .accordion .accordion-heading i {
            font-size:25px;
        }

        .container .accordion-container .accordion .accordion-content {
            padding: 15px 20px;
            border:.5px solid #000;
            font-size:13px;
            background:#fff;
            border-top:0;
            display: none;
            animation:animate .4s linear backwards;
            line-height:2;
            transform-origin:top;
            border-radius:1rem;

        }

        @keyframes animate {
            0% {
                transform:scale(0);
            }
        }

        
    
    </style>
    <title>Dashboard</title>
</head>

    <body>

        <!-- header section starts -->
        <header class="header">
        <a href="dashboard_shop.php" class="logo">Laundry</a>
        
        <nav class="navbar">
            <a href="dashboard_shop.php">Dashboard</a>
            <a href="store_shop.php">Shop</a>
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

        <!-- FAQ section-->

        <div class="container">
            <h1 class="heading">Frequently Asked Questions</h1>

            <div class="accordion-container">

                <div class="accordion active">
                    <div class="accordion-heading">
                        <h3>Q: คนไปรับผ้าต้องมีเฉพาะไหม?</h3>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <p class="accordion-content">
                        ไม่จำเป็น ทางร้านจะให้พนักงาน หรือเจ้าของร้านจะเป็นคนไปรับผ้าก็ได้ หรือหากทางร้านมีพนักงานเฉพาะที่ไปรับผ้าอยู่แล้วก็สามารถใช้ได้
                    </p>
                </div>

                <div class="accordion">
                    <div class="accordion-heading">
                        <h3>Q: ใช้งานเว็บไซต์อย่างไร?</h3>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <p class="accordion-content">
                        หลังจากสมัครสมาชิก ผู้ใช้บริการเข้าไปที่หน้า Setting เพื่อตั้งค่ารายละเอียดของร้าน รูปภาพ เวลาทำการ และบัญชีธนาคาร จากนั้นไปหน้า Shop เพื่อ Add บริการที่ทางร้านให้บริการ เช่น <br>ชื่อบริการ : ซัก อบแห้ง <br>รายละเอียดบริการ : บริการซักผ้า และอบแห้งอย่างมาตรฐาน หอม สะอาด <br>ประเภทผ้า : เสื้อผ้า ผ้าห่ม ชุดเครื่องนอน <br>ราคาบริการ : 80฿ เป็นต้น
                    </p>
                </div>

                <div class="accordion">
                    <div class="accordion-heading">
                        <h3>Q: จะได้เงินหลังให้บริการลูกค้าอย่างไร?</h3>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <p class="accordion-content">
                        ระบบเว็บไซต์จะโอนยอดเงินให้ทางร้านหลังหักค่าบริการแล้ว ทุก 7 วัน 
                    </p>
                </div>

                <div class="accordion">
                    <div class="accordion-heading">
                        <h3>Q: คิดค่า GP เท่าไหร่?</h3>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <p class="accordion-content">
                        ทางเว็บไซต์หัก GP 5% จากยอดขาย
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
            
            </div>
        </div>

    <!-- FAQ section end-->
   
    


    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!--custom js file link -->
        <script src="js/script.js"></script>

        <script>
            let searchForm = document.querySelector('.search-form');
            let profile = document.querySelector('.header .profile');
            let navbar = document.querySelector('.header .navbar');
            let accordions = document.querySelectorAll('.accordion-container .accordion');

            accordions.forEach(acco =>{
            acco.onclick = () =>{
                accordions.forEach(subAcco => { subAcco.classList.remove('active') });
                acco.classList.add('active');
            }
        })

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

            function toggleForm() {
            var form = document.getElementById('issueForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }


        </script>
    </body>
</html>
