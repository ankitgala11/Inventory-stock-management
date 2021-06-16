<?php
session_start();
require('header.php') ?>

<?php
if (isset($_SESSION['logged_in'])) {
    $user = $_SESSION['logged_in'];
    require('conndb.php');
    if ($user[1] < 100) {
?>
        <div class="container-fluid">You are not allowed to view this <a href="dashboard.php">Click Here</a></div>
    <?php
    } else {
        $query = $conn->query("SELECT id, username, type from users where type<'$user[1]' order by type desc");
        $data = [];
        while ($datq = $query->fetch_assoc()) {
            array_push($data, $datq);
        }

        $types = [100, 10];
    ?>
        <div class="container-fluid">
            <div class="d-flex flex-wrap row <?php if ($user[1] >= 1000) { ?>justify-content-around<?php } ?> pt-4">
                <div class="user col-lg-5 <?php if ($user[1] >= 1000) { ?>mb-5<?php } ?>">
                    <h3 class="mb-3">Users</h3>
                    <?php
                    if (isset($_SESSION['eu_response'])) {
                    ?>
                        <div class="text-<?php echo $_SESSION['eu_response'][0]; ?> my-3">
                            <small><?php echo $_SESSION['eu_response'][1]; ?></small>
                        </div>
                    <?php
                    }
                    ?>
                    <?php foreach ($data as $key => $value) { ?>
                        <div class="form-group">
                            <label for=""><?php echo ucwords($value['username']); ?></label>
                            <?php if ($user[1] >= 1000) { ?>
                                <a href="#" class="toggle_changer"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i></a>
                                <div class="row hidden" id="type_changer">
                                    <form action="editing-users.php" method="POST" class="w-100 d-inline-block">
                                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                                        <select name="type" class="form-control col-5 mr-2 d-inline-block">
                                            <option value="<?php echo $value['type']; ?>"><?php echo $value['type']; ?></option>
                                            <?php foreach ($types as $type) {
                                                if ($type == $value['type']) continue;
                                            ?>
                                                <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" id="submit_pass" class="btn btn-primary btn-user btn-block col-4 d-inline-block">
                                            Update
                                        </button>
                                    </form>
                                    <form action="deleting-users.php" method="POST" class="w-100 d-inline-block">
                                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                                        <button type="submit" id="submit_pass" class="btn btn-danger btn-user btn-block col-4 d-inline-block">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($user[1] >= 1000) { ?>
                    <div>
                        <h3>Register New User</h3>
                        <a href="register.php" class="btn btn-primary">Register</a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php require('footer.php') ?>
        <script>
            $(".toggle_changer").on("click", function(e) {
                $(this).parent().find('#type_changer').toggleClass("visible");
            });
        </script>
    <?php } ?>
<?php  } else {
    header('index.php');
} ?>