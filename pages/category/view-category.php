<?php
$page = "List Category";
include('./../../includes/header.php');
include("./../../includes/check_login_handle.php");
include('./../../includes/sidebar.php');
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include('../../includes/navbar.php') ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?php echo $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?php echo $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <!-- Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Categories</h1>
                <a href="./add-category" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add New Category
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Category Name</th>
                                    <th>Short Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $cat_data = $invObj->get_All_Categories();
                                $count = 1;

                                foreach ($cat_data as $cat_row):

                                    // Prepare Status Badge
                                    $status = $cat_row['categorystatus'] == 1
                                        ? '<span class="badge badge-success">Active</span>'
                                        : '<span class="badge badge-danger">Inactive</span>';
                                ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><?= $cat_row['categoryname'] ?></td>
                                        <td><?= $cat_row['categorycode'] ?></td>
                                        <td><?= $cat_row['categorydescription'] ?></td>

                                        <!-- STATUS COLUMN FIXED -->
                                        <td><?= $status ?></td>

                                        <!-- ACTION BUTTONS FIXED -->
                                        <td class="text-center action-buttons d-flex justify-content-center">
                                            <a href="./edit-category?id=<?= $invObj->encryptData($cat_row['categoryuid']) ?>"
                                                class="btn btn-primary btn-sm mx-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="./../redirect?type=Category&cat_id=<?= $invObj->encryptData($cat_row['categoryuid']); ?>"
                                                class="btn btn-danger btn-sm mx-1"
                                                onclick="return confirm('Delete this category?')"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                        </td>

                                    </tr>
                                <?php
                                    $count++;
                                endforeach;
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <?php include('../../includes/footer.php'); ?>

</div>

<!-- DataTable Init -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "pagingType": "simple_numbers",
            "language": {
                "paginate": {
                    "previous": "Previous",
                    "next": "Next"
                }
            }
        });
    });
</script>