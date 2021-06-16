<?php
date_default_timezone_set('Asia/Kolkata');

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

//Create Tables
$create_users_table = "CREATE TABLE IF NOT EXISTS users(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(255),
	password VARCHAR(255),
	type VARCHAR(255)
);";
$creating_users_table = $conn->query($create_users_table);

$create_customers_table = "CREATE TABLE IF NOT EXISTS customers(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	fname VARCHAR(255),
	lname VARCHAR(255),
	company_business TEXT,
	email varchar(255),
	number text,
	address json
);";
$creating_customers_table = $conn->query($create_customers_table);

$create_vendors_table = "CREATE TABLE IF NOT EXISTS vendors(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	fname VARCHAR(255),
	lname VARCHAR(255),
	company_business TEXT,
	email varchar(255),
	number text,
	address json
);";
$creating_vendors_table = $conn->query($create_vendors_table);

$create_products_table = "CREATE TABLE IF NOT EXISTS products(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	price_per_box integer(10),	
	units_per_box integer(10),
	packaging_size integer(10),
	packaging_unit varchar(255)
);";

$create_orders_table = "CREATE TABLE IF NOT EXISTS orders(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	type VARCHAR(255) not null,
	person varchar(255) not null,
	invoice_no integer(10) not null,
	products json not null,
	quantities json not null,
	products_details json not null,	
	advanced_payment int,	
	method varchar(255) not null,
	status varchar(255) not null,
	date datetime,
	delivery_date datetime
);";
$creating_orders_table = $conn->query($create_orders_table);

$create_stock_table = "CREATE TABLE IF NOT EXISTS stock(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	name varchar(250)not null,
	date datetime not null,
	action varchar(255) not null,
	invoice_no varchar(255) not null,
	stock_in int not null,
	stock_out int not null,
	balance int not null
);";
$creating_stock_table = $conn->query($create_stock_table);


$create_settings_table = "CREATE TABLE IF NOT EXISTS settings(	
	name varchar(250) not null	
);";
$creating_settings_table = $conn->query($create_settings_table);

$create_activity_log_table = "CREATE TABLE IF NOT EXISTS activity_log(
	id INT(10) PRIMARY KEY AUTO_INCREMENT,
	username varchar(250) not null,
	activity varchar(250) not null,
	what varchar(250),
	object varchar(250),
	time datetime not null
);";
$creating_activity_log_table = $conn->query($create_activity_log_table);
