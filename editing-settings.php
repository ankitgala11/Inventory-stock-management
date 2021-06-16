<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
require('conndb.php');
$name = $_POST['name'];

$update = "UPDATE settings set name='{$name}';";
$updating = $conn->query($update);
if ($updating == True) {
    $_SESSION['settings_alert'] = ["success", "Successfully Updated Settings"];
    $username = $_SESSION['logged_in'][0];
    $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Edit', 'Settings', '--', '$time');";
    $inserting = $conn->query($insert_in_log);
    if (!$inserting) {
        echo $conn->error;
        die();
    }
} else {
    $_SESSION['settings_alert'] = ["danger", "Failed!" . $conn->error];
}
header('Location: settings.php');
