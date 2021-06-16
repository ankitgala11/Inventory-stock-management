<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php require('header.php');
    require('classes.php');
    require('conndb.php');
    $id = $name = $price_per_box = $price_per_unit = $units_per_box = $packaging_size = $packaging_unit = $products = [];
    $select_products = "SELECT * FROM products;";
    $selecting = $conn->query($select_products);
    if ($selecting->num_rows > 0) {
        while ($row = $selecting->fetch_assoc()) {
            $id[] = $row['id'];
            $name[] = $row['name'];
            $price_per_box[] = $row['price_per_box'];
            $units_per_box[] = $row['units_per_box'];
            $packaging_size[] = $row['packaging_size'];
            $packaging_unit[] = $row['packaging_unit'];
        }
        foreach ($name as $key => $naam) {
            $details = [$id[$key], $naam, $price_per_box[$key], $units_per_box[$key],  $packaging_size[$key], $packaging_unit[$key]];
            $product = new Product($details);
            array_push($products, $product);
        }
    }

    ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4">Products</h1>
            <a href="add-product.php" class="btn btn-success btn-sm">Add product</a>
        </div>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Products List</li>
        </ol>
        <?php
        if (isset($_SESSION['product_alert'])) {
            $alert = $_SESSION['product_alert'];
        ?>
            <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissible fade show" role="alert">
                <?php echo $alert[1]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
            unset($_SESSION['product_alert']);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Price / Box</th>
                        <th>Units / Box</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $key => $product) { ?>
                        <tr>
                            <td>
                                <form action="single-product.php" method="POST" id="product_<?php echo $key ?>">
                                    <input type="hidden" name="product" value="<?php print_r(htmlspecialchars(json_encode($product))) ?>">
                                    <a href="javascript:$('#product_<?php echo $key; ?>').submit();">
                                        <?php echo $product->name; ?>
                                    </a>
                                </form>
                            </td>

                            <td><?php echo $product->packaging_size . $product->packaging_unit; ?></td>
                            <td><?php echo $product->price_per_box; ?></td>
                            <td><?php echo $product->units_per_box; ?></td>
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