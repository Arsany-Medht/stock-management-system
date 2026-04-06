<?php
include 'db.php';
$pageTitle = "Add Sale";
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $product_id = $_POST['product_id'];
        $user_id = $_POST['user_id'];
        $quantity = $_POST['quantity'];
        $unit_price = $_POST['unit_price'];

        $stmt = $conn->prepare("INSERT INTO sales (product_id, user_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $product_id, $user_id, $quantity, $unit_price);
        $stmt->execute();

        $message = "Sale added successfully.";
    } catch (mysqli_sql_exception $e) {
        $error = $e->getMessage();
    }
}

$products = $conn->query("SELECT product_id, name, quantity FROM products");
$users = $conn->query("SELECT user_id, full_name FROM users");

include 'header.php';
?>

<h2>Add Sale</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="message error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product</label>
    <select name="product_id" required>
        <option value="">Select Product</option>
        <?php while($row = $products->fetch_assoc()): ?>
            <option value="<?= $row['product_id'] ?>">
                <?= htmlspecialchars($row['name']) ?> (Stock: <?= $row['quantity'] ?>)
            </option>
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

    <label>Unit Price</label>
    <input type="number" step="0.01" name="unit_price" required>

    <button type="submit" class="btn">Add Sale</button>
</form>

<?php include 'footer.php'; ?>