<?php
session_start();
require('conndb.php');
$select = "SELECT * from settings;";
$selecting = $conn->query($select);
$row = $selecting->fetch_assoc();
$name = $row['name'];
?>
<?php require('header.php'); ?>
<div class="container-fluid">
    <h1 class="mt-4">Settings</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>
    <form action="editing-settings.php" method="POST" class="col-lg-6">
        <?php
        if (isset($_SESSION['settings_alert'])) {
            $alert = $_SESSION['settings_alert'];
        ?>
            <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissible fade show" role="alert">
                <?php echo $alert[1]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
            unset($_SESSION['settings_alert']);
        }
        ?>

        <div class="form-group">
            <label for="">Company Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        <div class="">
            <button type="submit" class="btn btn-primary">Update Settings</button>
        </div>
    </form>
</div>
<?php require('footer.php'); ?>