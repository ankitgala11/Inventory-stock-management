<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require 'functions.php';
    require 'conndb.php';
    $order = json_decode(htmlspecialchars_decode($_POST['order']));
    $transactor = $order->type == "Sales" ? "Customer" : "Vendor";

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



    ?>
    <?php require('header.php');
    // print_r($order);
    ?>

    <div class="container-fluid single-order mb-4">
        <ol class="breadcrumb my-4">
            <li class="breadcrumb-item"><a href="dashborad.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list-orders.php">Transactions</a></li>
            <li class="breadcrumb-item active"><?php echo $order->type; ?></li>
            <li class="breadcrumb-item"><?php echo $order->invoice_no; ?></li>
        </ol>
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h4>Info</h4>
                            <?php if ($_SESSION['logged_in'][1] == "1000" or $_SESSION['logged_in'][1] == "100") { ?>
                                <?php if (!($order->status[1] == "Success") and !($order->status[1] == "Failed")) {
                                ?>
                                    <div class="btn btn-sm btn-info open-modal" data-modal-id="edit-info">Edit Order</div>
                                <?php }
                                ?>
                            <?php } ?>

                        </div>
                        <div class="data mt-4">
                            <div class="row">
                                <div class="col-lg-3 text-right"><?php echo $transactor ?> Name: </div>
                                <div class="col-lg-9"><?php echo $order->person; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Invoice Number</div>
                                <div class="col-lg-9"><?php echo $order->invoice_no; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Type</div>
                                <div class="col-lg-9"><?php echo $order->type; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Products</div>
                                <div class="col-lg-9">
                                    <?php
                                    foreach ($order->products as $key => $product) {
                                    ?>
                                        <div class="row col-lg-8 mb-4 mb-lg-0">
                                            <div class="col-lg-6" style="border:1px solid black;"><?php print_r($product); ?></div>
                                            <div class="col-lg-2" style="border:1px solid black;"><?php print_r($order->quantities[$key]); ?></div>
                                            <div class="col-lg-4" style="border:1px solid black;"><?php echo money($order->quantities[$key] * $order->products_details[$key][0]); ?></div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Cost</div>
                                <div class="col-lg-9"><?php echo money(total_cost($order)); ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Payment Method</div>
                                <div class="col-lg-9"><?php echo $order->method; ?></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3  text-right">Payment Status</div>
                                <div class="col-lg-9"><span class="badge badge-pill py-1 px-2 badge-<?php echo $order->status[0] ?>"><?php echo $order->status[1] ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>









    <div class="custom-modal my_modal" id="edit-info">
        <div class="my_popup">
            <h1>Edit Info</h1>
            <form action="editing-orders.php" class="add-edit-orders" method="POST">
                <input type="hidden" name="id" value="<?php echo $order->id; ?>">
                <input type="hidden" name="invoice_no" value="<?php echo $order->invoice_no; ?>">
                <input type="hidden" name="type" value="<?php echo $order->type; ?>">
                <div class="all-products">
                    <?php foreach ($order->products as $key => $product) { ?>
                        <div class="row" id="product">
                            <div class="form-group col-7">
                                <label for="">Product</label>
                                <input required type="text" autocomplete="off" name="product[]" list="productList" class="form-control product_name" value="<?php echo $product ?>">
                                <datalist id="productList">
                                    <?php
                                    foreach ($p_id as $key2 => $kid) { ?>
                                        <option value="<?php echo $p_name[$key2]; ?>">
                                        <?php } ?>
                                </datalist>
                            </div>
                            <div class="form-group col-4">
                                <label for="">Quantity</label>
                                <input type="hidden" name="old_quantity[]" value="<?php echo $order->quantities[$key] ?>">
                                <input type="number" required name="new_quantity[]" class="form-control product_quantity" value="<?php echo $order->quantities[$key] ?>">
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <button type="button" class="mb-4 btn btn-block btn-primary" id="add-product">Add Products</button>
                <div class="form-group">
                    <label for="">Payment Method</label>
                    <select name="method" required id="" class="form-control">
                        <option value="<?php echo $order->method; ?>"><?php echo $order->method; ?></option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Net Banking">Net Banking</option>
                        <option value="Card">Card</option>
                        <option value="Wallets">Wallets</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" id="select_new_status" required class="form-control">
                        <option value="<?php echo implode("-", $order->status); ?>"><?php echo $order->status[1]; ?></option>
                        <option value="success-Success">Success</option>
                        <option value="warning-Payment Due">Payment Due</option>
                        <option value="danger-Failed">Failed</option>
                    </select>
                </div>
                <div id="delivery_date" class="form-group hidden">
                    <label for="">Delivery Date</label>
                    <input type="date" required class="form-control" name="delivery_date">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>









    <?php require('footer.php'); ?>

    <script>
        (function($) {
            $(document).ready(function() {
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
                $("#delivery_date input").prop("disabled", true);
                $("#select_new_status").on("change", function() {
                    if (this.value == "success-Success") {
                        // alert(this.value);
                        $("#delivery_date").addClass("visible");
                        $("#delivery_date input").prop("disabled", false);
                    } else {
                        $("#delivery_date").removeClass("visible");
                        $("#delivery_date input").prop("disabled", true);
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