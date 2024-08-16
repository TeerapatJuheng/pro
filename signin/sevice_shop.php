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
            color:#507F99;
        }

        .btn-condition{
            font-size:15px;
            margin-left:2rem;
        }

        .ser2, .ser3{
            color:#507F99;
        }
        

        
    </style>
    <title>Sevice shop</title>
</head>

    <body>

        <!-- header section starts -->
        <header class="header">
        <a href="#" class="logo">Laundry</a>
        
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
            <img src="../photo/1x/รีด.png" alt="">
            <h3>kkkkkkk oooooooo</h3>
            <span>shop</span>
            <a href="profile_shop.php" class="btnp">Profile</a>
            <div class="flex-btnp">
                <a href="history_shop.php" class="option-btnp">ประวัติ</a>
                <a href="login.php" class="option-btnp">Logout</a>
            </div>
        </div>
        </header> 

   
    <!-- นโยบาย card-->
    <div class="card-condition">
        <div class="card-body">
        <h1 class="heading">เงื่อนไข <span>การใช้บริการ</span></h1>
            <h6>การบริการ</h6>
            <p>* ค่าบริการในการ ซัก อบ รีด จะมีราคาบริการเริ่มต้นประมาณ 40 บาท ต่อตระกร้า (1 เครื่อง) ราคาจะขึ้นอยู่กับแต่ละร้านค้า </p>
            <p>* โดยค่าบริการนี้ รวมน้ำยาซักผ้า และน้ำยาปรับผ้านุ่มที่ทางร้านมีบริการให้แล้ว </p>
            <p>* หากลูกค้าต้องการจะใช้น้ำยาซักผ้า หรือน้ำยาปรับผ้านุ่มที่ต้องการ ลูกค้าสามารถทำได้โดยแจ้งร้านค้าผ่าน หมายเหตุ หรือใส่น้ำยาซักผ้าและน้ำยาปรับผ้านุ่มไว้ในตระกร้า</p>
            <p>* บริการนี้จะไม่บริการ ผ้าที่ใช้วัสดุหนัง เช่น หนังแท้ หนังเทียม ขนสัตว์แท้ ขนสัตว์เทียม ผ้าไหม ผ้าลูกไม้ ผ้าเลื่อม หรือเสื้อผ้าที่ต้องซักพิเศษ (ซักแห้งเท่านั้น) </p>
            <p>* รับชำระค่าบริการผ่านช่องทางออนไลน์ในเว็บไซต์เท่านั้น</p>
            <br>
            <h6>การชดใช้ความเสียหาย</h6>
            <p>* ไม่รับผิดชอบกรณี ผ้าขาด ผ้าสีตก กระดุมหลุด กระดุมหาย ยางยืดเสียหาย การเสื่อมสภาพจากการใช้งาน การส่งผ้าผิดประเภทการซัก หรือปัญหาจากการตัดเย็บ ผ้าที่มีคราบฝังลึกเป็นเวลานานไม่สามารถซักออกได้</p>
            <p>* ทางร้านซักผ้าจะทำการยืนยันออเดอร์ และแจ้งตำหนิ (หากมี) ก่อนให้บริการ</p>
            <p>* หากทางผู้ใช้บริการต้องการให้บุคคลอื่นมารับผ้าแทนตอนทางร้านนำผ้าไปส่ง ให้แจ้งในหมายเหตุและเลขออเดอร์แก่บุคคลที่จะรับผ้าแทน เพื่อไม่ให้เกิดการเข้าใจผิด และทางร้านผู้ให้บริการจะไม่รับผิดชอบต่อความเสียหายและการสูญหายหลังจากมอบสินค้า </p>
            <p>* กรณีผ้าเสียหาย ฉีกขาด ชำรุด หรือสูญหาย ไม่สามารถใช้งานได้อีก อันเกิดจากการกระทำของทางร้านผู้ให้บริการ จะชดใช้ค่าเสียหายจำนวน 10 เท่าของค่าบริการเท่านั้น</p>
            <p>* การเรียกร้องค่าเสียหายต้องภายในเวลา 24 ชม. หลังได้รับผ้าคืน และต้องมีเลขออเดอร์เพื่อยืนยันการใช้บริการ รายละเอียดข้อมูลการใช้บริการในเว็บไซต์ เพื่อเรียกร้องค่าเสียหาย</p>
            <p>* ทางผู้ให้บริการขอสงวนสิทธิ์ในการยกเลิก หรือเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า</p>
            <p>* ทางผู้ให้บริการขอสงวนสิทธิ์ไม่คืนค่าบริการทั้งหมด หรือบางส่วน ในกรณียกเลิกการรับบริการไม่ว่ากรณีใดๆก็ตาม</p>
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
