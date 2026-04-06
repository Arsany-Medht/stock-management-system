<?php
include 'db.php';
$pageTitle = "Suppliers";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['supplier_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, phone, email, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $address);
    $stmt->execute();
    $message = "Supplier added successfully.";
}

$result = $conn->query("SELECT * FROM suppliers ORDER BY supplier_id DESC");
include 'header.php';
?>

<h2>Suppliers</h2>

<?php if ($message): ?>
    <div class="message success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="supplier_name" placeholder="Supplier Name" required>
    <input type="text" name="phone" placeholder="Phone">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="address" placeholder="Address">
    <button type="submit" class="btn">Add Supplier</button>
</form>

<div class="table-container">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['supplier_id'] ?></td>
            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>