<?php
$page = "Stock Ledger";
include('../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('../../includes/sidebar.php');

// Filters
$product  = "";
$movement = isset($_GET['movement']) ? $_GET['movement'] : "";
$from     = isset($_GET['from_date']) ? $_GET['from_date'] : "";
$to       = isset($_GET['to_date']) ? $_GET['to_date'] : "";

if (!empty($_GET['product'])) {
    $product = $invObj->decryptData($_GET['product']);
}

// Get ledger data
$ledger_data = $invObj->get_Stock_Ledger_Filter($product, $movement, $from, $to);

// ---- PRODUCT CACHE FOR PERFORMANCE ----
$allProducts = [];
foreach ($invObj->get_All_Products() as $p) {
    $allProducts[$p['productuid']] = $p;
}
?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <?php include('../../includes/navbar.php') ?>

        <div class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?php echo $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?php echo $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); // Clear the session message 
                ?>
            <?php endif; ?>

            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock Ledger</h1>
                <form action="./../redirect" method="POST" style="display:inline-block;">
                    <input type="hidden" name="ledger_data"
                        value='<?= htmlspecialchars(json_encode($ledger_data)) ?>'>

                    <button type="submit" name="export_csv_stock_ledger"
                        class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </form>
            </div>

            <!-- FILTERS -->
            <form method="GET" class="mb-4">
                <div class="form-row">

                    <!-- Product -->
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Product</label>
                        <select name="product" class="form-control">
                            <option value="">All Products</option>

                            <?php
                            foreach ($allProducts as $prd) {
                                $encID = $invObj->encryptData($prd['productuid']);
                                $selected = ($product == $prd['productuid']) ? "selected" : "";
                                echo "<option value='$encID' $selected>{$prd['productsku']} - {$prd['productname']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Movement Type -->
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Movement Type</label>
                        <select name="movement" class="form-control">
                            <option value="" <?= ($movement == "") ? "selected" : "" ?>>All</option>
                            <option value="IN" <?= ($movement == "IN") ? "selected" : "" ?>>Stock IN</option>
                            <option value="OUT" <?= ($movement == "OUT") ? "selected" : "" ?>>Stock OUT</option>
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="form-group col-md-2">
                        <label class="font-weight-bold">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="<?= $from ?>">
                    </div>

                    <!-- To Date -->
                    <div class="form-group col-md-2">
                        <label class="font-weight-bold">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="<?= $to ?>">
                    </div>

                    <!-- Filter -->
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>

                    <!-- Clear -->
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <a href="./stock-ledger" class="btn btn-danger btn-block" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
            <!-- END FILTERS -->

            <!-- TABLE -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Movement Records</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Date</th>
                                    <th>Movement</th>
                                    <th>Product</th>
                                    <th>Previous</th>
                                    <th>Qty</th>
                                    <th>New</th>
                                    <th>Party</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (empty($ledger_data)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            No stock movement found for selected filters.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php
                                $count = 1;
                                foreach ($ledger_data as $row):

                                    $prod = $allProducts[$row['productuid']];
                                    $sku  = $prod['productsku'];
                                    $name = $prod['productname'];

                                    $badge = ($row['movement'] == "IN") ? "success" : "danger";
                                    $qtySign = ($row['movement'] == "IN") ? "+" : "-";
                                    $qtyColor = ($row['movement'] == "IN") ? "text-success" : "text-danger";

                                    // combine date + time if available
                                    $dt = $row['movementdate'];
                                    if (!empty($row['movementtime'])) {
                                        $dt .= " " . $row['movementtime'];
                                    }
                                ?>

                                    <tr>
                                        <td><?= $count ?></td>

                                        <td><?= $row['movementdate'] ?></td>

                                        <td>
                                            <span class="badge badge-pill badge-<?= $badge ?>">
                                                <?= $row['movement'] ?>
                                            </span>
                                        </td>

                                        <td>
                                            <strong><?= $sku ?></strong><br>
                                            <?= $name ?>
                                        </td>

                                        <td><?= $row['previousstock'] ?></td>

                                        <td class="<?= $qtyColor ?> font-weight-bold">
                                            <?= $qtySign ?><?= $row['qty'] ?>
                                        </td>

                                        <td class="text-primary font-weight-bold"><?= $row['newstock'] ?></td>

                                        <td><?= $row['party'] ?: "-" ?></td>
                                        <td><?= $row['remarks'] ?: "-" ?></td>
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

    <?php include('../../includes/footer.php') ?>
</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>