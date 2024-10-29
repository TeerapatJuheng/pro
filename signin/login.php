<?php
session_start();
include('../inc/server.php');
include('../inc/header.php');

// Unset session variables (if needed)
unset($_SESSION['e_employee_id_add']);
unset($_SESSION['e_first_name_add']);
unset($_SESSION['e_role_add']);
unset($_SESSION['e_password_1']);
unset($_SESSION['e_password_2']);

// Language handling
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default language is English
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
} else {
    $lang = $_SESSION['lang'];
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ARM Corporation</title>
    <link rel="icon" type="image/png" href="../img/Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden; /* Prevent scrolling */
            background: linear-gradient(0deg, #ffff 0%, #5FABBC 100%);
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-side {
    background: rgba(255, 255, 255, 0); /* Fully transparent background */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    margin: auto;
    text-align: center;
    position: relative; /* For positioning child elements */
}

.speech-bubble {
    position: relative;
    padding: 10px;
    margin-bottom: 20px;
}

.speech-bubble::after {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -10px;
    border-width: 10px;
    border-style: solid;
    border-color: transparent transparent rgba(255, 255, 255, 0) transparent; /* Match the transparent background */
}

.logo {
    width: 80px; /* Adjust logo size */
    margin-bottom: 15px;
}

.text-box-user,
.text-box-password {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 2px solid #a5c8e1; /* Soft blue border */
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.9); /* Light background for better readability */
    transition: border-color 0.3s, background-color 0.3s;
}

.text-box-user::placeholder,
.text-box-password::placeholder {
    color: #a0a0a0; /* Soft gray placeholder text */
}

.text-box-user:focus,
.text-box-password:focus {
    border-color: #0056b3; /* Darker blue on focus */
    background: rgba(255, 255, 255, 1); /* Full white on focus for clarity */
    outline: none;
}


.button-submit {
    background-color: #507F99; /* New button color */
    color: white; /* Text color */
    border: none; /* No border */
    padding: 10px 15px; /* Padding */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor */
    transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s; /* Smooth transitions */
    font-size: 16px; /* Font size */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Initial shadow */
}

.button-submit:hover {
    background-color: #4a6d7f; /* Darker shade on hover */
    transform: translateY(-2px); /* Lift effect on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Stronger shadow on hover */
}

.button-submit:active {
    transform: translateY(1px); /* Press down effect */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Reduced shadow on active */
}


.dha,
.dhaa {
    margin: 10px 0;
}

.forgot,
.register-login {
    color: #007bff;
    text-decoration: none;
}

.forgot:hover,
.register-login:hover {
    text-decoration: underline;
    color: #0056b3; /* Darker blue on hover */
}




        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .logo {
            width: 200px;
            height: 80px;
            margin-right: 10px;
        }

        .font {
            font-family: Arial, sans-serif;
            font-size: 40px;
            color: white;
        }

        .iworld {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 30px;
            color: #000;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="sponge3"> 
            <img src="../photo/1x/Asset 3.png" alt="photo3" style="width:150px;height:150px;">
        </div>
        <div class="sponge4">
            <img src="../photo/1x/Asset 6.png" alt="photo3" style="width:150px;height:150px;">
        </div>
        <div class="left-side">
            <div class="speech-bubble" style="font-family: Arial Rounded MT Bold;">
                <img src="../img/Logo.png" alt="Logo" class="logo">
            </div>
            <form action="login_db.php" method="POST">
                <input type="text" name="employee_id" id="employee_id" placeholder="<?php echo $lang == 'en' ? 'Username' : 'ชื่อผู้ใช้'; ?>" class="text-box-user form-control">
                <input type="password" name="password" id="inputPassword" placeholder="<?php echo $lang == 'en' ? 'Password' : 'รหัสผ่าน'; ?>" class="text-box-password form-control">
                <p class="dha"><?php echo $lang == 'en' ? 'Forgot Password?' : 'ลืมรหัสผ่าน'; ?><a href="resetpass.php" class="forgot"><?php echo $lang == 'en' ? 'Forgot' : 'ลืมรหัสผ่าน'; ?></a></p>
                <button type="submit" name="login_user" class="button-submit"><?php echo $lang == 'en' ? 'Login' : 'ยืนยัน'; ?></button>
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="error">
                        <h6 class="text-danger">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </h6>
                    </div>
                <?php endif ?>
                <br>
                <br>
                <div>
                    <p class="dhaa"><?php echo $lang == 'en' ? 'Do not have an account?' : 'ยังไม่มีบัญชีผู้ใช้?'; ?><a href="../signin/register.php" class="register-login"><?php echo $lang == 'en' ? 'Register' : 'ลงทะเบียน'; ?></a></p>
                </div>
            </form>
        </div>
    </div>
    <div class="iworld">
        <?php if ($lang == 'en'): ?>
            <a href="?lang=th"><i class="bi bi-globe2"></i></a>
        <?php else: ?>
            <a href="?lang=en"><i class="bi bi-globe"></i></a>
        <?php endif; ?>
    </div>
    <?php include('../inc/footer.php'); ?>
    <script>
        // JavaScript to prevent scrolling
        window.addEventListener('wheel', function(e) {
            e.preventDefault();
        }, { passive: false });
    </script>
</body>

</html>