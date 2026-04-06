<?php
include 'db.php';
$pageTitle = "Add Purchase";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $supplier_id = $_POST['supplier_id'];
    $user_id = $_POST['user_id'];
    $quantity = $_POST['quantity'];
    $unit_cost = $_POST['unit_cost'];

    $stmt = $conn->prepare("INSERT INTO purchases (product_id, supplier_id, user_id, quantity, unit_cost) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiid", $product_id, $supplier_id, $user_id, $quantity, $unit_cost);
    $stmt->execute();

    $message = "Purchase added successfully.";
}

$products = $conn->query("SELECT product_id, name FROM products");
$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers");
$users = $conn->query("SELECT user_id, full_name FROM users");

include 'header.php';
?>

<h2>Add Purchase</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product</label>
    <select name="product_id" required>
        <option value="">Select Product</option>
        <?php while($row = $products->fetch_assoc()): ?>
            <option value="<?= $row['product_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Supplier</label>
    <select name="supplier_id" required>
        <option value="">Select Supplier</option>
        <?php while($row = $suppliers->fetch_assoc()): ?>
            <option value="<?= $row['supplier_id'] ?>"><?= htmlspecialchars($row['supplier_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>User</label>
    <select name="user_id" required>
        <option value="">Select User</option>
        <?php while($row = $users->fetch_assoc()): ?>
            <option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Quantity</label>
    <input type="number" name="quantity" required>

    <label>Unit Cost</label>
    <input type="number" step="0.01" name="unit_cost" required>

    <button type="submit" class="btn">Add Purchase</button>
</form>

<?php include 'footer.php'; ?>