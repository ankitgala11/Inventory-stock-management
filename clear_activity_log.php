<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    $user = $_SESSION['logged_in'];
    if ($user[1] >= 1000) {
        require('conndb.php');
        $query = $conn->query("TRUNCATE activity_log;");
        header('Location: activity_log.php');
    } else {
        header('Location: dashboard.php');
    }
} else {
    header('Location: index.php');
}
