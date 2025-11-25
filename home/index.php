<?php
$page = "Home";
include('./../includes/header.php');
include("../includes/check_login_handle.php");
include('./../includes/sidebar.php');
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include('./../includes/navbar.php') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?php echo $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?php echo $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); // Clear the session message 
                ?>
            <?php endif; ?>

            <!-- Dashboard Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <div class="row">

                <!-- Left Side (Cards) -->
                <div class="col-xl-9 col-lg-8">

                    <div class="row">

                        <!-- Total Products -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-box-open fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Products
                                            </div>
                                            <h4 class="font-weight-bold text-gray-800 mb-0"><?= count($invObj->get_All_Products()) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Categories -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-layer-group fa-2x text-success"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Categories
                                            </div>
                                            <h4 class="font-weight-bold text-gray-800 mb-0"><?= count($invObj->get_All_Categories()) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Items -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Low Stock Items
                                            </div>
                                            <h4 class="font-weight-bold text-gray-800 mb-0"><?= count($invObj->get_Low_Stock_Products()) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NEW: Monthly Stock Movements -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-info shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas fa-exchange-alt fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Stock Movements (This Month)
                                            </div>

                                            <?php
                                            $resArr = $invObj->get_Stock_Movements_This_Month();
                                            $totalMov = $resArr['stock_in'] + $resArr['stock_out'];
                                            ?>

                                            <h4 class="font-weight-bold text-gray-800 mb-0">
                                                <?= $totalMov ?>
                                                <small class="text-muted" style="font-size: 14px;">
                                                    (IN: <?= $resArr['stock_in'] ?> | OUT: <?= $resArr['stock_out'] ?>)
                                                </small>
                                            </h4>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

                <div class="col-xl-3 col-lg-4 mb-4">
                    <div class="card shadow-sm border-left-danger">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">
                                <i class="fas fa-bell mr-1"></i> Low Stock Alerts
                            </h6>
                        </div>

                        <div class="card-body low-stock-box">

                            <?php
                            $low_stock = $invObj->get_Low_Stock_Products();

                            if (count($low_stock) == 0) {
                                echo '<div class="alert alert-success py-2 mb-2 text-center">
                                    <strong>No Low Stock!</strong><br>
                                    <small>All products have sufficient stock</small>
                                </div>';
                            }

                            foreach ($low_stock as $item):

                                $product = $item['productname'];
                                $stock   = $item['productstock'];
                                $minReq  = $item['productminstock'];
                            ?>
                                <div class="alert alert-danger py-2 mb-2">
                                    <strong><?= $product ?></strong><br>
                                    <small>Only <?= $stock ?> left (Min: <?= $minReq ?>)</small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                $stockInData  = $invObj->get_StockIn_Monthly();
                $stockOutData = $invObj->get_StockOut_Monthly();

                $months     = [];
                $stock_in   = [];
                $stock_out  = [];

                $allMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                foreach ($allMonths as $m) {
                    $months[] = $m;

                    $inVal = 0;
                    foreach ($stockInData as $row) {
                        if ($row['month'] == $m) {
                            $inVal = $row['total_in'];
                            break;
                        }
                    }

                    $outVal = 0;
                    foreach ($stockOutData as $row) {
                        if ($row['month'] == $m) {
                            $outVal = $row['total_out'];
                            break;
                        }
                    }

                    $stock_in[]  = $inVal;
                    $stock_out[] = $outVal;
                }

                ?>

                <!-- Stock IN Chart -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card shadow-sm chart-card">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Stock IN Overview</h6>
                        </div>
                        <div class="card-body chart-container">
                            <canvas id="stockInChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Stock OUT Chart -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card shadow-sm chart-card">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Stock OUT Overview</h6>
                        </div>
                        <div class="card-body chart-container">
                            <canvas id="stockOutChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Latest 5 Stock Movements</h6>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Movement</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Party</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $all_movements = $invObj->get_Stock_Ledger_Filter("", "", "", "");
                                    $latestFive = array_slice($all_movements, 0, 5);

                                    $count = 1;

                                    if (empty($latestFive)) {
                                        echo "<tr><td colspan='6' class='text-center text-muted'>No stock movements found.</td></tr>";
                                    }

                                    foreach ($latestFive as $row):

                                        $encPID = $invObj->encryptData($row['productuid']);
                                        $name   = $invObj->get_Single_Colum_Product($encPID, 'productname');
                                        $sku    = $invObj->get_Single_Colum_Product($encPID, 'productsku');

                                        $qtySign  = ($row['movement'] == "IN") ? "+" : "-";
                                        $qtyColor = ($row['movement'] == "IN") ? "text-success" : "text-danger";
                                        $badge    = ($row['movement'] == "IN") ? "success" : "danger";

                                    ?>
                                        <tr>
                                            <td><?= $count ?></td>
                                            <td><?= date("d M Y", strtotime($row['movementdate'])) ?></td>
                                            <td><span class="badge badge-<?= $badge ?>"><?= $row['movement'] ?></span></td>
                                            <td><?= $name ?> <br><small class="text-muted"><?= $sku ?></small></td>
                                            <td class="<?= $qtyColor ?> font-weight-bold">
                                                <?= $qtySign . $row['qty'] ?>
                                            </td>
                                            <td><?= $row['party'] ?: "-" ?></td>
                                        </tr>

                                    <?php $count++;
                                    endforeach; ?>
                                </tbody>


                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <!-- /.container-fluid -->

    </div>

    <?php include('./../includes/footer.php'); ?>

</div>

<script>
    let months = <?= json_encode($months) ?>;
    let stockInVals = <?= json_encode($stock_in) ?>;
    let stockOutVals = <?= json_encode($stock_out) ?>;

    // Stock IN Chart
    new Chart(document.getElementById("stockInChart"), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: "Stock IN",
                data: stockInVals,
                backgroundColor: "rgba(78,115,223,0.1)",
                borderColor: "rgba(78,115,223,1)",
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });

    // Stock OUT Chart
    new Chart(document.getElementById("stockOutChart"), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: "Stock OUT",
                data: stockOutVals,
                backgroundColor: "rgba(231,74,59,0.1)",
                borderColor: "rgba(231,74,59,1)",
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });
</script>