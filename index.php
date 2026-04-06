<?php
include 'db.php';
$pageTitle = "Dashboard";
include 'header.php';

$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$totalSuppliers = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()['total'];
$totalWarehouses = $conn->query("SELECT COUNT(*) AS total FROM warehouses")->fetch_assoc()['total'];
$totalCategories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$totalStockValue = $conn->query("SELECT SUM(price * quantity) AS total FROM products")->fetch_assoc()['total'];
$lowStock = $conn->query("SELECT COUNT(*) AS total FROM products WHERE quantity < 5")->fetch_assoc()['total'];
?>

<div class="card-grid">
    <div class="card">
        <h3>Total Products</h3>
        <p><?= $totalProducts ?></p>
    </div>
    <div class="card">
        <h3>Total Suppliers</h3>
        <p><?= $totalSuppliers ?></p>
    </div>
    <div class="card">
        <h3>Total Warehouses</h3>
        <p><?= $totalWarehouses ?></p>
    </div>
    <div class="card">
        <h3>Total Categories</h3>
        <p><?= $totalCategories ?></p>
    </div>
    <div class="card">
        <h3>Total Stock Value</h3>
        <p><?= number_format($totalStockValue ?: 0, 2) ?></p>
    </div>
    <div class="card">
        <h3>Low Stock Products</h3>
        <p><?= $lowStock ?></p>
    </div>
</div>

<?php include 'footer.php'; ?>