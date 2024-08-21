<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Document</title>

    <style>
        body, html {
            font-family: Arial, sans-serif;
            height: 100%;
            margin: 0;
            background: linear-gradient(0deg, #ffff 0%, #5FABBC 100%);
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .formtatle {
            font-size: xxx-large;
            margin-bottom: 30px;
            color: #5FABBC; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff; 
        }

        .form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        input.form {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            width: 200px; 
            font-size: 16px;
            height: 30px;
        }
        input.form:focus {
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe; 
            outline: 0; 
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);

        }

        .Submit-Btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #5FABBC;
            color: #ffffff;
            font-size: 20px;
            margin-top: 20px;
            width: 100px; 
            margin-left: 60px;
        }

        .sponge3 {
            position: absolute;
            top: 0;
            right: 0;
            margin-right: 0px;
            margin-top: 0px;
            opacity: 0.5;
        }

        .sponge4 {
            position: absolute;
            top: 0;
            left: 0;
            margin-left: 0px;
            margin-top: 250px;
            opacity: 0.1;
        }
        @media screen and (max-width: 450px) {
        .formtatle {
            font-size: 30px;
            margin-bottom: 30px;
            color: #5FABBC; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff; 
            }
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
        <h1 class="formtatle" id="formtatle">Reset Password</h1>
        <div id="Resetpasswordform">
            <form action="">
                <div class="form">
                    <input id="Newpassword" class="form" type="password" placeholder="New Password">
                    <input id="confirmNewPassword" class="form" type="password" placeholder="Confirm Password">
                </div>
                <div class="center mt-20">
                    <input class="Submit-Btn" type="submit" value="Change" id="PasswordChangeBtn">
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
