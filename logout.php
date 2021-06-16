<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
require('conndb.php');
$username = $_SESSION['logged_in'][0];
$insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Logout', '--', '--', '$time');";
$inserting = $conn->query($insert_in_log);
session_destroy();
header('Location: index.php');
