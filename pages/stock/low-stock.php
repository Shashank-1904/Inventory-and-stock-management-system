<?php
$page = "Low Stock";
include('../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('../../includes/sidebar.php');

$low_stock_data = $invObj->get_Low_Stock_Products();
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

            <!-- HEADING -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Low Stock Items</h1>
                <form action="./../redirect" method="POST" style="display:inline-block;">
                    <input type="hidden" name="low_stock_data"
                        value='<?= htmlspecialchars(json_encode($low_stock_data)) ?>'>

                    <button type="submit" name="export_csv_low_stock"
                        class="btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </form>
            </div>

            <!-- TABLE -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Products Below Minimum Stock</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered" id="dataTable" width="100%">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Minimum Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $count = 1;
                                foreach ($low_stock_data as $p):

                                    // product image
                                    $img = $p['productimage']
                                        ? "../../assets/uploads/products/" . $p['productimage']
                                        : "../../assets/img/noimg.png";

                                    // category
                                    $categoryName = $invObj->get_Single_Colum_Categories(
                                        $invObj->encryptData($p['categoryuid']),
                                        'categoryname'
                                    );
                                ?>
                                    <tr>
                                        <td><?= $count ?></td>

                                        <td>
                                            <img src="<?= $img ?>" width="50" height="50" style="object-fit:cover;">
                                        </td>

                                        <td>
                                            <?= $p['productname'] ?><br>
                                            <small class="text-muted"><?= $categoryName ?></small>
                                        </td>

                                        <td><?= $p['productsku'] ?></td>

                                        <td><?= $categoryName ?></td>

                                        <td>
                                            <span class="badge badge-danger"><?= $p['productstock'] ?></span>
                                        </td>

                                        <td>
                                            <span class="badge badge-warning"><?= $p['productminstock'] ?></span>
                                        </td>

                                        <td>
                                            <span class="badge badge-danger">Low Stock</span>
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
</div>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>