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
$session = $_SESSION['role'];
$compair_otp = false;
$face_delete = false;

if (isset($_SESSION['employee_id'])) {
    $employee_id_edit = $_SESSION['employee_id'];
}

if (isset($_POST['reg_user']))  {
        $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
        $otp =  mysqli_real_escape_string($conn, $_POST['otp']);

        if (($_POST['role']) <> "") {
            $role = mysqli_real_escape_string($conn, $_POST['role']);
        } else {
            $role = mysqli_real_escape_string($conn, $_POST['role_default']);
           
        }
    }
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if (empty($name)) {
        array_push($errors, "Username is required");
        $_SESSION['error'] = "Username is required";
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
        $_SESSION['error'] = "Password is required";
    }

if (isset($_GET['employee_id'])) {

    $employee_id = $_GET['employee_id'];
    
    $sql = "DELETE FROM tb_master_employees WHERE employee_id = '$employee_id'";
    mysqli_query($conn, $sql);

}

if ($_POST['name'] != null){
    
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $lname = $_POST['lname'];
    $password = $_POST['password']; 
    $role = $_POST['role'];
    
    $sql = "SELECT password FROM tb_master_employees WHERE employee_id = '$employee_id' and ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    if($password == $row['password']){
        $sql = "UPDATE `tb_master_employees` SET `name`='$name',`lname`='$lname', `role`='$role'  WHERE `employee_id`=$employee_id";
    mysqli_query($conn, $sql);
    }
    else{
        $password = md5($_POST['password']);
        $sql = "UPDATE `tb_master_employees` SET `name`='$name',`lname`='$lname',`password`='$password' , `role`='$role'  WHERE `employee_id`=$employee_id";
    mysqli_query($conn, $sql);
    }

    $sql = "UPDATE `tb_master_employees` SET `name`='$name',`lname`='$lname',`password`='$password' , `role`='$role'  WHERE `employee_id`=$employee_id";
    mysqli_query($conn, $sql);

    header('location: ../inside/menageuser.php');
    
}
/*else {
   
    echo '<script>
            setTimeout(function() {
             swal({
                 title: "Invalid OTP",  
                 text: "Invalid OTP",
                 type: "warning"
             }, function() {
                window.history.back()
             });
           }, 1000);
     </script>';
}*/
