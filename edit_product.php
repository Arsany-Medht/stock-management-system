<?php
include 'db.php';
$pageTitle = "Edit Product";
$message = "";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;
    $warehouse_id = !empty($_POST['warehouse_id']) ? $_POST['warehouse_id'] : NULL;
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, warehouse_id=?, price=?, quantity=? WHERE product_id=?");
    $stmt->bind_param("siidii", $name, $category_id, $warehouse_id, $price, $quantity, $id);
    $stmt->execute();

    $message = "Product updated successfully.";

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}

$categories = $conn->query("SELECT * FROM categories");
$warehouses = $conn->query("SELECT * FROM warehouses");

include 'header.php';
?>

<h2>Edit Product</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <label>Product Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Category</label>
    <select name="category_id">
        <option value="">Select Category</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['category_id'] ?>" <?= ($product['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['category_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Warehouse</label>
    <select name="warehouse_id">
        <option value="">Select Warehouse</option>
        <?php while($wh = $warehouses->fetch_assoc()): ?>
            <option value="<?= $wh['warehouse_id'] ?>" <?= ($product['warehouse_id'] == $wh['warehouse_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($wh['warehouse_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Quantity</label>
    <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

    <button type="submit" class="btn">Update Product</button>
</form>

<?php include 'footer.php'; ?>