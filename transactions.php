<?php
include 'db.php';
$pageTitle = "Transactions";
include 'header.php';

$sql = "SELECT st.*, p.name AS product_name, u.full_name AS user_name
        FROM stock_transactions st
        JOIN products p ON st.product_id = p.product_id
        JOIN users u ON st.user_id = u.user_id
        ORDER BY st.transaction_date DESC";
$result = $conn->query($sql);
?>

<h2>Stock Transactions</h2>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>User</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Notes</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['transaction_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['transaction_type']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['transaction_date'] ?></td>
            <td><?= htmlspecialchars($row['notes']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>