<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
} else {
    if ($_SESSION['logged_in'][1] < 1000) {
        header('Location: index.php');
    } else {
        require('conndb.php');
        $id = $_POST['id'];
        $delete = $conn->query("DELETE from users where id='$id'");
        header('Location: users.php');
    }
}
