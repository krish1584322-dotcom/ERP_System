<?php
require_once __DIR__ . '/../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    exit("Customer not found.");
}

$districts = $conn->query(
    "SELECT district_id, district_name FROM district ORDER BY district_name"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h3 class="mb-0">Edit Customer</h3>
        </div>

        <div class="card-body">
            <form action="update_customer.php" method="POST">
                <input type="hidden" name="customer_id"
                       value="<?= $customer['customer_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <select name="title" class="form-select" required>
                        <?php foreach (['Mr', 'Mrs', 'Miss', 'Dr'] as $title): ?>
                            <option value="<?= $title ?>"
                                <?= $customer['title'] === $title ? 'selected' : '' ?>>
                                <?= $title ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control"
                           value="<?= htmlspecialchars($customer['first_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control"
                           value="<?= htmlspecialchars($customer['last_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control"
                           value="<?= htmlspecialchars($customer['contact_number']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">District</label>
                    <select name="district_id" class="form-select" required>
                        <?php while ($district = $districts->fetch_assoc()): ?>
                            <option value="<?= $district['district_id'] ?>"
                                <?= $customer['district_id'] == $district['district_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($district['district_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Update Customer</button>
                <a href="view_customer.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>