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
                width: 250px;
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

        .heading{
            font-size:2.5rem;
            margin-bottom:10px;
        }

        .card-condition{
            margin: 2rem 2rem;
            font-size:15px;
        }

        .card-condition h6{
            font-size:16px;
            padding: 5px;
        }

        .card-condition h1{
            text-align: center;
            margin-top:100px;
            color:#000;
        }

        .btn-condition{
            font-size:15px;
            margin-left:2rem;
        }

        .ser2, .ser3{
            color:#507F99;
        }

        .p2{
            margin-left:2rem;
            margin-bottom:1rem;
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
    <title>sevice</title>
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
                <span class="quantity">qty : 1 </span>
            </div>
        </div>
        <div class="box">
            <i class='bx bx-trash'></i>
            <img src="../photo/ตู้ซักผ้า.jpg" alt="">
            <div class="content">
                <h3>ซัก อบ </h3>
                <span class="price">50฿</span>
                <span class="quantity">qty : 1 </span>
            </div>
        </div>
        <div class="box">
            <i class='bx bx-trash'></i>
            <img src="../photo/ตู้ซักผ้า.jpg" alt="">
            <div class="content">
                <h3>ซัก อบ </h3>
                <span class="price">50฿</span>
                <span class="quantity">qty : 1 </span>
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

    <!-- นโยบาย card-->
    <div class="card-condition">
        <div class="card-body">
        <h1 class="heading">ข้อตกลงใน <span>การใช้บริการ</span></h1>
            <!--<h6>การบริการ</h6>-->
            <p>ข้อตกลงในการใช้บริการฉบับนี้ ("ข้อตกลง") ทำขึ้นระหว่าง บริษัท อาร์ม คอร์ปอเรชั่น จํากัด  ("บริษัท") กับท่านในฐานะผู้ใช้บริการ ("ผู้ใช้บริการ") </p>
            <p>(1) บริษัทให้บริการในการเป็นตัวกลางระหว่างผู้ใช้บริการ(ลูกค้า) และผู้ให้บริการ(ร้านค้า) ในการซักผ้า อบแห้ง และสิ่งอำนวยความสะดวกอื่น ที่ผู้ให้บริการมีไว้ให้บริการ</p>
            <p>(2)การเยี่ยมชมและใช้บริการเว็บไซต์ ของบริษัท ภายใต้ชื่อ Laundry รวมถึงการใช้บริการการชำระเงิน และการใช้บริการอื่นใดผ่านเว็บไซต์ที่บริษัทได้พัฒนาขึ้น เพื่อสนับสนุนการใช้บริการหลักของบริษัท("เว็บไซต์") </p>
            <p>(3) การใช้บริการติดต่อประสานงานสนับสนุนอื่นผ่าน  Line Official Account และ Facebookของบริษัท (บริการทั้งหมดเรี่ยกรวมกันว่า "บริการ")เพื่อให้ผู้ใช้บริการรับทราบเกี่ยวกับเงื่อนไข ข้อกำหนด สิทธิและ ความรับผิดชอบของผู้ให้บริการและผู้ใช้บริการ ในการใช้บริการทั้งหมด</p>
            <p>เมื่อผู้ใช้บริการได้ใช้บริการของบริษัทในการเลือกบริการ โดยเฉพาะเมื่อผู้ใช้บริการเริ่มต้นใช้บริการ เริ่มต้นลงทะเบียนเพื่อใช้งานผ่านเว็บไซต์  ทางบริษัทจะถือว่า ผู้ใช้บริการตกลงและยอมรับข้อ ตกลงฉบับนี้แล้ว หากผู้ใช้บริการปฏิเสธหรือไม่ยอมรับข้อตกลงฉบับนี้ บริษัทขอสงวนสิทธิ์ในการปฏิเสธการให้บริการต่าง ๆ แก่ผู้ใช้บริการ ทั้งนี้บริษัทสงวนสิทธิ์ในการแก๊ไขและปรับปรุงข้อตกลงฉบับนี้ได้ตามแต่ละระยะเวลา ให้
                เหมาะสมกับการให้บริการของบริษัท รวมถึงให้สอดคล้องกับกฎหมายที่เกี่ยวข้อง
                โดยบริษัทจะแจ้งการเปลี่ยนแปลงข้อตกลงดังกล้วผ่านเว็บไซต์ที่ให้บริการ และการที่ผู้ใช้บริการยังคงใช้บริการของบริษัทอยู่ บริษัทจะถือว่า ผู้ใช้บริการตกลงและยอมรับข้อตกลงของบริษัท รวมถึงฉบับแก้ไขปรับปรุงเสมอ </p>
            <br>
            <h6>ขอบเขตการบังคับใช้ข้อตกลง</h6>
            <p>ข้อตกลงฉบับนี้ใช้เฉพาะกับการให้บริการของบริษัทตามที่ระบุไว้ หรือ
                ที่บริษัทอาจดำเนินการปรับปรุงและเพิ่มเติมด้วยการแจ้งเป็นลาย
                ลักษณ์อักษรเท่านั้น ตามแต่ระยะเวลา โดยจะไม่มีผลบังคับใช้กับการ
                ให้บริการ หรือการเชื่อมโยงเพื่อให้บริการอื่นของ ร้านค้าผู้ให้บริการเนื่องมาจากบริการของบริษัท (รวมถึงไม่จำกัดเพียงการให้บริการ
                ของร้านค้าผู้ให้บริการผ่านเว็บไซต์ ที่อาจให้บริการเพิ่มเติมนอกเหนือจากที่
                บริษัทกำหนดและแจ้งอนุญาตให้ใช้สิทธิ) ซึ่งบริษัทไม่มีอำนาจควบคุม
                และไม่ได้รับประกันการให้บริการหรือประสิทธิภาพของผู้ให้บริการภายนอกดังกล่าว
            </p>
            <br>
            <p>ผู้ใช้บริการรับทราบว่า นอกเหนือจากข้อตกลงฉบับนี้แล้ว ในการให้บริการของบริษัทผ่านเว็บไซต์ บริษัทมีความจำเป็น ในการเก็บรวบรวม และใช้ข้อมูลส่วนบุคคลของผู้ใช้บริการ และร้านค้า เพื่อประโยชน์ในการให้บริการที่บริษัทให้แก่ผู้ใช้บริการซึ่งผู้ใช้บริการสามารถศึกษานโยบายความเป็นส่วนตัวของผู้ใช้บริการ ได้เพิ่มเติม</p>
            <br>
            <h6>ขอบเขตของการให้บริการเว็บไซต์</h6>
            <p>บริษัทให้บริการเว็บไซต์ ในการหาร้านซักผ้ามาขึ้นในเว็บไซต์เพื่อให้ผู้ใช้เลือกใช้บริการตามความต้องการ ทั้งเรื่องการซักผ้า อบแห้ง และรีดผ้า โดยมีไรเดอร์รับส่งผ้าถึงที่ หรือตามที่ร้านค้าผู้ให้บริการจะมีสิ่งอำนวยความสะดวกอื่นๆ โดยผู้ใช้บริการต้องยอมรับและรับทราบของเขตการให้บริการ และการจำกัดความรับผิดชอบของร้านค้าผู้ให้บริการในเว็บไซต์บริษัทดังนี้</p>
            <p>1. เมื่อผู้ใช้ลงทะเบียนในเว็บไซต์แล้วนั้น ผู้ใช้สามารถเลือกดูร้านค้าผู้ให้บริการได้ในหน้า Dashboard และหน้า Shop ตามที่ปรากฏในเว็บไซต์ จากนั้นเลือกบริการตามความต้องการของผู้ใช้บริการ โดยน้ำยาซักผ้า และน้ำยาปรับผ้านุ่มต่างๆทั้งหมดเป็นการตัดสินใจของทางร้านให้บริการเพียงฝ่ายเดียว ทางบริษัทไม่อาจให้การรับประกันความพึงพอใจได้ (ผู้ใช้บริการสามารถนำน้ำยาซักผ้า และน้ำยาปรับผ้านุ่มที่ต้องการให้ใช้กับผ้าไว้ในตระกร้าผ้าได้)</p>
            <p>2. ความรับผิดชอบของบริษัท หากร้านค้าผู้ให้บริการทำสินค้าของผู้ใช้เสียหายอันเกิดจากความผิดของผู้ให้บริการเอง ทางบริษัทรับผิดชอบโดยการชดใช้ค่าเสียหาย เป็นจำนวนเงิน 10 เท่าของราคาบริการที่ลูกค้าเลือกบริการ โดยผู้ใช้บริการต้องปฏิบัติตามเงื่อนไขที่กำหนด (หากเกิดจากความผิดของผู้ใช้บริการเอง ทางบริษัทไม่รับผิดชอบทุกกรณี)</p>
            <p>3. การชำระเงินค่าบริการ ผู้ใช้บริการสามารถชำระเงินการใช้บริการผ่านเว็บไซต์ โดยการแสกนคิวอาร์พร้อมเพย์ เพื่อชำระค่าบริการ</p>
            <br>
            <p>เพื่อหลีกเลี่ยงข้อสงสัย</p>
            <br>
            <p>a. การชำระราคาค่าบริการ บริษัทใช้บริการของผู้ให้บริการชำระเงินภายนอก ดังนั้น บริษัทไม่สามารถรับประกันการหักเงินและการชำระราคาที่ดำเนินการโดยผู้ใช้บริการชำระเงินภายนอกดังกล่าวได้</p>
            <p>b. บริษัทจะ ถือว่า การชำระค่าบริการสำเร็จเมื่อบริษัทได้รับค่าบริการในจำนวนเต็มตามที่ผู้ใช้บริการเลือกเท่านั้น</p>
            <br>
            <h6>การลงทะเบียนเป็นผู้ใช้บริการเว็บไซต์</h6>
            <p>1. ผู้ใช้บริการสามารถลงทะเบียนเข้าใช้บริการผ่านเว็บไซต์ได้ โดยจะต้องให้รายละเอียดข้อมูลตามที่บริษัทกำหนดเพื่อการเปิดบัญชีผู้ใช้งาน และต้องผูกพันและปฏิบัติตามเงื่อนไขฉบับนี้ รวมถึงเงื่อนไขฉบับแก้ไขอื่น</p>
            <p>2. ผู้ใช้บริการต้องรับประกันว่า บรรดาข้อมูลที่ให้ไว้แก่บริษัทในการลงทะเบียนเปิดบัญชีผู้ใช้งานเว็บไซต์ทั้งหมดนั้นถูกต้อง ครบถ้วน โดยเฉพาะผู้ใช้บริการต้องเป็นบุคคลผู้มีสิทธิและอำนาจตามกฏหมาย (โดนเฉพาะเป็นผู้มีความสามารถตามกฏหมาย) ในการเข้าทำสัญญาและตกลงยอมรับปฏิบัติตามข้อตกลงฉบับนี้</p>
            <br>
            <p>ทั้งนี้เพื่อหลีกเลี่ยงข้อสงสัย บริษัทสงวนสิทธิ แต่ไม่ถือเป็นหน้าที่ในการตรวจสอบข้อมูลของผู้ใช้บริการ และบริษัทมีสิทธิยกเลิกบัญชีผู้ใช้บริการของผู้ใช้บริการที่ให้ข้อมูลไม่ถูกต้อง หรือไม่มีคุณสมบัติตามเงื่อนไขที่บริษัทกำหนด</p>
            <br>
            <h6>การใช้บริการเว็บไซต์โดยผู้ใช้บริการ</h6>
            <p>1. ผู้ใช้บริการตกลงว่า จะใช้บริการเว็บไซต์เพื่อจุดประสงค์ที่ชอบด้วยกฏหมาย โดยเฉพาะภายใต้กรอบวัตถุประสงค์การให้บริการที่บริษัทแจ้งให้ผู้ใช้บริการทราบเป็นลายลักษณ์อักษรเท่านั้น</p>
            <p>2. สำหรับการใช้บริการผ่านเว็บ ผู้ใช้บริการต้องปฏิบัติตามข้อห้ามการใช้บริการดังต่อไปนี้ ทั้งนี้ หากบริษัทตรวจสอบพบกรณีที่ผู้ใช้บริการดำเนินการข้อผิดห้ามที่ระบบไว้ โดยเฉพาะข้อต่อไปนี้</p>
            <p class="p2">a. ห้ามดำเนินการใดที่ผิดกฏหมายหรือศีลธรรมอันดี หมิ่นประมาทหยาบคาบ อนาจาร และ/หรือละเมิดสิทธิ ส่วนบุคคลของผู้อื่น หรือดำเนินการใดนอกจากที่ระบุไว้ภายใต้เงื่อนไขนี้ โดยเฉพาะห้ามใช้บริการเว็บไซต์ เพื่อดำเนินการหรือสนับสนุนการดำเนินกิจกรรมที่ผิดกฏหมาย</p>
            <p class="p2">b. ห้ามดำเนินการปลอมแปลง หรือลอกเลียน หรือกระทำการใดๆ ที่เป็นการแสดงแก่บุคคลภายนอกว่า ตนเป็นผู้ใช้บริการบุคคลอื่น ไม่ว่าด้วยจุดดประสงค์ใด</p>
            <p class="p2">c. ห้ามดำเนินการที่เป็นการละเมิดสิทธิในทรัพย์สินทางปัญหาหรือสิทธิอื่นใดของผู้อื่น</p>
            <p class="p2">d. ห้ามใส่ไวรัส ชุดคำสั่ง หรือโค้ดคอมพิวเตอร์ เพื่อทำลาย หรือทำให้การทำงานของเว็บไซต์ หรือของผู้ใช้บริการอื่นให้ได้ผลกระทบ</p>
            <p class="p2">e. ห้ามพิมพ์ ดาวน์โหลด ทำซ้ำ ส่ง คัดลอก ผลิตซ้ำ จัดส่งต่อ ตีพิมพ์อีกหรือใช้ข้อมูลใดที่อาจทำให้ สามารถระบบตัวตนของผู้ใช้บริการอื่นที่ผู้ผู้ใช้บริการสามารถเข้าถึงได้ ไม่ว่าด้วยเหตุผลใด เว้นแต่จะได้รับความยินยอม เป็นลายลักษณ์อักษรจากเจ้าของข้อมูล</p>
            <p>3. บริษัทมีสิทธิแต่ไม่ใช่หน้าที่ในการตรวจสอบการเข้าถึง การใช้บริการในเวลาใดของผู้ใช้บริการ เพื่อให้แน่ใจว่าผู้ใช้บริการปฏิบัติตามข้อตกลงฉบับนี้ ในกรณีที่ผู้ใช้บริการละเลยไม่ปฏิบัติตามข้อตกลงฉบับนี้ โดยเฉพาะดำเนินการไม่สอดคล้องกับข้อห้ามที่บริษัทกำหนด บริษัทสงวนสิทธิ์ปฏิเสธการทำรายการที่ไม่ถูกต้องดังกล่าวได้ทุกเมื่อ รวมถึงอาจระงับ หรือยกเลิกบัญชีผู้ใช้บริการดังกล่าวได้ทุกเมื่อ โดยไม่ต้องรับผิดชอบและไม่ต้องแจ้งให้ผู้ใช้บริการนั้นทราบ รวมถึงบริษัทไม่ต้องรับผิดชอบต่อความเสียหายใดที่อาจเกิดขึ้นกับผู้ใช้บริการในกรณีดังกล่าว ทั้งนี้สิทธิที่หนดไว้ในภายใต้ข้อตกลงฉบับนี้จะไม่กระทบ สิทธิอื่นใดของบริษัทที่บริษัทมีภายใต้กฎหมายที่บังคับใช้หรือข้อผูกพันตามสัญญาอื่นที่บริษัทอาจมี</p>
            <br>
            <h6>ข้อจำกัดความรับผิดชอบของบริษัท</h6>
            <p>1. ภายใต้ขอบเขตสูงสุดที่กฎหมายที่เกี่ยวข้องอนุญาต บริษัท กรรมการ พนักงานของบริษัทไม่ต้องรับผิดในความเสียหายไม่ว่าใดลักษณะใดต่อผู้ใช้บริการไม่ว่ารายใด เว้นแต่เป็นกรณีเกิดความเสียหายทางตรง อันเกิดจากการกระทำผิด โดยจงใจ หรือประมาทของทางร้านค้าผู้ให้บริการ เพื่อหลีกเลี่ยงข้อสงสัยบริษัทจะไม่ผิดต่อความเสียหายทางอ้อม หรือความเสียหายเกี่ยวเนื่องไม่ว่าลักษณะใดก็ตาม</p>
            <p>2. ภายใต้ขอบเขตสูงสุดที่กฎหมายที่เกี่ยวข้องอนุญาต บริษัทปฏิเสธไม่ให้การรับรองและการรับประกัน รวมถึงไม่รับผิด ทั้งหมดอันเกี่ยวกับเว็บไซต์ โดยเฉพาะในหัวข้อต่อไปนี้</p>
            <p class="p2">a. บริษัทให้บริการแอปพลิเคชันและบริการอื่นบนพื้นฐาน "ตามที่เป็น" และ "ตามที่มี" โดยปราศจากการรับประกันใดว่าบริการ หรือส่วนใดส่วนหนึ่งในลักษณะหรือเนื้อหาของบริการ (1) จะสมบูรณ์ ถูกต้องมีคุณภาพที่แน่นอน หรือปลอดภัย นอกจากที่กำหนดเป็นการเฉพาะและเป็นลายลักษณ์อักษรโดยบริษัท (2) จะเหมาะตรงความต้อง การเฉพาะเจาะจงทั้งหมดของผู้ใช้บริการ (3) แอปพลิเคชันจะสามารถใช้งานได้อย่างต่อเนื่อง และแม่นยำตลอด เวลา โดยปราศจากการสะดุด</p>
            <p class="p2">b. บริษัทไม่รับผิดชอบในความผิดพลาด ความบกพร่อง หรือเหตุขัดข้องในการใช้บริการเว็บไซต์ อันเนื่องมากจากการที่ผู้ใช้บริการ ใช้บริการ หรือทำรายการที่ไม่เป็นไปตามที่บริษัทกำหนด หรือกรณีปัญหาอันเกิดจาก เหตุสุด วิสัย หรือเหตุอื่นใดที่อยู่นอกเหนือการควบคุมของบริษัท รวมถึงการกระทำของผู้ให้บริการภายนอก</p>
            <p>3. ไม่ว่ากรณีใดที่บริษัทต้องรับผิดชอบ และรับผิดต่อผู้ให้บริการ โดยเเฉพาะในส่วนที่เกี่ยวข้องกับเว็บไซต์ ร้านค้าผู้ให้บริการ ภายใต้ข้อจำกัดที่บริษัทกำหนดไว้บริษัทจะรับผิดชดใช้ค่าเสียหายให้แก่ผู้ใช้บริการ เป็นจำนวนเงิน 10 เท่าของราคาบริการ โดยผู้ใช้บริการที่ประสงค์จะใช้สิทธิต้องทำตามเงื่อนไขที่บริษัทกำหนด</p>
            <br>
            <h6>การคุ้มครองทรัพย์สินทางปัญญา</h6>
            <p>สิทธิ กรรมสิทธิ์ และผลประโยชน์ทั้งหมดในบริการ รวมทั้งวัตถุที่สามารถมีลิขสิทธิ์ได้ทั้งหมด หรือเนื้อหาอื่นใด สิทธิในทรัพย์สิน ทางปัญญาภายใต้กฎหมายที่เกี่ยวข้องไม่ว่าประเทศใด ที่มี หรืออาจมี หรือที่อาจแสดงผ่านช่องทางอื่น และโดยเฉพาะอย่างยิ่งผ่านเว็บไซต์ (รวมถึงแต่ไม่จำกัดเพียง งานศิลปะ กราฟฟิก รูปภาพ แม่แบบ เว็บไซต์และวิดเจ็ต งานวรรณกรรม รหัสต้นฉบับหรือออบเจ็คโค้ด คอมพิวเตอา์โค้ด (รวมถึง html) เว็บไซต์ เสียง เพลง วีดีโอและสื่ออื่น การออกแบบ แอนิเมชั่น อินเทอร์เฟซ เอกสาร สิ่งประดิษฐ์ต่อยอดและการปรับปรุงเวอร์ชั่นของบริการดังกล่าว "รูปลักษณ์และ ความรู้สึก Mood and Tone") ของบริการ วิธีการผลิตภัณฑ์ อัลกอริทึม ข้อมูล คุณลักษณะและวัตถุแบบโต้ตอบ 
               เครื่องมือ และวิธีการในการโฆษณาและการได้มา สิ่งประดิษฐ์ ความลับทางการค้า โลโก้ โดเมน URLs ที่กำหนดเอง เครื่องหมายการค้า เครื่องหมายบริการ ชื่อทางการค้า และสิ่งระบุกรรมสิทธไม่ว่าจะจดทะเบียน หรือสามารถจดทะเบียนได้ และที่มาของทรัพย์สินทางปัญญาใดนั้นเป็นของหรือเป็นสิทธิในทรัพย์สินทางปัญญาของตนทั้งหมด ซึ่งรวมถึงแต่ไม่จำกัดเป้นการใช้สิทธิบังคับต่อการละเมิดสิทธิหรือเงื่อนไขการใช้ทรัพย์สินทางปัญญาโดยผู้ใช้บริการ</p>
            <br>
            <h6>ข้อกำหนดอื่น</h6>
            <p>1. การโอนสิทธิหน้าที่ บริษัทอาจมอบหมายสิทธิหรือหน้าที่ของบริษัทภายใต้ข้อตกลงฉบับนี้ และ/หรือ โอนกรรมสิทธิความเป็นเจ้าของในบริการนี้ให้แก่บุคคลภายนอกได้ โดยไม่ต้องขอความยิยอมหรือไม่ต้องแจ้งให้ผู้ใช้บริการทราบ แต่ผู้ใช้บริการไม่สามารถมอบหมายหรือโอนสิทธืและหน้าที่ใดของผู้ใช้บริการ ภายใต้ข้อตกลงฉบับนี้ให้แก่บุคคลภายนอกได้ เว้นแต่จะได้รับความยินยอมเป็นลายลักษณ์อักษรอย่างชัดแจ้งจากบริษัท</p>
            <p>2. ความไม่สมบูรณ์ของข้อตกลง หากข้อกำหนดใดในข้อตกลงฉบับนี้ไม่ชอบด้วยกฎหมาย ตกเป็นโมฆะ หรือไม่สามารถบังคับใช้ได้ภายใต้กฎหมายที่ใช้บังคับข้อกำหนดดังกล่าวจะไม่มีผลถึงข้อกำหนดอื่นในข้อตกลงฉบับนี้ และตามขอบเขตสูงสุดที่กฎหมายอนุญาต ข้อกำหนดที่ไม่ชอบด้วยกฎหมาย เป็นโมฆะ หรือไม่สามารถใช้ได้นั้นจะถูกเปลี่ยนแปลง ด้วยข้อกำหนดที่ใกล้เเคียงและมีผลเช่นเดียวกันกับข้อกำหนดเดิมที่สามารถบังคับใช้ได้</p>
            <p>3. ข้อสละสิทธิ์ การที่บริษัทไม่บังคับสิทธิใด ภายใต้ข้อตกลงฉบับนี้หรือไม่มีการดำเนินการใด ในกรณีที่ผู้ใช้บริการละเมิดข้อตกลงฉบับนี้ ไม่ถือเป็นการที่บริษัทเสียสละสิทธิ์ดังกล่าวในอนาคต</p>
            <p>4. กฎหมายที่ใช้บังคับ ข้อตกลงฉบับนี้ให้ควบคุม ตีความ หรือบังคับใช้ตามกฎหมายภายในของประเทศไทย</p>
            <p>5. การระงับข้อพิพาท ในกรณีที่มีข้อเรียกร้องและข้อพิพาทใด เกิดขึ้นจากข้อตกลงฉบับนี้ ทั้งสองฝ่ายจะแก้ไขปัญหาระหว่างกันอย่างเป็นมิตรก่อน หากไม่สามารถแก้ไขได้ จะเสนอเรื่องดังกล่าวเป็นคดีขึ้นสู่ศาลในประเทศไทยที่มีเขตอำนาจตัดสิน</p>
            



        </div>
    </div>

    <div class="btn-condition">
        <a href="sevice2_user.php" class="ser2">ข้อตกลงการใช้บริการ</a> <br>
        <a href="sevice3_user.php" class="ser3">นโยบายความเป็นส่วนตัว</a>
    </div>

    <!-- นโยบาย card end-->


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

        /* popup */
        function togglePopup() {
            document.getElementById("popup-2").classList.toggle("active");
            document.querySelector('.shopping-cart').classList.remove('active');
        }


    </script>
</body>
</html>
