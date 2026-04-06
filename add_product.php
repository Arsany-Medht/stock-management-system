<?php
include 'db.php';
$pageTitle = "Add Product";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;
    $warehouse_id = !empty($_POST['warehouse_id']) ? $_POST['warehouse_id'] : NULL;
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO products (name, category_id, warehouse_id, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siidi", $name, $category_id, $warehouse_id, $price, $quantity);
    $stmt->execute();

    $message = "Product added successfully.";
}

$categories = $conn->query("SELECT * FROM categories");
$warehouses = $conn->query("SELECT * FROM warehouses");

include 'header.php';
?>

<h2>Add Product</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product Name</label>
    <input type="text" name="name" required>

    <label>Category</label>
    <select name="category_id">
        <option value="">Select Category</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Warehouse</label>
    <select name="warehouse_id">
        <option value="">Select Warehouse</option>
        <?php while($wh = $warehouses->fetch_assoc()): ?>
            <option value="<?= $wh['warehouse_id'] ?>"><?= htmlspecialchars($wh['warehouse_name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Quantity</label>
    <input type="number" name="quantity" required>

    <button type="submit" class="btn">Add Product</button>
</form>

<?php include 'footer.php'; ?>