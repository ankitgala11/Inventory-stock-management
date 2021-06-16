<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "premal";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("DROP TABLE orders;");
$conn->query("DROP TABLE customers;");
$conn->query("DROP TABLE vendors;");
$conn->query("DROP TABLE users;");
$conn->query("DROP TABLE products;");
$conn->query("DROP TABLE stock;");
