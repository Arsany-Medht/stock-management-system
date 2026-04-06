<?php if (!isset($pageTitle)) $pageTitle = "Stock Management System"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Stock Management System</h1>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="categories.php">Categories</a>
        <a href="suppliers.php">Suppliers</a>
        <a href="warehouses.php">Warehouses</a>
        <a href="purchase.php">Purchase</a>
        <a href="sale.php">Sale</a>
        <a href="transactions.php">Transactions</a>
        <a href="reports.php">Reports</a>
    </nav>