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

    if (empty($username)) {
        array_push($errors, "ต้องระบุชื่อผู้ใช้");
    }

    if (empty($password)) {
        array_push($errors, "ต้องระบุรหัสผ่าน");
    }
    
    if (count($errors) == 0) {
        //$password = md5($password); // ยกเลิกการคอมเมนต์หากรหัสผ่านเก็บเป็น md5 hash
        $query = "SELECT * FROM tb_customer WHERE name = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['employee_id'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['success'] = "คุณเข้าสู่ระบบเรียบร้อยแล้ว";
            header("location: ./dashboard_user.php");
            // Add JavaScript alert
            //echo '<script type="text/javascript">
                //alert("Employee ID: '.$_SESSION['employee_id'].'\nFirst Name: '.$_SESSION['first_name'].'\nRole: '.$_SESSION['role'].'\nBranch: '.$_SESSION['branch'].'\nBranch_ID: '.$_SESSION['branch_id'].'");
                //window.location.href = "../inside/dashboard.php";
            //</script>';
        } else {
            array_push($errors, "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง");
            $_SESSION['error'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
            header("location: login.php");
        }
    } else {
        array_push($errors, "ต้องระบุชื่อผู้ใช้และรหัสผ่าน");
        $_SESSION['error'] = "ต้องระบุชื่อผู้ใช้และรหัสผ่าน";
        header("location: login.php");
    }
}
?>