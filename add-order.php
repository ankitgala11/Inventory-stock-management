<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require('header.php'); ?>
    <?php
    require 'conndb.php';
    $c_id = $c_fname = $c_lname = $v_id = $v_fname = $v_lname = $p_id = $p_name = [];
    $selectcustomer = "SELECT * FROM customers";
    $selectingcustomer = $conn->query($selectcustomer);
    if ($selectingcustomer->num_rows > 0) {
        while ($row = $selectingcustomer->fetch_assoc()) {
            $c_id[] = $row['id'];
            $c_fname[] = $row['fname'];
            $c_lname[] = $row['lname'];
        }
    }
    $selectvendor = "SELECT * FROM vendors";
    $selectingvendor = $conn->query($selectvendor);
    if ($selectingvendor->num_rows > 0) {
        while ($row = $selectingvendor->fetch_assoc()) {
            $v_id[] = $row['id'];
            $v_fname[] = $row['fname'];
            $v_lname[] = $row['lname'];
        }
    }
    $selectproduct = "SELECT * FROM products";
    $selectingproduct = $conn->query($selectproduct);
    if ($selectingproduct->num_rows > 0) {
        while ($row = $selectingproduct->fetch_assoc()) {
            $p_id[] = $row['id'];
            $p_name[] = $row['name'];
        }
    }

    $select_stock_balance = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null;";
    $selecting_stock_balance = $conn->query($select_stock_balance);
    $balances = [];
    $order_names = [];
    while ($row_new = $selecting_stock_balance->fetch_assoc()) {
        $balances[] = $row_new['balance'];
        $order_names[] = $row_new['name'];
    }

    $select_last_sales_invoice = "SELECT invoice_no from orders where type='Sales' order by id desc limit 1";
    $selecting_last_sales_invoice = $conn->query($select_last_sales_invoice);
    if ($selecting_last_sales_invoice->num_rows > 0) {
        $row = $selecting_last_sales_invoice->fetch_assoc();
        $last_inv_no = $row['invoice_no'];
    } else {
        $last_inv_no = 0;
    }

    ?>
    <div class="container-fluid">
        <?php

        ?>
        <h1 class="mt-4">Add Transaction</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Add Transaction</li>
        </ol>
        <div class="row">
            <div class="form col-lg-6">
                <form action="adding-orders.php" method="POST">
                    <?php
                    if (isset($_SESSION['order_alert'])) {
                        $alert = $_SESSION['order_alert'];
                    ?>
                        <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $alert[1]; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        unset($_SESSION['order_alert']);
                    }
                    ?>
                    <div class="form-group">
                        <label for="">Type</label>
                        <select name="type" required id="typeListInput" class="form-control">
                            <option value="Sales">Sales</option>
                            <option value="Purchase">Purchase</option>
                        </select>
                    </div>
                    <div class="Sales order-type hidden visible">
                        <div class="form-group">
                            <label for="">Sales Invoice Number</label>
                            <input required type="number" name="invoice_no" class="form-control" value="<?php echo $last_inv_no + 1 ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Customer</label>
                            <input required type="text" autocomplete="off" name="customer_name" list="customerList" class="form-control">
                            <datalist id="customerList">
                                <?php
                                foreach ($c_id as $key => $kid) { ?>
                                    <option value="<?php echo $c_fname[$key] . " " . $c_lname[$key]; ?>">
                                    <?php }
                                    ?>
                            </datalist>
                        </div>
                    </div>

                    <div class="Purchase order-type hidden">
                        <div class="form-group">
                            <label for="">Purchase Invoice Number</label>
                            <input required type="number" name="invoice_no" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Vendor</label>
                            <input required type="text" autocomplete="off" name="vendor_name" list="vendorList" class="form-control">
                            <datalist id="vendorList">
                                <?php
                                foreach ($v_id as $key => $kid) { ?>
                                    <option value="<?php echo $v_fname[$key] . " " . $v_lname[$key]; ?>">
                                    <?php }
                                    ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="all-products">
                        <div class="row" id="product">
                            <div class="form-group col-7">
                                <label for="">Product</label>
                                <input required type="text" autocomplete="off" name="product[]" list="productList" class="form-control product_name">
                                <datalist id="productList">
                                    <?php
                                    foreach ($p_id as $key => $kid) { ?>
                                        <option value="<?php echo $p_name[$key]; ?>">
                                        <?php }
                                        ?>
                                </datalist>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Quantity</label>
                                <input type="number" required name="quantity[]" class="form-control product_quantity" value="1">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mb-4 btn btn-block btn-primary" id="add-product">Add Products</button>
                    <div class="form-group">
                        <label for="">Payment Method</label>
                        <select name="method" required id="" class="form-control">
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Net Banking">Net Banking</option>
                            <option value="Card">Card</option>
                            <option value="Wallets">Wallets</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" id="status" required class="form-control">
                            <option value="dark-Initated">Initiated</option>
                            <option value="warning-Payed In Advanced">Payed In Advanced</option>
                        </select>
                    </div>
                    <div class="advanced-payment form-group hidden">
                        <label for="">Payment done in Advanced</label>
                        <input type="text" name="advanced-payment" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Order Date</label>
                        <input type="date" required class="form-control" name="order_date">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="card" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <h3>Stock</h3>
                        <?php
                        foreach ($balances as $key => $balance) {
                            echo $order_names[$key] . " - " . $balance . " boxes<br>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('footer.php'); ?>
    <script>
        (function($) {
            $(document).ready(function() {
                $(".Purchase").find('input').prop("disabled", true);
                $("#typeListInput").on("change", function() {
                    $(".visible").find("input").prop("disabled", true);
                    $(".visible").removeClass("visible");
                    $("." + this.value).find("input").prop("disabled", false);
                    $("." + this.value).parent().find(".cloned_product").remove();
                    $("." + this.value).parent().find(".product_name").val("");
                    $("." + this.value).parent().find(".product_quantity").val("1");
                    $("." + this.value).addClass("visible");
                });
                var count = 1;
                $("#add-product").on("click", function() {
                    addProduct(count);
                    count++;

                });

                function addProduct(id) {
                    var product = $("#product").clone();
                    product[0].id += "_" + id;
                    $(product).addClass("cloned_product");
                    (product).find(".product_name").val("");
                    (product).find(".product_quantity").val("1");

                    var div = document.createElement("div");
                    div.classList = "form-group col-1 d-flex align-items-end";

                    var button = document.createElement("button");
                    button.classList = "btn btn-outline-danger";
                    button.type = "button";
                    button.innerHTML = "-";
                    button.addEventListener("click", function() {
                        $(".all-products #product_" + id).find("input").prop("disabled", true);
                        $(".all-products #product_" + id).addClass("hidden");

                    });

                    div.appendChild(button);

                    product.append(div);
                    product.appendTo(".all-products")
                }


                $("#status").on("change", function() {
                    if (this.value == "warning-Payed In Advanced") {
                        $(".advanced-payment").addClass("visible");
                        $(".visible").find("input").prop("disabled", false);
                    } else {
                        $(".visible").find("input").prop("disabled", true);
                        $(".advanced-payment").removeClass("visible");
                    }
                });
            });
        })(jQuery);
    </script>

<?php
} else {
    header('Location: login.php');
}
?>