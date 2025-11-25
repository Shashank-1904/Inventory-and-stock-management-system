<?php include('./login-header.php') ?>

<body class="bg-gradient-primary">

    <div class="container">


        <div class="text-center mt-4">
            <!-- PROJECT HEADING -->
            <img src="./../assets/img/logo.png"
                alt="App Logo"
                style="width: 120px; height:auto; margin-bottom:10px;">
            <h1 class="text-white font-weight-bold" style="font-size: 35px;">
                Inventory & Stock Management System
            </h1>
        </div>

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="toast <?php echo $_SESSION['message']['type']; ?>" id="toast">
                    <span class="toast-text"><?php echo $_SESSION['message']['text']; ?></span>
                    <button class="toast-close" id="closeToast">×</button>
                </div>
                <?php unset($_SESSION['message']); // Clear the session message 
                ?>
            <?php endif; ?>

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-2">
                    <div class="card-body p-0">

                        <div class="row">

                            <!-- LEFT SIDE IMAGE -->
                            <div class="col-lg-6 p-0">
                                <img src="./../assets/img/loginimage.png"
                                    class="img-fluid w-100"
                                    style="height:100%; object-fit:cover;"
                                    alt="Login Image">
                            </div>

                            <!-- RIGHT SIDE FORM -->
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="p-5 w-100">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                    <form class="user" action="./checklogin" method="POST">
                                        <div class="form-group">
                                            <input type="email"
                                                class="form-control form-control-user"
                                                id="exampleInputEmail"
                                                name="u_email"
                                                placeholder="Enter Email Address..." required>
                                        </div>

                                        <div class="form-group">
                                            <input type="password"
                                                class="form-control form-control-user"
                                                id="exampleInputPassword"
                                                name="u_pass"
                                                placeholder="Password" required>
                                        </div>

                                        <button name="login_btn" type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>

                                    </form>

                                </div>
                            </div>

                        </div> <!-- row end -->

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- JS -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <script src="../assets/js/custom.js"></script>

</body>

</html>