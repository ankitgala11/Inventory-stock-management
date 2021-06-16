<?php
session_start();
require('conndb.php');
$user = $_POST;
$query = $conn->query("UPDATE users set type='{$user['type']}' WHERE id='{$user['id']}';");
if ($query) {
    $_SESSION['eu_response'] = ['success', "User type Updated"];
} else {
    $_SESSION['eu_response'] = ['danger', "Couldn't update user type!"];
}

header('Location: users.php');
die();
