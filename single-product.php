<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    require('header.php');
    require('conndb.php');
    $product = json_decode(htmlspecialchars_decode($_POST['product']));
    $name = $product->name;
    $select_stock_balance = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null and s1.name='$name';";
    $selecting_stock_balance = $conn->query($select_stock_balance);
    $row_new = $selecting_stock_balance->fetch_assoc();
    $balance = $row_new['balance'];

?>

    <div class="container-fluid single-product">
        <ol class="breadcrumb my-4">
            <li class="breadcrumb-item"><a href="dashborad.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="list-products.php">Products</a></li>
            <li class="breadcrumb-item active"><?php echo $product->name; ?></li>
        </ol>
        <div class="pt-4">

            <?php if ($_SESSION['logged_in'][1] == "1000" or $_SESSION['logged_in'][1] == "100") { ?>
                <button class="btn btn-sm btn-info open-modal" data-modal-id="edit-info">Edit Product</button>
            <?php } ?>
            <?php if ($_SESSION['logged_in'][1] == "1000") { ?>
                <form action="deleting-product.php" method="POST" class="d-inline-block">
                    <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                    <button class="btn btn-danger btn-sm" type="submit">Delete product</button>
                </form>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card my-4" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <h4><?php echo $product->name; ?></h4>
                        <div class="mt-3"><b>Price Per Box:</b> <?php echo $product->price_per_box; ?></div>
                        <div class="mt-1"><b>Price Per Unit:</b> <?php echo $product->price_per_box / $product->units_per_box;; ?></div>
                        <div class="mt-1"><b>Units Per Box:</b> <?php echo $product->units_per_box; ?></div>
                        <div class="mt-1"><b>Quantity:</b> <?php echo $product->packaging_size . $product->packaging_unit; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card my-4" style="border-radius:0;border:0;box-shadow:0px 0px 5px #33333333">
                    <div class="card-body">
                        <h4>Stock</h4>
                        No of Boxes: <?php echo $balance ?><br>
                        No of Units: <?php echo $balance * $product->units_per_box; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="custom-modal my_modal" id="edit-info">
        <div class="my_popup">
            <h1>Edit Info</h1>
            <form action="editing-products.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $product->id ?>">
                <div class="form-group">
                    <label for="">Product Name</label>
                    <input required type="text" class="form-control" name="name" value="<?php echo $product->name ?>">
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="">Price of Box</label>
                        <input required type="text" class="form-control" name="price_per_box" value="<?php echo $product->price_per_box ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="">Units per Box</label>
                        <input required type="text" class="form-control" name="units_per_box" value="<?php echo $product->units_per_box ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Packaging Size</label>
                    <div class="input-group mb-3">
                        <input required type="text" class="form-control" name="packaging_size" value="<?php echo $product->packaging_size ?>">
                        <div class="input-group-append">
                            <select name="packaging_unit" class="form-control">
                                <option value="<?php echo $product->packaging_unit ?>"><?php echo $product->packaging_unit ?></option>
                                <option value="ml">ml</option>
                                <option value="gm">gm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <?php require('footer.php'); ?>
<?php
} else {
    header('Location: login.php');
}
?>