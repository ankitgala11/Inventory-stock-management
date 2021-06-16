<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php
    require('header.php');
    require('classes.php');
    require('conndb.php');
    $id = $fname = $lname = $company_business = $number = $email = $address = $address_alt = $customers = [];
    $select_customers = "SELECT * FROM customers;";
    $selecting = $conn->query($select_customers);
    if ($selecting->num_rows > 0) {
        while ($row = $selecting->fetch_assoc()) {
            $id[] = $row['id'];
            $fname[] = $row['fname'];
            $lname[] = $row['lname'];
            $company_business[] = $row['company_business'];
            $email[] = $row['email'];
            $number[] = $row['number'];
            $address[] = json_decode($row['address']);
        }
        foreach ($fname as $key => $name) {
            $details = [$id[$key], $name, $lname[$key], $company_business[$key],  $email[$key], $number[$key], $address[$key]];
            $customer = new Customer($details);
            array_push($customers, $customer);
        }
    }

    ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4">Customers</h1>
            <a href="add-customer.php" class="btn btn-success btn-sm">Add Customer</a>
        </div>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Customers List</li>
        </ol>
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
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company Business</th>
                        <th>Number</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $key => $customer) { ?>
                        <tr>
                            <td>
                                <form action="single-customer.php" method="POST" id="customer_<?php echo $key ?>">
                                    <input type="hidden" name="customer" value="<?php print_r(htmlspecialchars(json_encode($customer))) ?>">
                                    <a href="javascript:$('#customer_<?php echo $key; ?>').submit();">
                                        <?php echo $customer->fname . " " . $customer->lname; ?>
                                    </a>
                                </form>
                            </td>
                            <td><?php echo $customer->company_business; ?></td>
                            <td><?php echo $customer->number; ?></td>
                            <td><?php print_r($customer->address[2]); ?></td>
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