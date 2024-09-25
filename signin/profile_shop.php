<?php
// เริ่มต้นเซสชัน
session_start();
include('../inc/server.php');
include('../signin/shop_info.php');

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if (isset($_POST['save_shop'])) {
    // รับข้อมูลจากฟอร์ม
    $nameshop = $_POST['nameshop'];
    $name = $_POST['shop_name'];
    $lastname = $_POST['shop_lastname'];
    $phone = $_POST['shop_phone'];
    $sex = $_POST['shop_sex'];
    $age = $_POST['shop_age'];
    $job = $_POST['shop_job'];
    $details = $_POST['shop_details'];
    $address = $_POST['shop_address'];
    $email = $_POST['shop_email'];
    $password = $_POST['shop_pass']; // เข้ารหัสรหัสผ่าน
    $shop_id = $_SESSION['shop_id']; // รับ shop_id จากเซสชัน

    // เข้ารหัสรหัสผ่านก่อนบันทึกในฐานข้อมูล
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ตรวจสอบและอัพโหลดไฟล์ภาพ
    $imagePath = null;
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../photo/";
        $imagePath = $target_dir . basename($_FILES["shop_image"]["name"]);
        move_uploaded_file($_FILES["shop_image"]["tmp_name"], $imagePath);
    } else {
        // หากไม่อัปโหลดไฟล์ใหม่ ให้ใช้ค่าเดิมที่มีอยู่ในฐานข้อมูล
        $query = "SELECT shop_img FROM tb_shop WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $shop_id);
        $stmt->execute();
        $stmt->bind_result($current_image);
        $stmt->fetch();
        $stmt->close();
        $imagePath = $current_image; // ใช้ค่าเดิมที่ดึงมาจากฐานข้อมูล
    }

    // ใช้คำสั่ง INSERT INTO เพื่อลงข้อมูลในฐานข้อมูล
    $query = "INSERT INTO tb_shop (id, nameshop, shop_name, shop_lastname, shop_phone, shop_sex, shop_age, shop_job, shop_details, shop_address, shop_email, shop_pass, shop_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt->bind_param("issssssssssss", $shop_id, $nameshop, $name, $lastname, $phone, $sex, $age, $job, $details, $address, $email, $hashedPassword, $imagePath);

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

        /* profile shop*/

        .container {
            margin-top: 100px;
            max-width: 100%;
        }

        .container h1 {
            font-size: 25px;
            text-align: center;
        }

        .container .form_profile {
            padding: 20px 20px;
        }

        .from_group {
            margin-bottom: 20px;
        }

        label {
            font-size:15px;
        }
        
        input[type="text_name"],
        input[type="text_name2"],
        input[type="text_lname"],
        input[type="text_phon"],
        input[type="text_payment"],
        input[type="text_email"],
        input[type="text_pass"],
        input[type="text_sex"],
        input[type="text_age"],
        input[type="text_job"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #507F99;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 16px;
            display: flex;
        }

        .list-group {
            display: grid;
            grid-template-columns:repeat(3,1fr);
            font-size: 14px;
            margin-top: 5px;
        }

        input[type="time"] {
            padding: 10px 20px;
            margin:0 5px;
            outline:none;
            border:1px solid #507F99;
            border-radius:6px;
            color:#0298cf;
            font-size: 14px;
            background-color: #fff;
        }

        ul li {
            list-style: none;
        }

        .imglogo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            height: 300px;
            width: 300px;
            border: 1px solid #000;
            border-radius: 5px;
            align-items: center;
            display: flex;
        }

        .if {
            display: block;
            width: 200px;
            background:#507F99;
            color: #fff;
            padding: 10px;
            margin: 10px auto;
            border-radius:5px ;
            cursor: pointer;
            text-align: center;
        }

        .inimg {
            display: none;
        }

        iframe {
            max-width: 100%;
            display: block;
        }

        button {
            outline:none;
            border:1px solid #507F99;
            border-radius:6px;
            cursor:pointer;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #507F99;
            font-size: 14px;
            float: right;
            margin: 5px;
        }

        button:hover {
            background-color: #728FCE;
        }

        button:nth-child(1) {
            background-color: #AEB6BF;
        }

        button:nth-child(2) {
            background-color: #507F99;
        }

        
    </style>
    <title>profile shop</title>
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
        $shopimg = !empty($shop['shop_img']) ? htmlspecialchars($shop['shop_img']) : 'default.jpg'; 
        ?>
            <img src="../photo/<?php echo $shopimg; ?>" alt="Profile Image">
            <h3><?php echo $fullName; ?></h3>
            <span>shop</span>
            <a href="profile_shop.php" class="btnp">Profile</a>
            <div class="flex-btnp">
                <a href="report_shop.php" class="option-btnp">รายงาน</a>
                <a href="login.php" class="option-btnp">Logout</a>
            </div>
        </div>
        </header> 


        <!-- form profile -->
        <form method="POST" action="edit_shop.php" enctype="multipart/form-data">
            <div class="container">
                <h1>ข้อมูลร้านค้า</h1>
                <div class="form_profile">
                    <div class="form-group">
                    <?php 
                    // กำหนดรูปภาพเริ่มต้นเป็น default.jpg หากไม่มีข้อมูลรูปภาพในฐานข้อมูล
                        $shopimg = !empty($shop['shop_img']) ? htmlspecialchars($shop['shop_img']) : 'default.jpg';
                    ?>
                        <!-- แสดงรูปภาพโปรไฟล์ -->
                        <img id="profile-image-form" src="../photo/<?php echo $shopimg; ?>" alt="Profile Image" class="imglogo" disabled>

                        <input type="file" id="input-file" name="shop_image" accept="image/*" class="inimg">
                    </div>
                    <div class="from_group">
                        <label for="name_shop">ชื่อร้าน</label>
                        <input type="text_name" name="nameshop" id="name_shop" value="<?php echo $nameshop ?>" required disabled>
                    </div>
                    <div class="from_group">
                        <label for="name_shop" class="name">ชื่อ</label>
                        <input type="text_name2" name = "shop_name" value="<?php echo $name; ?>" disabled>
                        <label for="name_shop" class="name2">นามสกุล</label>
                        <input type="text_lname" name = "shop_lastname" value="<?php echo $lastname; ?>" disabled>
                    </div>
                    <div class="from_group">
                        <label for="phon_shop">เบอร์โทรศัพท์</label>
                        <input type="text_phon" name = "shop_phone"value="<?php echo $phone ?>" disabled>

                        <label for="sex_shop">เพศ</label>
                        <input type="text_sex" name = "shop_sex"value="<?php echo $sex ?>" disabled>

                        <label for="age_shop">อายุ</label>
                        <input type="text_age" name = "shop_age"value="<?php echo $age ?>" disabled>

                        <label for="job_shop">อาชีพ</label>
                        <input type="text_job" name = "shop_job"value="<?php echo $job ?>" disabled>
                    </div>
                    <div class="from_group">
                        <label for="date">วันทำการ</label>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันอาทิตย์</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันจันทร์</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันอังคาร</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันพุธ</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันพฤหัสบดี</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันศุกร์</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="" id="firstCheckbox">
                                <label class="form-check-label" for="firstCheckbox">วันเสาร์</label>
                            </li>
                        </ul>
                    </div>
                    <div class="from_group">
                        <label for="time_1" type="time">เวลาทำการ</label>
                        <input type="time" name="time" id="time"> <span> - </span>
                        <input type="time" name="time" id="time">
                    </div>
                    <div class="from_group">
                        <label for="data">รายละเอียด</label>
                        <textarea id="data" name="shop_details" rows="4" required disabled><?php echo htmlspecialchars($details ); ?></textarea>
                    </div>
                    <div class="from_group">
                        <label for="address">ที่อยู่ร้าน</label>
                        <textarea name="shop_address" id="address" rows="4" required disabled><?php echo $address; ?></textarea>
                    </div>
                    <div class="from_group">
                        <label for="address">พิกัด</label>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2739.3577164737044!2d100.7865171160615!3d13.836826733612302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6fa0d3e43c17%3A0x6900adb7c859f5c7!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4reC4suC4o-C5jOC4oSDguITguK3guKPguYzguJvguK3guYDguKPguIrguLHguYjguJkg4LiI4LmN4Liy4LiB4Lix4LiU!5e0!3m2!1sth!2sth!4v1720673706746!5m2!1sth!2sth" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <h1>บัญชีธนาคาร</h1>
                <div class="form_profile">
                    <div class="from_group">
                        <label for="name_shop">ชื่อบัญชีธนาคาร</label>
                        <input type="text_payment">
                    </div>
                    <div class="from_group">
                        <label for="name_shop">เลขบัญชีธนาคาร</label>
                        <input type="text_payment">
                    </div>
                    <div class="form-group">
                        <label for="input-file" class="if">update QR Code</label>
                        <input type="file" id="input-file" accept="image/*" class="inimg">
                        <img src="../photo/ตู้ซักผ้า2.jpg" alt="" class="imglogo">
                    </div>

                    <h1>บัญชีส่วนตัว</h1>
                    <div class="from_group">
                        <label for="name_shop">Email</label>
                        <input type="text_email" name = "shop_email" value="<?php echo $email ?>" disabled>
                    </div>
                    <div class="from_group">
                        <label for="name_shop">Password</label>
                        <input type="text_pass" name = "shop_pass" value = "<?php echo $password ?>" disabled>
                    </div>

                    <div class="form-group">
                    <button type="button" name="update_profile" id="edit-btn">แก้ไข</button>
                    <button type="submit" name="save_shop" style="display: none;" id="save-btn">บันทึก</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- form profile end-->


   
    











    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!--custom js file link -->
        <script src="js/script.js"></script>


         <!-- แก้ไขข้อมูลส่วนตัว -->


    <script>
    // แก้ไขรูปภาพในโปรไฟล์
