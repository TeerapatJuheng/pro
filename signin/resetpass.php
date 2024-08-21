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
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden; /* Prevent scrolling */
            background: linear-gradient(0deg, #ffff 0%, #5FABBC 100%);
        }

        .container {
            justify-content: center;
            align-items: center;
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

        .mt-10 {
            width: 220px;
            height: 25px;
            border-radius: 10px;
            padding: 10px;
            border: none;
            box-shadow: none;
            font-size: 20px;
            margin-bottom: 20px;
            color: #000;
            display: flex;
        }

        .Submit-Btn {
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

        .resetpass {
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
            margin-left: 10px;
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
        <div class="loginandregister">
            <h1 id="formtatle">Reset Password</h1>
            <div id="Resetpasswordform">
                <form action="">
                    <div class="contor">
                        <input id="Newpassword" class="mt-10" type="password" placeholder="New Password">
                        <input id="confirmNewPassword" class="mt-10" type="password" placeholder="Confirm Password">
                    </div>
                    <div class="center mt-20">
                        <input class="Submit-Btn" type="submit" value="Change" id="PasswordChangeBtn">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>