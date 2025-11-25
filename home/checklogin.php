<?php
require('./../classes/invclass.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $invObj->login_handle();
}
