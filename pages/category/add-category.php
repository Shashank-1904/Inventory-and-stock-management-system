<?php
$page = "Add Category";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');
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
                <h1 class="h3 mb-0 text-gray-800">Add New Category</h1>
                <a href="./view-category" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Categories
                </a>
            </div>

            <div class="row">

                <!-- Category Form -->
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">

                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <form action="./../redirect" method="POST">

                                <!-- Name & Code -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="category_name" class="font-weight-bold text-gray-700 required-label">
                                            Category Name
                                        </label>
                                        <input type="text" class="form-control" id="category_name" name="cat_name"
                                            placeholder="Enter category name" required maxlength="100">
                                        <small class="text-muted">Example: Cleaning, Grocery, Stationery</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="category_code" class="font-weight-bold text-gray-700 required-label">
                                            Short Code
                                        </label>
                                        <input type="text" class="form-control" id="category_code" name="cat_code"
                                            placeholder="ELEC, GROC, STAT" maxlength="20" pattern="[A-Za-z0-9-]+" required>
                                        <small class="text-muted">Use short unique code (no spaces)</small>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold text-gray-700 required-label">Status</label>
                                    <select class="form-control" id="status" name="cat_status" required>
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="text-muted">Active means visible in product module</small>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold text-gray-700">Description</label>
                                    <textarea class="form-control" id="description" name="cat_description" rows="3" maxlength="300"
                                        placeholder="Enter description (optional)"></textarea>
                                    <small class="text-muted">Optional: Add notes about category use</small>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group mt-4">
                                    <button type="submit" name="add_cat_btn" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                                        <span class="text">Add Category</span>
                                    </button>

                                    <button type="reset" class="btn btn-secondary btn-icon-split ml-2">
                                        <span class="icon text-white-50"><i class="fas fa-undo"></i></span>
                                        <span class="text">Reset</span>
                                    </button>
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
                                <i class="fas fa-info-circle mr-2"></i>Category Guidelines
                            </h6>
                        </div>

                        <div class="card-body">
                            <p class="small text-gray-600">✔ Use short and meaningful category names.</p>
                            <p class="small text-gray-600">✔ Codes should be unique (e.g., ELEC, GROC).</p>
                            <p class="small text-gray-600">✔ Description is optional but helpful.</p>
                            <p class="small text-gray-600">✔ Set status to Active for immediate use.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include('../../includes/footer.php') ?>