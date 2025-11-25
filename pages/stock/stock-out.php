<?php
$page = "Stock OUT";
include('../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('../../includes/sidebar.php');
?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <?php include('../../includes/navbar.php') ?>

        <div class="container-fluid">

            <!-- Toast -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?= $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?= $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']);
                ?>
            <?php endif; ?>

            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Stock OUT - Usage / Sales Entry</h1>
                <a href="./stock-out-history" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-history fa-sm text-white-50"></i> View Stock OUT History
                </a>
            </div>

            <div class="row">

                <!-- Stock OUT Form -->
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Stock OUT Entry</h6>
                        </div>

                        <div class="card-body">

                            <form action="./../redirect" method="POST">

                                <!-- Product -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Select Product *</label>
                                    <select class="form-control" name="product_id" required>
                                        <option value="">Select product...</option>

                                        <?php
                                        $prod_data = $invObj->get_All_Products();
                                        foreach ($prod_data as $p) {
                                            $enc = $invObj->encryptData($p['productuid']);
                                            $sku = htmlspecialchars($p['productsku']);
                                            $name = htmlspecialchars($p['productname']);
                                            $stock = (int)$p['productstock'];

                                            echo "<option value='{$enc}'>";
                                            echo "{$sku} - {$name} (Stock: {$stock})";
                                            echo "</option>";
                                        }
                                        ?>
                                    </select>
                                    <small class="form-text text-muted">Choose the product you want to remove stock from</small>
                                </div>

                                <!-- Quantity & Date -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700">Quantity *</label>
                                        <input type="number" class="form-control" name="quantity"
                                            placeholder="Enter quantity" min="1" required>
                                        <small class="form-text text-muted">Enter how many units you want to deduct</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700">Date *</label>
                                        <input type="date" class="form-control" name="used_date"
                                            value="<?= date('Y-m-d'); ?>" required>
                                        <small class="form-text text-muted">Date when stock was used or sold</small>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Customer (optional)</label>
                                    <input type="text" class="form-control" name="customer"
                                        placeholder="Customer name">
                                    <small class="form-text text-muted">Enter customer name or usage reference</small>
                                </div>

                                <!-- Remarks -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Remarks</label>
                                    <textarea class="form-control" name="remarks"
                                        rows="3" placeholder="Any notes..."></textarea>
                                    <small class="form-text text-muted">Additional notes related to stock out</small>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group mt-4">
                                    <button type="submit" name="stock_out_btn" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                        <span class="text">Remove Stock</span>
                                    </button>

                                    <button type="reset" class="btn btn-secondary btn-icon-split ml-2">
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

                <!-- Guidelines -->
                <div class="col-xl-4 col-lg-6">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle mr-2"></i>Stock OUT Guidelines
                            </h6>
                        </div>

                        <div class="card-body">

                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">When to Use</h6>
                                <p class="small text-gray-600">
                                    Use this form when stock is removed due to sales, customer delivery,
                                    internal usage, damage, or wastage.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Required Fields</h6>
                                <p class="small text-gray-600">
                                    Product, quantity, and date are mandatory to record accurate stock reduction.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Stock Validation</h6>
                                <p class="small text-gray-600">
                                    System prevents negative stock. You cannot remove more than available.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Customer / Usage Info</h6>
                                <p class="small text-gray-600">
                                    Enter customer name or internal usage notes for better tracking.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold text-gray-700">Auto Updates</h6>
                                <p class="small text-gray-600">
                                    Stock reduces automatically and a movement entry is added to the Ledger.
                                </p>
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