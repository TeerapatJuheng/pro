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
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .speech-bubble {
            width: 300px;
            border-radius: 10px;
            padding: 20px;
            border: none;
            box-shadow: none;
            font-size: 40px;
            color: #ffffff;
            text-align: center;
        }

        .text-box-user,
        .text-box-password {
            width: 220px;
            height: 50px;
            border-radius: 10px;
            padding: 10px;
            border: none;
            box-shadow: none;
            font-size: 20px;
            margin-bottom: 20px;
            color: #000;
        }

        .button-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #5FABBC;
            color: #ffffff;
            font-size: 20px;
            margin-left: 60px;
        }

        .dhaa {
            font-size: 12px;
            color: #1E1E1E;
            margin-left: 20px;
        }
        .dha{
            margin-left: 30px;
            font-size: 12px;
        }

        .register-login {
            font-size: 12px;
            color: #3F51B5;
            text-decoration: none;
            margin-top: 20px;
            margin-left: 10px;
        }

        .forgot {
            font-size: 12px;
            color: #3F51B5;
            text-decoration: none;
            margin-top: 0px;
            margin-left: 15px;
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
                <button type="submit" name="login_user" class="button-submit"><?php echo $lang == 'en' ? 'Submit' : 'ยืนยัน'; ?></button>
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