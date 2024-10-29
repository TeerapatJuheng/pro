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
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0;
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

        /* Card-condition */
        /* Card-condition */
        .card-condition {
            background-color: #f9f9f9;
            /* สีพื้นหลัง */
            border-radius: 8px;
            /* มุมมน */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* เงา */
            margin: 20px;
            /* ระยะห่างรอบๆ */
            padding: 20px;
            /* ช่องว่างภายใน */
            max-width: 800px;
            /* ความกว้างสูงสุด */
            font-family: 'Arial', sans-serif;
            /* ฟอนต์ */
        }

        .card-body {
            line-height: 1.6;
            /* ระยะห่างระหว่างบรรทัด */
        }

        .heading {
            font-size: 24px;
            /* ขนาดฟอนต์หัวเรื่อง */
            color: #333;
            /* สีข้อความ */
            margin-bottom: 20px;
            /* ระยะห่างด้านล่าง */
        }

        h6 {
            font-size: 18px;
            /* ขนาดฟอนต์หัวข้อย่อย */
            color: #555;
            /* สีข้อความหัวข้อย่อย */
            margin-top: 15px;
            /* ระยะห่างด้านบน */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่าง */
        }

        p {
            margin-bottom: 10px;
            /* ระยะห่างด้านล่าง */
        }

        ul {
            margin: 0;
            /* ลบระยะห่างด้านนอก */
            padding-left: 20px;
            /* ระยะห่างด้านซ้าย */
        }

        li {
            margin-bottom: 5px;
            /* ระยะห่างระหว่างรายการ */
            color: #444;
            /* สีข้อความในรายการ */
        }


        @media (max-width:991px) {
            html {
                font-size: 55%;
            }

            .header {
                padding: 2rem;
            }

            section {
                padding: 2rem;
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

        }

        @media (max-while:450px) {
            html {
                font-size: 50%;
            }
        }

        .card-condition {
            margin: 2rem 2rem;
            font-size: 15px;
        }

        .card-condition h6 {
            font-size: 16px;
            padding: 5px;
        }

        .card-condition h1 {
            text-align: center;
            margin-top: 100px;
            color: #507F99;
        }

        .btn-condition {
            font-size: 15px;
            margin-left: 2rem;
        }

        .ser2,
        .ser3 {
            color: #507F99;
        }
    </style>
    <title>Sevice shop</title>
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


    <!-- นโยบาย card-->
    <div class="card-condition">
        <div class="card-body">
            <h1 class="heading">นโยบายความเป็นส่วนตัวของ <span>ผู้ใช้บริการ</span></h1>

            <h6>1. ข้อมูลที่เราเก็บรวบรวม</h6>
            <p>- <strong>ข้อมูลส่วนบุคคล</strong>: ชื่อ, อีเมล, หมายเลขโทรศัพท์, ที่อยู่</p>
            <p>- <strong>ข้อมูลการใช้งาน</strong>: ข้อมูลเกี่ยวกับการเข้าถึงและการใช้งานเว็บแอปพลิเคชัน เช่น เวลาเข้าชม, หน้าที่เข้าชม, และประเภทของอุปกรณ์ที่ใช้</p>

            <h6>2. การใช้ข้อมูลส่วนบุคคล</h6>
            <p>เราใช้ข้อมูลที่เก็บรวบรวมเพื่อ:</p>
            <ul>
                <li>ให้บริการและดำเนินการตามคำสั่งซักผ้าของผู้ใช้</li>
                <li>ติดต่อผู้ใช้เกี่ยวกับบริการและโปรโมชั่น</li>
                <li>ปรับปรุงและพัฒนาเว็บแอปพลิเคชัน</li>
            </ul>

            <h6>3. การเปิดเผยข้อมูล</h6>
            <p>- เราจะไม่เปิดเผยข้อมูลส่วนบุคคลของผู้ใช้ต่อบุคคลที่สาม เว้นแต่จะได้รับความยินยอมจากผู้ใช้หรือเมื่อกฎหมายกำหนด</p>
            <p>- ข้อมูลอาจถูกเปิดเผยในกรณีที่จำเป็นเพื่อปฏิบัติตามกฎหมายหรือเพื่อป้องกันการฉ้อโกง</p>

            <h6>4. การรักษาความปลอดภัย</h6>
            <p>เราใช้มาตรการด้านความปลอดภัยที่เหมาะสมเพื่อปกป้องข้อมูลส่วนบุคคลจากการเข้าถึงที่ไม่ได้รับอนุญาต การใช้หรือการเปิดเผยข้อมูล</p>

            <h6>5. สิทธิของผู้ใช้</h6>
            <p>ผู้ใช้มีสิทธิในการ:</p>
            <ul>
                <li>เข้าถึงและขอสำเนาข้อมูลส่วนบุคคลที่เรามี</li>
                <li>ขอให้แก้ไขข้อมูลที่ไม่ถูกต้อง</li>
                <li>ขอให้ลบข้อมูลส่วนบุคคลในกรณีที่ไม่จำเป็นในการให้บริการ</li>
            </ul>

            <h6>6. การเปลี่ยนแปลงนโยบาย</h6>
            <p>- เราขอสงวนสิทธิ์ในการเปลี่ยนแปลงนโยบายความเป็นส่วนตัวนี้โดยไม่ต้องแจ้งให้ทราบล่วงหน้า</p>
            <p>- ผู้ใช้ควรตรวจสอบนโยบายนี้เป็นประจำเพื่อรับข้อมูลเกี่ยวกับการปรับปรุง</p>

            <h6>7. การติดต่อ</h6>
            <p>หากมีข้อสงสัยเกี่ยวกับนโยบายความเป็นส่วนตัวนี้ ผู้ใช้สามารถติดต่อเราผ่านช่องทางที่ระบุในเว็บแอปพลิเคชัน</p>

            <p>การใช้บริการจากร้านฝากซักผ้าของเราถือว่าผู้ใช้ได้ยอมรับนโยบายความเป็นส่วนตัวนี้แล้ว</p>
        </div>
    </div>

    <div class="btn-condition">
        <a href="sevice2_shop.php" class="ser2">ข้อตกลงการใช้บริการ</a> <br>
        <a href="sevice3_shop.php" class="ser3">นโยบายความเป็นส่วนตัว</a>
    </div>

    <!-- นโยบาย card end-->












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
    </script>
</body>

</html>