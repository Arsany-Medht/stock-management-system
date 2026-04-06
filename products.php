<?php
include 'db.php';
$pageTitle = "Products";
include 'header.php';

$sql = "SELECT p.*, c.category_name, w.warehouse_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN warehouses w ON p.warehouse_id = w.warehouse_id
        ORDER BY p.product_id DESC";
$result = $conn->query($sql);
?>

<h2>Products</h2>
<a href="add_product.php" class="btn btn-success">Add Product</a>
<br><br>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Warehouse</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($row['warehouse_name'] ?? 'N/A') ?></td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>
                <?php if ($row['quantity'] == 0): ?>
                    <span class="badge badge-danger">Out of Stock</span>
                <?php elseif ($row['quantity'] < 5): ?>
                    <span class="badge badge-warning">Low Stock</span>
                <?php else: ?>
                    <span class="badge badge-success">In Stock</span>
                <?php endif; ?>
            </td>
            <td>
                <div class="actions">
                    <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>