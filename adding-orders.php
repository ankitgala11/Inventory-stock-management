<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {
    $extra_message = "";
?>
<?php

    require('conndb.php');

    $date = $_POST['order_date'];

    $type = $_POST['type'];

    if ($_POST['type'] == "Sales") {
        $name = $_POST['customer_name'];
        $table = "customers";
    } else {
        $name = $_POST['vendor_name'];
        $table = "vendors";
    }
    $f_l_names = explode(" ", $name);
    print_r($table);
    echo "<br>";
    print_r($f_l_names[0]);
    echo "<br>";
    print_r($f_l_names[1]);
    echo "<br>";
    $select_person = "SELECT * FROM {$table} WHERE fname='{$f_l_names[0]}' and lname='{$f_l_names[1]}';";
    $selecting_person = $conn->query($select_person);
    if ($selecting_person->num_rows > 0) {

        $invoice_no = $_POST['invoice_no'];
        $products = $_POST['product'];
        $quantities = $_POST['quantity'];
        $status = $_POST['status'];
        $method = $_POST['method'];
        if ($status == "warning-Payed In Advanced") {
            $advanced_payment = $_POST['advanced-payment'];
        }

        $products_details = [];

        if ($type == "Purchase") {
            foreach ($products as $key => $product) {
                $select = "SELECT * from products where name='$product';";
                $selecting = $conn->query($select);
                if (!$selecting) {
                    $extra_message = "No such Product Found";
                    break;
                }
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

                $insert = "INSERT INTO stock(name, date, action, invoice_no, stock_in, stock_out, balance) 
        values('$product', '{$date}','new order','{$invoice_no}', '{$quantities[$key]}', 0, '{$new_balance}');";
                $inserting = $conn->query($insert);
                if (!$inserting) {
                    $extra_message = "Not able to Update Stock";
                    break;
                }
            }
        } else {
            foreach ($products as $key => $product) {
                $select = "SELECT * from products where name='$product';";
                $selecting = $conn->query($select);
                if (!$selecting) {
                    $extra_message = "No such Product Found";
                    break;
                }
                $row = $selecting->fetch_assoc();
                $price_per_box = $row['price_per_box'];
                $units_per_box = $row['units_per_box'];
                if ($price_per_box == null or $units_per_box == null) {
                    $extra_message = "No details for specific Product Found";
                    break;
                }
                $details = [$price_per_box, $units_per_box];
                $products_details[] = $details;
                $select_stock = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null and s1.name='$product';";
                $selecting_stock = $conn->query($select_stock);
                $row = $selecting_stock->fetch_assoc();
                $stock = $row['balance'];
                if ($stock < $quantities[$key]) {
                    $extra_message = "Trying to sell more than in Stock!!";
                    break;
                }
                $new_balance = $stock - $quantities[$key];
                $insert = "INSERT INTO stock(name, date, action, invoice_no, stock_in, stock_out, balance) 
        values('$product', '{$date}','new order','{$invoice_no}', 0, '{$quantities[$key]}', '{$new_balance}');";
                $inserting = $conn->query($insert);
                if (!$inserting) {
                    $extra_message = "Not able to Update Stock";
                    break;
                }
            }
        }

        if (!$inserting) {
            $_SESSION['order_alert'] = ["danger", "Failed!" . " - " . $extra_message . " - " . $conn->error];
            header('Location: add-order.php');
        } else {
            $products = json_encode($products);
            $quantities = json_encode($quantities);
            $products_details = json_encode($products_details);
            if ($status == "warning-Payed In Advanced") {
                $insert = "INSERT INTO orders(type, person, invoice_no, products, quantities,products_details,  method, status, advanced_payment, date) VALUES('$type', '$name', '$invoice_no', '{$products}', '{$quantities}','{$products_details}', '{$method}', '{$status}', '{$advanced_payment}', '{$date}');";
            } else {
                $insert = "INSERT INTO orders(type, person, invoice_no, products, quantities,products_details,  method, status, date) VALUES('$type', '$name', '$invoice_no', '{$products}', '{$quantities}','{$products_details}', '{$method}', '{$status}', '{$date}');";
            }
            $inserting = $conn->query($insert);
            if ($inserting == True) {
                $_SESSION['order_alert'] = ["success", "Successfully Added Order"];
                $username = $_SESSION['logged_in'][0];
                $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Add', 'Order', '$invoice_no', '$time');";
                $inserting = $conn->query($insert_in_log);
            } else {
                $extra_message = "Not able to update Orders!!";
                $_SESSION['order_alert'] = ["danger", "Failed!" . " - " . $extra_message . " - " . $conn->error];
            }

            header('Location: add-order.php');
        }
    } else {
        $extra_message = "No such person found";
        $_SESSION['order_alert'] = ["danger", "Failed!" . " - " . $extra_message . " - " . $conn->error];
        // header('Location: add-order.php');
    }



?>
<?php
} else {
    header('Location: login.php');
}
?>
