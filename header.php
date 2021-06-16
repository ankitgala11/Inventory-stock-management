<?php date_default_timezone_set('Asia/Kolkata');
require('conndb.php');
$select_settings = "SELECT * from settings;";
$selecting_settings = $conn->query($select_settings);
$row = $selecting_settings->fetch_assoc();
$name = $row['name'];
?>
<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $name ?></title>
    <link href="assets/datatable.bootstrap.css" rel="stylesheet" />
    <link href="assets/datatables-buttons.css" rel="stylesheet" />
    <link href="my_vendors/modal.css" rel="stylesheet" />
    <link href="my_vendors/scrollbar.css" rel="stylesheet" />
    <script src="assets/fontawesome.js"></script>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed sb-sidenav-toggled">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">
            <?php echo $name; ?>
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto mr-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="profile.php">Profile</a>
                    <?php if ($_SESSION['logged_in'][1] == "1000") { ?>
                        <a class="dropdown-item" href="settings.php">Settings</a>
                        <a class="dropdown-item" href="activity_log.php">Activity Log</a>
                        <div class="dropdown-divider"></div>
                    <?php } ?>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav  accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <?php if ($_SESSION['logged_in'][1] == "100" or $_SESSION['logged_in'][1] == "1000") {
                        ?>
                            <a class="nav-link" href="users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-alt fa-fw"></i></div>
                                Users
                            </a>
                        <?php } ?>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseCustomers">
                            <div class="sb-nav-link-icon"><i class="fas fa-users text-muted"></i></div>
                            Customers
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCustomers">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="list-customers.php">Customer List</a>
                                <a class="nav-link" href="add-customer.php">Add Customer</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseVendors">
                            <div class="sb-nav-link-icon"><i class="fas fa-users text-muted"></i></div>
                            Vendors
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseVendors">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="list-vendors.php">Vendor List</a>
                                <a class="nav-link" href="add-vendor.php">Add Vendor</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseTransactions">
                            <div class="sb-nav-link-icon"><i class="fas fa-rupee-sign text-muted"></i></div>
                            Transactions
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTransactions">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="list-orders.php">Transactions List</a>
                                <a class="nav-link" href="add-order.php">Add Transaction</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#collapseProducts">
                            <div class="sb-nav-link-icon"><i class="fab fa-apple text-muted"></i></div>
                            Products
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseProducts">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="list-products.php">Product List</a>
                                <a class="nav-link" href="add-product.php">Add Product</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo ucfirst($_SESSION['logged_in'][0]); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>