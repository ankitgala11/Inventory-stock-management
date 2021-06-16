<?php
session_start();
require('header.php') ?>

<?php
$user = $_SESSION['logged_in'];
?>
<div class="container-fluid">
    <div class="d-flex justify-content-around pt-4">
        <form action="edit_me.php" class="user col-lg-5" method="POST">
            <input type="hidden" name="what" value="username">
            <input type="hidden" name="username" value="<?php echo $user[0] ?>">
            <h3 class="mb-3">Change Username</h3>
            <?php
            if (isset($_SESSION['u_response'])) {
            ?>
                <div class="text-<?php echo $_SESSION['u_response'][0]; ?> my-3">
                    <small><?php echo $_SESSION['u_response'][1]; ?></small>
                </div>
            <?php
            }
            ?>
            <div class="form-group">
                <input required type="text" name="new_username" value="<?php echo ucwords($user[0]); ?>" class="form-control form-control-user">
            </div>
            <div class="">
                <button type="submit" class="btn btn-primary btn-user btn-block col-md-4">
                    Change
                </button>
            </div>
        </form>
        <form action="edit_me.php" class="user col-lg-5" method="POST">
            <input type="hidden" name="username" value="<?php echo $user[0] ?>">
            <input type="hidden" name="what" value="password">
            <h3 class="mb-3">Change Password</h3>
            <?php
            if (isset($_SESSION['p_response'])) {
            ?>
                <div class="text-<?php echo $_SESSION['p_response'][0]; ?> my-3">
                    <small><?php echo $_SESSION['p_response'][1]; ?></small>
                </div>
            <?php
            }
            ?>
            <div class="form-group">
                <label for="">Old Password</label>
                <input required type="password" name="old_password" class="form-control form-control-user">
            </div>
            <div class="form-group">
                <label for="">New Password</label>
                <input required type="password" name="new_password" class="form-control form-control-user">
            </div>
            <div class="form-group">
                <label for="">Confirm Password</label>
                <input required type="password" name="conf_password" class="form-control form-control-user">
            </div>
            <div class="">
                <button type="submit" id="submit_pass" class="btn btn-primary btn-user btn-block col-md-4">
                    Change
                </button>
            </div>
        </form>
    </div>
</div>

<?php require('footer.php') ?>