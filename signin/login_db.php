<?php
session_start();
// include('db_conection.php');
include('../inc/server.php');
$errors = array();

if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // แสดงค่า username และ password ในหน้าเว็บเบราว์เซอร์
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";

    // บันทึกค่า username และ password ลงใน server error log
    error_log("Username: " . $username);
    error_log("Password: " . $password);

    echo 1;
    echo count($errors);

    if (empty($username)) {
        array_push($errors, "ต้องระบุชื่อผู้ใช้");
    }

    if (empty($password)) {
        array_push($errors, "ต้องระบุรหัสผ่าน");
    }
    
    if (count($errors) == 0) {
        echo "gggggg";
        //$password = md5($password); // ยกเลิกการคอมเมนต์หากรหัสผ่านเก็บเป็น md5 hash

        $sql1 = "SELECT * FROM `tb_customer` WHERE username = '$username' AND password = '$password'";
        $sql2 = "SELECT * FROM `tb_shop` WHERE shop_user = '$username' AND shop_pass ='$password'";

        $result1 = mysqli_query($conn, $sql1);
        $result2 = mysqli_query($conn, $sql2);

        echo mysqli_num_rows($result1);
        echo mysqli_num_rows($result2);

        if (mysqli_num_rows($result1) == 1) {
            echo "l";
            $row = mysqli_fetch_assoc($result1);
            $_SESSION['employee_id'] = $username;
            $_SESSION['success'] = "คุณเข้าสู่ระบบเรียบร้อยแล้ว";
            header("location: ./dashboard_user.php");
            // Add JavaScript alert
            //echo '<script type="text/javascript">
                //alert("Employee ID: '.$_SESSION['employee_id'].'\nFirst Name: '.$_SESSION['first_name'].'\nRole: '.$_SESSION['role'].'\nBranch: '.$_SESSION['branch'].'\nBranch_ID: '.$_SESSION['branch_id'].'");
                //window.location.href = "../inside/dashboard.php";
            //</script>';
        }

        if (mysqli_num_rows($result2) == 1) {
            echo "k";
            $row = mysqli_fetch_assoc($result2);
            $_SESSION['employee_id'] = $username;
            $_SESSION['success'] = "คุณเข้าสู่ระบบเรียบร้อยแล้ว";
            header("location: ./dashboard_shop.php");
            // Add JavaScript alert
            //echo '<script type="text/javascript">
                //alert("Employee ID: '.$_SESSION['employee_id'].'\nFirst Name: '.$_SESSION['first_name'].'\nRole: '.$_SESSION['role'].'\nBranch: '.$_SESSION['branch'].'\nBranch_ID: '.$_SESSION['branch_id'].'");
                //window.location.href = "../inside/dashboard.php";
            //</script>';
        }
    } else {
        array_push($errors, "ต้องระบุชื่อผู้ใช้และรหัสผ่าน");
        $_SESSION['error'] = "ต้องระบุชื่อผู้ใช้และรหัสผ่าน";
        header("location: login.php");
    }
}
?>