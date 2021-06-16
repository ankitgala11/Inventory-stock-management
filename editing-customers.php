<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {
    $name = $_POST['fname'] . " " . $_POST['lname'];
?>
<?php

    require('conndb.php');
    $address = json_encode($_POST['address']);

    $update = "UPDATE customers SET fname='{$_POST['fname']}',
                                lname='{$_POST['lname']}',
                                company_business='{$_POST['company_business']}',
                                email='{$_POST['email']}',
                                number='{$_POST['number']}',
                                address='{$address}'
            WHERE id='{$_POST['id']}';";


    $updating = $conn->query($update);
    if ($updating == True) {
        $_SESSION['customer_alert'] = ["success", "Successfully Updated Customer"];

        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Edit', 'Customer', '$name', '$time');";
        $inserting = $conn->query($insert_in_log);
    } else {
        $_SESSION['customer_alert'] = ["danger", "Failed!" . $conn->error];
    }

    header('Location: list-customers.php');
?>
<?php
} else {
    header('Location: login.php');
}
?>
