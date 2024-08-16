<?php
session_start();
include('../inc/server.php');
include('../inc/header.php');
echo '
<script src="./inc/jquery.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="inc/jquery.autocomplete.js" type="text/javascript"></script>';

?>
<?php
if (isset($_GET['employee_id'])) {

    $employee_id = $_GET['employee_id'];
    //echo $employee_id . "<br>";

    $Query = "SELECT * FROM tb_master_employees WHERE employee_id = '$employee_id'";
    $result = mysqli_query($conn, $Query) or die("database error:" . mysqli_error($conn));
    $row1 = mysqli_fetch_array($result);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Customer</title>
    <script src="https://smtpjs.com/v3/smtp.js"></script>

    <script>
        function fncSubmit() {
            if (isset($_POST['employee_id']), isset($_POST['name']), isset($_POST['lname']), isset($_POST['password']),
                isset($_POST['role'])) {
                $employee_id = $_POST['employee_id'];
                $name = $_POST['name'];
                $lname = $_POST['lname'];
                $password = md5($_POST['password']);
                $role = $_POST['role'];


                echo(alert($employee_id, $name, $lname, $password, $role));

                $Query =
                    "UPDATE `tb_master_employees` SET `name`='$name',`lname`='$lname',`password`='$password',`role`='$role' WHERE `employee_id`=$employee_id";
                $result = mysqli_query($conn, $Query), die("database error:".mysqli_error($conn));
            }
        }
    </script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Edit Customer</h3>
                                </div>
                                <div class="card-body">
                                    <form action="register_edit_delete.php" method="post" name="form1">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="employee_id" type="text" name="employee_id" placeholder="Enter NEW ID" required minlength="3" value="<?= $row1['employee_id']; ?>" readonly="true" />
                                                    <label for="employee_id">Employee ID</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" id="password" type="password" name="password" placeholder="Enter NEW Password" min="1" max="2000" value="<?= $row1['password']; ?>" />
                                                    <label for="password">Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <?php if ($_SESSION['first_name_edit'] == '') { ?>
                                                        <input class="form-control" id="name" type="text" name="name" placeholder="Enter First name" required minlength="3" value="<?= $row1['name']; ?>" />
                                                    <?php } else { ?>
                                                        <input class="form-control" id="name" type="text" name="name" placeholder="Enter First name" required minlength="3" value="<?php echo $_SESSION['name']; ?>" />
                                                    <?php } ?>
                                                    <label for="name">Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <?php if ($_SESSION['last_name_edit'] == '') { ?>
                                                        <input class="form-control" id="lname" type="text" name="lname" placeholder="Enter Last name" required minlength="3" value="<?= $row1['lname']; ?>" />
                                                    <?php } else { ?>
                                                        <input class="form-control" id="lname" type="text" name="lname" placeholder="Enter Last name" required minlength="3" value="<?php echo $_SESSION['lname']; ?>" />

                                                    <?php } ?>
                                                    <label for="lname">Last Name</label>
                                                </div>
                                            </div>
                                            <div>
                                                <br>
                                                <a><b>Select Role:</b>
                                                    <label>
                                                        <input type="radio" name="role" value="Admin"> Admin

                                                        <input type="radio" name="role" value="Employee"> Employee
                                                    </label>
                                            </div>
                                        </div>


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
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button onclick="fncSubmit()" name="reg_user" class="btn btn-primary btn-block">SAVE</button>
                                                <!-- <a class="btn btn-primary btn-block" href="login.html">Create Account</a> -->
                                            </div>
                                        </div>

                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <a button class="btn btn-danger btn-block" href="../inside/menageuser.php">BACK</a>
                                                <!-- <a class="btn btn-primary btn-block" href="login.html">Create Account</a> -->
                                            </div>
                                        </div>

                                        </tbody>
                                        </table>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        var province = ["ABC", "DBA"];

        $('#province_auto').autocomplete({
            source: [province],
            limit: 10
        });
    </script>
    <?php include('../inc/footer.php'); ?>
    <script src="../inc/jquery.js"></script>