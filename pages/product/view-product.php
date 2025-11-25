<?php
$page = "List Products";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');

// Filter values
$category = "";
$status   = isset($_GET['status']) ? $_GET['status'] : "";

if (isset($_GET['category']) && $_GET['category'] !== "") {
    $category = $invObj->decryptData($_GET['category']);
}

// Get filtered products
$prod_data = $invObj->get_All_Filter_Products($category, $status);
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
                <h1 class="h3 mb-0 text-gray-800">Products</h1>

                <div>
                    <a href="./add-product" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Product
                    </a>

                    <!-- EXPORT BUTTONS -->
                    <form action="./../redirect" method="POST" style="display:inline-block;">
                        <input type="hidden" name="prod_data"
                            value='<?= htmlspecialchars(json_encode($prod_data)) ?>'>

                        <button type="submit" name="export_csv_product"
                            class="btn btn-sm btn-success shadow-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </form>
                </div>
            </div>

            <!-- FILTER SECTION -->
            <form method="GET" class="mb-4">
                <div class="form-row">

                    <!-- Category Filter -->
                    <div class="form-group col-md-5">
                        <label class="font-weight-bold">Category</label>
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>

                            <?php
                            $cat_data = $invObj->get_All_Categories();

                            foreach ($cat_data as $cat) {

                                $enc_cat = $invObj->encryptData($cat['categoryuid']);

                                // Compare RAW uid
                                $selected = ($category == $cat['categoryuid']) ? "selected" : "";

                                echo "<option value='$enc_cat' $selected>{$cat['categoryname']} - {$cat['categorycode']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="form-group col-md-5">
                        <label class="font-weight-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="1" <?= ($status === "1") ? "selected" : "" ?>>Active</option>
                            <option value="0" <?= ($status === "0") ? "selected" : "" ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <a href="./view-product" class="btn btn-danger btn-block" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                </div>
            </form>
            <!-- END FILTER SECTION -->

            <!-- Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $count = 1;
                                foreach ($prod_data as $p):

                                    $status_badge = $p['productstatus'] == 1
                                        ? '<span class="badge badge-success">Active</span>'
                                        : '<span class="badge badge-danger">Inactive</span>';

                                    $img = $p['productimage']
                                        ? "../../assets/uploads/products/" . $p['productimage']
                                        : "../../assets/img/noimg.png";
                                ?>
                                    <tr>
                                        <td><?= $count ?></td>

                                        <td><img src="<?= $img ?>" width="50" height="50" style="object-fit:cover;"></td>

                                        <td><?= $p['productname'] ?></td>
                                        <td><?= $p['productsku'] ?></td>

                                        <td><?= $invObj->get_Single_Colum_Categories($invObj->encryptData($p['categoryuid']), 'categoryname') ?></td>

                                        <td><?= $p['productprice'] ?></td>
                                        <td><?= $p['productstock'] ?></td>

                                        <td><?= $status_badge ?></td>

                                        <td class="text-center d-flex justify-content-center">

                                            <a href="./edit-product?id=<?= $invObj->encryptData($p['productuid']) ?>"
                                                class="btn btn-primary btn-sm mx-1"><i class="fas fa-edit"></i></a>

                                            <a href="./../redirect?type=Product&prod_id=<?= $invObj->encryptData($p['productuid']); ?>"
                                                class="btn btn-danger btn-sm mx-1"
                                                onclick="return confirm('Delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                        </td>
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