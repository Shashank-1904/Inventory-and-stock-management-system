<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- User Info Dropdown -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <!-- User Name -->
                <span class="mr-2 d-none d-lg-inline font-weight-bold text-gray-700 small">
                    <?= $_SESSION['admin_name']; ?>
                </span>

                <!-- Avatar Circle -->
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 34px; height: 34px; font-size: 14px; font-weight: 600;">
                    <?= strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
                </div>

            </a>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right shadow-sm animated--fade-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item text-danger font-weight-bold" href="./../home/logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Logout
                </a>

            </div>
        </li>

    </ul>


</nav>
<!-- End of Topbar -->