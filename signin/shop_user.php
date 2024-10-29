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
// SQL Query เพื่อดึงข้อมูลจาก tb_shop
$query = "SELECT * FROM tb_shop";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
        .swiper-slide.box {
    display: flex;
    flex-direction: column;
    align-items: center; /* จัดกลางเนื้อหาในแนวตั้ง */
    justify-content: center; /* จัดกลางเนื้อหาในแนวนอน */
    padding: 10px; /* เพิ่มระยะห่างรอบๆ */
    box-sizing: border-box; /* ให้การคำนวณขนาดรวม padding */
}

.swiper-slide.box img {
    max-width: 100%; /* ทำให้รูปภาพไม่เกินขอบ */
    height: auto; /* รักษาสัดส่วน */
    border-radius: 10px; /* ปรับขอบให้มน */
}
.products {
    display: flex;
    justify-content: center; /* จัดกึ่งกลางแนวนอน */
    align-items: center; /* จัดกึ่งกลางแนวตั้ง */
    min-height: 100vh; /* ตั้งค่าให้ความสูงของส่วนนี้เต็มหน้าจอ */
    padding: 20px;
    background-color: #f9f9f9; /* สีพื้นหลัง */
}
.swiper {
    width: 100%;
    max-width: 1200px; /* จำกัดความกว้างสูงสุด */
}


        /* Footer */
        .footer {
    background-color: #507F99; /* Main background color */
    color: #ffffff; /* Text color */
    padding: 3rem 0;
    text-align: center;
}

.footer-content {
    display: flex;
    justify-content: space-around; /* Evenly distribute space between sections */
    flex-wrap: wrap; /* Wrap sections on smaller screens */
    max-width: 1200px;
    margin: 0 auto;
}

.footer-section {
    flex: 1;
    margin: 1rem;
    min-width: 250px; /* Minimum width for responsiveness */
}

.footer-section h2 {
    color: #ffffff; /* Heading color */
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.footer-section p, .footer-section ul {
    color: #f0f0f0; /* Light text color for better contrast */
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 0.5rem 0;
}

.footer-section ul li a {
    color: #f0f0f0; /* Link color */
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #d1e2eb; /* Slightly lighter color on hover */
}

.footer-section.social {
    text-align: center;
    margin-top: 2rem;
}

.footer-section.social a {
    display: inline-block;
    margin: 0 10px;
    width: 50px;
    height: 50px;
    line-height: 50px;
    border-radius: 50%;
    background-color: #ffffff; /* Background color for icons */
    color: #507F99; /* Icon color */
    font-size: 1.5rem;
    transition: all 0.3s ease;
    text-align: center;
    position: relative;
}

.footer-section.social a:hover {
    background-color: #d1e2eb; /* Hover background */
    transform: translateY(-5px) scale(1.1); /* Lift effect */
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Shadow effect */
}

.footer-bottom {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #ffffff; /* Subtle top border */
    color: #f0f0f0; /* Footer bottom text color */
    font-size: 0.9rem;
}

/* Responsive styles */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column; /* Stack sections vertically */
        align-items: center; /* Center align sections */
    }

    .footer-section {
        margin-bottom: 2rem; /* Increase spacing between sections */
        max-width: 80%; /* Limit width for better readability */
    }

    .footer-section.social a {
        margin: 0 5px; /* Reduce margin between icons */
        width: 40px; /* Reduce icon size */
        height: 40px;
        line-height: 40px;
    }
}

@media (max-width: 480px) {
    .footer-section h2 {
        font-size: 1.5rem; /* Smaller font size for headings */
    }

    .footer-bottom {
        font-size: 0.8rem; /* Smaller font size for footer bottom text */
    }

    .footer-section.social a {
        width: 35px; /* Further reduce icon size */
        height: 35px;
        line-height: 35px;
        font-size: 1.2rem; /* Reduce icon font size */
    }
}



@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.footer {
    animation: fadeIn 1s ease-in-out;
}

.footer .social a {
    transition: transform 0.3s, color 0.3s;
}

