<?php
//session_start();
if (isset($_SESSION['admin_login_token'])) {
    $invObj->isloggedinuser();
} else {
    session_destroy();
    echo "<script> window.location.replace('../home/login'); </script> ";
    // echo "No token found";
}
