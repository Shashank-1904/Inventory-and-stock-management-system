<?php
$relativePath = "/MyProjects/Inventory-and-stock-management-system/";
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $relativePath ?>home/">

        <!-- Logo Image -->
        <div class="sidebar-brand-icon">
            <img src="<?= $relativePath ?>assets/img/logo.png"
                alt="Logo"
                style="
                width: 70px;
                height: auto;
                object-fit: contain;
                border-radius: 6px;
            ">
        </div>

        <!-- Brand Name -->
        <div class="sidebar-brand-text mx-2"
            style="font-size: 0.6rem; font-weight: 800;">
            Inventory & Stocks Management System
        </div>

    </a>




    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= $relativePath ?>home/">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Product Management Section -->
    <div class="sidebar-heading">Inventory Setup</div>

    <!-- Categories -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategories">
            <i class="fas fa-th-list"></i>
            <span>Categories</span>
        </a>
        <div id="collapseCategories" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= $relativePath ?>pages/category/add-category">
                    <i class="fas fa-plus-circle text-primary"></i> Add Category
                </a>
                <a class="collapse-item" href="<?= $relativePath ?>pages/category/view-category">
                    <i class="fas fa-list text-primary"></i> View Categories
                </a>
            </div>
        </div>
    </li>

    <!-- Products -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <div id="collapseProducts" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= $relativePath ?>pages/product/add-product">
                    <i class="fas fa-plus-circle text-success"></i> Add Product
                </a>
                <a class="collapse-item" href="<?= $relativePath ?>pages/product/view-product">
                    <i class="fas fa-box-open text-success"></i> View Products
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Stock Management Section -->
    <div class="sidebar-heading">Stock Operations</div>

    <!-- Stock IN -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStockIn">
            <i class="fas fa-arrow-circle-down"></i>
            <span>Stock IN</span>
        </a>
        <div id="collapseStockIn" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= $relativePath ?>pages/stock/stock-in">
                    <i class="fas fa-plus text-success"></i> Add Stock IN
                </a>
                <a class="collapse-item" href="<?= $relativePath ?>pages/stock/stock-in-history">
                    <i class="fas fa-history text-success"></i> Stock IN History
                </a>
            </div>
        </div>
    </li>

    <!-- Stock OUT -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStockOut">
            <i class="fas fa-arrow-circle-up"></i>
            <span>Stock OUT</span>
        </a>
        <div id="collapseStockOut" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= $relativePath ?>pages/stock/stock-out">
                    <i class="fas fa-minus text-danger"></i> Add Stock OUT
                </a>
                <a class="collapse-item" href="<?= $relativePath ?>pages/stock/stock-out-history">
                    <i class="fas fa-history text-danger"></i> Stock OUT History
                </a>
            </div>
        </div>
    </li>

    <!-- Ledger -->
    <li class="nav-item">
        <a class="nav-link" href="<?= $relativePath ?>pages/stock/stock-ledger">
            <i class="fas fa-book"></i>
            <span>Stock Ledger</span>
        </a>
    </li>

    <!-- Low Stock -->
    <li class="nav-item">
        <a class="nav-link" href="<?= $relativePath ?>pages/stock/low-stock">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Low Stock</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="<?= $relativePath ?>home/logout">
            <i class="fas fa-power-off"></i>
            <span>Logout</span>
        </a>
    </li>

</ul>
<!-- End of Sidebar -->