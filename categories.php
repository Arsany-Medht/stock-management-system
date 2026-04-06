<?php
include 'db.php';
$pageTitle = "Categories";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['category_name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();
    $message = "Category added successfully.";
}

$result = $conn->query("SELECT * FROM categories ORDER BY category_id DESC");
include 'header.php';
?>

<h2>Categories</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" class="btn">Add Category</button>
</form>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['category_id'] ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>