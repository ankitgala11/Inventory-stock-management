<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {

?>
<?php
    require('conndb.php');
    $id = $_POST['id'];

    $delete = "DELETE FROM customers where id='$id'";
    $deleting = $conn->query($delete);
    if ($deleting == True) {
        $_SESSION['customer_alert'] = ["success", "Successfully Deleted Customer"];
        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Delete', 'Customer', '$id', '$time');";
        $inserting = $conn->query($insert_in_log);
        if (!$inserting) {
            echo $conn->error;
            die();
        }
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