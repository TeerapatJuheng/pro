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

        .heading{
            font-size:2rem;
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
        <h1 class="heading">นโยบายความเป็นส่วนตัวของ <span>ผู้ใช้บริการ</span></h1>
            <!--<h6>การบริการ</h6>-->
            <br>
            <p>นโยบายความเป็นส่วนตัวของผู้ใช้บริการ (นโยบาย) ฉบับนี้ได้รับการจัดทำโดยบริษัท อาร์ม คอร์ปอเรชั่น จำกัด (บริษัท) เพื่อแจ้งให้ท่านในฐานะผู้ใช้บริการดังนี้ (ผู้ใช้บริการ) 1.ที่ใช้บริการฝากซักผ้าที่บริษัทได้ให้บริการ 2.ที่เยี่ยมชมและใช้บริการเว็บไซต์ของบริษัท ภายใต้ชื่อ Laundry system รวมถึงการใช้บริการชำระเงิน และบริการอื่นใดผ่านเว็บไซต์ที่บริษัทได้พัฒนาขึ้น เพื่อสนับสนุนการใช้บริการหลัก (เว็บไซต์) 3.ที่ใช้บริการติดต่อประสานงานสนับสนุนอื่นผ่าน Call Center , line Offcial Account ของบริษัท รับทราบเกี่ยวกับเงื่อนไข ความจำเป็นในการประมวลผลข้อมูลส่วนบุคคลของผู้ใช้บริการ ที่บริษัทจำเป็นต้องดำเนินการเพื่อการให้บริการทั้งหมดของบริษัทแก่ผู้ใช้บริการ  </p>
            <br>
            <p>เมื่อผู้ใช้บริการเริ่มต้นใช้บริการของบริษัท เริ่มต้นลงทะเบียนเพื่อใช้งานเว็บไซต์ หรือติดต่อเข้ามายังบริษัทผ่านช่องทางที่กำหนด ทางบริษัทจะถือว่าท่านตกลงและยอมรับนโยบายฉบับนี้แล้ว หากผู้ใช้บริการปฏิเสธหรือไม่ยอมรับนโยบายฉบับนี้ บริษัทขอสงวนสิทธิ์ในการปฏิเสธการให้บริการต่างๆแก่ผู้ใช้บริการดังกล่าว ทั้งนี้บริษัทสงวนสิทธิ์ในการแก้ไขประมวลผลข้อมูลส่วนบุคคลของผู้ใช้บริการให้สอดคล้องกับการให้บริการบริษัท รวมถึงสอดคล้องกับกฎหมายที่เกี่ยวข้อง โดยบริษัทจะแจ้งให้ท่านทราบถึงการเปลี่ยนแปลงก้วยการประกาศนโยบายฉบับนี้ ปรับปรุงผ่านช่องทางการติดต่อต่างๆของบริษัท</p>
            <br>
            <h6>ขอบเขตการบังคับของนโยบาย</h6>
            <p>นโยบายฉบับนี้ใช้เฉพาะกับการประมวลผลข้อมูลส่วนบุคคลของผู้ใช้บริการที่บริษัทดำเนินการ เพื่อให้บริการของบริษัทตามที่ระบุไว้ หรือที่บริษัทอาจดำเนินการปรับปรุงและเพิ่มเติมด้วยการแจ้งเป็นลายลักษร์อักษรเท่านั้น ตามแต่ระยะเวลา โดยจะไม่มีผลบังคับใช้กับการประมวลผลข้อมูลส่วนบุคคลของผู้ใช้บริการ โดยบุคคลภายนอกที่อาจต่อเนื่องมาจากบริการของบริษัท (รวมถึงแต่ไม่จำกัดเพียงการให้บริกการของร้านผู้ให้บริการของบีิษัท ที่อาจดำเนินการเพิ่มเติมนอกเหนือจากบริษัทกำหนดและแจ้งอนุญาตให้ใช้สิทธิ) ซึ่งบริษัทไม่มีอำนาจควบคุม และผู้ใช้บริการต้องศึกษานโยบายความเป็นส่วนตัวของบุคคลดังกล่าวเป็นการเฉพาะ</p>
            <br>
            <p>ภายใต้นโยบายฉบับนี้ "ข้อมูลส่วนบุคคล" หมายถึง ข้อมูลไม่ว่าในลักษณะใดที่ทำให้บริษัทสามารถระบุตัวผู้ใช้บริการรายบุคคลไม่ว่าทางตรงหรือทางอ้อม รวมถึงแต่ไม่จำกัดเพียงข้อมูลส่วนบุคคลของผู้ใช้บริการที่เป็oบุคคลธรรมดา หรือข้อมูลส่วนบุคคลของกรรมการผู้มีอำนาจลงนาม และ/หรือตัวแทนผู้ได้รับมอบอำนาจของผู้ใช้บริการที่เป็นนิติบุคคล</p>
            <br>
            <h6>ข้อมูลส่วนบุคคลที่มีการประมวลผล</h6>
            <p>บริษัทอาจได้รับข้อมูลส่วนบุคคลของผู้ใช้บริการจากแหล่งที่มา ดังนี้ (1) ได้รับจากผู้ใช้บริการโดยตรง ในระหว่างการลงทะเบียนและการติดต่อสื่อสารต่างๆเช่น call center, Line Offcial ของบริษัท (2) ผู้แนะนำอื่น (Referral) รวมถึงบริษัทอาจได้รับการยืนยันการชำระเงินของผู้ใช้บริการจากผู้ให้ ซึ่งในกรณีได้รับข้อมูลส่วนบุคคลของผู้ใช้บริการจากบุคคลอื่นดังกล่าว บริษัทจะถือว่า บุคคลที่ให้ข้อมูลของผู้ใช้บริการแก่บริษัทเหล่านั้น มีสิทธิอันชอบด้วยกฎหมายในการส่งต่อเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการมาให้แก่บริษัท เพื่อประมวลผลภายใต้เงื่อนไขของนโยบายฉบับนี้</p>
            <br>
            <h6>ข้อมูลส่วนบุคคลที่มีการประมวลผลระหว่างการใช้บริการเว็บไซต์ และการติดต่อประสานงานการใช้บริการ</h6>
            <p>ระหว่างที่ผู้ใช้บริการเข้ามาใช้บริการในเว็บไซต์ของบริษัท และ/หรือใช้บริการของบริษัท รวมถึงการติดต่อสอบถาม บริษัมจำเป็นต้องเก็บรวบรวมใช้ประมวลผลข้อมูลส่วนบุคคล ดังต่อไปนี้</p>
            <p>* ชื่อ-นามสกุล และรายละเอียดการติดต่อร้องเรียน ของผู้ใช้บริการที่มีต่อบริษัท ซึ่งผู้ใช้บริการนั้นอาจติดต่อสื่อสารมายังบริษัท ผ่านช่องทางการติดต่อที่บริษัทกำหนด</p>
            <br>
            <p>ข้อมูลส่วนบุคคลทั้งหมดที่บริษัทจำเป็นต้องเก็บ รวบรวม และใช้นี้จะถูกใช้เพื่อวัตถุประสงค์หลัก ดังนี้</p>
            <p>1. เพื่อการปฏิบัติหน้าที่ตามกฎหมายที่บริษัทอาจมี โดยเพาะกฎหมายว่าด้วย บัญชีภาษีหรือการเงิน ซึ่งบริษัทมีความจำเป็นต้องเก็บข้อมูลตามรอบระยะเวลาบัญชีและกฎหมายว่าด้วยความรับผิดทางคอมพิวเตอร์ที่บริษัทจำเป็นต้องเก็บรักษาข้อมูลการเชื่อมโยง wifi เป็นระยะเวลาอย่างน้อย 90 วัน</p>
            <p>2. เพื่อการปฏิบัติหน้าที่ตามสัญญาในการให้บริการสนับสนุน ตอบคำถามข้อสงสัยที่ผู้ใช้บริการอาจมีมายังบริษัท หรือการพิจารณาการตรวจสอบคุณสมบัติการเข้าร่วมกิจกรรมที่บริษัทจัดททำขึ้น โดยบริษัทมีความจำเป็นในการเก็บ รวบรวม ใช้ข้อมูลส่วนบุคคลของผู้ใช้บริการตราบเท่าที่จำเป็นในการให้บริการของบริษัท หรือตลอดระยะเวลาการจัดกิจกรรม </p>
            <p>3. เพื่อการรักษาสิทธิอันชอบด้วยกฎหมายของบริษัท โดยเฉพาะในส่วนของการติดตามวิเคราะห์การแก้ไขและบริหารจัดการข้อร้องเรียน การให้บริการ ทั้งนี้บริษัทจะไม่ดำเนินการใดอันเป็นไปในลักษณะที่กระทบสิทธิเจ้าของข้อมูลมากเกินไป</p>
            <br>
            <h6>ข้อมูลส่วนบุคคลที่มีการประมวลผลระหว่างการใช้บริการเว็บไซต์</h6>
            <p>ระหว่างการใช้งานเว็บไซต์ ตั้งแต่การสมัครบัญชีผู้ใช้งาน การดำเนินธุรกรรมใดก็ตามด้วยบัญชีผู้ใช้งานดังกล่าวผ่านเว็บไซต์ บริษัทจำเป็นต้องเก็บ รวบ รวม ใช้ และประมวลผลข้อมูลส่วนบุคคล ดังต่อไปนี้</p>
            <p>1. ข้อมูลทั่วไปของผู้ใช้บริการ ได้แก่ ชื่อ นามสกุล เบอร์โทร ที่อยู่ อีเมล์ รหัสผ่าน </p>
            <p>2. ข้อมูลการจราจรทางคอมพิวเตอร์ของผู้ใช้บริการ (Log Data) ซึ่งรวมถึงข้อมูล IP Address รายละเอียดระบบ ปฏิบัติการของเครื่องมือที่ผู้ใช้บริการใช้เพื่อการใช้บริการเว็บไซต์ </p>
            <p>3. ข้อมูลการชำระเงินเพื่อการใช้บริการของบริษัท โดยเฉพาะการชะเงิน ซึ่งบริษัทอาจได้รับข้อมูลการชำระเงินและข้อมูลบัญชีธนาคาร รวมถึงประวัติการชำระเงินของผู้ใช้บริการ</p>
            <p>4. ข้อมูลการลงทะเบียน (Log-in) และการใช้บริการของผู้ใช้บริการต่างๆผ่านเว็บไซต์ โดยเฉพาะ การเลือกบริการ การชำระเงิน ซึ่งข้อมูลดังกล่าวจะเชื่อมโยงมายังตัวผู้ใช้บริการด้วยบัญชีผู้ใช้งาน</p>
            <p>5. ข้อมูลส่วนบุคคลอื่นที่บริษัทอาจมีความจำเป็นต้องจัดเก็บ เพื่อปฏิบัติหน้าที่ของบริษัทตามกฏหมาย ประกาศ หรือข้อบังคับที่เกี่ยวข้อง รวมถึงข้อมูลส่วนบุคคลอื่นที่ผู้ใช้บริการอาจยินยอมส่งต่อเปิดเผยต่อบริษัท</p>
            <br>
            <p>ข้อมูลส่วนบุคคลทั้งหมดที่บริษัทจำเป็นต้องเก็บ รวบรวม และใช้สำหรับการใช้บริการเว็บไซต์ จะถูกใช้เพื่อวัตถุประสงค์หลัก ดังนี้</p>
            <p>1. เพื่อการปฏิบัติหน้าที่ตามกฎหมายที่บริษัทอาจมี และต้องดำเนินการ เช่น การจัดทำเอกสารทางด้านบัญชีและภาษี หรือหน้าที่อื่นตามที่กำหนดไว้ในกฎหมาย ประกาศ ข้อบังคับอื่นที่บริษัทต้องปฏิบัติตาม</p>
            <p>2. เพื่อการปฏิบัติหน้าที่ในการให้บริการแก่ผู้ใช้บริการ ตามเงื่อนไขที่ระบุไว้ในข้อตกลงการใช้บริการซึ่งบริษัทได้ประกาศกำหนดขึ้น ซึ่งรวมถึงแต่ไม่จำกัดเพียง (i) การยืนยันตัวตนและสิทธิของผู้ใช้บริการแต่ละบุคคล โดยอ้างอิงจากบัญชีผู้ใช้งาน (ii) การเลือกใช้บริการ การชำระค่าบริการให้สำเร็จและสมบูรณ์ตามคำสั่งของผู็ใช้บริการ (iii) การให้บริการตามที่ผู้ใช้บริการอาจร้องขอให้บริษัทดำเนินการให้ รวมถึงไม่จำกัดเพียงการติดต่อประสานงานการใช้บริการหน้าร้าน หรือการใช้บริการเว็บไซต์ของบริษัท ทั้งนี้บริษัทมีความจำเป็นต้องประมวลผล และเก็บรวบรวมข้อมูลส่วนบุคคลของผู้ใช้บริการ เพื่อจุดประสงค์ดังกล่าว ตลอดระยะเวลาตราบเท่าที่ผู้ใช้บริการยังคงมีบัญชีผู้ใช้งานบนเว็บไซต์ </p>
            <p>3. เพื่อการป้องกันและการใช้สิทธิประโยชน์อันชอบด้วยกฎหมายของบริษัท โดยไม่กระทบสิทธิของผู้ใช้บริการในฐานะเจ้าของข้อมูลมากเกินสมควร บริษัทจำเป็นต้องประมวลผลข้อมูลส่วนบุคคลของผู้ใช้บริการเพื่อวัตถุประสงค์หลัก ดังนี้ (i) เพื่อการบริหารจัดการความเสี่ยงภาพรวมขององค์กร โดยเฉพาะความเสี่ยงจากการใช้บริการเว็บไซต์ และป้องกันการทุจริตหรือการละเมิดหรือดำเนินการผิดจากเงื่อนไขในข้อกำหนดการใช้บริการที่บริษัทกำหนดไว้ (ii) เพื่อการสร้าง และปรับปรุงความสัมพันธ์ทางธุรกิจที่บริษัทมีกับผู้ใช้บริการ ด้วยการวิเคราะห์ข้อมูลผู้ใช้บริการจากการใช้บริการเว็บไซต์ และการจัดกลุ่มของผู้ใช้บริการเพื่อการปรับปรุงการให้บริการผ่านเว็บไซต์ให้ตรงกับความต้องการและความสนใจของผู้ใชบริการ ไม่ว่าจะเป็นรายบุคคลและผู้ใช้บริการโดยทั่วไปได้มากขึ้น และเพื่อการปรับปรุงการวางแผนทำการตลาดของบริษัทได้ตรงกับผู้ใช้บริการแต่ละกลุ่มได้ ซึ่งอาจรวมถึงการทำการตลาดแบบ Facebook เป็นต้น (iii) เพื่อการนำเสนอพื้นที่โฆษณาและบริการเสริมอื่น ตามความต้องการและพฤติกรรมการใช้บริการของผู้ใช้บริการ (ที่ไม่ได้มีการลักษณะเป็นการทำการตลาดแบบตรง) ทั้งนี้บริษัทสงวนสิทธิ์ในการเก็บรวบรวมข้อมูลส่วนบุคคลของผู้ใช้บริการเพื่อวัตถุประสงค์อันชอบด้วยกฎหมายดังกล่าว ตลอดระยะเวลาที่จำเป็นทางธุรกิจของบริษัท โดยบริษัทอาจประมวลผลข้อมูลรายบุคคล</p>
            <p>4. เพื่อการปกป้องและต่อสู้สิทธิการเรียกร้อง หรือข้อพิพาทใดที่บริษัทอาจมีกับผู้ใช้บริการ ทั้งนี้สำหรับจุดประสงค์ที่ระบุไว้นี้ บริษัทสงวนสิทธิ์ในการเก็บข้อมูลส่วนบุคคลดังกล่าวไว้ เป็นระยะเวลาสูงสุดตามอายุความที่กำหนดไว้ในกฎหมาย</p>
            <br>
            <h6>การส่งต่อเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการ</h6>
            <p>โดยหลักการ บริษัทจะไม่ส่งต่อเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการให้แก่บุคคลภายนอก เว้นแต่เป็นกรณีจำเป็น บริษัทอาจมีความจำเป็นต้องส่งต่อและ/หรือเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการดังกล่าว ให้แก่ (i) ผู้ให้บริการภายนอก ซึ่งบริษัทอาจว่าจ้างเพื่อสนับสนุนในการให้บริการที่บริษัทต้องดำเนินการให้แก่ผู้ใช้บริการ (ซึ่งรวมถึงแต่ไม่จำกัดเพียง ผู้ให้บริการระบบเทคโนโลยีสารสนเทศ ที่ปรึกษา หรือผู้ให้บริการระบบการชำระเงิน) ผู้ให้บริการภายนอกที่ได้รับการว่าจ้างเพื่อสนับสนุนในการดำเนินธุรกิจของบริษัท (เช่น ที่ปรึกษากฎหมาย ที่ปรึกษาบัญชี ผู้ตรวจสอบบัญชี เป็นต้น) โดยบริษัทจะเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการเพียงเท่าที่จำเป็นภายใต้กรอบสัญญาการประมวลผลข้อมูลส่วนบุคคลที่จะมีการลงนามระหว่างบริษัทและบุคคลดังกล่าวเท่านั้น (ii) หน่วยงานราชการที่บริษัทอาจอยู่ภายใต้กฎหมาย คำพิพากษา หรือคำสั่งของหน่วยงานดังกล่าวให้ต้องเปิดเผยข้อมูลของผู้ใช้บริการแก่หน่วยงานนั้น โดยจะเป็นการเปิดเผยเพียงเท่าที่จำเป็นเท่านั้น และ (iii) ในกรณีที่ผู้ใช้บริการอาจให้ความยินยอมแก่บริษัทในการส่งต่อเปิดเผยข้อมูลส่วนบุคคลแก่พันธมิตรของทางธุรกิจของบริษัทเป็นการเฉพาะ บริษัทจะส่งต่อเปิดเผยข้อมูลส่วนบุคคลของผู้ใช้บริการให้แก่พันธมิตรดังกล่าว เพื่อนำไปเสนอข้อเสนอบริการอื่นของพันธมิตรที่ผู้ใช้บริการอาจสนใจ</p>
            <br>
            <h6>มาตราการรักษาความมั่นคงปลอดภัยในข้อมูลส่วนบุคคล</h6>
            <p>บริษัทรับประกันจัดให้มีมาตรการรักษาความมั่นคงปลอดภัยที่เหมาะสม ภายใต้กฎหมายที่เกี่ยวข้องเพื่อป้องกันการเข้าถึง การใช้ การเปลี่ยนแปลง การแก้ไข หรือการเปิดเผยข้อมูลของผู้ใช้บริการโดยปราศจากอำนาจหรือโดยมิชอบ ทั้งนี้บริษัทจะทบทวนมาตรการดังกล่าวเป็นระยะ เพื่อให้สอดคล้องและเหมาะสมตามกฎหมายที่เกี่ยวข้อง</p>
            <br>
            <h6>สิทธิของเจ้าของข้อมูล</h6>
            <p>บริษัทเคารพสิทธิตามกฎหมายของผู้ใช้บริการ ในฐานะเจ้าของข้อมูลในส่วนที่เกี่ยวข้องกับข้อมูลส่วนบุคคลของผู้ใช้บริการดังกล่าวที่อยู่ในการควบคุมของบริษัท โดยผู้ใช้บริการสามารถขอใช้สิทธิที่มีต่อไปนี้ได้ภายใต้กรอบของกฎหมายที่เกี่ยวข้อง (i) สิทธิถอนความยินยอม (ii) สิทธิขอเข้าถึง และขอรับสำเนาข้อมูลส่วนบุคคล (iii) สิทธิในการขอแก้ไขข้อมูลส่วนบุคคลให้ถูกต้อง (iv) สิทธิขอรับข้อมูลส่วนบุคคลในกรณีที่บริษัททำให้ข้อมูลส่วนบุคคลนั้นอยู่ในรูปแบบที่สามารถอ่านหรือใช้งานด้วยเครื่องมือ หรืออุปกรณ์ที่ทำงานได้โดยอัตโนมัติ รวมถึงสิทธิขอให้ส่งหรือโอนข้อมูลรูปแบบดังกล่าวไปยังผู้ควบคุมข้อมูลส่วนบุคคลอื่น (v) สิทธิคัดค้านการประมวลผลข้อมูลส่วนบุคคล (vi) สิทธิขอให้ลบหรือทำลายข้อมูลส่วนบุคคล เมื่อข้อมูลนั้นหมดความจำเป็น (viii) สิทธิในการขอให้ระงับการใช้ข้อมูลส่วนบุคคลได้</p>


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
