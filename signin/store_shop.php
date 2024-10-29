<?php
session_start();
include('../inc/server.php');

// ดึง shop_id จาก session
$shopid = $_SESSION['shop_id'];

// จัดการการอัปโหลดไฟล์
$target_dir = "uploads/";
$fileTmpPath = $_FILES["image"]["tmp_name"];
$imageData = file_get_contents($fileTmpPath); // อ่านเนื้อหาของไฟล์ภาพ

// ตรวจสอบว่ามีข้อมูลภาพที่อัปโหลดหรือไม่
if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // เตรียมและผูกข้อมูล
    $stmt = $conn->prepare("INSERT INTO tb_product (product_img, product_name, product_type, product_details, product_price, shop_id) VALUES (?, ?, ?, ?, ?, ?)");
    
    // ใช้ "b" สำหรับ BLOB (Binary Large Object) 
    $stmt->bind_param("bssssi", $imageData, $product_name, $product_type, $product_details, $product_price, $shopid);
    
    // ส่งข้อมูลภาพ
    $stmt->send_long_data(0, $imageData);
    
    // ดำเนินการคำสั่ง
    if ($stmt->execute()) {
        echo "เพิ่มผลิตภัณฑ์ใหม่สำเร็จ.";
    } else {
        echo "ข้อผิดพลาด: " . $stmt->error;
    }
    
    // ปิดคำสั่ง
    $stmt->close();
} else {
    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES['image']['error'];
}

// ดึงข้อมูลผลิตภัณฑ์จากฐานข้อมูล
$sql = "SELECT * FROM `tb_product` WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shopid);
$stmt->execute();
$result = $stmt->get_result();

// ดึงข้อมูลชื่อและนามสกุลจากตาราง tb_shop
$query = "SELECT * FROM tb_shop WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shopid);
$stmt->execute();
$result2 = $stmt->get_result();

