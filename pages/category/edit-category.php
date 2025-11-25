<?php
$page = "Edit Category";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');

// Get category ID
if (!isset($_GET['id'])) {
    header("Location: view-category.php");
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
                <?php unset($_SESSION['message']); // Clear the session message 
                ?>
            <?php endif; ?>

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit Category</h1>
                <a href="./view-category" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Categories
                </a>
            </div>

            <div class="row">

                <!-- Category Form -->
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow mb-4">

                        <!-- Card Header -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Category</h6>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <form action="./../redirect" method="POST">

                                <!-- Hidden ID -->
                                <input type="hidden" name="cat_id" value="<?= $enc_id; ?>">

                                <!-- Name & Code -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">Category Name</label>
                                        <input type="text" class="form-control" name="cat_name"
                                            value="<?= $invObj->get_Single_Colum_Categories($enc_id, 'categoryname') ?>" required maxlength="100">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-gray-700 required-label">Short Code</label>
                                        <input type="text" class="form-control" name="cat_code"
                                            value="<?= $invObj->get_Single_Colum_Categories($enc_id, 'categorycode') ?>"
                                            maxlength="20" pattern="[A-Za-z0-9-]+" required>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700 required-label">Status</label>
                                    <select class="form-control" name="cat_status" required>
                                        <option value="1" <?= $invObj->get_Single_Colum_Categories($enc_id, 'categorystatus') == 1 ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= $invObj->get_Single_Colum_Categories($enc_id, 'categorystatus') == 0 ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label class="font-weight-bold text-gray-700">Description</label>
                                    <textarea class="form-control" name="cat_description" rows="3"
                                        maxlength="300"><?= $invObj->get_Single_Colum_Categories($enc_id, 'categorydescription') ?></textarea>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group mt-4">
                                    <button type="submit" name="update_cat_btn" class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                        <span class="text">Update Category</span>
                                    </button>

                                    <a href="./view-category" class="btn btn-light btn-icon-split ml-2">
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
                            <p class="small text-gray-600">✔ Short codes must be unique.</p>
                            <p class="small text-gray-600">✔ Use Inactive status carefully.</p>
                            <p class="small text-gray-600">✔ Description helps maintain clarity.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include('../../includes/footer.php'); ?>
</div>