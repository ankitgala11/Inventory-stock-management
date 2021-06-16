<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    require('header.php');
    require('conndb.php');
    $select_stock_balance = "SELECT s1.* from stock s1 left join stock s2 on (s1.name = s2.name and s1.id < s2.id) where s2.id is null;";
    $names = [];
    $balances = [];
    $selecting_stock_balance = $conn->query($select_stock_balance);
    while ($row_new = $selecting_stock_balance->fetch_assoc()) {
        $balances[] = $row_new['balance'];
        $names[] = $row_new['name'];
    }
    $select = "select count(case when status like 'warning%' and type='Sales' then 1 end)as Sales_Warning, count(case when status like 'warning%' and type='Purchase' then 1 end)as Purchase_Warning, count(case when status like 'success%' and type='Sales' then 1 end)as Sales_Success, count(case when status like 'success%' and type='Purchase' then 1 end)as Purchase_Success, count(case when status like 'danger%' and type='Sales' then 1 end)as Sales_Danger, count(case when status like 'danger%' and type='Purchase' then 1 end)as Purchase_Danger, count(case when status like 'dark%' and type='Sales' then 1 end)as Sales_Dark, count(case when status like 'dark%' and type='Purchase' then 1 end)as Purchase_Dark from orders;";
    $selecting = $conn->query($select);
    $row = $selecting->fetch_assoc();

?>


    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
            
        <div class="row ">
            <div class="col-lg-3 col-md-4 pr-0">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h2>Inventory Stock</h2>
                    </div>
                    <div class="bg-white text-dark p-3 mb-1 notranslate">
                        <?php foreach ($names as $key => $name) { ?>
                            <div class="row text-left mx-0">
                                <div class="col-8"><?php echo $name ?></div>
                                <div class="col-4"><?php echo $balances[$key]; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row col-lg-9 col-md-8 ml-0">

                <div class="col-lg-4 px-0 px-sm-2 col-md-6">
                    <div class="card bg-dark text-white mb-4">
                        <div class="card-body">
                            <h2>Initiated Transactions</h2>
                        </div>
                        <div class="bg-white text-dark p-3 mb-1 notranslate">
                            <div>
                                <h5>In - <?php echo $row['Purchase_Dark'] ?></h5>
                            </div>
                            <div>
                                <h5>Out - <?php echo $row['Sales_Dark'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 px-0 px-sm-2 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <h2>Pending Payments</h2>
                        </div>
                        <div class="bg-white text-dark p-3 mb-1 notranslate">
                            <div>
                                <h5>In - <?php echo $row['Purchase_Warning'] ?></h5>
                            </div>
                            <div>
                                <h5>Out - <?php echo $row['Sales_Warning'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 px-0 px-sm-2 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h2>Successfull Transactions</h2>
                        </div>
                        <div class="bg-white text-dark p-3 mb-1 notranslate">
                            <div>
                                <h5>In - <?php echo $row['Purchase_Success'] ?></h5>
                            </div>
                            <div>
                                <h5>Out - <?php echo $row['Sales_Success'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 px-0 px-sm-2 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <h2>Failed Transactions</h2>
                        </div>
                        <div class="bg-white text-dark p-3 mb-1 notranslate">
                            <div>
                                <h5>In - <?php echo $row['Purchase_Danger'] ?></h5>
                            </div>
                            <div>
                                <h5>Out - <?php echo $row['Sales_Danger'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php require('footer.php'); ?>
<?php
} else {
    header('Location: login.php');
}
?>