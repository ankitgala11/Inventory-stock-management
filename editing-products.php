<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {

    $name = $_POST['name'];
?>
<?php
    require('conndb.php');


    $update = "UPDATE products set name='{$_POST['name']}',
                            price_per_box='{$_POST['price_per_box']}',                            
                            units_per_box='{$_POST['units_per_box']}',
                            packaging_size='{$_POST['packaging_size']}',
                            packaging_unit='{$_POST['packaging_unit']}'
            WHERE id='{$_POST['id']}';";


    $updating = $conn->query($update);
    if ($updating == True) {
        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Edit', 'Product', '$name', '$time');";
        $inserting = $conn->query($insert_in_log);
        if (!$inserting) {
            echo $conn->error;
            die();
        }
        header('Location: list-products.php');
    } else {
        echo "Failed";
        print_r($conn->error);
    }
?>
<?php
} else {
    header('Location: login.php');
}
?>