if ($result2->num_rows > 0) {
    $shop = $result2->fetch_assoc();
    $fullName = htmlspecialchars($shop["shop_name"]) . " " . htmlspecialchars($shop["shop_lastname"]);
    $profileImage = htmlspecialchars($shop['shop_img']);
} else {
    $fullName = "User not found";
    $profileImage = "default-image.png"; // กรณีที่ไม่พบผู้ใช้
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>s
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

            .popup .content {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 70px;
            }

            .popup .name-report label,
            .popup .name-report span input {
                font-size: 14px;
                width: 100%;
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

            .popup .content {
                width: 90%;
                max-width: 90%;
                padding: 15px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 50px;
            }

            .popup .name-report label,
            .popup .name-report span {
                font-size: 14px;
            }

        }

        @media (max-width: 450px) {
            html {
                font-size: 50%;
            }

            .popup .content {
                padding: 5px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 30px;
            }

            .popup .name-report label,
            .popup .name-report span {
                font-size: 12px;
            }

            .popup.active {
                width: 100%;
                height: 100%;
            }

            .popup2 .content2 {
                padding: 5px;
                border-radius: 5px;
                overflow-y: auto;
                max-height: 80%;
                margin-top: 30px;
                max-width: 350px;
            }

            .popup2 .name-report2 label,
            .popup2 .name-report2 span {
                font-size: 12px;
            }

            .popup2.active {
                width: 100%;
                height: 100%;
            }
        }

        .table {
    margin: 8rem auto; /* Adds space between the header and the table */
    width: 90%;
    box-shadow: var(--box-shadow);
    background: var(--white);
    border-radius: .5rem;
    padding: 2rem;
}

.table_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.table_header h1 {
    font-size: 2.4rem;
    color: var(--black);
}

.table_header input {
    padding: 0.8rem 1rem;
    font-size: 1.4rem;
    border: 1px solid var(--light-color);
    border-radius: 0.5rem;
}

.table_header .add_new {
    background: #507F99;
    color: #fff;
    border: none;
    padding: 0.8rem 1.5rem;
    font-size: 1.6rem;
    border-radius: 0.5rem;
    cursor: pointer;
}

.table_header .add_new:hover {
    background: #728FCE;
}

/* Table styling */
.table_section table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.table_section table th,
.table_section table td {
    padding: 1rem;
    border-bottom: 1px solid var(--light-color);
    font-size: 1.6rem;
}

.table_section table th {
    background: #f4f4f4;
    color: var(--black);
}

.table_section table td img {
    width: 100px; /* Fixed width for images */
    height: 100px; /* Fixed height for images */
    object-fit: cover; /* Ensures the image fits within the dimensions without distortion */
    border-radius: 0.5rem;
}

.table_section table td button {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 2rem;
    color: var(--black);
}

.table_section table td button:hover {
    color: #507F99;
}

/* Media Queries for Responsiveness */
@media (max-width: 991px) {
    .table {
        width: 95%;
    }

    .table_section table th,
    .table_section table td {
        font-size: 1.4rem;
        padding: 0.8rem;
    }

    .table_header h1 {
        font-size: 2rem;
    }

    .table_header input {
        font-size: 1.3rem;
    }

    .table_header .add_new {
        font-size: 1.4rem;
    }
}

@media (max-width: 768px) {
    .table {
        margin-top: 12rem;
        width: 100%;
        padding: 1.5rem;
    }

    .table_section table,
    .table_section table thead,
    .table_section table tbody,
    .table_section table th,
    .table_section table td,
    .table_section table tr {
        display: block;
    }

    .table_section table th {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    .table_section table tr {
        border: 1px solid var(--light-color);
        margin-bottom: 1rem;
    }
    .table_section table tr:hover {
    background-color: rgba(80, 127, 153, 0.1); /* Light background on hover */
    transition: background-color 0.3s ease; /* Smooth hover transition */
    }
    .table_section table td button:hover {
    color: #507F99;
    transform: scale(1.1); /* Slight scaling effect on hover for buttons */
    transition: all 0.3s ease; /* Smooth hover transition */
    }

    .table_section table td {
        padding: 1rem;
        border-bottom: none;
        position: relative;
        font-size: 1.4rem;
    }

    .table_section table td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 1.5rem;
        font-weight: bold;
        text-transform: capitalize;
    }

    .table_section table td img {
        max-width: 100px;
    }
}
@media (max-width: 324px) {
    .table {
        margin-top: 15rem;
        width: 100%;
        padding: 1rem;
    }

    .table_header h1 {
        font-size: 1.6rem; /* Adjust the header font size */
    }

    .table_header input {
        width: 100%; /* Make the input take full width */
        font-size: 1.2rem; /* Adjust the font size for input */
    }

    .table_header .add_new {
        font-size: 1.2rem; /* Smaller button text size */
        padding: 0.6rem 1rem; /* Adjust padding for smaller button */
    }

    .table_section table,
    .table_section table thead,
    .table_section table tbody,
    .table_section table th,
    .table_section table td,
    .table_section table tr {
        display: block;
    }

    .table_section table tr {
        border: 1px solid var(--light-color);
        margin-bottom: 1rem;
    }

    .table_section table th {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    .table_section table td {
        padding: 0.8rem;
        border-bottom: none;
        position: relative;
        font-size: 1.2rem; /* Smaller font size for table data */
    }

    .table_section table td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 1rem;
        font-weight: bold;
        text-transform: capitalize;
    }

    .table_section table td img {
        max-width: 80px; /* Reduce image size slightly */
        height: 80px; /* Keep height proportional */
    }

    .table_section table td button {
        font-size: 1.6rem; /* Adjust button size */
    }

    .table_section table td button:hover {
        transform: scale(1.05); /* Slightly smaller hover effect */
    }
}



        ::placeholder {
            color: #0298cf;
        }

        ::-webkit-scrollbar {
            height: 5px;
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }


        /* popup */

        #popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: none;
        }

        #popup.active {
            display: block;
        }

        .overlay {
            width: 1000vw;
            height: 1000vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            height: 585px;
            z-index: 2;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        .popup .close-btn {
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

        .popup.active .overlay {
            display: block;
        }

        .popup.active .content {
            transition: all 300ms ease-in-out;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup .content h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup .content .name-report {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
        }

        .popup .content .name-report label {
            width: 100px;
            font-size: 14px;
        }

        .popup .content .name-report span {
            width: 100%;
            height: 100%;
        }

        .popup .content a {
            font-size: 14px;
        }

        .btn-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-container .btn-submit {
            padding: 0.5rem 1.5rem;
            margin: 0 1rem;
            border-radius: 50px;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 500;
            background-color: #507F99;
            color: var(--white);
            cursor: pointer;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #507F99;
            border-radius: 4px;
            margin-left: -5px;
        }

        /* popup2 */

        #popup2 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            display: none;
        }

        #popup2.active {
            display: block;
        }

        .overlay2 {
            width: 1000vw;
            height: 1000vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
            display: none;
        }

        .popup2 .content2 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: #fff;
            width: 450px;
            height: 585px;
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

        .popup2.active .overlay2 {
            display: block;
        }

        .popup2.active .content2 {
            transition: all 300ms ease-in-out;
            transform: translate(-50%, -50%) scale(1);
        }

        .popup2 .content2 h1 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 10px;

        }

        .popup2 .content2 .name-report2 {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            width: 100%;
        }

        .popup2 .content2 .name-report2 label {
            width: 100px;
            font-size: 14px;
        }

        .popup2 .content2 .name-report2 span {
            width: 100%;
            height: 100%;
        }

        .popup2 .content2 a {
            font-size: 14px;
        }

        .btn-container2 {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-container2 .btn-submit2 {
            padding: 0.5rem 1.5rem;
            margin: 0 1rem;
            border-radius: 50px;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 500;
            background-color: #507F99;
            color: var(--white);
            cursor: pointer;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #507F99;
            border-radius: 4px;
            margin-left: -5px;
        }
    </style>
    <title>store shop</title>
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


    <!-- product -->

    <div class="table">
    <div class="table_header">
        <h1>Product</h1>
        <div>
            <input placeholder="product" />
            <button class="add_new" onclick="add()">+ Add New</button>
        </div>
    </div>
    <div class="table_section">
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รูปภาพบริการ</th> <!-- Column for product image -->
                    <th>ชื่อบริการ</th> <!-- Column for product name -->
                    <th>ประเภท</th> <!-- Column for product type -->
                    <th>รายละเอียด</th> <!-- Column for product details -->
                    <th>ราคา</th> <!-- Column for product price -->
                    <th>การจัดการ</th> <!-- Column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Assuming $result is your fetched data from the database
                if ($result->num_rows > 0) {
                    $index = 1; // Counter for order
                    while ($row = $result->fetch_assoc()) {
                        // Assuming 'product_img' contains the image binary data
                        $imageData = base64_encode($row['product_img']);
                        $src = 'data:image/jpeg;base64,' . $imageData;

                        // Creating a row for each product
                        echo "<tr>
                                <td>{$index}</td> <!-- Displaying the order number -->
                                <td><img src='{$src}' alt='Image' style='max-width: 100px;'></td> <!-- Displaying the image -->
                                <td>{$row['product_name']}</td> <!-- Displaying the product name -->
                                <td>{$row['product_type']}</td> <!-- Displaying the product type -->
                                <td>{$row['product_details']}</td> <!-- Displaying product details -->
                                <td>{$row['product_price']} บาท</td> <!-- Displaying product price -->
                                <td>
                                    <button onclick='solve({$row['product_id']})'><i class='bx bx-edit-alt'></i></button>
                                    <button onclick='confirmDelete({$row['product_id']})'><i class='bx bx-trash'></i></button>
                                </td>
                              </tr>";
                        $index++; // Incrementing the counter for order
                    }
                } else {
                    echo "<tr><td colspan='7'>ไม่มีข้อมูลสินค้า</td></tr>"; // Message when no results found
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



    <!-- product end -->


    <!-- popup-->

    <div class="popup" id="popup">
        <div class="overlay"></div>
        <div class="content">
            <div class="close-btn" onclick="add()">&times;</div>
            <h1>Add new Product</h1>
            <form action="add_product.php" method="post" name="form1" enctype="multipart/form-data">
                <div class="name-report">
                    <label for="image">Product Image: </label>
                    <span><input type="file" id="image" name="image" accept="image/*" required></span>
                </div>
                <div class="name-report">
                    <label for="product_name">ชื่อบริการ: </label>
                    <span><input type="text" id="product_name" name="product_name" required></span>
                </div>
                <div class="name-report">
                    <label for="product_type">ประเภท: </label>
                    <span><input type="text" id="product_type" name="product_type" required></span>
                </div>
                <div class="name-report">
                    <label for="product_details">รายละเอียด: </label>
                    <span><input type="text" id="product_details" name="product_details" required></span>
                </div>
                <div class="name-report">
                    <label for="product_price">ราคา: </label>
                    <span><input type="text" id="product_price" name="product_price" required></span>
                </div>
                <div class="btn-container">
                    <!-- <button type="button" class="btn-submit" onclick="submitForm()">บันทึก</button> -->
                    <button type="submit" class="btn-submit" name="add" onclick="fncSubmit('add')">บันทึก</button>
                </div>
            </form>
        </div>
    </div>


    <!-- popup end-->

     <!-- popup2-->
     <div class="popup2" id="popup2">
    <div class="overlay2"></div>
    <div class="content2">
        <div class="close-btn" onclick="solve()">&times;</div>
        <h1>Edit Product</h1>
        <p id="edit-product-id-display"></p> <!-- Display the product ID here -->
        
        <!-- Add this line to display the current image -->
        <img id="current-image" style="display: none; max-width: 100px;"/>

        <form action="update_product.php" method="post" name="form1" enctype="multipart/form-data">
            <input type="hidden" id="edit-hidden_product_id" name="product_id"> <!-- Hidden field for product_id -->
            <div class="name-report">
                <label for="image">Product Image: </label>
                <span><input type="file" id="edit-image" name="image" accept="image/*"></span>
            </div>
            <div class="name-report">
                <label for="product_name">ชื่อบริการ: </label>
                <span><input type="text" id="edit-product_name" name="product_name" required></span>
            </div>
            <div class="name-report">
                <label for="product_type">ประเภท: </label>
                <span><input type="text" id="edit-product_type" name="product_type" required></span>
            </div>
            <div class="name-report">
                <label for="product_details">รายละเอียด: </label>
                <span><input type="text" id="edit-product_details" name="product_details" required></span>
            </div>
            <div class="name-report">
                <label for="product_price">ราคา: </label>
                <span><input type="text" id="edit-product_price" name="product_price" required></span>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn-submit" name="update">บันทึก</button>
            </div>
        </form>
    </div>
</div>


    <!-- popup end-->

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

        /* popup */
        function add() {
            document.getElementById("popup").classList.toggle("active");
        }

          /* popup2 */
          function solve(productId) {
        document.getElementById("popup2").classList.toggle("active");
        fetch(`get_product.php?product_id=${productId}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data && data.product_id) {
                    document.getElementById("edit-hidden_product_id").value = data.product_id;
                    document.getElementById("edit-product_name").value = data.product_name;
                    document.getElementById("edit-product_type").value = data.product_type;
                    document.getElementById("edit-product_details").value = data.product_details;
                    document.getElementById("edit-product_price").value = data.product_price;

                    // Display current image if available
                    if (data.product_img) {
                        document.getElementById("current-image").src = `data:image/jpeg;base64,${data.product_img}`;
                        document.getElementById("current-image").style.display = "block";
                    } else {
                        document.getElementById("current-image").style.display = "none";
                    }
                } else {
                    console.error('No data found');
                }
            })
            .catch(error => {
                console.error('Error fetching product data:', error);
            });
    }

function confirmDelete(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = `delete_product.php?product_id=${productId}`;
    }
}

    </script>
</body>

</html>