<?php
$page = "Add Product";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');
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

            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add New Product</h1>
                <a href="./view-product" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
                </a>
            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                        </div>
                        <div class="card-body">
                            <form action="./../redirect" method="POST" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">Product Name</label>
                                        <input type="text" class="form-control" name="product_name" required maxlength="120">
                                        <small class="form-text text-muted">Full Product Name</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">SKU (Manual)</label>
                                        <input type="text" class="form-control" name="product_sku" required maxlength="50">
                                        <small class="form-text text-muted">Enter Unique SKU (e.g., PROD-001)</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-bold required-label">Select Category</label>
                                        <select name="product_category" class="form-control" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            $cat_data = $invObj->get_All_Categories("1");
                                            if (!empty($cat_data)) {
                                                foreach ($cat_data as $cat) {
                                                    echo "<option value='{$cat['categoryuid']}'>{$cat['categoryname']} - {$cat['categorycode']}</option>";
                                                }
                                            } else {
                                                echo "<option disabled>No Categories Available</option>";
                                            }
                                            ?>
                                        </select>
                                        <small class="form-text text-muted">Choose category for this product</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">HSN Code</label>
                                        <input type="text" class="form-control" name="product_hsn" required maxlength="50">
                                        <small class="form-text text-muted">Example: 3402, 3004, 8536</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">Price</label>
                                        <input type="number" class="form-control" name="product_price" required min="1">
                                        <small class="form-text text-muted">Selling Price (in ₹)</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">Stock Quantity</label>
                                        <input type="number" class="form-control" name="product_stock" required min="0">
                                        <small class="form-text text-muted">Initial Stock Quantity</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">Minimum Stock Quantity</label>
                                        <input type="number" class="form-control" name="product_minstock" required min="0">
                                        <small class="form-text text-muted">Alert will be shown when stock goes below this value</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Product Image</label>
                                    <input type="file" name="product_image" class="form-control-file">
                                    <small class="form-text text-muted">Upload Product Image (JPG/PNG/WEBP — Max 2MB)</small>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Description</label>
                                    <textarea class="form-control" name="product_description" maxlength="300" rows="3"></textarea>
                                    <small class="form-text text-muted">Short description (optional)</small>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold required-label">Status</label>
                                    <select class="form-control" name="product_status" required>
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="form-text text-muted">Active = visible in inventory</small>
                                </div>

                                <button type="submit" name="add_product_btn" class="btn btn-primary btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                                    <span class="text">Add Product</span>
                                </button>

                                <button type="reset" class="btn btn-secondary btn-icon-split ml-2">
                                    <span class="icon text-white-50"><i class="fas fa-undo"></i></span>
                                    <span class="text">Reset</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle mr-2"></i>Guidelines
                            </h6>
                        </div>

                        <div class="card-body">
                            <p class="small text-gray-600">✔ SKU must be unique.</p>
                            <p class="small text-gray-600">✔ Product image increases visibility.</p>
                            <p class="small text-gray-600">✔ Status controls product visibility.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include('../../includes/footer.php'); ?>