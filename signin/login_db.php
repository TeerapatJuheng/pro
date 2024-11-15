<?php
echo '
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
session_start();
include('../inc/server.php');
$errors = array();

if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "ต้องระบุชื่อผู้ใช้");
    }

    if (empty($password)) {
        array_push($errors, "ต้องระบุรหัสผ่าน");
    }

    if (count($errors) == 0) {
        // ตรวจสอบผู้ใช้ใน tb_customer
        $sql1 = "SELECT * FROM `tb_customer` WHERE username = '$username'";
        $result1 = mysqli_query($conn, $sql1);
        $row1 = mysqli_fetch_assoc($result1);

        // ตรวจสอบผู้ใช้ใน tb_shop
        $sql2 = "SELECT * FROM `tb_shop` WHERE shop_user = '$username'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        // ตรวจสอบ tb_customer
        if ($row1) {
            if (password_verify($password, $row1['password'])) {
                $_SESSION['employee_id'] = $username;
                $_SESSION['user_id'] = $row1['id'];
                $_SESSION['ct_id'] = $row1['ct_id'];
                $_SESSION['success'] = "คุณเข้าสู่ระบบเรียบร้อยแล้ว";
                header("location: ./dashboard_user.php");
                exit();
            }
        }

        // ตรวจสอบ tb_shop
        if ($row2) {
            echo "User found in tb_shop"; // Debugging line
            if (password_verify($password, $row2['shop_pass'])) {
                $_SESSION['employee_id'] = $username;
                $_SESSION['shop_id'] = $row2['id'];
                $_SESSION['success'] = "คุณเข้าสู่ระบบเรียบร้อยแล้ว";
                header("location: ./dashboard_shop.php");
                exit();
            } else {
                echo "Password does not match"; // Debugging line
            }
        } else {
            echo "User not found in tb_shop"; // Debugging line
        }

        // หากชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง
        echo '<script> 
        setTimeout(function() { 
            swal({ 
                title: "Error", 
                text: "ชื่อผู้ใช้ หรือรหัสผ่านไม่ถูกต้อง", 
                type: "error" 
            }, function() { 
                window.location = "login.php"; 
            }); 
        }, 1000); 
        </script>';
    }
}
