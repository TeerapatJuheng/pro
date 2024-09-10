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
        box-sizing: border-box;
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

        .heading {
            padding-top:8rem ;
            padding-bottom:2rem ;
            font-size:3rem;
            color:var(--black);
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

            html{
                font-size: 55%;
            }

            .box-container .box {
                flex-flow: column;
            }

            .card-container {
                flex-flow:column;
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

            .box-container .box .image-container .big-image img {
                height: auto;
                width: 100%;
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

        /* shop2 */
        .box-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /*padding-bottom: 20px;*/
            /*background: #ccc;*/
            margin-top: 100px;
        }

        .box-container .box {
            display: flex;
            /*align-items: center;*/
            /*justify-content: center;*/
            background: #fff;
            width: 100%;
            padding: 1rem;
            margin: 2rem;
        }

        .box-container .box .image-container {
            text-align: center;
            padding: 1rem 2rem;
        }

        .box-container .box .image-container .big-image {
            border: 1px solid #507F99;
            padding: 2rem 1rem;
        }

        .box-container .box .image-container .big-image img {
            height: 25rem;
        }

        .box-container .box .content {
            padding: 1rem;
        }

        .box-container .box .content .title, 
        .box-container .box .content .address, 
        .box-container .box .content .datetime,
        .box-container .box .content .time ,
        .box-container .box .content .cont {
            font-size: 16px;
            color: #333;
            padding: 5px 0;
            text-transform: uppercase;
        }

        .box-container .box .content .p1 {
            padding: 5px 0;
            font-size: 13px;
            color: #666;
        }

        .box-container .box .content form .dropDown span {
            font-size: 18px;
            display: block;
            color: #333;
            padding: 5px 0;
        }

        .box-container .box .content form .dropDown select {
            width: 100%;
            height: 3rem;
            font-size: 1.5rem;
            color: #666;
            border: .1rem solid #507F99;
            border-radius: 10px;
        }

        .price2 {
            color: #333;
            border-top: .2rem solid #333;
            border-bottom: .2rem solid #333;
            font-size: 15px;
            padding: .5rem;
            display: inline-block;
            text-shadow: 0 0 .1rem rgba(0, 0, 0, .4);
            margin-top: 10px;
        }

        .box-container .box .content form .quantity2 {
            padding: 1rem 0;
        }

        .box-container .box .content form .quantity2 span {
            font-size: 15px;
            color: #333;
        }

        .box-container .box .content form .quantity2 .q {
            height: 3rem;
            width: 6rem;
            text-align: center;
            font-size: 15px;
            color: #666;
            margin: 0 1rem;
            border: .1rem solid #000;
            border-radius: 5px;
        }

        /*.box-container .box .content form .tt {
            padding: 1rem 0;
        }*/

        .box-container .box .content form .tt span {
            font-size: 18px;
            color: #333;
            padding: 5px 0;
        }

        .box-container .box .content form .tt textarea {
            border: .1rem solid #000;
            border-radius: 5px;
            width: 100%;
        }

        .box-container .box .content .btn2 {
            height: 4rem;
            width: 18rem;
            background: #507F99;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 1.7rem;
            border-radius: 5px;
            float: right;
        }  

        .box-container .box .content .btn2:hover {
            background: #728FCE;
        } 

        /* ช่องบริการ*/
        .card-container {
            display: flex;
        }

        .img {
            width: 100px;
            height: 100px;
            border: .3rem solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 .5rem rgba(0, 0, 0, .4);
            overflow: hidden;
        }

        .img2 {
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
        }

        .card {
            background-color: #fff;
            margin: 0 1rem;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: .5rem;
            position: relative;
            cursor: pointer;
            border: .5rem solid transparent;
            transition: 200ms ease-in-out transform;
        }

        .title2 {
            margin: 1rem 0;
        }

        .title2,
        .p2 {
            color: #1f1208;
            text-shadow: 0 0 .1rem rgba(0, 0, 0, .4);
            font-size: 13px;
            text-align: center;
        }

        .card:hover {
            transform: scale(1.02)
        }
        .card:active {
            transform: scale(.97)
        }

        input[id="card_one"]:checked ~ label[for="card_one"] .card,
        input[id="card_two"]:checked ~ label[for="card_two"] .card,
        input[id="card_three"]:checked ~ label[for="card_three"] .card,
        input[id="card_four"]:checked ~ label[for="card_four"] .card,
        input[id="card_five"]:checked ~ label[for="card_five"] .card  {
            border-color: #507F99;
        }
        /* you can see based on which card is checked the border color will be changed */
        /* ~ selector selects the sibling elements, in this case the label */

        input[id="card_one"]:checked ~ label[for="card_one"] .card .img,
        input[id="card_two"]:checked ~ label[for="card_two"] .card .img,
        input[id="card_three"]:checked ~ label[for="card_three"] .card .img,
        input[id="card_four"]:checked ~ label[for="card_four"] .card .img,
        input[id="card_five"]:checked ~ label[for="card_five"] .card .img {
            border-color: #507F99;
            box-shadow: 0 0 .5rem #507F99;
        }

        /* its all done and the last thing is we need to remove the input radio btns */
        .card-service .card-container input {
            display: none;
        }

        /*review */
        .review {
            margin-left: 20px;
        }

        .review .review-slider {
            padding: 1rem;
        }

        .review .review-slider:first-child {
            margin-bottom:2rem;
        }

        .review .review-slider .box3 {
            background:#fff;
            border-radius:.5rem;
            text-align:center;
            padding: 3rem 2rem;
            outline-offset:-1rem;
            outline:var(--outline);
            box-shadow:var(--box-shadow);
            transition: .2s linear;
        }

        .review .review-slider .box3:hover {
            outline-offset:0rem;
            outline:var(--outline-hover);
        }

        .review .review-slider .box3 img {
            height: 100px;
            width: 100px;
            border: 1px solid #000;
        }

        .review .review-slider .box3 h3 {
            font-size:15px;
            color:var(--black);
        }

        .review .review-slider .box3 p {
            font-size:12px;
            color:var(--light-color);
            padding: .2rem 0;
        }

        .review .review-slider .box3 .stars i {
            font-size:1.7rem;
            color:#FFBF00;
            padding:0.5rem 0;
        }

        .review h1 {
            text-align:left;
            font-size:22px;
            color:#000;
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

    <title>Shop</title>

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

    <!-- ร้านค้าที่กดเลือก  -->

        <div class="box-container">

            <div class="box">

                <div class="image-container">

                    <div class="big-image">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                    </div>

                </div>

                <div class="content">

                    <h3 class="title">ชื่อร้าน</h3>
                    <p class="p1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero, exercitationem.</p>

                    <h4 class="address">ที่อยู่ร้าน</h4>
                    <p class="p1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero, exercitationem.</p>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d684.840511221341!2d100.7878557596282!3d13.836459168288247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6fa0d3e43c17%3A0x6900adb7c859f5c7!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4o-C5jOC4oSDguITguK3guKPguYzguJvguK3guYDguKPguIrguLHguYjguJkg4LiI4LmN4Liy4LiB4Lix4LiU!5e0!3m2!1sth!2sth!4v1721792305749!5m2!1sth!2sth" width="200" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <h4 class="datetime">วันทำการ</h4>
                    <p class="p1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero, exercitationem.</p>

                    <h4 class="time">เวลาทำการ</h4>
                    <p class="p1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero, exercitationem.</p>
                    
                    <h4 class="cont">ช่องทางติดต่อ</h4>
                    <p class="p1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero, exercitationem.</p>

                    <form action="">

                        <div class="dropDown">
                            <!-- <span><h5>ประเภท :</h5></span>
                            <select name="" id="">
                                <option value="1">เสื้อผ้า</option>
                                <option value="2">ชุดที่นอน</option>
                            </select> -->
                            <span><h5>ขนาด :</h5></span>
                            <select name="" id="">
                                <option value="small">S (ขนาดเล็ก)</option>
                                <option value="medium">M (ขนาดมาตรฐาน)</option>
                                <option value="big">L (ขนาดใหญ่)</option>
                            </select>

                            <div class="card-service">
                                <span><h5>บริการ :</h5></span>
                            <div class="card-container">
                                <input type="radio" name="card" id="card_one">
                                <input type="radio" name="card" id="card_two">
                                <input type="radio" name="card" id="card_three">
                                <input type="radio" name="card" id="card_four">
                                <input type="radio" name="card" id="card_five">
                                <!-- they should all have the same name attr but different ids -->
                                <label for="card_one">
                                    <div class="card">
                                        <div class="img">
                                            <img src="../photo/ตู้ซักผ้า2.jpg" alt="" class="img2">
                                        </div>
                                        <h5 class="title2">ซัก อบ</h5>
                                        <p class="p2">Lorem ipsum dolor sit amet consectetur.</p>
                                        <h5 class="title2">ประเภท</h5>
                                        <p class="p2">ผ้าม่านแบบบาง</p>
                                        <div class="price2">100 บาท</div>
                                    </div>
                                </label>
                                <label for="card_two">
                                    <div class="card">
                                        <div class="img">
                                            <img src="../photo/ตู้ซักผ้า2.jpg" alt="" class="img2">
                                        </div>
                                        <h5 class="title2">ซัก อบ </h5>
                                        <p class="p2">Lorem ipsum dolor sit amet consectetur.</p>
                                        <h5 class="title2">ประเภท</h5>
                                        <p class="p2">Topper 5 ฟุต</p>
                                        <div class="price2">120 บาท</div>
                                    </div>
                                </label>
                                <label for="card_three">
                                    <div class="card">
                                        <div class="img">
                                            <img src="../photo/ตู้ซักผ้า2.jpg" alt="" class="img2">
                                        </div>
                                        <h5 class="title2">ซัก อบ รีด</h5>
                                        <p class="p2">Lorem ipsum dolor sit amet consectetur.</p>
                                        <h5 class="title2">ประเภท</h5>
                                        <p class="p2">ชุดสูท ชุดทางการ</p>
                                        <div class="price2">80 บาท</div>
                                    </div>
                                </label>
                                <label for="card_four">
                                    <div class="card">
                                        <div class="img">
                                            <img src="../photo/ตู้ซักผ้า2.jpg" alt="" class="img2">
                                        </div>
                                        <h5 class="title2">ซัก อบ </h5>
                                        <p class="p2">Lorem ipsum dolor sit amet consectetur.</p>
                                        <h5 class="title2">ประเภท</h5>
                                        <p class="p2">เสื้อผ้า ชุดที่นอน</p>
                                        <div class="price2">60 บาท</div>
                                    </div>
                                </label>
                                
                            </div>
                        </div>
                        </div>
                        
                        <!-- <div class="quantity2">
                            <span> quantity : </span>
                            <input type="number" value="1" class="q">
                        </div> -->

                        <div class="tt">
                            <span><h5>หมายเหตุ : </h5></span>
                            <textarea name="textarea" id="textarea" cols="50" rows="3"></textarea>
                        </div>

                    </form>

                    <a href="#"><button class="btn2">ใส่ตระกร้า</button></a>
                    

                </div>

            </div>

        </div>

    <!-- ร้านค้าที่กดเลือก end -->

    <!-- review -->

        <section class="review" id="review">

            <h1 class="review-name">Review</h1> 

            <div class="swiper review-slider">

                <div class="swiper-wrapper">

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>

                    <div class="swiper-slide box3">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="">
                        <h3>ชื่อลูกค้า</h3>
                        <div class="stars">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                        <p>Lorem ipsum dolor sit amet.</p>
                    </div>
                </div>
            </div>
        </section>

    <!-- review end -->

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

        var swiper = new Swiper(".review-slider", {
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
                1080: {
                slidesPerView: 4,
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