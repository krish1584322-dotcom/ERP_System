<?php
require_once __DIR__ . '/../config/database.php';

$sql = "SELECT
            c.customer_id,
            c.title,
            c.first_name,
            c.last_name,
            c.contact_number,
            d.district_name
        FROM customer c
        INNER JOIN district d ON c.district_id = d.district_id
        ORDER BY c.customer_id DESC";

$customers = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h3>Customer List</h3>
            <a href="add_customer.php" class="btn btn-primary">Add Customer</a>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact Number</th>
                            <th>District</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($customer = $customers->fetch_assoc()): ?>
                            <tr>
                                <td><?= $customer['customer_id'] ?></td>
                                <td><?= htmlspecialchars($customer['title']) ?></td>
                                <td><?= htmlspecialchars($customer['first_name']) ?></td>
                                <td><?= htmlspecialchars($customer['last_name']) ?></td>
                                <td><?= htmlspecialchars($customer['contact_number']) ?></td>
                                <td><?= htmlspecialchars($customer['district_name']) ?></td>
                                <td>
                                    <a href="edit_customer.php?id=<?= $customer['customer_id'] ?>"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <a href="delete_customer.php?id=<?= $customer['customer_id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this customer?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>