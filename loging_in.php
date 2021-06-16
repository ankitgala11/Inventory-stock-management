<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
require 'conndb.php';
// Variables
$back_to = $_POST['back_to'];
$go_to = $_POST['go_to'];
$username = $_POST['username'];
$username = strtolower($username);
$password = $_POST['password'];

$select = "SELECT * FROM users WHERE username = '$username'";
$selecting = $conn->query($select);
if ($selecting) {
    if ($selecting->num_rows > 0) {
        while ($row = $selecting->fetch_assoc()) {
            $hash = $row['password'];
            $type = $row['type'];
            if (password_verify($password, $hash)) {
                $_SESSION['logged_in'] = [$username, $type];

                $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Login', '--', '--', '$time');";
                $inserting_in_log = $conn->query($insert_in_log);
                if (!$inserting_in_log) {
                    echo $conn->error;
                    die();
                }
            } else {
                $alert = ['danger', 'Credentials do not match!'];
            }
        }
    } else {
        $alert = ['danger', 'User not present!'];
    }
} else {
    $alert = ['danger', 'Error! Try after some time. Please contact admin !'];
}
if (isset($alert)) {
    array_push($alert, $username, $password);
    array_unshift($alert, "login");
    $_SESSION['alert'] = $alert;
    $go_to = $back_to;
}
header('Location: ' . $go_to);