.footer .social a:hover {
    transform: translateY(-5px) scale(1.2);
    color: #ff7800;
}

  /* รายการในตะกร้า */
  #cart-items-container {
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 20px;
    padding-right: 10px; /* เพิ่ม Padding เพื่อหลีกเลี่ยงการชนของ Scrollbar */
}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.cart-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 15px;
}

.item-info {
    flex-grow: 1;
    text-align: left;
}

.item-info h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.item-info .price {
    color: #666;
}

/* Total และค่าจัดส่ง */
.total-info {
    font-size: 18px;
    margin-bottom: 20px;
    font-weight: bold;
    color: #333;
    text-align: center;
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
    <div id="cart-items"></div> <!-- ส่วนนี้จะถูกอัปเดตเมื่อมีสินค้าถูกเพิ่มเข้ามา -->
    <div class="total"> Total : <span id="cart-total">0฿</span></div>
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

    

    <!-- section ของตัวเลือกที่ต้องการดู -->
    <section class="products" id="products">
    <div class="swiper product-slider">
        <div class="swiper-wrapper">
            <?php
            // แสดงข้อมูลร้านแต่ละร้าน
            while ($shop = mysqli_fetch_assoc($result)) {
                $shopName = htmlspecialchars($shop['nameshop']);
                $shopImage = htmlspecialchars($shop['shop_img']);
                $shopDetails = htmlspecialchars($shop['shop_details']);
                $latitude = htmlspecialchars($shop['latitude']);
                $longitude = htmlspecialchars($shop['longitude']);
                $shopAddress = htmlspecialchars($shop['shop_address']);
                $googleMapsUrl = "https://www.google.com/maps?q={$latitude},{$longitude}";
            ?>
                <div class="swiper-slide box">
                    <!-- รูปภาพของร้าน -->
                    <img src="../photo/<?php echo $shopImage; ?>" alt="">

                    <!-- ชื่อร้าน -->
                    <h3><?php echo $shopName; ?></h3>

                    <!-- รายละเอียด -->
                     <h2><?php echo $shopDetails; ?></h2>
                     <h1>50.00THB - 100THB</h1>
                     <p>(4.7)</p>



                    <!-- ลิงก์ไปยัง Google Maps -->
                    <!-- <div class="gps">
                        <a href="javascript:void(0);" onclick="showMap('<?php echo $latitude; ?>''<?php echo $longitude; ?>')">พิกัด</a>
                        <a href="<?php echo $googleMapsUrl; ?>" target="_blank">ดูบน Google Maps</a>
                    </div> -->

                    <!-- ลิงก์สำหรับเลือกร้าน -->
                    <a href="shop2_user.php?id=<?php echo $shop['id']; ?>" class="btn">เลือก</a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

         <!-- popup-->

         <div class="popup2" id="popup-2">
    <div class="overlay1"></div>
    <div class="content5">
        <div class="close-btn" onclick="togglePopup()">&times;</div>
        
        <!-- หัวข้อแสดงคำว่า "รายการ" ที่ด้านบนสุด -->
        <h1 class="header-title">รายการ</h1>

        <!-- รายการสินค้า -->
        <div id="cart-items-container">
            <div class="cart-item">
                <div class="item-image">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="item-info">
                    <h3>${item.name}</h3>
                    <span class="price">${item.price}฿</span>
                </div>
            </div>
        </div>

        <!-- Total และ QR Code -->
        <div class="total-info">
            <!-- Total info will be injected here -->
        </div>
        <div class="qr-code-container" id="qr-code-container">
            <!-- QR code will be generated here -->
        </div>
    </div>
</div>
    <!-- popup end-->

         
 <!-- Footer Section -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h2>About Us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eleifend libero nec libero convallis, id molestie nulla dignissim.</p>
        </div>
        <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-section social">
            <h2>Follow Us</h2>
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-line"></i></a>
            <a href="#"><i class="fa-regular fa-envelope"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2024 Laundry Service | Designed by YourName
    </div>
</footer>
<!-- Footer End -->





    



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
// Function to toggle popup for checkout
function togglePopup() {
    const popup = document.getElementById("popup-2");
    const shoppingCart = document.querySelector('.shopping-cart'); // เลือก shopping-cart
    const isActive = popup.classList.contains("active");

    // If the popup is currently active, close it
    if (isActive) {
        popup.classList.remove("active");
        return; // Exit the function
    }

    // Clear previous content
    const popupContent = document.querySelector('.content5');
    popupContent.innerHTML = ''; // Clear previous content in popup

    // If there are no items in the cart, notify the user
    if (userCart.length === 0) {
        popupContent.innerHTML = '<p>ไม่มีสินค้าในตะกร้า</p>'; // "No items in the cart"
        popup.classList.add("active"); // Show popup if cart is empty
        return;
    }

    let total = 0;
    const shippingFee = 50; // Assuming a static shipping fee

    // Add header title (แสดงคำว่า "รายการ" แค่ครั้งเดียว)
    popupContent.innerHTML += `<h1 class="header-title">รายการ</h1>`;

    // Generate checkout content
    userCart.forEach(item => {
        total += parseFloat(item.price);
        
        // Append item details to popup content
        popupContent.innerHTML += `
        <div class="cart-item">
            <div class="item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="item-info">
                <h3>${item.name}</h3>
                <span class="price">${item.price}฿</span>
            </div>
        </div>
        `;
    });

    // Add shipping fee and total to popup
    total += shippingFee; // Include shipping fee in total

    popupContent.innerHTML += `
    <div class="total-info">
        <p>ค่าจัดส่ง: <span id="shipping-fee">${shippingFee}฿</span></p>
        <p>รวม: <span id="total-price">${total}฿</span></p>
    </div>
    `;

    // Add QR code container below total
    popupContent.innerHTML += `
    <div class="qr-code-container" id="qr-code-container"></div>
    `;

    // Add close button at the top of the popup
    popupContent.innerHTML = `
        <div class="close-btn" onclick="togglePopup()">&times;</div>
        ${popupContent.innerHTML}  <!-- Insert previous content below the close button -->
    `;

    // Show popup
    popup.classList.add("active");

    // Hide the shopping cart when the popup is shown
    shoppingCart.classList.remove('active'); // ซ่อน shopping-cart

    // Generate QR Code for the total price
    const qrData = "test qr code"; // ข้อมูลใน QR code (เช่น จำนวนเงินที่ต้องชำระ)
    console.log("QR Data: ", qrData); // Log QR Data

    try {
        // Generate QR Code
        const qrCodeContainer = document.getElementById("qr-code-container");
        qrCodeContainer.innerHTML = ''; // Clear previous QR Code
        new QRCode(qrCodeContainer, {
            text: qrData,
            width: 128,
            height: 128
        });
    } catch (error) {
        console.error("Error generating QR Code:", error);
        alert("เกิดข้อผิดพลาดในการสร้าง QR Code กรุณาลองใหม่อีกครั้ง");
    }
}

    // Function to remove item from cart
    function removeFromCart(index) {
        userCart.splice(index, 1); // Remove item at index
        updateCart(userCart); // Update cart display
    }

    // Retrieve data from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || {};
        let user_id = <?php echo json_encode($user_id); ?>; // Retrieve user_id from PHP
        userCart = cartItems[user_id] || []; // If there's no cart data, make it an empty array

        // Update the display
        updateCart(userCart);
    });

    function updateCart(cartItems) {
        const cartItemsContainer = document.getElementById('cart-items');
        let cartHTML = '';
        let total = 0;

        cartItems.forEach((item, index) => {
            cartHTML += `
                <div class="box">
                    <i class='bx bx-trash' onclick="removeFromCart(${index})"></i>
                    <img src="${item.image}" alt="${item.name}">
                    <div class="content">
                        <h3>${item.name}</h3>
                        <span class="price">${item.price}฿</span>
                    </div>
                </div>
            `;
            total += parseFloat(item.price);
        });

        cartItemsContainer.innerHTML = cartHTML;
        document.getElementById('cart-total').textContent = total + '฿';
    }

    // Show cart data when the page loads
    updateCart(userCart);
</script>

</body>
</html>
