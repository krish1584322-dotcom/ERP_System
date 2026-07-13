<?php
require_once __DIR__ . '/../config/database.php';

$sql = "SELECT
            i.item_name,
            c.category_name,
            s.subcategory_name,
            SUM(i.quantity) AS item_quantity
        FROM item i
        INNER JOIN item_category c ON i.category_id = c.category_id
        INNER JOIN item_subcategory s ON i.subcategory_id = s.subcategory_id
        GROUP BY
            i.item_name,
            c.category_name,
            s.subcategory_name
        ORDER BY i.item_name";

$items = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-3">Item Report</h3>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Item Name</th>
                        <th>Item Category</th>
                        <th>Item Subcategory</th>
                        <th>Item Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= htmlspecialchars($item['category_name']) ?></td>
                            <td><?= htmlspecialchars($item['subcategory_name']) ?></td>
                            <td><?= $item['item_quantity'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>