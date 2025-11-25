<?php
require('./../classes/invclass.php');

// Category Module Starts

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cat_btn'])) {
    $invObj->add_Category();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cat_btn'])) {
    $invObj->update_Category();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type']) && $_GET['type'] === 'Category' && isset($_GET['cat_id'])) {
    $invObj->delete_Category();
}

// Category Module Ends


// Product Module Starts

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product_btn'])) {
    $invObj->add_Product();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product_btn'])) {
    $invObj->update_Product();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type']) && $_GET['type'] === 'Product' && isset($_GET['prod_id'])) {
    $invObj->delete_Product();
}

// Product Module Ends

// Stock In Module Starts

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stock_in_btn'])) {
    $invObj->add_Stock_In();
}

// stock in module ends

// stock out module starts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stock_out_btn'])) {
    $invObj->add_Stock_Out();
}
// stock out module ends

// report export module starts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv_product'])) {
    $invObj->export_Current_Stock_To_CSV();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv_stock_in'])) {
    $invObj->export_Stock_In_To_CSV();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv_stock_out'])) {
    $invObj->export_Stock_Out_To_CSV();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv_stock_ledger'])) {
    $invObj->export_Stock_Ledger_To_CSV();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv_low_stock'])) {
    $invObj->export_Low_Stock_To_CSV();
}

// report export module ends
