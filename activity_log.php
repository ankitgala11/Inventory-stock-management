<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
} else {
    if ($_SESSION['logged_in'][1] < 1000) {
        header('Location: dashboard.php');
    } else {

        require('header.php');
        require('functions.php');
        require('conndb.php');
        $select = "SELECT * FROM activity_log order by id desc";
        $selecting = $conn->query($select);
        $logs = [];
        if ($selecting->num_rows > 0) {
            while ($row = $selecting->fetch_assoc()) {
                array_push($logs, $row);
            }
        }
?>

        <div class="container-fluid">
            <h1 class="mt-4">Activity Log</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Activity Log</li>
            </ol>
            <div class="table-responsive">
                <table class="table table-bordered" id="activityLog" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Username</th>
                            <th>Activity</th>
                            <th>What</th>
                            <th>Object</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $key => $log) { ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo ucwords($log['username']); ?></td>
                                <td><?php echo $log['activity']; ?></td>
                                <td><?php echo $log['what']; ?></td>
                                <td><?php echo $log['object']; ?></td>
                                <td><?php echo $log['time']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <a href="clear_activity_log.php" onclick="confirm('Do you want to clear the log? Consider saving a backup?');" class="btn btn-danger my-5 btn-block">Clear Activity Log</a>
        </div>
        <?php require('footer.php'); ?>
        <script>
            $(document).ready(function() {
                $('#activityLog').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'csv',
                            className: 'btn btn-primary btn-sm'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-primary btn-sm'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-primary btn-sm'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-primary btn-sm'
                        }
                    ],
                    "columnDefs": [{
                        "targets": [0],
                        "visible": false,
                    }, ]
                });

            });
        </script>
<?php }
}