document.getElementById('edit-btn').addEventListener('click', function() {
    // เปิดใช้งาน input และ textarea ทุกช่องในฟอร์ม
    document.querySelectorAll('input, textarea').forEach(function(element) {
        element.disabled = false;
    });

    // ทำให้รูปภาพคลิกได้
    document.querySelectorAll('.imglogo').forEach(function(imgElement) {
        imgElement.style.cursor = 'pointer';
    });

    // แสดงปุ่มบันทึกและซ่อนปุ่มแก้ไข
    document.getElementById('save-btn').style.display = 'block';
    document.getElementById('edit-btn').style.display = 'none';

    // เปิดให้คลิกที่รูปภาพเพื่อเปลี่ยนรูป
    document.querySelectorAll('.imglogo').forEach(function(imgElement, index) {
        imgElement.addEventListener('click', function() {
            if (index === 0) {
                document.getElementById('input-file').click(); // เปิดฟอร์มเลือกรูปภาพสำหรับโปรไฟล์ร้าน
            } else if (index === 1) {
                document.querySelector('input[name="shop_image"]').click(); // เปิดฟอร์มเลือกรูปภาพ QR Code
            }
        });
    });
});

// เมื่อมีการเลือกไฟล์ใหม่สำหรับโปรไฟล์ร้าน
document.getElementById('input-file').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // แสดงรูปภาพโปรไฟล์ที่เลือกใหม่
            document.querySelector('.imglogo').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// เมื่อมีการเลือกไฟล์ใหม่สำหรับ QR Code
document.querySelector('input[name="shop_image"]').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // แสดง QR Code ที่เลือกใหม่
            document.querySelectorAll('.imglogo')[1].src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

</script>







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
