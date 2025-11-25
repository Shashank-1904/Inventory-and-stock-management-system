<?php
$page = "Edit Product";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');

if (!isset($_GET['id'])) {
    header("Location: view-product.php");
    exit;
}

$enc_id = $_GET['id'];
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include('./../../includes/navbar.php') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?php echo $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?php echo $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
                <a href="./view-product" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Products
                </a>
            </div>

            <div class="row">

                <!-- Product Form -->
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">

                        <!-- Card Header -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Product</h6>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <form action="./../redirect" method="POST" enctype="multipart/form-data">

                                <!-- Hidden Product ID -->
                                <input type="hidden" name="product_id" value="<?= $enc_id; ?>">

                                <!-- Product Name & SKU -->
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">Product Name</label>
                                        <input type="text" class="form-control" name="product_name" required maxlength="120"
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'productname'); ?>">
                                        <small class="form-text text-muted">Enter the product name</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">SKU (Manual)</label>
                                        <input type="text" class="form-control" name="product_sku" required maxlength="50"
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'productsku'); ?>">
                                        <small class="form-text text-muted">Unique product SKU code</small>
                                    </div>

                                </div>

                                <!-- Category -->
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="font-weight-bold text-gray-700 required-label">Category</label>
                                        <select class="form-control" name="product_category" required>
                                            <option value="">Select</option>

                                            <?php
                                            $cat_data = $invObj->get_All_Categories();
                                            $selected_cat = $invObj->get_Single_Colum_Product($enc_id, 'categoryuid');

                                            foreach ($cat_data as $cat) {
                                                $sel = ($cat['categoryuid'] == $selected_cat) ? "selected" : "";
                                                echo "<option value='{$cat['categoryuid']}' $sel>{$cat['categoryname']}</option>";
                                            }
                                            ?>
                                        </select>
                                        <small class="form-text text-muted">Choose the product’s category</small>
                                    </div>
                                </div>

                                <!-- HSN & Price -->
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">HSN Code</label>
                                        <input type="text" class="form-control" name="product_hsn" required maxlength="50"
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'producthsn'); ?>">
                                        <small class="form-text text-muted">Example: 3402, 3004, 8536</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">Price</label>
                                        <input type="number" class="form-control" name="product_price" min="1" required
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'productprice'); ?>">
                                        <small class="form-text text-muted">Set the selling price</small>
                                    </div>

                                </div>

                                <!-- Stock & Min Stock -->
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">Stock Quantity</label>
                                        <input type="number" class="form-control" name="product_stock" min="0" required
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'productstock'); ?>">
                                        <small class="form-text text-muted">Current available stock</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold required-label">Minimum Stock Quantity</label>
                                        <input type="number" class="form-control" name="product_minstock" required min="0"
                                            value="<?= $invObj->get_Single_Colum_Product($enc_id, 'productminstock'); ?>">
                                        <small class="form-text text-muted">Alert triggers when stock falls below this</small>
                                    </div>

                                </div>

                                <!-- Image -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Product Image</label><br>

                                    <?php $img = $invObj->get_Single_Colum_Product($enc_id, 'productimage'); ?>

                                    <?php if ($img != ""): ?>
                                        <img src="./../../assets/uploads/products/<?= $img ?>" width="80" height="80"
                                            class="rounded mb-2" style="object-fit:cover;">
                                        <br>
                                    <?php endif; ?>

                                    <input type="file" name="product_image" class="form-control-file">
                                    <small class="form-text text-muted">Upload only if you want to change the image</small>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Description</label>
                                    <textarea class="form-control" name="product_description" maxlength="300" rows="3">
<?= $invObj->get_Single_Colum_Product($enc_id, 'productdescription'); ?>
                                    </textarea>
                                    <small class="form-text text-muted">Short description of the product</small>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700 required-label">Status</label>
                                    <select class="form-control" name="product_status" required>
                                        <option value="1" <?= $invObj->get_Single_Colum_Product($enc_id, 'productstatus') == 1 ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= $invObj->get_Single_Colum_Product($enc_id, 'productstatus') == 0 ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                    <small class="form-text text-muted">Inactive products will not appear in stock operations</small>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group mt-4">
                                    <button type="submit" name="update_product_btn" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                        <span class="text">Update Product</span>
                                    </button>

                                    <a href="./view-product" class="btn btn-light btn-icon-split ml-2">
                                        <span class="icon text-gray-600"><i class="fas fa-times"></i></span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

                <!-- Help Card -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle mr-2"></i>Editing Guidelines
                            </h6>
                        </div>

                        <div class="card-body">
                            <p class="small text-gray-600">✔ Update only required fields.</p>
                            <p class="small text-gray-600">✔ SKU must be unique.</p>
                            <p class="small text-gray-600">✔ Upload new product image only if necessary.</p>
                            <p class="small text-gray-600">✔ Status controls visibility.</p>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include('../../includes/footer.php'); ?>
</div>