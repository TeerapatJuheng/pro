<?php
echo '
 <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
session_start();
include('../inc/server.php');
$errors = array();
date_default_timezone_set('Asia/Bangkok');
$create_date = date("Y-m-d H:i:s");

// กำหนดค่าเริ่มต้นให้กับตัวแปร session ถ้ายังไม่มีค่า
$_SESSION['e_user_add'] = isset($_POST['user']) ? $_POST['user'] : '';
$_SESSION['e_email_add'] = isset($_POST['email']) ? $_POST['email'] : '';
$_SESSION['e_role_add'] = isset($_POST['role']) ? $_POST['role'] : '';
$_SESSION['e_pass_add'] = isset($_POST['pass']) ? $_POST['pass'] : '';
$_SESSION['e_conpass_add'] = isset($_POST['conpass']) ? $_POST['conpass'] : '';
$_SESSION['e_phone_add'] = isset($_POST['phone']) ? $_POST['phone'] : '';
$_SESSION['e_sex_add'] = isset($_POST['sex']) ? $_POST['sex'] : '';

if (isset($_POST['reg_user'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $conpass = mysqli_real_escape_string($conn, $_POST['conpass']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);

    if (empty($user)) {
        array_push($errors, "Username is required");
        $_SESSION['error'] = "Username is required";
    }

    if (empty($pass)) {
        array_push($errors, "Password is required");
        $_SESSION['error'] = "Password is required";
    }

    if ($pass != $conpass) {
        array_push($errors, "The two passwords do not match");
        $_SESSION['error'] = "The two passwords do not match";

        echo '<script>
            setTimeout(function() {
                swal({
                    title: "Error Password",  
                    text: "Password NOT Match",
                    type: "warning"
                }, function() {
                    window.history.back();
                });
            }, 1000);
        </script>';
    } else {
        if ($pass == $conpass) {
            $sql1 = "SELECT * FROM `tb_customer` WHERE username = '$user'";
            $sql2 = "SELECT * FROM `tb_shop` WHERE shop_user = '$user'";

            $result1 = mysqli_query($conn, $sql1);
            $result2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($result1) <= 0 && mysqli_num_rows($result2) <= 0) {
                // เพิ่มข้อมูลใหม่เข้าไปในฐานข้อมูล
                if($role == "user"){
                    $sql_insert = "INSERT INTO `tb_customer` (`username`, `password`, `email`, `phone`, `sex`) VALUES ('$user', '$pass', '$email', '$phone', '$sex')";
                }
                else{
                    $sql_insert = "INSERT INTO `tb_shop` (`shop_user`, `shop_pass`, `shop_email`, `shop_phone`, `shop_sex`) VALUES ('$user', '$pass', '$email', '$phone', '$sex')";
                }
                $result_insert = mysqli_query($conn, $sql_insert);

                if ($result_insert) {
                    echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Success",  
                                text: "Registration successful",
                                type: "success"
                            }, function() {
                                window.location = "./login.php";
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
    }
}
?>
