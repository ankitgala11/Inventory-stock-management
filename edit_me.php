<?php
session_start();
require('conndb.php');
$what = $_POST['what'];
$change = $_POST;
$username = $change['username'];
if ($what == 'username') {
    $new = strtolower($change['new_username']);
    echo "<br>";
    print_r($new);
    $query = $conn->query("UPDATE users SET username='$new' where username='$username';");
    if (!$query) {
        $_SESSION['u_response'] = ['danger', "Couldn't change username!"];
        if ($conn->error == "Duplicate entry 'admin' for key 'users.username'")
            $_SESSION['u_response'] = ['danger', "Username already exists!"];
    } else {
        if ($conn->affected_rows == 0) {
            $_SESSION['u_response'] = ['danger', "Couldn't change username!"];
        } else {
            $_SESSION['u_response'] = ['success', "Username Updated"];
            $_SESSION['logged_in'][0] = $new;
        }
    }
    header('Location: profile.php');
    die();
}
?>
<a href="users.php">Go Back</a>
<?php


if ($what == 'password') {
    $select = $conn->query("SELECT password FROM users where username='{$change['username']}';");
    $hash = $select->fetch_assoc();
    if (!password_verify($change['old_password'], $hash['password'])) {
        $_SESSION['p_response'] = ['danger', "Wrong Credentials!"];
        header('Location: profile.php');
        die();
    }
    if ($change['new_password'] !== $change['conf_password']) {
        $_SESSION['p_response'] = ['danger', "Passwords do not Match"];
        header('Location: profile.php');
        die();
    }
    $new_pass = password_hash($change['new_password'], PASSWORD_DEFAULT);
    $update = $conn->query("UPDATE users SET password='$new_pass' where username='{$change['username']}'");
    unset($_SESSION['logged_in']);
    $_SESSION['response'] = ['success', "Password Updated!", '', ''];

    header('Location: login.php');
    die();
}
