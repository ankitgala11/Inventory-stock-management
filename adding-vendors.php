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
    $insert = "INSERT INTO vendors (fname, lname, company_business, email, number, address) Values ('{$_POST['fname']}','{$_POST['lname']}','{$_POST['company_business']}', '{$_POST['email']}', '{$_POST['number']}', '{$address}');";
    $inserting = $conn->query($insert);

    if ($inserting == True) {
        $_SESSION['vendor_alert'] = ["success", "Successfully Added Vendor"];

        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Add', 'Vendor', '$name', '$time');";
        $inserting = $conn->query($insert_in_log);
    } else {
        $_SESSION['vendor_alert'] = ["danger", "Failed!" . $conn->error];
    }

    header('Location: add-vendor.php');



?>
<?php
} else {
    header('Location: login.php');
}
?>