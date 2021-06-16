<?php
session_start();
if (isset($_SESSION['logged_in']) and ($_SESSION['logged_in'][1] == "100" or $_SESSION['logged_in'][1] == "1000")) {

?>
    <?php

    if (isset($_SESSION['logged_in'])) {
        if (isset($_SESSION['alert']) and $_SESSION['alert'][0] == 'register') {
            $alert =  $_SESSION['alert'];
        } else {
            $alert = ['', '', '', '', '', '', ''];
        }
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>App | Register</title>
            <link rel="stylesheet" href="./my_vendors/bootstrap.min.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
            <link href="https://fonts.googleapis.com/css2?family=Karla&display=swap" rel="stylesheet">

            <style>
                body {
                    padding: 30px;
                    padding-top: 10px;
                    background-color: #333;
                    box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
                    background-size: cover;
                    min-height: 100vh;
                }

                body * {
                    font-family: "Karla";
                }

                .message {
                    position: absolute;
                    top: 10px;
                    width: 500px;
                    left: calc(50% - 250px);
                }

                form {
                    background-color: white;
                    border-radius: 12px;
                    padding: 20px;
                    margin-top: 20px;
                    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
                }
            </style>
        </head>

        <body>
            <a style="color:white;" href="users.php" class="btn"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>

            </div>
            <?php if ($alert[1] != '') { ?>
                <div class="message">
                    <div class="alert alert-<?php echo $alert[1] ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert[2];
                        if ($alert[1] == 'success') {
                            echo " &nbsp;<a href='logout.php'>Login</a>";
                        }
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            <?php } ?>



            <div class="login-page-wrapper d-flex justify-content-center">

                <form class="col-lg-4 text-center" action="register_in.php" method="POST">
                    <input type="hidden" name="back_to" value="register.php">
                    <input type="hidden" name="go_to" value="register.php">
                    <h1 class="mb-3">Register</h1>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" required name="username" class="form-control" value="<?php echo $alert[3]; ?>">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select type="text" required name="type" class="form-control">
                            <option value="<?php echo $alert[6]; ?>">
                                <?php $alert[6] ?>
                            </option>
                            <option value="10">USER</option>
                            <option value="100">ADMIN</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" autocomplete="false" required name="password" id="myPassword" class="form-control" value="<?php echo $alert[4]; ?>">
                        <div class="m-1"><input type="checkbox" onclick="myFunction()">&nbsp;&nbsp;<small>Show Password</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" autocomplete="false" required name="confirm_password" id="myConfirmPassword" class="form-control" value="<?php echo $alert[5]; ?>">
                        <div class="m-1"><input type="checkbox" onclick="myConfirmFunction()">&nbsp;&nbsp;<small>Show
                                Password</small></div>
                    </div>
                    <div>
                        <button class="btn btn-success">Register</button></div>
                </form>
            </div>
            <script src="./my_vendors/jquery.min.js"></script>
            <script src="./my_vendors/bootstrap.min.js"></script>
            <script>
                function myFunction() {
                    var x = document.getElementById("myPassword");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                }

                function myConfirmFunction() {
                    var x = document.getElementById("myConfirmPassword");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                }
            </script>
        </body>

        </html>

    <?php unset($_SESSION['alert']);
    } else {
        header("Location: index.php");
    }
    ?>

<?php
} else {
    header('Location: logout.php');
}
?>