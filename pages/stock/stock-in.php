<?php
$page = "Stock IN";
include('../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('../../includes/sidebar.php');
?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <?php include('../../includes/navbar.php') ?>

        <div class="container-fluid">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?= $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?= $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock IN - Purchase Entry</h1>
                <a href="./stock-ledger" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-history fa-sm text-white-50"></i> View Stock Ledger
                </a>
            </div>

            <div class="row">

                <!-- Stock IN Form -->
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Purchase Entry Form</h6>
                        </div>

                        <div class="card-body">
                            <form id="stockInForm" action="./../redirect" method="POST">
                                <div class="form-group">
                                    <label for="product_id" class="font-weight-bold text-gray-700">Select Product *</label>
                                    <select class="form-control select2" id="product_id" name="product_id" required>
                                        <option value="">Select a product...</option>
                                        <?php
                                        $prod_data = $invObj->get_All_Products();
                                        foreach ($prod_data as $p) {
                                            // encrypt product id for value (keep consistent)
                                            $enc = $invObj->encryptData($p['productuid']);

                                            // Escape values for safety in attribute context
                                            $sku = htmlspecialchars($p['productsku']);
                                            $name = htmlspecialchars($p['productname']);
                                            $stock = (int)$p['productstock'];

                                            // Option contains data-stock attribute (raw number) for JS
                                            echo "<option value='{$enc}'>";
                                            echo "{$sku} - {$name} (Current Stock: {$stock})";
                                            echo "</option>";
                                        }
                                        ?>
                                    </select>
                                    <small class="form-text text-muted">Select the product to add stock</small>
                                </div>

                                <!-- Quantity & Purchase Date -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="quantity" class="font-weight-bold text-gray-700">Quantity *</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            placeholder="Enter quantity" min="1" required>
                                        <small class="form-text text-muted">Number of units to add</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="purchase_date" class="font-weight-bold text-gray-700">Purchase Date *</label>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                            value="<?php echo date('Y-m-d'); ?>" required>
                                        <small class="form-text text-muted">Date when stock was purchased</small>
                                    </div>
                                </div>

                                <!-- Purchase Price & Supplier -->
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="supplier" class="font-weight-bold text-gray-700">Supplier</label>
                                        <input type="text" class="form-control" id="supplier" name="supplier"
                                            placeholder="Enter supplier name (optional)">
                                        <small class="form-text text-muted">Supplier/vendor name (optional)</small>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="form-group">
                                    <label for="remarks" class="font-weight-bold text-gray-700">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks"
                                        rows="3" placeholder="Any additional notes (optional)"></textarea>
                                    <small class="form-text text-muted">Additional information about this purchase</small>
                                </div>

                                <!-- Form Actions -->
                                <div class="form-group mt-4">
                                    <button type="submit" name="stock_in_btn" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                        <span class="text">Add Stock IN</span>
                                    </button>
                                    <button type="reset" class="btn btn-secondary btn-icon-split ml-2" id="stockReset">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-undo"></i>
                                        </span>
                                        <span class="text">Reset</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Quick Help & Recent -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle mr-2"></i>Stock IN Guidelines
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">When to Use</h6>
                                <p class="small text-gray-600">Use this form when new stock arrives from suppliers, manufacturers, or returns.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Required Fields</h6>
                                <p class="small text-gray-600">Product selection, quantity, and date are mandatory for tracking.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Auto Updates</h6>
                                <p class="small text-gray-600">Stock levels are automatically updated and recorded in the ledger.</p>
                            </div>
                            <hr>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-asterisk text-danger mr-1"></i>
                                    Indicates required fields
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>

</div>