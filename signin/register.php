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

        .sign {
            /*position: absolute;
            top:280px;
            left:50px;
            transform:translate(-50%,-50%);
            width: 350px;
            height: 490px;*/
            text-align:center;
            /*border: 1px solid rgb(241, 241, 241);
            border-radius:12px;*/
            /*background: transparent;
            backdrop-filter:blur(6px);
            box-shadow:5px 5px 10px 0 rgba(0, 0, 0, 0.5);
            /*margin-top:50px;
            margin-left:150px;*/
            max-width: 100%;
            margin-top: 80px;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
       
        .sign h2 {
            font-size: 40px;
            color:#fffefe;
            margin-top:50px;
        }

        .input-field {
            position: relative;
        }

        .input-field input[type="username"],
        .input-field input[type="email"],
        .input-field input[type="password"],
        .input-field input[type="confirmpass"],
        .input-field input[type="age"],
        .input-field input[type="job"] {
            border-radius:10px;
            background: #fff;
            margin:15px;
            border: 2px solid #5FABBC;
            width: 280px;
            height: 2px;
            padding:20px 40px 20px 20px;
            backdrop-filter:blur(15px);
        }

        .input::placeholder {
            color: rgb(255, 255, 255);
        }

        .input-field input[type="username"]:focus::placeholder,
        .input-field input[type="email"]:focus::placeholder,
        .input-field input[type="password"]:focus::placeholder,
        .input-field input[type="confirmpass"]:focus::placeholder,
        .input-field input[type="age"]:focus::placeholder,
        .input-field input[type="job"]:focus::placeholder {
            transform:translateY(-100%);
            transition:transform 0.2s ease-in-out;
            font-size:14px;
        }

        .input-field input[type="username"]:not(:focus)::placeholder,
        .input-field input[type="email"]:not(:focus)::placeholder,
        .input-field input[type="password"]:not(:focus)::placeholder,
        .input-field input[type="confirmpass"]:not(:focus)::placeholder,
        .input-field input[type="age"]:not(:focus)::placeholder,
        .input-field input[type="job"]:not(:focus)::placeholder {
            transform:translateY(0%);
            transition:transform 0.2s ease-in-out;
            font-size:16px;
        }

        /*.sign .sign2 {
            background:#5FABBC;
            border:20px 20px;
            outline:none;
            cursor:pointer;
            font-weight:600;
            border-radius:20px;
            width: 200px;
            height: 30px;
            color:#fff;
        }*/

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
            font-size:16px;
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
    
    <div class="sign">
        <h2>Sign up</h2>
        <form action="register_db.php" method="post" name="form1">
            <div class="input-field">
                <input type="username" name="user" id="user" placeholder="Username"  value="<?php echo $_SESSION['e_user_add']; ?>" require>
            </div>
            <div class="input-field">
                <input type="email" name="email" id="email" placeholder="Email"  value="<?php echo $_SESSION['e_email_add']; ?>" require>
            </div>
            <div class="input-field">
                <input type="password" name="pass" id="pass" placeholder="Password"  value="<?php echo $_SESSION['e_pass_add']; ?>" require>
            </div>
            <div class="input-field">
                <input type="password" name="conpass" id="conpass" placeholder="ConfirmPassword"  value="<?php echo $_SESSION['e_conpass_add']; ?>" require>
            </div>
            <div class="input-field">
                <input type="age" name="age" id="age" placeholder="age"  value="Age" require>
            </div>
            <div class="input-field">
                <input type="job" name="job" id="job" placeholder="job"  value="Job" require>
            </div>
            <div class="sex">
                <br>
                <label>
                    <input type="radio" name="sex" value="male"> Male
                    <input type="radio" name="sex" value="female"> Female
                    <input type="radio" name="sex" value="other"> Other
                </label>
            </div>
            <div class="role">
                <br>
                <label>
                    <input type="radio" name="role" value="user"> User
                    <input type="radio" name="role" value="shop"> Shop
                </label>
            </div>
            <br>
            <button type="submit" class="sign2" name="reg_user" onclick="fncSubmit('page1')">Sign up</button>
        </form>
    </div>
    
    

    

    <?php include('../inc/footer.php'); ?>

    

</body>

</html>
