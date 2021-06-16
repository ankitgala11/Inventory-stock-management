<?php
require('functions.php');
require('conndb.php');

$select = "SELECT * from settings;";
$selecting = $conn->query($select);
$row = $selecting->fetch_assoc();
$app_name = $row['name'];
$order = json_decode(htmlspecialchars_decode($_POST['order']));
// print_r($order);
$name = explode(" ", $order->person);
$fname = $name[0];
$lname = $name[1];
$data = [];
$selectcustomer = "SELECT * FROM customers where fname='$fname' and lname='$lname'";
$selectingcustomer = $conn->query($selectcustomer);
if ($selectingcustomer->num_rows > 0) {
    while ($row = $selectingcustomer->fetch_assoc()) {
        $data[] = $row; 
    }
}
$c_address = json_decode($data[0]['address']);
// print_r($data);
// echo "<br>";
// print_r($order);
if($order->advanced_payment == '')$order->advanced_payment = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" />    
    <title><?php echo $app_name; ?> Invoice</title>
    <style>
        body{
            padding:30px 0;
        }
        .title{
            color:dodgerblue;
        }
        h4, h3, h5{
            font-weight:300
        }
        hr.hr{            
            margin-top: 5rem;
            margin-bottom: 1rem;
            /* border: 10px; */
            border-top: 5px solid dodgerblue;
        }
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-between">
        <div>
            <h1 class="display-3" style="font-weight:500">
                <?php echo $app_name ?>
            </h1>
            <h1 class="display-4">Invoice</h1>
        </div>
        <h4 class="text-right" style="font-weight:200">            
            Cecilia Chapman<br>
            711-2880 Nulla St.<br>
            Mankato Mississippi 96522<br>
        </h4>
    </div>
    <div style="margin-top:150px;" class="d-flex justify-content-between">
        <div>
            <h4 class="title">Billed To:</h4>
            <h5 style="font-weight:500"><?php echo $order->person; ?></h5>
            <h5>
                <?php print_r($c_address[0]); ?>
            </h5>
            <h5>
                <?php print_r($c_address[1]); ?>
            </h5>
            <h5>
                <?php print_r($c_address[2]); ?>
            </h5>
        </div>
        <div>
            <div>
                <h4 class="title">Issue Date:</h4>
                <h5><?php echo date_format(date_create($order->date), 'd-m-Y'); ?></h5>
            </div>
            <div>
                <h4 class="title">Delivery Date:</h4>
                <h5>
                <?php
                if($order->delivery_date != ''){
                    echo date_format(date_create($order->delivery_date), 'd-m-Y');
                }else{
                    echo "--";
                } 
                ?>
                 </h5>                
            </div>
        </div>
        <div>
            <h4 class="title">Invoice No.</h4>
            <h5><?php echo $order->invoice_no; ?></h5>
        </div>
        <div class="text-right">
            <h4 class="title">Total Cost</h4>
            <h5 class="display-4"><?php echo money(total_cost($order)); ?></h5>
        </div>        
    </div>
    
    <hr class="hr">
    <div class="d-flex">
            <div class="col-6">
                <h4 class="title">Description</h4>
                <?php foreach($order->products as $product){ ?>
                    <h5><?php echo $product; ?> Box</h5>
                <?php } ?>
            </div>
            <div class="col-2">
                <h4 class="title">Rate</h4>
                <?php foreach($order->products_details as $product){ ?>
                    <h5><?php echo money($product[0]); ?></h5>
                <?php } ?>
            </div>
            <div class="col-2">
                <h4 class="title">Qty</h4>
                <?php foreach($order->quantities as $product){ ?>
                    <h5><?php echo $product; ?></h5>
                <?php } ?>
            </div>
            <div class="col-2 text-right">
                <h4 class="title">Cost</h4>
                <?php foreach($order->quantities as $key=>$product){ ?>
                    <h5>
                        <?php echo money($product * $order->products_details[$key][0]); ?>    
                    </h5>
                <?php } ?>                
            </div>
        </div>
        <div class="d-flex justify-content-end mt-5">
            <table class="col-4 text-right">
                <tbody>
                    <tr>
                        <td><h4 style="font-weight:500">Subtotal</h4></td>
                        <td><h4><?php echo money(total_cost($order)); ?></h4></td>
                    </tr>
                    <tr style="border-bottom:1px solid #444;">
                        <td><h4 style="font-weight:500">Tax</h4></td>
                        <td><h4><?php echo money(0); ?></h4></td>
                    </tr>                    
                    <tr>
                        <td><h4 style="font-weight:500">Total</h4></td>
                        <td><h4><?php echo money(total_cost($order)); ?></h4></td>
                    </tr>
                    <tr  style="border-bottom:1px solid #444;">
                        <td><h4 style="font-weight:500">Advanced Payment</h4></td>
                        <?php if($order->status[0] == 'success'){ ?>
                            <td>
                                <h4><?php echo money(total_cost($order)); ?></h4>
                            </td>
                        <?php }else{ ?>
                            <td>
                                <h4><?php echo money($order->advanced_payment); ?></h4>
                            </td>
                        <?php } ?>
                        
                    </tr>
                    <tr>
                        <td>
                            <h4 style="font-weight:500" class="text-<?php echo $order->status[0] ?>">Amount Due</h4>
                        </td>                        
                        <?php if($order->status[0] == 'success'){ ?>
                            <td>
                                <h4><?php echo money(0); ?></h4>
                            </td>
                        <?php }else{ ?>
                            <td>
                                <h4><?php echo money(total_cost($order) - $order->advanced_payment); ?></h4>
                            </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    <script>
        window.print();
    </script>
</body>
</html>

