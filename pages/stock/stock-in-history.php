<?php
$page = "Stock IN History";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');

// Filters
$product  = "";
$supplier = isset($_GET['supplier']) ? $_GET['supplier'] : "";
$from     = isset($_GET['from_date']) ? $_GET['from_date'] : "";
$to       = isset($_GET['to_date']) ? $_GET['to_date'] : "";

if (isset($_GET['product']) && $_GET['product'] !== "") {
    $product = $invObj->decryptData($_GET['product']);
}

// Fetch Stock IN based on filters
$stock_data = $invObj->get_All_StockIn_Filter($product, $supplier, $from, $to);
?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <?php include('./../../includes/navbar.php') ?>

        <div class="container-fluid">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?= $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?= $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>


            <!-- PAGE HEADING -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock IN History</h1>
                <div>
                    <a href="./stock-in" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Add Stock IN
                    </a>

                    <!-- EXPORT BUTTONS -->
                    <form action="./../redirect" method="POST" style="display:inline-block;">
                        <input type="hidden" name="stock_in_data"
                            value='<?= htmlspecialchars(json_encode($stock_data)) ?>'>

                        <button type="submit" name="export_csv_stock_in"
                            class="btn btn-sm btn-success shadow-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </form>
                </div>
            </div>

            <!-- FILTER SECTION -->
            <form method="GET" class="mb-4">
                <div class="form-row">

                    <!-- Product Filter -->
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Product</label>
                        <select name="product" class="form-control">
                            <option value="">All Products</option>

                            <?php
                            $products = $invObj->get_All_Products();
                            foreach ($products as $prd) {

                                $encID = $invObj->encryptData($prd['productuid']);

                                $selected = ($product === $prd['productuid']) ? "selected" : "";

                                echo "<option value='$encID' $selected>{$prd['productsku']} - {$prd['productname']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Supplier Filter (Dropdown) -->
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Supplier</label>
                        <select name="supplier" class="form-control">
                            <option value="">All Suppliers</option>

                            <?php
                            // Fetch unique supplier list from stock-in table
                            $supplierList = $invObj->get_All_Suppliers_StockIn();

                            foreach ($supplierList as $sup) {
                                $sel = ($supplier === $sup['supplier']) ? "selected" : "";
                                echo "<option value='{$sup['supplier']}' $sel>{$sup['supplier']}</option>";
                            }
                            ?>
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

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <a href="./stock-in-history" class="btn btn-danger btn-block" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                </div>
            </form>
            <!-- END FILTER -->



            <!-- STOCK IN TABLE -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Stock IN Records</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Previous Qty</th>
                                    <th>Added Qty</th>
                                    <th>New Qty</th>
                                    <th>Supplier</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $count = 1;
                                foreach ($stock_data as $s):

                                    $encPID = $invObj->encryptData($s['productuid']);
                                    $prodName = $invObj->get_Single_Colum_Product($encPID, 'productname');
                                    $prodSku  = $invObj->get_Single_Colum_Product($encPID, 'productsku');
                                ?>

                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><?= date("d M Y", strtotime($s['purchasedate'])) ?></td>

                                        <td>
                                            <strong><?= $prodSku ?></strong><br>
                                            <?= $prodName ?>
                                        </td>

                                        <td><?= $s['previousstock'] ?></td>
                                        <td class="text-success font-weight-bold">+<?= $s['addedstock'] ?></td>
                                        <td class="text-primary font-weight-bold"><?= $s['newstock'] ?></td>
                                        <td><?= $s['supplier'] ?: "-" ?></td>
                                        <td><?= $s['remarks'] ?: "-" ?></td>
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

    <?php include('../../includes/footer.php'); ?>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>