<?php
require('conndb.php');

$date = date("Y/m/d");

$type = $_POST['type'];

if ($_POST['type'] == "Sales") $name = $_POST['customer_name'];
else $name = $_POST['vendor_name'];

$invoice_no = $_POST['invoice_no'];
$products = $_POST['product'];
$quantities = $_POST['quantity'];
$status = $_POST['status'];
$method = $_POST['method'];

$products_details = [];

if ($type == "Purchase") {
    foreach ($products as $key => $product) {
        $select = "SELECT * from products where name='$product';";
        $selecting = $conn->query($select);
        $row = $selecting->fetch_assoc();
        $price_per_box = $row['price_per_box'];
        $units_per_box = $row['units_per_box'];
        $details = [$price_per_box, $units_per_box];
        $products_details[] = $details;

        $select_stock_balance = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null and s1.name='$product';";
        $selecting_stock_balance = $conn->query($select_stock_balance);
        echo $selecting_stock_balance->num_rows;
        while ($row_new = $selecting_stock_balance->fetch_assoc()) {
            $balance = $row_new['balance'];
            echo ">>" . $balance;
        }

        if ($row_new) {
            echo "1";
        }
        $new_balance = $balance + $quantities[$key];

        $insert = "INSERT INTO stock(name, date, stock_in, stock_out, balance) 
        values('$product', '{$date}', '{$quantities[$key]}', 0, '{$new_balance}');";
        $inserting = $conn->query($insert);
        if (!$inserting) break;
    }
} else {
    foreach ($products as $key => $product) {
        $select = "SELECT * from products where name='$product';";
        $selecting = $conn->query($select);
        $row = $selecting->fetch_assoc();
        $price_per_box = $row['price_per_box'];
        $units_per_box = $row['units_per_box'];
        $details = [$price_per_box, $units_per_box];
        $products_details[] = $details;

        $select_stock = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null and s1.name='$product';";
        $selecting_stock = $conn->query($select_stock);
        $row = $selecting_stock->fetch_assoc();
        $stock = $row['balance'];
        if ($stock < $quantities[$key]) {
            break;
        }
        $new_balance = $stock - $quantities[$key];
        $insert = "INSERT INTO stock(name, date, stock_in, stock_out, balance) 
        values('$product', '{$date}', 0, '{$quantities[$key]}', '{$new_balance}');";
        $inserting = $conn->query($insert);
        if (!$inserting) break;
    }
}

if (!$inserting) {
    print_r("Failed here");
    echo "<br>";
    print_r($conn->error);
} else {
    $products = json_encode($products);
    $quantities = json_encode($quantities);
    $products_details = json_encode($products_details);

    $insert = "INSERT INTO orders(type, person, invoice_no, products, quantities,products_details,  method, status) VALUES('$type', '$name', '$invoice_no', '{$products}', '{$quantities}','{$products_details}', '{$method}', '{$status}');";
    $inserting = $conn->query($insert);
    if ($inserting == True) {
        // header('Location: add-order.php');
        echo "Success";
    } else {
        echo "Failed";
        print_r($conn->error);
    }
}
