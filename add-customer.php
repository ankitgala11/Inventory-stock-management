<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require('header.php');
    ?>
    <div>
        <div class="container-fluid">
            <h1 class="mt-4">Add Customer</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Customer</li>
            </ol>
            <div class="form col-lg-6">

                <?php
                if (isset($_SESSION['customer_alert'])) {
                    $alert = $_SESSION['customer_alert'];
                ?>
                    <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert[1]; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                    unset($_SESSION['customer_alert']);
                }
                ?>

                <form action="adding-customers.php" class="add-edit-customers-vendors" method="POST">
                    <div class="form-group mt-3">
                        <label for="name">Name</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="text" required class="form-control" name="fname" placeholder="first name">
                            </div>
                            <div class="col-6">
                                <input type="text" required class="form-control" name="lname" placeholder="last name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label for="business">Company Business</label>
                        <input type="text" required name="company_business" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="number">Number</label>
                                <input type="number " required name="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" required name="address[]" class="mb-3 form-control" placeholder="Shop No, Building Number">
                        <input type="text" required name="address[]" class="mb-3 form-control" placeholder="Street Name / Area">
                        <input type="text" required name="address[]" class="mb-3 form-control" placeholder="Mira Road (East)">
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