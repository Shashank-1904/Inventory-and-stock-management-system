<?php
session_start();
require('invconfig.php');
// include_once("./../../assets/email/lib/PHPMailerAutoload");
class invFront extends config
{
    // ============================================================
    //                     BASIC DEPENDENCIES STARTS
    // ============================================================

    private $isqueryrun, $query;

    public function setter()
    {
        $this->isqueryrun = mysqli_query($this->connect, $this->query);
    }

    function getCurrentTime()
    {
        date_default_timezone_set('Asia/Kolkata');
        $day = date('Y-m-d H:i:s');
        return $day;
    }

    function login_handle()
    {
        $u_email = $_POST['u_email'];
        $u_pass = $_POST['u_pass'];
        $u_pass = md5($u_pass);

        $this->query = "SELECT * FROM inv_users WHERE useremail='{$u_email}'";
        $this->setter();

        $user_count = mysqli_num_rows($this->isqueryrun);

        if ($user_count > 0) {
            $row = mysqli_fetch_assoc($this->isqueryrun);
            if ($row['userpass'] == $u_pass) {
                $token = "ILOVEINVENTORY&STOCKAPP" . $row['userfname'] . " " . $row['userlname'] . time();
                $login_token = md5($token);

                $this->query = "INSERT INTO inv_users_login_history(useremail,logintoken) VALUES ('$u_email', '$login_token');";
                $this->setter();
                if ($this->isqueryrun) {
                    $_SESSION['admin_login_token'] = $login_token;
                    $_SESSION['admin_email'] = $u_email;
                    $_SESSION['admin_name'] = $row['userfname'] . " " . $row['userlname'];
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'User LoggedIn Successfully'];
                    header("Location: ./../home/");
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Login Failed'];
                    header("Location: ./../home/login");
                    exit;
                }
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Password is Incorrect'];
                header("Location: ./../home/login");
                exit;
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'User not found'];
            header("Location: ./../home/login");
            exit;
        }
    }

    function isloggedinuser()
    {
        $login_token = $_SESSION['admin_login_token'];
        $u_email = $_SESSION['admin_email'];

        $this->query = "SELECT * FROM inv_users_login_history WHERE useremail='{$u_email}' order by loginid desc limit 1";
        $this->setter();

        if ($this->isqueryrun) {
            $info = mysqli_fetch_assoc($this->isqueryrun);
            if ($info['logintoken'] == $login_token) {
                // echo $login_token."-->".$u_email;        
                // echo "<script> window.location.replace('../home/index'); </script> ";
            } else {
                session_destroy();
                echo "<script> window.location.replace('../home/login'); </script> ";
            }
        } else {
            session_destroy();
            echo "<script> window.location.replace('../home/login'); </script> ";
        }
    }

    function encryptData($plaintext)
    {
        $secret_key = "ILOVEINVENTORY&STOCK@123";
        $key = hash('sha256', $secret_key, true);
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return rtrim(strtr(base64_encode($iv . $encrypted), '+/', '-_'), '=');
    }

    function decryptData($ciphertext)
    {
        $secret_key = "ILOVEINVENTORY&STOCK@123";
        $key = hash('sha256', $secret_key, true);
        $ciphertext = strtr($ciphertext, '-_', '+/');
        $ciphertext = base64_decode($ciphertext);
        $iv = substr($ciphertext, 0, 16);
        $encrypted = substr($ciphertext, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    // ============================================================
    //                     BASIC DEPENDENCIES ENDS
    // ============================================================


    // ============================================================
    //                      Category Modue Start
    // ============================================================

    // add Categories start
    function add_Category()
    {
        $cat_name = $_POST['cat_name'];
        $cat_code = $_POST['cat_code'];
        $cat_status = $_POST['cat_status'];
        $cat_description = $_POST['cat_description'];

        $this->query = "SELECT * FROM inv_category WHERE categoryname='$cat_name' OR categorycode='$cat_code'";
        $this->setter();

        $currentTime = $this->getCurrentTime();

        if ($this->isqueryrun) {
            if (mysqli_num_rows($this->isqueryrun) > 0) {
                $_SESSION['message'] = ['type' => 'error', 'text' => $cat_name . '(' . $cat_code . ') Already Present'];
                header("Location: ../pages/category/add-category"); // Using header for redirect
                exit;
            } else {
                $this->query = "INSERT INTO inv_category (categoryname, categorycode, categorystatus, categorydescription, categorycreated) 
                VALUES ('$cat_name', '$cat_code', $cat_status, '$cat_description', '$currentTime')";
                $this->setter();

                if ($this->isqueryrun) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => $cat_name . '(' . $cat_code . ') Added Successfully'];
                    header("Location: ../pages/category/view-category");
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Something went wrong'];
                    header("Location: ../pages/category/add-category");
                    exit;
                }
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Something went wrong'];
            header("Location: ../pages/category/add-category");
            exit;
        }
    }
    // add Categories end

    // fetch all Categories start
    function get_All_Categories($status = "")
    {
        $query = "SELECT * FROM inv_category WHERE 1=1";

        if ($status === "0") {
            $query .= " AND categorystatus = '0'";
        } else if ($status === "1") {
            // If status = 1 → show only active categories
            $query .= " AND categorystatus = '1'";
        }

        $query .= " ORDER BY categoryuid DESC";

        $this->query = $query;
        $this->setter();

        $data = array();

        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }

        return $data;
    }
    // fetch all Categories end

    // fetch single column of Categories start
    function get_Single_Colum_Categories($id, $colname)
    {
        $id = $this->decryptData($id);
        $this->query = "SELECT * FROM inv_category WHERE categoryuid='$id'";
        $this->setter();

        if (mysqli_num_rows($this->isqueryrun) > 0) {
            $row = mysqli_fetch_assoc($this->isqueryrun);
            return $row[$colname];
        }
    }
    // fetch single column of Categories end

    // update categories start
    function update_Category()
    {
        $enc_id = $_POST['cat_id'];
        $cat_uid = $this->decryptData($enc_id);

        $cat_name = $_POST['cat_name'];
        $cat_code = $_POST['cat_code'];
        $cat_status = $_POST['cat_status'];
        $cat_description = $_POST['cat_description'];

        $this->query = "SELECT * FROM inv_category WHERE categoryuid = '$cat_uid'";
        $this->setter();

        $currentTime = $this->getCurrentTime();

        if ($this->isqueryrun) {

            if (mysqli_num_rows($this->isqueryrun) == 0) {

                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => $cat_name . '(' . $cat_code . ') is Not Present'
                ];

                header("Location: ../pages/category/edit-category?id=" . urlencode($enc_id));
                exit;
            } else {

                $this->query = "SELECT * FROM inv_category WHERE categoryuid != '$cat_uid'";
                $this->setter();

                if (mysqli_num_rows($this->isqueryrun) > 0) {
                    while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                        if ($row['categorycode'] === $cat_code or $row['categoryname'] === $cat_name) {
                            $_SESSION['message'] = [
                                'type' => 'error',
                                'text' => $cat_name . '(' . $cat_code . ') is Already Present Try Different Name or Code'
                            ];

                            header("Location: ../pages/category/edit-category?id=" . urlencode($enc_id));
                            exit;
                        }
                    }
                }

                $this->query = "UPDATE inv_category SET 
                        categoryname = '$cat_name',
                        categorycode = '$cat_code',
                        categorystatus = '$cat_status',
                        categorydescription = '$cat_description',
                        categoryupdated = '$currentTime'
                        WHERE categoryuid = '$cat_uid'";
                $this->setter();

                if ($this->isqueryrun) {
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => $cat_name . '(' . $cat_code . ') Updated Successfully'
                    ];

                    header("Location: ../pages/category/view-category");
                    exit;
                } else {
                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Something went wrong'
                    ];
                    header("Location: ../pages/category/edit-category?id=" . urlencode($enc_id));
                    exit;
                }
            }
        }
    }
    //update categories end

    // delete categories start
    function delete_Category()
    {
        $enc_id = $_GET['cat_id'];
        $cat_uid = $this->decryptData($enc_id);
        $cat_name = $this->get_Single_Colum_Categories($enc_id, 'categoryname');
        $cat_code = $this->get_Single_Colum_Categories($enc_id, 'categorycode');

        $this->query = "SELECT * FROM inv_category WHERE categoryuid = '$cat_uid'";
        $this->setter();

        if ($this->isqueryrun) {

            if (mysqli_num_rows($this->isqueryrun) == 0) {

                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => $cat_name . '(' . $cat_code . ') is Not Present'
                ];
                header("Location: ../pages/category/view-category");
                exit;
            } else {
                $this->query = "DELETE FROM inv_category WHERE categoryuid = '$cat_uid'";
                $this->setter();

                if ($this->isqueryrun) {
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => $cat_name . '(' . $cat_code . ') Deleted Successfully'
                    ];

                    header("Location: ../pages/category/view-category");
                    exit;
                } else {

                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Something went wrong'
                    ];

                    header("Location: ../pages/category/view-category");
                    exit;
                }
            }
        } else {

            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Something went wrong'
            ];

            header("Location: ../pages/category/view-category");
            exit;
        }
    }
    // delete categories start

    // ============================================================
    //                      Category Modue Ends
    // ============================================================

    // ============================================================
    //                      Product Module Starts
    // ============================================================

    // add Products start
    function add_Product()
    {
        $name = $_POST['product_name'];
        $sku = $_POST['product_sku'];
        $hsn = $_POST['product_hsn'];
        $cat = $_POST['product_category'];
        $price = $_POST['product_price'];
        $stock = $_POST['product_stock'];
        $minstock = $_POST['product_minstock'];
        $desc = $_POST['product_description'];
        $status = $_POST['product_status'];


        $this->query = "SELECT * FROM inv_products WHERE productname='$name' OR productsku='$sku'";
        $this->setter();

        if ($this->isqueryrun) {
            if (mysqli_num_rows($this->isqueryrun) > 0) {
                $_SESSION['message'] = ['type' => 'error', 'text' => $name . '(' . $sku . ') Already Present'];
                header("Location: ../pages/product/add-product"); // Using header for redirect
                exit;
            } else {

                $time = $this->getCurrentTime();

                $imgName = "";
                if (!empty($_FILES['product_image']['name'])) {
                    $tmp = $_FILES['product_image']['tmp_name'];
                    $fileName = time() . "_" . rand(1000, 9999) . ".jpg";
                    $path = "../assets/uploads/products/" . $fileName;
                    $imgName = $fileName;
                }

                $this->query = "INSERT INTO inv_products
                (categoryuid, productname, productsku, producthsn, productprice, productstock, productminstock, productdescription, productimage, productstatus, productcreated)
                VALUES ('$cat', '$name','$sku','$hsn','$price','$stock','$minstock','$desc','$imgName','$status','$time')";
                $this->setter();

                if ($this->isqueryrun) {
                    if (move_uploaded_file($tmp, $path)) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => $name . '(' . $sku . ') Added Successfully'];
                        header("Location: ../pages/product/view-product");
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Image Upload Error'];
                        header("Location: ../pages/product/add-product");
                        exit;
                    }
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Something went wrong'];
                    header("Location: ../pages/product/add-product");
                    exit;
                }
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Something went wrong'];
            header("Location: ../pages/product/add-product");
            exit;
        }
    }
    // add products end

    // fetch all products start
    function get_All_Products($status = "")
    {
        $query = "SELECT * FROM inv_products WHERE 1=1";

        if ($status === "1") {
            $query .= " AND productstatus = '1'";
        } else if ($status === "0") {
            $query .= " AND productstatus = '0'";
        }

        $query .= " ORDER BY productuid DESC";

        $this->query = $query;
        $this->setter();

        $data = [];
        while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
            $data[] = $row;
        }
        return $data;
    }
    // fetch all products ends

    // fetch single column of Product start
    function get_Single_Colum_Product($id, $colname)
    {
        $id = $this->decryptData($id);
        $this->query = "SELECT * FROM inv_products WHERE productuid='$id'";
        $this->setter();

        if (mysqli_num_rows($this->isqueryrun) > 0) {
            $row = mysqli_fetch_assoc($this->isqueryrun);
            return $row[$colname];
        }
    }
    // fetch single column of Product end

    // update Products start
    function update_Product()
    {
        $enc_id = $_POST['product_id'];
        $product_uid = $this->decryptData($enc_id);

        $name = $_POST['product_name'];
        $sku = $_POST['product_sku'];
        $hsn = $_POST['product_hsn'];
        $cat = $_POST['product_category'];
        $price = $_POST['product_price'];
        $stock = $_POST['product_stock'];
        $minstock = $_POST['product_minstock'];
        $desc = $_POST['product_description'];
        $status = $_POST['product_status'];

        $this->query = "SELECT * FROM inv_products WHERE productuid = '$product_uid'";
        $this->setter();

        $currentTime = $this->getCurrentTime();

        if ($this->isqueryrun) {

            if (mysqli_num_rows($this->isqueryrun) == 0) {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => $name . '(' . $sku . ') is Not Present'
                ];
                header("Location: ../pages/product/edit-product?id=" . urlencode($enc_id));
                exit;
            } else {

                $this->query = "SELECT * FROM inv_products WHERE productuid != '$product_uid'";
                $this->setter();

                if (mysqli_num_rows($this->isqueryrun) > 0) {
                    while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                        if ($row['productsku'] === $sku) {
                            $_SESSION['message'] = [
                                'type' => 'error',
                                'text' => $sku . ' is Already Present Try Different SKU Code'
                            ];

                            header("Location: ../pages/product/edit-product?id=" . urlencode($enc_id));
                            exit;
                        }
                    }
                }

                $existingImage = $this->get_Single_Colum_Product($enc_id, 'productimage');
                $finalImage = $existingImage;

                if (!empty($_FILES['product_image']['name'])) {
                    $tmp = $_FILES['product_image']['tmp_name'];
                    $fileName = time() . "_" . rand(1000, 9999) . ".jpg";
                    $path = "../assets/uploads/products/" . $fileName;
                    $finalImage = $fileName;
                }

                $this->query = "UPDATE inv_products SET
                categoryuid = '$cat',
                productname = '$name',
                productsku = '$sku',
                producthsn = '$hsn',
                productprice = '$price',
                productstock = '$stock',
                productminstock = '$minstock',
                productdescription = '$desc',
                productimage = '$finalImage',
                productstatus = '$status',
                productupdated = '$currentTime'
                WHERE productuid = '$product_uid'";

                $this->setter();

                if ($this->isqueryrun) {
                    if (!empty($_FILES['product_image']['name'])) {
                        if (move_uploaded_file($tmp, $path)) {
                            $_SESSION['message'] = [
                                'type' => 'success',
                                'text' => $name . '(' . $sku . ') Updated Successfully'
                            ];
                            header("Location: ../pages/product/view-product");
                        } else {
                            $_SESSION['message'] = [
                                'type' => 'error',
                                'text' => 'Image Upload Error'
                            ];
                            header("Location: ../pages/product/edit-product?id=" . urlencode($enc_id));
                            exit;
                        }
                    }
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => $name . '(' . $sku . ') Updated Successfully'
                    ];
                    header("Location: ../pages/product/view-product");
                } else {
                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Something went wrong'
                    ];
                    header("Location: ../pages/product/edit-product?id=" . urlencode($enc_id));
                    exit;
                }
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Something went wrong'
            ];
            header("Location: ../pages/product/edit-product?id=" . urlencode($enc_id));
            exit;
        }
    }
    // update Products end

    // delete product start
    function delete_Product()
    {
        $enc_id = $_GET['prod_id'];
        $product_uid = $this->decryptData($enc_id);
        $product_name = $this->get_Single_Colum_Product($enc_id, 'productname');
        $product_sku = $this->get_Single_Colum_Product($enc_id, 'productsku');

        $this->query = "SELECT * FROM inv_products WHERE productuid = '$product_uid'";
        $this->setter();

        if ($this->isqueryrun) {

            if (mysqli_num_rows($this->isqueryrun) == 0) {

                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => $product_name . '(' . $product_sku . ') is Not Present'
                ];
                header("Location: ../pages/product/view-product");
                exit;
            } else {
                $this->query = "DELETE FROM inv_products WHERE productuid = '$product_uid'";
                $this->setter();

                if ($this->isqueryrun) {
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => $product_name . '(' . $product_sku . ') Deleted Successfully'
                    ];

                    header("Location: ../pages/product/view-product");
                    exit;
                } else {

                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Something went wrong'
                    ];

                    header("Location: ../pages/product/view-product");
                    exit;
                }
            }
        } else {

            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Something went wrong'
            ];

            header("Location: ../pages/product/view-product");
            exit;
        }
    }
    // delete categories start

    // fetch filter data of Product start
    function get_All_Filter_Products($category, $status)
    {
        $query = "SELECT * FROM inv_products WHERE 1 = 1";
        if (!empty($category)) {
            $query .= " AND categoryuid = '$category'";
        }
        if ($status !== "") {
            $query .= " AND productstatus = '$status'";
        }
        $this->query = $query;
        $this->setter();
        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    // fetch filter data of Product end

    // ============================================================
    //                      Product Module Ends
    // ============================================================

    // ============================================================
    //                      Stock In Module Starts
    // ============================================================

    // add stock in starts
    function add_Stock_In()
    {
        $enc_prod_id = $_POST['product_id'];
        $qty = $_POST['quantity'];
        $date = $_POST['purchase_date'];
        $supplier = $_POST['supplier'];
        $remarks = $_POST['remarks'];

        $prod_id = $this->decryptData($enc_prod_id);

        $this->query = "SELECT * FROM inv_products WHERE productuid='$prod_id' LIMIT 1";
        $this->setter();

        if (mysqli_num_rows($this->isqueryrun) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid Product Selected'];
            header("Location: ../pages/stock/stock-in");
            exit;
        }

        $previous_stock = $this->get_Single_Colum_Product($enc_prod_id, 'productstock');
        $new_stock = $previous_stock + $qty;

        $time = $this->getCurrentTime();

        $this->query = "INSERT INTO inv_stockin 
        (productuid, previousstock, addedstock, newstock, supplier, remarks, purchasedate, stockintime)
        VALUES 
        ('$prod_id', '$previous_stock', '$qty', '$new_stock', '$supplier', '$remarks', '$date', '$time')";
        $this->setter();

        if (!$this->isqueryrun) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to Insert Stock Entry'];
            header("Location: ../pages/stock/stock-in");
            exit;
        }

        $this->query = "UPDATE inv_products SET productstock='$new_stock', productupdated='$time'
                    WHERE productuid='$prod_id'";
        $this->setter();

        if ($this->isqueryrun) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Stock Updated Successfully (Added $qty units)"
            ];
            header("Location: ../pages/stock/stock-in-history");
            exit;
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Stock Updated Failed'];
            header("Location: ../pages/stock/stock-in");
            exit;
        }
    }
    //add stock in ends

    // fetch filter data of Stock in start
    function get_All_StockIn_Filter($product, $supplier, $from, $to)
    {
        $query = "SELECT * FROM inv_stockin WHERE 1 = 1";

        if (!empty($product)) {
            $query .= " AND productuid = '$product'";
        }

        if (!empty($supplier)) {
            $query .= " AND supplier LIKE '%$supplier%'";
        }

        if (!empty($from)) {
            $query .= " AND purchasedate >= '$from'";
        }

        if (!empty($to)) {
            $query .= " AND purchasedate <= '$to'";
        }

        $query .= " ORDER BY purchasedate DESC, stockintime DESC";

        $this->query = $query;
        $this->setter();

        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    // fetch filter data of Stock in ends

    //fetch all suppliers from stockin starts
    function get_All_Suppliers_StockIn()
    {
        $this->query = "SELECT DISTINCT supplier FROM inv_stockin WHERE supplier IS NOT NULL AND supplier != ''";
        $this->setter();

        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    //fetch all suppliers from stockin ends



    // ============================================================
    //                      Stock In Module Ends
    // ============================================================

    // ============================================================
    //                     STOCK OUT MODULE STARTS
    // ============================================================

    //add stock out starts
    function add_Stock_Out()
    {
        $enc_prod_id = $_POST['product_id'];
        $qty = $_POST['quantity'];
        $date = $_POST['used_date'];
        $customer = $_POST['customer'];
        $remarks = $_POST['remarks'];

        $prod_id = $this->decryptData($enc_prod_id);

        $this->query = "SELECT * FROM inv_products WHERE productuid='$prod_id' LIMIT 1";
        $this->setter();

        if (mysqli_num_rows($this->isqueryrun) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid Product Selected'];
            header("Location: ../pages/stock/stock-out");
            exit;
        }
        $previous_stock = $this->get_Single_Colum_Product($enc_prod_id, 'productstock');

        if ($qty > $previous_stock) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Insufficient Stock!'];
            header("Location: ../pages/stock/stock-out");
            exit;
        }

        $new_stock = $previous_stock - $qty;

        $time = $this->getCurrentTime();

        $this->query = "INSERT INTO inv_stockout
        (productuid, previousstock, removedstock, newstock, customer, remarks, useddate, stockouttime)
        VALUES
        ('$prod_id', '$previous_stock', '$qty', '$new_stock', '$customer', '$remarks', '$date', '$time')";
        $this->setter();

        if (!$this->isqueryrun) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to Insert Stock OUT'];
            header("Location: ../pages/stock/stock-out");
            exit;
        }

        $this->query = "UPDATE inv_products SET productstock='$new_stock', productupdated='$time' WHERE productuid='$prod_id'";
        $this->setter();

        if ($this->isqueryrun) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Stock Updated Successfully (Removed $qty units)"
            ];
            header("Location: ../pages/stock/stock-out-history");
            exit;
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Stock Update Failed'];
            header("Location: ../pages/stock/stock-out");
            exit;
        }
    }
    //add stock out ends

    // fetch filter data of Stock out start
    function get_All_StockOut_Filter($product, $customer, $from, $to)
    {
        $query = "SELECT * FROM inv_stockout WHERE 1 = 1";

        if (!empty($product)) {
            $query .= " AND productuid = '$product'";
        }

        if (!empty($customer)) {
            $query .= " AND customer LIKE '%$customer%'";
        }

        if (!empty($from)) {
            $query .= " AND useddate >= '$from'";
        }

        if (!empty($to)) {
            $query .= " AND useddate <= '$to'";
        }

        $query .= " ORDER BY useddate DESC, stockouttime DESC";

        $this->query = $query;
        $this->setter();

        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    // fetch filter data of Stock out ends

    //fetch all customer from stockout starts
    function get_All_Customers_StockOut()
    {
        $this->query = "SELECT DISTINCT customer 
                    FROM inv_stockout 
                    WHERE customer IS NOT NULL AND customer != ''";
        $this->setter();

        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    //fetch all customer from stockout ends


    // ============================================================
    //                     STOCK OUT MODULE ENDS
    // ============================================================


    // ============================================================
    //                     STOCK LEDGER MODULE STARTS
    // ============================================================

    // fetch filtered stock ledger start
    function get_Stock_Ledger_Filter($product, $movement, $from, $to)
    {
        $ledger = [];

        if ($movement == "" || $movement == "IN") {

            $query = "SELECT 
                    'IN' AS movement,
                    si.productuid,
                    si.previousstock,
                    si.addedstock AS qty,
                    si.newstock,
                    si.supplier AS party,
                    si.remarks,
                    si.purchasedate AS movementdate,
                    si.stockintime AS movementtime
                FROM inv_stockin si
                WHERE 1=1";

            if (!empty($product)) {
                $query .= " AND si.productuid='$product'";
            }
            if (!empty($from)) {
                $query .= " AND si.purchasedate >= '$from'";
            }
            if (!empty($to)) {
                $query .= " AND si.purchasedate <= '$to'";
            }

            $query .= " ORDER BY si.stockintime DESC";

            $this->query = $query;
            $this->setter();

            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $ledger[] = $row;
            }
        }

        if ($movement == "" || $movement == "OUT") {

            $query = "SELECT 
                    'OUT' AS movement,
                    so.productuid,
                    so.previousstock,
                    so.removedstock AS qty,
                    so.newstock,
                    so.customer AS party,
                    so.remarks,
                    so.useddate AS movementdate,
                    so.stockouttime AS movementtime
                FROM inv_stockout so
                WHERE 1=1";

            if (!empty($product)) {
                $query .= " AND so.productuid='$product'";
            }
            if (!empty($from)) {
                $query .= " AND so.useddate >= '$from'";
            }
            if (!empty($to)) {
                $query .= " AND so.useddate <= '$to'";
            }

            $query .= " ORDER BY so.stockouttime DESC";

            $this->query = $query;
            $this->setter();

            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $ledger[] = $row;
            }
        }

        usort($ledger, function ($a, $b) {
            return strtotime($b['movementtime']) - strtotime($a['movementtime']);
        });

        return $ledger;
    }
    // fetch filtered stock ledger end

    // ============================================================
    //                     STOCK LEDGER MODULE ENDS
    // ============================================================


    // ============================================================
    //                     LOW STOCK MODULE STARTS
    // ============================================================

    // fetch low stock start
    function get_Low_Stock_Products()
    {
        $this->query = "SELECT * FROM inv_products WHERE productstatus = 1 AND productstock < productminstock";
        $this->setter();

        $data = [];
        if (mysqli_num_rows($this->isqueryrun) > 0) {
            while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    // fetch low stock end

    // ============================================================
    //                     LOW STOCK MODULE ENDS
    // ============================================================


    // ============================================================
    //                     DASHBOARD STATS STARTS
    // ============================================================

    // fetch movement for dashboard start
    function get_Stock_Movements_This_Month()
    {
        $result = [
            "stock_in" => 0,
            "stock_out" => 0
        ];

        $this->query = "SELECT SUM(addedstock) AS total_in FROM inv_stockin
                        WHERE MONTH(purchasedate) = MONTH(CURRENT_DATE())
                        AND YEAR(purchasedate) = YEAR(CURRENT_DATE())";
        $this->setter();
        $in = mysqli_fetch_assoc($this->isqueryrun);
        $result['stock_in'] = $in['total_in'] ?? 0;

        $this->query = "SELECT SUM(removedstock) AS total_out FROM inv_stockout
                        WHERE MONTH(useddate) = MONTH(CURRENT_DATE())
                        AND YEAR(useddate) = YEAR(CURRENT_DATE())";
        $this->setter();
        $out = mysqli_fetch_assoc($this->isqueryrun);
        $result['stock_out'] = $out['total_out'] ?? 0;

        return $result;
    }

    function get_StockIn_Monthly()
    {
        $this->query = "SELECT DATE_FORMAT(purchasedate, '%b') AS month, SUM(addedstock) AS total_in
                        FROM inv_stockin
                        WHERE YEAR(purchasedate) = YEAR(CURDATE())
                        GROUP BY MONTH(purchasedate)
                        ORDER BY MONTH(purchasedate)";
        $this->setter();

        $result = [];
        while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
            $result[] = $row;
        }
        return $result;
    }

    function get_StockOut_Monthly()
    {
        $this->query = "SELECT DATE_FORMAT(useddate, '%b') AS month, SUM(removedstock) AS total_out
                        FROM inv_stockout
                        WHERE YEAR(useddate) = YEAR(CURDATE())
                        GROUP BY MONTH(useddate)
                        ORDER BY MONTH(useddate)";
        $this->setter();

        $result = [];
        while ($row = mysqli_fetch_assoc($this->isqueryrun)) {
            $result[] = $row;
        }
        return $result;
    }
    // fetch movement for dashboard end

    // ============================================================
    //                     DASHBOARD STATS ENDS
    // ============================================================

    // ============================================================
    //                     REPORT MODULE STARTS
    // ============================================================

    // current stock For CSV export start
    public function export_Current_Stock_To_CSV()
    {
        if (!isset($_POST['prod_data'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No Data Found'];
            header("Location: ../pages/product/view-product");
            exit;
        }

        $prodData = json_decode(htmlspecialchars_decode($_POST['prod_data']), true);

        if (!is_array($prodData) || count($prodData) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid or empty product data'];
            header("Location: ../pages/product/view-product");
            exit;
        }

        $filename = "current_stock_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');

        fputcsv($out, [
            'Sr',
            'Product Name',
            'SKU',
            'Category',
            'HSN',
            'Price',
            'Current Stock',
            'Min Stock',
            'Discription',
            'Status'
        ]);

        $count = 1;

        foreach ($prodData as $p) {
            $catName = $this->get_Single_Colum_Categories(
                $this->encryptData($p['categoryuid']),
                'categoryname'
            );

            $statusLabel = ($p['productstatus'] == 1) ? 'Active' : 'Inactive';

            fputcsv($out, [
                $count++,
                $p['productname'],
                $p['productsku'],
                $catName,
                $p['producthsn'],
                $p['productprice'],
                $p['productstock'],
                $p['productminstock'],
                $p['productdescription'],
                $statusLabel
            ]);
        }

        fclose($out);
        exit;
    }
    // current stock For CSV export start

    // stock in For CSV export start
    public function export_Stock_In_To_CSV()
    {
        if (!isset($_POST['stock_in_data'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No Stock IN Data Found'];
            header("Location: ../pages/stock/stock-in-history");
            exit;
        }

        $data = json_decode(htmlspecialchars_decode($_POST['stock_in_data']), true);

        if (!is_array($data) || count($data) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid Stock IN Data'];
            header("Location: ../pages/stock/stock-in-history");
            exit;
        }

        $filename = "stock_in_" . date('Ymd_His') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename");

        $out = fopen("php://output", "w");

        fputcsv($out, [
            "Sr",
            "Product",
            "Added Qty",
            "Previous Stock",
            "New Stock",
            "Supplier",
            "Purchased Date",
            "Remarks"
        ]);

        $count = 1;
        foreach ($data as $r) {
            fputcsv($out, [
                $count++,
                $this->get_Single_Colum_Product($this->encryptData($r['productuid']), 'productname'),
                $r['addedstock'],
                $r['previousstock'],
                $r['newstock'],
                $r['supplier'],
                $r['purchasedate'],
                $r['remarks']
            ]);
        }

        fclose($out);
        exit;
    }
    // stock out For CSV export ends

    // stock out For CSV export start
    public function export_Stock_Out_To_CSV()
    {
        if (!isset($_POST['stock_out_data'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No Stock OUT Data Found'];
            header("Location: ../pages/stock/stock-out-history");
            exit;
        }

        $data = json_decode(htmlspecialchars_decode($_POST['stock_out_data']), true);

        if (!is_array($data) || count($data) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid Stock OUT Data'];
            header("Location: ../pages/stock/stock-out-history");
            exit;
        }

        $filename = "stock_out_" . date('Ymd_His') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename");

        $out = fopen("php://output", "w");

        fputcsv($out, [
            "Sr",
            "Product",
            "Removed Qty",
            "Previous Stock",
            "New Stock",
            "Issued To",
            "Used Date",
            "Remarks"
        ]);

        $count = 1;
        foreach ($data as $r) {
            fputcsv($out, [
                $count++,
                $this->get_Single_Colum_Product($this->encryptData($r['productuid']), 'productname'),
                $r['removedstock'],
                $r['previousstock'],
                $r['newstock'],
                $r['customer'],
                $r['useddate'],
                $r['remarks']
            ]);
        }

        fclose($out);
        exit;
    }
    // stock out For CSV export ends

    // stock ledger For CSV export starts
    public function export_Stock_Ledger_To_CSV()
    {
        if (!isset($_POST['ledger_data'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No Ledger Data Found'];
            header("Location: ../pages/stock/stock-ledger");
            exit;
        }

        $data = json_decode(htmlspecialchars_decode($_POST['ledger_data']), true);

        if (!is_array($data) || count($data) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid Ledger Data'];
            header("Location: ../pages/stock/stock-ledger");
            exit;
        }

        $filename = "stock_ledger_" . date('Ymd_His') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=$filename");

        $out = fopen("php://output", "w");

        fputcsv($out, [
            "Sr",
            "Movement",
            "Product",
            "Qty",
            "Previous Stock",
            "New Stock",
            "Party",
            "Date",
            "Time",
            "Remarks"
        ]);

        $count = 1;

        foreach ($data as $r) {
            $movement = $r['movement'];            // IN / OUT
            $qty      = $r['qty'];                 // addedstock OR removedstock
            $product  = $this->get_Single_Colum_Product($this->encryptData($r['productuid']), 'productname');
            fputcsv($out, [
                $count++,
                $movement,
                $product,
                $qty,
                $r['previousstock'],
                $r['newstock'],
                $r['party'],
                $r['movementdate'],
                $r['movementtime'],
                $r['remarks']
            ]);
        }

        fclose($out);
        exit;
    }
    // stock ledger For CSV export ends

    // low stock For CSV export starts
    public function export_Low_Stock_To_CSV()
    {
        if (!isset($_POST['low_stock_data'])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'No Low Stock Data Found'];
            header("Location: ../pages/stock/low-stock");
            exit;
        }

        $lowStockData = json_decode(htmlspecialchars_decode($_POST['low_stock_data']), true);

        if (!is_array($lowStockData) || count($lowStockData) == 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid or empty data'];
            header("Location: ../pages/stock/low-stock");
            exit;
        }

        // File name
        $filename = "low_stock_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');

        // CSV Header
        fputcsv($out, [
            'Sr',
            'Product Name',
            'SKU',
            'Category',
            'HSN',
            'Price',
            'Current Stock',
            'Min Stock',
            'Status'
        ]);

        $count = 1;

        foreach ($lowStockData as $p) {

            // Get Category Name
            $catName = $this->get_Single_Colum_Categories(
                $this->encryptData($p['categoryuid']),
                'categoryname'
            );

            $statusLabel = ($p['productstatus'] == 1) ? "Active" : "Inactive";

            fputcsv($out, [
                $count++,
                $p['productname'],
                $p['productsku'],
                $catName,
                $p['producthsn'],
                $p['productprice'],
                $p['productstock'],
                $p['productminstock'],
                $statusLabel
            ]);
        }

        fclose($out);
        exit;
    }

    // low stock For CSV export ends


    // ============================================================
    //                     REPORT MODULE ENDS
    // ============================================================
}

$invObj = new invFront();
