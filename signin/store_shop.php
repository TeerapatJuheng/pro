<?php
session_start();
echo $_SESSION['shop_id'];
include('../inc/server.php');

$sql = "SELECT * FROM `tb_product`";
$result = $conn->query($sql);

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
            width: 100%;
            margin-top: 90px;
        }

        .table_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgb(240, 240, 240);
        }

        button {
            outline: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            padding: 10px;
            color: #ffffff;
        }

        button:hover {
            background-color: #728FCE;
        }

        td button:nth-child(1) {
            background-color: #0298cf;
        }

        td button:nth-child(2) {
            background-color: #f80000;
        }

        .add_new {
            padding: 10px 10px;
            color: #ffffff;
            background-color: #0298cf;
        }

        input {
            padding: 10px 10px;
            margin: 0 5px;
            outline: none;
            border: .5px solid #0298cf;
            border-radius: 6px;
            color: #0298cf;
        }

        .table_section {
            height: 100%;
            overflow: auto;
            border-radius: 10px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            min-width: 1000px;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #507F99;
            color: #ffffff;
            font-size: 15px;
            text-align: center;
            padding: 10px 20px;
        }

        /*th,td {
            border-bottom: .5px solid #dddddd;
            padding: 10px 20px;
            word-break: break-all;
            text-align: center;
        }*/

        td {
            text-align: center;
            padding: 5px 10px;
        }

        td img {
            height: 60px;
            width: 60px;
            object-fit: cover;
            border-radius: 15px;
            border: 1px solid #000;
        }

        tr {
            background-color: #ffffff;
            font-size: 13px;
        }

        tr:hover td {
            color: #507F99;
            cursor: pointer;
            background-color: #EBF5FB;
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
            <img src="../photo/1x/รีด.png" alt="">
            <h3>kkkkkkk oooooooo</h3>
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
                        <th>product</th>
                        <th>ชื่อบริการ</th>
                        <th>ประเภท</th>
                        <th>รายละเอียด</th>
                        <th>ราคา</th>
                        <th>action</th>
                    </tr>
                </thead>
                <?php
                if ($result->num_rows > 0) {
                    echo "<table><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        // แปลง BLOB เป็น Base64
                        $imageData = base64_encode($row['product_img']); // ใช้ 'product_img' สำหรับภาพ
                        $src = 'data:image/jpeg;base64,' . $imageData; // เปลี่ยนประเภทภาพตามที่ใช้ (jpeg, png, etc.)

                        echo "<tr>
                                        <td>{$row['product_id']}</td>
                                        <td><img src='{$src}' alt='Image' style='max-width: 100px;'></td> <!-- ใช้ $src แทน -->
                                        <td>{$row['product_name']}</td>
                                        <td>{$row['product_type']}</td>
                                        <td>{$row['product_details']}</td>
                                        <td>{$row['product_price']}</td>
                                        <td>
                                            <button onclick='solve()'><i class='bx bx-edit-alt'></i></button>
                                            <button><i class='bx bx-trash'></i></button>
                                        </td>
                                    </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "0 results";
                }
                ?>
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
            <form action="add_product.php" method="post" name="form1" enctype="multipart/form-data">
                <div class="name-report">
                    <label for="image">Product Image: </label>
                    <span><input type="file" id="image" name="image" accept="image/*" required>
                </span>
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
                    <button type="submit" class="btn-submit" name="add">บันทึก</button>
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
        function solve() {
            document.getElementById("popup2").classList.toggle("active");
        }

        function fncSubmit(x) {
            if (x == "add") {
                //alert(document.form1.getElementById("otp"))
                document.form1.action = "add_product.php";
            }

            if (x == "edit") {

                document.form1.action = "edit_product.php";
            }
        }
    </script>
</body>

</html>