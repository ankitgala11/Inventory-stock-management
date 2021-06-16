<?php
session_start();
if (isset($_SESSION['logged_in'])) {

?>
    <?php require('header.php');
    require('classes.php');
    require('conndb.php');
    $id = $fname = $lname = $company_business = $number = $email = $address = $address_alt = $vendors = [];
    $select_vendors = "SELECT * FROM vendors;";
    $selecting = $conn->query($select_vendors);
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
            $vendor = new Vendor($details);
            array_push($vendors, $vendor);
        }
    }

    ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-4">Vendors</h1>
            <a href="add-vendor.php" class="btn btn-success btn-sm">Add vendor</a>
        </div>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">vendors List</li>
        </ol>
        <?php
        if (isset($_SESSION['vendor_alert'])) {
            $alert = $_SESSION['vendor_alert'];
        ?>
            <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissible fade show" role="alert">
                <?php echo $alert[1]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
            unset($_SESSION['vendor_alert']);
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
                    <?php foreach ($vendors as $key => $vendor) { ?>
                        <tr>
                            <td>
                                <form action="single-vendor.php" method="POST" id="vendor_<?php echo $key ?>">
                                    <input type="hidden" name="vendor" value="<?php print_r(htmlspecialchars(json_encode($vendor))) ?>">
                                    <a href="javascript:$('#vendor_<?php echo $key; ?>').submit();">
                                        <?php echo $vendor->fname . " " . $vendor->lname; ?>
                                    </a>
                                </form>
                            </td>
                            <td><?php echo $vendor->company_business; ?></td>
                            <td><?php echo $vendor->number; ?></td>
                            <td><?php print_r($vendor->address[2]); ?></td>
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