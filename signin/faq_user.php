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

        @media (max-width:450px){
            html{
                font-size:50%;
            }

            .popup2 .content5 {
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 100%;
                margin-top: 40px;
                width: 250px;
                max-height: 150%;
                
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
        let accordions = document.querySelectorAll('.accordion-container .accordion');

        accordions.forEach(acco =>{
            acco.onclick = () =>{
                accordions.forEach(subAcco => { subAcco.classList.remove('active') });
                acco.classList.add('active');
            }
        })

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


        function toggleForm() {
            var form = document.getElementById('issueForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        /* popup */
        function togglePopup() {
            document.getElementById("popup-2").classList.toggle("active");
            document.querySelector('.shopping-cart').classList.remove('active');
        }


    </script>
</body>
</html>