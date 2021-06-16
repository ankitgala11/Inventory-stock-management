<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require('header.php'); ?>
    <div>
        <div class="container-fluid">
            <h1 class="mt-4">Add Product</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Product</li>
            </ol>
            <div class="form col-lg-6">
                <form action="adding-products.php" method="POST">

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

                    <div class="form-group">
                        <label for="">Product Name</label>
                        <input required type="text" class="form-control" name="name" placeholder="eg. Sosyo 10ml">
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="">Price of Box</label>
                            <input required type="text" class="form-control" name="price_per_box" placeholder="eg. 200">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="">Units per Box</label>
                            <input required type="text" class="form-control" name="units_per_box" placeholder="eg. 20">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Packaging Size</label>
                        <div class="input-group mb-3">
                            <input required type="number" class="form-control" name="packaging_size" placeholder="100">
                            <div class="input-group-append">
                                <select name="packaging_unit" class="form-control">
                                    <option value="ml">ml</option>
                                    <option value="l">l</option>
                                    <option value="gm">gm</option>
                                </select>
                            </div>
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
    header('Location: logout.php');
}
?>