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

    $update = "UPDATE vendors SET fname='{$_POST['fname']}',
                                lname='{$_POST['lname']}',
                                company_business='{$_POST['company_business']}',
                                email='{$_POST['email']}',
                                number='{$_POST['number']}',
                                address='{$address}'
            WHERE id='{$_POST['id']}';";


    $updating = $conn->query($update);
    if ($updating == True) {


        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Edit', 'Vendor', '$name', '$time');";
        $inserting = $conn->query($insert_in_log);
        if (!$inserting) {
            echo $conn->error;
            die();
        }
        header('Location: list-vendors.php');
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