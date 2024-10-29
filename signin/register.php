<?php

session_start();
include('../inc/server.php');
include('../inc/header.php');



echo '
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';



echo '<script>
function test(){
    $.ajax({url:"linealert.php", success:function(result){
    $("div").text(result);}
})
} 
</script>';


$session = $_SESSION['role'];

if (isset($_POST['button1'])) {
    sendLineNotify();
}

function sendLineNotify($message = "ทดสอบ By Programmer")
{
    //$token = "lso4mqedW5gVLJ32e3PTi2MsAKemcyrv0EUsOJ9jJ38"; // ใส่ Token ที่สร้างไว้
    $token = "djInvZWWEKgBm7VR5k9Oo420BGMh7l3mMTVG2Gxj8uL"; // ตัว Test

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . $message);
    $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token . '',);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);

    if (curl_error($ch)) {
        echo 'error:' . curl_error($ch);
    } else {
        $res = json_decode($result, true);
        echo "status : " . $res['status'];
        echo "message : " . $res['message'];
    }
    curl_close($ch);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee edit</title>
    <script type="text/javascript">
    /***********************************************
     * Disable "Enter" key in Form script- By Nurul Fadilah(nurul@REMOVETHISvolmedia.com)
     * This notice must stay intact for use
     * Visit http://www.dynamicdrive.com/ for full source code
     ***********************************************/

    function handleEnter(field, event) {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if (keyCode == 13) {
            var i;
            for (i = 0; i < field.form.elements.length; i++)
                if (field == field.form.elements[i])
                    break;
            i = (i + 1) % field.form.elements.length;
            field.form.elements[i].focus();
            return false;
        } else
            return true;
    }
    </script>

    <script>
    function fncSubmit(strPage) {
        if (strPage == "page1") {
            //alert(document.form1.getElementById("otp"))
            document.form1.action = "register_db.php";
        }

        if (strPage == "page2") {

            document.form1.action = "linealert.php";
        }
    }
    </script>
</head>

    <style>

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden; /* Prevent scrolling */
            background: linear-gradient(0deg, #ffff 0%, #5FABBC 100%);
        }

        .sponge3 {
            position: absolute;
            top: 0;
            right: 0;
            margin-right: -20px;
            margin-top: 0px;
        }

        .sponge4 {
            position: absolute;
            top: 0;
            left: 0;
            margin-left: -20px;
            margin-top: 250px;
        }

        .sponge5 {
            position: absolute;
            bottom: 0;
            right: 0;
            margin-right: -20px;
            margin-bottom: 170px;
        }
        .container {
        display: flex;
        justify-content: center; /* จัดแนวนอนกลาง */
        align-items: center; /* จัดแนวตั้งกลาง */
        height: 100vh; /* ความสูงเต็มหน้าจอ */
        background: linear-gradient(0deg, #ffffff 0%, #5FABBC 100%); /* พื้นหลัง */
    }

    .sign {
        background: rgba(255, 255, 255, 0); /* พื้นหลังโปร่งใส */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        max-width: 400px; /* กำหนดความกว้างสูงสุด */
        width: 100%; /* ทำให้ฟอร์มไม่เกินความกว้าง */
    }
       
    .sign h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .input-field {
        margin-bottom: 15px;
    }
    .input-field select {
    width: 100%; /* ทำให้ select กว้างเต็มช่อง */
    padding: 10px; /* เพิ่ม padding */
    border: 1px solid #ccc; /* ขอบ */
    border-radius: 5px; /* มุมโค้ง */
    background-color: rgba(255, 255, 255, 0.8); /* พื้นหลังโปร่งใส */
}

    .input-field input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .sex label,
    .role label {
        display: inline-block;
        margin-right: 10px;
    }

    .sign2 {
        width: 100%;
        padding: 10px;
        background-color: #6C63FF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .sign2:hover {
        background-color: #5548c8;
    }
        button {
            outline:none;
            border:1px solid #507F99;
            border-radius:6px;
            cursor:pointer;
            padding: 10px 30px;
            color: #ffffff;
            background-color: #507F99;
            font-size: 14px;
        }

        .role {
    text-align: center; /* จัดตำแหน่งให้ข้อความอยู่กลาง */
    margin: 20px 0; /* เพิ่มช่องว่างด้านบนและด้านล่าง */
}

.role-options {
    display: flex; /* ใช้ flexbox เพื่อจัดตำแหน่งตัวเลือก */
    justify-content: center; /* จัดตำแหน่งตัวเลือกให้กึ่งกลาง */
}

.role-options input {
    margin: 0 10px; /* เพิ่มช่องว่างระหว่างตัวเลือก */
}

    </style>
</head>

<body>
    <div class="sponge3"> 
        <img src="../photo/1x/Asset 3.png" alt="photo3" style="width:150px;height:150px;">
    </div>
    <div class="sponge4">
        <img src="../photo/1x/Asset 6.png" alt="photo3" style="width:150px;height:150px;">
    </div>
    <div class="sponge5">
        <img src="../photo/1x/Asset 1.png" alt="photo3" style="width:150px;height:150px;">
    </div>
    
    <div class="container">
    <div class="sign">
        <h2>Sign Up</h2>
        <form action="register_db.php" method="post" name="form1">
            <div class="input-field">
                <input type="text" name="user" id="user" placeholder="Username" value="<?php echo $_SESSION['e_user_add']; ?>" required>
            </div>
            <div class="input-field">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['e_email_add']; ?>" required>
            </div>
            <div class="input-field">
                <input type="password" name="pass" id="pass" placeholder="Password" value="<?php echo $_SESSION['e_pass_add']; ?>" required>
            </div>
            <div class="input-field">
                <input type="password" name="conpass" id="conpass" placeholder="Confirm Password" value="<?php echo $_SESSION['e_conpass_add']; ?>" required>
            </div>
            <div class="input-field">
                <input type="number" name="age" id="age" placeholder="Age" value="<?php echo $_SESSION['e_age_add']; ?>" required>
            </div>
            <div class="input-field">
                <input type="text" name="job" id="job" placeholder="Job" value="<?php echo $_SESSION['e_job_add']; ?>" required>
            </div>
            <div class="input-field">
                <select name="sex" id="sex" required>
                    <option value="" disabled <?= empty($_SESSION['e_sex_add']) ? 'selected' : ''; ?>>Select Gender</option>
                    <option value="Male" <?= (isset($_SESSION['e_sex_add']) && $_SESSION['e_sex_add'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?= (isset($_SESSION['e_sex_add']) && $_SESSION['e_sex_add'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?= (isset($_SESSION['e_sex_add']) && $_SESSION['e_sex_add'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="role">
                <label>
                    <input type="radio" name="role" value="user" <?= (isset($_SESSION['e_role_add']) && $_SESSION['e_role_add'] == 'user') ? 'checked' : ''; ?>> User
                    <input type="radio" name="role" value="shop" <?= (isset($_SESSION['e_role_add']) && $_SESSION['e_role_add'] == 'shop') ? 'checked' : ''; ?>> Shop
                </label>
            </div>
            <button type="submit" class="sign2" name="reg_user" onclick="fncSubmit('page1')">Sign Up</button>
        </form>
    </div>
</div>

    

    

    <?php include('../inc/footer.php'); ?>

    

</body>

</html>