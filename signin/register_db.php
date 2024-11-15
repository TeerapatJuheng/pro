<?php
echo '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

session_start();
include('../inc/server.php');
$errors = array();
date_default_timezone_set('Asia/Bangkok');

// กำหนดค่าเริ่มต้นให้กับตัวแปร session
$_SESSION['e_user_add'] = isset($_POST['user']) ? $_POST['user'] : '';
$_SESSION['e_email_add'] = isset($_POST['email']) ? $_POST['email'] : '';
$_SESSION['e_role_add'] = isset($_POST['role']) ? $_POST['role'] : '';
$_SESSION['e_pass_add'] = isset($_POST['pass']) ? $_POST['pass'] : '';
$_SESSION['e_conpass_add'] = isset($_POST['conpass']) ? $_POST['conpass'] : '';
$_SESSION['e_dob_add'] = isset($_POST['birth_date']) ? $_POST['birth_date'] : '';
$_SESSION['e_job_add'] = isset($_POST['job']) ? $_POST['job'] : '';
$_SESSION['e_sex_add'] = isset($_POST['sex']) ? $_POST['sex'] : '';

if (isset($_POST['reg_user'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $conpass = mysqli_real_escape_string($conn, $_POST['conpass']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $job = mysqli_real_escape_string($conn, $_POST['job']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);

    // ตรวจสอบความถูกต้องของข้อมูล
    if (empty($user)) {
        array_push($errors, "Username is required");
    }

    if (empty($pass)) {
        array_push($errors, "Password is required");
    }

    if ($pass != $conpass) {
        array_push($errors, "The two passwords do not match");
    }

    if (empty($birth_date)) {
        array_push($errors, "Birth date is required");
    } else {
        // แปลงวันที่จาก dd/mm/yy เป็น YYYY-MM-DD
        $parts = explode('/', $birth_date);
        if (count($parts) == 3) {
            $formatted_birth_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // YYYY-MM-DD
            $dob = new DateTime($formatted_birth_date);
            $now = new DateTime();
            $age = $now->diff($dob)->y;
        } else {
            array_push($errors, "Invalid birth date format");
        }
    }

    // แสดงข้อผิดพลาดถ้ามี
    if (!empty($errors)) {
        echo '<script> 
            setTimeout(function() { 
                swal({ 
                    title: "Error", 
                    text: "' . implode(", ", $errors) . '", 
                    type: "error" 
                }, function() { 
                    window.history.back(); 
                }); 
            }, 1000); 
        </script>';
        exit(); // หยุดการทำงานหลังแสดงข้อผิดพลาด
    }

    // ตรวจสอบการซ้ำซ้อนของชื่อผู้ใช้
    $sql1 = "SELECT * FROM `tb_customer` WHERE username = '$user'";
    $sql2 = "SELECT * FROM `tb_shop` WHERE shop_user = '$user'";

    $result1 = mysqli_query($conn, $sql1);
    $result2 = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($result1) <= 0 && mysqli_num_rows($result2) <= 0) {
        // แฮชรหัสผ่านก่อนบันทึก
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        // เพิ่มข้อมูลใหม่เข้าไปในฐานข้อมูล
        if ($role == "user") {
            $sql_insert = "INSERT INTO `tb_customer` (`username`, `password`, `email`, `age`, `job`, `sex`) VALUES ('$user', '$hashedPassword', '$email', '$age', '$job', '$sex')";
        } else {
            $sql_insert = "INSERT INTO `tb_shop` (`shop_user`, `shop_pass`, `shop_email`, `shop_age`, `shop_job`, `shop_sex`) VALUES ('$user', '$hashedPassword', '$email', '$age', '$job', '$sex')";
        }
        $result_insert = mysqli_query($conn, $sql_insert);

        if ($result_insert) {
            // ล้างค่าที่เกี่ยวข้องกับฟอร์มใน session
            unset($_SESSION['e_user_add'], $_SESSION['e_email_add'], $_SESSION['e_role_add'], $_SESSION['e_pass_add'], $_SESSION['e_conpass_add'], $_SESSION['e_dob_add'], $_SESSION['e_job_add'], $_SESSION['e_sex_add']);
            
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "Success",  
                        text: "Registration successful",
                        type: "success"
                    }, function() {
                        window.location = "./login.php"; // เปลี่ยนเส้นทางไปที่หน้า login
                    });
                }, 1000);
            </script>';
        } else {
            echo '<script> 
                setTimeout(function() { 
                    swal({ 
                        title: "Error", 
                        text: "Failed to register user", 
                        type: "error" 
                    }, function() { 
                        window.location = "register.php"; 
                    }); 
                }, 1000); 
            </script>';
        }
    } else {
        echo '<script> 
            setTimeout(function() { 
                swal({ 
                    title: "Error", 
                    text: "This username is already registered", 
                    type: "error" 
                }, function() { 
                    window.location = "register.php"; 
                }); 
            }, 1000); 
        </script>';
    }
}
?>