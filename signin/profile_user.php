<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');
include('../signin/customer_info.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['save_customer'])) {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $lastname =$_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
    $user_id = $_SESSION['user_id']; // รับ customer_id จากเซสชัน

       // ตรวจสอบและอัพโหลดไฟล์ภาพ
       $imagePath = null;
       if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
           $target_dir = "../photo/";
           $imagePath = $target_dir . basename($_FILES["shop_image"]["name"]);
           move_uploaded_file($_FILES["shop_image"]["tmp_name"], $imagePath);
       } else {
           // หากไม่อัปโหลดไฟล์ใหม่ ให้ใช้ค่าเดิมที่มีอยู่ในฐานข้อมูล
           $query = "SELECT img FROM tb_customer WHERE id = ?";
           $stmt = $conn->prepare($query);
           $stmt->bind_param("i", $user_id);
           $stmt->execute();
           $stmt->bind_result($current_image);
           $stmt->fetch();
           $stmt->close();
           $imagePath = $current_image;
       }

    // ใช้คำสั่ง INSERT INTO เพื่อลงข้อมูลในฐานข้อมูล
    $query = "INSERT INTO tb_customer (id, name, lastname, phone, address, email, password, img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssss", $user_id, $name, $lastname, $phone, $address, $email, $password, $imagePath);

    if ($stmt->execute()) {
        echo "ข้อมูลถูกบันทึกเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
    }

    $stmt->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
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
            margin: auto;
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

    /* profile user */
    .container {
        margin-top: 100px;
        max-width: 90%;
        margin-left: auto;
        margin-right: auto;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .container h1 {
        font-size: 25px;
        text-align: center;
        margin-bottom: 20px;
        color: #507F99;
    }

    .form_profile {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;
    }

    .from_group {
        margin-bottom: 20px;
    }

    label {
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: #507F99;
    }

    input[type="text_name2"],
    input[type="text_lname"],
    input[type="text_phon"],
    input[type="text_email"],
    input[type="text_pass"],
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #507F99;
        border-radius: 10px;
        box-sizing: border-box;
        font-size: 16px;
    }

    textarea {
        resize: none;
    }

    .imglogo {
    grid-column: span 2;
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 225px;
    width: 225px;
    border: 1px solid #000;
    border-radius: 50%;
    position: relative; /* หรือ absolute ถ้าจำเป็น */
    z-index: 10; /* หรือค่าที่สูงกว่าองค์ประกอบอื่น */
}

    .form-group {
        grid-column: span 2;
        text-align: center;
    }

    iframe {
        grid-column: span 2;
        width: 100%;
        height: 300px;
        border: 0;
        border-radius: 10px;
    }

    button {
        outline: none;
        border: 1px solid #507F99;
        border-radius: 6px;
        cursor: pointer;
        padding: 10px 20px;
        color: #ffffff;
        background-color: #507F99;
        font-size: 14px;
    }

    button:hover {
        background-color: #728FCE;
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        grid-column: span 2;
        margin-top: 10px;
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
    <title>Profile</title>
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

<!-- form profile -->
<form method="POST" action="edit_user.php" enctype="multipart/form-data">
    <div class="container">
        <h1>ข้อมูลส่วนตัว</h1>
        <div class="form_profile">
        <div class="form-group">
            <?php 
                // กำหนดรูปภาพเริ่มต้นเป็น default.jpg หากไม่มีข้อมูลรูปภาพในฐานข้อมูล
                $customerimg = !empty($customer['img']) ? htmlspecialchars($customer['img']) : 'default.jpg';
            ?>
            <!-- แสดงรูปภาพโปรไฟล์ -->
            <img id="profile-image-form" src="../photo/<?php echo $img; ?>" alt="Profile Image" class="imglogo" disabled>

            <!-- Input file สำหรับอัปโหลดรูปภาพ -->
            <input type="file" id="input-file" name="shop_image" accept="image/*" class="inimg" style="display: none;">
            </div>

            <div class="from_group">
                <label for="name_shop" class="name">ชื่อ</label>
                <input type="text_name2" name="name" value="<?php echo $name; ?>" required disabled>
            </div>

            <div class="from_group">
                <label for="name_shop" class="name2">นามสกุล</label>
                <input type="text_lname" name="lastname" value="<?php echo $lastname; ?>" required disabled>
            </div>

            <div class="from_group">
                <label for="phon_shop">เบอร์โทรศัพท์</label>
                <input type="text_phon" name="phone" id="phone" value="<?php echo $phone; ?>" required disabled>
            </div>

            <div class="from_group">
                <label for="address">ที่อยู่</label>
                <textarea name="address" id="address" rows="4" required disabled><?php echo htmlspecialchars($address); ?></textarea>
            </div>

            <div class="from_group">
                <label for="address">พิกัด</label>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2739.3577164737044!2d100.7865171160615!3d13.836826733612302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6fa0d3e43c17%3A0x6900adb7c859f5c7!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4o-C5jOC4oSDguITguK3guKPguYzguJvguK3guYDguKPguIrguLHguYjguJkg4LiI4LmN4Liy4LiB4Lix4LiU!5e0!3m2!1sth!2sth!4v1720673706746!5m2!1sth!2sth" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
        </div>

        <h1>บัญชี</h1>
        <div class="form_profile">
            <div class="from_group">
                <label for="name_shop">Email</label>
                <input type="text_email" name="email" id="email" value="<?php echo $email; ?>" required disabled>
            </div>

            <div class="from_group">
                <label for="name_shop">Password</label>
                <input type="text_pass" name="password" id="password" value="<?php echo $password; ?>" required disabled>
            </div>
        </div>

        <div class="button-group">
            <button type="button" name="update_profile" id="edit-btn">แก้ไข</button>
            <button type="submit" name="save_customer" style="display: none;" id="save-btn">บันทึก</button>
        </div>
    </div>
</form>
<!-- form profile end -->

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

    <!-- แก้ไขข้อมูลส่วนตัว -->


    <script>
// แก้ไขรูปภาพในโปรไฟล์
document.getElementById('edit-btn').addEventListener('click', function() {
    // เปิดใช้งาน input และ textarea ทุกช่อง
    document.querySelectorAll('input, textarea').forEach(function(element) {
        element.disabled = false;
    });

    // ทำให้รูปภาพคลิกได้
    document.getElementById('profile-image-form').style.cursor = 'pointer';

    // แสดงปุ่มบันทึกและซ่อนปุ่มแก้ไข
    document.getElementById('save-btn').style.display = 'block';
    document.getElementById('edit-btn').style.display = 'none';

    // เปิดให้คลิกที่รูปภาพเพื่อเปลี่ยนรูป
    document.getElementById('profile-image-form').addEventListener('click', function() {
        document.getElementById('input-file').click();
    });
});

// เมื่อมีการเลือกไฟล์ใหม่
document.getElementById('input-file').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // แสดงรูปภาพที่เลือกใหม่
            document.getElementById('profile-image-form').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

    </script>



    

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
