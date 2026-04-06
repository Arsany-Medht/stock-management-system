<?php
include 'db.php';
$pageTitle = "Reports";
include 'header.php';

$stock = $conn->query("SELECT * FROM stock_view");
$sales = $conn->query("SELECT * FROM sales_report");
$purchases = $conn->query("SELECT * FROM purchase_report");
?>

<h2>Stock Report</h2>
<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Category</th>
            <th>Warehouse</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Value</th>
        </tr>
        <?php while($row = $stock->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['total_stock_value'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<h2>Sales Report</h2>
<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Total Sold</th>
            <th>Total Sales Amount</th>
        </tr>
        <?php while($row = $sales->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['total_sold'] ?></td>
            <td><?= number_format($row['total_sales_amount'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<h2>Purchase Report</h2>
<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Total Purchased</th>
            <th>Total Purchase Amount</th>
        </tr>
        <?php while($row = $purchases->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['total_purchased'] ?></td>
            <td><?= number_format($row['total_purchase_amount'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>