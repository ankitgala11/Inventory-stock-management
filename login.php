<?php
session_start();
if (isset($_SESSION['alert']) and $_SESSION['alert'][0] == 'login') {
    $alert =  $_SESSION['alert'];
} else {
    $alert = ['', '', '', '', ''];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App | Login</title>
    <link rel="stylesheet" href="./my_vendors/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Karla&display=swap" rel="stylesheet">
    <script src="assets/fontawesome.js"></script>
    <style>
        body {
            padding: 30px;
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

        .login-page-wrapper {
            height: 80vh;
        }

        form {
            background-color: white;
            border-radius: 12px;
            padding: 50px;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }
    </style>
    <style>
        /*
 * Globals
 */

/* Links */
a,
a:focus,
a:hover {
  color: #fff;
}

/* Custom default button */
.btn-secondary,
.btn-secondary:hover,
.btn-secondary:focus {
  color: #333;
  text-shadow: none; /* Prevent inheritance from `body` */
  background-color: #fff;
  border: .05rem solid #fff;
}


/*
 * Base structure
 */

html,
body {
  height: 100%;
  background-color: #333;
}

body {      
  box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
}


/*
 * Header
 */
.masthead {
  margin-bottom: 2rem;
}

.masthead-brand {
  margin-bottom: 0;
}

.nav-masthead .nav-link {
  padding: .25rem 0;
  font-weight: 700;
  color: rgba(255, 255, 255, .5);
  background-color: transparent;
  border-bottom: .25rem solid transparent;
}

.nav-masthead .nav-link:hover,
.nav-masthead .nav-link:focus {
  border-bottom-color: rgba(255, 255, 255, .25);
}

.nav-masthead .nav-link + .nav-link {
  margin-left: 1rem;
}

.nav-masthead .active {
  color: #fff;  
}

.nav-masthead .activeactive {  
  border-bottom: 2px solid white !important;
}

@media (min-width: 48em) {
  .masthead-brand {
    float: left;
  }
  .nav-masthead {
    float: right;
  }
}


/*
 * Cover
 */
.cover {
  padding: 0 1.5rem;
}
.cover .btn-lg {
  padding: .75rem 1.25rem;
  font-weight: 700;
}


/*
 * Footer
 */
.mastfoot {
  color: rgba(255, 255, 255, .5);
}     
    </style>
</head>

<body>
<a style="color:white;" href="index.php" class="btn"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    <?php if ($alert[1] != '') { ?>
        <div class="message">
            <div class="alert alert-<?php echo $alert[1] ?> alert-dismissible fade show" role="alert">
                <?php echo $alert[2]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    <?php } ?>
    <div class="login-page-wrapper d-flex justify-content-center align-items-center">

        <form class="col-lg-4 text-center" action="loging_in.php" method="POST">
            <input type="hidden" name="back_to" value="login.php">
            <input type="hidden" name="go_to" value="dashboard.php">
            <h1>Login</h1>
            <div class="form-group">
                <label>Username</label>
                <input type="text" required name="username" class="form-control" value="<?php echo $alert[3]; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" required autocomplete="false" name="password" id="myPassword" class="form-control" value="<?php echo $alert[4]; ?>">
                <div class="m-1"><input type="checkbox" onclick="myFunction()">&nbsp;&nbsp;Show Password</div>
            </div>
            <div>
                <button class="btn btn-success">Login</button></div>
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

<?php unset($_SESSION['alert']); ?>