<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {
    $name = $_POST['name'];
?>
<?php

    require('conndb.php');

    $insert = "INSERT INTO products (name, price_per_box, units_per_box, packaging_size, packaging_unit) Values ('{$_POST['name']}','{$_POST['price_per_box']}', '{$_POST['units_per_box']}', '{$_POST['packaging_size']}', '{$_POST['packaging_unit']}');";
    $inserting = $conn->query($insert);
    if ($inserting == True) {
        $_SESSION['product_alert'] = ["success", "Successfully Added Product"];

        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Add', 'Product', '$name', '$time');";
        $inserting = $conn->query($insert_in_log);
    } else {
        $_SESSION['product_alert'] = ["danger", "Failed!" . $conn->error];
    }

    header('Location: add-product.php');



?>
<?php
} else {
    header('Location: login.php');
}
?>
