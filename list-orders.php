<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require('header.php');
    require('classes.php');
    require('conndb.php');
    require('functions.php');

    $id = $type = $person = $invoice_no = $products = $quantities = $orders = $products_details = $method = $status = $advanced_payment = $date = $delivery_date = [];

    $select = "SELECT * FROM orders;";
    $selecting = $conn->query($select);
    if ($selecting->num_rows > 0) {
        while ($row = $selecting->fetch_assoc()) {
            $id[] = $row['id'];
            $type[] = $row['type'];
            $person[] = $row['person'];
            $invoice_no[] = $row['invoice_no'];
            $products[] = json_decode($row['products']);
            $quantities[] = json_decode($row['quantities']);
            $products_details[] = json_decode($row['products_details']);
            $method[] = $row['method'];
            $status[] = explode("-", $row['status']);
            $advanced_payment[] = $row['advanced_payment'];
            $date[] = $row['date'];
            $delivery_date[] = $row['delivery_date'];
        }

        foreach ($person as $key => $naam) {
            $details = [$id[$key], $type[$key], $naam, $invoice_no[$key], $products[$key], $quantities[$key], $products_details[$key], $method[$key], $status[$key], $advanced_payment[$key], $date[$key], $delivery_date[$key]];
            $order = new Order($details);
            array_push($orders, $order);
        }
    }

    ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4">Transactions</h1>
            <a href="add-order.php" class="btn btn-success btn-sm">Add Transaction</a>
        </div>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Transactions List</li>
        </ol>
        <?php
        ?>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Invoice</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Cost</th>
                        <th>Rem.$</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Delivery</th>                    
                        <th>Invoice</th>                    
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $key => $order) {                    ?>

                        <tr>
                            <td><?php echo $order->id; ?></td>
                            <td>
                                <form action="single-order.php" method="POST" id="order_<?php echo $key ?>">
                                    <input type="hidden" name="order" value="<?php print_r(htmlspecialchars(json_encode($order))) ?>">
                                    <a href="javascript:$('#order_<?php echo $key; ?>').submit();">
                                        <?php echo $order->invoice_no; ?>
                                    </a>
                                </form>
                            </td>
                            <td><?php echo $order->type; ?></td>
                            <td><?php echo $order->person ?></td>
                            <td><?php
                                $total_cost = total_cost($order);
                                echo money($total_cost); ?></td>
                            <td><?php

                                if ($order->advanced_payment != null) {
                                    if ($order->status[1] == "Success") {
                                        echo money(0);
                                    } else {
                                        echo money($total_cost - $order->advanced_payment);
                                    }
                                } else {
                                    echo money(0);
                                }

                                ?></td>
                            <td><?php echo $order->method; ?></td>
                            <td><span class="badge badge-pill badge-<?php echo $order->status[0] ?>"><?php echo $order->status[1] ?></span></td>
                            <td><?php echo date_format(date_create($order->date), "d-m-Y"); ?></td>
                            <td><?php echo isset($order->delivery_date) ? date_format(date_create($order->delivery_date), "d-m-Y") : " -- "; ?></td>
                            <td>
                                <form action="print_invoice.php" method="POST">
                                    <input type="hidden" name="order" value="<?php print_r(htmlspecialchars(json_encode($order))) ?>">
                                    <button <?php if($order->type != "Sales") echo "disabled"; ?> type="submit" class="btn btn-primary badge badge-pill badge-primary">Print</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php require('footer.php'); ?>
<?php
} else {
    header('Location: login.php');
}
?>