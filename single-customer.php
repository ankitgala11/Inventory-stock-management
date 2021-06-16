<?php
session_start();
if (isset($_SESSION['logged_in'])) {
?>
    <?php require('header.php'); ?>
    <?php
    require('conndb.php');
    require('classes.php');
    require('functions.php');
    $customer = json_decode(htmlspecialchars_decode($_POST['customer']));
    $name = $customer->fname . " " . $customer->lname;
    $select_orders = "SELECT * FROM orders where person='$name' and type='Sales' order by invoice_no desc;";
    $rows = [];
    $selecting_orders = $conn->query($select_orders);
    if ($selecting_orders->num_rows > 0) {
        while ($row = $selecting_orders->fetch_assoc()) {
            moveElement($row, 7, 9);
            $row['quantities'] = json_decode($row['quantities']);
            $row['products'] = json_decode($row['products']);
            $row['products_details'] = json_decode($row['products_details']);
            $row['status'] = explode("-", $row['status']);
            $row = array_values($row);
            $row = new Order($row);
            array_push($rows, $row);
        }
    }
    // print_r($rows[0]);
    ?>
    <div class="container-fluid single-customer">
        <ol class="breadcrumb my-4">
            <li class="breadcrumb-item"><a href="dashborad.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list-customers.php">Customers</a></li>
            <li class="breadcrumb-item active"><?php echo $customer->fname . " " . $customer->lname; ?></li>
        </ol>
        <div class="row">
            <div class="col-4">
                <div class="card" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h4>Info</h4>
                            <?php if ($_SESSION['logged_in'][1] == "1000" or $_SESSION['logged_in'][1] == "100") { ?>
                                <div class="btn btn-sm btn-info open-modal" data-modal-id="edit-info">Edit Info</div>
                            <?php } ?>
                        </div>
                        <div class="data mt-4">
                            <div class="row">
                                <div class="col-lg-4 col-12">Name: </div>
                                <div class="col-lg-8 col-12"><?php echo $customer->fname . " " . $customer->lname; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-12 pr-0">Business:</div>
                                <div class="col-lg-8 col-12"><?php echo $customer->company_business; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-12 pr-0">Email:</div>
                                <div class="col-lg-8 col-12"><?php echo $customer->email; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-12 pr-0">Number:</div>
                                <div class="col-lg-8 col-12"><?php echo $customer->number; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <h6>Address</h6>
                        <?php echo $customer->address[0]; ?>,
                        <?php echo $customer->address[1]; ?>,
                        <?php echo $customer->address[2]; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-5" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
            <div class="card-body">
                <h4>Activity</h4>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered activity-table" id="dataTable_invoice" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Invoice Id</th>
                                <th>Order Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Pay Remaining</th>
                                <th>Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $key => $row) { ?>
                                <tr>
                                    <td>
                                        <form action="single-order.php" method="POST" id="order_<?php echo $key ?>">
                                            <input type="hidden" name="order" value="<?php print_r(htmlspecialchars(json_encode($row))) ?>">
                                            <a href="javascript:$('#order_<?php echo $key; ?>').submit();">
                                                <?php echo $row->invoice_no; ?>
                                            </a>
                                        </form>
                                    </td>
                                    <td><?php echo date_format(date_create($row->date), "Y/m/d"); ?></td>
                                    <td>
                                        <?php
                                        $total_cost = total_cost($row);
                                        echo money($total_cost);
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-<?php echo $row->status[0]; ?> py-1 px-2"><?php echo $row->status[1]; ?></span>
                                    </td>
                                    <td><?php echo $row->method; ?></td>
                                    <td><?php

                                        if ($row->advanced_payment != null) {
                                            if ($row->status[1] == "Success") {
                                                echo money(0);
                                            } else {
                                                echo money($total_cost - $row->advanced_payment);
                                            }
                                        } else {
                                            echo money(0);
                                        }

                                        ?></td>
                                    <td><?php echo date_format(date_create($row->delivery_date), "Y/m/d"); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($_SESSION['logged_in'][1] == "1000") { ?>
            <form action="deleting-customer.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $customer->id; ?>">
                <button class="btn btn-danger btn-lg btn-block" type="submit">Delete Customer</button>
            </form>
        <?php } ?>
    </div>


    <div class="custom-modal my_modal" id="edit-info">
        <div class="my_popup">
            <h1>Edit Info</h1>
            <form action="editing-customers.php" class="add-edit-customers" method="POST">
                <input type="hidden" name="id" value="<?php echo $customer->id ?>">
                <div class="form-group mt-3">
                    <label for="name">Name</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" required class="form-control" name="fname" value="<?php echo $customer->fname; ?>">
                        </div>
                        <div class="col-6">
                            <input type="text" required class="form-control" name="lname" value="<?php echo $customer->lname; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <label for="business">Company Business</label>
                    <input type="text" required name="company_business" class="form-control" value="<?php echo $customer->company_business; ?>">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $customer->email; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="number">Number</label>
                            <input type="number " required name="number" class="form-control" value="<?php echo $customer->number; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" required name="address[]" class="mb-3 form-control" value="<?php echo $customer->address[0]; ?>">
                    <input type="text" required name="address[]" class="mb-3 form-control" value="<?php echo $customer->address[1]; ?>">
                    <input type="text" required name="address[]" class="mb-3 form-control" value="<?php echo $customer->address[2]; ?>">
                </div>
                <div class="form-group">
                    <button class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php require('footer.php');
    ?>
<?php
} else {
    header('Location: login.php');
}
?>