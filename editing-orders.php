<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$time = date("Y/m/d h:i:s");
if (isset($_SESSION['logged_in'])) {
?>
<?php
    require('conndb.php');
    $date = date("Y/m/d");
    $broke = 0;

    $id = $_POST['id'];
    $invoice = $_POST['invoice_no'];
    $type = $_POST['type'];
    $status = $_POST['status'];
    $method = $_POST['method'];
    $products = $_POST['product'];
    $new_quantities = $_POST['new_quantity'];
    $old_quantities = $_POST['old_quantity'];
    if (isset($_POST['delivery_date'])) {
        $delivery_date = $_POST['delivery_date'];
    }
    $products_details = [];
    $balances = [];

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
        while ($row_new = $selecting_stock_balance->fetch_assoc()) {
            $balances[] = $row_new['balance'];
        }
    }


    echo "<br>";
    foreach ($new_quantities as $key => $quantity) {
        if ($status != "danger-Failed") {
            if ($quantity == $old_quantities[$key]) {
                echo "Continued<br>";
                continue;
            }
            if (($quantity > ($balances[$key] + $old_quantities[$key])) and $type == "Sales") {
                echo "Why here !!!";
                break;
            }
            $change = $quantity - $old_quantities[$key];
            $change = abs($change);

            if (($type == "Sales" and $quantity > $old_quantities[$key]) or ($type == "Purchase" and $quantity < $old_quantities[$key])) {
                $balances[$key] -= $change;
            } else {
                $balances[$key] += $change;
            }

            if ($type == "Sales") {
                $insert_stock = "INSERT INTO stock(name, date, action,invoice_no, stock_in, stock_out,balance) values ('{$products[$key]}', '{$date}', 'update order', '{$invoice}', 0, '{$quantity}', '{$balances[$key]}');";
            } else {
                $insert_stock = "INSERT INTO stock(name, date, action,invoice_no, stock_in, stock_out,balance) values ('{$products[$key]}', '{$date}', 'update order', '{$invoice}', '{$quantity}', 0, '{$balances[$key]}');";
            }
            $updating = $conn->query($insert_stock);
        } else {
            if ($type == "Sales") {
                $balances[$key] += $old_quantities[$key];
            } elseif ($type == "Purchase") {
                $balances[$key] -= $old_quantities[$key];
            }
            if ($type == "Sales") {
                $insert_stock = "INSERT INTO stock(name, date, action,invoice_no, stock_in, stock_out,balance) values ('{$products[$key]}', '{$date}', 'update order', '{$invoice}', 0, '{$quantity}', '{$balances[$key]}');";
            } else {
                $insert_stock = "INSERT INTO stock(name, date, action,invoice_no, stock_in, stock_out,balance) values ('{$products[$key]}', '{$date}', 'update order', '{$invoice}', '{$quantity}', 0, '{$balances[$key]}');";
            }
            $updating = $conn->query($insert_stock);
        }
        if (!$updating) {
            $broke = 1;
            break;
        }
    }
    if ($broke == 0) {
        $products = json_encode($products);
        $quantities = json_encode($new_quantities);
        $products_details = json_encode($products_details);
        if (isset($_POST['delivery_date'])) {
            $update = "UPDATE orders set products='{$products}', quantities='{$quantities}',products_details='{$products_details}', method='{$method}', status='{$status}', delivery_date='{$delivery_date}' where id='{$id}'";
        } else {
            $update = "UPDATE orders set products='{$products}', quantities='{$quantities}',products_details='{$products_details}', method='{$method}', status='{$status}' where id='{$id}'";
        }
        $updating = $conn->query($update);
    }

    if ($updating == True) {

        $username = $_SESSION['logged_in'][0];
        $insert_in_log = "INSERT INTO activity_log(username, activity, what, object, time) values ('$username', 'Edit', 'Order', '$invoice', '$time');";
        $inserting = $conn->query($insert_in_log);
        if (!$inserting) {
            echo $conn->error;
            die();
        }

        header('Location: list-orders.php');
        echo "<br>Success";
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