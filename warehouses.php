<?php
include 'db.php';
$pageTitle = "Warehouses";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['warehouse_name'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO warehouses (warehouse_name, location) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $location);
    $stmt->execute();
    $message = "Warehouse added successfully.";
}

$result = $conn->query("SELECT * FROM warehouses ORDER BY warehouse_id DESC");
include 'header.php';
?>

<h2>Warehouses</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="warehouse_name" placeholder="Warehouse Name" required>
    <input type="text" name="location" placeholder="Location" required>
    <button type="submit" class="btn">Add Warehouse</button>
</form>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['warehouse_id'] ?></td>
            <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>