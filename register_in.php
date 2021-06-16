<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in']) and ($_SESSION['logged_in'][1] == "100" or $_SESSION['logged_in'][1] == "1000")) {
    require 'conndb.php';

    $back_to = $_POST['back_to'];
    $go_to = $_POST['go_to'];
    $username = $_POST['username'];
    $username = strtolower($username);
    $type = $_POST['type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $select = "SELECT * FROM users WHERE username='$username'";
        $selecting = $conn->query($select);
        if ($selecting->num_rows > 0) {
            $alert = ['danger', 'User Already Exists !'];
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users(username,type, password) VALUES('$username','$type', '$password')";
            $inserting = $conn->query($insert);
            $alert = ['success', $username . ' was registered Successfully'];

            $name = $username;
            $username = $_SESSION['logged_in'][0];
            $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Register', 'User', '$name', '$time');";
            $inserting = $conn->query($insert_in_log);
            if (!$inserting) {
                echo $conn->error;
                die();
            }
        }
    } else {
        $alert = ['danger', 'Passwords Do not Match !'];
    }



    if (isset($alert)) {
        if ($alert[0] == 'danger') {
            array_push($alert, $username, $password, $confirm_password, $type);
        } else {
            array_push($alert, '', '', '', '');
        }
        array_unshift($alert, "register");
        $_SESSION['alert'] = $alert;
        $go_to = $back_to;
    }
    header('Location: ' . $go_to);
} else {
    header("Location: logout.php");
}
